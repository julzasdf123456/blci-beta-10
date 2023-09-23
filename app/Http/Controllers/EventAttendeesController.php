<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventAttendeesRequest;
use App\Http\Requests\UpdateEventAttendeesRequest;
use App\Repositories\EventAttendeesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class EventAttendeesController extends AppBaseController
{
    /** @var  EventAttendeesRepository */
    private $eventAttendeesRepository;

    public function __construct(EventAttendeesRepository $eventAttendeesRepo)
    {
        $this->middleware('auth');
        $this->eventAttendeesRepository = $eventAttendeesRepo;
    }

    /**
     * Display a listing of the EventAttendees.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $eventAttendees = $this->eventAttendeesRepository->all();

        return view('event_attendees.index')
            ->with('eventAttendees', $eventAttendees);
    }

    /**
     * Show the form for creating a new EventAttendees.
     *
     * @return Response
     */
    public function create()
    {
        return view('event_attendees.create');
    }

    /**
     * Store a newly created EventAttendees in storage.
     *
     * @param CreateEventAttendeesRequest $request
     *
     * @return Response
     */
    public function store(CreateEventAttendeesRequest $request)
    {
        $input = $request->all();

        $eventAttendees = $this->eventAttendeesRepository->create($input);

        Flash::success('Event Attendees saved successfully.');

        return redirect(route('eventAttendees.index'));
    }

    /**
     * Display the specified EventAttendees.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $eventAttendees = $this->eventAttendeesRepository->find($id);

        if (empty($eventAttendees)) {
            Flash::error('Event Attendees not found');

            return redirect(route('eventAttendees.index'));
        }

        return view('event_attendees.show')->with('eventAttendees', $eventAttendees);
    }

    /**
     * Show the form for editing the specified EventAttendees.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $eventAttendees = $this->eventAttendeesRepository->find($id);

        if (empty($eventAttendees)) {
            Flash::error('Event Attendees not found');

            return redirect(route('eventAttendees.index'));
        }

        return view('event_attendees.edit')->with('eventAttendees', $eventAttendees);
    }

    /**
     * Update the specified EventAttendees in storage.
     *
     * @param int $id
     * @param UpdateEventAttendeesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEventAttendeesRequest $request)
    {
        $eventAttendees = $this->eventAttendeesRepository->find($id);

        if (empty($eventAttendees)) {
            Flash::error('Event Attendees not found');

            return redirect(route('eventAttendees.index'));
        }

        $eventAttendees = $this->eventAttendeesRepository->update($request->all(), $id);

        Flash::success('Event Attendees updated successfully.');

        return redirect(route('eventAttendees.index'));
    }

    /**
     * Remove the specified EventAttendees from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $eventAttendees = $this->eventAttendeesRepository->find($id);

        if (empty($eventAttendees)) {
            Flash::error('Event Attendees not found');

            return redirect(route('eventAttendees.index'));
        }

        $this->eventAttendeesRepository->delete($id);

        Flash::success('Event Attendees deleted successfully.');

        return redirect(route('eventAttendees.index'));
    }
}
