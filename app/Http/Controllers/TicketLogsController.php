<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTicketLogsRequest;
use App\Http\Requests\UpdateTicketLogsRequest;
use App\Repositories\TicketLogsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class TicketLogsController extends AppBaseController
{
    /** @var  TicketLogsRepository */
    private $ticketLogsRepository;

    public function __construct(TicketLogsRepository $ticketLogsRepo)
    {
        $this->middleware('auth');
        $this->ticketLogsRepository = $ticketLogsRepo;
    }

    /**
     * Display a listing of the TicketLogs.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $ticketLogs = $this->ticketLogsRepository->all();

        return view('ticket_logs.index')
            ->with('ticketLogs', $ticketLogs);
    }

    /**
     * Show the form for creating a new TicketLogs.
     *
     * @return Response
     */
    public function create()
    {
        return view('ticket_logs.create');
    }

    /**
     * Store a newly created TicketLogs in storage.
     *
     * @param CreateTicketLogsRequest $request
     *
     * @return Response
     */
    public function store(CreateTicketLogsRequest $request)
    {
        $input = $request->all();

        $ticketLogs = $this->ticketLogsRepository->create($input);

        Flash::success('Ticket Logs saved successfully.');

        return redirect(route('ticketLogs.index'));
    }

    /**
     * Display the specified TicketLogs.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $ticketLogs = $this->ticketLogsRepository->find($id);

        if (empty($ticketLogs)) {
            Flash::error('Ticket Logs not found');

            return redirect(route('ticketLogs.index'));
        }

        return view('ticket_logs.show')->with('ticketLogs', $ticketLogs);
    }

    /**
     * Show the form for editing the specified TicketLogs.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $ticketLogs = $this->ticketLogsRepository->find($id);

        if (empty($ticketLogs)) {
            Flash::error('Ticket Logs not found');

            return redirect(route('ticketLogs.index'));
        }

        return view('ticket_logs.edit')->with('ticketLogs', $ticketLogs);
    }

    /**
     * Update the specified TicketLogs in storage.
     *
     * @param int $id
     * @param UpdateTicketLogsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicketLogsRequest $request)
    {
        $ticketLogs = $this->ticketLogsRepository->find($id);

        if (empty($ticketLogs)) {
            Flash::error('Ticket Logs not found');

            return redirect(route('ticketLogs.index'));
        }

        $ticketLogs = $this->ticketLogsRepository->update($request->all(), $id);

        Flash::success('Ticket Logs updated successfully.');

        return redirect(route('ticketLogs.index'));
    }

    /**
     * Remove the specified TicketLogs from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ticketLogs = $this->ticketLogsRepository->find($id);

        if (empty($ticketLogs)) {
            Flash::error('Ticket Logs not found');

            return redirect(route('ticketLogs.index'));
        }

        $this->ticketLogsRepository->delete($id);

        Flash::success('Ticket Logs deleted successfully.');

        return redirect(route('ticketLogs.index'));
    }
}
