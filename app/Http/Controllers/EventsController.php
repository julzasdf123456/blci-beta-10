<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventsRequest;
use App\Http\Requests\UpdateEventsRequest;
use App\Repositories\EventsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class EventsController extends AppBaseController
{
    /** @var  EventsRepository */
    private $eventsRepository;

    public function __construct(EventsRepository $eventsRepo)
    {
        $this->middleware('auth');
        $this->eventsRepository = $eventsRepo;
    }

    /**
     * Display a listing of the Events.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $events = $this->eventsRepository->all();

        return view('events.index')
            ->with('events', $events);
    }

    /**
     * Show the form for creating a new Events.
     *
     * @return Response
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created Events in storage.
     *
     * @param CreateEventsRequest $request
     *
     * @return Response
     */
    public function store(CreateEventsRequest $request)
    {
        $input = $request->all();

        $events = $this->eventsRepository->create($input);

        Flash::success('Events saved successfully.');

        return redirect(route('events.index'));
    }

    /**
     * Display the specified Events.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $events = $this->eventsRepository->find($id);

        if (empty($events)) {
            Flash::error('Events not found');

            return redirect(route('events.index'));
        }

        return view('events.show')->with('events', $events);
    }

    /**
     * Show the form for editing the specified Events.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $events = $this->eventsRepository->find($id);

        if (empty($events)) {
            Flash::error('Events not found');

            return redirect(route('events.index'));
        }

        return view('events.edit')->with('events', $events);
    }

    /**
     * Update the specified Events in storage.
     *
     * @param int $id
     * @param UpdateEventsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEventsRequest $request)
    {
        $events = $this->eventsRepository->find($id);

        if (empty($events)) {
            Flash::error('Events not found');

            return redirect(route('events.index'));
        }

        $events = $this->eventsRepository->update($request->all(), $id);

        Flash::success('Events updated successfully.');

        return redirect(route('events.index'));
    }

    /**
     * Remove the specified Events from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $events = $this->eventsRepository->find($id);

        if (empty($events)) {
            Flash::error('Events not found');

            return redirect(route('events.index'));
        }

        $this->eventsRepository->delete($id);

        Flash::success('Events deleted successfully.');

        return redirect(route('events.index'));
    }
}
