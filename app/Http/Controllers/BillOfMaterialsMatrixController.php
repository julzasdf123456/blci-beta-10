<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillOfMaterialsMatrixRequest;
use App\Http\Requests\UpdateBillOfMaterialsMatrixRequest;
use App\Repositories\BillOfMaterialsMatrixRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\ServiceConnections;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\BillOfMaterialsMatrix;
use App\Models\MaterialsMatrix;
use App\Exports\BillOfMaterialsExport;
use App\Models\TransformersAssignedMatrix;
use App\Models\StructureAssignments;
use App\Models\Structures;
use App\Models\SpanningData;
use App\Models\MaterialAssets;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\IDGenerator;
use Flash;
use Response;

class BillOfMaterialsMatrixController extends AppBaseController
{
    /** @var  BillOfMaterialsMatrixRepository */
    private $billOfMaterialsMatrixRepository;

    public function __construct(BillOfMaterialsMatrixRepository $billOfMaterialsMatrixRepo)
    {
        $this->middleware('auth');
        $this->billOfMaterialsMatrixRepository = $billOfMaterialsMatrixRepo;
    }

    /**
     * Display a listing of the BillOfMaterialsMatrix.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $billOfMaterialsMatrices = $this->billOfMaterialsMatrixRepository->all();

        return view('bill_of_materials_matrices.index')
            ->with('billOfMaterialsMatrices', $billOfMaterialsMatrices);
    }

    /**
     * Show the form for creating a new BillOfMaterialsMatrix.
     *
     * @return Response
     */
    public function create()
    {
        return view('bill_of_materials_matrices.create');
    }

    /**
     * Store a newly created BillOfMaterialsMatrix in storage.
     *
     * @param CreateBillOfMaterialsMatrixRequest $request
     *
     * @return Response
     */
    public function store(CreateBillOfMaterialsMatrixRequest $request)
    {
        $input = $request->all();

        $billOfMaterialsMatrix = $this->billOfMaterialsMatrixRepository->create($input);

        Flash::success('Bill Of Materials Matrix saved successfully.');

        return redirect(route('billOfMaterialsMatrices.index'));
    }

    /**
     * Display the specified BillOfMaterialsMatrix.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $billOfMaterialsMatrix = $this->billOfMaterialsMatrixRepository->find($id);

        if (empty($billOfMaterialsMatrix)) {
            Flash::error('Bill Of Materials Matrix not found');

            return redirect(route('billOfMaterialsMatrices.index'));
        }

        return view('bill_of_materials_matrices.show')->with('billOfMaterialsMatrix', $billOfMaterialsMatrix);
    }

    /**
     * Show the form for editing the specified BillOfMaterialsMatrix.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $billOfMaterialsMatrix = $this->billOfMaterialsMatrixRepository->find($id);

        if (empty($billOfMaterialsMatrix)) {
            Flash::error('Bill Of Materials Matrix not found');

            return redirect(route('billOfMaterialsMatrices.index'));
        }

        return view('bill_of_materials_matrices.edit')->with('billOfMaterialsMatrix', $billOfMaterialsMatrix);
    }

    /**
     * Update the specified BillOfMaterialsMatrix in storage.
     *
     * @param int $id
     * @param UpdateBillOfMaterialsMatrixRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillOfMaterialsMatrixRequest $request)
    {
        $billOfMaterialsMatrix = $this->billOfMaterialsMatrixRepository->find($id);

        if (empty($billOfMaterialsMatrix)) {
            Flash::error('Bill Of Materials Matrix not found');

            return redirect(route('billOfMaterialsMatrices.index'));
        }

        $billOfMaterialsMatrix = $this->billOfMaterialsMatrixRepository->update($request->all(), $id);

        Flash::success('Bill Of Materials Matrix updated successfully.');

        return redirect(route('billOfMaterialsMatrices.index'));
    }

    /**
     * Remove the specified BillOfMaterialsMatrix from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $billOfMaterialsMatrix = $this->billOfMaterialsMatrixRepository->find($id);

        if (empty($billOfMaterialsMatrix)) {
            Flash::error('Bill Of Materials Matrix not found');

            return redirect(route('billOfMaterialsMatrices.index'));
        }

        $this->billOfMaterialsMatrixRepository->delete($id);

        Flash::success('Bill Of Materials Matrix deleted successfully.');

        return redirect(route('billOfMaterialsMatrices.index'));
    }

    public function view($scId) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->join('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.ContactNumber',
                    'CRM_ServiceConnections.BuildingType',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->where('CRM_ServiceConnections.id', $scId)
            ->first();

        $billOfMaterials = DB::table('CRM_BillOfMaterialsMatrix')
            ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
            ->leftJoin('CRM_Structures', 'CRM_BillOfMaterialsMatrix.StructureId', '=', 'CRM_Structures.id')    
            ->select('CRM_MaterialAssets.id',
                    'CRM_MaterialAssets.Description',
                    'CRM_MaterialAssets.Amount',
                    DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),
                    DB::raw('(CAST(CRM_MaterialAssets.Amount As Money) * SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer))) AS ExtendedCost'))
            ->whereIn('CRM_Structures.Data', function($query)  use ($scId) {
                $query->select('StructureId')
                    ->from('CRM_StructureAssignments')
                    ->where('ServiceConnectionId', $scId);
            })
            ->groupBy('CRM_MaterialAssets.Description', 'CRM_MaterialAssets.Amount', 'CRM_MaterialAssets.id')
            ->orderBy('CRM_MaterialAssets.Description')
            ->get();   

        return view('/bill_of_materials_matrices/view', ['serviceConnection' => $serviceConnection, 'billOfMaterials' => $billOfMaterials]);
    }

    public function downloadBillOfMaterials($scId) {
        $data = DB::table('CRM_BillOfMaterialsMatrix')
            ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
            ->leftJoin('CRM_Structures', 'CRM_BillOfMaterialsMatrix.StructureId', '=', 'CRM_Structures.id')    
            ->select('CRM_MaterialAssets.id',
                    'CRM_MaterialAssets.Description',
                    'CRM_MaterialAssets.Amount',
                    DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),
                    DB::raw('(CAST(CRM_MaterialAssets.Amount As Money) * SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer))) AS ExtendedCost'))
            ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
            ->groupBy('CRM_MaterialAssets.Description', 'CRM_MaterialAssets.Amount', 'CRM_MaterialAssets.id')
            ->orderBy('CRM_MaterialAssets.Description')
            ->get();  
            
            $export = new BillOfMaterialsExport($data->toArray());

            // dd($data);
        return Excel::download($export, 'BillOfMaterials.xlsx');
    }

    public function getBillOfMaterialsJson(Request $request) {
        if ($request->ajax()) {
            $scId = $request['scId'];
            $billOfMaterials = DB::table('CRM_BillOfMaterialsMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
                ->leftJoin('CRM_Structures', 'CRM_BillOfMaterialsMatrix.StructureId', '=', 'CRM_Structures.id')    
                ->select('CRM_MaterialAssets.id',
                        'CRM_MaterialAssets.Description',
                        'CRM_BillOfMaterialsMatrix.Amount',
                        DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),
                        DB::raw('(CAST(CRM_BillOfMaterialsMatrix.Amount As Money) * SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer))) AS ExtendedCost'))
                ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
                ->whereNull('CRM_BillOfMaterialsMatrix.StructureType')
                ->groupBy('CRM_MaterialAssets.Description', 'CRM_BillOfMaterialsMatrix.Amount', 'CRM_MaterialAssets.id')
                ->orderBy('CRM_MaterialAssets.Description')
                ->get();  

            echo json_encode($billOfMaterials);
        }        
    }

    public function getBillOfMaterialsBrackets(Request $request) {
        if ($request->ajax()) {
            $scId = $request['scId'];
            $billOfMaterials = DB::table('CRM_BillOfMaterialsMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
                ->leftJoin('CRM_Structures', 'CRM_BillOfMaterialsMatrix.StructureId', '=', 'CRM_Structures.id')    
                ->select('CRM_MaterialAssets.id',
                        'CRM_MaterialAssets.Description',
                        'CRM_BillOfMaterialsMatrix.Amount',
                        DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),
                        DB::raw('(CAST(CRM_BillOfMaterialsMatrix.Amount As Money) * SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer))) AS ExtendedCost'))
                ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
                ->where('CRM_BillOfMaterialsMatrix.StructureType', 'A_DT')
                ->groupBy('CRM_MaterialAssets.Description', 'CRM_BillOfMaterialsMatrix.Amount', 'CRM_MaterialAssets.id')
                ->orderBy('CRM_MaterialAssets.Description')
                ->get(); 

            echo json_encode($billOfMaterials);
        }        
    }

    public function insertTransformerBracket(Request $request) {
        if ($request->ajax()) {
            // QUERY TOTAL QUANTITY OF TRANSFORMER ASSIGNED
            $transformers = TransformersAssignedMatrix::where('ServiceConnectionId', $request['ServiceConnectionId'])
                                ->where('Type', 'Transformer')
                                ->get();
            $totalTrans = $request['Quantity'];

            // SAVE TO STRUCTURE ASSIGNMENTS FIRST
            $structureCore = Structures::find($request['StructureId']);

            $structure  = new StructureAssignments;
            $structure->id = IDGenerator::generateIDandRandString();
            $structure->ServiceConnectionId = $request['ServiceConnectionId'];
            $structure->StructureId = $structureCore->Data;
            $structure->Quantity = $totalTrans;
            $structure->Type = 'A_DT'; // TRANSFORMER 
            $structure->ConAssGrouping = Structures::groupConAss($structureCore->Type);
            $structure->save();

            // QUERY MATERIALS INSIDE STRUCTURE
            $materials = DB::table('CRM_MaterialsMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_MaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
                ->where('CRM_MaterialsMatrix.StructureId', $structureCore->id)
                ->select('*')
                ->get();

            if ($materials != null) {
                foreach ($materials as $item) {
                    // INSERT TO BillOfMaterialsMatrix
                    $bracket = new BillOfMaterialsMatrix;
                    $bracket->id = IDGenerator::generateIDandRandString();
                    $bracket->ServiceConnectionId = $request['ServiceConnectionId'];
                    $bracket->StructureAssigningId = $structure->id;
                    $bracket->StructureId = $request['StructureId'];
                    $bracket->MaterialsId = $item->MaterialsId;
                    $bracket->Amount = $item->Amount;
                    $bracket->Quantity = ($totalTrans * intval($item->Quantity));
                    $bracket->StructureType = 'A_DT'; // BRACKET TYPE
                    $bracket->save();
                }
            }
            
            return json_encode(['response' => 'ok']);
        }
    }

    public function insertPole(Request $request) {
        if ($request->ajax()) {
            $materials = DB::table('CRM_PoleIndex')
                ->leftJoin('CRM_MaterialAssets', 'CRM_PoleIndex.NEACode', '=', 'CRM_MaterialAssets.id')
                ->where('CRM_PoleIndex.NEACode', $request['MaterialsId'])
                ->select('*')
                ->first();

            $pole = new BillOfMaterialsMatrix;
            $pole->id = IDGenerator::generateIDandRandString();
            $pole->ServiceConnectionId = $request['ServiceConnectionId'];
            $pole->MaterialsId = $request['MaterialsId'];
            $pole->Quantity = $request['Quantity'];
            $pole->Amount = $materials->Amount;
            $pole->StructureType = 'POLE'; // FOR POLES
            $pole->save();

            return json_encode(['response' => 'ok']);
        }
    }

    public function fetchPoles(Request $request) {
        if ($request->ajax()) {
            $poleAssigned = DB::table('CRM_BillOfMaterialsMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')  
                ->select('CRM_BillOfMaterialsMatrix.id',
                        'CRM_MaterialAssets.Description',
                        'CRM_MaterialAssets.Amount',
                        'CRM_BillOfMaterialsMatrix.Quantity')
                ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $request['ServiceConnectionId'])
                ->where('CRM_BillOfMaterialsMatrix.StructureType', 'POLE')
                ->orderBy('CRM_MaterialAssets.Description')
                ->get(); 

            return json_encode($poleAssigned);
        }
    }

    public function deletePole(Request $request) {
        if ($request->ajax()) {
            BillOfMaterialsMatrix::find($request['id'])->delete(); 

            return json_encode(['response' => 'deleted']);
        }
    }

    public function deleteMaterial(Request $request) {
        if ($request->ajax()) {
            BillOfMaterialsMatrix::where('ServiceConnectionId', $request['ServiceConnectionId'])
                        ->where('MaterialsId', $request['MaterialsId'])    
                        ->whereNull('StructureType')        
                        ->delete();

            return json_encode(['response' => true]);
        }
    }

    public function addCustomMaterial(Request $request) {
        if ($request->ajax()) {
            $sourceMat = MaterialAssets::find($request['MaterialsId']);

            if ($sourceMat != null) {
                $material = new BillOfMaterialsMatrix;
                $material->id = IDGenerator::generateIDandRandString();
                $material->ServiceConnectionId = $request['ServiceConnectionId'];
                $material->MaterialsId = $request['MaterialsId'];
                $material->Quantity = $request['Quantity'];
                $material->Amount = $sourceMat->Amount;
                $material->save();
            } 

            return json_encode(['response' => true]);
        }
    }

    public function insertSpanningMaterials(Request $request) {
        if ($request->ajax()) {
            BillOfMaterialsMatrix::where('ServiceConnectionId', $request['data'][0]['svcId'])
                        ->where('StructureType', 'SPAN')
                        ->delete();

            StructureAssignments::where('ServiceConnectionId', $request['data'][0]['svcId'])
                        ->where('ConAssGrouping', '9')
                        ->delete();

            foreach($request['data'] as $item) {
                $spanData = DB::table('CRM_SpanningIndex')
                    ->leftJoin('CRM_MaterialAssets', 'CRM_SpanningIndex.NeaCode', '=', 'CRM_MaterialAssets.id')
                    ->where('CRM_SpanningIndex.Size', $item['size'])
                    ->where('CRM_SpanningIndex.Type', $item['type'])
                    ->select('CRM_MaterialAssets.id',
                            'CRM_SpanningIndex.Structure',
                            'CRM_SpanningIndex.Description',
                            'CRM_MaterialAssets.Amount',
                            'CRM_SpanningIndex.id as SpanId',
                            'CRM_SpanningIndex.SpliceNeaCode')
                    ->first();

                // INSERT TO SpanninData
                $spanning = SpanningData::where('ServiceConnectionId', $item['svcId'])->first();
                if ($spanning == null) { // CREATE NEW
                    $spanning = new SpanningData;
                    $spanning->id = IDGenerator::generateIDandRandString();
                    $spanning->ServiceConnectionId = $item['svcId'];

                    if ($item['line'] == 'primary') {
                        $spanning->PrimarySpan = $item['span'];
                        $spanning->PrimarySize = $item['size'];
                        $spanning->PrimaryType = $item['type'];
                    } elseif ($item['line'] == 'neutral') {
                        $spanning->NeutralSpan = $item['span'];
                        $spanning->NeutralSize = $item['size'];
                        $spanning->NeutralType = $item['type'];
                    } elseif ($item['line'] == 'secondary') {
                        $spanning->SecondarySpan = $item['span'];
                        $spanning->SecondarySize = $item['size'];
                        $spanning->SecondaryType = $item['type'];
                    }

                    $spanning->save();
                } else { // UPDATE
                    if ($item['line'] == 'primary') {
                        $spanning->PrimarySpan = $item['span'];
                        $spanning->PrimarySize = $item['size'];
                        $spanning->PrimaryType = $item['type'];
                    } elseif ($item['line'] == 'neutral') {
                        $spanning->NeutralSpan = $item['span'];
                        $spanning->NeutralSize = $item['size'];
                        $spanning->NeutralType = $item['type'];
                    } elseif ($item['line'] == 'secondary') {
                        $spanning->SecondarySpan = $item['span'];
                        $spanning->SecondarySize = $item['size'];
                        $spanning->SecondaryType = $item['type'];
                    }

                    $spanning->save();
                }

                if ($spanData != null) {
                    // SAVE TO StructureAssignments
                    $structureAssignments = new StructureAssignments;
                    $structureAssignments->id = IDGenerator::generateIDandRandString();
                    $structureAssignments->ServiceConnectionId = $item['svcId'];
                    $structureAssignments->StructureId = $spanData->Structure;
                    $structureAssignments->Quantity = 1000 * floatval($item['span']);
                    $structureAssignments->ConAssGrouping = '9';
                    $structureAssignments->save();

                    // SAVE TO BillsOfMaterialsMatrix
                    $billOfMaterialsMatrix = new BillOfMaterialsMatrix;
                    $billOfMaterialsMatrix->id = IDGenerator::generateIDandRandString();
                    $billOfMaterialsMatrix->ServiceConnectionId = $item['svcId'];
                    $billOfMaterialsMatrix->StructureAssigningId = $structureAssignments->id;
                    $billOfMaterialsMatrix->StructureId = $spanData->SpanId;
                    $billOfMaterialsMatrix->MaterialsId = $spanData->id;
                    $billOfMaterialsMatrix->Quantity = 1000 * floatval($item['span']);
                    $billOfMaterialsMatrix->StructureType = 'SPAN';
                    $billOfMaterialsMatrix->Amount = $spanData->Amount;
                    $billOfMaterialsMatrix->save();

                    // SAVE Splice Data if There's any
                    if ($spanData->SpliceNeaCode != null) {
                        // SAVE TO BillsOfMaterialsMatrix
                        $billOfMaterialsMatrixSplice = new BillOfMaterialsMatrix;
                        $billOfMaterialsMatrixSplice->id = IDGenerator::generateIDandRandString();
                        $billOfMaterialsMatrixSplice->StructureAssigningId = $structureAssignments->id;
                        $billOfMaterialsMatrixSplice->ServiceConnectionId = $item['svcId'];
                        $billOfMaterialsMatrixSplice->MaterialsId = $spanData->SpliceNeaCode;
                        $billOfMaterialsMatrixSplice->StructureId = $spanData->SpanId;
                        $billOfMaterialsMatrixSplice->Quantity = '1';
                        $billOfMaterialsMatrixSplice->StructureType = 'SPAN';
                        $billOfMaterialsMatrixSplice->Amount = $spanData->Amount;
                        $billOfMaterialsMatrixSplice->save();
                    }
                }
            }

            return json_encode(['response' => true]);
        }
    }

    public function fetchSpanMaterials(Request $request) {
        if ($request->ajax()) {
            $billOfMaterials = DB::table('CRM_BillOfMaterialsMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id') 
                ->select('CRM_MaterialAssets.id',
                        'CRM_MaterialAssets.Description',
                        'CRM_BillOfMaterialsMatrix.Amount',
                        DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),
                        DB::raw('(CAST(CRM_BillOfMaterialsMatrix.Amount As Money) * SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer))) AS ExtendedCost'))
                ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $request['scId'])
                ->where('CRM_BillOfMaterialsMatrix.StructureType', 'SPAN')
                ->groupBy('CRM_MaterialAssets.Description', 'CRM_BillOfMaterialsMatrix.Amount', 'CRM_MaterialAssets.id')
                ->orderBy('CRM_MaterialAssets.Description')
                ->get(); 

            echo json_encode($billOfMaterials);
        }
    }

    public function deleteSpanMaterial(Request $request) {
        if ($request->ajax()) {
            BillOfMaterialsMatrix::where('ServiceConnectionId', $request['ServiceConnectionId'])
                        ->where('MaterialsId', $request['MaterialsId'])    
                        ->where('StructureType', 'SPAN')        
                        ->delete();

            return json_encode(['response' => true]);
        }
    }

    public function insertSDWMaterials(Request $request) {
        if ($request->ajax()) {
            $spanData = DB::table('CRM_SpanningIndex')
                    ->leftJoin('CRM_MaterialAssets', 'CRM_SpanningIndex.NeaCode', '=', 'CRM_MaterialAssets.id')
                    ->where('CRM_SpanningIndex.Size', $request['Size'])
                    ->where('CRM_SpanningIndex.Type', $request['Type'])
                    ->select('CRM_MaterialAssets.id',
                            'CRM_SpanningIndex.Structure',
                            'CRM_SpanningIndex.id as SpanId',
                            'CRM_SpanningIndex.Description',
                            'CRM_MaterialAssets.Amount',)
                    ->first();

            $structureAssignments = StructureAssignments::where('StructureId', $spanData->Structure)->first();
            if ($structureAssignments != null) {
                StructureAssignments::where('StructureId', $spanData->Structure)->delete();
            }

                // INSERT TO SpanninData
            $spanning = SpanningData::where('ServiceConnectionId', $request['ServiceConnectionId'])->first();
            if ($spanning == null) { // CREATE NEW
                $spanning = new SpanningData;
                $spanning->id = IDGenerator::generateIDandRandString();
                $spanning->ServiceConnectionId = $request['ServiceConnectionId'];

                $spanning->SDWSpan = $request['Span'];
                $spanning->SDWSize = $request['Size'];
                $spanning->SDWType = $request['Type'];

                $spanning->save();
            } else { // UPDATE
                $spanning->SDWSpan = $request['Span'];
                $spanning->SDWSize = $request['Size'];
                $spanning->SDWType = $request['Type'];

                $spanning->save();
            }

            // SAVE TO StructureAssignments
            $structureAssignments = new StructureAssignments;
            $structureAssignments->id = IDGenerator::generateIDandRandString();
            $structureAssignments->ServiceConnectionId = $request['ServiceConnectionId'];
            $structureAssignments->StructureId = $spanData->Structure;
            $structureAssignments->Quantity = 1000 * floatval($request['Span']);
            $structureAssignments->ConAssGrouping = '9';
            $structureAssignments->save();

            // SAVE TO BillsOfMaterialsMatrix
            $billOfMaterialsMatrix = new BillOfMaterialsMatrix;
            $billOfMaterialsMatrix->id = IDGenerator::generateIDandRandString();
            $billOfMaterialsMatrix->ServiceConnectionId = $request['ServiceConnectionId'];
            $billOfMaterialsMatrix->StructureAssigningId = $structureAssignments->id;
            $billOfMaterialsMatrix->MaterialsId = $spanData->id;
            $billOfMaterialsMatrix->StructureId = $spanData->SpanId;
            $billOfMaterialsMatrix->Quantity = 1000 * floatval($request['Span']);
            $billOfMaterialsMatrix->StructureType = 'SPAN';
            $billOfMaterialsMatrix->Amount = $spanData->Amount;
            $billOfMaterialsMatrix->save();
        }
    }

    public function insertSpecialEquipment(Request $request) {
        if ($request->ajax()) {
            $materials = DB::table('CRM_SpecialEquipmentMaterials')
                ->leftJoin('CRM_MaterialAssets', 'CRM_SpecialEquipmentMaterials.NEACode', '=', 'CRM_MaterialAssets.id')
                ->where('CRM_SpecialEquipmentMaterials.NEACode', $request['MaterialsId'])
                ->select('*')
                ->first();

            $pole = new BillOfMaterialsMatrix;
            $pole->id = IDGenerator::generateIDandRandString();
            $pole->ServiceConnectionId = $request['ServiceConnectionId'];
            $pole->MaterialsId = $request['MaterialsId'];
            $pole->Quantity = $request['Quantity'];
            $pole->Amount = $materials->Amount;
            $pole->StructureType = 'SPEC_EQUIP'; // FOR POLES
            $pole->save();

            return json_encode(['response' => 'ok']);
        }
    }

    public function fetchEquipments(Request $request) {
        if ($request->ajax()) {
            $equipmentAssigned = DB::table('CRM_BillOfMaterialsMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')  
                ->select('CRM_BillOfMaterialsMatrix.id',
                        'CRM_MaterialAssets.Description',
                        'CRM_MaterialAssets.Amount',
                        'CRM_BillOfMaterialsMatrix.Quantity')
                ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $request['ServiceConnectionId'])
                ->where('CRM_BillOfMaterialsMatrix.StructureType', 'SPEC_EQUIP')
                ->orderBy('CRM_MaterialAssets.Description')
                ->get(); 

            return json_encode($equipmentAssigned);
        }
    }
}
