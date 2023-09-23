<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransformersAssignedMatrixRequest;
use App\Http\Requests\UpdateTransformersAssignedMatrixRequest;
use App\Repositories\TransformersAssignedMatrixRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\TransformersAssignedMatrix;
use App\Models\TransformerIndex;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class TransformersAssignedMatrixController extends AppBaseController
{
    /** @var  TransformersAssignedMatrixRepository */
    private $transformersAssignedMatrixRepository;

    public function __construct(TransformersAssignedMatrixRepository $transformersAssignedMatrixRepo)
    {
        $this->middleware('auth');
        $this->transformersAssignedMatrixRepository = $transformersAssignedMatrixRepo;
    }

    /**
     * Display a listing of the TransformersAssignedMatrix.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $transformersAssignedMatrices = $this->transformersAssignedMatrixRepository->all();

        return view('transformers_assigned_matrices.index')
            ->with('transformersAssignedMatrices', $transformersAssignedMatrices);
    }

    /**
     * Show the form for creating a new TransformersAssignedMatrix.
     *
     * @return Response
     */
    public function create()
    {
        return view('transformers_assigned_matrices.create');
    }

    /**
     * Store a newly created TransformersAssignedMatrix in storage.
     *
     * @param CreateTransformersAssignedMatrixRequest $request
     *
     * @return Response
     */
    public function store(CreateTransformersAssignedMatrixRequest $request)
    {
        $input = $request->all();

        $transformersAssignedMatrix = $this->transformersAssignedMatrixRepository->create($input);

        Flash::success('Transformers Assigned Matrix saved successfully.');

        return redirect(route('transformersAssignedMatrices.index'));
    }

    /**
     * Display the specified TransformersAssignedMatrix.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $transformersAssignedMatrix = $this->transformersAssignedMatrixRepository->find($id);

        if (empty($transformersAssignedMatrix)) {
            Flash::error('Transformers Assigned Matrix not found');

            return redirect(route('transformersAssignedMatrices.index'));
        }

        return view('transformers_assigned_matrices.show')->with('transformersAssignedMatrix', $transformersAssignedMatrix);
    }

    /**
     * Show the form for editing the specified TransformersAssignedMatrix.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $transformersAssignedMatrix = $this->transformersAssignedMatrixRepository->find($id);

        if (empty($transformersAssignedMatrix)) {
            Flash::error('Transformers Assigned Matrix not found');

            return redirect(route('transformersAssignedMatrices.index'));
        }

        return view('transformers_assigned_matrices.edit')->with('transformersAssignedMatrix', $transformersAssignedMatrix);
    }

    /**
     * Update the specified TransformersAssignedMatrix in storage.
     *
     * @param int $id
     * @param UpdateTransformersAssignedMatrixRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTransformersAssignedMatrixRequest $request)
    {
        $transformersAssignedMatrix = $this->transformersAssignedMatrixRepository->find($id);

        if (empty($transformersAssignedMatrix)) {
            Flash::error('Transformers Assigned Matrix not found');

            return redirect(route('transformersAssignedMatrices.index'));
        }

        $transformersAssignedMatrix = $this->transformersAssignedMatrixRepository->update($request->all(), $id);

        Flash::success('Transformers Assigned Matrix updated successfully.');

        return redirect(route('transformersAssignedMatrices.index'));
    }

    /**
     * Remove the specified TransformersAssignedMatrix from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $transformersAssignedMatrix = $this->transformersAssignedMatrixRepository->find($id);

        if (empty($transformersAssignedMatrix)) {
            Flash::error('Transformers Assigned Matrix not found');

            return redirect(route('transformersAssignedMatrices.index'));
        }

        $this->transformersAssignedMatrixRepository->delete($id);

        Flash::success('Transformers Assigned Matrix deleted successfully.');

        // return redirect(route('transformersAssignedMatrices.index'));
        echo json_encode(['response' => 'success']);
    }

    public function createAjax(Request $request) {
        if ($request->ajax()) {
            $materials = DB::table('CRM_TransformerIndex')
                ->leftJoin('CRM_MaterialAssets', 'CRM_TransformerIndex.NEACode', '=', 'CRM_MaterialAssets.id')
                ->where('CRM_TransformerIndex.NEACode', $request['MaterialsId'])
                ->select('*')
                ->first();


            // ADD TRANSFORMER
            $transformerMatrix = new TransformersAssignedMatrix;
            $transformerMatrix->id = IDGenerator::generateIDandRandString();
            $transformerMatrix->ServiceConnectionId = $request['ServiceConnectionId'];
            $transformerMatrix->MaterialsId = $request['MaterialsId'];
            $transformerMatrix->Quantity = $request['Quantity'];
            $transformerMatrix->Type = 'Transformer';
            $transformerMatrix->Amount = $materials->Amount;
            $transformerMatrix->save();

            // ADD FUSELINK IF THERE IS ANY
            $transformerIndex = TransformerIndex::find($request['TransformerId']);
            if ($transformerIndex != null) {
                if ($transformerIndex->LinkFuseCode != null) {
                    $materials = DB::table('CRM_MaterialAssets')
                        ->where('CRM_MaterialAssets.id', $transformerIndex->LinkFuseCode)
                        ->select('*')
                        ->first();

                    $linkFuse = new TransformersAssignedMatrix;
                    $linkFuse->id = IDGenerator::generateIDandRandString();
                    $linkFuse->ServiceConnectionId = $request['ServiceConnectionId'];
                    $linkFuse->MaterialsId = $transformerIndex->LinkFuseCode;
                    $linkFuse->Quantity = $request['Quantity'];
                    $linkFuse->Amount = $materials->Amount;
                    $linkFuse->Type = 'Fuse';
                    $linkFuse->save();
                }
            }
            
            echo json_encode(['response' => 'ok']);
        }
    }

    public function fetchTransformers(Request $request) {
        if ($request->ajax()) {
            $transformerMatrix = DB::table('CRM_TransformersAssignedMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_TransformersAssignedMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
                ->select('CRM_TransformersAssignedMatrix.id',
                        'CRM_MaterialAssets.Description',
                        'CRM_TransformersAssignedMatrix.Amount',
                        'CRM_TransformersAssignedMatrix.Quantity')
                ->where('CRM_TransformersAssignedMatrix.ServiceConnectionId', $request['ServiceConnectionId'])
                ->get();

            echo json_encode($transformerMatrix);
        }        
    }
}
