<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSpecialEquipmentMaterialsRequest;
use App\Http\Requests\UpdateSpecialEquipmentMaterialsRequest;
use App\Repositories\SpecialEquipmentMaterialsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SpecialEquipmentMaterials;
use App\Models\IDGenerator;
use Flash;
use Response;

class SpecialEquipmentMaterialsController extends AppBaseController
{
    /** @var  SpecialEquipmentMaterialsRepository */
    private $specialEquipmentMaterialsRepository;

    public function __construct(SpecialEquipmentMaterialsRepository $specialEquipmentMaterialsRepo)
    {
        $this->middleware('auth');
        $this->specialEquipmentMaterialsRepository = $specialEquipmentMaterialsRepo;
    }

    /**
     * Display a listing of the SpecialEquipmentMaterials.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $specialEquipmentMaterials = DB::table('CRM_SpecialEquipmentMaterials')
            ->leftJoin('CRM_MaterialAssets', 'CRM_SpecialEquipmentMaterials.NEACode', '=', 'CRM_MaterialAssets.id')
            ->select('CRM_SpecialEquipmentMaterials.id',
                'CRM_SpecialEquipmentMaterials.NEACode',
                'CRM_MaterialAssets.Description',
                'CRM_MaterialAssets.Amount')
            ->get();

        $materialAssets = DB::table('CRM_MaterialAssets')->whereNotIn('id', function($q){
                $q->select('NEACode')->from('CRM_SpecialEquipmentMaterials');
            })->get();

        

        return view('special_equipment_materials.index', [
            'specialEquipmentMaterials' => $specialEquipmentMaterials,
            'materialAssets' => $materialAssets,
        ]);
    }

    /**
     * Show the form for creating a new SpecialEquipmentMaterials.
     *
     * @return Response
     */
    public function create()
    {
        return view('special_equipment_materials.create');
    }

    /**
     * Store a newly created SpecialEquipmentMaterials in storage.
     *
     * @param CreateSpecialEquipmentMaterialsRequest $request
     *
     * @return Response
     */
    public function store(CreateSpecialEquipmentMaterialsRequest $request)
    {
        $input = $request->all();

        $specialEquipmentMaterials = $this->specialEquipmentMaterialsRepository->create($input);

        Flash::success('Special Equipment Materials saved successfully.');

        return redirect(route('specialEquipmentMaterials.index'));
    }

    /**
     * Display the specified SpecialEquipmentMaterials.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $specialEquipmentMaterials = $this->specialEquipmentMaterialsRepository->find($id);

        if (empty($specialEquipmentMaterials)) {
            Flash::error('Special Equipment Materials not found');

            return redirect(route('specialEquipmentMaterials.index'));
        }

        return view('special_equipment_materials.show')->with('specialEquipmentMaterials', $specialEquipmentMaterials);
    }

    /**
     * Show the form for editing the specified SpecialEquipmentMaterials.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $specialEquipmentMaterials = $this->specialEquipmentMaterialsRepository->find($id);

        if (empty($specialEquipmentMaterials)) {
            Flash::error('Special Equipment Materials not found');

            return redirect(route('specialEquipmentMaterials.index'));
        }

        return view('special_equipment_materials.edit')->with('specialEquipmentMaterials', $specialEquipmentMaterials);
    }

    /**
     * Update the specified SpecialEquipmentMaterials in storage.
     *
     * @param int $id
     * @param UpdateSpecialEquipmentMaterialsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSpecialEquipmentMaterialsRequest $request)
    {
        $specialEquipmentMaterials = $this->specialEquipmentMaterialsRepository->find($id);

        if (empty($specialEquipmentMaterials)) {
            Flash::error('Special Equipment Materials not found');

            return redirect(route('specialEquipmentMaterials.index'));
        }

        $specialEquipmentMaterials = $this->specialEquipmentMaterialsRepository->update($request->all(), $id);

        Flash::success('Special Equipment Materials updated successfully.');

        return redirect(route('specialEquipmentMaterials.index'));
    }

    /**
     * Remove the specified SpecialEquipmentMaterials from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $specialEquipmentMaterials = $this->specialEquipmentMaterialsRepository->find($id);

        if (empty($specialEquipmentMaterials)) {
            Flash::error('Special Equipment Materials not found');

            return redirect(route('specialEquipmentMaterials.index'));
        }

        $this->specialEquipmentMaterialsRepository->delete($id);

        Flash::success('Special Equipment Materials deleted successfully.');

        return redirect(route('specialEquipmentMaterials.index'));
    }

    public function createEquipment(Request $request) {
        if ($request->ajax()) {
            $material = new SpecialEquipmentMaterials;
            $material->id = IDGenerator::generateID();
            $material->NEACode = $request['NEACode'];

            if ($material->save()) {
                return response()->json(['response' => 'ok'], 200);
            } else {
                return response()->json(['response' => 'error'], 401);
            }
        }
    }
}
