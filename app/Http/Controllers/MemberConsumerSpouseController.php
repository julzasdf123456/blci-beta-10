<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMemberConsumerSpouseRequest;
use App\Http\Requests\UpdateMemberConsumerSpouseRequest;
use App\Repositories\MemberConsumerSpouseRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\MemberConsumers;
use App\Models\Towns;
use Flash;
use Response;

class MemberConsumerSpouseController extends AppBaseController
{
    /** @var  MemberConsumerSpouseRepository */
    private $memberConsumerSpouseRepository;

    public function __construct(MemberConsumerSpouseRepository $memberConsumerSpouseRepo)
    {
        $this->middleware('auth');
        $this->memberConsumerSpouseRepository = $memberConsumerSpouseRepo;
    }

    /**
     * Display a listing of the MemberConsumerSpouse.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $memberConsumerSpouses = $this->memberConsumerSpouseRepository->all();

        return view('member_consumer_spouses.index')
            ->with('memberConsumerSpouses', $memberConsumerSpouses);
    }

    /**
     * Show the form for creating a new MemberConsumerSpouse.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $memberConsumer = MemberConsumers::where('Id', $request['consumerId'])->first();

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        $cond = 'new';

        return view('member_consumer_spouses.create', ['memberConsumer' => $memberConsumer, 'cond' => $cond, 'towns' => $towns]);
    }

    /**
     * Store a newly created MemberConsumerSpouse in storage.
     *
     * @param CreateMemberConsumerSpouseRequest $request
     *
     * @return Response
     */
    public function store(CreateMemberConsumerSpouseRequest $request)
    {
        $input = $request->all();

        $memberConsumerSpouse = $this->memberConsumerSpouseRepository->create($input);

        Flash::success('Member Consumer Spouse saved successfully.');

        // return redirect(route('memberConsumers.assess-checklists', [$input['MemberConsumerId']]));
        return redirect(route('serviceConnections.create_new', [$memberConsumerSpouse->MemberConsumerId]));
        // return redirect(route('memberConsumers.show', [$input['MemberConsumerId']]));
    }

    /**
     * Display the specified MemberConsumerSpouse.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $memberConsumerSpouse = $this->memberConsumerSpouseRepository->find($id);

        if (empty($memberConsumerSpouse)) {
            Flash::error('Member Consumer Spouse not found');

            return redirect(route('memberConsumerSpouses.index'));
        }

        return view('member_consumer_spouses.show')->with('memberConsumerSpouse', $memberConsumerSpouse);
    }

    /**
     * Show the form for editing the specified MemberConsumerSpouse.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $memberConsumerSpouse = $this->memberConsumerSpouseRepository->find($id);

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        $cond = 'edit';

        if (empty($memberConsumerSpouse)) {
            Flash::error('Member Consumer Spouse not found');

            return redirect(route('memberConsumerSpouses.index'));
        }

        return view('member_consumer_spouses.edit', ['memberConsumerSpouse' => $memberConsumerSpouse, 'cond' => $cond, 'towns' => $towns]);
    }

    /**
     * Update the specified MemberConsumerSpouse in storage.
     *
     * @param int $id
     * @param UpdateMemberConsumerSpouseRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMemberConsumerSpouseRequest $request)
    {
        $memberConsumerSpouse = $this->memberConsumerSpouseRepository->find($id);

        if (empty($memberConsumerSpouse)) {
            Flash::error('Member Consumer Spouse not found');

            return redirect(route('memberConsumerSpouses.index'));
        }

        $memberConsumerSpouse = $this->memberConsumerSpouseRepository->update($request->all(), $id);

        Flash::success('Member Consumer Spouse updated successfully.');

        return redirect(route('memberConsumers.show', [$memberConsumerSpouse->MemberConsumerId]));
    }

    /**
     * Remove the specified MemberConsumerSpouse from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $memberConsumerSpouse = $this->memberConsumerSpouseRepository->find($id);

        if (empty($memberConsumerSpouse)) {
            Flash::error('Member Consumer Spouse not found');

            return redirect(route('memberConsumerSpouses.index'));
        }

        $this->memberConsumerSpouseRepository->delete($id);

        Flash::success('Member Consumer Spouse deleted successfully.');

        return redirect(route('memberConsumerSpouses.index'));
    }
}
