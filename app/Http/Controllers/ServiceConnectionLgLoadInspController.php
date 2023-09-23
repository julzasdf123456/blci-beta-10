<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionLgLoadInspRequest;
use App\Http\Requests\UpdateServiceConnectionLgLoadInspRequest;
use App\Repositories\ServiceConnectionLgLoadInspRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServiceConnectionLgLoadInspController extends AppBaseController
{
    /** @var  ServiceConnectionLgLoadInspRepository */
    private $serviceConnectionLgLoadInspRepository;

    public function __construct(ServiceConnectionLgLoadInspRepository $serviceConnectionLgLoadInspRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionLgLoadInspRepository = $serviceConnectionLgLoadInspRepo;
    }

    /**
     * Display a listing of the ServiceConnectionLgLoadInsp.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionLgLoadInsps = $this->serviceConnectionLgLoadInspRepository->all();

        return view('service_connection_lg_load_insps.index')
            ->with('serviceConnectionLgLoadInsps', $serviceConnectionLgLoadInsps);
    }

    /**
     * Show the form for creating a new ServiceConnectionLgLoadInsp.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_connection_lg_load_insps.create');
    }

    /**
     * Store a newly created ServiceConnectionLgLoadInsp in storage.
     *
     * @param CreateServiceConnectionLgLoadInspRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionLgLoadInspRequest $request)
    {
        $input = $request->all();

        $serviceConnectionLgLoadInsp = $this->serviceConnectionLgLoadInspRepository->create($input);

        Flash::success('Service Connection Lg Load Insp saved successfully.');

        return redirect(route('serviceConnectionLgLoadInsps.index'));
    }

    /**
     * Display the specified ServiceConnectionLgLoadInsp.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionLgLoadInsp = $this->serviceConnectionLgLoadInspRepository->find($id);

        if (empty($serviceConnectionLgLoadInsp)) {
            Flash::error('Service Connection Lg Load Insp not found');

            return redirect(route('serviceConnectionLgLoadInsps.index'));
        }

        return view('service_connection_lg_load_insps.show')->with('serviceConnectionLgLoadInsp', $serviceConnectionLgLoadInsp);
    }

    /**
     * Show the form for editing the specified ServiceConnectionLgLoadInsp.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionLgLoadInsp = $this->serviceConnectionLgLoadInspRepository->find($id);

        if (empty($serviceConnectionLgLoadInsp)) {
            Flash::error('Service Connection Lg Load Insp not found');

            return redirect(route('serviceConnectionLgLoadInsps.index'));
        }

        return view('service_connection_lg_load_insps.edit')->with('serviceConnectionLgLoadInsp', $serviceConnectionLgLoadInsp);
    }

    /**
     * Update the specified ServiceConnectionLgLoadInsp in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionLgLoadInspRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionLgLoadInspRequest $request)
    {
        $serviceConnectionLgLoadInsp = $this->serviceConnectionLgLoadInspRepository->find($id);

        if (empty($serviceConnectionLgLoadInsp)) {
            Flash::error('Service Connection Lg Load Insp not found');

            return redirect(route('serviceConnectionLgLoadInsps.index'));
        }

        $serviceConnectionLgLoadInsp = $this->serviceConnectionLgLoadInspRepository->update($request->all(), $id);

        Flash::success('Service Connection Lg Load Insp updated successfully.');

        return redirect(route('serviceConnectionLgLoadInsps.index'));
    }

    /**
     * Remove the specified ServiceConnectionLgLoadInsp from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionLgLoadInsp = $this->serviceConnectionLgLoadInspRepository->find($id);

        if (empty($serviceConnectionLgLoadInsp)) {
            Flash::error('Service Connection Lg Load Insp not found');

            return redirect(route('serviceConnectionLgLoadInsps.index'));
        }

        $this->serviceConnectionLgLoadInspRepository->delete($id);

        Flash::success('Service Connection Lg Load Insp deleted successfully.');

        return redirect(route('serviceConnectionLgLoadInsps.index'));
    }
}
