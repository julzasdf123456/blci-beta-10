<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChangeMeterLogsRequest;
use App\Http\Requests\UpdateChangeMeterLogsRequest;
use App\Repositories\ChangeMeterLogsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ChangeMeterLogsController extends AppBaseController
{
    /** @var  ChangeMeterLogsRepository */
    private $changeMeterLogsRepository;

    public function __construct(ChangeMeterLogsRepository $changeMeterLogsRepo)
    {
        $this->middleware('auth');
        $this->changeMeterLogsRepository = $changeMeterLogsRepo;
    }

    /**
     * Display a listing of the ChangeMeterLogs.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $changeMeterLogs = $this->changeMeterLogsRepository->all();

        return view('change_meter_logs.index')
            ->with('changeMeterLogs', $changeMeterLogs);
    }

    /**
     * Show the form for creating a new ChangeMeterLogs.
     *
     * @return Response
     */
    public function create()
    {
        return view('change_meter_logs.create');
    }

    /**
     * Store a newly created ChangeMeterLogs in storage.
     *
     * @param CreateChangeMeterLogsRequest $request
     *
     * @return Response
     */
    public function store(CreateChangeMeterLogsRequest $request)
    {
        $input = $request->all();

        $changeMeterLogs = $this->changeMeterLogsRepository->create($input);

        Flash::success('Change Meter Logs saved successfully.');

        return redirect(route('changeMeterLogs.index'));
    }

    /**
     * Display the specified ChangeMeterLogs.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $changeMeterLogs = $this->changeMeterLogsRepository->find($id);

        if (empty($changeMeterLogs)) {
            Flash::error('Change Meter Logs not found');

            return redirect(route('changeMeterLogs.index'));
        }

        return view('change_meter_logs.show')->with('changeMeterLogs', $changeMeterLogs);
    }

    /**
     * Show the form for editing the specified ChangeMeterLogs.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $changeMeterLogs = $this->changeMeterLogsRepository->find($id);

        if (empty($changeMeterLogs)) {
            Flash::error('Change Meter Logs not found');

            return redirect(route('changeMeterLogs.index'));
        }

        return view('change_meter_logs.edit')->with('changeMeterLogs', $changeMeterLogs);
    }

    /**
     * Update the specified ChangeMeterLogs in storage.
     *
     * @param int $id
     * @param UpdateChangeMeterLogsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChangeMeterLogsRequest $request)
    {
        $changeMeterLogs = $this->changeMeterLogsRepository->find($id);

        if (empty($changeMeterLogs)) {
            Flash::error('Change Meter Logs not found');

            return redirect(route('changeMeterLogs.index'));
        }

        $changeMeterLogs = $this->changeMeterLogsRepository->update($request->all(), $id);

        Flash::success('Change Meter Logs updated successfully.');

        return redirect(route('changeMeterLogs.index'));
    }

    /**
     * Remove the specified ChangeMeterLogs from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $changeMeterLogs = $this->changeMeterLogsRepository->find($id);

        if (empty($changeMeterLogs)) {
            Flash::error('Change Meter Logs not found');

            return redirect(route('changeMeterLogs.index'));
        }

        $this->changeMeterLogsRepository->delete($id);

        Flash::success('Change Meter Logs deleted successfully.');

        return redirect(route('changeMeterLogs.index'));
    }
}
