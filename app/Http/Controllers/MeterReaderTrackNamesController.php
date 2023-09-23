<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMeterReaderTrackNamesRequest;
use App\Http\Requests\UpdateMeterReaderTrackNamesRequest;
use App\Repositories\MeterReaderTrackNamesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class MeterReaderTrackNamesController extends AppBaseController
{
    /** @var  MeterReaderTrackNamesRepository */
    private $meterReaderTrackNamesRepository;

    public function __construct(MeterReaderTrackNamesRepository $meterReaderTrackNamesRepo)
    {
        $this->meterReaderTrackNamesRepository = $meterReaderTrackNamesRepo;
    }

    /**
     * Display a listing of the MeterReaderTrackNames.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $meterReaderTrackNames = $this->meterReaderTrackNamesRepository->all();

        return view('meter_reader_track_names.index')
            ->with('meterReaderTrackNames', $meterReaderTrackNames);
    }

    /**
     * Show the form for creating a new MeterReaderTrackNames.
     *
     * @return Response
     */
    public function create()
    {
        return view('meter_reader_track_names.create');
    }

    /**
     * Store a newly created MeterReaderTrackNames in storage.
     *
     * @param CreateMeterReaderTrackNamesRequest $request
     *
     * @return Response
     */
    public function store(CreateMeterReaderTrackNamesRequest $request)
    {
        $input = $request->all();

        $meterReaderTrackNames = $this->meterReaderTrackNamesRepository->create($input);

        Flash::success('Meter Reader Track Names saved successfully.');

        return redirect(route('meterReaderTrackNames.index'));
    }

    /**
     * Display the specified MeterReaderTrackNames.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $meterReaderTrackNames = $this->meterReaderTrackNamesRepository->find($id);

        if (empty($meterReaderTrackNames)) {
            Flash::error('Meter Reader Track Names not found');

            return redirect(route('meterReaderTrackNames.index'));
        }

        return view('meter_reader_track_names.show')->with('meterReaderTrackNames', $meterReaderTrackNames);
    }

    /**
     * Show the form for editing the specified MeterReaderTrackNames.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $meterReaderTrackNames = $this->meterReaderTrackNamesRepository->find($id);

        if (empty($meterReaderTrackNames)) {
            Flash::error('Meter Reader Track Names not found');

            return redirect(route('meterReaderTrackNames.index'));
        }

        return view('meter_reader_track_names.edit')->with('meterReaderTrackNames', $meterReaderTrackNames);
    }

    /**
     * Update the specified MeterReaderTrackNames in storage.
     *
     * @param int $id
     * @param UpdateMeterReaderTrackNamesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMeterReaderTrackNamesRequest $request)
    {
        $meterReaderTrackNames = $this->meterReaderTrackNamesRepository->find($id);

        if (empty($meterReaderTrackNames)) {
            Flash::error('Meter Reader Track Names not found');

            return redirect(route('meterReaderTrackNames.index'));
        }

        $meterReaderTrackNames = $this->meterReaderTrackNamesRepository->update($request->all(), $id);

        Flash::success('Meter Reader Track Names updated successfully.');

        return redirect(route('meterReaderTrackNames.index'));
    }

    /**
     * Remove the specified MeterReaderTrackNames from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $meterReaderTrackNames = $this->meterReaderTrackNamesRepository->find($id);

        if (empty($meterReaderTrackNames)) {
            Flash::error('Meter Reader Track Names not found');

            return redirect(route('meterReaderTrackNames.index'));
        }

        $this->meterReaderTrackNamesRepository->delete($id);

        Flash::success('Meter Reader Track Names deleted successfully.');

        return redirect(route('meterReaderTrackNames.index'));
    }
}
