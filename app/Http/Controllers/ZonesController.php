<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateZonesRequest;
use App\Http\Requests\UpdateZonesRequest;
use App\Repositories\ZonesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ZonesController extends AppBaseController
{
    /** @var  ZonesRepository */
    private $zonesRepository;

    public function __construct(ZonesRepository $zonesRepo)
    {
        $this->middleware('auth');
        $this->zonesRepository = $zonesRepo;
    }

    /**
     * Display a listing of the Zones.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $zones = $this->zonesRepository->all();

        return view('zones.index')
            ->with('zones', $zones);
    }

    /**
     * Show the form for creating a new Zones.
     *
     * @return Response
     */
    public function create()
    {
        return view('zones.create');
    }

    /**
     * Store a newly created Zones in storage.
     *
     * @param CreateZonesRequest $request
     *
     * @return Response
     */
    public function store(CreateZonesRequest $request)
    {
        $input = $request->all();

        $zones = $this->zonesRepository->create($input);

        Flash::success('Zones saved successfully.');

        return redirect(route('zones.index'));
    }

    /**
     * Display the specified Zones.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $zones = $this->zonesRepository->find($id);

        if (empty($zones)) {
            Flash::error('Zones not found');

            return redirect(route('zones.index'));
        }

        return view('zones.show')->with('zones', $zones);
    }

    /**
     * Show the form for editing the specified Zones.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $zones = $this->zonesRepository->find($id);

        if (empty($zones)) {
            Flash::error('Zones not found');

            return redirect(route('zones.index'));
        }

        return view('zones.edit')->with('zones', $zones);
    }

    /**
     * Update the specified Zones in storage.
     *
     * @param int $id
     * @param UpdateZonesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateZonesRequest $request)
    {
        $zones = $this->zonesRepository->find($id);

        if (empty($zones)) {
            Flash::error('Zones not found');

            return redirect(route('zones.index'));
        }

        $zones = $this->zonesRepository->update($request->all(), $id);

        Flash::success('Zones updated successfully.');

        return redirect(route('zones.index'));
    }

    /**
     * Remove the specified Zones from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $zones = $this->zonesRepository->find($id);

        if (empty($zones)) {
            Flash::error('Zones not found');

            return redirect(route('zones.index'));
        }

        $this->zonesRepository->delete($id);

        Flash::success('Zones deleted successfully.');

        return redirect(route('zones.index'));
    }
}
