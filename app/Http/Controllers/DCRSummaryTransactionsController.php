<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDCRSummaryTransactionsRequest;
use App\Http\Requests\UpdateDCRSummaryTransactionsRequest;
use App\Repositories\DCRSummaryTransactionsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Rates;
use App\Models\Bills;
use App\Models\PaidBills;
use App\Models\User;
use App\Models\DCRSummaryTransactions;
use App\Models\IDGenerator;
use Flash;
use Response;

class DCRSummaryTransactionsController extends AppBaseController
{
    /** @var  DCRSummaryTransactionsRepository */
    private $dCRSummaryTransactionsRepository;

    public function __construct(DCRSummaryTransactionsRepository $dCRSummaryTransactionsRepo)
    {
        $this->middleware('auth');
        $this->dCRSummaryTransactionsRepository = $dCRSummaryTransactionsRepo;
    }

    /**
     * Display a listing of the DCRSummaryTransactions.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {        
        if ($request['Day'] != null) {
            $data = DB::table('Cashier_DCRSummaryTransactions')
                ->where('Day', $request['Day'])
                ->where('Teller', $request['Teller'])
                ->whereIn('ReportDestination', ['COLLECTION', 'BOTH'])
                ->select('GLCode',
                    DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                    DB::raw("SUM(CAST(Amount AS DECIMAL(10,2))) AS Amount")
                )
                ->groupBy('GLCode')
                ->orderBy('GLCode')
                ->get();
        } else {
            $data = DB::table('Cashier_DCRSummaryTransactions')
                ->where('Day', date('Y-m-d'))
                ->where('Teller', $request['Teller'])
                ->whereIn('ReportDestination', ['COLLECTION', 'BOTH'])
                ->select('GLCode',
                    DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                    DB::raw("SUM(CAST(Amount AS DECIMAL(10,2))) AS Amount")
                )
                ->groupBy('GLCode')
                ->orderBy('GLCode')
                ->get();
        }

        $powerBills = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Cashier_PaidBills.ORDate', $request['Day'] != null ? $request['Day'] : date('Y-m-d'))
            ->where('Cashier_PaidBills.Teller', $request['Teller'])
            ->whereNull('Cashier_PaidBills.Status')
            // ->whereRaw("Cashier_PaidBills.PaymentUsed LIKE '%Cash%'")
            ->select('Cashier_PaidBills.*', 
                DB::raw("(SELECT SUM(CAST(Amount AS DECIMAL(10,2))) FROM Cashier_PaidBillsDetails WHERE ORNumber=Cashier_PaidBills.ORNumber AND PaymentUsed='Cash' AND UserId='" . $request['Teller'] ."') AS CashPaid"),            
                'Billing_ServiceAccounts.ServiceAccountName', 
                'Billing_ServiceAccounts.OldAccountNo')
            ->get();

        $nonPowerBills = DB::table('Cashier_TransactionDetails')
            ->leftJoin('Cashier_TransactionIndex', 'Cashier_TransactionDetails.TransactionIndexId', '=', 'Cashier_TransactionIndex.id')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_TransactionIndex.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Cashier_TransactionIndex.ORDate', $request['Day'] != null ? $request['Day'] : date('Y-m-d'))
            ->where('Cashier_TransactionIndex.UserId', $request['Teller'])
            ->whereNull('Cashier_TransactionIndex.Status')
            ->select('Cashier_TransactionIndex.ORNumber',
                'Cashier_TransactionDetails.Total',
                'Cashier_TransactionIndex.AccountNumber',
                'Cashier_TransactionIndex.PayeeName',
                'Cashier_TransactionDetails.Particular',
                'Cashier_TransactionDetails.AccountCode',
                'Cashier_TransactionIndex.CheckNo',
                'Cashier_TransactionIndex.Bank',
                'Cashier_TransactionIndex.id',
                'Billing_ServiceAccounts.OldAccountNo',
                'Cashier_TransactionIndex.PayeeName')
            ->orderBy('Cashier_TransactionDetails.TransactionIndexId')
            ->get();

        $powerBillsCheck = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Cashier_PaidBills.PostingDate', $request['Day'] != null ? $request['Day'] : date('Y-m-d'))
            ->where('Cashier_PaidBills.Teller', $request['Teller'])
            ->whereRaw("Cashier_PaidBills.PaymentUsed LIKE '%Check%'")
            ->whereNull('Cashier_PaidBills.Status')
            ->select('Cashier_PaidBills.*', 'Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.OldAccountNo')
            ->get();

        $nonPowerBillsCheck = DB::table('Cashier_TransactionDetails')
            ->leftJoin('Cashier_TransactionIndex', 'Cashier_TransactionDetails.TransactionIndexId', '=', 'Cashier_TransactionIndex.id')
            ->where('Cashier_TransactionIndex.ORDate', $request['Day'] != null ? $request['Day'] : date('Y-m-d'))
            ->where('Cashier_TransactionIndex.UserId', $request['Teller'])
            ->whereRaw("Cashier_TransactionIndex.PaymentUsed LIKE '%Check%'")
            ->whereNull('Cashier_TransactionIndex.Status')
            ->select('Cashier_TransactionIndex.ORNumber',
                'Cashier_TransactionIndex.Total',
                'Cashier_TransactionIndex.AccountNumber',
                'Cashier_TransactionIndex.PayeeName',
                'Cashier_TransactionDetails.AccountCode',
                'Cashier_TransactionIndex.CheckNo',
                'Cashier_TransactionIndex.Bank',
                'Cashier_TransactionIndex.PayeeName')
            ->orderBy('Cashier_TransactionDetails.TransactionIndexId')
            ->get();

        $powerBillsCancelled = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Cashier_PaidBills.PostingDate', $request['Day'] != null ? $request['Day'] : date('Y-m-d'))
            ->where('Cashier_PaidBills.Teller', $request['Teller'])
            ->whereIn('Cashier_PaidBills.Status', ['CANCELLED', 'PENDING CANCEL'])
            ->select('Cashier_PaidBills.*', 'Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.OldAccountNo')
            ->get();

        $nonPowerBillsCancelled = DB::table('Cashier_TransactionIndex')
            ->where('Cashier_TransactionIndex.ORDate', $request['Day'] != null ? $request['Day'] : date('Y-m-d'))
            ->where('Cashier_TransactionIndex.UserId', $request['Teller'])
            ->whereIn('Cashier_TransactionIndex.Status', ['CANCELLED', 'PENDING CANCEL'])
            ->select('Cashier_TransactionIndex.ORNumber',
                'Cashier_TransactionIndex.Total',
                'Cashier_TransactionIndex.AccountNumber',
                'Cashier_TransactionIndex.PayeeName',
                'Cashier_TransactionIndex.CheckNo',
                'Cashier_TransactionIndex.Bank',
                'Cashier_TransactionIndex.PayeeName')
            ->get();

        $summary = DB::table('Cashier_PaidBillsDetails')
            ->select(
                DB::raw("(SELECT SUM(CAST(Amount AS DECIMAL(10,2))) FROM Cashier_PaidBillsDetails WHERE PaymentUsed='Cash' AND UserId='" . $request['Teller'] ."' AND CAST(created_at AS DATE)='" . ($request['Day'] != null ? $request['Day'] : date('Y-m-d')) . "') AS CashTotal"),
                DB::raw("(SELECT SUM(CAST(Amount AS DECIMAL(10,2))) FROM Cashier_PaidBillsDetails WHERE PaymentUsed='Check' AND UserId='" . $request['Teller'] ."' AND CAST(created_at AS DATE)='" . ($request['Day'] != null ? $request['Day'] : date('Y-m-d')) . "') AS CheckTotal"),
                DB::raw("(SELECT SUM(CAST(td.Amount AS DECIMAL(10,2))) FROM Cashier_TransactionPaymentDetails td LEFT JOIN Cashier_TransactionIndex t ON t.ORNumber=td.ORNumber WHERE td.PaymentUsed='Cash' AND t.UserId='" . $request['Teller'] ."' AND t.ORDate='" . ($request['Day'] != null ? $request['Day'] : date('Y-m-d')) . "') AS CashNpbTotal"),
            )
            ->first();

        return view('d_c_r_summary_transactions.index', [
            'data' => $data,
            'day' => $request['Day'] != null ? $request['Day'] : date('Y-m-d'),
            'powerBills' => $powerBills,
            'nonPowerBills' => $nonPowerBills,
            'powerBillsCheck' => $powerBillsCheck,
            'nonPowerBillsCheck' => $nonPowerBillsCheck,
            'powerBillsCancelled' => $powerBillsCancelled,
            'nonPowerBillsCancelled' => $nonPowerBillsCancelled,
            'summary' => $summary
        ]);
    }

    public function printDcr($teller, $day) {
        if ($day != null) {
            $data = DB::table('Cashier_DCRSummaryTransactions')
                ->where('Day', $day)
                ->where('Teller', $teller)
                ->where('ReportDestination', 'COLLECTION')
                ->select('GLCode',
                    DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                    DB::raw("SUM(CAST(Amount AS DECIMAL(10,2))) AS Amount")
                )
                ->groupBy('GLCode')
                ->orderBy('GLCode')
                ->get();
        } else {
            $data = DB::table('Cashier_DCRSummaryTransactions')
                ->where('Day', date('Y-m-d'))
                ->where('Teller', $teller)
                ->where('ReportDestination', 'COLLECTION')
                ->select('GLCode',
                    DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                    DB::raw("SUM(CAST(Amount AS DECIMAL(10,2))) AS Amount")
                )
                ->groupBy('GLCode')
                ->orderBy('GLCode')
                ->get();
        }

        $powerBills = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Cashier_PaidBills.PostingDate', $day != null ? $day : date('Y-m-d'))
            ->where('Cashier_PaidBills.Teller', $teller)
            ->whereNull('Cashier_PaidBills.Status')
            ->whereRaw("Cashier_PaidBills.PaymentUsed LIKE '%Cash%'")
            ->select('Cashier_PaidBills.*', 'Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.OldAccountNo',
                DB::raw("(SELECT TOP 1 BillNumber FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod=Cashier_PaidBills.ServicePeriod) AS BillNumber"))
            ->get();

        $nonPowerBills = DB::table('Cashier_TransactionDetails')
            ->leftJoin('Cashier_TransactionIndex', 'Cashier_TransactionDetails.TransactionIndexId', '=', 'Cashier_TransactionIndex.id')
            ->where('Cashier_TransactionIndex.ORDate', $day != null ? $day : date('Y-m-d'))
            ->where('Cashier_TransactionIndex.UserId', $teller)
            ->whereNull('Cashier_TransactionIndex.Status')
            ->select('Cashier_TransactionIndex.ORNumber',
                'Cashier_TransactionDetails.Total',
                'Cashier_TransactionIndex.AccountNumber',
                'Cashier_TransactionIndex.PayeeName',
                'Cashier_TransactionDetails.AccountCode',
                'Cashier_TransactionIndex.CheckNo',
                'Cashier_TransactionIndex.Bank',
                'Cashier_TransactionIndex.PaymentUsed',
                DB::raw("(SELECT TOP 1 OldAccountNo FROM Billing_ServiceAccounts WHERE id=Cashier_TransactionIndex.AccountNumber) AS OldAccountNo"),
                DB::raw("(SELECT TOP 1 ServiceAccountName FROM Billing_ServiceAccounts WHERE id=Cashier_TransactionIndex.AccountNumber) AS ServiceAccountName"),
                'Cashier_TransactionDetails.Particular',
                'Cashier_TransactionIndex.PayeeName')
            ->orderBy('Cashier_TransactionDetails.TransactionIndexId')
            ->get();

        $powerBillsCheck = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('Cashier_PaidBillsDetails', 'Cashier_PaidBills.ORNumber', '=', 'Cashier_PaidBillsDetails.ORNumber')
            ->where('Cashier_PaidBills.PostingDate', $day != null ? $day : date('Y-m-d'))
            ->where('Cashier_PaidBills.Teller', $teller)
            ->whereRaw("Cashier_PaidBillsDetails.PaymentUsed='Check'")
            ->whereNull('Cashier_PaidBills.Status')
            ->select('Cashier_PaidBills.ORNumber', 
                'Billing_ServiceAccounts.ServiceAccountName', 
                'Billing_ServiceAccounts.OldAccountNo',
                'Cashier_PaidBillsDetails.CheckNo',
                'Cashier_PaidBillsDetails.Bank',
                'Cashier_PaidBillsDetails.Amount AS Total');

        $allCheck = DB::table('Cashier_TransactionDetails')
            ->leftJoin('Cashier_TransactionIndex', 'Cashier_TransactionDetails.TransactionIndexId', '=', 'Cashier_TransactionIndex.id')
            ->leftJoin('Cashier_TransactionPaymentDetails', 'Cashier_TransactionPaymentDetails.ORNumber', '=', 'Cashier_TransactionIndex.ORNumber')
            ->where('Cashier_TransactionIndex.ORDate', $day != null ? $day : date('Y-m-d'))
            ->where('Cashier_TransactionIndex.UserId', $teller)
            ->whereRaw("Cashier_TransactionPaymentDetails.PaymentUsed = 'Check'")
            ->whereNull('Cashier_TransactionIndex.Status')
            ->select('Cashier_TransactionIndex.ORNumber',
                'Cashier_TransactionIndex.PaymentTitle as ServiceAccountName',
                DB::raw("(SELECT TOP 1 OldAccountNo FROM Billing_ServiceAccounts WHERE id=Cashier_TransactionIndex.AccountNumber) AS OldAccountNo"),
                'Cashier_TransactionPaymentDetails.CheckNo',
                'Cashier_TransactionPaymentDetails.Bank',
                'Cashier_TransactionPaymentDetails.Amount AS Total')
            ->union($powerBillsCheck)
            ->get();

        $powerBillsCancelled = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Cashier_PaidBills.PostingDate', $day != null ? $day : date('Y-m-d'))
            ->where('Cashier_PaidBills.Teller', $teller)
            ->where('Cashier_PaidBills.Status', 'CANCELLED')
            ->select('Cashier_PaidBills.ORNumber', 
                'Billing_ServiceAccounts.ServiceAccountName', 
                'Billing_ServiceAccounts.OldAccountNo',
                'Cashier_PaidBills.ServicePeriod', 
                DB::raw("(SELECT TOP 1 BillNumber FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod=Cashier_PaidBills.ServicePeriod) AS BillNumber"),
                'Cashier_PaidBills.NetAmount');

        $allCancelled = DB::table('Cashier_TransactionIndex')
            ->where('Cashier_TransactionIndex.ORDate', $day != null ? $day : date('Y-m-d'))
            ->where('Cashier_TransactionIndex.UserId', $teller)
            ->where('Cashier_TransactionIndex.Status', 'CANCELLED')
            ->select('Cashier_TransactionIndex.ORNumber',
                DB::raw("(SELECT TOP 1 ServiceAccountName FROM Billing_ServiceAccounts WHERE id=Cashier_TransactionIndex.AccountNumber) AS ServiceAccountName"),
                DB::raw("(SELECT TOP 1 OldAccountNo FROM Billing_ServiceAccounts WHERE id=Cashier_TransactionIndex.AccountNumber) AS OldAccountNo"),
                DB::raw("NULL AS ServicePeriod"),
                DB::raw("'' AS BillNumber"),
                'Cashier_TransactionIndex.Total')
            ->union($powerBillsCancelled)
            ->get();

        return view('/d_c_r_summary_transactions/print_dcr', [
            'data' => $data,
            'day' => $day != null ? $day : date('Y-m-d'),
            'powerBills' => $powerBills,
            'nonPowerBills' => $nonPowerBills,
            'allCheck' => $allCheck,
            // 'nonPowerBillsCheck' => $nonPowerBillsCheck,
            // 'powerBillsCancelled' => $powerBillsCancelled,
            'allCancelled' => $allCancelled,
        ]);
    }

    /**
     * Show the form for creating a new DCRSummaryTransactions.
     *
     * @return Response
     */
    public function create()
    {
        return view('d_c_r_summary_transactions.create');
    }

    /**
     * Store a newly created DCRSummaryTransactions in storage.
     *
     * @param CreateDCRSummaryTransactionsRequest $request
     *
     * @return Response
     */
    public function store(CreateDCRSummaryTransactionsRequest $request)
    {
        $input = $request->all();

        $dCRSummaryTransactions = $this->dCRSummaryTransactionsRepository->create($input);

        Flash::success('D C R Summary Transactions saved successfully.');

        return redirect(route('dCRSummaryTransactions.index'));
    }

    /**
     * Display the specified DCRSummaryTransactions.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $dCRSummaryTransactions = $this->dCRSummaryTransactionsRepository->find($id);

        if (empty($dCRSummaryTransactions)) {
            Flash::error('D C R Summary Transactions not found');

            return redirect(route('dCRSummaryTransactions.index'));
        }

        return view('d_c_r_summary_transactions.show')->with('dCRSummaryTransactions', $dCRSummaryTransactions);
    }

    /**
     * Show the form for editing the specified DCRSummaryTransactions.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $dCRSummaryTransactions = $this->dCRSummaryTransactionsRepository->find($id);

        if (empty($dCRSummaryTransactions)) {
            Flash::error('D C R Summary Transactions not found');

            return redirect(route('dCRSummaryTransactions.index'));
        }

        return view('d_c_r_summary_transactions.edit')->with('dCRSummaryTransactions', $dCRSummaryTransactions);
    }

    /**
     * Update the specified DCRSummaryTransactions in storage.
     *
     * @param int $id
     * @param UpdateDCRSummaryTransactionsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDCRSummaryTransactionsRequest $request)
    {
        $dCRSummaryTransactions = $this->dCRSummaryTransactionsRepository->find($id);

        if (empty($dCRSummaryTransactions)) {
            Flash::error('D C R Summary Transactions not found');

            return redirect(route('dCRSummaryTransactions.index'));
        }

        $dCRSummaryTransactions = $this->dCRSummaryTransactionsRepository->update($request->all(), $id);

        Flash::success('D C R Summary Transactions updated successfully.');

        return redirect(route('dCRSummaryTransactions.index'));
    }

    /**
     * Remove the specified DCRSummaryTransactions from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $dCRSummaryTransactions = $this->dCRSummaryTransactionsRepository->find($id);

        if (empty($dCRSummaryTransactions)) {
            Flash::error('D C R Summary Transactions not found');

            return redirect(route('dCRSummaryTransactions.index'));
        }

        $this->dCRSummaryTransactionsRepository->delete($id);

        Flash::success('D C R Summary Transactions deleted successfully.');

        return redirect(route('dCRSummaryTransactions.index'));
    }

    public function salesDcrMonitor(Request $request) {        
        $tellers = User::permission('teller create')->get();
        $offices = DB::table('Cashier_DCRSummaryTransactions')
            ->select('Office')
            ->groupBy('Office')
            ->orderBy('Office')
            ->get();

        if ($request['From'] != null || $request['To'] != null) {
            if ($request['Teller'] == 'All') {
                if ($request['Office'] == 'All') {
                        $collection = DB::table('Cashier_DCRSummaryTransactions')
                            ->whereBetween('Day', [$request['From'], $request['To']])
                            // ->where('Teller', $request['Teller'])
                            ->where('ReportDestination', 'COLLECTION')
                            ->whereRaw("Cashier_DCRSummaryTransactions.Status IS NULL AND GLCode IS NOT NULL")
                            ->select('GLCode',
                                DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                                DB::raw("SUM(TRY_CAST(Amount AS DECIMAL(10,2))) AS Amount")
                            )
                            ->groupBy('GLCode')
                            ->orderBy('GLCode')
                            ->get();

                        $sales = DB::table('Cashier_DCRSummaryTransactions')
                            ->whereBetween('Day', [$request['From'], $request['To']])
                            // ->where('Teller', $request['Teller'])
                            ->where('ReportDestination', 'SALES')
                            ->select('GLCode',
                                DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                                DB::raw("SUM(TRY_CAST(Amount AS DECIMAL(10,2))) AS Amount")
                            )
                            ->groupBy('GLCode')
                            ->orderBy('GLCode')
                            ->get();
                } else {
                    $collection = DB::table('Cashier_DCRSummaryTransactions')
                        ->whereBetween('Day', [$request['From'], $request['To']])
                        // ->where('Teller', $request['Teller'])
                        ->where('ReportDestination', 'COLLECTION')
                        ->where('Office', $request['Office'])
                        ->whereRaw("Cashier_DCRSummaryTransactions.Status IS NULL AND GLCode IS NOT NULL")
                        ->select('GLCode',
                            DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                            DB::raw("SUM(TRY_CAST(Amount AS DECIMAL(10,2))) AS Amount")
                        )
                        ->groupBy('GLCode')
                        ->orderBy('GLCode')
                        ->get();

                    $sales = DB::table('Cashier_DCRSummaryTransactions')
                        ->whereBetween('Day', [$request['From'], $request['To']])
                        // ->where('Teller', $request['Teller'])
                        ->where('ReportDestination', 'SALES')
                        ->where('Office', $request['Office'])
                        ->select('GLCode',
                            DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                            DB::raw("SUM(TRY_CAST(Amount AS DECIMAL(10,2))) AS Amount")
                        )
                        ->groupBy('GLCode')
                        ->orderBy('GLCode')
                        ->get();
                }                
            } else {
                if ($request['Office'] == 'All') {
                    $collection = DB::table('Cashier_DCRSummaryTransactions')
                        ->whereBetween('Day', [$request['From'], $request['To']])
                        ->where('Teller', $request['Teller'])
                        ->where('ReportDestination', 'COLLECTION')
                        ->whereRaw("Cashier_DCRSummaryTransactions.Status IS NULL AND GLCode IS NOT NULL")
                        ->select('GLCode',
                            DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                            DB::raw("SUM(TRY_CAST(Amount AS DECIMAL(10,2))) AS Amount")
                        )
                        ->groupBy('GLCode')
                        ->orderBy('GLCode')
                        ->get();

                    $sales = DB::table('Cashier_DCRSummaryTransactions')
                        ->whereBetween('Day', [$request['From'], $request['To']])
                        ->where('Teller', $request['Teller'])
                        ->where('ReportDestination', 'SALES')
                        ->select('GLCode',
                            DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                            DB::raw("SUM(TRY_CAST(Amount AS DECIMAL(10,2))) AS Amount")
                        )
                        ->groupBy('GLCode')
                        ->orderBy('GLCode')
                        ->get();
                } else {
                    $collection = DB::table('Cashier_DCRSummaryTransactions')
                        ->whereBetween('Day', [$request['From'], $request['To']])
                        ->where('Teller', $request['Teller'])
                        ->where('ReportDestination', 'COLLECTION')
                        ->where('Office', $request['Office'])
                        ->whereRaw("Cashier_DCRSummaryTransactions.Status IS NULL AND GLCode IS NOT NULL")
                        ->select('GLCode',
                            DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                            DB::raw("SUM(TRY_CAST(Amount AS DECIMAL(10,2))) AS Amount")
                        )
                        ->groupBy('GLCode')
                        ->orderBy('GLCode')
                        ->get();

                    $sales = DB::table('Cashier_DCRSummaryTransactions')
                        ->whereBetween('Day', [$request['From'], $request['To']])
                        ->where('Teller', $request['Teller'])
                        ->where('ReportDestination', 'SALES')
                        ->where('Office', $request['Office'])
                        ->select('GLCode',
                            DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                            DB::raw("SUM(TRY_CAST(Amount AS DECIMAL(10,2))) AS Amount")
                        )
                        ->groupBy('GLCode')
                        ->orderBy('GLCode')
                        ->get();
                }                
            }            
        } else {
            $collection = [];
            $sales = [];
        }

        return view('/d_c_r_summary_transactions/sales_dcr_monitor', [
            'collection' => $collection,
            'sales' => $sales,
            'tellers' => $tellers,
            'tellerSelect' => $request['Teller'],
            'from' => $request['From'] != null ? $request['From'] : date('Y-m-d'),
            'to' => $request['To'] != null ? $request['To'] : date('Y-m-d', strtotime('+1 day')),
            'offices' => $offices,
            'officeSelect' => $request['Office'],
        ]);
    }

    public function collectionDashboard() {
        return view('/d_c_r_summary_transactions/dashboard', [

        ]);
    }

    public function dashboardGetCollectionPerArea(Request $request) {
        $from = $request['From'];
        $to = $request['To'];

        $latestRate = Rates::orderByDesc('ServicePeriod')
            ->first();
        $period = ($latestRate != null ? $latestRate->ServicePeriod : date('Y-m-01'));

        if ($from != null && $to != null) {
            $data = DB::table('Cashier_PaidBills')
                ->select(
                    'OfficeTransacted',
                    DB::raw("(SELECT SUM(CAST(KwhUsed AS DECIMAL)) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AS KwhUsedTotal"),
                    DB::raw("(SELECT COUNT(id) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AS TotalConsumers"),
                    DB::raw("(SELECT SUM(CAST(Surcharge AS DECIMAL)) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AS TotalSurcharges"),
                    DB::raw("(SELECT SUM(CAST(Form2307TwoPercent AS DECIMAL)) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AS TotalEWT"),
                    DB::raw("(SELECT SUM(CAST(Form2307FivePercent AS DECIMAL)) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AS TotalEVAT"),
                    DB::raw("(SELECT SUM(CAST(AdditionalCharges AS DECIMAL)) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AS TotalOCL"),
                    DB::raw("(SELECT SUM(CAST(Deductions AS DECIMAL)) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AS TotalDeductions"),
                    DB::raw("(SELECT SUM(CAST(NetAmount AS DECIMAL)) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AS TotalAmount"),
                    DB::raw("(SELECT SUM(CAST(Total AS DECIMAL)) FROM Cashier_TransactionIndex pb WHERE pb.TransactionNumber LIKE CONCAT(Cashier_PaidBills.OfficeTransacted, '%') AND Status IS NULL AND pb.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AS TotalMisc"),
                )
                ->groupBy('OfficeTransacted')
                ->orderBy('OfficeTransacted')
                ->get();
        } else {
            $data = DB::table('Cashier_PaidBills')
                ->select(
                    'OfficeTransacted',
                    DB::raw("(SELECT SUM(CAST(KwhUsed AS DECIMAL)) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ServicePeriod='" . $period ."') AS KwhUsedTotal"),
                    DB::raw("(SELECT COUNT(id) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ServicePeriod='" . $period ."') AS TotalConsumers"),
                    DB::raw("(SELECT SUM(CAST(Surcharge AS DECIMAL)) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ServicePeriod='" . $period ."') AS TotalSurcharges"),
                    DB::raw("(SELECT SUM(CAST(Form2307TwoPercent AS DECIMAL)) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ServicePeriod='" . $period ."') AS TotalEWT"),
                    DB::raw("(SELECT SUM(CAST(Form2307FivePercent AS DECIMAL)) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ServicePeriod='" . $period ."') AS TotalEVAT"),
                    DB::raw("(SELECT SUM(CAST(AdditionalCharges AS DECIMAL)) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ServicePeriod='" . $period ."') AS TotalOCL"),
                    DB::raw("(SELECT SUM(CAST(Deductions AS DECIMAL)) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ServicePeriod='" . $period ."') AS TotalDeductions"),
                    DB::raw("(SELECT SUM(CAST(NetAmount AS DECIMAL)) FROM Cashier_PaidBills pb WHERE pb.OfficeTransacted=Cashier_PaidBills.OfficeTransacted AND Status IS NULL AND pb.ServicePeriod='" . $period ."') AS TotalAmount"),
                    DB::raw("(SELECT SUM(CAST(Total AS DECIMAL)) FROM Cashier_TransactionIndex pb WHERE pb.TransactionNumber LIKE CONCAT(Cashier_PaidBills.OfficeTransacted, '%') AND Status IS NULL AND pb.ORDate BETWEEN '" . $period . "' AND '" . date('Y-m-d', strtotime($period . ' +1 month')) . "') AS TotalMisc"),
                )
                ->groupBy('OfficeTransacted')
                ->orderBy('OfficeTransacted')
                ->get();
        }

        $output = "";
        foreach($data as $item) {
            $output .= "<tr>" .
                            "<th><a href='" . route('dCRSummaryTransactions.collection-office-expand', [urlencode($item->OfficeTransacted)]) . "'><i class='fas fa-external-link-alt ico-tab'></i>" . $item->OfficeTransacted . "</a></th>" .
                            "<td class='text-right'>" . number_format($item->KwhUsedTotal, 2) . "</td>" .
                            "<td class='text-right'>" . number_format($item->TotalConsumers) . "</td>" .
                            "<td class='text-right text-info'>₱ " . number_format($item->TotalSurcharges, 2) . "</td>" .
                            "<td class='text-right text-danger'>₱ " . number_format($item->TotalEWT, 2) . "</td>" .
                            "<td class='text-right text-danger'>₱ " . number_format($item->TotalEVAT, 2) . "</td>" .
                            "<td class='text-right text-info'>₱ " . number_format($item->TotalOCL, 2) . "</td>" .
                            "<td class='text-right text-danger'>₱ " . number_format($item->TotalDeductions, 2) . "</td>" .
                            "<th class='text-right text-success'>₱ " . number_format($item->TotalAmount, 2) . "</th>" .
                            "<th class='text-right text-info'>₱ " . number_format($item->TotalMisc, 2) . "</th>" .
                            "<th class='text-right text-primary'>₱ " . number_format(floatval($item->TotalMisc) + floatval($item->TotalAmount), 2) . "</th>" .
                        "</tr>";
        }

        return response()->json($output, 200);
    }

    public function collectionOfficeEpand($office, Request $request) {
        $office = urldecode($office);
        $office = trim($office);
        $teller = $request['Teller'];
        $from = $request['From'];
        $to = $request['To'];

        if ($from == null && $to == null) {
            $from = date('Y-m-d', strtotime('today -1 months'));
            $to = date('Y-m-d');
        }

        if ($teller=='All') {
            $data = DB::table('Cashier_DCRSummaryTransactions')
                ->whereRaw("Day BETWEEN '" . $from . "' AND '" . $to . "'")
                ->where('Office', $office)
                ->where(function ($query) {
                    $query->where('Cashier_DCRSummaryTransactions.ReportDestination', 'COLLECTION')
                        ->orWhere('Cashier_DCRSummaryTransactions.ReportDestination', 'BOTH');
                })  
                ->whereRaw("Cashier_DCRSummaryTransactions.Status IS NULL")
                ->select('GLCode',
                    DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                    DB::raw("SUM(TRY_CAST(Amount AS DECIMAL(10,2))) AS Amount")
                )
                ->groupBy('GLCode')
                ->orderBy('GLCode')
                ->get();

            $powerBills = DB::table('Cashier_PaidBills')
                ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereBetween('Cashier_PaidBills.ORDate', [$from, $to])
                ->whereNull('Cashier_PaidBills.Status')
                ->whereRaw("Cashier_PaidBills.PaymentUsed LIKE '%Cash%' AND Cashier_PaidBills.OfficeTransacted='" . $office . "'")
                ->select('Cashier_PaidBills.*', 
                    DB::raw("(SELECT SUM(TRY_CAST(Amount AS DECIMAL(25,4))) FROM Cashier_PaidBillsDetails WHERE ServicePeriod=Cashier_PaidBills.ServicePeriod AND AccountNumber=Cashier_PaidBills.AccountNumber AND PaymentUsed='Cash') AS CashPaid"),
                    'Billing_ServiceAccounts.ServiceAccountName', 
                    'Billing_ServiceAccounts.OldAccountNo')
                ->orderByDesc('Cashier_PaidBills.created_at')
                ->get();

            $nonPowerBills = DB::table('Cashier_TransactionPaymentDetails')
                ->leftJoin('Cashier_TransactionIndex', 'Cashier_TransactionPaymentDetails.ORNumber', '=', 'Cashier_TransactionIndex.ORNumber')
                ->leftJoin('Billing_ServiceAccounts', 'Cashier_TransactionIndex.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereRaw("(Cashier_TransactionIndex.ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND
                    Cashier_TransactionPaymentDetails.PaymentUsed='Cash' AND Cashier_TransactionIndex.Status IS NULL)")
                ->whereRaw("Cashier_TransactionIndex.TransactionNumber LIKE '" . $office . "%'")
                ->select(
                    'Cashier_TransactionIndex.ORNumber',
                    'Cashier_TransactionIndex.AccountNumber',
                    'Cashier_TransactionPaymentDetails.Amount AS Total',
                    'Cashier_TransactionIndex.PayeeName',
                    'Cashier_TransactionIndex.PaymentDetails',
                    'Billing_ServiceAccounts.OldAccountNo',
                )
                ->orderBy('Cashier_TransactionIndex.ORNumber')
                ->get(); 
                
            $checkPaymentPowerBills = DB::table('Cashier_PaidBillsDetails')
                ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBillsDetails.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('Cashier_PaidBills', function($join) {
                    $join->on('Cashier_PaidBills.ServicePeriod', '=', 'Cashier_PaidBillsDetails.ServicePeriod')
                        ->on('Cashier_PaidBills.AccountNumber', '=', 'Cashier_PaidBillsDetails.AccountNumber');
                })
                ->whereRaw("CAST(Cashier_PaidBillsDetails.created_at AS DATE) BETWEEN '" . $from . "' AND '" . $to . "'")
                ->whereRaw("Cashier_PaidBillsDetails.PaymentUsed LIKE '%Check%' AND Cashier_PaidBills.OfficeTransacted='" . $office . "'")
                ->select('Cashier_PaidBillsDetails.ORNumber',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Cashier_PaidBillsDetails.Amount',
                    'Cashier_PaidBillsDetails.CheckNo',
                    'Cashier_PaidBills.ServicePeriod',
                    'Cashier_PaidBillsDetails.Bank',
                    DB::raw("'POWER BILL' AS Source"));

            $checkPaymentsAll = DB::table("Cashier_TransactionPaymentDetails")
                ->leftJoin('Cashier_TransactionIndex', 'Cashier_TransactionPaymentDetails.ORNumber', '=', 'Cashier_TransactionIndex.ORNumber')
                ->whereBetween('Cashier_TransactionIndex.ORDate', [$from, $to])
                ->whereRaw("Cashier_TransactionPaymentDetails.PaymentUsed LIKE '%Check%'")
                ->whereRaw("Cashier_TransactionIndex.TransactionNumber LIKE '" . $office . "%'")
                ->select('Cashier_TransactionIndex.ORNumber',
                    'Cashier_TransactionIndex.AccountNumber',
                    'Cashier_TransactionIndex.PayeeName',
                    'Cashier_TransactionPaymentDetails.Amount',
                    'Cashier_TransactionPaymentDetails.CheckNo',
                    DB::raw("'1997-01-01' AS ServicePeriod"),
                    'Cashier_TransactionPaymentDetails.Bank',
                    DB::raw("'OTHERS' AS Source")
                )
                ->union($checkPaymentPowerBills)
                ->get();

            $cancelledPowerBills = DB::table('Cashier_PaidBills')
                ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereBetween('Cashier_PaidBills.ORDate', [$from, $to])
                ->whereIn('Cashier_PaidBills.Status', ['CANCELLED', 'PENDING CANCEL'])
                ->whereRaw("Cashier_PaidBills.OfficeTransacted='" . $office . "'")
                ->select('Cashier_PaidBills.ORNumber',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Cashier_PaidBills.NetAmount',
                    'Cashier_PaidBills.KwhUsed',
                    'Cashier_PaidBills.Status',
                    DB::raw("'POWER BILL' AS Source"));

            $cancelledAllPayments = DB::table("Cashier_TransactionIndex")
                ->whereBetween('Cashier_TransactionIndex.ORDate', [$from, $to])
                ->whereIn('Cashier_TransactionIndex.Status', ['CANCELLED', 'PENDING CANCEL'])
                ->whereRaw("Cashier_TransactionIndex.TransactionNumber LIKE '" . $office . "%'")
                ->select('Cashier_TransactionIndex.ORNumber',
                    'Cashier_TransactionIndex.AccountNumber',
                    'Cashier_TransactionIndex.PayeeName',
                    'Cashier_TransactionIndex.Total',
                    DB::raw("'' AS KwhUsed"),
                    'Cashier_TransactionIndex.Status',
                    DB::raw("'OTHERS' AS Source")
                )
                ->union($cancelledPowerBills)
                ->get();
        
        } else {
            $data = DB::table('Cashier_DCRSummaryTransactions')
                ->whereRaw("Day BETWEEN '" . $from . "' AND '" . $to . "'")
                ->where('Teller', $teller)
                ->where('Office', $office)
                ->where(function ($query) {
                    $query->where('Cashier_DCRSummaryTransactions.ReportDestination', 'COLLECTION')
                        ->orWhere('Cashier_DCRSummaryTransactions.ReportDestination', 'BOTH');
                }) 
                ->whereRaw("Cashier_DCRSummaryTransactions.Status IS NULL")
                ->select('GLCode',
                    DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                    DB::raw("SUM(TRY_CAST(Amount AS DECIMAL(10,2))) AS Amount")
                )
                ->groupBy('GLCode')
                ->orderBy('GLCode')
                ->get();

            $powerBills = DB::table('Cashier_PaidBills')
                ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereBetween('Cashier_PaidBills.ORDate', [$from, $to])
                ->where('Cashier_PaidBills.Teller', $teller)
                ->whereNull('Cashier_PaidBills.Status')
                ->whereRaw("Cashier_PaidBills.PaymentUsed LIKE '%Cash%' AND Cashier_PaidBills.OfficeTransacted='" . $office . "'")
                ->select('Cashier_PaidBills.*', 
                    DB::raw("(SELECT SUM(TRY_CAST(Amount AS DECIMAL(25,4))) FROM Cashier_PaidBillsDetails WHERE ServicePeriod=Cashier_PaidBills.ServicePeriod AND AccountNumber=Cashier_PaidBills.AccountNumber AND PaymentUsed='Cash' AND UserId='" . $teller . "') AS CashPaid"),
                    'Billing_ServiceAccounts.ServiceAccountName', 
                    'Billing_ServiceAccounts.OldAccountNo')
                ->orderByDesc('Cashier_PaidBills.created_at')
                ->get();


            $nonPowerBills = DB::table('Cashier_TransactionPaymentDetails')
                ->leftJoin('Cashier_TransactionIndex', 'Cashier_TransactionPaymentDetails.ORNumber', '=', 'Cashier_TransactionIndex.ORNumber')
                ->leftJoin('Billing_ServiceAccounts', 'Cashier_TransactionIndex.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereRaw("(Cashier_TransactionIndex.ORDate BETWEEN '" . $from . "' AND '" . $to . "' AND
                    Cashier_TransactionIndex.UserId='" . $teller . "' AND Cashier_TransactionPaymentDetails.PaymentUsed='Cash' AND Cashier_TransactionIndex.Status IS NULL)")
                ->whereRaw("Cashier_TransactionIndex.TransactionNumber LIKE '" . $office . "%'")
                ->select(
                    'Cashier_TransactionIndex.ORNumber',
                    'Cashier_TransactionIndex.AccountNumber',
                    'Cashier_TransactionPaymentDetails.Amount AS Total',
                    'Cashier_TransactionIndex.PayeeName',
                    'Cashier_TransactionIndex.PaymentDetails',
                    'Billing_ServiceAccounts.OldAccountNo',
                )
                ->orderBy('Cashier_TransactionIndex.ORNumber')
                ->get(); 
        
            $checkPaymentPowerBills = DB::table('Cashier_PaidBillsDetails')
                ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBillsDetails.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('Cashier_PaidBills', function($join) {
                    $join->on('Cashier_PaidBills.ServicePeriod', '=', 'Cashier_PaidBillsDetails.ServicePeriod')
                        ->on('Cashier_PaidBills.AccountNumber', '=', 'Cashier_PaidBillsDetails.AccountNumber');
                })
                ->where('Cashier_PaidBillsDetails.UserId', $teller)
                ->whereRaw("TRY_CAST(Cashier_PaidBillsDetails.created_at AS DATE) BETWEEN '" . $from . "' AND '" . $to . "'")
                ->whereRaw("Cashier_PaidBillsDetails.PaymentUsed LIKE '%Check%' AND Cashier_PaidBills.OfficeTransacted='" . $office . "'")
                ->select('Cashier_PaidBillsDetails.ORNumber',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Cashier_PaidBillsDetails.Amount',
                    'Cashier_PaidBillsDetails.CheckNo',
                    'Cashier_PaidBills.ServicePeriod',
                    'Cashier_PaidBillsDetails.Bank',
                    DB::raw("'POWER BILL' AS Source"));   

            $checkPaymentsAll = DB::table("Cashier_TransactionPaymentDetails")
                ->leftJoin('Cashier_TransactionIndex', 'Cashier_TransactionPaymentDetails.ORNumber', '=', 'Cashier_TransactionIndex.ORNumber')
                ->whereBetween('Cashier_TransactionIndex.ORDate', [$from, $to])
                ->whereRaw("Cashier_TransactionPaymentDetails.PaymentUsed LIKE '%Check%'")
                ->whereRaw("Cashier_TransactionIndex.TransactionNumber LIKE '" . $office . "%'")
                ->where('Cashier_TransactionIndex.UserId', $teller)
                ->select('Cashier_TransactionIndex.ORNumber',
                    'Cashier_TransactionIndex.AccountNumber',
                    'Cashier_TransactionIndex.PayeeName',
                    'Cashier_TransactionPaymentDetails.Amount',
                    'Cashier_TransactionPaymentDetails.CheckNo',
                    DB::raw("'1997-01-01' AS ServicePeriod"),
                    'Cashier_TransactionPaymentDetails.Bank',
                    DB::raw("'OTHERS' AS Source")
                )
                ->union($checkPaymentPowerBills)
                ->get(); 

            $cancelledPowerBills = DB::table('Cashier_PaidBills')
                ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereBetween('Cashier_PaidBills.ORDate', [$from, $to])
                ->whereIn('Cashier_PaidBills.Status', ['CANCELLED', 'PENDING CANCEL'])
                ->where('Cashier_PaidBills.Teller', $teller)                
                ->whereRaw("Cashier_PaidBills.OfficeTransacted='" . $office . "'")
                ->select('Cashier_PaidBills.ORNumber',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Cashier_PaidBills.NetAmount',
                    'Cashier_PaidBills.KwhUsed',
                    'Cashier_PaidBills.Status',
                    DB::raw("'POWER BILL' AS Source"));

            $cancelledAllPayments = DB::table("Cashier_TransactionIndex")
                ->whereBetween('Cashier_TransactionIndex.ORDate', [$from, $to])
                ->whereIn('Cashier_TransactionIndex.Status', ['CANCELLED', 'PENDING CANCEL'])
                ->where('Cashier_TransactionIndex.UserId', $teller)
                ->whereRaw("Cashier_TransactionIndex.TransactionNumber LIKE '" . $office . "%'")
                ->select('Cashier_TransactionIndex.ORNumber',
                    'Cashier_TransactionIndex.AccountNumber',
                    'Cashier_TransactionIndex.PayeeName',
                    'Cashier_TransactionIndex.Total',
                    DB::raw("'' AS KwhUsed"),
                    'Cashier_TransactionIndex.Status',
                    DB::raw("'OTHERS' AS Source")
                )
                ->union($cancelledPowerBills)
                ->get();
        }

        $fromT = date('Y-m-d', strtotime('today -2 months'));
        $toT = date('Y-m-d');

        $tellers = DB::table('Cashier_PaidBills')
            ->leftJoin('users', 'Cashier_PaidBills.Teller', '=', 'users.id')
            ->select('users.name', 'users.id')
            ->groupBy('users.name', 'users.id')
            ->orderBy('users.name')
            ->get();

        return view('/d_c_r_summary_transactions/collection_office_expand', [
            'office' => $office,
            'tellers' => $tellers,
            'data' => $data,
            'powerBills' => $powerBills,
            'nonPowerBills' => $nonPowerBills,
            'checkPayments' => $checkPaymentsAll,
            'cancelledAllPayments' => $cancelledAllPayments,
        ]);
    }

    public function getGLCodePaymentDetails(Request $request) {
        $from = $request['From'];
        $to = $request['To'];
        $glCode = $request['GLCode'];

        $data = DB::table('Cashier_DCRSummaryTransactions')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_DCRSummaryTransactions.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('users', 'Cashier_DCRSummaryTransactions.Teller', '=', 'users.id')
            ->whereRaw("(Cashier_DCRSummaryTransactions.Day BETWEEN '" . $from . "' AND '" . $to . "') 
                AND Cashier_DCRSummaryTransactions.GLCode='" . $glCode . "' AND (TRY_CAST(Amount AS DECIMAL(12,2)) != 0 OR Amount IS NOT NULL) 
                AND ReportDestination IN ('COLLECTION', 'BOTH')")
            ->select(
                'Billing_ServiceAccounts.id',
                'OldAccountNo',
                'ServiceAccountName',
                'Amount',
                'ORNumber',
                'Day',
                'name'
            )
            ->orderBy('Day')
            ->orderBy('ORNumber')
            ->get();

        $output = "";
        $i=1;
        foreach($data as $item) {
            $output .= "<tr>
                            <td>" . $i . "</td>
                            <td><a href='" . ($item->id != null ? route('serviceAccounts.show', [$item->id]) : '') . "'>" . $item->OldAccountNo . "</a></td>
                            <td>" . $item->ServiceAccountName . "</td>
                            <td>" . (is_numeric($item->Amount) ? number_format($item->Amount, 2) : $item->Amount) . "</td>
                            <td>" . $item->ORNumber . "</td>
                            <td>" . date('M d, Y', strtotime($item->Day)) . "</td>
                            <td>" . $item->name . "</td>                           
                        </tr>";
            $i++;
        }

        return response()->json($output, 200);
    }

    public function applicationDcrSummary(Request $request) {
        $from = $request['From'];
        $to = $request['To'];
        $office = $request['Office'];

        if ($office == 'All') {
            $data = DB::table('Cashier_DCRSummaryTransactions')
                ->whereRaw("(Day BETWEEN '" . $from . "' AND '" . $to . "') AND Status='Application'")
                ->where(function ($query) {
                    $query->where('Cashier_DCRSummaryTransactions.ReportDestination', 'COLLECTION')
                        ->orWhere('Cashier_DCRSummaryTransactions.ReportDestination', 'BOTH');
                })  
                ->select('GLCode',
                    DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                    DB::raw("SUM(TRY_CAST(Amount AS DECIMAL(10,2))) AS Amount")
                )
                ->groupBy('GLCode')
                ->orderBy('GLCode')
                ->get();

            $powerBills = DB::table('Cashier_PaidBills')
                ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereBetween('Cashier_PaidBills.ORDate', [$from, $to])
                ->whereRaw("Cashier_PaidBills.Source='APPLICATION ADJUSTMENT'")
                ->select('Cashier_PaidBills.*', 
                    DB::raw("Cashier_PaidBills.NetAmount AS CashPaid"),
                    'Billing_ServiceAccounts.ServiceAccountName', 
                    'Billing_ServiceAccounts.OldAccountNo')
                ->get();
        } else {
            $data = DB::table('Cashier_DCRSummaryTransactions')
                ->whereRaw("(Day BETWEEN '" . $from . "' AND '" . $to . "') AND Status='Application'")
                ->where('Office', $office)
                ->where(function ($query) {
                    $query->where('Cashier_DCRSummaryTransactions.ReportDestination', 'COLLECTION')
                        ->orWhere('Cashier_DCRSummaryTransactions.ReportDestination', 'BOTH');
                })  
                ->select('GLCode',
                    DB::raw("(SELECT Notes FROM Cashier_AccountGLCodes WHERE AccountCode=Cashier_DCRSummaryTransactions.GLCode) AS Description"),
                    DB::raw("SUM(TRY_CAST(Amount AS DECIMAL(10,2))) AS Amount")
                )
                ->groupBy('GLCode')
                ->orderBy('GLCode')
                ->get();

            $powerBills = DB::table('Cashier_PaidBills')
                ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereBetween('Cashier_PaidBills.ORDate', [$from, $to])
                ->whereRaw("Cashier_PaidBills.OfficeTransacted='" . $office . "' AND Cashier_PaidBills.Source='APPLICATION ADJUSTMENT'")
                ->select('Cashier_PaidBills.*', 
                    DB::raw("Cashier_PaidBills.NetAmount AS CashPaid"),
                    'Billing_ServiceAccounts.ServiceAccountName', 
                    'Billing_ServiceAccounts.OldAccountNo')
                ->get();
        }

        return view('/d_c_r_summary_transactions/application_dcr_summary', [
            'data' => $data,
            'powerBills' => $powerBills,
            'offices' => DB::table('Cashier_DCRSummaryTransactions')->whereNotNull('Office')->select('Office')->groupBy('Office')->get(),
        ]);
    }

    public function fixDcr(Request $request) {
        $figure = $request['Figure'];
        $teller = $request['Teller'];
        $day = $request['Day'];
        $office = $request['Office'];

        $dcrSum = new DCRSummaryTransactions;
        $dcrSum->id = IDGenerator::generateIDandRandString();
        $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersPerArea($office);
        $dcrSum->Amount = $figure;
        $dcrSum->Day = $day;
        $dcrSum->Time = date('H:i:s');
        $dcrSum->Teller = $teller;
        // $dcrSum->ORNumber = $orNo;
        $dcrSum->ReportDestination = 'BOTH';
        $dcrSum->Office = $office;
        // $dcrSum->AccountNumber = $bill->AccountNumber;
        $dcrSum->save();

        return DCRSummaryTransactions::getARConsumersPerArea($office);
    }
}
