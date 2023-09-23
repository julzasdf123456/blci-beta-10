<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateORCancellationsRequest;
use App\Http\Requests\UpdateORCancellationsRequest;
use App\Repositories\ORCancellationsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\PaidBills;
use App\Models\PaidBillsDetails;
use App\Models\ServiceAccounts;
use App\Models\Notifiers;
use App\Models\IDGenerator;
use App\Models\TransactionIndex;
use App\Models\TransactionDetails;
use App\Models\ORCancellations;
use App\Models\DCRSummaryTransactions;
use App\Models\TransacionPaymentDetails;
use Illuminate\Support\Facades\DB;   
use Illuminate\Support\Facades\Auth; 
use Flash;
use Response;

class ORCancellationsController extends AppBaseController
{
    /** @var  ORCancellationsRepository */
    private $oRCancellationsRepository;

    public function __construct(ORCancellationsRepository $oRCancellationsRepo)
    {
        $this->middleware('auth');
        $this->oRCancellationsRepository = $oRCancellationsRepo;
    }

    /**
     * Display a listing of the ORCancellations.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $billsCancellations = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('Cashier_ORCancellations', 'Cashier_PaidBills.id', '=', 'Cashier_ORCancellations.ObjectId')
            ->leftJoin('users', 'Cashier_PaidBills.FiledBy', '=', 'users.id')
            ->where('Cashier_PaidBills.Status', 'PENDING CANCEL')
            ->select('Cashier_PaidBills.ORNumber',
                'Cashier_PaidBills.ORDate',
                'Billing_ServiceAccounts.ServiceAccountName',
                'users.name')
            ->groupBy('Cashier_PaidBills.ORNumber',
                    'Cashier_PaidBills.ORDate',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'users.name')
            ->get();

        $transactionCancellations = DB::table('Cashier_TransactionIndex')
            ->leftJoin('Cashier_ORCancellations', 'Cashier_TransactionIndex.id', '=', 'Cashier_ORCancellations.ObjectId')
            ->leftJoin('users', 'Cashier_TransactionIndex.FiledBy', '=', 'users.id')
            ->where('Cashier_TransactionIndex.Status', 'PENDING CANCEL')
            ->select('Cashier_TransactionIndex.id', 
                'Cashier_TransactionIndex.ORNumber',
                'Cashier_TransactionIndex.ORDate',
                'Cashier_TransactionIndex.PaymentTitle',
                'users.name')
            ->get();

        return view('o_r_cancellations.index', [
            'billsCancellations' => $billsCancellations,
            'transactionCancellations' => $transactionCancellations,
        ]);
    }

    /**
     * Show the form for creating a new ORCancellations.
     *
     * @return Response
     */
    public function create()
    {
        return view('o_r_cancellations.create');
    }

    /**
     * Store a newly created ORCancellations in storage.
     *
     * @param CreateORCancellationsRequest $request
     *
     * @return Response
     */
    public function store(CreateORCancellationsRequest $request)
    {
        $input = $request->all();

        $oRCancellations = $this->oRCancellationsRepository->create($input);

        Flash::success('O R Cancellations saved successfully.');

        return redirect(route('oRCancellations.index'));
    }

    /**
     * Display the specified ORCancellations.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $oRCancellations = ORCancellations::where('ORNumber', $id)->get();

        if (empty($oRCancellations)) {
            Flash::error('O R Cancellations not found');

            return redirect(route('oRCancellations.index'));
        }

        $paidBill = DB::table('Cashier_PaidBills')
            ->leftJoin('Billing_ServiceAccounts', 'Cashier_PaidBills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('Cashier_ORCancellations', 'Cashier_PaidBills.id', '=', 'Cashier_ORCancellations.ObjectId')
            ->leftJoin('users', 'Cashier_PaidBills.FiledBy', '=', 'users.id')
            ->where('Cashier_PaidBills.Status', 'PENDING CANCEL')
            ->where('Cashier_PaidBills.ORNumber', $id)
            ->select('Cashier_PaidBills.ORNumber',
                'Cashier_PaidBills.ORDate',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Cashier_PaidBills.AccountNumber',
                'Cashier_PaidBills.NetAmount',
                'Cashier_PaidBills.id as PaidBillsId',
                'Cashier_ORCancellations.id as ORCancellationId',
                'Cashier_PaidBills.ServicePeriod',
                'users.name')
            ->get();

        return view('o_r_cancellations.show', [
            'orCancellations' => $oRCancellations,
            'paidBill' => $paidBill,
            'orNo' => $id
        ]);
    }

    /**
     * Show the form for editing the specified ORCancellations.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $oRCancellations = $this->oRCancellationsRepository->find($id);

        if (empty($oRCancellations)) {
            Flash::error('O R Cancellations not found');

            return redirect(route('oRCancellations.index'));
        }

        return view('o_r_cancellations.edit')->with('oRCancellations', $oRCancellations);
    }

    /**
     * Update the specified ORCancellations in storage.
     *
     * @param int $id
     * @param UpdateORCancellationsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateORCancellationsRequest $request)
    {
        $oRCancellations = $this->oRCancellationsRepository->find($id);

        if (empty($oRCancellations)) {
            Flash::error('O R Cancellations not found');

            return redirect(route('oRCancellations.index'));
        }

        $oRCancellations = $this->oRCancellationsRepository->update($request->all(), $id);

        Flash::success('O R Cancellations updated successfully.');

        return redirect(route('oRCancellations.index'));
    }

    /**
     * Remove the specified ORCancellations from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $oRCancellations = $this->oRCancellationsRepository->find($id);

        if (empty($oRCancellations)) {
            Flash::error('O R Cancellations not found');

            return redirect(route('oRCancellations.index'));
        }

        $this->oRCancellationsRepository->delete($id);

        Flash::success('O R Cancellations deleted successfully.');

        return redirect(route('oRCancellations.index'));
    }

    public function approveBillsORCancellation($orCancellationId) {
        $oRCancellations = ORCancellations::where('ORNumber', $orCancellationId)->get();
        $paidBill = PaidBills::where('ORNumber', $orCancellationId)->get();

        if ($oRCancellations != null) {
            foreach($oRCancellations as $item) {
                $item->DateTimeApproved = date('Y-m-d H:i:s');
                $item->save();
            }            
        }

        if ($paidBill != null) {
            $filedBy = "";
            foreach($paidBill as $item) {
                $filedBy = $item->FiledBy;

                $item->Status = 'CANCELLED';
                $item->ApprovedBy = Auth::id();
                $item->save();
            }

            // REMOVE FROM DCR 
            DCRSummaryTransactions::where('ORNumber', $orCancellationId)->delete();

            // REMOVE FROM PAIDBILLDETAILS
            PaidBillsDetails::where('ORNumber', $orCancellationId)->delete();

            // ADD NOTIFICATION
            $notifier = new Notifiers;
            $notifier->id = IDGenerator::generateIDandRandString();
            $notifier->Notification = 'Your OR cancellation request for OR No. ' . $orCancellationId . ' has been approved by ' . Auth::user()->name;
            $notifier->From = Auth::id();
            $notifier->To = $filedBy;
            $notifier->Status = 'SENT';
            $notifier->Intent = "OR CANCELLATION"; 
            $notifier->IntentLink = ""; // change later to or cancellation confirmations
            $notifier->ObjectId = $orCancellationId;
            $notifier->save();

            Flash::success('OR number ' . $orCancellationId . ' cancelled');
        }

        return redirect(route('oRCancellations.index'));
    }

    public function otherPaymentsORCancellation() {
        return view('/o_r_cancellations/other_payments', [

        ]);
    }

    public function fetchTransactionIndices(Request $request) {
        $result = DB::table('Cashier_TransactionIndex')
            ->where('ORNumber', 'LIKE', '%' . $request['query'] . '%')
            ->whereNull('Status')
            ->get();

        $output = "";
        foreach ($result as $item) {
            $output .= '<tr onclick=fetchDetails("' . $item->id . '")>
                            <td>' . $item->ORNumber . '</td>
                            <td>' . $item->PaymentTitle . '</td>
                            <td>' . $item->Source . '</td>
                        </tr>';
        }

        return response()->json($output, 200);
    }

    public function fetchTransactionDetails(Request $request) {
        $transaction = TransactionIndex::find($request['id']);

        return response()->json($transaction, 200);
    }

    public function fetchParticulars(Request $request) {
        $particulars = TransactionDetails::where('TransactionIndexId', $request['id'])
            ->get();

        $output = "";
        foreach($particulars as $item) {
            $output .= '<tr>
                            <td>' . $item->Particular . '</td>
                            <td>' . number_format($item->Amount, 2) . '</td>
                            <td>' . number_format($item->VAT, 2) . '</td>
                            <td>' . number_format($item->Total, 2) . '</td>
                        </tr>';
        }

        return response()->json($output, 200);
    }

    public function attemptCancelTransactionOR(Request $request) {
        $transaction = TransactionIndex::find($request['id']);

        if ($transaction != null) {
            $transaction->Status = 'PENDING CANCEL';
            $transaction->FiledBy = Auth::id();
            $transaction->CancellationNotes = $request['Notes'];
            $transaction->save();

            // SAVE TO OR CANCELLATIONS
            $cancellation = new ORCancellations;
            $cancellation->id = IDGenerator::generateIDandRandString();
            $cancellation->ORNumber = $transaction->ORNumber;
            $cancellation->ORDate = $transaction->ORDate;
            $cancellation->From = 'Transactions';
            $cancellation->ObjectId = $transaction->id;
            $cancellation->DateTimeFiled = date('Y-m-d H:i:s');
            $cancellation->save();

            // ADD NOTIFICATION
            $notifier = new Notifiers;
            $notifier->id = IDGenerator::generateIDandRandString();
            $notifier->Notification = 'OR Cancellation requested by ' . Auth::user()->name . ' with OR Number ' . $transaction->ORNumber;
            $notifier->From = Auth::id();
            $notifier->To = env('APP_CASHIER_HEAD_ID');
            $notifier->Status = 'SENT';
            $notifier->Intent = "OR CANCELLATION"; 
            $notifier->IntentLink = ""; // change later to or cancellation confirmations
            $notifier->ObjectId = $transaction->id;
            $notifier->save();

            return response()->json('ok', 200);
        } else {
            return response()->json('not found', 404);
        }
    }

    public function showOtherPayments($id) {
        $transaction = TransactionIndex::find($id);
        $user = DB::table('users')->where('id', $transaction->FiledBy)->first();
        $transactionDetails = TransactionDetails::where('TransactionIndexId', $transaction->id)->get();
        $oRCancellations = ORCancellations::where('ObjectId', $transaction->id)->first();

        return view('/o_r_cancellations/show_other_payments', [
            'transaction' => $transaction,
            'user' => $user,
            'transactionDetails' => $transactionDetails,
            'orCancellations' => $oRCancellations,
        ]);
    }

    public function approveTransactionCancellation($id) {
        $oRCancellations = $this->oRCancellationsRepository->find($id);
        $transaction = TransactionIndex::find($oRCancellations->ObjectId);

        if ($oRCancellations != null) {
            $oRCancellations->DateTimeApproved = date('Y-m-d H:i:s');
            $oRCancellations->save();
        }

        if ($transaction != null) {
            $transaction->Status = 'CANCELLED';
            $transaction->ApprovedBy = Auth::id();
            $transaction->save();

            // REMOVE FROM DCR 
            DCRSummaryTransactions::where('ORNumber', $transaction->ORNumber)->delete();

            // REMOVE FROM Transaction Details
            TransactionDetails::where('TransactionIndexId', $transaction->id)->delete();
            TransacionPaymentDetails::where('ORNumber', $transaction->ORNumber)->delete();

            // ADD NOTIFICATION
            $notifier = new Notifiers;
            $notifier->id = IDGenerator::generateIDandRandString();
            $notifier->Notification = 'Your OR cancellation request for OR number ' . $transaction->ORNumber . ' has been approved by ' . Auth::user()->name;
            $notifier->From = Auth::id();
            $notifier->To = $transaction->FiledBy;
            $notifier->Status = 'SENT';
            $notifier->Intent = "OR CANCELLATION"; 
            $notifier->IntentLink = ""; // change later to or cancellation confirmations
            $notifier->ObjectId = $transaction->id;
            $notifier->save();

            Flash::success('OR number ' . $transaction->ORNumber . ' cancelled');
        }

        return redirect(route('oRCancellations.index'));
    }
}
