<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMaterialPresetsRequest;
use App\Http\Requests\UpdateMaterialPresetsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\MaterialPresetsRepository;
use Illuminate\Http\Request;
use Flash;

class MaterialPresetsController extends AppBaseController
{
    /** @var MaterialPresetsRepository $materialPresetsRepository*/
    private $materialPresetsRepository;

    public function __construct(MaterialPresetsRepository $materialPresetsRepo)
    {
        $this->middleware('auth');
        $this->materialPresetsRepository = $materialPresetsRepo;
    }

    /**
     * Display a listing of the MaterialPresets.
     */
    public function index(Request $request)
    {
        $materialPresets = $this->materialPresetsRepository->paginate(10);

        return view('material_presets.index')
            ->with('materialPresets', $materialPresets);
    }

    /**
     * Show the form for creating a new MaterialPresets.
     */
    public function create()
    {
        return view('material_presets.create');
    }

    /**
     * Store a newly created MaterialPresets in storage.
     */
    public function store(CreateMaterialPresetsRequest $request)
    {
        $input = $request->all();

        $materialPresets = $this->materialPresetsRepository->create($input);

        Flash::success('Material Presets saved successfully.');

        return redirect(route('materialPresets.index'));
    }

    /**
     * Display the specified MaterialPresets.
     */
    public function show($id)
    {
        $materialPresets = $this->materialPresetsRepository->find($id);

        if (empty($materialPresets)) {
            Flash::error('Material Presets not found');

            return redirect(route('materialPresets.index'));
        }

        return view('material_presets.show')->with('materialPresets', $materialPresets);
    }

    /**
     * Show the form for editing the specified MaterialPresets.
     */
    public function edit($id)
    {
        $materialPresets = $this->materialPresetsRepository->find($id);

        if (empty($materialPresets)) {
            Flash::error('Material Presets not found');

            return redirect(route('materialPresets.index'));
        }

        return view('material_presets.edit')->with('materialPresets', $materialPresets);
    }

    /**
     * Update the specified MaterialPresets in storage.
     */
    public function update($id, UpdateMaterialPresetsRequest $request)
    {
        $materialPresets = $this->materialPresetsRepository->find($id);

        if (empty($materialPresets)) {
            Flash::error('Material Presets not found');

            return redirect(route('materialPresets.index'));
        }

        $materialPresets = $this->materialPresetsRepository->update($request->all(), $id);

        Flash::success('Material Presets updated successfully.');

        return redirect(route('materialPresets.index'));
    }

    /**
     * Remove the specified MaterialPresets from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $materialPresets = $this->materialPresetsRepository->find($id);

        if (empty($materialPresets)) {
            Flash::error('Material Presets not found');

            return redirect(route('materialPresets.index'));
        }

        $this->materialPresetsRepository->delete($id);

        Flash::success('Material Presets deleted successfully.');

        return redirect(route('materialPresets.index'));
    }
}
