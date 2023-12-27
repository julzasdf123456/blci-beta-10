<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PaidBills;
use App\Models\PaidBillsDetails;
use App\Models\ServiceAccounts;
use App\Models\IDGenerator;
use App\Models\Bills;
use App\Models\BillMirror;
use App\Models\ThirdPartyTokens;
use App\Models\Users;
use App\Models\ArrearsLedgerDistribution;
use App\Models\DCRSummaryTransactions;
use App\Models\TransactionIndex;
use App\Models\TransactionDetails;
use App\Models\Tickets;
use App\Models\TicketLogs;
use App\Http\Requests\CreateReadingsRequest;

class ThirdPartyAPI extends Controller {
    /**
     * Fetch all unpaid bills by account number
     */
    public function getUnpaidBillsByAccountNumber(Request $request) {
        $token = $request['_token'];
        $accountNumber = $request['AccountNumber'];

        if ($token != null) {
            // VALIDATE TOKEN
            if (ThirdPartyAPI::isTokenValid($token)) {
                // VALIDATE ACCOUNT NUMBER
                if ($accountNumber != null) {
                    // GET ACCOUNT DETAILS
                    $serviceAccount = ServiceAccounts::where('OldAccountNo', $accountNumber)->first();

                    if ($serviceAccount != null) {
                        // GET BILLS
                        if ($serviceAccount->DownloadedByDisco == 'Yes') {
                            return response()->json("This account is due for disconnection. A disconnector is on its way to disconnect this consumer.", 403);
                        } else {
                            // $bills = DB::table('Billing_Bills')
                            //     ->whereRaw("AccountNumber='" . $serviceAccount->id . "' AND AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=Billing_Bills.AccountNumber AND ServicePeriod=Billing_Bills.ServicePeriod AND (Status IS NULL OR Status='Application'))")
                            //     ->select('Billing_Bills.*')
                            //     ->orderByDesc('Billing_Bills.ServicePeriod')
                            //     ->get();
                            $bills = DB::table('Billing_Bills')
                                ->whereRaw("AccountNumber='" . $serviceAccount->id . "' AND Balance > 0")
                                ->select('Billing_Bills.*')
                                ->orderByDesc('Billing_Bills.ServicePeriod')
                                ->get();

                            $resData = [];

                            // ADD ACCOUNT DETAILS FIRST
                            $resData['CustomerName'] = trim($serviceAccount->ServiceAccountName);
                            $resData['AccountNumber'] = $serviceAccount->OldAccountNo;
                            $resData['AccountID'] = $serviceAccount->id;
                            $resData['AccountStatus'] = $serviceAccount->AccountStatus;
                            $resData['AccountType'] = $serviceAccount->AccountType;

                            if ($serviceAccount->AccountStatus == 'DISCONNECTED') {
                                array_push($resData, [
                                    'Payable' => 'Reconnection Fee',
                                    'TotalAmountDue' => 60.00,
                                ]);
                            }                            

                            $unpaidbills = [];
                            $totalSubTotal = 0;
                            $totalSurcharge = 0;
                            $totalPayable = 0;

                            foreach($bills as $item) {
                                array_push($unpaidbills, [
                                    'BillNumber' => $item->BillNumber,
                                    'BillingMonth' => $item->ServicePeriod,
                                    'KwhUsed' => round($item->KwhUsed, 2),
                                    'DueDate' => $item->DueDate,
                                    'AmountDue' => round($item->Balance, 2),
                                    'Surcharge' => round(Bills::getSurchargeFinal($item), 2),
                                    'TotalAmountDue' => round(floatval($item->Balance) + floatval(Bills::getSurchargeFinal($item)), 2),                            
                                ]);

                                $totalSubTotal += floatval($item->Balance);
                                $totalSurcharge += floatval(Bills::getSurchargeFinal($item));
                                $totalPayable += (floatval($item->Balance) + floatval(Bills::getSurchargeFinal($item)));
                            }
                            
                            $resData['OverallSubTotal'] = round($totalSubTotal, 2);
                            $resData['OverallSurcharges'] = round($totalSurcharge, 2);
                            $resData['OverallAmountDue'] = round($totalPayable, 2);
                            $resData['UnpaidBills'] = $unpaidbills;

                            return response()->json($resData, 200);
                        }                        
                    } else {
                        return response()->json('Account Not Found', 404);
                    }
                } else {
                    return response()->json('Account Not Found', 404);
                }
            } else {
                return response()->json('Unauthorized', 401);
            }
        } else {
            return response()->json('Unauthorized', 401);
        }
    }

    public static function getTotalBalance($accountId) {        // VALIDATE ACCOUNT NUMBER
        if ($accountId != null) {
            // GET ACCOUNT DETAILS
            $serviceAccount = ServiceAccounts::find($accountId);

            if ($serviceAccount != null) {
                $bills = DB::table('Billing_Bills')
                    ->whereRaw("AccountNumber='" . $serviceAccount->id . "' AND Balance > 0")
                    ->select('Billing_Bills.*')
                    ->orderByDesc('Billing_Bills.ServicePeriod')
                    ->get();

                $totalPayable = 0;

                foreach($bills as $item) {
                    $totalPayable += (floatval($item->Balance) + floatval(Bills::getSurchargeFinal($item)));
                }                

                return $totalPayable;                     
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function attemptTransactPayment(Request $request) {
        $token = $request['_token'];
        $accountNumber = $request['AccountId'];
        // $period = $request['BillingMonth'];
        $orNo = $request['ORNumber'];
        $companyCode = $request['CompanyCode'];
        $netAmount = $request['TotalAmountPaid'];
        // $surcharge = $request['Surcharge'];
        $teller = $request['Teller'];
        // $branchOffice = $request['Branch'];
        // $paymentUsed = $request['PaymentUsed'];
        $userId = $request['UserId'];

        if ($token != null) {
            // VALIDATE TOKEN
            if (ThirdPartyAPI::isTokenValid($token)) {
                $data = ThirdPartyTokens::where('ThirdPartyToken', $token)->whereNull('Status')->first();

                if ($userId != null) {
                    $user = Users::find($userId);
                    if ($user != null) {
                        // VALIDATE ACCOUNT
                        if ($accountNumber != null) {
                            // GET ACCOUNT DETAILS
                            $account = ServiceAccounts::find($accountNumber);

                            // VALIDATE ACCOUNT
                            if ($account != null) {
                                // CHECK IF TOTAL AMOUNT TENDERED IS GREATER THAN AMOUNT PAYABLE
                                $totalPayable = ThirdPartyAPI::getTotalBalance($accountNumber);

                                $netAmount = floatval($netAmount);

                                if ($netAmount < $totalPayable) {
                                    return response()->json('Amount tendered should be greater than or equal to the total amount payable!', 403);
                                } else {
                                    // TRANSACT ALL
                                    $bills = DB::table('Billing_Bills')
                                        ->whereRaw("AccountNumber='" . $accountNumber . "' AND Balance > 0")
                                        ->select('Billing_Bills.*')
                                        ->orderByDesc('Billing_Bills.ServicePeriod')
                                        ->get();

                                    foreach ($bills as $item) {
                                        $amountRemaining = $item->Balance;
                                        $surcharge = floatval(Bills::getSurchargeFinal($item));

                                        // SKIP PAID
                                        // SAVE UNPAID
                                        $paidBill = new PaidBills([
                                            'id' => IDGenerator::generateIDandRandString(),
                                            'BillNumber' => $item->BillNumber,
                                            'AccountNumber' => $accountNumber,
                                            'ServicePeriod' => $item->ServicePeriod,
                                            'ORNumber' => $orNo,
                                            'ORDate' => date('Y-m-d'),
                                            'DCRNumber' => $companyCode . '-' . date('Y-m-d'),
                                            'KwhUsed' => $item->KwhUsed,
                                            'Teller' => $user->id,
                                            'OfficeTransacted' => 'API',
                                            'PostingDate' => null,
                                            'Surcharge' => $surcharge,
                                            'Form2307TwoPercent' => null,
                                            'Form2307FivePercent' => null,
                                            'AdditionalCharges' => null,
                                            'Deductions' => null,
                                            'NetAmount' => $item->Balance,
                                            'Source' => 'THIRD-PARTY COLLECTION API', // THIRD PARTY COLLECTION INDICATOR
                                            'ObjectSourceId' => $data != null ? $data->ThirdPartyCompany : '-', // THIRD PARTY COMPANY
                                            'UserId' => $user->id,
                                            'Status' => null,
                                            'FiledBy' => null,
                                            'ApprovedBy' => null,
                                            // 'AuditedBy' => $row['account_number'], // ACCOUNT NUMBER IN THE BILL
                                            // 'Notes' => $this->seriesNo, // SERIES REF NO
                                            'CheckNo' => $teller, // TELLER
                                            'CheckExpiration' => null,
                                            'PaymentUsed' => 'API',
                                        ]);
    
                                        $paidBill->save();
    
                                        // SAVE paidbill details
                                        $paidBillDetails = new PaidBillsDetails([
                                            'id' => IDGenerator::generateIDandRandString(),
                                            'AccountNumber' => $item->AccountNumber,
                                            'ServicePeriod' => $item->ServicePeriod,
                                            'ORNumber' => $orNo,
                                            'Amount' => $item->Balance,
                                            'PaymentUsed' => 'API',
                                            'UserId' => $user->id,
                                            'BillId' => $paidBill->id,
                                        ]);
                                        $paidBillDetails->save();

                                        // UPDATE BILLS BALANCE
                                        $bill = Bills::find($item->id);
                                        $bill->PaidAmount = $item->Balance + $bill->PaidAmount;
                                        $bill->Balance = 0;
                                        $bill->save();

                                        // SAVE BILL MIRROR
                                        $bm = BillMirror::where('AccountNumber', $item->AccountNumber)
                                            ->where('ServicePeriod', $item->ServicePeriod)
                                            ->first();

                                        if ($bm == null) {
                                            $bm = new BillMirror;
                                            $bm->id = IDGenerator::generateIDandRandString();
                                            $bm->ORNumber = $orNo;
                                            $bm->ORDate = date('Y-m-d');
                                            $bm->AccountNumber = $item->AccountNumber;
                                            $bm->ServicePeriod = $item->ServicePeriod;
                                            $bm->BillNumber = $item->BillNumber;
                                            $bm->DueDate = $item->DueDate;
                                            $bm->NetAmount = $item->NetAmount;
                                            $bm->PaidBillId = $paidBill->id;
                                        } else {
                                            $bm->PaidBillId = $paidBill->id;
                                            $bm->ORNumber = $orNo;
                                            $bm->ORDate = date('Y-m-d');
                                        }

                                        if ($item->TermedPayments != null && $item->TermedPayments > 0) {
                                            $amountRemaining = BillMirror::populateTermedPaymentAmountUpdate($amountRemaining, $bill, $bm);
                                        }

                                        if ($amountRemaining >= Bills::getOthersAmount($bill)) {
                                            $amountRemaining = BillMirror::populateOtherAmountUpdate($amountRemaining, $bill, $bm);

                                            if ($amountRemaining > 0) {
                                                $amountRemaining = BillMirror::populateBilledAmountUpdate($amountRemaining, $bill, $bm);
                                            }
                                        }

                                        $bm->save();
                                    }

                                    return response()->json('ok', 200);
                                }                                                      
                            } else {
                                return response()->json('Account Not Found', 404);
                            }
                        } else {
                            return response()->json('Account Not Found', 404);
                        }
                    } else {
                        return response()->json('User not found!', 404);
                    }                    
                } else {
                    return response()->json('UserID not provided', 404);
                }                
            } else {
                return response()->json('Unauthorized', 401);
            }
        } else {
            return response()->json('Unauthorized', 401);
        }
    }

    public function transactReconnectionFee(Request $request) {
        $token = $request['_token'];
        $accountNumber = $request['AccountId'];
        $userId = $request['UserId'];
        $orNo = $request['ORNumber'];
        $teller = $request['Teller'];
        $branchOffice = $request['Branch'];
        
        if ($token != null) {
            // VALIDATE TOKEN
            if (ThirdPartyAPI::isTokenValid($token)) {
                $data = ThirdPartyTokens::where('ThirdPartyToken', $token)->whereNull('Status')->first();

                if ($userId != null) {
                    $user = Users::find($userId);
                    if ($user != null) {
                        // VALIDATE ACCOUNT
                        if ($accountNumber != null) {
                            // GET ACCOUNT DETAILS
                            $account = ServiceAccounts::find($accountNumber);

                            // VALIDATE ACCOUNT
                            if ($account != null) {
                                if ($account->AccountStatus == 'DISCONNECTED') {
                                        // SAVE TRANSACTION  
                                        $id = IDGenerator::generateID();
                                    
                                        $transactionIndex = new TransactionIndex;
                                        $transactionIndex->id = $id;
                                        $transactionIndex->TransactionNumber = env('APP_LOCATION') . '-' . $id;
                                        $transactionIndex->PaymentTitle = $account->ServiceAccountName;
                                        $transactionIndex->PaymentDetails = "Reconnection Payment for Account Name " . $account->ServiceAccountName;
                                        $transactionIndex->ORNumber = $orNo;
                                        $transactionIndex->ORDate = date('Y-m-d');
                                        $transactionIndex->SubTotal = 60;
                                        $transactionIndex->VAT = 0;
                                        $transactionIndex->Total = 60;
                                        $transactionIndex->ObjectId = $accountNumber;
                                        $transactionIndex->Source = 'THIRD-PARTY COLLECTION API';
                                        $transactionIndex->Notes = $data->ThirdPartyCompany;
                                        $transactionIndex->PaymentUsed = 'Cash';
                                        $transactionIndex->AccountNumber = $accountNumber;
                                        $transactionIndex->UserId = $userId;
                                        $transactionIndex->save();
    
                                        // SAVE TRANSACTION DETAILS
                                        $transactionDetails = new TransactionDetails;
                                        $transactionDetails->id = IDGenerator::generateIDandRandString();
                                        $transactionDetails->TransactionIndexId = $id;
                                        $transactionDetails->Particular = 'Reconnection Fee';
                                        $transactionDetails->Amount = 60;
                                        $transactionDetails->VAT = 0;
                                        $transactionDetails->Total = 60;
                                        $transactionDetails->AccountCode = '312-456-00';
                                        $transactionDetails->save();
    
                                        // SAVE DCR
                                        $dcrSum = new DCRSummaryTransactions;
                                        $dcrSum->id = IDGenerator::generateIDandRandString();
                                        $dcrSum->GLCode = '312-456-00';
                                        $dcrSum->Amount = 60;
                                        $dcrSum->Day = date('Y-m-d');
                                        $dcrSum->Time = date('H:i:s');
                                        $dcrSum->Teller = $userId;
                                        $dcrSum->ORNumber = $orNo;
                                        $dcrSum->ReportDestination = 'COLLECTION';
                                        $dcrSum->Office = env('APP_LOCATION');
                                        $dcrSum->DCRNumber = 'API COLLECTION';
                                        $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                        $dcrSum->save();
    
                                        // CREATE RECONNECTION TICKET 
                                        $ticket = new Tickets;
                                        $ticket->id = IDGenerator::generateIDandRandString();
                                        $ticket->AccountNumber = $accountNumber;
                                        $ticket->ConsumerName = $account->ServiceAccountName;
                                        $ticket->Town =$account->Town;
                                        $ticket->Barangay = $account->Barangay;
                                        $ticket->Sitio = $account->Purok;
                                        $ticket->Ticket = Tickets::getReconnection();
                                        $ticket->Reason = 'Delinquency';
                                        $ticket->GeoLocation = $account->Latitude != null ? ($account->Latitude . ',' . $account->Longitude) : null;
                                        $ticket->Status = 'Received';
                                        $ticket->UserId = $userId;
                                        $ticket->Office = env('APP_LOCATION');
                                        $ticket->save();
    
                                        // CREATE LOG
                                        $ticketLog = new TicketLogs;
                                        $ticketLog->id = IDGenerator::generateIDandRandString();
                                        $ticketLog->TicketId = $ticket->id;
                                        $ticketLog->Log = "Ticket Filed";
                                        $ticketLog->LogDetails = "Ticket automatically created via Reconnection Payment Module";
                                        $ticketLog->UserId = $userId;
                                        $ticketLog->save();
    
                                        return response()->json('ok', 200);
                                } else {
                                    return response()->json('Not allowed on active accounts!', 403);
                                }                                
                            } else {
                                return response()->json('Account Not Found', 404);
                            }
                        } else {
                            return response()->json('Account Not Found', 404);
                        }
                    } else {
                        return response()->json('User not found!', 401);
                    }                    
                } else {
                    return response()->json('UserID not provided', 401);
                }                
            } else {
                return response()->json('Unauthorized', 401);
            }
        } else {
            return response()->json('Unauthorized', 401);
        }
    }

    /**
     * Token Validation
     */
    public static function isTokenValid($token) {
        $data = ThirdPartyTokens::where('ThirdPartyToken', $token)->whereNull('Status')->first();

        if ($data != null) {
            return true;
        } else {
            return false;
        }
    }
}