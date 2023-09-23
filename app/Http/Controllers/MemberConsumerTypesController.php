<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMemberConsumerTypesRequest;
use App\Http\Requests\UpdateMemberConsumerTypesRequest;
use App\Repositories\MemberConsumerTypesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class MemberConsumerTypesController extends AppBaseController
{
    /** @var  MemberConsumerTypesRepository */
    private $memberConsumerTypesRepository;

    public function __construct(MemberConsumerTypesRepository $memberConsumerTypesRepo)
    {
        $this->middleware('auth');
        $this->memberConsumerTypesRepository = $memberConsumerTypesRepo;
    }

    /**
     * Display a listing of the MemberConsumerTypes.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $memberConsumerTypes = $this->memberConsumerTypesRepository->all();

        return view('member_consumer_types.index')
            ->with('memberConsumerTypes', $memberConsumerTypes);
    }

    /**
     * Show the form for creating a new MemberConsumerTypes.
     *
     * @return Response
     */
    public function create()
    {
        return view('member_consumer_types.create');
    }

    /**
     * Store a newly created MemberConsumerTypes in storage.
     *
     * @param CreateMemberConsumerTypesRequest $request
     *
     * @return Response
     */
    public function store(CreateMemberConsumerTypesRequest $request)
    {
        $input = $request->all();

        $memberConsumerTypes = $this->memberConsumerTypesRepository->create($input);

        Flash::success('Member Consumer Types saved successfully.');

        return redirect(route('memberConsumerTypes.index'));
    }

    /**
     * Display the specified MemberConsumerTypes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $memberConsumerTypes = $this->memberConsumerTypesRepository->find($id);

        if (empty($memberConsumerTypes)) {
            Flash::error('Member Consumer Types not found');

            return redirect(route('memberConsumerTypes.index'));
        }

        return view('member_consumer_types.show')->with('memberConsumerTypes', $memberConsumerTypes);
    }

    /**
     * Show the form for editing the specified MemberConsumerTypes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $memberConsumerTypes = $this->memberConsumerTypesRepository->find($id);

        if (empty($memberConsumerTypes)) {
            Flash::error('Member Consumer Types not found');

            return redirect(route('memberConsumerTypes.index'));
        }

        return view('member_consumer_types.edit')->with('memberConsumerTypes', $memberConsumerTypes);
    }

    /**
     * Update the specified MemberConsumerTypes in storage.
     *
     * @param int $id
     * @param UpdateMemberConsumerTypesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMemberConsumerTypesRequest $request)
    {
        $memberConsumerTypes = $this->memberConsumerTypesRepository->find($id);

        if (empty($memberConsumerTypes)) {
            Flash::error('Member Consumer Types not found');

            return redirect(route('memberConsumerTypes.index'));
        }

        $memberConsumerTypes = $this->memberConsumerTypesRepository->update($request->all(), $id);

        Flash::success('Member Consumer Types updated successfully.');

        return redirect(route('memberConsumerTypes.index'));
    }

    /**
     * Remove the specified MemberConsumerTypes from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $memberConsumerTypes = $this->memberConsumerTypesRepository->find($id);

        if (empty($memberConsumerTypes)) {
            Flash::error('Member Consumer Types not found');

            return redirect(route('memberConsumerTypes.index'));
        }

        $this->memberConsumerTypesRepository->delete($id);

        Flash::success('Member Consumer Types deleted successfully.');

        return redirect(route('memberConsumerTypes.index'));
    }
}
