<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionIndexRequest;
use App\Http\Requests\UpdateTransactionIndexRequest;
use App\Repositories\TransactionIndexRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceConnectionTotalPayments;
use App\Models\ServiceConnections;
use App\Models\TransactionDetails;
use App\Models\TransacionPaymentDetails;
use App\Models\TransactionIndex;
use App\Models\ArrearsLedgerDistribution;
use App\Models\Collectibles;
use App\Models\ServiceAccounts;
use App\Models\BillsOfMaterialsSummary;
use App\Models\IDGenerator;
use App\Models\AccountPayables;
use App\Models\ORAssigning;
use App\Models\Bills;
use App\Models\PaidBills;
use App\Models\PaidBillsDetails;
use App\Models\Tickets;
use App\Models\TicketLogs;
use App\Models\DCRSummaryTransactions;
use Flash;
use Response;

class TransactionIndexController extends AppBaseController
{
    /** @var  TransactionIndexRepository */
    private $transactionIndexRepository;

    public function __construct(TransactionIndexRepository $transactionIndexRepo)
    {
        $this->middleware('auth');
        $this->transactionIndexRepository = $transactionIndexRepo;
    }

    /**
     * Display a listing of the TransactionIndex.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $transactionIndices = $this->transactionIndexRepository->all();

        return view('transaction_indices.index')
            ->with('transactionIndices', $transactionIndices);
    }

    /**
     * Show the form for creating a new TransactionIndex.
     *
     * @return Response
     */
    public function create()
    {
        return view('transaction_indices.create');
    }

    /**
     * Store a newly created TransactionIndex in storage.
     *
     * @param CreateTransactionIndexRequest $request
     *
     * @return Response
     */
    public function store(CreateTransactionIndexRequest $request)
    {
        $input = $request->all();

        $transactionIndex = $this->transactionIndexRepository->create($input);

        Flash::success('Transaction Index saved successfully.');

        return redirect(route('transactionIndices.index'));
    }

    /**
     * Display the specified TransactionIndex.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $transactionIndex = $this->transactionIndexRepository->find($id);

        if (empty($transactionIndex)) {
            Flash::error('Transaction Index not found');

            return redirect(route('transactionIndices.index'));
        }

        return view('transaction_indices.show')->with('transactionIndex', $transactionIndex);
    }

    /**
     * Show the form for editing the specified TransactionIndex.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $transactionIndex = $this->transactionIndexRepository->find($id);

        if (empty($transactionIndex)) {
            Flash::error('Transaction Index not found');

            return redirect(route('transactionIndices.index'));
        }

        return view('transaction_indices.edit')->with('transactionIndex', $transactionIndex);
    }

    /**
     * Update the specified TransactionIndex in storage.
     *
     * @param int $id
     * @param UpdateTransactionIndexRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTransactionIndexRequest $request)
    {
        $transactionIndex = $this->transactionIndexRepository->find($id);

        if (empty($transactionIndex)) {
            Flash::error('Transaction Index not found');

            return redirect(route('transactionIndices.index'));
        }

        $transactionIndex = $this->transactionIndexRepository->update($request->all(), $id);

        Flash::success('Transaction Index updated successfully.');

        return redirect(route('transactionIndices.index'));
    }

    /**
     * Remove the specified TransactionIndex from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $transactionIndex = $this->transactionIndexRepository->find($id);

        if (empty($transactionIndex)) {
            Flash::error('Transaction Index not found');

            return redirect(route('transactionIndices.index'));
        }

        $this->transactionIndexRepository->delete($id);

        Flash::success('Transaction Index deleted successfully.');

        return redirect(route('transactionIndices.index'));
    }

    public function serviceConnectionCollection() {
        $applications = DB::table('CRM_ServiceConnections')
            // ->where('Status', 'Approved')
            ->whereIn('Status', ['Approved', 'For Inspection'])
            ->whereNull("Trash")
            ->whereNull('ORNumber')
            ->whereNull('ORDate')
            ->get();

        $orAssignedLast = ORAssigning::whereRaw("UserId='" . Auth::id() . "'")
            ->orderByDesc('created_at')
            ->first();

        return view('/transaction_indices/service_connection_collection', [
            'applications' => $applications,
            'orAssignedLast' => $orAssignedLast,
        ]);
    }

    public function getPayableDetails(Request $request) {
        $particularPayments = DB::table('CRM_ServiceConnectionParticularPaymentsTransactions')
                    ->leftJoin('CRM_ServiceConnectionPaymentParticulars', 'CRM_ServiceConnectionParticularPaymentsTransactions.Particular', '=', 'CRM_ServiceConnectionPaymentParticulars.id')
                    ->select('CRM_ServiceConnectionParticularPaymentsTransactions.id',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Amount',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Vat',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Total',
                            'CRM_ServiceConnectionPaymentParticulars.Particular')
                    ->where('CRM_ServiceConnectionParticularPaymentsTransactions.ServiceConnectionId', $request['svcId'])
                    ->get();

        return response()->json($particularPayments, 200);
    }

    public function getPowerLoadPayables(Request $request) {
        $powerLoadPayables = BillsOfMaterialsSummary::where('ServiceConnectionId', $request['ServiceConnectionId'])->first();

        if ($powerLoadPayables != null) {
            return response()->json($powerLoadPayables, 200);
        } else {
            return response()->json('No payables found', 404);
        }        
    }

    public function getPayableTotal(Request $request) {
        $totalTransactions = DB::table('CRM_ServiceConnectionTotalPayments')
            ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionTotalPayments.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->where('CRM_ServiceConnectionTotalPayments.ServiceConnectionId', $request['svcId'])
            ->select('CRM_ServiceConnectionTotalPayments.*',
                'CRM_ServiceConnections.ServiceAccountName',
                'CRM_Towns.Town as Town',
                'CRM_Barangays.Barangay as Barangay',)
            ->first();

        return response()->json($totalTransactions, 200);
    }

    public function saveAndPrintORServiceConnections(Request $request) {
        $particularPayments = DB::table('CRM_ServiceConnectionParticularPaymentsTransactions')
            ->leftJoin('CRM_ServiceConnectionPaymentParticulars', 'CRM_ServiceConnectionParticularPaymentsTransactions.Particular', '=', 'CRM_ServiceConnectionPaymentParticulars.id')
            ->select('CRM_ServiceConnectionParticularPaymentsTransactions.id',
                    'CRM_ServiceConnectionParticularPaymentsTransactions.Amount',
                    'CRM_ServiceConnectionParticularPaymentsTransactions.Vat',
                    'CRM_ServiceConnectionParticularPaymentsTransactions.Total',
                    'CRM_ServiceConnectionPaymentParticulars.Particular',
                    'CRM_ServiceConnectionPaymentParticulars.AccountNumber')
            ->where('CRM_ServiceConnectionParticularPaymentsTransactions.ServiceConnectionId', $request['svcId'])
            ->get();

        $totalTransactions = DB::table('CRM_ServiceConnectionTotalPayments')
            ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionTotalPayments.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->where('CRM_ServiceConnectionTotalPayments.ServiceConnectionId', $request['svcId'])
            ->select('CRM_ServiceConnectionTotalPayments.*',
                'CRM_ServiceConnections.ServiceAccountName',
                'CRM_Towns.Town as Town',
                'CRM_Barangays.Barangay as Barangay',)
            ->first();

        // SAVE TRANSACTION
        $id = IDGenerator::generateID();

        $transactionIndex = new TransactionIndex;
        $transactionIndex->id = $id;
        $transactionIndex->TransactionNumber = env('APP_LOCATION') . '-' . $id;
        $transactionIndex->PaymentTitle = $totalTransactions->ServiceAccountName;
        $transactionIndex->PaymentDetails = "Service Connection Application Payment of " . $totalTransactions->ServiceAccountName;
        $transactionIndex->ORNumber = $request['ORNumber'];
        $transactionIndex->ORDate = date('Y-m-d');
        $transactionIndex->SubTotal = $totalTransactions->SubTotal;
        $transactionIndex->VAT = $totalTransactions->TotalVat;
        $transactionIndex->Total = $request['Total'];
        $transactionIndex->ServiceConnectionId = $request['svcId'];
        $transactionIndex->Source = $request['LoadCategory'] == 'above 5kVa' ? "Service Connection Application w Power Load" : "Service Connection Application";
        $transactionIndex->PaymentUsed = $request['PaymentUsed'];
        $transactionIndex->UserId = Auth::id();
        $transactionIndex->PayeeName = $totalTransactions->ServiceAccountName;

        $transactionIndex->save();

        $saTransaction = ServiceConnectionTotalPayments::where('ServiceConnectionId', $request['svcId'])->first();
        if ($saTransaction != null) {
            $saTransaction->Notes = $request['ORNumber'];
            $saTransaction->save();
        }

        // SAVE TRANSACTION DETIALS
        foreach($particularPayments as $item) {
            $transactionDetails = new TransactionDetails;
            $transactionDetails->id = IDGenerator::generateIDandRandString();
            $transactionDetails->TransactionIndexId = $id;
            $transactionDetails->Particular = $item->Particular;
            $transactionDetails->Amount = $item->Amount;
            $transactionDetails->VAT = $item->Vat;
            $transactionDetails->Total = $item->Total;
            $transactionDetails->AccountCode = $item->AccountNumber;
            $transactionDetails->save();

            // SAVE DCR TRANSACTIONS
            if ($item->AccountNumber != null) {
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = $item->AccountNumber;
                $dcrSum->Amount = $item->Total;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->save();
            }            
        }

        // SAVE TRANSACTION DETAILS FOR POWER LOAD
        if ($request['LoadCategory'] == 'above 5kVa') {
            $powerLoadPayables = BillsOfMaterialsSummary::where('ServiceConnectionId', $request['svcId'])->first();

            if ($powerLoadPayables != null) {
                $transactionDetails = new TransactionDetails;
                $transactionDetails->id = IDGenerator::generateIDandRandString();
                $transactionDetails->TransactionIndexId = $id;
                $transactionDetails->Particular = 'Power Load Payables';
                $transactionDetails->Amount = round(floatval($powerLoadPayables->Total) - floatval($powerLoadPayables->TotalVAT), 2);
                $transactionDetails->VAT = $powerLoadPayables->TotalVAT;
                $transactionDetails->Total = $powerLoadPayables->Total;
                $transactionDetails->save();

                $powerLoadPayables->IsPaid = 'Yes';
                $powerLoadPayables->ORNumber = $request['ORNumber'];
                $powerLoadPayables->ORDate = date('Y-m-d');
                $powerLoadPayables->save();

                // SAVE GL CODE
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '250-251-00';
                $dcrSum->Amount = $transactionDetails->Total;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->save();
            }            
        }

        // SAVE TRANSACTION PAYMENT DETAILS LOGS
        if ($request['PaymentUsed'] == 'Cash' | $request['PaymentUsed'] == 'Cash and Check') {
            if ($request['CashAmount'] != null) {
                $transactionPaymentDetails = new TransacionPaymentDetails;
                $transactionPaymentDetails->id = IDGenerator::generateIDandRandString();
                $transactionPaymentDetails->TransactionIndexId = $id;
                $transactionPaymentDetails->Amount = $request['CashAmount'];
                $transactionPaymentDetails->PaymentUsed = 'Cash';
                $transactionPaymentDetails->ORNumber = $request['ORNumber'];
                $transactionPaymentDetails->save();
            }            
        }

        // SAVE OR
        $saveOR = ORAssigning::where('ORNumber', $transactionIndex->ORNumber)
            ->whereRaw("UserId='" . Auth::id() . "'")
            ->first();        
        if ($saveOR == null) {
            $saveOR = new ORAssigning;
            $saveOR->id = IDGenerator::generateIDandRandString();
            $saveOR->ORNumber = $transactionIndex->ORNumber;
            $saveOR->UserId = Auth::id();
            $saveOR->DateAssigned = $transactionIndex->ORDate;
            $saveOR->TimeAssigned = date('H:i:s');
            $saveOR->Office = env('APP_LOCATION');
            $saveOR->save();
        }   

        // UPDATE Service Connection OR
        $serviceConnection = ServiceConnections::find($request['svcId']);
        $serviceConnection->ORNumber = $transactionIndex->ORNumber;
        $serviceConnection->ORDate = $transactionIndex->ORDate;
        $serviceConnection->save();

        return response()->json(['id' => $id], 200);
    }

    public function printORServiceConnections($transactionIndexId) {
        $transactionIndex = TransactionIndex::find($transactionIndexId);
        $transactionDetails = TransactionDetails::where('TransactionIndexId', $transactionIndexId)->get();

        return view('/transaction_indices/print_or_service_connections', [
            'transactionIndex' => $transactionIndex,
            'transactionDetails' => $transactionDetails,
        ]);
    }

    public function uncollectedArrears() {
        $orAssignedLast = ORAssigning::whereRaw("UserId='" . Auth::id() . "'")
            ->orderByDesc('created_at')
            ->first();

        return view('/transaction_indices/uncollected_arrears', [
            'orAssignedLast' => $orAssignedLast,
        ]);
    }

    public function searchArrearCollectibles(Request $request) {
        $results = DB::table('Billing_Collectibles')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Collectibles.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['query'] . '%')
            ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['query'] . '%')
            ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['query'] . '%')
            ->where('Billing_Collectibles.Balance', '!=', '0')
            ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_Collectibles.Balance',
                    'Billing_Collectibles.id')
            ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
            ->get();

        $output = "";

        if (count($results) > 0) {
            foreach($results as $item) {
                $ledger = ArrearsLedgerDistribution::where('AccountNumber', $item->AccountNumber)
                    ->whereNull('IsPaid')
                    ->get();

                if (count($ledger) > 0) {
                    $output .= '
                        <tr>
                            <td>' . $item->AccountNumber . '</td>
                            <td>' . $item->ServiceAccountName . '</td>
                            <td>' . number_format($item->Balance, 2) . '</td>
                            <td class="text-right">
                                <a class="btn btn-link text-primary" href="' . route('transactionIndices.ledger-arrears-collection', [$item->AccountNumber]) . '"><i class="fas fa-forward"></i></a>
                            </td>
                        </tr>
                    ';
                } else {
                    $output .= '
                        <tr onclick=fetchDetails("' . $item->AccountNumber . '")>
                            <td>' . $item->AccountNumber . '</td>
                            <td>' . $item->ServiceAccountName . '</td>
                            <td>' . number_format($item->Balance, 2) . '</td>
                            <td class="text-right">
                                <button class="btn btn-link text-primary" onclick=fetchDetails("' . $item->AccountNumber . '")><i class="fas fa-forward"></i></button>
                            </td>
                        </tr>
                    ';
                }                
            }

            return response()->json($output, 200);
        } else {
            return response()->json(['res' => 'No results found'], 200);
        }       
    }

    public function fetchArrearDetails(Request $request) {
        $collectibles = Collectibles::where('AccountNumber', $request['AccountNumber'])->first();

        if ($collectibles != null) {
            return response()->json($collectibles, 200);
        } else {
            return response()->json([], 200);
        }
    }

    public function saveArrearTransaction(Request $request) {
        $account = ServiceAccounts::find($request['AccountNumber']);

        $total = floatval($request['AmountPaid']);

        // SAVE TRANSACTION
        $id = IDGenerator::generateID();

        $transactionIndex = new TransactionIndex;
        $transactionIndex->id = $id;
        $transactionIndex->TransactionNumber = env('APP_LOCATION') . '-' . $id;
        $transactionIndex->PaymentTitle = "Partial Payment to the arrears of " . $account->ServiceAccountName;
        $transactionIndex->ORNumber = $request['ORNumber'];
        $transactionIndex->ORDate = date('Y-m-d');
        $transactionIndex->SubTotal = round($total, 2);
        // $transactionIndex->VAT = 0; // TO BE ADDED LATER
        $transactionIndex->Total = round($total, 2);
        $transactionIndex->Source = "Arrears Collectible";
        $transactionIndex->ObjectId = $request['AccountNumber']; // ACCOUNT NUMBER
        $transactionIndex->PaymentUsed = $request['PaymentUsed'];
        $transactionIndex->UserId = Auth::id();
        $transactionIndex->PayeeName = $account->ServiceAccountName;
        $transactionIndex->AccountNumber = $account->OldAccountNo;

        $transactionIndex->save();

        // SAVE TRANSACTION DETAILS
        $transactionDetails = new TransactionDetails;
        $transactionDetails->id = IDGenerator::generateIDandRandString();
        $transactionDetails->TransactionIndexId = $id;
        $transactionDetails->Particular = "Uncollected Arrear Partial Payment";
        $transactionDetails->Amount = round($total, 2);
        // $transactionDetails->VAT = $item->Vat; // TO BE ADDED LATER
        $transactionDetails->Total = round($total, 2);
        $transactionDetails->save();

        // DEDUCT BALANCE
        $collectibles = Collectibles::where('AccountNumber', $request['AccountNumber'])->first();

        if ($collectibles != null) {
            $collectibles->Balance = floatval($request['RemainingBalance']);
            $collectibles->save();
        }

        // SAVE TRANSACTION PAYMENT DETAILS LOGS
        if ($request['PaymentUsed'] == 'Cash' | $request['PaymentUsed'] == 'Cash and Check') {
            if ($request['CashAmount'] != null) {
                $transactionPaymentDetails = new TransacionPaymentDetails;
                $transactionPaymentDetails->id = IDGenerator::generateIDandRandString();
                $transactionPaymentDetails->TransactionIndexId = $id;
                $transactionPaymentDetails->Amount = $request['CashAmount'];
                $transactionPaymentDetails->PaymentUsed = 'Cash';
                $transactionPaymentDetails->ORNumber = $request['ORNumber'];
                $transactionPaymentDetails->save();
            }            
        }

        // SAVE OR
        $saveOR = ORAssigning::where('ORNumber', $transactionIndex->ORNumber)
            ->whereRaw("UserId='" . Auth::id() . "'")
            ->first();        
        if ($saveOR == null) {
            $saveOR = new ORAssigning;
            $saveOR->id = IDGenerator::generateIDandRandString();
            $saveOR->ORNumber = $transactionIndex->ORNumber;
            $saveOR->UserId = Auth::id();
            $saveOR->DateAssigned = $transactionIndex->ORDate;
            $saveOR->TimeAssigned = date('H:i:s');
            $saveOR->Office = env('APP_LOCATION');
            $saveOR->save();
        }  

        return response()->json($transactionIndex, 200);
    }

    public function ledgerArrearsCollection($accountNo) {
        $account = ServiceAccounts::find($accountNo);
        $collectibles = Collectibles::where('AccountNumber', $accountNo)->first();
        $ledger = ArrearsLedgerDistribution::where('AccountNumber', $accountNo)->orderBy('ServicePeriod')->get();
        $orAssignedLast = ORAssigning::whereRaw("UserId='" . Auth::id() . "'")
            ->orderByDesc('created_at')
            ->first();

        return view('/transaction_indices/ledger_arrears_collection', [
            'account' => $account,
            'collectibles' => $collectibles,
            'ledger' => $ledger,
            'orAssignedLast' => $orAssignedLast,
        ]);
    }

    public function saveLedgerArrearTransaction(Request $request) {
        $ledgerIds = $request['LedgerIds'];
        $len = count($ledgerIds);
        $account = ServiceAccounts::find($request['AccountNumber']);
        $collectibles = Collectibles::where('AccountNumber', $request['AccountNumber'])->first();

        // SAVE TRANSACTION
        $total = floatval($request['TotalPayment']);

        // SAVE TRANSACTION
        $id = IDGenerator::generateID();

        $transactionIndex = new TransactionIndex;
        $transactionIndex->id = $id;
        $transactionIndex->TransactionNumber = env('APP_LOCATION') . '-' . $id;
        $transactionIndex->PaymentTitle = $account->ServiceAccountName;
        $transactionIndex->PaymentDetails = "Partial Payment to the arrears of " . $account->ServiceAccountName;
        $transactionIndex->ORNumber = $request['ORNumber'];
        $transactionIndex->ORDate = date('Y-m-d');
        $transactionIndex->SubTotal = round($total, 2);
        // $transactionIndex->VAT = 0; // TO BE ADDED LATER
        $transactionIndex->Total = round($total, 2);
        $transactionIndex->Source = "Arrears Termed Ledger";
        $transactionIndex->ObjectId = $request['AccountNumber']; // ACCOUNT NUMBER
        $transactionIndex->PaymentUsed = $request['PaymentUsed'];
        $transactionIndex->UserId = Auth::id();
        $transactionIndex->PayeeName = $account->ServiceAccountName;
        $transactionIndex->AccountNumber = $account->OldAccountNo;

        if ($request['PaymentUsed'] == 'Check') {
            $transactionIndex->CheckNo = $request['CheckNo'];
            $transactionIndex->Bank = $request['Bank'];
        }

        $transactionIndex->save();

        for ($i=0; $i<$len; $i++) {
            // LEDGERS
            $ledgers = ArrearsLedgerDistribution::find($ledgerIds[$i]);

            if ($ledgers != null) {
                // SAVE TRANSACTION DETAILS
                $transactionDetails = new TransactionDetails;
                $transactionDetails->id = IDGenerator::generateIDandRandString();
                $transactionDetails->TransactionIndexId = $id;
                $transactionDetails->Particular = "Termed arrear for " . date('F Y', strtotime($ledgers->ServicePeriod));
                $transactionDetails->Amount = round(floatval($ledgers->Amount), 2);
                // $transactionDetails->VAT = $item->Vat; // TO BE ADDED LATER
                $transactionDetails->Total = round(floatval($ledgers->Amount), 2);
                $transactionDetails->AccountCode = DCRSummaryTransactions::getARConsumersTermedPayments($account->Town);
                $transactionDetails->save();

                // SAVE TO DCR SUMMARY
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersTermedPayments($account->Town);
                $dcrSum->Amount = $ledgers->Amount;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $request['ORNumber'];
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $account->id;
                $dcrSum->save();

                // UPDATE LEDGER STATUS
                $ledgers->IsPaid = 'Yes';
                $ledgers->save();
            }
        }

        // DEDUCT BALANCE
        if ($collectibles != null) {
            $collectibles->Balance = round((floatval($collectibles->Balance) - $total), 2);
            $collectibles->save();
        }

        // SAVE OR
        $saveOR = ORAssigning::where('ORNumber', $transactionIndex->ORNumber)
            ->whereRaw("UserId='" . Auth::id() . "'")
            ->first();        
        if ($saveOR == null) {
            $saveOR = new ORAssigning;
            $saveOR->id = IDGenerator::generateIDandRandString();
            $saveOR->ORNumber = $transactionIndex->ORNumber;
            $saveOR->UserId = Auth::id();
            $saveOR->DateAssigned = $transactionIndex->ORDate;
            $saveOR->TimeAssigned = date('H:i:s');
            $saveOR->Office = env('APP_LOCATION');
            $saveOR->save();
        } 

        return response()->json($transactionIndex, 200);
    }

    public function printORTermedLedgerArrears($transactionIndexId) {
        $transactionIndex = TransactionIndex::find($transactionIndexId);
        $transactionDetails = TransactionDetails::where('TransactionIndexId', $transactionIndexId)->get();
        $user = Auth::user();

        return view('/transaction_indices/print_or_termed_ledger_arrears', [
            'transactionIndex' => $transactionIndex,
            'transactionDetails' => $transactionDetails,
            'user' => $user,
        ]);
    }

    public function otherPayments() {
        $payables = AccountPayables::all();
        $orAssignedLast = ORAssigning::whereRaw("UserId='" . Auth::id() . "'")
            ->orderByDesc('created_at')
            ->first();
        $transactionId = IDGenerator::generateID();

        return view('/transaction_indices/other_payments', [
            'payables' => $payables,
            'orAssignedLast' => $orAssignedLast,
            'transactionId' => $transactionId,
        ]);
    }

    public function searchConsumer(Request $request) {
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
                        <tr onclick=fetchAccountDetails("' . $item->id . '")>
                            <td>' . $item->id . '</td>
                            <td>' . $item->ServiceAccountName . '</td>
                            <td>' . ServiceAccounts::getAddress($item) . '</td>
                            <td>' . $item->AccountStatus . '</td>
                            <td>
                                <button class="btn btn-link text-primary" onclick=fetchAccountDetails("' . $item->id . '")><i class="fas fa-forward"></i></button>
                            </td>
                        </tr>
                    '; 
            }

            return response()->json($output, 200);
        } else {
            return response()->json([], 200);
        }        
    }

    public function fetchAccountDetails(Request $request) {
        $account = ServiceAccounts::find($request['id']);

        if ($account != null) {
            return response()->json($account, 200);
        } else {
            return response()->json('Account not found', 404);
        }
    }

    public function fetchPayableDetails(Request $request) {
        $payable = AccountPayables::find($request['id']);

        if ($payable != null) {
            return response()->json($payable, 200);
        } else {
            return response()->json('Payable not found', 404);
        }
    }

    public function printOtherPayments($transactionIndexId) {
        $transactionIndex = TransactionIndex::find($transactionIndexId);
        $transactionDetails = TransactionDetails::where('TransactionIndexId', $transactionIndexId)->get();
        $account = ServiceAccounts::find($transactionIndex->AccountNumber);
        $user = Auth::user();

        return view('/transaction_indices/print_other_payments', [
            'transactionIndex' => $transactionIndex,
            'transactionDetails' => $transactionDetails,
            'user' => $user,
            'account' => $account
        ]);
    }

    public function reconnectionCollection() {
        $reconnectionPayable = AccountPayables::where('id', TransactionIndex::getReconnectionFeeId())->first();
        $orAssignedLast = ORAssigning::whereRaw("UserId='" . Auth::id() . "'")
            ->orderByDesc('created_at')
            ->first();
        return view('/transaction_indices/reconnection_collection', [
            'reconnectionPayable' => $reconnectionPayable,
            'orAssignedLast' => $orAssignedLast,
        ]);
    }

    public function searchDisconnectedConsumers(Request $request) {
        $results = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Billing_ServiceAccounts.AccountStatus', 'DISCONNECTED')
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

    public function getArrearsData(Request $request) {
        $billArrears = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Billing_Bills.AccountNumber', $request['AccountNumber'])
            ->whereRaw("Billing_Bills.id NOT IN (SELECT ObjectSourceId FROM Cashier_PaidBills WHERE AccountNumber='" . $request['AccountNumber'] . "')")
            ->select('Billing_Bills.*')
            ->orderByDesc('Billing_Bills.ServicePeriod')
            ->get();

        $output = "";

        if (count($billArrears) > 0) {
            foreach($billArrears as $item) {
                $output .= '
                        <tr>
                            <td>' . date('F Y', strtotime($item->ServicePeriod)) . '</td>
                            <td>' . number_format($item->NetAmount, 2) . '</td>
                            <td>' . Bills::getFinalPenalty($item) . '</td>
                            <td>' . number_format((floatval($item->NetAmount) + Bills::getFinalPenalty($item)), 2) . '</td>
                        </tr>
                    '; 
            }

            return response()->json($output, 200);
        } else {
            return response()->json([], 200);
        }       
    }

    public function saveReconnectionTransaction(Request $request) {
        // SAVE TRANSACTION
        $id = IDGenerator::generateID();

        $reconnectionPayable = AccountPayables::where('id', TransactionIndex::getReconnectionFeeId())->first();

        $serviceAccount = ServiceAccounts::find($request['AccountNumber']);

        $transactionIndex = new TransactionIndex;
        $transactionIndex->id = $id;
        $transactionIndex->TransactionNumber = env('APP_LOCATION') . '-' . $id;
        $transactionIndex->PaymentTitle = $serviceAccount->ServiceAccountName;
        $transactionIndex->PaymentDetails = "Reconnection Payment for Account Name " . $serviceAccount->ServiceAccountName;
        $transactionIndex->ORNumber = $request['ORNumber'];
        $transactionIndex->ORDate = date('Y-m-d');
        $transactionIndex->SubTotal = round(floatval($request['SubTotal']), 2);
        $transactionIndex->VAT = round(floatval($request['VAT']), 2);
        $transactionIndex->Total = round(floatval($request['Total']), 2);
        $transactionIndex->ObjectId = $request['AccountNumber'];
        $transactionIndex->Source = 'Reconnection Payments';
        $transactionIndex->Notes = "Object Id: Account Number";
        $transactionIndex->PaymentUsed = $request['PaymentUsed'];
        $transactionIndex->AccountNumber = $request['AccountNumber'];
        $transactionIndex->UserId = Auth::id();
        $transactionIndex->save();

        // SAVE TRANSACTION DETAILS
        $transactionDetails = new TransactionDetails;
        $transactionDetails->id = IDGenerator::generateIDandRandString();
        $transactionDetails->TransactionIndexId = $id;
        $transactionDetails->Particular = $reconnectionPayable != null ? $reconnectionPayable->AccountTitle : 'Reconnection Fee';
        $transactionDetails->Amount = round(floatval($request['SubTotal']), 2);
        $transactionDetails->VAT = round(floatval($request['VAT']), 2);
        $transactionDetails->Total = round(floatval($request['Total']), 2);
        $transactionDetails->AccountCode = $reconnectionPayable->AccountCode;
        $transactionDetails->save();

        // SAVE DCR TRANSACTIONS
        if ($reconnectionPayable->AccountCode != null) {
            $dcrSum = new DCRSummaryTransactions;
            $dcrSum->id = IDGenerator::generateIDandRandString();
            $dcrSum->GLCode = $reconnectionPayable->AccountCode;
            $dcrSum->Amount = $transactionDetails->Total;
            $dcrSum->Day = date('Y-m-d');
            $dcrSum->Time = date('H:i:s');
            $dcrSum->Teller = Auth::id();
            $dcrSum->ORNumber = $request['ORNumber'];
            $dcrSum->ReportDestination = 'COLLECTION';
            $dcrSum->Office = env('APP_LOCATION');
            $dcrSum->save();
        }  

        // CREATE RECONNECTION TICKET 
        $ticket = new Tickets;
        $ticket->id = IDGenerator::generateIDandRandString();
        $ticket->AccountNumber = $request['AccountNumber'];
        $ticket->ConsumerName = $serviceAccount->ServiceAccountName;
        $ticket->Town =$serviceAccount->Town;
        $ticket->Barangay = $serviceAccount->Barangay;
        $ticket->Sitio = $serviceAccount->Purok;
        $ticket->Ticket = Tickets::getReconnection();
        $ticket->Reason = 'Delinquency';
        $ticket->GeoLocation = $serviceAccount->Latitude . ',' . $serviceAccount->Longitude;
        $ticket->Status = 'Received';
        $ticket->UserId = Auth::id();
        $ticket->Office = env('APP_LOCATION');
        $ticket->save();

        // CREATE LOG
        $ticketLog = new TicketLogs;
        $ticketLog->id = IDGenerator::generateIDandRandString();
        $ticketLog->TicketId = $ticket->id;
        $ticketLog->Log = "Ticket Filed";
        $ticketLog->LogDetails = "Ticket automatically created via Reconnection Payment Module";
        $ticketLog->UserId = Auth::id();
        $ticketLog->save();

        // ADD OR LOG
        // SAVE OR
        $saveOR = ORAssigning::where('ORNumber', $request['ORNumber'])
            ->whereRaw("UserId='" . Auth::id() . "'")
            ->first();        
        if ($saveOR == null) {
            $saveOR = new ORAssigning;
            $saveOR->id = IDGenerator::generateIDandRandString();
            $saveOR->ORNumber = $request['ORNumber'];
            $saveOR->UserId = Auth::id();
            $saveOR->DateAssigned = $transactionIndex->ORDate;
            $saveOR->TimeAssigned = date('H:i:s');
            $saveOR->Office = env('APP_LOCATION');
            $saveOR->save();
        } 

        return response()->json($transactionIndex, 200);
    }

    public function printOrReconnection($transactionIndexId) {
        $transactionIndex = TransactionIndex::find($transactionIndexId);
        $transactionDetails = TransactionDetails::where('TransactionIndexId', $transactionIndexId)->get();
        $account = ServiceAccounts::find($transactionIndex->AccountNumber);
        $user = Auth::user();

        return view('/transaction_indices/print_reconnection_collection', [
            'transactionIndex' => $transactionIndex,
            'transactionDetails' => $transactionDetails,
            'user' => $user,
            'account' => $account
        ]);
    }

    public function addCheckPayment(Request $request) {
        $transactionPaymentDetails = new TransacionPaymentDetails;
        $transactionPaymentDetails->id = IDGenerator::generateIDandRandString();
        $transactionPaymentDetails->TransactionIndexId = $request['TransactionIndexId'];
        $transactionPaymentDetails->Amount = $request['Amount'];
        $transactionPaymentDetails->PaymentUsed = 'Check';
        $transactionPaymentDetails->CheckNo = $request['CheckNo'];
        $transactionPaymentDetails->Bank = $request['Bank'];
        $transactionPaymentDetails->ORNumber = $request['ORNumber'];
        $transactionPaymentDetails->CheckExpiration = $request['CheckExpiration'];
        $transactionPaymentDetails->save();

        return response()->json($transactionPaymentDetails, 200);
    }

    public function deleteCheckPayment(Request $request) {
        $transactionPaymentDetails = TransacionPaymentDetails::find($request['id']);

        $transactionPaymentDetails->delete();

        return response()->json($transactionPaymentDetails, 200);
    }

    public function browseORs(Request $request) {
        $params = $request['params'];

        if ($params == null) {
            $paidBills = DB::table('Cashier_PaidBills')
                ->whereRaw("ORNumber LIKE '%" . $params . "%' AND (Status IS NULL OR Status='Application')")
                ->select('id', 'ORNumber', 'ORDate', 'AccountNumber', 'Source', 'NetAmount', 'CheckNo', 'ObjectSourceId', DB::raw("'BILLS PAYMENT' AS PaymentType"))
                ->orderByDesc('created_at')
                ->limit(20);
            $allPayments = DB::table('Cashier_TransactionIndex')
                ->whereRaw("ORNumber LIKE '%" . $params . "%'")
                ->select('id', 'ORNumber', 'ORDate', 'AccountNumber', DB::raw("'OTHERS' AS Source"), 'Total', 'CheckNo', 'ObjectId', DB::raw("'OTHER PAYMENT' AS PaymentType"))
                ->orderByDesc('created_at')
                ->limit(20)
                ->union($paidBills)
                ->get();
        } else {
            $paidBills = DB::table('Cashier_PaidBills')
                ->whereRaw("ORNumber LIKE '%" . $params . "%' AND (Status IS NULL OR Status='Application')")
                ->select('id', 'ORNumber', 'ORDate', 'AccountNumber', 'Source', 'NetAmount', 'CheckNo', 'ObjectSourceId', DB::raw("'BILLS PAYMENT' AS PaymentType"));
            $allPayments = DB::table('Cashier_TransactionIndex')
                ->whereRaw("ORNumber LIKE '%" . $params . "%'")
                ->select('id', 'ORNumber', 'ORDate', 'AccountNumber', DB::raw("'OTHERS' AS Source"), 'Total', 'CheckNo', 'ObjectId', DB::raw("'OTHER PAYMENT' AS PaymentType"))
                ->union($paidBills)
                ->get();
        }

        return view('/transaction_indices/browse_ors', [
            'params' => $params,
            'allPayments' => $allPayments,
        ]);
    }

    public function browseORView($id, $paymentType) {
        return view('/transaction_indices/browse_ors_view', [
            'id' => $id,
            'paymentType' => $paymentType,
        ]);
    }

    public function printOrTransactions($transactionIndexId) {
        $transactionIndex = TransactionIndex::find($transactionIndexId);
        $transactionDetails = TransactionDetails::where('TransactionIndexId', $transactionIndexId)->get();
        $user = Auth::user();

        return view('/transaction_indices/print_or_transactions', [
            'transactionIndex' => $transactionIndex,
            'transactionDetails' => $transactionDetails,
            'user' => $user,
        ]);
    }

    public function orMaintenance(Request $request) {
        if ($request['Date'] != null) {
            $paidBills = DB::table('Cashier_PaidBills')
                ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->where('Cashier_PaidBills.ORDate', $request['Date'])
                ->whereRaw("Cashier_PaidBills.Teller='" . Auth::id() . "'")
                ->whereNull('Status')
                ->select('Cashier_PaidBills.*',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.AccountType')
                ->get();

            $nonPowerBills = TransactionIndex::where('ORDate', $request['Date'])
                ->whereRaw("UserId='" . Auth::id() . "'")
                ->whereNull('Status')
                ->get();
        } else {
            $paidBills = DB::table('Cashier_PaidBills')
                ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->where('Cashier_PaidBills.ORDate', date('Y-m-d'))
                ->whereRaw("Cashier_PaidBills.Teller='" . Auth::id() . "'")
                ->whereNull('Status')
                ->select('Cashier_PaidBills.*',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.AccountType')
                ->get();
            
            $nonPowerBills = TransactionIndex::where('ORDate', date('Y-m-d'))
                ->whereRaw("UserId='" . Auth::id() . "'")
                ->whereNull('Status')
                ->get();
        }
        

        return view('/transaction_indices/or_maintenance', [
            'paidBills' => $paidBills,
            'nonPowerBills' => $nonPowerBills
        ]);
    }

    public function updateORNumber(Request $request) {
        if ($request['Type'] == 'POWERBILL') {
            $paidBills = PaidBills::find($request['id']);

            if ($paidBills != null) {
                $originalOR = $paidBills->ORNumber;
                // UPDATE PaidbillDetails
                DB::table('Cashier_PaidBillsDetails')
                    ->where('ORNumber', $originalOR)
                    ->update(['ORNumber' => $request['ORNumber']]);

                // UPDATE DCR SUmmary transactions
                DB::table('Cashier_DCRSummaryTransactions')
                    ->where('ORNumber', $originalOR)
                    ->update(['ORNumber' => $request['ORNumber']]);

                // UPDATE Denominations
                DB::table('Cashier_Denominations')
                    ->where('ORNumber', $originalOR)
                    ->update(['ORNumber' => $request['ORNumber']]);

                $paidBills->ORNumber = $request['ORNumber'];
                $paidBills->save();

                return response()->json(['res' => 'ok', 'message' => 'OR ' . $originalOR . ' changed to ' . $paidBills->ORNumber], 200);
            } else {
                return response()->json(['res' => 'error', 'message' => 'Payment not found!'], 200);
            }
        } else {
            $transactionIndex = TransactionIndex::find($request['id']);

            if ($transactionIndex != null) {
                $originalOR = $transactionIndex->ORNumber;
                // UPDATE TRANSACTION DETAILS
                DB::table('Cashier_TransactionPaymentDetails')
                    ->where('ORNumber', $originalOR)
                    ->update(['ORNumber' => $request['ORNumber']]);

                // UPDATE DCR SUmmary transactions
                DB::table('Cashier_DCRSummaryTransactions')
                    ->where('ORNumber', $originalOR)
                    ->update(['ORNumber' => $request['ORNumber']]);

                // UPDATE Denominations
                DB::table('Cashier_Denominations')
                    ->where('ORNumber', $originalOR)
                    ->update(['ORNumber' => $request['ORNumber']]);

                $transactionIndex->ORNumber = $request['ORNumber'];
                $transactionIndex->save();

                return response()->json(['res' => 'ok', 'message' => 'OR ' . $originalOR . ' changed to ' . $transactionIndex->ORNumber], 200);
            } else {
                return response()->json(['res' => 'error', 'message' => 'Payment not found!'], 200);
            }
        }
    }
}
