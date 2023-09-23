<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMemberConsumerChecklistsRepRequest;
use App\Http\Requests\UpdateMemberConsumerChecklistsRepRequest;
use App\Repositories\MemberConsumerChecklistsRepRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class MemberConsumerChecklistsRepController extends AppBaseController
{
    /** @var  MemberConsumerChecklistsRepRepository */
    private $memberConsumerChecklistsRepRepository;

    public function __construct(MemberConsumerChecklistsRepRepository $memberConsumerChecklistsRepRepo)
    {
        $this->middleware('auth');
        $this->memberConsumerChecklistsRepRepository = $memberConsumerChecklistsRepRepo;
    }

    /**
     * Display a listing of the MemberConsumerChecklistsRep.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $memberConsumerChecklistsReps = $this->memberConsumerChecklistsRepRepository->all();

        return view('member_consumer_checklists_reps.index')
            ->with('memberConsumerChecklistsReps', $memberConsumerChecklistsReps);
    }

    /**
     * Show the form for creating a new MemberConsumerChecklistsRep.
     *
     * @return Response
     */
    public function create()
    {
        return view('member_consumer_checklists_reps.create');
    }

    /**
     * Store a newly created MemberConsumerChecklistsRep in storage.
     *
     * @param CreateMemberConsumerChecklistsRepRequest $request
     *
     * @return Response
     */
    public function store(CreateMemberConsumerChecklistsRepRequest $request)
    {
        $input = $request->all();

        $memberConsumerChecklistsRep = $this->memberConsumerChecklistsRepRepository->create($input);

        Flash::success('Member Consumer Checklists Rep saved successfully.');

        return redirect(route('memberConsumerChecklistsReps.index'));
    }

    /**
     * Display the specified MemberConsumerChecklistsRep.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $memberConsumerChecklistsRep = $this->memberConsumerChecklistsRepRepository->find($id);

        if (empty($memberConsumerChecklistsRep)) {
            Flash::error('Member Consumer Checklists Rep not found');

            return redirect(route('memberConsumerChecklistsReps.index'));
        }

        return view('member_consumer_checklists_reps.show')->with('memberConsumerChecklistsRep', $memberConsumerChecklistsRep);
    }

    /**
     * Show the form for editing the specified MemberConsumerChecklistsRep.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $memberConsumerChecklistsRep = $this->memberConsumerChecklistsRepRepository->find($id);

        if (empty($memberConsumerChecklistsRep)) {
            Flash::error('Member Consumer Checklists Rep not found');

            return redirect(route('memberConsumerChecklistsReps.index'));
        }

        return view('member_consumer_checklists_reps.edit')->with('memberConsumerChecklistsRep', $memberConsumerChecklistsRep);
    }

    /**
     * Update the specified MemberConsumerChecklistsRep in storage.
     *
     * @param int $id
     * @param UpdateMemberConsumerChecklistsRepRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMemberConsumerChecklistsRepRequest $request)
    {
        $memberConsumerChecklistsRep = $this->memberConsumerChecklistsRepRepository->find($id);

        if (empty($memberConsumerChecklistsRep)) {
            Flash::error('Member Consumer Checklists Rep not found');

            return redirect(route('memberConsumerChecklistsReps.index'));
        }

        $memberConsumerChecklistsRep = $this->memberConsumerChecklistsRepRepository->update($request->all(), $id);

        Flash::success('Member Consumer Checklists Rep updated successfully.');

        return redirect(route('memberConsumerChecklistsReps.index'));
    }

    /**
     * Remove the specified MemberConsumerChecklistsRep from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $memberConsumerChecklistsRep = $this->memberConsumerChecklistsRepRepository->find($id);

        if (empty($memberConsumerChecklistsRep)) {
            Flash::error('Member Consumer Checklists Rep not found');

            return redirect(route('memberConsumerChecklistsReps.index'));
        }

        $this->memberConsumerChecklistsRepRepository->delete($id);

        Flash::success('Member Consumer Checklists Rep deleted successfully.');

        return redirect(route('memberConsumerChecklistsReps.index'));
    }
}
