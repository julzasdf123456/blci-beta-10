<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionAccountTypesRequest;
use App\Http\Requests\UpdateServiceConnectionAccountTypesRequest;
use App\Repositories\ServiceConnectionAccountTypesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServiceConnectionAccountTypesController extends AppBaseController
{
    /** @var  ServiceConnectionAccountTypesRepository */
    private $serviceConnectionAccountTypesRepository;

    public function __construct(ServiceConnectionAccountTypesRepository $serviceConnectionAccountTypesRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionAccountTypesRepository = $serviceConnectionAccountTypesRepo;
    }

    /**
     * Display a listing of the ServiceConnectionAccountTypes.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionAccountTypes = $this->serviceConnectionAccountTypesRepository->all();

        return view('service_connection_account_types.index')
            ->with('serviceConnectionAccountTypes', $serviceConnectionAccountTypes);
    }

    /**
     * Show the form for creating a new ServiceConnectionAccountTypes.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_connection_account_types.create');
    }

    /**
     * Store a newly created ServiceConnectionAccountTypes in storage.
     *
     * @param CreateServiceConnectionAccountTypesRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionAccountTypesRequest $request)
    {
        $input = $request->all();

        $serviceConnectionAccountTypes = $this->serviceConnectionAccountTypesRepository->create($input);

        Flash::success('Service Connection Account Types saved successfully.');

        return redirect(route('serviceConnectionAccountTypes.index'));
    }

    /**
     * Display the specified ServiceConnectionAccountTypes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionAccountTypes = $this->serviceConnectionAccountTypesRepository->find($id);

        if (empty($serviceConnectionAccountTypes)) {
            Flash::error('Service Connection Account Types not found');

            return redirect(route('serviceConnectionAccountTypes.index'));
        }

        return view('service_connection_account_types.show')->with('serviceConnectionAccountTypes', $serviceConnectionAccountTypes);
    }

    /**
     * Show the form for editing the specified ServiceConnectionAccountTypes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionAccountTypes = $this->serviceConnectionAccountTypesRepository->find($id);

        if (empty($serviceConnectionAccountTypes)) {
            Flash::error('Service Connection Account Types not found');

            return redirect(route('serviceConnectionAccountTypes.index'));
        }

        return view('service_connection_account_types.edit')->with('serviceConnectionAccountTypes', $serviceConnectionAccountTypes);
    }

    /**
     * Update the specified ServiceConnectionAccountTypes in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionAccountTypesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionAccountTypesRequest $request)
    {
        $serviceConnectionAccountTypes = $this->serviceConnectionAccountTypesRepository->find($id);

        if (empty($serviceConnectionAccountTypes)) {
            Flash::error('Service Connection Account Types not found');

            return redirect(route('serviceConnectionAccountTypes.index'));
        }

        $serviceConnectionAccountTypes = $this->serviceConnectionAccountTypesRepository->update($request->all(), $id);

        Flash::success('Service Connection Account Types updated successfully.');

        return redirect(route('serviceConnectionAccountTypes.index'));
    }

    /**
     * Remove the specified ServiceConnectionAccountTypes from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionAccountTypes = $this->serviceConnectionAccountTypesRepository->find($id);

        if (empty($serviceConnectionAccountTypes)) {
            Flash::error('Service Connection Account Types not found');

            return redirect(route('serviceConnectionAccountTypes.index'));
        }

        $this->serviceConnectionAccountTypesRepository->delete($id);

        Flash::success('Service Connection Account Types deleted successfully.');

        return redirect(route('serviceConnectionAccountTypes.index'));
    }
}
