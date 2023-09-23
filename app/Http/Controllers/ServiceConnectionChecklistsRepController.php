<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionChecklistsRepRequest;
use App\Http\Requests\UpdateServiceConnectionChecklistsRepRequest;
use App\Repositories\ServiceConnectionChecklistsRepRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServiceConnectionChecklistsRepController extends AppBaseController
{
    /** @var  ServiceConnectionChecklistsRepRepository */
    private $serviceConnectionChecklistsRepRepository;

    public function __construct(ServiceConnectionChecklistsRepRepository $serviceConnectionChecklistsRepRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionChecklistsRepRepository = $serviceConnectionChecklistsRepRepo;
    }

    /**
     * Display a listing of the ServiceConnectionChecklistsRep.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionChecklistsReps = $this->serviceConnectionChecklistsRepRepository->all();

        return view('service_connection_checklists_reps.index')
            ->with('serviceConnectionChecklistsReps', $serviceConnectionChecklistsReps);
    }

    /**
     * Show the form for creating a new ServiceConnectionChecklistsRep.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_connection_checklists_reps.create');
    }

    /**
     * Store a newly created ServiceConnectionChecklistsRep in storage.
     *
     * @param CreateServiceConnectionChecklistsRepRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionChecklistsRepRequest $request)
    {
        $input = $request->all();

        $serviceConnectionChecklistsRep = $this->serviceConnectionChecklistsRepRepository->create($input);

        Flash::success('Service Connection Checklists Rep saved successfully.');

        return redirect(route('serviceConnectionChecklistsReps.index'));
    }

    /**
     * Display the specified ServiceConnectionChecklistsRep.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionChecklistsRep = $this->serviceConnectionChecklistsRepRepository->find($id);

        if (empty($serviceConnectionChecklistsRep)) {
            Flash::error('Service Connection Checklists Rep not found');

            return redirect(route('serviceConnectionChecklistsReps.index'));
        }

        return view('service_connection_checklists_reps.show')->with('serviceConnectionChecklistsRep', $serviceConnectionChecklistsRep);
    }

    /**
     * Show the form for editing the specified ServiceConnectionChecklistsRep.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionChecklistsRep = $this->serviceConnectionChecklistsRepRepository->find($id);

        if (empty($serviceConnectionChecklistsRep)) {
            Flash::error('Service Connection Checklists Rep not found');

            return redirect(route('serviceConnectionChecklistsReps.index'));
        }

        return view('service_connection_checklists_reps.edit')->with('serviceConnectionChecklistsRep', $serviceConnectionChecklistsRep);
    }

    /**
     * Update the specified ServiceConnectionChecklistsRep in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionChecklistsRepRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionChecklistsRepRequest $request)
    {
        $serviceConnectionChecklistsRep = $this->serviceConnectionChecklistsRepRepository->find($id);

        if (empty($serviceConnectionChecklistsRep)) {
            Flash::error('Service Connection Checklists Rep not found');

            return redirect(route('serviceConnectionChecklistsReps.index'));
        }

        $serviceConnectionChecklistsRep = $this->serviceConnectionChecklistsRepRepository->update($request->all(), $id);

        Flash::success('Service Connection Checklists Rep updated successfully.');

        return redirect(route('serviceConnectionChecklistsReps.index'));
    }

    /**
     * Remove the specified ServiceConnectionChecklistsRep from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionChecklistsRep = $this->serviceConnectionChecklistsRepRepository->find($id);

        if (empty($serviceConnectionChecklistsRep)) {
            Flash::error('Service Connection Checklists Rep not found');

            return redirect(route('serviceConnectionChecklistsReps.index'));
        }

        $this->serviceConnectionChecklistsRepRepository->delete($id);

        Flash::success('Service Connection Checklists Rep deleted successfully.');

        return redirect(route('serviceConnectionChecklistsReps.index'));
    }

    
}
