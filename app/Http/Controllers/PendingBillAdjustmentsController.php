<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePendingBillAdjustmentsRequest;
use App\Http\Requests\UpdatePendingBillAdjustmentsRequest;
use App\Repositories\PendingBillAdjustmentsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\PendingBillAdjustments;
use App\Models\Bills;
use App\Models\ServiceAccounts;
use App\Models\Readings;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class PendingBillAdjustmentsController extends AppBaseController
{
    /** @var  PendingBillAdjustmentsRepository */
    private $pendingBillAdjustmentsRepository;

    public function __construct(PendingBillAdjustmentsRepository $pendingBillAdjustmentsRepo)
    {
        $this->middleware('auth');
        $this->pendingBillAdjustmentsRepository = $pendingBillAdjustmentsRepo;
    }

    /**
     * Display a listing of the PendingBillAdjustments.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $pendingBillAdjustments = DB::table('Billing_PendingBillAdjustments')
            ->groupBy('ServicePeriod')
            ->select('ServicePeriod')
            ->whereNull('Confirmed')
            ->orderByDesc('ServicePeriod')
            ->get();

        return view('pending_bill_adjustments.index')
            ->with('pendingBillAdjustments', $pendingBillAdjustments);
    }

    /**
     * Show the form for creating a new PendingBillAdjustments.
     *
     * @return Response
     */
    public function create()
    {
        return view('pending_bill_adjustments.create');
    }

    /**
     * Store a newly created PendingBillAdjustments in storage.
     *
     * @param CreatePendingBillAdjustmentsRequest $request
     *
     * @return Response
     */
    public function store(CreatePendingBillAdjustmentsRequest $request)
    {
        $input = $request->all();

        $pendingBillAdjustments = PendingBillAdjustments::where('AccountNumber', $input['AccountNumber'])
            ->where('ServicePeriod', $input['ServicePeriod'])
            ->first();

        if ($pendingBillAdjustments != null) {
            $pendingBillAdjustments->KwhUsed = $input['KwhUsed'];
            $pendingBillAdjustments->save();
        } else {
            $pendingBillAdjustments = $this->pendingBillAdjustmentsRepository->create($input);
        }        

        // Flash::success('Pending Bill Adjustments saved successfully.');

        // return redirect(route('pendingBillAdjustments.index'));
        return response()->json($pendingBillAdjustments, 200);
    }

    /**
     * Display the specified PendingBillAdjustments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $pendingBillAdjustments = $this->pendingBillAdjustmentsRepository->find($id);

        if (empty($pendingBillAdjustments)) {
            Flash::error('Pending Bill Adjustments not found');

            return redirect(route('pendingBillAdjustments.index'));
        }

        return view('pending_bill_adjustments.show')->with('pendingBillAdjustments', $pendingBillAdjustments);
    }

    /**
     * Show the form for editing the specified PendingBillAdjustments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $pendingBillAdjustments = $this->pendingBillAdjustmentsRepository->find($id);

        if (empty($pendingBillAdjustments)) {
            Flash::error('Pending Bill Adjustments not found');

            return redirect(route('pendingBillAdjustments.index'));
        }

        return view('pending_bill_adjustments.edit')->with('pendingBillAdjustments', $pendingBillAdjustments);
    }

    /**
     * Update the specified PendingBillAdjustments in storage.
     *
     * @param int $id
     * @param UpdatePendingBillAdjustmentsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePendingBillAdjustmentsRequest $request)
    {
        $pendingBillAdjustments = $this->pendingBillAdjustmentsRepository->find($id);

        if (empty($pendingBillAdjustments)) {
            Flash::error('Pending Bill Adjustments not found');

            return redirect(route('pendingBillAdjustments.index'));
        }

        $pendingBillAdjustments = $this->pendingBillAdjustmentsRepository->update($request->all(), $id);

        Flash::success('Pending Bill Adjustments updated successfully.');

        return redirect(route('pendingBillAdjustments.index'));
    }

    /**
     * Remove the specified PendingBillAdjustments from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $pendingBillAdjustments = $this->pendingBillAdjustmentsRepository->find($id);

        if (empty($pendingBillAdjustments)) {
            Flash::error('Pending Bill Adjustments not found');

            return redirect(route('pendingBillAdjustments.index'));
        }

        $this->pendingBillAdjustmentsRepository->delete($id);

        Flash::success('Pending Bill Adjustments deleted successfully.');

        return redirect(route('pendingBillAdjustments.index'));
    }

    public function openReadingAdjustments($servicePeriod) {
        $pendingBillAdjustments = DB::table('Billing_PendingBillAdjustments')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_PendingBillAdjustments.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('users', 'Billing_PendingBillAdjustments.UserId', '=', 'users.id')
            ->whereNull('Billing_PendingBillAdjustments.Confirmed')
            ->where('Billing_PendingBillAdjustments.ServicePeriod', $servicePeriod)
            ->select('Billing_PendingBillAdjustments.id',
                'Billing_PendingBillAdjustments.ReadingId',
                'Billing_PendingBillAdjustments.AccountNumber',
                'Billing_PendingBillAdjustments.KwhUsed',
                'Billing_PendingBillAdjustments.ReadDate',
                'Billing_PendingBillAdjustments.Office',
                'Billing_ServiceAccounts.ServiceAccountName',
                'users.name')
            ->get();

        return view('/pending_bill_adjustments/open_reading_adjustments', [
            'servicePeriod' => $servicePeriod,
            'pendingBillAdjustments' => $pendingBillAdjustments,
        ]);
    }

    public function confirmAllAdjustments($servicePeriod) {
        $pendingBillAdjustments = PendingBillAdjustments::where('ServicePeriod', $servicePeriod)
            ->whereNull('Confirmed')
            ->get();

        foreach($pendingBillAdjustments as $item) {
            $account = ServiceAccounts::find($item->AccountNumber);

            $presReading = Readings::find($item->ReadingId);
            $prevReading = Readings::where('ServicePeriod', date('Y-m-01', strtotime($presReading->ServicePeriod . ' -1 month')))
                ->orderByDesc('ServicePeriod')
                ->first();

            // BILL ALL
            $bill = Bills::computeRegularBill($account, null, $item->KwhUsed, ($prevReading!=null ? $prevReading->KwhUsed : 0), $presReading->KwhUsed, $servicePeriod, $item->ReadDate, 0, 0, 'false');

            $bill->Notes = 'ZERO READING ADJUSTMENT';
            $bill->save();

            $item->Confirmed = 'Yes';
            $item->save();
        }

        Flash::success('Pending Bill Adjustments for ' . date('F Y', strtotime($servicePeriod)) . ' confirmed successfully.');

        return redirect(route('pendingBillAdjustments.index'));
    }

    public function confirmAdjustment($pendingBillAdjustmentId) {
        $pendingBillAdjustments = PendingBillAdjustments::find($pendingBillAdjustmentId);

        if ($pendingBillAdjustments != null) {
            $account = ServiceAccounts::find($pendingBillAdjustments->AccountNumber);

            $presReading = Readings::find($pendingBillAdjustments->ReadingId);
            $prevReading = Readings::where('ServicePeriod', date('Y-m-01', strtotime($presReading->ServicePeriod . ' -1 month')))
                ->orderByDesc('ServicePeriod')
                ->first();

            // BILL
            $bill = Bills::computeRegularBill($account, null, $pendingBillAdjustments->KwhUsed, ($prevReading!=null ? $prevReading->KwhUsed : 0), $presReading->KwhUsed, $pendingBillAdjustments->ServicePeriod, $pendingBillAdjustments->ReadDate, 0, 0, 'false');
            
            $bill->Notes = 'ZERO READING ADJUSTMENT';
            $bill->save();
        
            $pendingBillAdjustments->Confirmed = 'Yes';
            $pendingBillAdjustments->save();
        } 

        return redirect(route('pendingBillAdjustments.open-reading-adjustments', [$pendingBillAdjustments->ServicePeriod]));
    }
}
