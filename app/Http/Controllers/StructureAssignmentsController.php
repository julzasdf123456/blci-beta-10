<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStructureAssignmentsRequest;
use App\Http\Requests\UpdateStructureAssignmentsRequest;
use App\Repositories\StructureAssignmentsRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\StructureAssignments;
use App\Models\MaterialsMatrix;
use App\Models\Structures;
use App\Models\IDGenerator;
use App\Models\BillOfMaterialsMatrix;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Flash;
use Response;

class StructureAssignmentsController extends AppBaseController
{
    /** @var  StructureAssignmentsRepository */
    private $structureAssignmentsRepository;

    public function __construct(StructureAssignmentsRepository $structureAssignmentsRepo)
    {
        $this->middleware('auth');
        $this->structureAssignmentsRepository = $structureAssignmentsRepo;
    }

    /**
     * Display a listing of the StructureAssignments.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $structureAssignments = $this->structureAssignmentsRepository->all();

        return view('structure_assignments.index')
            ->with('structureAssignments', $structureAssignments);
    }

    /**
     * Show the form for creating a new StructureAssignments.
     *
     * @return Response
     */
    public function create()
    {
        return view('structure_assignments.create');
    }

    /**
     * Store a newly created StructureAssignments in storage.
     *
     * @param CreateStructureAssignmentsRequest $request
     *
     * @return Response
     */
    public function store(CreateStructureAssignmentsRequest $request)
    {
        $input = $request->all();

        $structureAssignments = $this->structureAssignmentsRepository->create($input);

        Flash::success('Structure Assignments saved successfully.');

        return redirect(route('structureAssignments.index'));
    }

    /**
     * Display the specified StructureAssignments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $structureAssignments = $this->structureAssignmentsRepository->find($id);

        if (empty($structureAssignments)) {
            Flash::error('Structure Assignments not found');

            return redirect(route('structureAssignments.index'));
        }

        return view('structure_assignments.show')->with('structureAssignments', $structureAssignments);
    }

    /**
     * Show the form for editing the specified StructureAssignments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $structureAssignments = $this->structureAssignmentsRepository->find($id);

        if (empty($structureAssignments)) {
            Flash::error('Structure Assignments not found');

            return redirect(route('structureAssignments.index'));
        }

        return view('structure_assignments.edit')->with('structureAssignments', $structureAssignments);
    }

    /**
     * Update the specified StructureAssignments in storage.
     *
     * @param int $id
     * @param UpdateStructureAssignmentsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateStructureAssignmentsRequest $request)
    {
        $structureAssignments = $this->structureAssignmentsRepository->find($id);

        if (empty($structureAssignments)) {
            Flash::error('Structure Assignments not found');

            return redirect(route('structureAssignments.index'));
        }

        $structureAssignments = $this->structureAssignmentsRepository->update($request->all(), $id);

        Flash::success('Structure Assignments updated successfully.');

        return redirect(route('structureAssignments.index'));
    }

    /**
     * Remove the specified StructureAssignments from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $structureAssignments = $this->structureAssignmentsRepository->find($id);

        if (empty($structureAssignments)) {
            Flash::error('Structure Assignments not found');

            return redirect(route('structureAssignments.index'));
        }

        // DELETE BillOfMaterialsMatrix
        BillOfMaterialsMatrix::where('StructureAssigningId', $structureAssignments->id)->delete();

        $this->structureAssignmentsRepository->delete($id);

        // Flash::success('Structure Assignments deleted successfully.');

        // return redirect(route('structureAssignments.index'));
        echo json_encode(['response' => 'success']);
    }

    public function insertStructureAssignment(Request $request) {
        if (request()->ajax()) {
            // Query Structures
            $structureCore = Structures::where('Data', $request['Structure'])->first();

            // SAVE TO STRUCTURE ASSIGNMENTS FIRST
            $structure  = new StructureAssignments;
            $structure->id = IDGenerator::generateID();
            $structure->ServiceConnectionId = $request['ServiceConnectionId'];
            $structure->StructureId = $request['Structure'];
            $structure->Quantity = $request['Quantity'];
            $structure->ConAssGrouping = Structures::groupConAss($structureCore->Type);
            $structure->save();

            // $materials = MaterialsMatrix::where('StructureId', $structureCore->id)->get();

            // SAVE TO BillOfMaterialsMatrix
            $materials = DB::table('CRM_MaterialsMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_MaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
                ->where('CRM_MaterialsMatrix.StructureId', $structureCore->id)
                ->select('*')
                ->get();

            foreach($materials as $item) {
                try {
                    $billsOfMaterialsIndex = new BillOfMaterialsMatrix;
                    $billsOfMaterialsIndex->id = IDGenerator::generateRandString(25);
                    $billsOfMaterialsIndex->ServiceConnectionId = $request['ServiceConnectionId'];
                    $billsOfMaterialsIndex->StructureAssigningId = $structure->id;
                    $billsOfMaterialsIndex->StructureId = $item->StructureId;
                    $billsOfMaterialsIndex->MaterialsId = $item->MaterialsId;
                    $billsOfMaterialsIndex->Amount = $item->Amount;
                    $billsOfMaterialsIndex->Quantity = (intval($item->Quantity) * intval($request['Quantity'])) . '';
                    $billsOfMaterialsIndex->save();
                } catch (Exception $e) {
                    $billsOfMaterialsIndex = new BillOfMaterialsMatrix;
                    $billsOfMaterialsIndex->id = IDGenerator::generateRandString(25);
                    $billsOfMaterialsIndex->ServiceConnectionId = $request['ServiceConnectionId'];
                    $billsOfMaterialsIndex->StructureAssigningId = $structure->id;
                    $billsOfMaterialsIndex->StructureId = $item->StructureId;
                    $billsOfMaterialsIndex->MaterialsId = $item->MaterialsId;
                    $billsOfMaterialsIndex->Amount = $item->Amount;
                    $billsOfMaterialsIndex->Quantity = (intval($item->Quantity) * intval($request['Quantity'])) . '';
                    $billsOfMaterialsIndex->save();
                }                
            }           

            return json_encode($structure);
        }
    }

    public function deleteBrackets(Request $request) {
        if ($request->ajax()) {
            StructureAssignments::where('ServiceConnectionId', $request['ServiceConnectionId'])
                ->where('Type', 'A_DT')
                ->delete();
            BillOfMaterialsMatrix::where('ServiceConnectionId', $request['ServiceConnectionId'])
                ->where('StructureType', 'A_DT')
                ->delete();

            return json_encode(['response' => true]);
        }
    }

    public function getBracketStructure(Request $request) {
        if ($request->ajax()) {
            $structure = StructureAssignments::where('ServiceConnectionId', $request['ServiceConnectionId'])
                ->where('Type', 'A_DT')
                ->first();

            if ($structure != null) {
                return json_encode(['Structure' => $structure->StructureId]);
            } else {
                return json_encode(['Structure' => ""]);
            }            
        }
    }
}
