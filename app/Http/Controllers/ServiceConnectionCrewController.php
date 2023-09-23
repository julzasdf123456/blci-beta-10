<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionCrewRequest;
use App\Http\Requests\UpdateServiceConnectionCrewRequest;
use App\Repositories\ServiceConnectionCrewRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServiceConnectionCrewController extends AppBaseController
{
    /** @var  ServiceConnectionCrewRepository */
    private $serviceConnectionCrewRepository;

    public function __construct(ServiceConnectionCrewRepository $serviceConnectionCrewRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionCrewRepository = $serviceConnectionCrewRepo;
    }

    /**
     * Display a listing of the ServiceConnectionCrew.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionCrews = $this->serviceConnectionCrewRepository->all();

        return view('service_connection_crews.index')
            ->with('serviceConnectionCrews', $serviceConnectionCrews);
    }

    /**
     * Show the form for creating a new ServiceConnectionCrew.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_connection_crews.create');
    }

    /**
     * Store a newly created ServiceConnectionCrew in storage.
     *
     * @param CreateServiceConnectionCrewRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionCrewRequest $request)
    {
        $input = $request->all();

        $serviceConnectionCrew = $this->serviceConnectionCrewRepository->create($input);

        Flash::success('Service Connection Crew saved successfully.');

        return redirect(route('serviceConnectionCrews.index'));
    }

    /**
     * Display the specified ServiceConnectionCrew.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionCrew = $this->serviceConnectionCrewRepository->find($id);

        if (empty($serviceConnectionCrew)) {
            Flash::error('Service Connection Crew not found');

            return redirect(route('serviceConnectionCrews.index'));
        }

        return view('service_connection_crews.show')->with('serviceConnectionCrew', $serviceConnectionCrew);
    }

    /**
     * Show the form for editing the specified ServiceConnectionCrew.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionCrew = $this->serviceConnectionCrewRepository->find($id);

        if (empty($serviceConnectionCrew)) {
            Flash::error('Service Connection Crew not found');

            return redirect(route('serviceConnectionCrews.index'));
        }

        return view('service_connection_crews.edit')->with('serviceConnectionCrew', $serviceConnectionCrew);
    }

    /**
     * Update the specified ServiceConnectionCrew in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionCrewRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionCrewRequest $request)
    {
        $serviceConnectionCrew = $this->serviceConnectionCrewRepository->find($id);

        if (empty($serviceConnectionCrew)) {
            Flash::error('Service Connection Crew not found');

            return redirect(route('serviceConnectionCrews.index'));
        }

        $serviceConnectionCrew = $this->serviceConnectionCrewRepository->update($request->all(), $id);

        Flash::success('Service Connection Crew updated successfully.');

        return redirect(route('serviceConnectionCrews.index'));
    }

    /**
     * Remove the specified ServiceConnectionCrew from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionCrew = $this->serviceConnectionCrewRepository->find($id);

        if (empty($serviceConnectionCrew)) {
            Flash::error('Service Connection Crew not found');

            return redirect(route('serviceConnectionCrews.index'));
        }

        $this->serviceConnectionCrewRepository->delete($id);

        Flash::success('Service Connection Crew deleted successfully.');

        return redirect(route('serviceConnectionCrews.index'));
    }
}
