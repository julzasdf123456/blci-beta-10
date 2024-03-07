<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMeterInstallationRequest;
use App\Http\Requests\UpdateMeterInstallationRequest;
use App\Repositories\MeterInstallationRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class MeterInstallationController extends AppBaseController
{
    /** @var  MeterInstallationRepository */
    private $meterInstallationRepository;

    public function __construct(MeterInstallationRepository $meterInstallationRepo)
    {
        $this->middleware('auth');
        $this->meterInstallationRepository = $meterInstallationRepo;
    }

    /**
     * Display a listing of the MeterInstallation.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $meterInstallations = $this->meterInstallationRepository->all();

        return view('meter_installations.index')
            ->with('meterInstallations', $meterInstallations);
    }

    /**
     * Show the form for creating a new MeterInstallation.
     *
     * @return Response
     */
    public function create()
    {
        return view('meter_installations.create');
    }

    /**
     * Store a newly created MeterInstallation in storage.
     *
     * @param CreateMeterInstallationRequest $request
     *
     * @return Response
     */
    public function store(CreateMeterInstallationRequest $request)
    {
        $input = $request->all();

        $meterInstallation = $this->meterInstallationRepository->create($input);

        Flash::success('Meter Installation saved successfully.');

        return redirect(route('meterInstallations.index'));
    }

    /**
     * Display the specified MeterInstallation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $meterInstallation = $this->meterInstallationRepository->find($id);

        if (empty($meterInstallation)) {
            Flash::error('Meter Installation not found');

            return redirect(route('meterInstallations.index'));
        }

        return view('meter_installations.show')->with('meterInstallation', $meterInstallation);
    }

    /**
     * Show the form for editing the specified MeterInstallation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $meterInstallation = $this->meterInstallationRepository->find($id);

        if (empty($meterInstallation)) {
            Flash::error('Meter Installation not found');

            return redirect(route('meterInstallations.index'));
        }

        return view('meter_installations.edit')->with('meterInstallation', $meterInstallation);
    }

    /**
     * Update the specified MeterInstallation in storage.
     *
     * @param int $id
     * @param UpdateMeterInstallationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMeterInstallationRequest $request)
    {
        $meterInstallation = $this->meterInstallationRepository->find($id);

        if (empty($meterInstallation)) {
            Flash::error('Meter Installation not found');

            return redirect(route('meterInstallations.index'));
        }

        $meterInstallation = $this->meterInstallationRepository->update($request->all(), $id);

        Flash::success('Meter Installation updated successfully.');

        return redirect(route('serviceConnections.show', [$meterInstallation->ServiceConnectionId]));
    }

    /**
     * Remove the specified MeterInstallation from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $meterInstallation = $this->meterInstallationRepository->find($id);

        if (empty($meterInstallation)) {
            Flash::error('Meter Installation not found');

            return redirect(route('meterInstallations.index'));
        }

        $this->meterInstallationRepository->delete($id);

        Flash::success('Meter Installation deleted successfully.');

        return redirect(route('meterInstallations.index'));
    }
}
