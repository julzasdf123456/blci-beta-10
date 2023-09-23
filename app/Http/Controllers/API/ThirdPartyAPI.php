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
                        // $bills = DB::table('Billing_Bills')
                        //     ->whereRaw("AccountNumber='" . $serviceAccount->id . "' AND AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber='" . $serviceAccount->id . "' AND (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod)")
                        //     ->select('Billing_Bills.*')
                        //     ->orderByDesc('Billing_Bills.ServicePeriod')
                        //     ->get();
                        if ($serviceAccount->DownloadedByDisco == 'Yes') {
                            return response()->json("This account is due for disconnection. A disconnector is on its way to disconnect this consumer.", 403);
                        } else {
                            $bills = DB::table('Billing_Bills')
                                ->whereRaw("AccountNumber='" . $serviceAccount->id . "'")
                                ->select('Billing_Bills.*',
                                    DB::raw("(SELECT TOP 1 ORNumber FROM Cashier_PaidBills WHERE AccountNumber='" . $serviceAccount->id . "' AND (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod) AS ORNumber")
                                )
                                ->orderByDesc('Billing_Bills.ServicePeriod')
                                ->get();

                            $resData = [];

                            if ($serviceAccount->AccountStatus == 'DISCONNECTED') {
                                array_push($resData, [
                                    'AccountID' => $serviceAccount->id,
                                    'AccountNumber' => $serviceAccount->OldAccountNo,
                                    'ServiceAccountName' => $serviceAccount->ServiceAccountName,
                                    'AccountStatus' => $serviceAccount->AccountStatus,
                                    'AccountType' => $serviceAccount->AccountType,
                                    'Payable' => 'Reconnection Fee',
                                    'TotalAmountDue' => 60.00,
                                ]);
                            }

                            foreach($bills as $item) {
                                array_push($resData, [
                                    'AccountID' => $serviceAccount->id,
                                    'AccountNumber' => $serviceAccount->OldAccountNo,
                                    'ServiceAccountName' => $serviceAccount->ServiceAccountName,
                                    'AccountStatus' => $serviceAccount->AccountStatus,
                                    'AccountType' => $serviceAccount->AccountType,
                                    'BillNumber' => $item->BillNumber,
                                    'BillId' => $item->id,
                                    'BillingMonth' => $item->ServicePeriod,
                                    'KwhUsed' => round($item->KwhUsed, 2),
                                    'TwoPercentWT' => round($item->Evat2Percent, 2),
                                    'FivePercentWT' => round($item->Evat5Percent, 2),
                                    'DueDate' => $item->DueDate,
                                    'AmountDue' => round($item->NetAmount, 2),
                                    'Surcharge' => round(Bills::getSurchargeFinal($item), 2),
                                    'TotalAmountDue' => round(floatval($item->NetAmount) + floatval(Bills::getSurchargeFinal($item)), 2),   
                                    'IsPaid' => $item->ORNumber==null ? 'No' : 'Yes',                             
                                ]);
                            }

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

    public function attemptTransactPayment(Request $request) {
        $token = $request['_token'];
        $accountNumber = $request['AccountId'];
        $period = $request['BillingMonth'];
        $orNo = $request['ORNumber'];
        $companyCode = $request['CompanyCode'];
        $netAmount = $request['TotalAmountDue'];
        $surcharge = $request['Surcharge'];
        $teller = $request['Teller'];
        $branchOffice = $request['Branch'];
        $paymentUsed = $request['PaymentUsed'];
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

                                // check if is already paid
                                $pbCheck = PaidBills::whereRaw("AccountNumber='" . $accountNumber . "' AND ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application')")
                                    ->first();
                                
                                if ($pbCheck != null) {
                                    return response()->json('Consumer already paid', 403);
                                } else {
                                    // ONLY ALLOW NON 2% and 5%
                                    if ($account->Evat5Percent != 'Yes' || $account->Ewt2Percent != 'Yes') {
                                        // GET BILL
                                        $bill = Bills::where('AccountNumber', $accountNumber)
                                            ->where('ServicePeriod', $period)
                                            ->first();
                                        
                                        if ($bill != null) {
                                            $billAmnt = floatval(round(floatval($bill->NetAmount) + floatval(Bills::getSurchargeFinal($bill)), 2));

                                            if ($netAmount < $billAmnt) {
                                                return response()->json('Amount provided is less than the bill amount', 403);
                                            } else {
                                                $paidBill = new PaidBills([
                                                    'id' => IDGenerator::generateIDandRandString(),
                                                    'BillNumber' => $bill->BillNumber,
                                                    'AccountNumber' => $accountNumber,
                                                    'ServicePeriod' => $period,
                                                    'ORNumber' => $orNo,
                                                    'ORDate' => date('Y-m-d'),
                                                    'DCRNumber' => $companyCode . '-' . date('Y-m-d'),
                                                    'KwhUsed' => $bill->KwhUsed,
                                                    'Teller' => $user->id,
                                                    'OfficeTransacted' => env('APP_LOCATION'),
                                                    'PostingDate' => null,
                                                    'Surcharge' => $surcharge,
                                                    'Form2307TwoPercent' => null,
                                                    'Form2307FivePercent' => null,
                                                    'AdditionalCharges' => null,
                                                    'Deductions' => null,
                                                    'NetAmount' => $netAmount,
                                                    'Source' => 'THIRD-PARTY COLLECTION API', // THIRD PARTY COLLECTION INDICATOR
                                                    'ObjectSourceId' => $data != null ? $data->ThirdPartyCompany : '-', // THIRD PARTY COMPANY
                                                    'UserId' => $user->id,
                                                    'Status' => null,
                                                    'FiledBy' => null,
                                                    'ApprovedBy' => null,
                                                    // 'AuditedBy' => $row['account_number'], // ACCOUNT NUMBER IN THE BILL
                                                    // 'Notes' => $this->seriesNo, // SERIES REF NO
                                                    'CheckNo' => $teller, // TELLER
                                                    'Bank' => $branchOffice, // THIRD PARTY OFFICE
                                                    'CheckExpiration' => null,
                                                    'PaymentUsed' => $paymentUsed,
                                                ]);
            
                                                $paidBill->save();
            
                                                // SAVE paidbill details
                                                $paidBillDetails = new PaidBillsDetails([
                                                    'id' => IDGenerator::generateIDandRandString(),
                                                    'AccountNumber' => $accountNumber,
                                                    'ServicePeriod' => $period,
                                                    'ORNumber' => $orNo,
                                                    'Amount' => $netAmount,
                                                    'PaymentUsed' => $paymentUsed,
                                                    'UserId' => $user->id,
                                                    'BillId' => $paidBill->id,
                                                ]);
                                                $paidBillDetails->save();

                                                /**
                                                 * SAVE DCR AND SALES REPORT
                                                 */
                                                if ($account != null) {                    
                                                    if ($account->ForDistribution == 'Yes') {
                                                        // IF ACCOUNT IS MARKED AS FOR DISTRIBUTION
                                                        if ($account->DistributionAccountCode != null) {
                                                            // GET AR CONSUMERS
                                                            $dcrSum = new DCRSummaryTransactions;
                                                            $dcrSum->id = IDGenerator::generateIDandRandString();
                                                            $dcrSum->GLCode = $account->DistributionAccountCode;
                                                            $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                                                            $dcrSum->Day = date('Y-m-d');
                                                            $dcrSum->NEACode = $bill->ServicePeriod;
                                                            $dcrSum->Time = date('H:i:s');
                                                            $dcrSum->Teller = $user->id;
                                                            $dcrSum->ORNumber = $orNo;
                                                            $dcrSum->ReportDestination = 'BOTH';
                                                            $dcrSum->Office = env('APP_LOCATION');
                                                            $dcrSum->AccountNumber = $bill->AccountNumber;
                                                            $dcrSum->DCRNumber = 'API COLLECTION';
                                                            $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                            $dcrSum->save();
                                                        }                        
                                                    } else {
                                                        // GET AR CONSUMERS
                                                        $dcrSum = new DCRSummaryTransactions;
                                                        $dcrSum->id = IDGenerator::generateIDandRandString();
                                                        $dcrSum->GLCode = DCRSummaryTransactions::getARConsumers($account->Town);
                                                        $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                                                        $dcrSum->Day = date('Y-m-d');
                                                        $dcrSum->NEACode = $bill->ServicePeriod;
                                                        $dcrSum->Time = date('H:i:s');
                                                        $dcrSum->Teller = $user->id;
                                                        $dcrSum->ORNumber = $orNo;
                                                        $dcrSum->ReportDestination = 'COLLECTION';
                                                        $dcrSum->Office = env('APP_LOCATION');
                                                        $dcrSum->AccountNumber = $bill->AccountNumber;
                                                        $dcrSum->DCRNumber = 'API COLLECTION';
                                                        $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                        $dcrSum->save();

                                                        // GET RPT FOR DCR
                                                        $dcrSum = new DCRSummaryTransactions;
                                                        $dcrSum->id = IDGenerator::generateIDandRandString();
                                                        $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersRPT($account->Town);
                                                        $dcrSum->Amount = $bill->RealPropertyTax;
                                                        $dcrSum->Day = date('Y-m-d');
                                                        $dcrSum->NEACode = $bill->ServicePeriod;
                                                        $dcrSum->Time = date('H:i:s');
                                                        $dcrSum->Teller = $user->id;
                                                        $dcrSum->ORNumber = $orNo;
                                                        $dcrSum->ReportDestination = 'COLLECTION';
                                                        $dcrSum->Office = env('APP_LOCATION');
                                                        $dcrSum->AccountNumber = $bill->AccountNumber;
                                                        $dcrSum->DCRNumber = 'API COLLECTION';
                                                        $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                        $dcrSum->save();

                                                        // GET RPT  FOR SALES
                                                        $dcrSum = new DCRSummaryTransactions;
                                                        $dcrSum->id = IDGenerator::generateIDandRandString();
                                                        $dcrSum->GLCode = '140-143-30';
                                                        $dcrSum->Amount = $bill->RealPropertyTax;
                                                        $dcrSum->Day = date('Y-m-d');
                                                        $dcrSum->NEACode = $bill->ServicePeriod;
                                                        $dcrSum->Time = date('H:i:s');
                                                        $dcrSum->Teller = $user->id;
                                                        $dcrSum->ORNumber = $orNo;
                                                        $dcrSum->ReportDestination = 'SALES';
                                                        $dcrSum->Office = env('APP_LOCATION');
                                                        $dcrSum->AccountNumber = $bill->AccountNumber;
                                                        $dcrSum->DCRNumber = 'API COLLECTION';
                                                        $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                        $dcrSum->save();

                                                        // GET FRANCHISE TAX FOR DCR
                                                        $dcrSum = new DCRSummaryTransactions;
                                                        $dcrSum->id = IDGenerator::generateIDandRandString();
                                                        $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersRPT($account->Town);
                                                        $dcrSum->Amount = $bill->FranchiseTax;
                                                        $dcrSum->Day = date('Y-m-d');
                                                        $dcrSum->NEACode = $bill->ServicePeriod;
                                                        $dcrSum->Time = date('H:i:s');
                                                        $dcrSum->Teller = $user->id;
                                                        $dcrSum->ORNumber = $orNo;
                                                        $dcrSum->ReportDestination = 'COLLECTION';
                                                        $dcrSum->Office = env('APP_LOCATION');
                                                        $dcrSum->AccountNumber = $bill->AccountNumber;
                                                        $dcrSum->DCRNumber = 'API COLLECTION';
                                                        $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                        $dcrSum->save();

                                                        // GET FRANCHISE TAX FOR SALES
                                                        $dcrSum = new DCRSummaryTransactions;
                                                        $dcrSum->id = IDGenerator::generateIDandRandString();
                                                        $dcrSum->GLCode = '140-143-30';
                                                        $dcrSum->Amount = $bill->FranchiseTax;
                                                        $dcrSum->Day = date('Y-m-d');
                                                        $dcrSum->NEACode = $bill->ServicePeriod;
                                                        $dcrSum->Time = date('H:i:s');
                                                        $dcrSum->Teller = $user->id;
                                                        $dcrSum->ORNumber = $orNo;
                                                        $dcrSum->ReportDestination = 'SALES';
                                                        $dcrSum->Office = env('APP_LOCATION');
                                                        $dcrSum->AccountNumber = $bill->AccountNumber;
                                                        $dcrSum->DCRNumber = 'API COLLECTION';
                                                        $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                        $dcrSum->save();

                                                        // GET BUSINESS TAX FOR DCR
                                                        $dcrSum = new DCRSummaryTransactions;
                                                        $dcrSum->id = IDGenerator::generateIDandRandString();
                                                        $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersRPT($account->Town);
                                                        $dcrSum->Amount = $bill->BusinessTax;
                                                        $dcrSum->Day = date('Y-m-d');
                                                        $dcrSum->NEACode = $bill->ServicePeriod;
                                                        $dcrSum->Time = date('H:i:s');
                                                        $dcrSum->Teller = $user->id;
                                                        $dcrSum->ORNumber = $orNo;
                                                        $dcrSum->ReportDestination = 'COLLECTION';
                                                        $dcrSum->Office = env('APP_LOCATION');
                                                        $dcrSum->AccountNumber = $bill->AccountNumber;
                                                        $dcrSum->DCRNumber = 'API COLLECTION';
                                                        $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                        $dcrSum->save();

                                                        // GET BUSINESS TAX FOR SALES
                                                        $dcrSum = new DCRSummaryTransactions;
                                                        $dcrSum->id = IDGenerator::generateIDandRandString();
                                                        $dcrSum->GLCode = '140-143-30';
                                                        $dcrSum->Amount = $bill->BusinessTax;
                                                        $dcrSum->Day = date('Y-m-d');
                                                        $dcrSum->NEACode = $bill->ServicePeriod;
                                                        $dcrSum->Time = date('H:i:s');
                                                        $dcrSum->Teller = $user->id;
                                                        $dcrSum->ORNumber = $orNo;
                                                        $dcrSum->ReportDestination = 'SALES';
                                                        $dcrSum->Office = env('APP_LOCATION');
                                                        $dcrSum->AccountNumber = $bill->AccountNumber;
                                                        $dcrSum->DCRNumber = 'API COLLECTION';
                                                        $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                        $dcrSum->save();

                                                        // GET SALES AR BY CONSUMER TYPE 
                                                        if ($account->OrganizationParentAccount != null) {
                                                            // GET BAPA
                                                            $dcrSum = new DCRSummaryTransactions;
                                                            $dcrSum->id = IDGenerator::generateIDandRandString();
                                                            $dcrSum->GLCode = '311-448-00';
                                                            $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                                                            $dcrSum->Day = date('Y-m-d');
                                                            $dcrSum->NEACode = $bill->ServicePeriod;
                                                            $dcrSum->Time = date('H:i:s');
                                                            $dcrSum->Teller = $user->id;
                                                            $dcrSum->ORNumber = $orNo;
                                                            $dcrSum->ReportDestination = 'SALES';
                                                            $dcrSum->Office = env('APP_LOCATION');
                                                            $dcrSum->AccountNumber = $bill->AccountNumber;
                                                            $dcrSum->DCRNumber = 'API COLLECTION';
                                                            $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                            $dcrSum->save();
                                                        } else {
                                                            // GET NOT BAPA
                                                            if ($account->AccountType == 'RURAL RESIDENTIAL' || $account->AccountType == 'RESIDENTIAL') {
                                                                // GET RESIDENTIALS
                                                                $dcrSum = new DCRSummaryTransactions;
                                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                                $dcrSum->GLCode = DCRSummaryTransactions::getARConsumers($account->Town);;
                                                                $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                                                                $dcrSum->Day = date('Y-m-d');
                                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                                $dcrSum->Time = date('H:i:s');
                                                                $dcrSum->Teller = $user->id;
                                                                $dcrSum->ORNumber = $orNo;
                                                                $dcrSum->ReportDestination = 'SALES';
                                                                $dcrSum->Office = env('APP_LOCATION');
                                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                                $dcrSum->save();
                                                            } else {
                                                                // GET NOT RESIDENTIALS
                                                                $dcrSum = new DCRSummaryTransactions;
                                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                                $dcrSum->GLCode = DCRSummaryTransactions::getGLCodePerAccountType($account->AccountType);;
                                                                $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                                                                $dcrSum->Day = date('Y-m-d');
                                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                                $dcrSum->Time = date('H:i:s');
                                                                $dcrSum->Teller = $user->id;
                                                                $dcrSum->ORNumber = $orNo;
                                                                $dcrSum->ReportDestination = 'SALES';
                                                                $dcrSum->Office = env('APP_LOCATION');
                                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                                $dcrSum->save();
                                                            }
                                                        }
                                                    }

                                                    // GET TERMED PAYMENT BUNDLES
                                                    if ($bill->AdditionalCharges != null) {
                                                        // GET TERMED PAYMENT
                                                        $termedPayment = ArrearsLedgerDistribution::where('AccountNumber', $account->id)
                                                            ->where('ServicePeriod', $bill->ServicePeriod)
                                                            ->whereNull('IsPaid')
                                                            ->first();

                                                        if ($termedPayment != null) {
                                                            $dcrSum = new DCRSummaryTransactions;
                                                            $dcrSum->id = IDGenerator::generateIDandRandString();
                                                            $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersTermedPayments($account->Town);
                                                            $dcrSum->Amount = $termedPayment->Amount;
                                                            $dcrSum->Day = date('Y-m-d');
                                                            $dcrSum->NEACode = $bill->ServicePeriod;
                                                            $dcrSum->Time = date('H:i:s');
                                                            $dcrSum->Teller = $user->id;
                                                            $dcrSum->ORNumber = $orNo;
                                                            $dcrSum->ReportDestination = 'COLLECTION';
                                                            $dcrSum->Office = env('APP_LOCATION');
                                                            $dcrSum->AccountNumber = $bill->AccountNumber;
                                                            $dcrSum->DCRNumber = 'API COLLECTION';
                                                            $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                            $dcrSum->save();

                                                            $termedPayment->IsPaid = 'Yes';
                                                            $termedPayment->save();
                                                        }
                                                    }
                                                }

                                                // GET UC-NPC Stranded Debt COLLECTION
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '140-142-87';
                                                $dcrSum->Amount = $bill->NPCStrandedDebt;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'COLLECTION';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET UC-NPC Stranded Debt Sales
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '230-232-65';
                                                $dcrSum->Amount = $bill->NPCStrandedDebt;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'SALES';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET STRANDED CONTRACT COST COLLECTION
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '140-142-92';
                                                $dcrSum->Amount = $bill->StrandedContractCosts;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'COLLECTION';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET STRANDED CONTRACT COST SALES
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '230-232-62';
                                                $dcrSum->Amount = $bill->StrandedContractCosts;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'SALES';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET FIT ALL COLLECTION
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '140-142-88';
                                                $dcrSum->Amount = $bill->FeedInTariffAllowance;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'COLLECTION';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET FIT ALL SALES
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '230-232-64';
                                                $dcrSum->Amount = $bill->FeedInTariffAllowance;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'SALES';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET UCME REDCI COLLECTION
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '140-142-89';
                                                $dcrSum->Amount = $bill->MissionaryElectrificationREDCI;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'COLLECTION';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET UCME REDCI SALES
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '230-232-63';
                                                $dcrSum->Amount = $bill->MissionaryElectrificationREDCI;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'SALES';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET GENCO
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '140-142-94';
                                                $dcrSum->Amount = $bill->GenerationVAT;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'COLLECTION';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET TRANSCO
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '140-142-95';
                                                $dcrSum->Amount = $bill->TransmissionVAT;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'COLLECTION';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET SYSLOSS VAT
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '140-142-96';
                                                $dcrSum->Amount = $bill->SystemLossVAT;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'COLLECTION';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET DIST/OTHERS VAT
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '140-142-97';
                                                $dcrSum->Amount = $bill->DistributionVAT;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'COLLECTION';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET GENVAT, TRANSVAT, SYSLOSSVAT SALES
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '170-184-40';
                                                $dcrSum->Amount = DCRSummaryTransactions::getSalesGenTransSysLossVatAmount($bill);
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'SALES';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET DIST AND OTHERS SALES
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '250-255-00';
                                                $dcrSum->Amount = DCRSummaryTransactions::getSalesDistOthersVatAmount($bill);
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'SALES';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET UCME COLLECTION
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '140-142-98';
                                                $dcrSum->Amount = $bill->MissionaryElectrificationCharge;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'COLLECTION';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET UCME SALES
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '230-232-60';
                                                $dcrSum->Amount = $bill->MissionaryElectrificationCharge;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'SALES';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET EWT 2%
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '140-160-00';
                                                $dcrSum->Amount = $paidBill->Form2307TwoPercent;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'COLLECTION';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET EVAT 5%
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '140-170-00';
                                                $dcrSum->Amount = $paidBill->Form2307FivePercent;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'COLLECTION';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET ENVIRONMENT CHARGE COLLECTION
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '140-142-99';
                                                $dcrSum->Amount = $bill->EnvironmentalCharge;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'COLLECTION';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET ENVIRONMENT CHARGE SALES
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '230-232-90';
                                                $dcrSum->Amount = $bill->EnvironmentalCharge;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'SALES';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET RFSC COLLECTION
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '140-142-93';
                                                $dcrSum->Amount = $bill->RFSC;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'COLLECTION';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                // GET RFSC SALES
                                                $dcrSum = new DCRSummaryTransactions;
                                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                                $dcrSum->GLCode = '211-211-10';
                                                $dcrSum->Amount = $bill->RFSC;
                                                $dcrSum->Day = date('Y-m-d');
                                                $dcrSum->NEACode = $bill->ServicePeriod;
                                                $dcrSum->Time = date('H:i:s');
                                                $dcrSum->Teller = $user->id;
                                                $dcrSum->ORNumber = $orNo;
                                                $dcrSum->ReportDestination = 'SALES';
                                                $dcrSum->Office = env('APP_LOCATION');
                                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                                $dcrSum->DCRNumber = 'API COLLECTION';
                                                $dcrSum->Description = $data != null ? $data->ThirdPartyCompany : '-';
                                                $dcrSum->save();

                                                /**
                                                 * NET METERING
                                                 * EDIT IN THE FUTURE
                                                 */
                                                // if ($account->NetMetered == 'Yes') {

                                                // }
                                            }   
                                        } else {
                                            return response()->json('Bill Not Found', 404);
                                        }

                                        return response()->json('ok', 200);
                                    } else {
                                        return response()->json('Only accounts with no 2% and 5% grant are allowed!', 403);
                                    } 
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