<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCacheOtherPaymentsRequest;
use App\Http\Requests\UpdateCacheOtherPaymentsRequest;
use App\Repositories\CacheOtherPaymentsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\CacheOtherPayments;
use App\Models\TransactionDetails;
use App\Models\TransactionIndex;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DCRSummaryTransactions;
use App\Models\TransacionPaymentDetails;
use App\Models\ORAssigning;
use App\Models\ServiceAccounts;
use Flash;
use Response;

class CacheOtherPaymentsController extends AppBaseController
{
    /** @var  CacheOtherPaymentsRepository */
    private $cacheOtherPaymentsRepository;

    public function __construct(CacheOtherPaymentsRepository $cacheOtherPaymentsRepo)
    {
        $this->middleware('auth');
        $this->cacheOtherPaymentsRepository = $cacheOtherPaymentsRepo;
    }

    /**
     * Display a listing of the CacheOtherPayments.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $cacheOtherPayments = $this->cacheOtherPaymentsRepository->all();

        return view('cache_other_payments.index')
            ->with('cacheOtherPayments', $cacheOtherPayments);
    }

    /**
     * Show the form for creating a new CacheOtherPayments.
     *
     * @return Response
     */
    public function create()
    {
        return view('cache_other_payments.create');
    }

    /**
     * Store a newly created CacheOtherPayments in storage.
     *
     * @param CreateCacheOtherPaymentsRequest $request
     *
     * @return Response
     */
    public function store(CreateCacheOtherPaymentsRequest $request)
    {
        $input = $request->all();
        $input['id'] = IDGenerator::generateIDandRandString();
        $cacheOtherPayments = $this->cacheOtherPaymentsRepository->create($input);

        Flash::success('Cache Other Payments saved successfully.');

        return response()->json($cacheOtherPayments, 200);
    }

    /**
     * Display the specified CacheOtherPayments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cacheOtherPayments = $this->cacheOtherPaymentsRepository->find($id);

        if (empty($cacheOtherPayments)) {
            Flash::error('Cache Other Payments not found');

            return redirect(route('cacheOtherPayments.index'));
        }

        return view('cache_other_payments.show')->with('cacheOtherPayments', $cacheOtherPayments);
    }

    /**
     * Show the form for editing the specified CacheOtherPayments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cacheOtherPayments = $this->cacheOtherPaymentsRepository->find($id);

        if (empty($cacheOtherPayments)) {
            Flash::error('Cache Other Payments not found');

            return redirect(route('cacheOtherPayments.index'));
        }

        return view('cache_other_payments.edit')->with('cacheOtherPayments', $cacheOtherPayments);
    }

    /**
     * Update the specified CacheOtherPayments in storage.
     *
     * @param int $id
     * @param UpdateCacheOtherPaymentsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCacheOtherPaymentsRequest $request)
    {
        $cacheOtherPayments = $this->cacheOtherPaymentsRepository->find($id);

        if (empty($cacheOtherPayments)) {
            Flash::error('Cache Other Payments not found');

            return redirect(route('cacheOtherPayments.index'));
        }

        $cacheOtherPayments = $this->cacheOtherPaymentsRepository->update($request->all(), $id);

        Flash::success('Cache Other Payments updated successfully.');

        return redirect(route('cacheOtherPayments.index'));
    }

    /**
     * Remove the specified CacheOtherPayments from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cacheOtherPayments = $this->cacheOtherPaymentsRepository->find($id);

        if (empty($cacheOtherPayments)) {
            Flash::error('Cache Other Payments not found');

            return redirect(route('cacheOtherPayments.index'));
        }

        $this->cacheOtherPaymentsRepository->delete($id);

        // Flash::success('Cache Other Payments deleted successfully.');

        return response()->json($cacheOtherPayments, 200);
    }

    public function fetchCached(Request $request) {
        $cacheOtherPayments = CacheOtherPayments::where('AccountNumber', $request['AccountNumber'])->get();

        return response()->json($cacheOtherPayments, 200);
    }

    public function saveOtherPayments(Request $request) {
        $cacheOtherPayments = CacheOtherPayments::where('AccountNumber', $request['AccountNumber'])->get();
        $account = ServiceAccounts::find($request['AccountNumber']);

        // SAVE TRANSACTION
        // $id = $request['TransactionId'];
        $id = IDGenerator::generateID();

        $subTotal = 0.0;
        $vat = 0.0;
        $total = 0.0;

        $transactionIndex = new TransactionIndex;
        $transactionIndex->id = $id;
        $transactionIndex->TransactionNumber = env('APP_LOCATION') . '-' . $id;
        $transactionIndex->PaymentTitle = $account != null ? $account->ServiceAccountName : $request['PaymentTitle'];
        $transactionIndex->PaymentDetails = $request['PaymentTitle'];
        $transactionIndex->ORNumber = $request['ORNumber'];
        $transactionIndex->ORDate = date('Y-m-d');
        $transactionIndex->ObjectId = $request['AccountNumber'];
        $transactionIndex->Source = 'Other Payments';
        $transactionIndex->PaymentUsed = $request['PaymentUsed'];
        $transactionIndex->PayeeName = $request['PaymentTitle'];
        $transactionIndex->UserId = Auth::id();
        $transactionIndex->AccountNumber = $request['AccountNumber'];

        foreach($cacheOtherPayments as $item) {
            $subTotal += floatval($item->Amount);
            $vat += floatval($item->VAT);
            $total += floatval($item->Total);

            $transactionDetails = new TransactionDetails;
            $transactionDetails->id = IDGenerator::generateIDandRandString();
            $transactionDetails->TransactionIndexId = $id;
            $transactionDetails->Particular = $item->Particular;
            $transactionDetails->Amount = $item->Amount;
            $transactionDetails->VAT = $item->VAT;
            $transactionDetails->Total = $item->Total;
            $transactionDetails->AccountCode = $item->AccountCode;
            $transactionDetails->save();

            // SAVE DCR TRANSACTIONS
            if ($item->AccountCode != null) {
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = $item->AccountCode;
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
        
        $transactionIndex->SubTotal = round($subTotal, 2);
        $transactionIndex->VAT = round($vat, 2);
        $transactionIndex->Total = round($total, 2);
        $transactionIndex->save();

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

        // CLEAR CACHE PAYMENTS
        CacheOtherPayments::where('AccountNumber', $request['AccountNumber'])->delete();

        // SAVE OR
        $saveOR = ORAssigning::where('ORNumber', $transactionIndex->ORNumber)
            ->where('UserId', Auth::id())
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
}
