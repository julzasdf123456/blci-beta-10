<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaidBillsRequest;
use App\Http\Requests\UpdatePaidBillsRequest;
use App\Repositories\PaidBillsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;   
use Illuminate\Support\Facades\Auth;    
use App\Models\ServiceAccounts;
use App\Models\Bills;
use App\Models\IDGenerator;
use App\Models\PaidBills;
use App\Models\PaidBillsDetails;
use App\Models\ORAssigning;
use App\Models\Notifiers;
use App\Models\BillingMeters;
use App\Models\ORCancellations;
use App\Models\Towns;
use App\Models\Rates;
use App\Models\BAPAPayments;
use App\Models\DCRSummaryTransactions;
use App\Models\ArrearsLedgerDistribution;
use App\Models\BAPAAdjustmentDetails;
use App\Models\Denominations;
use App\Models\PrePaymentBalance;
use App\Models\TransactionDetails;
use App\Models\TransactionIndex;
use App\Models\PrePaymentTransHistory;
use App\Imports\ThirdPartyPaidBills;
use Maatwebsite\Excel\Facades\Excel;
use DateTime;
use DatePeriod;
use DateInterval;
use Flash;
use Response;

class PaidBillsController extends AppBaseController
{
    /** @var  PaidBillsRepository */
    private $paidBillsRepository;

    public function __construct(PaidBillsRepository $paidBillsRepo)
    {
        $this->middleware('auth');
        $this->paidBillsRepository = $paidBillsRepo;
    }

    /**
     * Display a listing of the PaidBills.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $orAssignedLast = ORAssigning::whereRaw("UserId ='" . Auth::id() . "'")
            ->orderByDesc('created_at')
            ->first();

        return view('paid_bills.index', [
            'orAssignedLast' => $orAssignedLast,
        ]);
    }

    /**
     * Show the form for creating a new PaidBills.
     *
     * @return Response
     */
    public function create()
    {
        return view('paid_bills.create');
    }

    /**
     * Store a newly created PaidBills in storage.
     *
     * @param CreatePaidBillsRequest $request
     *
     * @return Response
     */
    public function store(CreatePaidBillsRequest $request)
    {
        $input = $request->all();

        $paidBills = $this->paidBillsRepository->create($input);

        Flash::success('Paid Bills saved successfully.');

        return redirect(route('paidBills.index'));
    }

    /**
     * Display the specified PaidBills.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $paidBills = $this->paidBillsRepository->find($id);

        if (empty($paidBills)) {
            Flash::error('Paid Bills not found');

            return redirect(route('paidBills.index'));
        }

        return view('paid_bills.show')->with('paidBills', $paidBills);
    }

    /**
     * Show the form for editing the specified PaidBills.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $paidBills = $this->paidBillsRepository->find($id);

        if (empty($paidBills)) {
            Flash::error('Paid Bills not found');

            return redirect(route('paidBills.index'));
        }

        return view('paid_bills.edit')->with('paidBills', $paidBills);
    }

    /**
     * Update the specified PaidBills in storage.
     *
     * @param int $id
     * @param UpdatePaidBillsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePaidBillsRequest $request)
    {
        $paidBills = $this->paidBillsRepository->find($id);

        if (empty($paidBills)) {
            Flash::error('Paid Bills not found');

            return redirect(route('paidBills.index'));
        }

        $paidBills = $this->paidBillsRepository->update($request->all(), $id);

        Flash::success('Paid Bills updated successfully.');

        return redirect(route('paidBills.index'));
    }

    /**
     * Remove the specified PaidBills from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $paidBills = $this->paidBillsRepository->find($id);

        if (empty($paidBills)) {
            Flash::error('Paid Bills not found');

            return redirect(route('paidBills.index'));
        }

        $this->paidBillsRepository->delete($id);

        Flash::success('Paid Bills deleted successfully.');

        return redirect(route('paidBills.index'));
    }

    public function search(Request $request) {
        $results = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['query'] . '%')
            ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['query'] . '%')
            ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['query'] . '%')
            ->select('Billing_ServiceAccounts.id',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
            ->get();

        $output = "";

        if (count($results) > 0) {
            foreach($results as $item) {
                $output .= '
                        <tr onclick=fetchDetails("' . $item->id . '")>
                            <td>' . $item->id . '</td>
                            <td>' . $item->OldAccountNo . '</td>
                            <td>' . $item->ServiceAccountName . '</td>
                            <td>' . ServiceAccounts::getAddress($item) . '</td>
                            <td>' . $item->AccountStatus . '</td>
                            <td>
                                <button class="btn btn-link text-primary" onclick=fetchDetails("' . $item->id . '")><i class="fas fa-forward"></i></button>
                            </td>
                        </tr>
                    '; 
            }

            return response()->json($output, 200);
        } else {
            return response()->json([], 200);
        }        
    }

    public function fetchDetails(Request $request) {
        $unpaidBills = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Billing_Bills.AccountNumber', $request['AccountNumber'])
            ->whereNull('Billing_Bills.MergedToCollectible')
            ->whereNotIn('Billing_Bills.id', DB::table('Cashier_PaidBills')->where('AccountNumber', $request['AccountNumber'])->whereNull('Status')->pluck('Cashier_PaidBills.ObjectSourceId'))
            ->select('Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.*')
            ->orderByDesc('Billing_Bills.ServicePeriod')
            ->get();

        $output = "";

        if (count($unpaidBills) > 0) {
            foreach($unpaidBills as $item) {
                $ewt2 = round((floatval($item->DistributionSystemCharge) + floatval($item->SupplyRetailCustomerCharge) + floatval($item->MeteringRetailCustomerCharge)
                        + floatval($item->LifelineRate) + floatval($item->InterClassCrossSubsidyCharge)) * .02, 2);

                $evat5 = round((floatval($item->DistributionSystemCharge) + floatval($item->SupplyRetailCustomerCharge) + floatval($item->MeteringRetailCustomerCharge)
                        + floatval($item->LifelineRate) + floatval($item->InterClassCrossSubsidyCharge)) * .05, 2);
                if (date('Y-m-d', strtotime($item->DueDate)) < date('Y-m-d')) {
                    // ARREARS

                    // IF ARREARS IS LONG DUE, IT SHOULD FIRST BE UNLOCKED BY ADMINS TO BE ABLE TO PAY
                    if ($item->IsUnlockedForPayment == 'Yes') {
                        $output .= '
                            <tr onclick=addToPayables("' . $item->id . '")>
                                <td>' . $item->BillNumber . '</td>
                                <td>' . date('F Y', strtotime($item->ServicePeriod)) . '</td>
                                <td>' . date('F d, Y', strtotime($item->DueDate)) . '</td>
                                <th class="text-danger">₱ ' . number_format($item->NetAmount, 2) . ' + ' . Bills::getFinalPenalty($item) . '</th>
                                <td class="text-right">
                                    <button id="' . $item->id . '" ischecked="false" additionalCharges="' . $item->AdditionalCharges . '" deductions="' . $item->Deductions . '" surcharge="' . Bills::getFinalPenalty($item) . '" amount="' . $item->NetAmount . '" ewt="' . $ewt2 . '" evat="' . $evat5 . '" class="btn btn-link text-muted" onclick=addToPayables("' . $item->id . '")><i class="fas fa-check-circle"></i></button>
                                </td>
                            </tr>
                        '; 
                    } elseif ($item->IsUnlockedForPayment == 'Requested') {
                        $output .= '
                            <tr>
                                <td>' . $item->BillNumber . '</td>
                                <td>' . date('F Y', strtotime($item->ServicePeriod)) . '</td>
                                <td>' . date('F d, Y', strtotime($item->DueDate)) . '</td>
                                <th class="text-danger">₱ ' . number_format($item->NetAmount, 2) . ' + ' . Bills::getFinalPenalty($item) . '</th>
                                <td class="text-right">
                                    <button id="' . $item->id . '" ischecked="false" additionalCharges="' . $item->AdditionalCharges . '" deductions="' . $item->Deductions . '" surcharge="' . Bills::getFinalPenalty($item) . '" amount="' . $item->NetAmount . '" ewt="' . $ewt2 . '" evat="' . $evat5 . '" class="btn btn-link text-muted"><i class="fas fa-exclamation-circle"></i></button>
                                </td>
                            </tr>
                        ';
                    }  else {
                        $output .= '
                            <tr onclick=addToPayables("' . $item->id . '")>
                                <td>' . $item->BillNumber . '</td>
                                <td>' . date('F Y', strtotime($item->ServicePeriod)) . '</td>
                                <td>' . date('F d, Y', strtotime($item->DueDate)) . '</td>
                                <th>₱ ' . number_format($item->NetAmount, 2) . ' + ' . Bills::getFinalPenalty($item) . '</th>
                                <td class="text-right">
                                    <button id="' . $item->id . '" ischecked="false" additionalCharges="' . $item->AdditionalCharges . '" deductions="' . $item->Deductions . '" surcharge="' . Bills::getFinalPenalty($item) . '" amount="' . $item->NetAmount . '" ewt="' . $ewt2 . '" evat="' . $evat5 . '" class="btn btn-link text-muted" onclick=addToPayables("' . $item->id . '")><i class="fas fa-check-circle"></i></button>
                                </td>
                            </tr>
                        '; 
                    }                  
                } else {
                    // PROMPT PAYMENTS
                    $output .= '
                        <tr onclick=addToPayables("' . $item->id . '")>
                            <td>' . $item->BillNumber . '</td>
                            <td>' . date('F Y', strtotime($item->ServicePeriod)) . '</td>
                            <td>' . date('F d, Y', strtotime($item->DueDate)) . '</td>
                            <th>₱ ' . number_format($item->NetAmount, 2) . '</th>
                            <td class="text-right">
                                <button id="' . $item->id . '" ischecked="false" additionalCharges="' . $item->AdditionalCharges . '" deductions="' . $item->Deductions . '" amount="' . $item->NetAmount . '" ewt="' . $ewt2 . '" evat="' . $evat5 . '" class="btn btn-link text-muted" onclick=addToPayables("' . $item->id . '")><i class="fas fa-check-circle"></i></button>
                            </td>
                        </tr>
                    '; 
                }                
            }

            return response()->json($output, 200);
        } else {
            return response()->json([], 200);
        }            
    }

    public function fetchAccount(Request $request) {
        $account = DB::table('Billing_ServiceAccounts')
            ->where('id', $request['AccountNumber'])
            ->first();

        return response()->json($account, 200);
    }

    public function fetchPayable(Request $request) {
        $bill = Bills::find($request['BillId']);

        if ($bill != null) {
            return response()->json($bill, 200);
        } else {
            return response()->json(['res' => 'Bill not found'], 404);
        }
    }

    public function savePaidBillAndPrint(Request $request) {
        $billsId = $request['BillsId'];
        $len = count($billsId);

        for ($i=0; $i<$len; $i++) {
            $bill = Bills::find($billsId[$i]);

            if ($bill != null) {
                $account = ServiceAccounts::find($bill->AccountNumber);

                $paidBill = new PaidBills;
                $paidBill->id = IDGenerator::generateIDandRandString();
                $paidBill->BillNumber = $bill->BillNumber;
                $paidBill->AccountNumber = $bill->AccountNumber;
                $paidBill->ServicePeriod = $bill->ServicePeriod;
                $paidBill->KwhUsed = $bill->KwhUsed;
                $paidBill->Teller = Auth::id();
                $paidBill->OfficeTransacted = env('APP_LOCATION');
                $paidBill->PostingDate = date('Y-m-d');
                $paidBill->PostingTime = date('H:i:s');

                $paidBill->PaymentUsed = $request['PaymentUsed'];
                // CHECK IF PAYMENT IS CASH, CHECK, OR CARD
                // if ($request['PaymentUsed'] == 'Check') {
                //     $paidBill->CheckNo = $request['CheckNo'];
                //     $paidBill->Bank = $request['Bank'];
                // }

                if (date('Y-m-d', strtotime($bill->DueDate)) < date('Y-m-d')) {
                    $paidBill->Surcharge = Bills::getFinalPenalty($bill);
                } else {
                    $paidBill->Surcharge = "0";
                }
               
                // COMPUTE EWT 2%
                $ewt = 0;
                $evat = 0;
                if ($request['Ewt'] == 'true') {
                    $ewt = round((floatval($bill->DistributionSystemCharge) + floatval($bill->SupplyRetailCustomerCharge) + floatval($bill->MeteringRetailCustomerCharge)
                        + floatval($bill->LifelineRate) + floatval($bill->InterClassCrossSubsidyCharge)) * .02, 2);
                }

                // COMPUTE VAT 5%
                if ($request['VAT'] == 'true') {
                    $evat = round((floatval($bill->DistributionSystemCharge) + floatval($bill->SupplyRetailCustomerCharge) + floatval($bill->MeteringRetailCustomerCharge)
                        + floatval($bill->LifelineRate) + floatval($bill->InterClassCrossSubsidyCharge)) * .05, 2);
                }

                $paidBill->AdditionalCharges = $bill->AdditionalCharges;
                $paidBill->Deductions = $bill->Deductions;
                $paidBill->NetAmount = round((floatval($bill->NetAmount) + floatval($paidBill->Surcharge) + $evat) - $ewt, 2);
                $paidBill->Form2307TwoPercent = $ewt;
                $paidBill->Form2307FivePercent = $evat;
                $paidBill->Source = 'MONTHLY BILL';
                $paidBill->ObjectSourceId = $bill->id;
                $paidBill->ORNumber = $request['ORNumber'];
                $paidBill->ORDate = date('Y-m-d');
                $paidBill->UserId = Auth::id();
                $paidBill->save();

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
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->ORNumber = $request['ORNumber'];
                            $dcrSum->ReportDestination = 'BOTH';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
                            $dcrSum->save();
                        }                        
                    } else {
                        // GET AR CONSUMERS
                        $dcrSum = new DCRSummaryTransactions;
                        $dcrSum->id = IDGenerator::generateIDandRandString();
                        $dcrSum->GLCode = DCRSummaryTransactions::getARConsumers($account->Town);
                        $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                        $dcrSum->Day = date('Y-m-d');
                        $dcrSum->Time = date('H:i:s');
                        $dcrSum->Teller = Auth::id();
                        $dcrSum->ORNumber = $request['ORNumber'];
                        $dcrSum->ReportDestination = 'COLLECTION';
                        $dcrSum->Office = env('APP_LOCATION');
                        $dcrSum->AccountNumber = $bill->AccountNumber;
                        $dcrSum->save();

                        // GET RPT FOR DCR
                        $dcrSum = new DCRSummaryTransactions;
                        $dcrSum->id = IDGenerator::generateIDandRandString();
                        $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersRPT($account->Town);
                        $dcrSum->Amount = $bill->RealPropertyTax;
                        $dcrSum->Day = date('Y-m-d');
                        $dcrSum->Time = date('H:i:s');
                        $dcrSum->Teller = Auth::id();
                        $dcrSum->ORNumber = $request['ORNumber'];
                        $dcrSum->ReportDestination = 'COLLECTION';
                        $dcrSum->Office = env('APP_LOCATION');
                        $dcrSum->AccountNumber = $bill->AccountNumber;
                        $dcrSum->save();

                        // GET RPT  FOR SALES
                        $dcrSum = new DCRSummaryTransactions;
                        $dcrSum->id = IDGenerator::generateIDandRandString();
                        $dcrSum->GLCode = '140-143-30';
                        $dcrSum->Amount = $bill->RealPropertyTax;
                        $dcrSum->Day = date('Y-m-d');
                        $dcrSum->Time = date('H:i:s');
                        $dcrSum->Teller = Auth::id();
                        $dcrSum->ORNumber = $request['ORNumber'];
                        $dcrSum->ReportDestination = 'SALES';
                        $dcrSum->Office = env('APP_LOCATION');
                        $dcrSum->AccountNumber = $bill->AccountNumber;
                        $dcrSum->save();

                        // GET SALES AR BY CONSUMER TYPE 
                        if ($account->OrganizationParentAccount != null) {
                            // GET BAPA
                            $dcrSum = new DCRSummaryTransactions;
                            $dcrSum->id = IDGenerator::generateIDandRandString();
                            $dcrSum->GLCode = '311-448-00';
                            $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                            $dcrSum->Day = date('Y-m-d');
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->ORNumber = $request['ORNumber'];
                            $dcrSum->ReportDestination = 'SALES';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
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
                                $dcrSum->Time = date('H:i:s');
                                $dcrSum->Teller = Auth::id();
                                $dcrSum->ORNumber = $request['ORNumber'];
                                $dcrSum->ReportDestination = 'SALES';
                                $dcrSum->Office = env('APP_LOCATION');
                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                $dcrSum->save();
                            } else {
                                // GET NOT RESIDENTIALS
                                $dcrSum = new DCRSummaryTransactions;
                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                $dcrSum->GLCode = DCRSummaryTransactions::getGLCodePerAccountType($account->AccountType);;
                                $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                                $dcrSum->Day = date('Y-m-d');
                                $dcrSum->Time = date('H:i:s');
                                $dcrSum->Teller = Auth::id();
                                $dcrSum->ORNumber = $request['ORNumber'];
                                $dcrSum->ReportDestination = 'SALES';
                                $dcrSum->Office = env('APP_LOCATION');
                                $dcrSum->AccountNumber = $bill->AccountNumber;
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
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->ORNumber = $request['ORNumber'];
                            $dcrSum->ReportDestination = 'COLLECTION';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
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
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UC-NPC Stranded Debt Sales
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-65';
                $dcrSum->Amount = $bill->NPCStrandedDebt;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET STRANDED CONTRACT COST COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-92';
                $dcrSum->Amount = $bill->StrandedContractCosts;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET STRANDED CONTRACT COST SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-62';
                $dcrSum->Amount = $bill->StrandedContractCosts;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET FIT ALL COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-88';
                $dcrSum->Amount = $bill->FeedInTariffAllowance;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET FIT ALL SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-64';
                $dcrSum->Amount = $bill->FeedInTariffAllowance;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UCME REDCI COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-89';
                $dcrSum->Amount = $bill->MissionaryElectrificationREDCI;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UCME REDCI SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-63';
                $dcrSum->Amount = $bill->MissionaryElectrificationREDCI;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET GENCO
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-94';
                $dcrSum->Amount = $bill->GenerationVAT;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET TRANSCO
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-95';
                $dcrSum->Amount = $bill->TransmissionVAT;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET SYSLOSS VAT
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-96';
                $dcrSum->Amount = $bill->SystemLossVAT;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET DIST/OTHERS VAT
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-97';
                $dcrSum->Amount = $bill->DistributionVAT;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET GENVAT, TRANSVAT, SYSLOSSVAT SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '170-184-40';
                $dcrSum->Amount = DCRSummaryTransactions::getSalesGenTransSysLossVatAmount($bill);
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET DIST AND OTHERS SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '250-255-00';
                $dcrSum->Amount = DCRSummaryTransactions::getSalesDistOthersVatAmount($bill);
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UCME COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-98';
                $dcrSum->Amount = $bill->MissionaryElectrificationCharge;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UCME SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-60';
                $dcrSum->Amount = $bill->MissionaryElectrificationCharge;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET EWT 2%
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-160-00';
                $dcrSum->Amount = $paidBill->Form2307TwoPercent;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET EVAT 5%
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-170-00';
                $dcrSum->Amount = $paidBill->Form2307FivePercent;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET ENVIRONMENT CHARGE COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-99';
                $dcrSum->Amount = $bill->EnvironmentalCharge;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET ENVIRONMENT CHARGE SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-90';
                $dcrSum->Amount = $bill->EnvironmentalCharge;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET RFSC COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-93';
                $dcrSum->Amount = $bill->RFSC;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET RFSC SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '211-211-10';
                $dcrSum->Amount = $bill->RFSC;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();
            }            
        }  

        // SAVE PAIDBILL DETAILS
        if ($request['PaymentUsed'] == 'Cash and Check') {
            $paidBillDetails = new PaidBillsDetails;
            $paidBillDetails->id = IDGenerator::generateIDandRandString();
            $paidBillDetails->AccountNumber = $request['AccountNumber'];
            $paidBillDetails->ORNumber = $request['ORNumber'];
            $paidBillDetails->Amount = $request['CashAmount'];
            $paidBillDetails->PaymentUsed = 'Cash';
            $paidBillDetails->UserId = Auth::id();
            $paidBillDetails->save();
        } elseif ($request['PaymentUsed'] == 'Cash') {
            $paidBillDetails = new PaidBillsDetails;
            $paidBillDetails->id = IDGenerator::generateIDandRandString();
            $paidBillDetails->AccountNumber = $request['AccountNumber'];
            $paidBillDetails->ORNumber = $request['ORNumber'];
            $paidBillDetails->Amount = $bill->NetAmount;
            $paidBillDetails->PaymentUsed = 'Cash';
            $paidBillDetails->UserId = Auth::id();
            $paidBillDetails->save();
        }

        // DELETE CHECK ITEMS FROM PaidBillDetails that are not on the CheckIds
        $checkIds = $request['CheckIds'];
        if ($checkIds != null) {
            PaidBillsDetails::where('ORNumber', $request['ORNumber'])
                ->where('PaymentUsed', 'Check')
                // ->where('AccountNumber', $request['AccountNumber'])
                ->whereNotIn('id', $checkIds)
                ->delete();
        }        
        
        // SAVE OR
        $saveOR = ORAssigning::where('ORNumber', $request['ORNumber'])
            ->whereRaw("UserId='" . Auth::id() . "'")
            ->first();        
        if ($saveOR == null) {
            $saveOR = new ORAssigning;
            $saveOR->id = IDGenerator::generateIDandRandString();
            $saveOR->ORNumber = $request['ORNumber'];
            $saveOR->UserId = Auth::id();
            $saveOR->DateAssigned = date('Y-m-d');
            $saveOR->TimeAssigned = date('H:i:s');
            $saveOR->Office = env('APP_LOCATION');
            $saveOR->save();
        }  

        return response()->json(['ORNumber' => $request['ORNumber'], 'Teller' => Auth::id()], 200);
    }

    public function printBillPayment($paidBillId) {
        $paidBill = PaidBills::where('ORNumber', $paidBillId)->get();

        $paidBillSingle = PaidBills::where('ORNumber', $paidBillId)->first();
    
        if ($paidBillSingle != null) {
            $account = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->select('Billing_ServiceAccounts.id',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.ForDistribution',
                    'Billing_ServiceAccounts.Organization',
                    'Billing_ServiceAccounts.Main',
                    'Billing_ServiceAccounts.GroupCode',
                    'Billing_ServiceAccounts.Multiplier',
                    'Billing_ServiceAccounts.Coreloss',
                    'Billing_ServiceAccounts.ConnectionDate',
                    'Billing_ServiceAccounts.ServiceConnectionId',
                    'Billing_ServiceAccounts.SeniorCitizen',
                    'Billing_ServiceAccounts.Evat5Percent',
                    'Billing_ServiceAccounts.Ewt2Percent',
                    'Billing_ServiceAccounts.Contestable',
                    'Billing_ServiceAccounts.NetMetered',
                    'Billing_ServiceAccounts.AccountRetention',
                    'Billing_ServiceAccounts.DurationInMonths',
                    'Billing_ServiceAccounts.AccountExpiration',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->where('Billing_ServiceAccounts.id', $paidBillSingle->AccountNumber)
            ->first();
            $meter = BillingMeters::where('ServiceAccountId', $paidBillSingle->AccountNumber)
                ->orderByDesc('created_at')
                ->first();
        }

        $user = Auth::user();

        return view('/paid_bills/print_bill_payment', [
            'paidBill' => $paidBill,
            'paidBillSingle' => $paidBillSingle,
            'account' => $account,
            'meter' => $meter,
            'user' => $user,
        ]);
    }

    public function orCancellation() {
        return view('/paid_bills/or_cancellation', [

        ]);
    }

    public function searchOR(Request $request) {
        $regex = $request['query'];
        
        $results = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereNull('Cashier_PaidBills.Status')
            ->where(function($query) use ($regex) {
                $query->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $regex . '%')
                    ->orWhere('Cashier_PaidBills.AccountNumber', 'LIKE', '%' . $regex . '%')
                    ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $regex . '%')
                    ->orWhere('Cashier_PaidBills.BillNumber', 'LIKE', '%' . $regex . '%')
                    ->orWhere('Cashier_PaidBills.ORNumber', 'LIKE', '%' . $regex . '%');
            })            
            ->select('Cashier_PaidBills.AccountNumber',
                'Cashier_PaidBills.ORNumber',
                'Cashier_PaidBills.ORDate',
                'Billing_ServiceAccounts.ServiceAccountName',)
            ->groupBy('Cashier_PaidBills.ORNumber',
                'Cashier_PaidBills.ORDate',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Cashier_PaidBills.AccountNumber')
            ->get();

        $output = "";
        foreach($results as $item) {
            $output .= '<tr onclick=fetchDetails("' . $item->ORNumber . '")>
                            <td>' . $item->ORNumber . '</td>
                            <td>' . $item->AccountNumber . '</td>
                            <td>' . $item->ServiceAccountName . '</td>
                            <td>' . $item->ORDate . '</td>
                        </tr>';

        }

        return response()->json($output, 200);
    }

    public function fetchORDetails(Request $request) {
        $orNo = $request['orNo'];

        $paidBill = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('users', 'Cashier_PaidBills.Teller', '=', 'users.id')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Cashier_PaidBills.ORNumber', $orNo)
            ->whereNull('Cashier_PaidBills.Status')
            ->select('Cashier_PaidBills.id', 
                'Cashier_PaidBills.ORNumber',
                'Cashier_PaidBills.ORDate',
                'Cashier_PaidBills.PostingDate',
                'Cashier_PaidBills.PostingTime',
                'Cashier_PaidBills.KwhUsed',
                'Cashier_PaidBills.BillNumber',
                'Cashier_PaidBills.ServicePeriod',
                'Cashier_PaidBills.AdditionalCharges',
                'Cashier_PaidBills.Deductions',
                'Cashier_PaidBills.NetAmount',
                'Cashier_PaidBills.ObjectSourceId',
                'users.name',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.id as AccountNumber',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'Billing_ServiceAccounts.Purok')
            ->get();
        
        $output = "";
        $total = 0;
        foreach($paidBill as $item) {
            $output .= '<tr>
                            <td>' . $item->BillNumber . '</td>
                            <td>' . $item->ServiceAccountName . '</td>
                            <td>' . date('M d, Y', strtotime($item->ServicePeriod)) . '</td>
                            <td>' . $item->ORNumber . '</td>                            
                            <td>' . $item->ORDate . '</td>                            
                            <td>' . number_format($item->NetAmount, 2) . '</td>
                        </tr>';

            
            $total += floatval($item->NetAmount);
        }

        $output .= '<tr>
                            <th>Total</th>
                            <th></th>
                            <th></th>                            
                            <th></th>                            
                            <th></th>                            
                            <th>' . number_format($total, 2) . '</th>
                        </tr>';

        return response()->json($output, 200);
    }

    public function requestCancelOR(Request $request) {
        $orNo = $request['orNo'];

        $paidBill = PaidBills::where('ORNumber', $orNo)->get();

        if (count($paidBill) > 0) {
            foreach ($paidBill as $item) {
                $item->Status = 'PENDING CANCEL';
                $item->FiledBy = Auth::id();
                $item->Notes = $request['Notes'];
                $item->save();

                // SAVE TO OR CANCELLATIONS
                $cancellation = new ORCancellations;
                $cancellation->id = IDGenerator::generateIDandRandString();
                $cancellation->ORNumber = $item->ORNumber;
                $cancellation->ORDate = $item->ORDate;
                $cancellation->From = 'PaidBills';
                $cancellation->ObjectId = $item->id;
                $cancellation->DateTimeFiled = date('Y-m-d H:i:s');
                $cancellation->save();
            }

            // ADD NOTIFICATION
            $notifier = new Notifiers;
            $notifier->id = IDGenerator::generateIDandRandString();
            $notifier->Notification = 'OR Cancellation requested by ' . Auth::user()->name . ' with OR Number ' . $orNo;
            $notifier->From = Auth::id();
            $notifier->To = env('APP_CASHIER_HEAD_ID');
            $notifier->Status = 'SENT';
            $notifier->Intent = "OR CANCELLATION"; 
            $notifier->IntentLink = ""; // change later to or cancellation confirmations
            $notifier->ObjectId = $orNo;
            $notifier->save();

            return response()->json('ok', 200);
        } else {
            return response()->json('paid bill not found', 404);
        }
    }

    public function requestBillsPaymentUnlock(Request $request) {
        $bill = Bills::find($request['id']);

        if ($bill != null) {
            $bill->IsUnlockedForPayment = 'Requested';
            $bill->UnlockedBy = Auth::id();
            $bill->save();

            // ADD NOTIFICATION
            $notifier = new Notifiers;
            $notifier->id = IDGenerator::generateIDandRandString();
            $notifier->Notification = 'Bill unlock requested for bill number ' . $bill->BillNumber;
            $notifier->From = Auth::id();
            $notifier->To = env('APP_BILLING_ANALYST_ID');
            $notifier->Status = 'SENT';
            $notifier->Intent = "BILL ARREAR PAYMENT UNLOCKING"; 
            $notifier->IntentLink = ""; // change later to or cancellation confirmations
            $notifier->ObjectId = $bill->id;
            $notifier->save();
        }

        return response()->json($bill, 200);
    }

    public function bapaPayments() {
        $towns = Towns::orderBy('id')->get();

        return view('/paid_bills/bapa_payments', [
            'towns' => $towns,
        ]);
    }

    public function searchBapa(Request $request) {
        $param = $request['BAPA'];
        $town = $request['Town'];

        if ($town == 'All') {
            $bapas = DB::table('Billing_ServiceAccounts AS sa')
            ->where('sa.OrganizationParentAccount', 'LIKE', '%' . $param . '%')
            ->select('sa.OrganizationParentAccount', 
                'sa.Town',
                DB::raw("COUNT(sa.id) AS NoOfAccounts"),
                DB::raw("(SELECT SUBSTRING((SELECT ',' + AreaCode AS 'data()' FROM Billing_ServiceAccounts WHERE OrganizationParentAccount=sa.OrganizationParentAccount GROUP BY AreaCode FOR XML PATH('')), 2 , 9999)) As Result"))
            ->groupBy('sa.OrganizationParentAccount', 
                'sa.Town')
            ->orderBy('sa.OrganizationParentAccount')
            ->get();
        } else {
            $bapas = DB::table('Billing_ServiceAccounts AS sa')
            ->where('sa.OrganizationParentAccount', 'LIKE', '%' . $param . '%')
            ->where('sa.Town', $town)
            ->select('sa.OrganizationParentAccount', 
                'sa.Town',
                DB::raw("COUNT(sa.id) AS NoOfAccounts"),
                DB::raw("(SELECT SUBSTRING((SELECT ',' + AreaCode AS 'data()' FROM Billing_ServiceAccounts WHERE OrganizationParentAccount=sa.OrganizationParentAccount GROUP BY AreaCode FOR XML PATH('')), 2 , 9999)) As Result"))
            ->groupBy('sa.OrganizationParentAccount', 
                'sa.Town')
            ->orderBy('sa.OrganizationParentAccount')
            ->get();
        }

        $output = "";
        foreach($bapas as $item) {
            if (strlen($item->OrganizationParentAccount) > 1) {
                $output .= '<tr>
                                <td><a href="' . route('paidBills.bapa-payment-console', [urlencode($item->OrganizationParentAccount)]) . '">' . $item->OrganizationParentAccount . '</a></td>
                                <td>' . $item->Town . '</td>
                                <td>' . number_format($item->NoOfAccounts) . '</td>
                                <td>' . $item->Result . '</td>
                            </tr>';
            }
            
        }

        return response()->json($output, 200);
    }

    public function bapaPaymentConsole($bapaName) {
        $bapaName = urldecode($bapaName);

        $orAssignedLast = ORAssigning::whereRaw("UserId='" . Auth::id() . "'")
            ->orderByDesc('created_at')
            ->first();

        return view('/paid_bills/bapa_payment_console', [
            'bapaName' => $bapaName,
            'orAssignedLast' => $orAssignedLast,
        ]);
    }

    // RE-DIRECTED TO BAPA ADJUSTMENTS
    public function getBillsFromBapa(Request $request) {
        ini_set('max_execution_time', '900');
        $bapaName = $request['BAPAName'];
        $period = $request['Period'];

        $accounts = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_ServiceAccounts.id', '=', 'Billing_Bills.AccountNumber')
            ->whereRaw("Billing_Bills.ServicePeriod <= '" . $period . "'")
            ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
            // ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod = '" . $period ."' AND Status IS NULL)")
            ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=Billing_Bills.AccountNumber AND ServicePeriod=Billing_Bills.ServicePeriod AND (Status IS NULL OR Status='Application'))")
            ->whereRaw("Billing_Bills.id NOT IN (SELECT BillId FROM Cashier_BAPAAdjustmentDetails WHERE BillId IS NOT NULL)")
            ->select('Billing_ServiceAccounts.id AS AccountNumber',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.AccountStatus',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_Bills.KwhUsed',
                'Billing_Bills.ServicePeriod',
                'Billing_Bills.id as BillId',
                'Billing_Bills.NetAmount',
                // DB::raw("(SELECT TOP 1 id FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod=Billing_Bills.ServicePeriod) AS BillId"),
                // DB::raw("(SELECT TOP 1 NetAmount FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod=Billing_Bills.ServicePeriod) AS NetAmount"),
                DB::raw("(SELECT TOP 1 ORNumber FROM Cashier_PaidBills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod=Billing_Bills.ServicePeriod AND Status IS NULL) AS ORNumber"),)
            ->orderBy('Billing_ServiceAccounts.OldAccountNo')
            ->get();

        return response()->json($accounts, 200);
    }

    // THE NEW BAPA BILLS QUERY
    public function getAdjustedBapaBills(Request $request) {
        $bapaName = $request['BAPAName'];
        $period = $request['Period'];

        $accounts = DB::table('Cashier_BAPAAdjustmentDetails')
            ->leftJoin('Billing_Bills', 'Cashier_BAPAAdjustmentDetails.BillId', '=', 'Billing_Bills.id')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_ServiceAccounts.id', '=', 'Billing_Bills.AccountNumber')
            ->where("Cashier_BAPAAdjustmentDetails.ServicePeriod", $period)
            ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
            ->select('Billing_ServiceAccounts.id AS AccountNumber',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.AccountStatus',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_Bills.KwhUsed',
                'Billing_Bills.ServicePeriod',
                'Billing_Bills.id',
                'Billing_Bills.BillNumber',
                'Billing_Bills.NetAmount',
                'Cashier_BAPAAdjustmentDetails.DiscountPercentage',
                'Cashier_BAPAAdjustmentDetails.DiscountAmount',
                DB::raw("(SELECT TOP 1 ORNumber FROM Cashier_PaidBills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod=Billing_Bills.ServicePeriod AND Status IS NULL) AS ORNumber"),)
            ->orderBy('Billing_ServiceAccounts.AccountStatus')
            ->get();

        return response()->json($accounts, 200);
    }

    public function saveBapaPayments(Request $request) {
        $accounts = $request['AccountNumbers'];
        $len = count($accounts);
        $period = $request['Period'];

        $orStart = intval($request['ORNumber']) + 1;
        $dcrNum = 'BAPA-' . IDGenerator::generateID();

        // SAVE EACH TRANSACTION
        for($i=0; $i<$len; $i++) {
            $bill = Bills::find($accounts[$i]);
            $billAdjustment = BAPAAdjustmentDetails::where('BillId', $bill->id)->first();

            $account = ServiceAccounts::find($bill->AccountNumber);

            if ($bill != null) {
                // SAVE OR FIRST
                $saveORNew = new ORAssigning;
                $saveORNew->id = IDGenerator::generateIDandRandString();
                $saveORNew->ORNumber = $orStart;
                $saveORNew->UserId = Auth::id();
                $saveORNew->DateAssigned = date('Y-m-d');
                $saveORNew->TimeAssigned = date('H:i:s');
                $saveORNew->Office = env('APP_LOCATION');
                $saveORNew->save();

                $orStart += 1;

                $paidBill = new PaidBills;
                $paidBill->id = IDGenerator::generateIDandRandString();
                $paidBill->BillNumber = $bill->BillNumber;
                $paidBill->AccountNumber = $bill->AccountNumber;
                $paidBill->ServicePeriod = $bill->ServicePeriod;
                $paidBill->KwhUsed = $bill->KwhUsed;
                $paidBill->Teller = Auth::id();
                $paidBill->OfficeTransacted = env('APP_LOCATION');
                $paidBill->PostingDate = date('Y-m-d');
                $paidBill->PostingTime = date('H:i:s');
                $paidBill->DCRNumber = $dcrNum;
                
                $paidBill->PaymentUsed = $request['PaymentUsed'];
                // CHECK IF PAYMENT IS CASH, CHECK, OR CARD
                if ($request['PaymentUsed'] == 'Check') {
                    $paidBill->CheckNo = $request['CheckNo'];
                    $paidBill->Bank = $request['Bank'];
                }
                if (date('Y-m-d', strtotime($bill->DueDate)) < date('Y-m-d') && ($bill->ConsumerType != 'RESIDENTIAL' || $bill->ConsumerType != 'RURAL RESIDENTIAL')) {
                    $paidBill->Surcharge = Bills::getFinalPenalty($bill);
                } else {
                    $paidBill->Surcharge = "0";
                }            

                $paidBill->AdditionalCharges = $bill->AdditionalCharges;

                if ($billAdjustment != null) {
                    $paidBill->Deductions = round(floatval($bill->Deductions) + floatval($billAdjustment->DiscountAmount), 2);
                } else {
                    $paidBill->Deductions = round(floatval($bill->Deductions), 2);
                }
                
                $paidBill->NetAmount = round((floatval($bill->NetAmount) + floatval($paidBill->Surcharge)) - floatval($paidBill->Deductions), 2);

                $paidBill->Source = 'MONTHLY BILL';
                $paidBill->ObjectSourceId = $bill->id;
                $paidBill->ORNumber = $saveORNew->ORNumber;
                $paidBill->ORDate = date('Y-m-d');
                $paidBill->UserId = Auth::id();
                $paidBill->save();

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
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->ORNumber = $request['ORNumber'];
                            $dcrSum->ReportDestination = 'BOTH';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
                            $dcrSum->save();
                        }                        
                    } else {
                        // GET AR CONSUMERS
                        $dcrSum = new DCRSummaryTransactions;
                        $dcrSum->id = IDGenerator::generateIDandRandString();
                        $dcrSum->GLCode = DCRSummaryTransactions::getARConsumers($account->Town);
                        $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                        $dcrSum->Day = date('Y-m-d');
                        $dcrSum->Time = date('H:i:s');
                        $dcrSum->Teller = Auth::id();
                        $dcrSum->ORNumber = $request['ORNumber'];
                        $dcrSum->ReportDestination = 'COLLECTION';
                        $dcrSum->Office = env('APP_LOCATION');
                        $dcrSum->AccountNumber = $bill->AccountNumber;
                        $dcrSum->save();

                        // GET RPT FOR DCR
                        $dcrSum = new DCRSummaryTransactions;
                        $dcrSum->id = IDGenerator::generateIDandRandString();
                        $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersRPT($account->Town);
                        $dcrSum->Amount = $bill->RealPropertyTax;
                        $dcrSum->Day = date('Y-m-d');
                        $dcrSum->Time = date('H:i:s');
                        $dcrSum->Teller = Auth::id();
                        $dcrSum->ORNumber = $request['ORNumber'];
                        $dcrSum->ReportDestination = 'COLLECTION';
                        $dcrSum->Office = env('APP_LOCATION');
                        $dcrSum->AccountNumber = $bill->AccountNumber;
                        $dcrSum->save();

                        // GET RPT  FOR SALES
                        $dcrSum = new DCRSummaryTransactions;
                        $dcrSum->id = IDGenerator::generateIDandRandString();
                        $dcrSum->GLCode = '140-143-30';
                        $dcrSum->Amount = $bill->RealPropertyTax;
                        $dcrSum->Day = date('Y-m-d');
                        $dcrSum->Time = date('H:i:s');
                        $dcrSum->Teller = Auth::id();
                        $dcrSum->ORNumber = $request['ORNumber'];
                        $dcrSum->ReportDestination = 'SALES';
                        $dcrSum->Office = env('APP_LOCATION');
                        $dcrSum->AccountNumber = $bill->AccountNumber;
                        $dcrSum->save();

                        // GET SALES AR BY CONSUMER TYPE 
                        if ($account->OrganizationParentAccount != null) {
                            // GET BAPA
                            $dcrSum = new DCRSummaryTransactions;
                            $dcrSum->id = IDGenerator::generateIDandRandString();
                            $dcrSum->GLCode = '311-448-00';
                            $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                            $dcrSum->Day = date('Y-m-d');
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->ORNumber = $request['ORNumber'];
                            $dcrSum->ReportDestination = 'SALES';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
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
                                $dcrSum->Time = date('H:i:s');
                                $dcrSum->Teller = Auth::id();
                                $dcrSum->ORNumber = $request['ORNumber'];
                                $dcrSum->ReportDestination = 'SALES';
                                $dcrSum->Office = env('APP_LOCATION');
                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                $dcrSum->save();
                            } else {
                                // GET NOT RESIDENTIALS
                                $dcrSum = new DCRSummaryTransactions;
                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                $dcrSum->GLCode = DCRSummaryTransactions::getGLCodePerAccountType($account->AccountType);;
                                $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                                $dcrSum->Day = date('Y-m-d');
                                $dcrSum->Time = date('H:i:s');
                                $dcrSum->Teller = Auth::id();
                                $dcrSum->ORNumber = $request['ORNumber'];
                                $dcrSum->ReportDestination = 'SALES';
                                $dcrSum->Office = env('APP_LOCATION');
                                $dcrSum->AccountNumber = $bill->AccountNumber;
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
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->ORNumber = $request['ORNumber'];
                            $dcrSum->ReportDestination = 'COLLECTION';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
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
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UC-NPC Stranded Debt Sales
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-65';
                $dcrSum->Amount = $bill->NPCStrandedDebt;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET STRANDED CONTRACT COST COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-92';
                $dcrSum->Amount = $bill->StrandedContractCosts;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET STRANDED CONTRACT COST SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-62';
                $dcrSum->Amount = $bill->StrandedContractCosts;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET FIT ALL COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-88';
                $dcrSum->Amount = $bill->FeedInTariffAllowance;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET FIT ALL SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-64';
                $dcrSum->Amount = $bill->FeedInTariffAllowance;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UCME REDCI COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-89';
                $dcrSum->Amount = $bill->MissionaryElectrificationREDCI;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UCME REDCI SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-63';
                $dcrSum->Amount = $bill->MissionaryElectrificationREDCI;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET GENCO
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-94';
                $dcrSum->Amount = $bill->GenerationVAT;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET TRANSCO
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-95';
                $dcrSum->Amount = $bill->TransmissionVAT;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET SYSLOSS VAT
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-96';
                $dcrSum->Amount = $bill->SystemLossVAT;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET DIST/OTHERS VAT
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-97';
                $dcrSum->Amount = $bill->DistributionVAT;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET GENVAT, TRANSVAT, SYSLOSSVAT SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '170-184-40';
                $dcrSum->Amount = DCRSummaryTransactions::getSalesGenTransSysLossVatAmount($bill);
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET DIST AND OTHERS SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '250-255-00';
                $dcrSum->Amount = DCRSummaryTransactions::getSalesDistOthersVatAmount($bill);
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UCME COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-98';
                $dcrSum->Amount = $bill->MissionaryElectrificationCharge;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UCME SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-60';
                $dcrSum->Amount = $bill->MissionaryElectrificationCharge;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET EWT 2%
                // $dcrSum = new DCRSummaryTransactions;
                // $dcrSum->id = IDGenerator::generateIDandRandString();
                // $dcrSum->GLCode = '140-160-00';
                // $dcrSum->Amount = $paidBill->Form2307TwoPercent;
                // $dcrSum->Day = date('Y-m-d');
                // $dcrSum->Time = date('H:i:s');
                // $dcrSum->Teller = Auth::id();
                // $dcrSum->ORNumber = $saveORNew->ORNumber;
                // $dcrSum->ReportDestination = 'COLLECTION';
                // $dcrSum->Office = env('APP_LOCATION');
                // $dcrSum->AccountNumber = $bill->AccountNumber;
                // $dcrSum->save();

                // GET EVAT 5%
                // $dcrSum = new DCRSummaryTransactions;
                // $dcrSum->id = IDGenerator::generateIDandRandString();
                // $dcrSum->GLCode = '140-170-00';
                // $dcrSum->Amount = $paidBill->Form2307FivePercent;
                // $dcrSum->Day = date('Y-m-d');
                // $dcrSum->Time = date('H:i:s');
                // $dcrSum->Teller = Auth::id();
                // $dcrSum->ORNumber = $saveORNew->ORNumber;
                // $dcrSum->ReportDestination = 'COLLECTION';
                // $dcrSum->Office = env('APP_LOCATION');
                // $dcrSum->AccountNumber = $bill->AccountNumber;
                // $dcrSum->save();

                // GET ENVIRONMENT CHARGE COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-99';
                $dcrSum->Amount = $bill->EnvironmentalCharge;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET ENVIRONMENT CHARGE SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-90';
                $dcrSum->Amount = $bill->EnvironmentalCharge;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET RFSC COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-93';
                $dcrSum->Amount = $bill->RFSC;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET RFSC SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '211-211-10';
                $dcrSum->Amount = $bill->RFSC;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $saveORNew->ORNumber;
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();
            }
        }

        // SAVE PAYMENT TO BAPA Payments
        $bapaPayments = new BAPAPayments;
        $bapaPayments->id = IDGenerator::generateIDandRandString();
        $bapaPayments->BAPAName = urldecode($request['BAPAName']);
        $bapaPayments->ServicePeriod = $period;
        // $bapaPayments->ORNumber = $saveORNew->ORNumber;
        $bapaPayments->ORDate = date('Y-m-d');
        $bapaPayments->SubTotal = round(floatval($request['SubTotal']), 2);
        $bapaPayments->TwoPercentDiscount = round(floatval($request['DiscountAmount']), 2);
        // $bapaPayments->FivePercentDiscount = round(floatval($request['Discount5']), 2);
        $bapaPayments->Total = round(floatval($request['TotalAmountPaid']), 2);
        $bapaPayments->Teller = Auth::id();
        $bapaPayments->NoOfConsumersPaid = $len;
        $bapaPayments->save(); 

        return response()->json($dcrNum, 200);
    }

    public function printBapaPayments($dcrNum) {
        $paidBills = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Cashier_PaidBills.DCRNumber', $dcrNum)
            ->select('Cashier_PaidBills.*',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.Purok',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.AccountType',
                'Billing_ServiceAccounts.AccountStatus',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id AND created_at IS NOT NULL ORDER BY created_at DESC) AS MeterNumber"))
            ->get();

        return view('/paid_bills/print_bapa_payments', [
            'paidBills' => $paidBills
        ]);
    }

    public function billsCollection() {
        // $paidBills = $this->paidBillsRepository->all();
        $orAssignedLast = ORAssigning::whereRaw("UserId='" . Auth::id() . "'")
            ->orderByDesc('created_at')
            ->first();

        return view('paid_bills.index', [
            // 'paidBills' => $paidBills,
            'orAssignedLast' => $orAssignedLast,
        ]);
    }

    public function addCheckPayments(Request $request) {
        $paidBillDetails = new PaidBillsDetails;
        $paidBillDetails->id = IDGenerator::generateIDandRandString();
        $paidBillDetails->AccountNumber = $request['AccountNumber'];
        $paidBillDetails->ServicePeriod = $request['ServicePeriod'];
        $paidBillDetails->BillId = $request['Billid'];
        $paidBillDetails->ORNumber = $request['ORNumber'];
        $paidBillDetails->Amount = $request['Amount'];
        $paidBillDetails->PaymentUsed = 'Check';
        $paidBillDetails->CheckNo = $request['CheckNo'];
        $paidBillDetails->Bank = $request['Bank'];
        $paidBillDetails->CheckExpiration = $request['CheckExpiration'];
        $paidBillDetails->UserId = Auth::id();
        $paidBillDetails->save();

        return response()->json($paidBillDetails, 200);
    }

    public function deleteCheckPayment(Request $request) {
        $paidBillDetails = PaidBillsDetails::find($request['id']);

        $paidBillDetails->delete();

        return response()->json($paidBillDetails, 200);
    }

    public function fetchAccountByOldAccountNumber(Request $request) {
        $account = ServiceAccounts::where('OldAccountNo', $request['OldAccountNo'])->first();

        if ($account != null) {
            return response()->json($account, 200);
        } else {
            return response()->json('Account not found', 404);
        }
    }

    public function getORsFromRange(Request $request) {
        $from = $request['From'];
        $to = $request['To'];

        $paidBills = PaidBills::whereBetween('ORNumber', [$from, $to])
            ->get();

        return response()->json($paidBills, 200);
    }

    public function addDenomination(Request $request) {
        $acctNo = $request['AccountNumber'];
        $period = $request['ServicePeriod'];

        $denominations = Denominations::where('AccountNumber', $acctNo)
            ->where('ServicePeriod', $period)
            ->first();

        if ($denominations != null) {
            // update
            $denominations->OneThousand = $request['OneThousand'];
            $denominations->FiveHundred = $request['FiveHundred'];
            $denominations->OneHundred = $request['OneHundred'];
            $denominations->Fifty = $request['Fifty'];
            $denominations->Twenty = $request['Twenty'];
            $denominations->Ten = $request['Ten'];
            $denominations->Five = $request['Five'];
            $denominations->Peso = $request['Peso'];
            $denominations->Cents = $request['Cents'];
            $denominations->ORNumber = $request['ORNumber'];
            $denominations->ORDate = date('Y-m-d');
            $denominations->save();
        } else {
            // save
            $denominations = new Denominations;
            $denominations->id = IDGenerator::generateIDandRandString();
            $denominations->ServicePeriod = $period;
            $denominations->AccountNumber = $acctNo;
            $denominations->OneThousand = $request['OneThousand'];
            $denominations->FiveHundred = $request['FiveHundred'];
            $denominations->OneHundred = $request['OneHundred'];
            $denominations->Fifty = $request['Fifty'];
            $denominations->Twenty = $request['Twenty'];
            $denominations->Ten = $request['Ten'];
            $denominations->Five = $request['Five'];
            $denominations->Peso = $request['Peso'];
            $denominations->Cents = $request['Cents'];
            $denominations->ORNumber = $request['ORNumber'];
            $denominations->ORDate = date('Y-m-d');
            $denominations->save();
        }

        return response()->json($denominations, 200);
    }

    public function thirdPartyCollection(Request $request) {
        $date = $request['Day'] != null ? $request['Day'] : date('Y-m-d');

        $unposted = DB::table('Cashier_PaidBills')
            ->whereRaw("Status='PENDING POST' AND Source='THIRD-PARTY COLLECTION'")
            ->select('Notes',
                DB::raw("COUNT(id) AS NoOfPayments"))
            ->groupBy('Notes')
            ->orderByDesc('Notes')
            ->get();

        $transacted = DB::table('Cashier_PaidBills')
            ->whereRaw("Source='THIRD-PARTY COLLECTION' AND ORDate='" . $date . "'")
            ->select('ObjectSourceId',
                'Notes',
                'PostingDate',
                DB::raw("(SELECT COUNT(p.id) FROM Cashier_PaidBills p WHERE p.Source='THIRD-PARTY COLLECTION' AND p.ORDate='" . $date . "' AND p.Status='DEPOSITED AS PRE-PAYMENT' AND p.ObjectSourceId=Cashier_PaidBills.ObjectSourceId AND p.Notes=Cashier_PaidBills.Notes) AS DoublePayments"),
                DB::raw("(SELECT SUM(CAST(p.NetAmount AS Decimal(10,2))) FROM Cashier_PaidBills p WHERE p.Source='THIRD-PARTY COLLECTION' AND p.ORDate='" . $date . "' AND p.Status='DEPOSITED AS PRE-PAYMENT' AND p.ObjectSourceId=Cashier_PaidBills.ObjectSourceId AND p.Notes=Cashier_PaidBills.Notes) AS DoublePaymentsSum"),
                DB::raw("(SELECT COUNT(p.id) FROM Cashier_PaidBills p WHERE p.Source='THIRD-PARTY COLLECTION' AND p.ORDate='" . $date . "' AND p.Status='PENDING POST' AND p.ObjectSourceId=Cashier_PaidBills.ObjectSourceId AND p.Notes=Cashier_PaidBills.Notes) AS Pendings"),
                DB::raw("(SELECT SUM(CAST(p.NetAmount AS Decimal(10,2))) FROM Cashier_PaidBills p WHERE p.Source='THIRD-PARTY COLLECTION' AND p.ORDate='" . $date . "' AND p.Status='PENDING POST' AND p.ObjectSourceId=Cashier_PaidBills.ObjectSourceId AND p.Notes=Cashier_PaidBills.Notes) AS PendingsSum"),
                DB::raw("(SELECT COUNT(p.id) FROM Cashier_PaidBills p WHERE p.Source='THIRD-PARTY COLLECTION' AND p.ORDate='" . $date . "' AND p.Status IS NULL AND p.ObjectSourceId=Cashier_PaidBills.ObjectSourceId AND p.Notes=Cashier_PaidBills.Notes) AS Posted"),
                DB::raw("(SELECT SUM(CAST(p.NetAmount AS Decimal(10,2))) FROM Cashier_PaidBills p WHERE p.Source='THIRD-PARTY COLLECTION' AND p.ORDate='" . $date . "' AND p.Status IS NULL AND p.ObjectSourceId=Cashier_PaidBills.ObjectSourceId AND p.Notes=Cashier_PaidBills.Notes) AS PostedSum"),
                DB::raw("COUNT(id) AS TotalPayments"),
                DB::raw("SUM(CAST(NetAmount AS Decimal(10,2))) AS TotalPaymentsSum"),
            )
            ->groupBy('ObjectSourceId', 'Notes', 'PostingDate')
            ->orderByDesc('ObjectSourceId')
            ->get();

        return view('/paid_bills/third_party_collection', [
            'unposted' => $unposted,
            'date' => $date,
            'transacted' => $transacted,
        ]);
    }

    public function uploadThirdPartyCollection() {
        return view('/paid_bills/upload_third_party_collection', [

        ]);
    }

    public function validateTpcUpload(Request $request) {
        if ($request->file('file') != null) {
            $file = $request->file('file');
            $userId = Auth::id();
            $seriesNo = IDGenerator::generateID();

            // IMPORT
            $tpc = new ThirdPartyPaidBills($userId, $seriesNo);
            Excel::import($tpc, $file);

            return redirect(route('paidBills.tcp-upload-validator', [$seriesNo]));
        } else {
            return abort(404, "No file imported!");
        }
    }

    public function tcpUploadValidator($seriesNo) {
        // QUERY IMPORTED
        $paidBills = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Cashier_PaidBills.Notes', $seriesNo)
            ->where('Cashier_PaidBills.Status', 'PENDING POST')
            ->select('Cashier_PaidBills.*',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.ServiceAccountName',
                DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Cashier_PaidBills.AccountNumber AND ServicePeriod=Cashier_PaidBills.ServicePeriod) AS KwhUsed"),
                DB::raw("(SELECT TOP 1 BillNumber FROM Billing_Bills WHERE AccountNumber=Cashier_PaidBills.AccountNumber AND ServicePeriod=Cashier_PaidBills.ServicePeriod) AS BillNumber"),
                DB::raw("(SELECT TOP 1 NetAmount FROM Billing_Bills WHERE AccountNumber=Cashier_PaidBills.AccountNumber AND ServicePeriod=Cashier_PaidBills.ServicePeriod) AS BillAmount"),
                DB::raw("(SELECT COUNT(p.id) FROM Cashier_PaidBills p WHERE p.AccountNumber=Cashier_PaidBills.AccountNumber AND ServicePeriod=Cashier_PaidBills.ServicePeriod AND Status IS NULL) AS Duplicates")
            )
            ->orderBy('OldAccountNo')
            ->get();

        return view('/paid_bills/tcp_upload_validator', [
            'paidBills' => $paidBills,
            'seriesNo' => $seriesNo
        ]);
    }

    public function depositDoublePayments($seriesNo) {
        $paidBills = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Cashier_PaidBills.Notes', $seriesNo)
            ->where('Cashier_PaidBills.Status', 'PENDING POST')
            ->whereRaw("Cashier_PaidBills.AccountNumber IN (SELECT AccountNumber FROM Cashier_PaidBills p WHERE p.AccountNumber=Cashier_PaidBills.AccountNumber AND ServicePeriod=Cashier_PaidBills.ServicePeriod AND Status IS NULL)")
            ->select('Cashier_PaidBills.*',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.ServiceAccountName',
            )
            ->orderBy('OldAccountNo')
            ->get();

        foreach($paidBills as $item) {
            $prePaymentBalance = PrePaymentBalance::where('AccountNumber', $item->AccountNumber)->first();

            if ($prePaymentBalance != null) {
                $oldAmount = floatval($prePaymentBalance->Balance);
                $newAmount = floatval($item->NetAmount) + $oldAmount;

                $prePaymentBalance->Balance = $newAmount;
                $prePaymentBalance->save();
            } else {
                $prePaymentBalance = new PrePaymentBalance();
                $prePaymentBalance->id = IDGenerator::generateIDandRandString();
                $prePaymentBalance->AccountNumber = $item->AccountNumber;
                $prePaymentBalance->Balance = $item->NetAmount;
                $prePaymentBalance->save();
            }

            // ADD TRANSACTION HISTORY
            $transHistory = new PrePaymentTransHistory;
            $transHistory->id = IDGenerator::generateIDandRandString();
            $transHistory->AccountNumber = $item->AccountNumber;
            $transHistory->Method = 'DEPOSIT';
            $transHistory->Amount = $item->NetAmount;
            $transHistory->UserId = Auth::id(); 
            $transHistory->Notes = 'Double Payment from Third Party Collection (' . $item->ObjectSourceId . ' - Ref. No: ' . $item->DCRNumber . ')';
            $transHistory->ORNumber = $item->ORNumber;
            $transHistory->save();

            // SAVE TRANSACTION
            $id = IDGenerator::generateID();

            $transactionIndex = new TransactionIndex;
            $transactionIndex->id = $id;
            $transactionIndex->TransactionNumber = env('APP_LOCATION') . '-' . $id;
            $transactionIndex->PaymentTitle = "Pre-payment Deposit for Account No. " . $item->AccountNumber;
            $transactionIndex->ORNumber = $item->ORNumber;
            $transactionIndex->ORDate = date('Y-m-d');
            $transactionIndex->Total = $item->NetAmount;
            $transactionIndex->Source = 'Pre-Payment Deposits';
            $transactionIndex->ObjectId = $transHistory->id;
            $transactionIndex->PaymentUsed = 'Cash';
            $transactionIndex->UserId = Auth::id();
            $transactionIndex->save();

            // SAVE TRANSACTION DETAILS
            $transactionDetails = new TransactionDetails;
            $transactionDetails->id = IDGenerator::generateIDandRandString();
            $transactionDetails->TransactionIndexId = $id;
            $transactionDetails->Particular = 'Pre-Payment Deposit';
            $transactionDetails->Amount = $item->NetAmount;
            $transactionDetails->Total = $item->NetAmount;
            $transactionDetails->save();

            // update paidbills
            $pb = PaidBills::find($item->id);
            if ($pb != null) {
                $pb->Status = 'DEPOSITED AS PRE-PAYMENT';
                $pb->save();
            }            
        }

        return redirect(route('paidBills.tcp-upload-validator', [$seriesNo]));
    }

    public function postPayments($seriesNo) {
        $paidBills = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Cashier_PaidBills.Notes', $seriesNo)
            ->where('Cashier_PaidBills.Status', 'PENDING POST')
            ->whereRaw("Cashier_PaidBills.AccountNumber IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod=Cashier_PaidBills.ServicePeriod)")
            ->whereRaw("Cashier_PaidBills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills p WHERE p.AccountNumber=Cashier_PaidBills.AccountNumber AND ServicePeriod=Cashier_PaidBills.ServicePeriod AND Status IS NULL)")
            ->select('Cashier_PaidBills.*')
            ->get();

        foreach($paidBills as $item) {
            $bill = Bills::where('ServicePeriod', $item->ServicePeriod)
                ->where('AccountNumber', $item->AccountNumber)
                ->first();

            if ($bill != null) {
                if (round($bill->NetAmount, 2) == round($item->NetAmount, 2)) {
                    $account = ServiceAccounts::find($bill->AccountNumber);
                    $paidBill = PaidBills::find($item->id);
                    $paidBill->Status = null;
                    $paidBill->PostingDate = date('Y-m-d');
                    $paidBill->PostingTime = date('H:i:s');
                    $paidBill->save();

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
                                $dcrSum->Teller = Auth::id();
                                $dcrSum->DCRNumber = 'TP COLLECTION';
                                $dcrSum->ORNumber = $item->ORNumber;
                                $dcrSum->ReportDestination = 'BOTH';
                                $dcrSum->Office = env('APP_LOCATION');
                                $dcrSum->AccountNumber = $bill->AccountNumber;
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
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->DCRNumber = 'TP COLLECTION';
                            $dcrSum->ORNumber = $item->ORNumber;
                            $dcrSum->ReportDestination = 'COLLECTION';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
                            $dcrSum->save();

                            // GET RPT FOR DCR
                            $dcrSum = new DCRSummaryTransactions;
                            $dcrSum->id = IDGenerator::generateIDandRandString();
                            $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersRPT($account->Town);
                            $dcrSum->Amount = $bill->RealPropertyTax;
                            $dcrSum->Day = date('Y-m-d');
                            $dcrSum->NEACode = $bill->ServicePeriod;
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->DCRNumber = 'TP COLLECTION';
                            $dcrSum->ORNumber = $item->ORNumber;
                            $dcrSum->ReportDestination = 'COLLECTION';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
                            $dcrSum->save();

                            // GET RPT  FOR SALES
                            $dcrSum = new DCRSummaryTransactions;
                            $dcrSum->id = IDGenerator::generateIDandRandString();
                            $dcrSum->GLCode = '140-143-30';
                            $dcrSum->Amount = $bill->RealPropertyTax;
                            $dcrSum->Day = date('Y-m-d');
                            $dcrSum->NEACode = $bill->ServicePeriod;
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->DCRNumber = 'TP COLLECTION';
                            $dcrSum->ORNumber = $item->ORNumber;
                            $dcrSum->ReportDestination = 'SALES';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
                            $dcrSum->save();

                            // GET FRANCHISE TAX FOR DCR
                            $dcrSum = new DCRSummaryTransactions;
                            $dcrSum->id = IDGenerator::generateIDandRandString();
                            $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersRPT($account->Town);
                            $dcrSum->Amount = $bill->FranchiseTax;
                            $dcrSum->Day = date('Y-m-d');
                            $dcrSum->NEACode = $bill->ServicePeriod;
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->DCRNumber = 'TP COLLECTION';
                            $dcrSum->ORNumber = $item->ORNumber;
                            $dcrSum->ReportDestination = 'COLLECTION';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
                            $dcrSum->save();

                            // GET FRANCHISE TAX  FOR SALES
                            $dcrSum = new DCRSummaryTransactions;
                            $dcrSum->id = IDGenerator::generateIDandRandString();
                            $dcrSum->GLCode = '140-143-30';
                            $dcrSum->Amount = $bill->FranchiseTax;
                            $dcrSum->Day = date('Y-m-d');
                            $dcrSum->NEACode = $bill->ServicePeriod;
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->DCRNumber = 'TP COLLECTION';
                            $dcrSum->ORNumber = $item->ORNumber;
                            $dcrSum->ReportDestination = 'SALES';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
                            $dcrSum->save();

                            // GET BUSINESS TAX FOR DCR
                            $dcrSum = new DCRSummaryTransactions;
                            $dcrSum->id = IDGenerator::generateIDandRandString();
                            $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersRPT($account->Town);
                            $dcrSum->Amount = $bill->BusinessTax;
                            $dcrSum->Day = date('Y-m-d');
                            $dcrSum->NEACode = $bill->ServicePeriod;
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->DCRNumber = 'TP COLLECTION';
                            $dcrSum->ORNumber = $item->ORNumber;
                            $dcrSum->ReportDestination = 'COLLECTION';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
                            $dcrSum->save();

                            // GET BUSINESS TAX  FOR SALES
                            $dcrSum = new DCRSummaryTransactions;
                            $dcrSum->id = IDGenerator::generateIDandRandString();
                            $dcrSum->GLCode = '140-143-30';
                            $dcrSum->Amount = $bill->BusinessTax;
                            $dcrSum->Day = date('Y-m-d');
                            $dcrSum->NEACode = $bill->ServicePeriod;
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->DCRNumber = 'TP COLLECTION';
                            $dcrSum->ORNumber = $item->ORNumber;
                            $dcrSum->ReportDestination = 'SALES';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
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
                                $dcrSum->Teller = Auth::id();
                                $dcrSum->DCRNumber = 'TP COLLECTION';
                                $dcrSum->ORNumber = $item->ORNumber;
                                $dcrSum->ReportDestination = 'SALES';
                                $dcrSum->Office = env('APP_LOCATION');
                                $dcrSum->AccountNumber = $bill->AccountNumber;
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
                                    $dcrSum->Teller = Auth::id();
                                    $dcrSum->DCRNumber = 'TP COLLECTION';
                                    $dcrSum->ORNumber = $item->ORNumber;
                                    $dcrSum->ReportDestination = 'SALES';
                                    $dcrSum->Office = env('APP_LOCATION');
                                    $dcrSum->AccountNumber = $bill->AccountNumber;
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
                                    $dcrSum->Teller = Auth::id();
                                    $dcrSum->DCRNumber = 'TP COLLECTION';
                                    $dcrSum->ORNumber = $item->ORNumber;
                                    $dcrSum->ReportDestination = 'SALES';
                                    $dcrSum->Office = env('APP_LOCATION');
                                    $dcrSum->AccountNumber = $bill->AccountNumber;
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
                                $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                                $dcrSum->ORNumber = $item->ORNumber;
                                $dcrSum->ReportDestination = 'COLLECTION';
                                $dcrSum->Office = env('APP_LOCATION');
                                $dcrSum->AccountNumber = $bill->AccountNumber;
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
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'COLLECTION';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET UC-NPC Stranded Debt Sales
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '230-232-65';
                    $dcrSum->Amount = $bill->NPCStrandedDebt;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'SALES';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET STRANDED CONTRACT COST COLLECTION
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '140-142-92';
                    $dcrSum->Amount = $bill->StrandedContractCosts;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'COLLECTION';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET STRANDED CONTRACT COST SALES
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '230-232-62';
                    $dcrSum->Amount = $bill->StrandedContractCosts;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'SALES';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET FIT ALL COLLECTION
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '140-142-88';
                    $dcrSum->Amount = $bill->FeedInTariffAllowance;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'COLLECTION';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET FIT ALL SALES
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '230-232-64';
                    $dcrSum->Amount = $bill->FeedInTariffAllowance;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'SALES';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET UCME REDCI COLLECTION
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '140-142-89';
                    $dcrSum->Amount = $bill->MissionaryElectrificationREDCI;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'COLLECTION';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET UCME REDCI SALES
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '230-232-63';
                    $dcrSum->Amount = $bill->MissionaryElectrificationREDCI;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'SALES';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET GENCO
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '140-142-94';
                    $dcrSum->Amount = $bill->GenerationVAT;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'COLLECTION';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET TRANSCO
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '140-142-95';
                    $dcrSum->Amount = $bill->TransmissionVAT;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'COLLECTION';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET SYSLOSS VAT
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '140-142-96';
                    $dcrSum->Amount = $bill->SystemLossVAT;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'COLLECTION';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET DIST/OTHERS VAT
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '140-142-97';
                    $dcrSum->Amount = $bill->DistributionVAT;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'COLLECTION';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET GENVAT, TRANSVAT, SYSLOSSVAT SALES
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '170-184-40';
                    $dcrSum->Amount = DCRSummaryTransactions::getSalesGenTransSysLossVatAmount($bill);
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'SALES';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET DIST AND OTHERS SALES
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '250-255-00';
                    $dcrSum->Amount = DCRSummaryTransactions::getSalesDistOthersVatAmount($bill);
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'SALES';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET UCME COLLECTION
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '140-142-98';
                    $dcrSum->Amount = $bill->MissionaryElectrificationCharge;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'COLLECTION';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET UCME SALES
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '230-232-60';
                    $dcrSum->Amount = $bill->MissionaryElectrificationCharge;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'SALES';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET EWT 2%
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '140-160-00';
                    $dcrSum->Amount = $paidBill->Form2307TwoPercent;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'COLLECTION';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET EVAT 5%
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '140-170-00';
                    $dcrSum->Amount = $paidBill->Form2307FivePercent;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'COLLECTION';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET ENVIRONMENT CHARGE COLLECTION
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '140-142-99';
                    $dcrSum->Amount = $bill->EnvironmentalCharge;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'COLLECTION';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET ENVIRONMENT CHARGE SALES
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '230-232-90';
                    $dcrSum->Amount = $bill->EnvironmentalCharge;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'SALES';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET RFSC COLLECTION
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '140-142-93';
                    $dcrSum->Amount = $bill->RFSC;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'COLLECTION';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // GET RFSC SALES
                    $dcrSum = new DCRSummaryTransactions;
                    $dcrSum->id = IDGenerator::generateIDandRandString();
                    $dcrSum->GLCode = '211-211-10';
                    $dcrSum->Amount = $bill->RFSC;
                    $dcrSum->Day = date('Y-m-d');
                    $dcrSum->NEACode = $bill->ServicePeriod;
                    $dcrSum->Time = date('H:i:s');
                    $dcrSum->Teller = Auth::id();
                    $dcrSum->DCRNumber = 'TP COLLECTION';
                    $dcrSum->ORNumber = $item->ORNumber;
                    $dcrSum->ReportDestination = 'SALES';
                    $dcrSum->Office = env('APP_LOCATION');
                    $dcrSum->AccountNumber = $bill->AccountNumber;
                    $dcrSum->save();

                    // SAVE PAID BILL DETAILS
                    $paidBillDetails = new PaidBillsDetails;
                    $paidBillDetails->id = IDGenerator::generateIDandRandString();
                    $paidBillDetails->AccountNumber = $bill->AccountNumber;
                    $paidBillDetails->ORNumber = $item->ORNumber;
                    $paidBillDetails->Amount = $bill->NetAmount;
                    $paidBillDetails->PaymentUsed = 'Cash';
                    $paidBillDetails->UserId = Auth::id();
                    $paidBillDetails->save();
                }
            }
        }

        return redirect(route('paidBills.tcp-upload-validator', [$seriesNo]));
        // $paidBills = DB::table('Cashier_PaidBills')
        //     ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
        //     ->where('Cashier_PaidBills.Notes', $seriesNo)
        //     ->where('Cashier_PaidBills.Status', 'PENDING POST')
        //     ->whereRaw("Cashier_PaidBills.AccountNumber IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod=Cashier_PaidBills.ServicePeriod)")
        //     ->whereRaw("Cashier_PaidBills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills p WHERE p.AccountNumber=Cashier_PaidBills.AccountNumber AND ServicePeriod=Cashier_PaidBills.ServicePeriod AND Status IS NULL)")
        //     ->update(['Cashier_PaidBills.Status' => null, 'Cashier_PaidBills.PostingDate' => date('Y-m-d'), 'Cashier_PaidBills.PostingTime' => date('H:i:s')]);
    }

    public function thirdPartyCollectionDCR($source, $date, $seriesNo, $postingDate) {
        $data = DB::table('Cashier_DCRSummaryTransactions')
            ->where(function ($query) {
                $query->where('Cashier_DCRSummaryTransactions.ReportDestination', 'COLLECTION')
                    ->orWhere('Cashier_DCRSummaryTransactions.ReportDestination', 'BOTH');
            })  
            ->where('Day', $postingDate)
            ->whereRaw("AccountNumber IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE Source='THIRD-PARTY COLLECTION' AND ORDate='" . $date . "' AND ObjectSourceId='" . $source . "' AND Notes='" . $seriesNo . "' AND PostingDate='" . $postingDate . "' AND Status IS NULL AND ORNumber IS NOT NULL)")
            ->whereRaw("ORNumber IN (SELECT ORNumber FROM Cashier_PaidBills WHERE Source='THIRD-PARTY COLLECTION' AND ORDate='" . $date . "' AND ObjectSourceId='" . $source . "' AND Notes='" . $seriesNo . "' AND PostingDate='" . $postingDate . "' AND Status IS NULL AND ORNumber IS NOT NULL)")
            ->select('GLCode',
                DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                DB::raw("SUM(CAST(Amount AS DECIMAL(10,2))) AS Amount")
            )
            ->groupBy('GLCode')
            ->orderBy('GLCode')
            ->get();

        $powerBills = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereRaw("Source='THIRD-PARTY COLLECTION' AND ORDate='" . $date . "' AND ObjectSourceId='" . $source . "' AND Cashier_PaidBills.Notes='" . $seriesNo . "' AND PostingDate='" . $postingDate . "' AND Status IS NULL AND ORNumber IS NOT NULL AND Cashier_PaidBills.PaymentUsed LIKE '%Cash%'")
            ->select('Cashier_PaidBills.*', 
                // DB::raw("(SELECT SUM(TRY_CAST(Amount AS DECIMAL(25,4))) FROM Cashier_PaidBillsDetails WHERE ServicePeriod=Cashier_PaidBills.ServicePeriod AND AccountNumber=Cashier_PaidBills.AccountNumber AND PaymentUsed='Cash') AS CashPaid"),
                'Cashier_PaidBills.NetAmount AS CashPaid',
                'Billing_ServiceAccounts.ServiceAccountName', 
                'Billing_ServiceAccounts.OldAccountNo')
            ->orderBy('ORNumber')
            ->get();

        $checkPayments = DB::table('Cashier_PaidBillsDetails')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBillsDetails.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('Cashier_PaidBills', 'Cashier_PaidBillsDetails.ORNumber', '=', 'Cashier_PaidBills.ORNumber')
            ->whereRaw("Cashier_PaidBills.Source='THIRD-PARTY COLLECTION' AND Cashier_PaidBills.ORDate='" . $date . "' AND Cashier_PaidBills.ObjectSourceId='" . $source . "' AND Cashier_PaidBills.Notes='" . $seriesNo . "' AND PostingDate='" . $postingDate . "' AND Cashier_PaidBills.Status IS NULL AND Cashier_PaidBills.ORNumber IS NOT NULL")
            ->whereRaw("Cashier_PaidBillsDetails.PaymentUsed LIKE '%Check%'")
            ->select('Cashier_PaidBillsDetails.ORNumber',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Cashier_PaidBillsDetails.Amount',
                'Cashier_PaidBillsDetails.CheckNo',
                'Cashier_PaidBillsDetails.Bank',
                'Cashier_PaidBillsDetails.ServicePeriod',
                DB::raw("'POWER BILL' AS Source"))
            ->get();

        return view('/paid_bills/third_party_collection_dcr', [
            'source' => $source,
            'date' => $date,
            'data' => $data,
            'powerBills' => $powerBills,
            'checkPayments' => $checkPayments,
            'postingDate' => $postingDate,
            'seriesNo' => $seriesNo
        ]);
    }

    public function clearAllTcpUploads(Request $request) {
        $seriesNo = $request['SeriesNo'];

        PaidBills::where('Cashier_PaidBills.Notes', $seriesNo)
            ->whereNotNull('Status')
            ->delete();

        return response()->json('ok', 200);
    }

    public function cancelORAdmin(Request $request) {
        $paymentType = $request['PaymentType'];

        if ($paymentType == 'BILLS PAYMENT') {
            $paidBill = PaidBills::find($request['id']);

            if ($paidBill != null) {
                $paidBill->Status = 'CANCELLED';
                $paidBill->FiledBy = Auth::id();
                $paidBill->ApprovedBy = Auth::id();
                $paidBill->save();

                // DELETE DCR
                DCRSummaryTransactions::where('AccountNumber', $paidBill->AccountNumber)
                    ->where('Teller', $paidBill->Teller)
                    ->where('ORNumber', $paidBill->ORNumber)
                    ->delete();
            }
        } else {
            $transactionIndex = TransactionIndex::find($request['id']);

            if ($transactionIndex != null) {
                $transactionIndex->Status = 'CANCELLED';
                $transactionIndex->FiledBy = Auth::id();
                $transactionIndex->ApprovedBy = Auth::id();
                $transactionIndex->save();

                // DELETE DCR
                DCRSummaryTransactions::where('Teller', $transactionIndex->UserId)
                    ->where('ORNumber', $transactionIndex->ORNumber)
                    ->delete();
            }
        }

        return response()->json('ok', 200);
    }

    public function collectionSummaryReport(Request $request) {
        $from = $request['From'];
        $to = $request['To'];
        $town = $request['Town'];

        $latestRate = Rates::orderByDesc('ServicePeriod')->first();
        $currentPeriod = $latestRate != null ? $latestRate->ServicePeriod : date('Y-m-01');
        $previousPeriod = date('Y-m-01', strtotime($currentPeriod . ' -1 month'));

        if ($from != null && $to != null) {
            $data = DB::table('Cashier_PaidBills')
                ->leftJoin('Billing_Bills', function($join) {
                    $join->on('Cashier_PaidBills.AccountNumber', '=', 'Billing_Bills.AccountNumber')
                        ->on('Cashier_PaidBills.ServicePeriod', '=', 'Billing_Bills.ServicePeriod');
                })
                ->whereRaw("Cashier_PaidBills.ORDate BETWEEN '" . $from . "' AND '" . $to . "' 
                    AND (Cashier_PaidBills.Status IS NULL OR Cashier_PaidBills.Status='Application') 
                    AND Cashier_PaidBills.AccountNumber IS NOT NULL 
                    AND Cashier_PaidBills.AccountNumber LIKE '" . $town . "%'")
                ->select(
                    'Cashier_PaidBills.ORDate',
                    DB::raw("(SELECT COUNT(pb.id) FROM Cashier_PaidBills pb WHERE ServicePeriod < '" . $previousPeriod . "' AND ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL AND AccountNumber LIKE '" . $town . "%' AND pb.ORDate=Cashier_PaidBills.ORDate) AS ArrearsCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(pb.NetAmount AS DECIMAL(15,2))) FROM Cashier_PaidBills pb WHERE ServicePeriod < '" . $previousPeriod . "' AND ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL AND AccountNumber LIKE '" . $town . "%' AND pb.ORDate=Cashier_PaidBills.ORDate) AS ArrearsTotal"),
                    DB::raw("(SELECT COUNT(pb.id) FROM Cashier_PaidBills pb WHERE ServicePeriod='" . $previousPeriod . "' AND ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL AND AccountNumber LIKE '" . $town . "%' AND pb.ORDate=Cashier_PaidBills.ORDate) AS PreviousCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(pb.NetAmount AS DECIMAL(15,2))) FROM Cashier_PaidBills pb WHERE ServicePeriod='" . $previousPeriod . "' AND ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL AND AccountNumber LIKE '" . $town . "%' AND pb.ORDate=Cashier_PaidBills.ORDate) AS PreviousTotal"),
                    DB::raw("(SELECT COUNT(pb.id) FROM Cashier_PaidBills pb WHERE ServicePeriod='" . $currentPeriod . "' AND ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL AND AccountNumber LIKE '" . $town . "%' AND pb.ORDate=Cashier_PaidBills.ORDate) AS CurrentCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(pb.NetAmount AS DECIMAL(15,2))) FROM Cashier_PaidBills pb WHERE ServicePeriod='" . $currentPeriod . "' AND ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL AND AccountNumber LIKE '" . $town . "%' AND pb.ORDate=Cashier_PaidBills.ORDate) AS CurrentTotal"),
                    DB::raw("SUM(TRY_CAST(Cashier_PaidBills.Surcharge AS DECIMAL(15,2))) AS Surcharge"),
                    DB::raw("(SELECT SUM(TRY_CAST(Total AS DECIMAL(15,2))) FROM Cashier_TransactionIndex WHERE ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND Status IS NULL AND ORDate=Cashier_PaidBills.ORDate) AS Misc"),
                    DB::raw("SUM(TRY_CAST(Billing_Bills.GenerationVat AS DECIMAL(15,2))) AS GenerationVat"),
                    DB::raw("SUM(TRY_CAST(Billing_Bills.TransmissionVat AS DECIMAL(15,2))) AS TransmissionVat"),
                    DB::raw("SUM(TRY_CAST(Billing_Bills.SystemLossVat AS DECIMAL(15,2))) AS SystemLossVat"),
                    DB::raw("SUM(TRY_CAST(Billing_Bills.DistributionVat AS DECIMAL(15,2))) AS DistributionVat"),
                    DB::raw("(SELECT SUM(TRY_CAST(VAT AS DECIMAL(15,2))) FROM Cashier_TransactionIndex WHERE ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND Status IS NULL AND ORDate=Cashier_PaidBills.ORDate) AS MiscVat"),
                    DB::raw("SUM(TRY_CAST(Cashier_PaidBills.Form2307FivePercent AS DECIMAL(15,2))) AS FivePercent"),
                    DB::raw("SUM(TRY_CAST(Cashier_PaidBills.Form2307TwoPercent AS DECIMAL(15,2))) AS TwoPercent"),
                    DB::raw("SUM(TRY_CAST(Billing_Bills.RFSC AS DECIMAL(15,2))) AS RFSC"),
                    DB::raw("SUM(TRY_CAST(Billing_Bills.EnvironmentalCharge AS DECIMAL(15,2))) AS EnvironmentalCharge"),
                    DB::raw("SUM(TRY_CAST(Billing_Bills.MissionaryElectrificationCharge AS DECIMAL(15,2))) AS MissionaryElectrification"),
                )
                ->groupBy('ORDate')
                ->orderBy('ORDate')
                ->get();
        } else {
            $data = [];
        }
        
        return view('/paid_bills/collection_summary_report', [
            'towns' => Towns::all(),
            'data' => $data,
        ]);
    }

    public function printCollectionSummaryReport($from, $to, $town) {
        $latestRate = Rates::orderByDesc('ServicePeriod')->first();
        $currentPeriod = $latestRate != null ? $latestRate->ServicePeriod : date('Y-m-01');
        $previousPeriod = date('Y-m-01', strtotime($currentPeriod . ' -1 month'));

        if ($from != null && $to != null) {
            $data = DB::table('Cashier_PaidBills')
                ->leftJoin('Billing_Bills', function($join) {
                    $join->on('Cashier_PaidBills.AccountNumber', '=', 'Billing_Bills.AccountNumber')
                        ->on('Cashier_PaidBills.ServicePeriod', '=', 'Billing_Bills.ServicePeriod');
                })
                ->whereRaw("Cashier_PaidBills.ORDate BETWEEN '" . $from . "' AND '" . $to . "' 
                    AND (Cashier_PaidBills.Status IS NULL OR Cashier_PaidBills.Status='Application') 
                    AND Cashier_PaidBills.AccountNumber IS NOT NULL 
                    AND Cashier_PaidBills.AccountNumber LIKE '" . $town . "%'")
                ->select(
                    'Cashier_PaidBills.ORDate',
                    DB::raw("(SELECT COUNT(pb.id) FROM Cashier_PaidBills pb WHERE ServicePeriod < '" . $previousPeriod . "' AND ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL AND AccountNumber LIKE '" . $town . "%' AND pb.ORDate=Cashier_PaidBills.ORDate) AS ArrearsCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(pb.NetAmount AS DECIMAL(15,2))) FROM Cashier_PaidBills pb WHERE ServicePeriod < '" . $previousPeriod . "' AND ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL AND AccountNumber LIKE '" . $town . "%' AND pb.ORDate=Cashier_PaidBills.ORDate) AS ArrearsTotal"),
                    DB::raw("(SELECT COUNT(pb.id) FROM Cashier_PaidBills pb WHERE ServicePeriod='" . $previousPeriod . "' AND ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL AND AccountNumber LIKE '" . $town . "%' AND pb.ORDate=Cashier_PaidBills.ORDate) AS PreviousCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(pb.NetAmount AS DECIMAL(15,2))) FROM Cashier_PaidBills pb WHERE ServicePeriod='" . $previousPeriod . "' AND ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL AND AccountNumber LIKE '" . $town . "%' AND pb.ORDate=Cashier_PaidBills.ORDate) AS PreviousTotal"),
                    DB::raw("(SELECT COUNT(pb.id) FROM Cashier_PaidBills pb WHERE ServicePeriod='" . $currentPeriod . "' AND ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL AND AccountNumber LIKE '" . $town . "%' AND pb.ORDate=Cashier_PaidBills.ORDate) AS CurrentCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(pb.NetAmount AS DECIMAL(15,2))) FROM Cashier_PaidBills pb WHERE ServicePeriod='" . $currentPeriod . "' AND ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL AND AccountNumber LIKE '" . $town . "%' AND pb.ORDate=Cashier_PaidBills.ORDate) AS CurrentTotal"),
                    DB::raw("SUM(TRY_CAST(Cashier_PaidBills.Surcharge AS DECIMAL(15,2))) AS Surcharge"),
                    DB::raw("(SELECT SUM(TRY_CAST(Total AS DECIMAL(15,2))) FROM Cashier_TransactionIndex WHERE ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND Status IS NULL AND ORDate=Cashier_PaidBills.ORDate) AS Misc"),
                    DB::raw("SUM(TRY_CAST(Billing_Bills.GenerationVat AS DECIMAL(15,2))) AS GenerationVat"),
                    DB::raw("SUM(TRY_CAST(Billing_Bills.TransmissionVat AS DECIMAL(15,2))) AS TransmissionVat"),
                    DB::raw("SUM(TRY_CAST(Billing_Bills.SystemLossVat AS DECIMAL(15,2))) AS SystemLossVat"),
                    DB::raw("SUM(TRY_CAST(Billing_Bills.DistributionVat AS DECIMAL(15,2))) AS DistributionVat"),
                    DB::raw("(SELECT SUM(TRY_CAST(VAT AS DECIMAL(15,2))) FROM Cashier_TransactionIndex WHERE ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND Status IS NULL AND ORDate=Cashier_PaidBills.ORDate) AS MiscVat"),
                    DB::raw("SUM(TRY_CAST(Cashier_PaidBills.Form2307FivePercent AS DECIMAL(15,2))) AS FivePercent"),
                    DB::raw("SUM(TRY_CAST(Cashier_PaidBills.Form2307TwoPercent AS DECIMAL(15,2))) AS TwoPercent"),
                    DB::raw("SUM(TRY_CAST(Billing_Bills.RFSC AS DECIMAL(15,2))) AS RFSC"),
                    DB::raw("SUM(TRY_CAST(Billing_Bills.EnvironmentalCharge AS DECIMAL(15,2))) AS EnvironmentalCharge"),
                    DB::raw("SUM(TRY_CAST(Billing_Bills.MissionaryElectrificationCharge AS DECIMAL(15,2))) AS MissionaryElectrification"),
                )
                ->groupBy('ORDate')
                ->orderBy('ORDate')
                ->get();
        } else {
            $data = [];
        }

        return view('/paid_bills/print_collection_summary_report', [
            'data' => $data,
            'from' => $from,
            'to' => $to,
            'towns' => Towns::find($town)
        ]);
    }

    public function agingReport(Request $request) {
        $town = $request['Town'];
        $asOf = $request['AsOf'];
    
        if ($town != null) {
            $data = DB::table('Billing_ServiceAccounts')
                ->whereRaw("Town='" . $town . "'")
                ->select(
                    'AreaCode',
                    'Town',
                    DB::raw("(SELECT COUNT(DISTINCT b.AccountNumber) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -90, '". $asOf ."') AND '". $asOf ."') AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS ConsCountNinetyDays"),
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -90, '". $asOf ."') AND '". $asOf ."') AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsCountNinetyDays"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -90, '". $asOf ."') AND '". $asOf ."') AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsAmountNinetyDays"),

                    DB::raw("(SELECT COUNT(DISTINCT b.AccountNumber) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -91, '". $asOf ."') AND DATEADD(day, -180, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS ConsCount180Days"),
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -91, '". $asOf ."') AND DATEADD(day, -180, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsCount180Days"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -91, '". $asOf ."') AND DATEADD(day, -180, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsAmount180Days"),

                    DB::raw("(SELECT COUNT(DISTINCT b.AccountNumber) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -181, '". $asOf ."') AND DATEADD(day, -240, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS ConsCount240Days"),
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -181, '". $asOf ."') AND DATEADD(day, -240, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsCount240Days"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -181, '". $asOf ."') AND DATEADD(day, -240, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsAmount240Days"),

                    DB::raw("(SELECT COUNT(DISTINCT b.AccountNumber) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -241, '". $asOf ."') AND DATEADD(day, -360, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS ConsCount360Days"),
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -241, '". $asOf ."') AND DATEADD(day, -360, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsCount360Days"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -241, '". $asOf ."') AND DATEADD(day, -360, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsAmount360Days"),

                    DB::raw("(SELECT COUNT(DISTINCT b.AccountNumber) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) < DATEADD(day, -361, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS ConsCountOver360Days"),
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) < DATEADD(day, -361, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsCountOver360Days"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) < DATEADD(day, -361, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsAmountOver360Days"),

                    DB::raw("(SELECT COUNT(DISTINCT b.AccountNumber) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS ConsCountBooksTotal"),
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsCountBooksTotal"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsAmountBooksTotal"),

                    DB::raw("(SELECT COUNT(DISTINCT b.AccountNumber) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS TotalCons"),
                )
                ->groupBy('Town', 'AreaCode')
                ->orderBy('AreaCode')
                ->get();
        } else {
            $data = [];
        }

        return view('/paid_bills/aging_report', [
            'towns' => Towns::all(),
            'data' => $data,
        ]);
    }

    public function printAgingReport($town, $asOf) {
        if ($town != null) {
            $data = DB::table('Billing_ServiceAccounts')
                ->whereRaw("Town='" . $town . "'")
                ->select(
                    'AreaCode',
                    'Town',
                    DB::raw("(SELECT COUNT(DISTINCT b.AccountNumber) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -90, '". $asOf ."') AND '". $asOf ."') AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS ConsCountNinetyDays"),
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -90, '". $asOf ."') AND '". $asOf ."') AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsCountNinetyDays"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -90, '". $asOf ."') AND '". $asOf ."') AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsAmountNinetyDays"),

                    DB::raw("(SELECT COUNT(DISTINCT b.AccountNumber) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -91, '". $asOf ."') AND DATEADD(day, -180, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS ConsCount180Days"),
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -91, '". $asOf ."') AND DATEADD(day, -180, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsCount180Days"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -91, '". $asOf ."') AND DATEADD(day, -180, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsAmount180Days"),

                    DB::raw("(SELECT COUNT(DISTINCT b.AccountNumber) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -181, '". $asOf ."') AND DATEADD(day, -240, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS ConsCount240Days"),
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -181, '". $asOf ."') AND DATEADD(day, -240, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsCount240Days"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -181, '". $asOf ."') AND DATEADD(day, -240, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsAmount240Days"),

                    DB::raw("(SELECT COUNT(DISTINCT b.AccountNumber) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -241, '". $asOf ."') AND DATEADD(day, -360, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS ConsCount360Days"),
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -241, '". $asOf ."') AND DATEADD(day, -360, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsCount360Days"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) BETWEEN DATEADD(day, -241, '". $asOf ."') AND DATEADD(day, -360, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsAmount360Days"),

                    DB::raw("(SELECT COUNT(DISTINCT b.AccountNumber) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) < DATEADD(day, -361, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS ConsCountOver360Days"),
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) < DATEADD(day, -361, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsCountOver360Days"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE (CAST(b.created_at AS DATE) < DATEADD(day, -361, '". $asOf ."')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsAmountOver360Days"),

                    DB::raw("(SELECT COUNT(DISTINCT b.AccountNumber) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS ConsCountBooksTotal"),
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsCountBooksTotal"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS BillsAmountBooksTotal"),

                    DB::raw("(SELECT COUNT(DISTINCT b.AccountNumber) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber 
                            WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.Town=Billing_ServiceAccounts.Town AND b.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ORDate <= '" . $asOf . "' AND AccountNumber=b.AccountNumber AND (Status IS NULL OR Status='Application') AND ServicePeriod=b.ServicePeriod)) AS TotalCons"),
                )
                ->groupBy('Town', 'AreaCode')
                ->orderBy('AreaCode')
                ->get();
        } else {
            $data = [];
        }

        return view('/paid_bills/print_aging_report', [
            'towns' => Towns::find($town),
            'data' => $data,
        ]);
    }

    public function thirdPartyReport(Request $request) {
        $day = $request['Day'];
        $town = $request['Town'];

        if ($day != null && $town != null) {
            if ($town == 'All') {
                $data = DB::table('Cashier_PaidBills')
                    ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')                 
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('users', 'Cashier_PaidBills.Teller', '=', 'users.id')
                    ->whereRaw("Source='THIRD-PARTY COLLECTION' AND ORDate='" . $day . "' AND Status IS NULL AND ORNumber IS NOT NULL")
                    ->select('Cashier_PaidBills.*', 
                        'Billing_ServiceAccounts.ServiceAccountName', 
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.Purok',
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'users.name')
                    ->orderBy('OldAccountNo')
                    ->get();
            } else {
                $data = DB::table('Cashier_PaidBills')
                    ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')                 
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('users', 'Cashier_PaidBills.Teller', '=', 'users.id')
                    ->whereRaw("Source='THIRD-PARTY COLLECTION' AND ORDate='" . $day . "' AND Status IS NULL AND ORNumber IS NOT NULL AND Billing_ServiceAccounts.Town='" . $town . "'")
                    ->select('Cashier_PaidBills.*', 
                        'Billing_ServiceAccounts.ServiceAccountName', 
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.Purok',
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'users.name')
                    ->orderBy('OldAccountNo')
                    ->get();
            }           
        } else {
            $data = [];
        }

        return view('/paid_bills/third_party_report', [
            'data' => $data,
            'towns' => Towns::all(),
        ]);
    }

    public function printThirdPartyReport($day, $town) {
        if ($day != null && $town != null) {
            if ($town == 'All') {
                $data = DB::table('Cashier_PaidBills')
                    ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')                 
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('users', 'Cashier_PaidBills.Teller', '=', 'users.id')
                    ->whereRaw("Source='THIRD-PARTY COLLECTION' AND ORDate='" . $day . "' AND Status IS NULL AND ORNumber IS NOT NULL")
                    ->select('Cashier_PaidBills.*', 
                        'Billing_ServiceAccounts.ServiceAccountName', 
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.Purok',
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'users.name')
                    ->orderBy('OldAccountNo')
                    ->get();
            } else {
                $data = DB::table('Cashier_PaidBills')
                    ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')                 
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('users', 'Cashier_PaidBills.Teller', '=', 'users.id')
                    ->whereRaw("Source='THIRD-PARTY COLLECTION' AND ORDate='" . $day . "' AND Status IS NULL AND ORNumber IS NOT NULL AND Billing_ServiceAccounts.Town='" . $town . "'")
                    ->select('Cashier_PaidBills.*', 
                        'Billing_ServiceAccounts.ServiceAccountName', 
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.Purok',
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'users.name')
                    ->orderBy('OldAccountNo')
                    ->get();
            }           
        } else {
            $data = [];
        }

        return view('/paid_bills/print_third_party_report', [
            'data' => $data,
            'day' => $day,
            'town' => $town,
        ]);
    }

    public function thirdPartyAPIConsole(Request $request) {
        $date = $request['Day'];

        $transacted = DB::table('Cashier_PaidBills')
            ->whereRaw("Source='THIRD-PARTY COLLECTION API' AND ORDate='" . $date . "'")
            ->select('ObjectSourceId',
                'ORDate',
                DB::raw("(SELECT COUNT(p.id) FROM Cashier_PaidBills p WHERE p.Source='THIRD-PARTY COLLECTION API' AND p.ORDate='" . $date . "' AND p.Status IS NULL AND p.ObjectSourceId=Cashier_PaidBills.ObjectSourceId) AS Posted"),
                DB::raw("(SELECT SUM(TRY_CAST(p.NetAmount AS Decimal(10,2))) FROM Cashier_PaidBills p WHERE p.Source='THIRD-PARTY COLLECTION API' AND p.ORDate='" . $date . "' AND p.Status IS NULL AND p.ObjectSourceId=Cashier_PaidBills.ObjectSourceId) AS PostedSum"),
                DB::raw("COUNT(id) AS TotalPayments"),
                DB::raw("SUM(TRY_CAST(NetAmount AS Decimal(10,2))) AS TotalPaymentsSum"),
                DB::raw("(SELECT COUNT(p.id) FROM Cashier_TransactionIndex p WHERE p.Source='THIRD-PARTY COLLECTION API' AND p.ORDate='" . $date . "' AND p.Status IS NULL AND p.Notes=Cashier_PaidBills.ObjectSourceId) AS OthersCount"),
                DB::raw("(SELECT SUM(TRY_CAST(p.Total AS Decimal(10,2))) FROM Cashier_TransactionIndex p WHERE p.Source='THIRD-PARTY COLLECTION API' AND p.ORDate='" . $date . "' AND p.Status IS NULL AND p.Notes=Cashier_PaidBills.ObjectSourceId) AS OthersSum"),
            )
            ->groupBy('ObjectSourceId', 'ORDate')
            ->orderByDesc('ObjectSourceId')
            ->get();

        return view('/paid_bills/third_party_collection_api', [
            'date' => $date,
            'transacted' => $transacted,

        ]);
    }

    public function thirdPartyCollectionAPIDCR($source, $date) {
        $data = DB::table('Cashier_DCRSummaryTransactions')
            ->where(function ($query) {
                $query->where('Cashier_DCRSummaryTransactions.ReportDestination', 'COLLECTION')
                    ->orWhere('Cashier_DCRSummaryTransactions.ReportDestination', 'BOTH');
            })  
            ->where('Day', $date)
            // ->whereRaw("AccountNumber IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE Source='THIRD-PARTY COLLECTION API' AND ORDate='" . $date . "' AND ObjectSourceId='" . $source . "'  AND Status IS NULL AND ORNumber IS NOT NULL)")
            // ->whereRaw("ORNumber IN (SELECT ORNumber FROM Cashier_PaidBills WHERE Source='THIRD-PARTY COLLECTION API' AND ORDate='" . $date . "' AND ObjectSourceId='" . $source . "'  AND Status IS NULL AND ORNumber IS NOT NULL)")
            ->whereRaw("DCRNumber='API COLLECTION' AND Description='" . $source . "'")
            ->select('GLCode',
                DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                DB::raw("SUM(CAST(Amount AS DECIMAL(10,2))) AS Amount")
            )
            ->groupBy('GLCode')
            ->orderBy('GLCode')
            ->get();

        $powerBills = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereRaw("Source='THIRD-PARTY COLLECTION API' AND ORDate='" . $date . "' AND ObjectSourceId='" . $source . "' AND Status IS NULL AND ORNumber IS NOT NULL AND Cashier_PaidBills.PaymentUsed LIKE '%Cash%'")
            ->select('Cashier_PaidBills.*', 
                DB::raw("(SELECT SUM(CAST(Amount AS DECIMAL(10,2))) FROM Cashier_PaidBillsDetails WHERE ORNumber=Cashier_PaidBills.ORNumber AND PaymentUsed='Cash' AND AccountNumber=Cashier_PaidBills.AccountNumber) AS CashPaid"),
                'Billing_ServiceAccounts.ServiceAccountName', 
                'Billing_ServiceAccounts.OldAccountNo')
            ->orderBy('ORNumber')
            ->get();

        $checkPayments = DB::table('Cashier_PaidBillsDetails')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBillsDetails.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('Cashier_PaidBills', 'Cashier_PaidBillsDetails.ORNumber', '=', 'Cashier_PaidBills.ORNumber')
            ->whereRaw("Cashier_PaidBills.Source='THIRD-PARTY COLLECTION API' AND Cashier_PaidBills.ORDate='" . $date . "' AND Cashier_PaidBills.ObjectSourceId='" . $source . "' AND Cashier_PaidBills.Status IS NULL AND Cashier_PaidBills.ORNumber IS NOT NULL")
            ->whereRaw("Cashier_PaidBillsDetails.PaymentUsed LIKE '%Check%'")
            ->select('Cashier_PaidBillsDetails.ORNumber',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Cashier_PaidBillsDetails.Amount',
                'Cashier_PaidBillsDetails.CheckNo',
                'Cashier_PaidBillsDetails.Bank',
                DB::raw("'POWER BILL' AS Source"))
            ->get();

        $nonPowerBills = DB::table('Cashier_TransactionDetails')
            ->leftJoin('Cashier_TransactionIndex', 'Cashier_TransactionDetails.TransactionIndexId', '=', 'Cashier_TransactionIndex.id')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_TransactionIndex.AccountNumber', '=', 'Billing_ServiceAccounts.id')         
            ->whereRaw("Cashier_TransactionIndex.ORDate='" . $date . "' AND Cashier_TransactionIndex.Notes='" . $source . "'")
            ->whereNull('Cashier_TransactionIndex.Status')
            ->select('Cashier_TransactionIndex.ORNumber',
                'Cashier_TransactionDetails.Total',
                'Cashier_TransactionDetails.Particular',
                'Cashier_TransactionIndex.AccountNumber',
                'Cashier_TransactionIndex.id',
                'Cashier_TransactionIndex.PayeeName',
                'Cashier_TransactionIndex.PaymentDetails',
                'Cashier_TransactionDetails.AccountCode',
                'Cashier_TransactionIndex.CheckNo',
                'Cashier_TransactionIndex.Bank',
                'Billing_ServiceAccounts.OldAccountNo',
                'Cashier_TransactionIndex.PayeeName')
            ->orderBy('Cashier_TransactionDetails.TransactionIndexId')
            ->get();

        return view('/paid_bills/third_party_collection_api_dcr', [
            'source' => $source,
            'date' => $date,
            'data' => $data,
            'powerBills' => $powerBills,
            'checkPayments' => $checkPayments,
            'nonPowerBills' => $nonPowerBills,
        ]);
    }

    public function clearDeposit(Request $request) {
        $accountNumber = $request['AccountNumber'];

        $balance = PrePaymentBalance::where('AccountNumber', $accountNumber)->first();

        PrePaymentBalance::where('AccountNumber', $accountNumber)
            ->update(['Balance' => '0']);

        // ADD TRANSACTION HISTORY
        $transHistory = new PrePaymentTransHistory;
        $transHistory->id = IDGenerator::generateIDandRandString();
        $transHistory->AccountNumber = $accountNumber;
        $transHistory->Method = 'DEDUCT';
        $transHistory->Amount = $balance->Balance;
        $transHistory->UserId = Auth::id(); 
        $transHistory->Notes = 'Deposit/Prepayment Cleared/Removed';
        $transHistory->save();

        return response()->json('ok', 200);
    }

    public function fixThirdPartyDCR(Request $request) {
        $postingDate = $request['PostingDate'];
        $date = $request['Date'];
        $source = $request['Source'];
        $seriesNo = $request['SeriesNo'];
        $figure = $request['Figure'];

        $data = DB::table('Cashier_DCRSummaryTransactions')
            ->where(function ($query) {
                $query->where('Cashier_DCRSummaryTransactions.ReportDestination', 'COLLECTION')
                    ->orWhere('Cashier_DCRSummaryTransactions.ReportDestination', 'BOTH');
            })  
            ->where('Day', $postingDate)
            ->whereRaw("AccountNumber IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE Source='THIRD-PARTY COLLECTION' AND ORDate='" . $date . "' AND ObjectSourceId='" . $source . "' AND Notes='" . $seriesNo . "' AND PostingDate='" . $postingDate . "' AND Status IS NULL AND ORNumber IS NOT NULL)")
            ->whereRaw("ORNumber IN (SELECT ORNumber FROM Cashier_PaidBills WHERE Source='THIRD-PARTY COLLECTION' AND ORDate='" . $date . "' AND ObjectSourceId='" . $source . "' AND Notes='" . $seriesNo . "' AND PostingDate='" . $postingDate . "' AND Status IS NULL AND ORNumber IS NOT NULL)")
            ->select('Teller',
                'Office',
                'ORNumber',
                'AccountNumber',
            )
            ->orderBy('GLCode')
            ->first();

        if ($data != null) {
            $account = ServiceAccounts::find($data->AccountNumber);

            $dcrSum = new DCRSummaryTransactions;
            $dcrSum->id = IDGenerator::generateIDandRandString();
            $dcrSum->GLCode = DCRSummaryTransactions::getARConsumers($account->Town );
            $dcrSum->Amount = $figure;
            $dcrSum->Day = $postingDate;
            $dcrSum->Time = date('H:i:s');
            $dcrSum->Teller = $data->Teller;
            $dcrSum->ORNumber = $data->ORNumber;
            $dcrSum->ReportDestination = 'BOTH';
            $dcrSum->Office = $data->Office;
            $dcrSum->AccountNumber = $data->AccountNumber;
            $dcrSum->save();
        }
        
        return response()->json('ok', 200);
    }
}

