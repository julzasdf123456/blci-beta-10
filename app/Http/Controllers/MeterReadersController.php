<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMeterReadersRequest;
use App\Http\Requests\UpdateMeterReadersRequest;
use App\Repositories\MeterReadersRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class MeterReadersController extends AppBaseController
{
    /** @var  MeterReadersRepository */
    private $meterReadersRepository;

    public function __construct(MeterReadersRepository $meterReadersRepo)
    {
        $this->meterReadersRepository = $meterReadersRepo;
    }

    /**
     * Display a listing of the MeterReaders.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $meterReaders = $this->meterReadersRepository->all();

        return view('meter_readers.index')
            ->with('meterReaders', $meterReaders);
    }

    /**
     * Show the form for creating a new MeterReaders.
     *
     * @return Response
     */
    public function create()
    {
        return view('meter_readers.create');
    }

    /**
     * Store a newly created MeterReaders in storage.
     *
     * @param CreateMeterReadersRequest $request
     *
     * @return Response
     */
    public function store(CreateMeterReadersRequest $request)
    {
        $input = $request->all();

        $meterReaders = $this->meterReadersRepository->create($input);

        Flash::success('Meter Readers saved successfully.');

        return redirect(route('meterReaders.index'));
    }

    /**
     * Display the specified MeterReaders.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $meterReaders = $this->meterReadersRepository->find($id);

        if (empty($meterReaders)) {
            Flash::error('Meter Readers not found');

            return redirect(route('meterReaders.index'));
        }

        return view('meter_readers.show')->with('meterReaders', $meterReaders);
    }

    /**
     * Show the form for editing the specified MeterReaders.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $meterReaders = $this->meterReadersRepository->find($id);

        if (empty($meterReaders)) {
            Flash::error('Meter Readers not found');

            return redirect(route('meterReaders.index'));
        }

        return view('meter_readers.edit')->with('meterReaders', $meterReaders);
    }

    /**
     * Update the specified MeterReaders in storage.
     *
     * @param int $id
     * @param UpdateMeterReadersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMeterReadersRequest $request)
    {
        $meterReaders = $this->meterReadersRepository->find($id);

        if (empty($meterReaders)) {
            Flash::error('Meter Readers not found');

            return redirect(route('meterReaders.index'));
        }

        $meterReaders = $this->meterReadersRepository->update($request->all(), $id);

        Flash::success('Meter Readers updated successfully.');

        return redirect(route('meterReaders.index'));
    }

    /**
     * Remove the specified MeterReaders from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $meterReaders = $this->meterReadersRepository->find($id);

        if (empty($meterReaders)) {
            Flash::error('Meter Readers not found');

            return redirect(route('meterReaders.index'));
        }

        $this->meterReadersRepository->delete($id);

        Flash::success('Meter Readers deleted successfully.');

        return redirect(route('meterReaders.index'));
    }
}
