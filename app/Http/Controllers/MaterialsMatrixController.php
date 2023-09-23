<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMaterialsMatrixRequest;
use App\Http\Requests\UpdateMaterialsMatrixRequest;
use App\Repositories\MaterialsMatrixRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class MaterialsMatrixController extends AppBaseController
{
    /** @var  MaterialsMatrixRepository */
    private $materialsMatrixRepository;

    public function __construct(MaterialsMatrixRepository $materialsMatrixRepo)
    {
        $this->middleware('auth');
        $this->materialsMatrixRepository = $materialsMatrixRepo;
    }

    /**
     * Display a listing of the MaterialsMatrix.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $materialsMatrices = $this->materialsMatrixRepository->all();

        return view('materials_matrices.index')
            ->with('materialsMatrices', $materialsMatrices);
    }

    /**
     * Show the form for creating a new MaterialsMatrix.
     *
     * @return Response
     */
    public function create()
    {
        return view('materials_matrices.create');
    }

    /**
     * Store a newly created MaterialsMatrix in storage.
     *
     * @param CreateMaterialsMatrixRequest $request
     *
     * @return Response
     */
    public function store(CreateMaterialsMatrixRequest $request)
    {
        $input = $request->all();

        $materialsMatrix = $this->materialsMatrixRepository->create($input);

        Flash::success('Materials Matrix saved successfully.');

        return redirect(route('materialsMatrices.index'));
    }

    /**
     * Display the specified MaterialsMatrix.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $materialsMatrix = $this->materialsMatrixRepository->find($id);

        if (empty($materialsMatrix)) {
            Flash::error('Materials Matrix not found');

            return redirect(route('materialsMatrices.index'));
        }

        return view('materials_matrices.show')->with('materialsMatrix', $materialsMatrix);
    }

    /**
     * Show the form for editing the specified MaterialsMatrix.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $materialsMatrix = $this->materialsMatrixRepository->find($id);

        if (empty($materialsMatrix)) {
            Flash::error('Materials Matrix not found');

            return redirect(route('materialsMatrices.index'));
        }

        return view('materials_matrices.edit')->with('materialsMatrix', $materialsMatrix);
    }

    /**
     * Update the specified MaterialsMatrix in storage.
     *
     * @param int $id
     * @param UpdateMaterialsMatrixRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMaterialsMatrixRequest $request)
    {
        $materialsMatrix = $this->materialsMatrixRepository->find($id);

        if (empty($materialsMatrix)) {
            Flash::error('Materials Matrix not found');

            return redirect(route('materialsMatrices.index'));
        }

        $materialsMatrix = $this->materialsMatrixRepository->update($request->all(), $id);

        Flash::success('Materials Matrix updated successfully.');

        return redirect(route('materialsMatrices.index'));
    }

    /**
     * Remove the specified MaterialsMatrix from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $materialsMatrix = $this->materialsMatrixRepository->find($id);

        if (empty($materialsMatrix)) {
            Flash::error('Materials Matrix not found');

            return redirect(route('materialsMatrices.index'));
        }

        $this->materialsMatrixRepository->delete($id);

        Flash::success('Materials Matrix deleted successfully.');

        return redirect(route('materialsMatrices.index'));
    }
}
