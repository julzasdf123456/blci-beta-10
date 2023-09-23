<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTicketsRepositoryRequest;
use App\Http\Requests\UpdateTicketsRepositoryRequest;
use App\Repositories\TicketsRepositoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\TicketsRepository;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class TicketsRepositoryController extends AppBaseController
{
    /** @var  TicketsRepositoryRepository */
    private $ticketsRepositoryRepository;

    public function __construct(TicketsRepositoryRepository $ticketsRepositoryRepo)
    {
        $this->middleware('auth');
        $this->ticketsRepositoryRepository = $ticketsRepositoryRepo;
    }

    /**
     * Display a listing of the TicketsRepository.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $ticketsRepositories = DB::table('CRM_TicketsRepository')->whereNull('ParentTicket')->get();

        return view('tickets_repositories.index')
            ->with('ticketsRepositories', $ticketsRepositories);
    }

    /**
     * Show the form for creating a new TicketsRepository.
     *
     * @return Response
     */
    public function create()
    {
        $parentReps = TicketsRepository::whereNull('ParentTicket')->pluck('Name', 'id');

        return view('tickets_repositories.create', [
            'parentReps' => $parentReps
        ]);
    }

    /**
     * Store a newly created TicketsRepository in storage.
     *
     * @param CreateTicketsRepositoryRequest $request
     *
     * @return Response
     */
    public function store(CreateTicketsRepositoryRequest $request)
    {
        $input = $request->all();

        $ticketsRepository = $this->ticketsRepositoryRepository->create($input);

        Flash::success('Tickets Repository saved successfully.');

        return redirect(route('ticketsRepositories.index'));
    }

    /**
     * Display the specified TicketsRepository.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $ticketsRepository = $this->ticketsRepositoryRepository->find($id);

        if (empty($ticketsRepository)) {
            Flash::error('Tickets Repository not found');

            return redirect(route('ticketsRepositories.index'));
        }

        return view('tickets_repositories.show')->with('ticketsRepository', $ticketsRepository);
    }

    /**
     * Show the form for editing the specified TicketsRepository.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $ticketsRepository = $this->ticketsRepositoryRepository->find($id);

        $parentReps = TicketsRepository::whereNull('ParentTicket')->pluck('Name', 'id');

        if (empty($ticketsRepository)) {
            Flash::error('Tickets Repository not found');

            return redirect(route('ticketsRepositories.index'));
        }

        return view('tickets_repositories.edit', ['ticketsRepository' => $ticketsRepository, 'parentReps' => $parentReps]);
    }

    /**
     * Update the specified TicketsRepository in storage.
     *
     * @param int $id
     * @param UpdateTicketsRepositoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicketsRepositoryRequest $request)
    {
        $ticketsRepository = $this->ticketsRepositoryRepository->find($id);

        if (empty($ticketsRepository)) {
            Flash::error('Tickets Repository not found');

            return redirect(route('ticketsRepositories.index'));
        }

        $ticketsRepository = $this->ticketsRepositoryRepository->update($request->all(), $id);

        Flash::success('Tickets Repository updated successfully.');

        return redirect(route('ticketsRepositories.index'));
    }

    /**
     * Remove the specified TicketsRepository from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ticketsRepository = $this->ticketsRepositoryRepository->find($id);

        if (empty($ticketsRepository)) {
            Flash::error('Tickets Repository not found');

            return redirect(route('ticketsRepositories.index'));
        }

        $this->ticketsRepositoryRepository->delete($id);

        Flash::success('Tickets Repository deleted successfully.');

        return redirect(route('ticketsRepositories.index'));
    }
}
