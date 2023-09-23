<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionMatPaymentsRequest;
use App\Http\Requests\UpdateServiceConnectionMatPaymentsRequest;
use App\Repositories\ServiceConnectionMatPaymentsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServiceConnectionMatPaymentsController extends AppBaseController
{
    /** @var  ServiceConnectionMatPaymentsRepository */
    private $serviceConnectionMatPaymentsRepository;

    public function __construct(ServiceConnectionMatPaymentsRepository $serviceConnectionMatPaymentsRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionMatPaymentsRepository = $serviceConnectionMatPaymentsRepo;
    }

    /**
     * Display a listing of the ServiceConnectionMatPayments.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionMatPayments = $this->serviceConnectionMatPaymentsRepository->all();

        return view('service_connection_mat_payments.index')
            ->with('serviceConnectionMatPayments', $serviceConnectionMatPayments);
    }

    /**
     * Show the form for creating a new ServiceConnectionMatPayments.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_connection_mat_payments.create');
    }

    /**
     * Store a newly created ServiceConnectionMatPayments in storage.
     *
     * @param CreateServiceConnectionMatPaymentsRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionMatPaymentsRequest $request)
    {
        $input = $request->all();

        $serviceConnectionMatPayments = $this->serviceConnectionMatPaymentsRepository->create($input);

        // Flash::success('Service Connection Mat Payments saved successfully.');

        return json_encode([
            'result' => 'ok'
        ]);
    }

    /**
     * Display the specified ServiceConnectionMatPayments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionMatPayments = $this->serviceConnectionMatPaymentsRepository->find($id);

        if (empty($serviceConnectionMatPayments)) {
            Flash::error('Service Connection Mat Payments not found');

            return redirect(route('serviceConnectionMatPayments.index'));
        }

        return view('service_connection_mat_payments.show')->with('serviceConnectionMatPayments', $serviceConnectionMatPayments);
    }

    /**
     * Show the form for editing the specified ServiceConnectionMatPayments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionMatPayments = $this->serviceConnectionMatPaymentsRepository->find($id);

        if (empty($serviceConnectionMatPayments)) {
            Flash::error('Service Connection Mat Payments not found');

            return redirect(route('serviceConnectionMatPayments.index'));
        }

        return view('service_connection_mat_payments.edit')->with('serviceConnectionMatPayments', $serviceConnectionMatPayments);
    }

    /**
     * Update the specified ServiceConnectionMatPayments in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionMatPaymentsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionMatPaymentsRequest $request)
    {
        $serviceConnectionMatPayments = $this->serviceConnectionMatPaymentsRepository->find($id);

        if (empty($serviceConnectionMatPayments)) {
            Flash::error('Service Connection Mat Payments not found');

            return redirect(route('serviceConnectionMatPayments.index'));
        }

        $serviceConnectionMatPayments = $this->serviceConnectionMatPaymentsRepository->update($request->all(), $id);

        Flash::success('Service Connection Mat Payments updated successfully.');

        return redirect(route('serviceConnectionMatPayments.index'));
    }

    /**
     * Remove the specified ServiceConnectionMatPayments from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionMatPayments = $this->serviceConnectionMatPaymentsRepository->find($id);

        // if (empty($serviceConnectionMatPayments)) {
        //     Flash::error('Service Connection Mat Payments not found');

        //     return redirect(route('serviceConnectionMatPayments.index'));
        // }

        $this->serviceConnectionMatPaymentsRepository->delete($id);

        // Flash::success('Service Connection Mat Payments deleted successfully.');

        // return redirect(route('serviceConnectionMatPayments.index'));
        return json_encode([
            'result' => 'ok',
        ]);
    }
}
