<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMaterialAssetsRequest;
use App\Http\Requests\UpdateMaterialAssetsRequest;
use App\Repositories\MaterialAssetsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class MaterialAssetsController extends AppBaseController
{
    /** @var  MaterialAssetsRepository */
    private $materialAssetsRepository;

    public function __construct(MaterialAssetsRepository $materialAssetsRepo)
    {
        $this->middleware('auth');
        $this->materialAssetsRepository = $materialAssetsRepo;
    }

    /**
     * Display a listing of the MaterialAssets.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $materialAssets = $this->materialAssetsRepository->all();

        return view('material_assets.index')
            ->with('materialAssets', $materialAssets);
    }

    /**
     * Show the form for creating a new MaterialAssets.
     *
     * @return Response
     */
    public function create()
    {
        return view('material_assets.create');
    }

    /**
     * Store a newly created MaterialAssets in storage.
     *
     * @param CreateMaterialAssetsRequest $request
     *
     * @return Response
     */
    public function store(CreateMaterialAssetsRequest $request)
    {
        $input = $request->all();

        $materialAssets = $this->materialAssetsRepository->create($input);

        Flash::success('Material Assets saved successfully.');

        return redirect(route('materialAssets.index'));
    }

    /**
     * Display the specified MaterialAssets.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $materialAssets = $this->materialAssetsRepository->find($id);

        if (empty($materialAssets)) {
            Flash::error('Material Assets not found');

            return redirect(route('materialAssets.index'));
        }

        return view('material_assets.show')->with('materialAssets', $materialAssets);
    }

    /**
     * Show the form for editing the specified MaterialAssets.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $materialAssets = $this->materialAssetsRepository->find($id);

        if (empty($materialAssets)) {
            Flash::error('Material Assets not found');

            return redirect(route('materialAssets.index'));
        }

        return view('material_assets.edit')->with('materialAssets', $materialAssets);
    }

    /**
     * Update the specified MaterialAssets in storage.
     *
     * @param int $id
     * @param UpdateMaterialAssetsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMaterialAssetsRequest $request)
    {
        $materialAssets = $this->materialAssetsRepository->find($id);

        if (empty($materialAssets)) {
            Flash::error('Material Assets not found');

            return redirect(route('materialAssets.index'));
        }

        $materialAssets = $this->materialAssetsRepository->update($request->all(), $id);

        Flash::success('Material Assets updated successfully.');

        return redirect(route('materialAssets.index'));
    }

    /**
     * Remove the specified MaterialAssets from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $materialAssets = $this->materialAssetsRepository->find($id);

        if (empty($materialAssets)) {
            Flash::error('Material Assets not found');

            return redirect(route('materialAssets.index'));
        }

        $this->materialAssetsRepository->delete($id);

        Flash::success('Material Assets deleted successfully.');

        return redirect(route('materialAssets.index'));
    }
}
