<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMeterReaderTracksRequest;
use App\Http\Requests\UpdateMeterReaderTracksRequest;
use App\Repositories\MeterReaderTracksRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\MeterReaderTracks;
use Flash;
use Response;

class MeterReaderTracksController extends AppBaseController
{
    /** @var  MeterReaderTracksRepository */
    private $meterReaderTracksRepository;

    public function __construct(MeterReaderTracksRepository $meterReaderTracksRepo)
    {
        $this->meterReaderTracksRepository = $meterReaderTracksRepo;
    }

    /**
     * Display a listing of the MeterReaderTracks.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $meterReaderTracks = $this->meterReaderTracksRepository->all();

        return view('meter_reader_tracks.index')
            ->with('meterReaderTracks', $meterReaderTracks);
    }

    /**
     * Show the form for creating a new MeterReaderTracks.
     *
     * @return Response
     */
    public function create()
    {
        return view('meter_reader_tracks.create');
    }

    /**
     * Store a newly created MeterReaderTracks in storage.
     *
     * @param CreateMeterReaderTracksRequest $request
     *
     * @return Response
     */
    public function store(CreateMeterReaderTracksRequest $request)
    {
        $input = $request->all();

        $meterReaderTracks = $this->meterReaderTracksRepository->create($input);

        Flash::success('Meter Reader Tracks saved successfully.');

        return redirect(route('meterReaderTracks.index'));
    }

    /**
     * Display the specified MeterReaderTracks.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $meterReaderTracks = $this->meterReaderTracksRepository->find($id);

        if (empty($meterReaderTracks)) {
            Flash::error('Meter Reader Tracks not found');

            return redirect(route('meterReaderTracks.index'));
        }

        return view('meter_reader_tracks.show')->with('meterReaderTracks', $meterReaderTracks);
    }

    /**
     * Show the form for editing the specified MeterReaderTracks.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $meterReaderTracks = $this->meterReaderTracksRepository->find($id);

        if (empty($meterReaderTracks)) {
            Flash::error('Meter Reader Tracks not found');

            return redirect(route('meterReaderTracks.index'));
        }

        return view('meter_reader_tracks.edit')->with('meterReaderTracks', $meterReaderTracks);
    }

    /**
     * Update the specified MeterReaderTracks in storage.
     *
     * @param int $id
     * @param UpdateMeterReaderTracksRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMeterReaderTracksRequest $request)
    {
        $meterReaderTracks = $this->meterReaderTracksRepository->find($id);

        if (empty($meterReaderTracks)) {
            Flash::error('Meter Reader Tracks not found');

            return redirect(route('meterReaderTracks.index'));
        }

        $meterReaderTracks = $this->meterReaderTracksRepository->update($request->all(), $id);

        Flash::success('Meter Reader Tracks updated successfully.');

        return redirect(route('meterReaderTracks.index'));
    }

    /**
     * Remove the specified MeterReaderTracks from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $meterReaderTracks = $this->meterReaderTracksRepository->find($id);

        if (empty($meterReaderTracks)) {
            Flash::error('Meter Reader Tracks not found');

            return redirect(route('meterReaderTracks.index'));
        }

        $this->meterReaderTracksRepository->delete($id);

        Flash::success('Meter Reader Tracks deleted successfully.');

        return redirect(route('meterReaderTracks.index'));
    }

    public function getTracksByTrackNameId(Request $request) {
        if ($request->ajax()) {
            $tracks = MeterReaderTracks::where('TrackNameId', $request['TrackNameId'])->get();

            return response()->json($tracks, 200);
        }
    }
}
