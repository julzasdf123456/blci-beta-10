<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionMatPayablesRequest;
use App\Http\Requests\UpdateServiceConnectionMatPayablesRequest;
use App\Repositories\ServiceConnectionMatPayablesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServiceConnectionMatPayablesController extends AppBaseController
{
    /** @var  ServiceConnectionMatPayablesRepository */
    private $serviceConnectionMatPayablesRepository;

    public function __construct(ServiceConnectionMatPayablesRepository $serviceConnectionMatPayablesRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionMatPayablesRepository = $serviceConnectionMatPayablesRepo;
    }

    /**
     * Display a listing of the ServiceConnectionMatPayables.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionMatPayables = $this->serviceConnectionMatPayablesRepository->all();

        return view('service_connection_mat_payables.index')
            ->with('serviceConnectionMatPayables', $serviceConnectionMatPayables);
    }

    /**
     * Show the form for creating a new ServiceConnectionMatPayables.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_connection_mat_payables.create');
    }

    /**
     * Store a newly created ServiceConnectionMatPayables in storage.
     *
     * @param CreateServiceConnectionMatPayablesRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionMatPayablesRequest $request)
    {
        $input = $request->all();

        $serviceConnectionMatPayables = $this->serviceConnectionMatPayablesRepository->create($input);

        Flash::success('Service Connection Mat Payables saved successfully.');

        return redirect(route('serviceConnectionMatPayables.index'));
    }

    /**
     * Display the specified ServiceConnectionMatPayables.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionMatPayables = $this->serviceConnectionMatPayablesRepository->find($id);

        if (empty($serviceConnectionMatPayables)) {
            Flash::error('Service Connection Mat Payables not found');

            return redirect(route('serviceConnectionMatPayables.index'));
        }

        return view('service_connection_mat_payables.show')->with('serviceConnectionMatPayables', $serviceConnectionMatPayables);
    }

    /**
     * Show the form for editing the specified ServiceConnectionMatPayables.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionMatPayables = $this->serviceConnectionMatPayablesRepository->find($id);

        if (empty($serviceConnectionMatPayables)) {
            Flash::error('Service Connection Mat Payables not found');

            return redirect(route('serviceConnectionMatPayables.index'));
        }

        return view('service_connection_mat_payables.edit')->with('serviceConnectionMatPayables', $serviceConnectionMatPayables);
    }

    /**
     * Update the specified ServiceConnectionMatPayables in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionMatPayablesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionMatPayablesRequest $request)
    {
        $serviceConnectionMatPayables = $this->serviceConnectionMatPayablesRepository->find($id);

        if (empty($serviceConnectionMatPayables)) {
            Flash::error('Service Connection Mat Payables not found');

            return redirect(route('serviceConnectionMatPayables.index'));
        }

        $serviceConnectionMatPayables = $this->serviceConnectionMatPayablesRepository->update($request->all(), $id);

        Flash::success('Service Connection Mat Payables updated successfully.');

        return redirect(route('serviceConnectionMatPayables.index'));
    }

    /**
     * Remove the specified ServiceConnectionMatPayables from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionMatPayables = $this->serviceConnectionMatPayablesRepository->find($id);

        if (empty($serviceConnectionMatPayables)) {
            Flash::error('Service Connection Mat Payables not found');

            return redirect(route('serviceConnectionMatPayables.index'));
        }

        $this->serviceConnectionMatPayablesRepository->delete($id);

        Flash::success('Service Connection Mat Payables deleted successfully.');

        return redirect(route('serviceConnectionMatPayables.index'));
    }
}
