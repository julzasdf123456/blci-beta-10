<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDamageAssessmentRequest;
use App\Http\Requests\UpdateDamageAssessmentRequest;
use App\Repositories\DamageAssessmentRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DamageAssessment;
use Flash;
use Response;

class DamageAssessmentController extends AppBaseController
{
    /** @var  DamageAssessmentRepository */
    private $damageAssessmentRepository;

    public function __construct(DamageAssessmentRepository $damageAssessmentRepo)
    {
        $this->middleware('auth');
        $this->damageAssessmentRepository = $damageAssessmentRepo;
    }

    /**
     * Display a listing of the DamageAssessment.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $feeders = DB::table('CRM_DamageAssessment')
            ->select('Feeder')
            ->orderBy('Feeder')
            ->groupBy('Feeder')
            ->get();

        // $poles = DamageAssessment::select('id', 'ObjectName', 'Feeder')->get();

        return view('damage_assessments.index', [
            'feeders' => $feeders,
            // 'poles' => $poles,
        ]);
    }

    /**
     * Show the form for creating a new DamageAssessment.
     *
     * @return Response
     */
    public function create()
    {
        return view('damage_assessments.create');
    }

    /**
     * Store a newly created DamageAssessment in storage.
     *
     * @param CreateDamageAssessmentRequest $request
     *
     * @return Response
     */
    public function store(CreateDamageAssessmentRequest $request)
    {
        $input = $request->all();

        $damageAssessment = $this->damageAssessmentRepository->create($input);

        Flash::success('Damage Assessment saved successfully.');

        return redirect(route('damageAssessments.index'));
    }

    /**
     * Display the specified DamageAssessment.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $damageAssessment = $this->damageAssessmentRepository->find($id);

        if (empty($damageAssessment)) {
            Flash::error('Damage Assessment not found');

            return redirect(route('damageAssessments.index'));
        }

        return view('damage_assessments.show')->with('damageAssessment', $damageAssessment);
    }

    /**
     * Show the form for editing the specified DamageAssessment.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $damageAssessment = $this->damageAssessmentRepository->find($id);

        if (empty($damageAssessment)) {
            Flash::error('Damage Assessment not found');

            return redirect(route('damageAssessments.index'));
        }

        return view('damage_assessments.edit')->with('damageAssessment', $damageAssessment);
    }

    /**
     * Update the specified DamageAssessment in storage.
     *
     * @param int $id
     * @param UpdateDamageAssessmentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDamageAssessmentRequest $request)
    {
        $damageAssessment = $this->damageAssessmentRepository->find($id);

        if (empty($damageAssessment)) {
            Flash::error('Damage Assessment not found');

            return redirect(route('damageAssessments.index'));
        }

        $damageAssessment = $this->damageAssessmentRepository->update($request->all(), $id);

        Flash::success('Damage Assessment updated successfully.');

        return redirect(route('damageAssessments.index'));
    }

    /**
     * Remove the specified DamageAssessment from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $damageAssessment = $this->damageAssessmentRepository->find($id);

        if (empty($damageAssessment)) {
            Flash::error('Damage Assessment not found');

            return redirect(route('damageAssessments.index'));
        }

        $this->damageAssessmentRepository->delete($id);

        Flash::success('Damage Assessment deleted successfully.');

        return redirect(route('damageAssessments.index'));
    }

    public function getObjects(Request $request) {
        if ($request['Feeder'] == 'All') {
            $objects = DamageAssessment::all();
        } else {
            $objects = DamageAssessment::where('Feeder', $request['Feeder'])->get();
        }

        return response()->json($objects, 200);
    }

    public function searchPole(Request $request) {
        $objects = DamageAssessment::where('ObjectName', 'LIKE', '%' . $request['Search'] . '%')->get();

        return response()->json($objects, 200);
    }

    public function viewPole(Request $request) {
        $objects = DamageAssessment::where('id', $request['id'])->first();

        return response()->json($objects, 200);
    }

    public function updateAjax(Request $request)
    {
        $damageAssessment = $this->damageAssessmentRepository->find($request['id']);

        if (empty($damageAssessment)) {

            return response()->json('not found', 404);
        }

        $damageAssessment = $this->damageAssessmentRepository->update($request->all(), $request['id']);

        return response()->json('ok', 200);
    }
}
