<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePreDefinedMaterialsMatrixRequest;
use App\Http\Requests\UpdatePreDefinedMaterialsMatrixRequest;
use App\Repositories\PreDefinedMaterialsMatrixRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\PreDefinedMaterialsMatrix;
use Flash;
use Response;

class PreDefinedMaterialsMatrixController extends AppBaseController
{
    /** @var  PreDefinedMaterialsMatrixRepository */
    private $preDefinedMaterialsMatrixRepository;

    public function __construct(PreDefinedMaterialsMatrixRepository $preDefinedMaterialsMatrixRepo)
    {
        $this->middleware('auth');
        $this->preDefinedMaterialsMatrixRepository = $preDefinedMaterialsMatrixRepo;
    }

    /**
     * Display a listing of the PreDefinedMaterialsMatrix.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $preDefinedMaterialsMatrices = $this->preDefinedMaterialsMatrixRepository->all();

        return view('pre_defined_materials_matrices.index')
            ->with('preDefinedMaterialsMatrices', $preDefinedMaterialsMatrices);
    }

    /**
     * Show the form for creating a new PreDefinedMaterialsMatrix.
     *
     * @return Response
     */
    public function create()
    {
        return view('pre_defined_materials_matrices.create');
    }

    /**
     * Store a newly created PreDefinedMaterialsMatrix in storage.
     *
     * @param CreatePreDefinedMaterialsMatrixRequest $request
     *
     * @return Response
     */
    public function store(CreatePreDefinedMaterialsMatrixRequest $request)
    {
        $input = $request->all();

        $preDefinedMaterialsMatrix = $this->preDefinedMaterialsMatrixRepository->create($input);

        Flash::success('Pre Defined Materials Matrix saved successfully.');

        return redirect(route('preDefinedMaterialsMatrices.index'));
    }

    /**
     * Display the specified PreDefinedMaterialsMatrix.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $preDefinedMaterialsMatrix = $this->preDefinedMaterialsMatrixRepository->find($id);

        if (empty($preDefinedMaterialsMatrix)) {
            Flash::error('Pre Defined Materials Matrix not found');

            return redirect(route('preDefinedMaterialsMatrices.index'));
        }

        return view('pre_defined_materials_matrices.show')->with('preDefinedMaterialsMatrix', $preDefinedMaterialsMatrix);
    }

    /**
     * Show the form for editing the specified PreDefinedMaterialsMatrix.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $preDefinedMaterialsMatrix = $this->preDefinedMaterialsMatrixRepository->find($id);

        if (empty($preDefinedMaterialsMatrix)) {
            Flash::error('Pre Defined Materials Matrix not found');

            return redirect(route('preDefinedMaterialsMatrices.index'));
        }

        return view('pre_defined_materials_matrices.edit')->with('preDefinedMaterialsMatrix', $preDefinedMaterialsMatrix);
    }

    /**
     * Update the specified PreDefinedMaterialsMatrix in storage.
     *
     * @param int $id
     * @param UpdatePreDefinedMaterialsMatrixRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePreDefinedMaterialsMatrixRequest $request)
    {
        $preDefinedMaterialsMatrix = $this->preDefinedMaterialsMatrixRepository->find($id);

        if (empty($preDefinedMaterialsMatrix)) {
            Flash::error('Pre Defined Materials Matrix not found');

            return redirect(route('preDefinedMaterialsMatrices.index'));
        }

        $preDefinedMaterialsMatrix = $this->preDefinedMaterialsMatrixRepository->update($request->all(), $id);

        Flash::success('Pre Defined Materials Matrix updated successfully.');

        // return redirect(route('preDefinedMaterialsMatrices.index'));
        return json_encode(['response' => 'ok']);
    }

    /**
     * Remove the specified PreDefinedMaterialsMatrix from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $preDefinedMaterialsMatrix = $this->preDefinedMaterialsMatrixRepository->find($id);

        if (empty($preDefinedMaterialsMatrix)) {
            Flash::error('Pre Defined Materials Matrix not found');

            return redirect(route('preDefinedMaterialsMatrices.index'));
        }

        $this->preDefinedMaterialsMatrixRepository->delete($id);

        Flash::success('Pre Defined Materials Matrix deleted successfully.');

        // return redirect(route('preDefinedMaterialsMatrices.index'));
        return json_encode(['result' => 'ok']);
    }

    public function updateData(Request $request) {
        if ($request->ajax()) {
            $preDefinedMaterialsMatrices = PreDefinedMaterialsMatrix::find($request['id']);

            if ($preDefinedMaterialsMatrices != null) {
                $preDefinedMaterialsMatrices->Amount = $request['Amount'];
                $preDefinedMaterialsMatrices->Quantity = $request['Quantity'];
                if ($request['ApplicationType'] == 'Temporary' & $request['Options'] == 'Transformer Only') {
                    $preDefinedMaterialsMatrices->Cost = floatval($request['Quantity']) * floatval($request['Amount']) * 0.15 * floatval($request['MonthsDuration']);
                } else {
                    $preDefinedMaterialsMatrices->Cost = floatval($request['Quantity']) * floatval($request['Amount']);
                }
                $preDefinedMaterialsMatrices->LaborCost = floatval($preDefinedMaterialsMatrices->Cost) * floatval($request['LaborPercentage']);
                $preDefinedMaterialsMatrices->LaborPercentage = $request['LaborPercentage'];

                $preDefinedMaterialsMatrices->save();
            }
        }

        return json_encode(['response' => 'ok']);
    }

    public function reInit($scId, $options) {
        PreDefinedMaterialsMatrix::where('ServiceConnectionId', $scId)->delete();

        return redirect(route('serviceConnections.largeload-predefined-materials', [$scId, $options]));
    }

    public function addMaterial(Request $request) {
        if ($request->ajax()) {
            $preDefinedMaterialsMatrices = new PreDefinedMaterialsMatrix;
            $preDefinedMaterialsMatrices->id = IDGenerator::generateID();
            $preDefinedMaterialsMatrices->ServiceConnectionId = $request['ServiceConnectionId'];
            $preDefinedMaterialsMatrices->NEACode = $request['NEACode'];
            $preDefinedMaterialsMatrices->Description = $request['Description'];
            $preDefinedMaterialsMatrices->Quantity = $request['Quantity'];
            $preDefinedMaterialsMatrices->Options = $request['Options'];
            $preDefinedMaterialsMatrices->ApplicationType = $request['ApplicationType'];
            $preDefinedMaterialsMatrices->LaborPercentage = $request['LaborPercentage'];
            $preDefinedMaterialsMatrices->Amount = $request['Amount'];
            if ($request['ApplicationType'] == 'Temporary') {
                $preDefinedMaterialsMatrices->Cost = floatval($request['Quantity']) * floatval($request['Amount']) * 0.15 * floatval($request['MonthsDuration']);
            } else {
                $preDefinedMaterialsMatrices->Cost = floatval($request['Quantity']) * floatval($request['Amount']);
            }
            $preDefinedMaterialsMatrices->LaborCost = floatval($preDefinedMaterialsMatrices->Cost) * floatval($request['LaborPercentage']);

            $preDefinedMaterialsMatrices->save();

            return json_encode(['response' => 'ok']);
        }
    }
}
