<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionTotalPaymentsRequest;
use App\Http\Requests\UpdateServiceConnectionTotalPaymentsRequest;
use App\Repositories\ServiceConnectionTotalPaymentsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\ServiceConnectionTimeframes;
use App\Models\IDGenerator;
use Illuminate\Support\Facades\Auth;
use Flash;
use Response;

class ServiceConnectionTotalPaymentsController extends AppBaseController
{
    /** @var  ServiceConnectionTotalPaymentsRepository */
    private $serviceConnectionTotalPaymentsRepository;

    public function __construct(ServiceConnectionTotalPaymentsRepository $serviceConnectionTotalPaymentsRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionTotalPaymentsRepository = $serviceConnectionTotalPaymentsRepo;
    }

    /**
     * Display a listing of the ServiceConnectionTotalPayments.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionTotalPayments = $this->serviceConnectionTotalPaymentsRepository->all();

        return view('service_connection_total_payments.index')
            ->with('serviceConnectionTotalPayments', $serviceConnectionTotalPayments);
    }

    /**
     * Show the form for creating a new ServiceConnectionTotalPayments.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_connection_total_payments.create');
    }

    /**
     * Store a newly created ServiceConnectionTotalPayments in storage.
     *
     * @param CreateServiceConnectionTotalPaymentsRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionTotalPaymentsRequest $request)
    {
        $input = $request->all();

        $serviceConnectionTotalPayments = $this->serviceConnectionTotalPaymentsRepository->create($input);

        Flash::success('Service Connection Total Payments saved successfully.');

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $input['ServiceConnectionId'];
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Payment Invoice Created';
        $timeFrame->save();

        // return redirect(route('serviceConnectionTotalPayments.index'));
        return redirect()->action([ServiceConnectionsController::class, 'show'], [$input['ServiceConnectionId']]); 
    }

    /**
     * Display the specified ServiceConnectionTotalPayments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionTotalPayments = $this->serviceConnectionTotalPaymentsRepository->find($id);

        if (empty($serviceConnectionTotalPayments)) {
            Flash::error('Service Connection Total Payments not found');

            return redirect(route('serviceConnectionTotalPayments.index'));
        }

        return view('service_connection_total_payments.show')->with('serviceConnectionTotalPayments', $serviceConnectionTotalPayments);
          
    }

    /**
     * Show the form for editing the specified ServiceConnectionTotalPayments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionTotalPayments = $this->serviceConnectionTotalPaymentsRepository->find($id);

        if (empty($serviceConnectionTotalPayments)) {
            Flash::error('Service Connection Total Payments not found');

            return redirect(route('serviceConnectionTotalPayments.index'));
        }

        return view('service_connection_total_payments.edit')->with('serviceConnectionTotalPayments', $serviceConnectionTotalPayments);
    }

    /**
     * Update the specified ServiceConnectionTotalPayments in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionTotalPaymentsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionTotalPaymentsRequest $request)
    {
        $serviceConnectionTotalPayments = $this->serviceConnectionTotalPaymentsRepository->find($id);

        if (empty($serviceConnectionTotalPayments)) {
            Flash::error('Service Connection Total Payments not found');

            return redirect(route('serviceConnectionTotalPayments.index'));
        }

        $serviceConnectionTotalPayments = $this->serviceConnectionTotalPaymentsRepository->update($request->all(), $id);

        // Flash::success('Service Connection Total Payments updated successfully.');

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $request['ServiceConnectionId'];
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Payment Invoice Updated';
        $timeFrame->save();

        // return redirect(route('serviceConnectionTotalPayments.index'));
        return redirect()->action([ServiceConnectionsController::class, 'show'], [$request['ServiceConnectionId']]); 
    }

    /**
     * Remove the specified ServiceConnectionTotalPayments from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionTotalPayments = $this->serviceConnectionTotalPaymentsRepository->find($id);

        if (empty($serviceConnectionTotalPayments)) {
            Flash::error('Service Connection Total Payments not found');

            return redirect(route('serviceConnectionTotalPayments.index'));
        }

        $this->serviceConnectionTotalPaymentsRepository->delete($id);

        Flash::success('Service Connection Total Payments deleted successfully.');

        return redirect(route('serviceConnectionTotalPayments.index'));
    }
}
