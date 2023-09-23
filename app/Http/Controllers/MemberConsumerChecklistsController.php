<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMemberConsumerChecklistsRequest;
use App\Http\Requests\UpdateMemberConsumerChecklistsRequest;
use App\Repositories\MemberConsumerChecklistsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\MemberConsumerChecklists;
use App\Models\MemberConsumerChecklistsRep;
use Flash;
use Response;

class MemberConsumerChecklistsController extends AppBaseController
{
    /** @var  MemberConsumerChecklistsRepository */
    private $memberConsumerChecklistsRepository;

    public function __construct(MemberConsumerChecklistsRepository $memberConsumerChecklistsRepo)
    {
        $this->middleware('auth');
        $this->memberConsumerChecklistsRepository = $memberConsumerChecklistsRepo;
    }

    /**
     * Display a listing of the MemberConsumerChecklists.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $memberConsumerChecklists = $this->memberConsumerChecklistsRepository->all();

        return view('member_consumer_checklists.index')
            ->with('memberConsumerChecklists', $memberConsumerChecklists);
    }

    /**
     * Show the form for creating a new MemberConsumerChecklists.
     *
     * @return Response
     */
    public function create()
    {
        return view('member_consumer_checklists.create');
    }

    /**
     * Store a newly created MemberConsumerChecklists in storage.
     *
     * @param CreateMemberConsumerChecklistsRequest $request
     *
     * @return Response
     */
    public function store(CreateMemberConsumerChecklistsRequest $request)
    {
        $input = $request->all();

        $memberConsumerChecklists = $this->memberConsumerChecklistsRepository->create($input);

        Flash::success('Member Consumer Checklists saved successfully.');

        return redirect(route('memberConsumerChecklists.index'));
    }

    /**
     * Display the specified MemberConsumerChecklists.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $memberConsumerChecklists = $this->memberConsumerChecklistsRepository->find($id);

        if (empty($memberConsumerChecklists)) {
            Flash::error('Member Consumer Checklists not found');

            return redirect(route('memberConsumerChecklists.index'));
        }

        return view('member_consumer_checklists.show')->with('memberConsumerChecklists', $memberConsumerChecklists);
    }

    /**
     * Show the form for editing the specified MemberConsumerChecklists.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $memberConsumerChecklists = $this->memberConsumerChecklistsRepository->find($id);

        if (empty($memberConsumerChecklists)) {
            Flash::error('Member Consumer Checklists not found');

            return redirect(route('memberConsumerChecklists.index'));
        }

        return view('member_consumer_checklists.edit')->with('memberConsumerChecklists', $memberConsumerChecklists);
    }

    /**
     * Update the specified MemberConsumerChecklists in storage.
     *
     * @param int $id
     * @param UpdateMemberConsumerChecklistsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMemberConsumerChecklistsRequest $request)
    {
        $memberConsumerChecklists = $this->memberConsumerChecklistsRepository->find($id);

        if (empty($memberConsumerChecklists)) {
            Flash::error('Member Consumer Checklists not found');

            return redirect(route('memberConsumerChecklists.index'));
        }

        $memberConsumerChecklists = $this->memberConsumerChecklistsRepository->update($request->all(), $id);

        Flash::success('Member Consumer Checklists updated successfully.');

        return redirect(route('memberConsumerChecklists.index'));
    }

    /**
     * Remove the specified MemberConsumerChecklists from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $memberConsumerChecklists = $this->memberConsumerChecklistsRepository->find($id);

        if (empty($memberConsumerChecklists)) {
            Flash::error('Member Consumer Checklists not found');

            return redirect(route('memberConsumerChecklists.index'));
        }

        $this->memberConsumerChecklistsRepository->delete($id);

        Flash::success('Member Consumer Checklists deleted successfully.');

        return redirect(route('memberConsumerChecklists.index'));
    }

    public function complyChecklists($id, Request $request) {
        $inputs = $request->input('ChecklistId');

        $checklistsRep = MemberConsumerChecklistsRep::all();

        foreach($inputs as $input){
            $memberConsumerChecklists = new MemberConsumerChecklists;
            $memberConsumerChecklists->id = IDGenerator::generateID();
            $memberConsumerChecklists->MemberConsumerId = $id;
            $memberConsumerChecklists->ChecklistId = $input;

            $memberConsumerChecklists->save();
        }

        if (count($inputs) == count($checklistsRep)) {
            // IF REQUIREMENTS ARE COMPLETE

            return redirect(route('serviceConnections.create_new', [$id]));
        } else {
            // IF REQUIREMENTS AIN'T COMPLETE

            return redirect(route('memberConsumers.show', [$id]));
        }
    }
}
