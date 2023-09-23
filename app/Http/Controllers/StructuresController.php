<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStructuresRequest;
use App\Http\Requests\UpdateStructuresRequest;
use App\Repositories\StructuresRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Structures;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class StructuresController extends AppBaseController
{
    /** @var  StructuresRepository */
    private $structuresRepository;

    public function __construct(StructuresRepository $structuresRepo)
    {
        $this->middleware('auth');
        $this->structuresRepository = $structuresRepo;
    }

    /**
     * Display a listing of the Structures.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $structures = $this->structuresRepository->all();

        return view('structures.index')
            ->with('structures', $structures);
    }

    /**
     * Show the form for creating a new Structures.
     *
     * @return Response
     */
    public function create()
    {
        return view('structures.create');
    }

    /**
     * Store a newly created Structures in storage.
     *
     * @param CreateStructuresRequest $request
     *
     * @return Response
     */
    public function store(CreateStructuresRequest $request)
    {
        $input = $request->all();

        $structures = $this->structuresRepository->create($input);

        Flash::success('Structures saved successfully.');

        return redirect(route('structures.index'));
    }

    /**
     * Display the specified Structures.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $structures = $this->structuresRepository->find($id);

        $materials = DB::table('CRM_MaterialsMatrix')
            ->leftJoin('CRM_MaterialAssets', 'CRM_MaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
            ->select('CRM_MaterialsMatrix.id as id',
                    'CRM_MaterialAssets.id as NeaCode',
                    'CRM_MaterialAssets.Description as Description', 
                    'CRM_MaterialAssets.Amount as Rate',
                    'CRM_MaterialsMatrix.Quantity as Quantity')
            ->where('CRM_MaterialsMatrix.StructureId', $id)
            ->orderBy('CRM_MaterialAssets.Description')
            ->get();

        if (empty($structures)) {
            Flash::error('Structures not found');

            return redirect(route('structures.index'));
        }

        return view('structures.show', ['structures' => $structures, 'materials' => $materials]);
    }

    /**
     * Show the form for editing the specified Structures.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $structures = $this->structuresRepository->find($id);

        if (empty($structures)) {
            Flash::error('Structures not found');

            return redirect(route('structures.index'));
        }

        return view('structures.edit')->with('structures', $structures);
    }

    /**
     * Update the specified Structures in storage.
     *
     * @param int $id
     * @param UpdateStructuresRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateStructuresRequest $request)
    {
        $structures = $this->structuresRepository->find($id);

        if (empty($structures)) {
            Flash::error('Structures not found');

            return redirect(route('structures.index'));
        }

        $structures = $this->structuresRepository->update($request->all(), $id);

        Flash::success('Structures updated successfully.');

        return redirect(route('structures.index'));
    }

    /**
     * Remove the specified Structures from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $structures = $this->structuresRepository->find($id);

        if (empty($structures)) {
            Flash::error('Structures not found');

            return redirect(route('structures.index'));
        }

        $this->structuresRepository->delete($id);

        Flash::success('Structures deleted successfully.');

        return redirect(route('structures.index'));
    }

    public function getStructuresJson(Request $request) {
        if (request()->ajax()) {
            $data = Structures::all();

            echo json_encode($data);
        }
    }

    public function getStructuresByType(Request $request) {
        if (request()->ajax()) {
            $data = Structures::where('Type', $request['Type'])->get();

            echo json_encode($data);
        }
    }

}
