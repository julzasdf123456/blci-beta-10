<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePreDefinedMaterialsRequest;
use App\Http\Requests\UpdatePreDefinedMaterialsRequest;
use App\Repositories\PreDefinedMaterialsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class PreDefinedMaterialsController extends AppBaseController
{
    /** @var  PreDefinedMaterialsRepository */
    private $preDefinedMaterialsRepository;

    public function __construct(PreDefinedMaterialsRepository $preDefinedMaterialsRepo)
    {
        $this->middleware('auth');
        $this->preDefinedMaterialsRepository = $preDefinedMaterialsRepo;
    }

    /**
     * Display a listing of the PreDefinedMaterials.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $preDefinedMaterials = $this->preDefinedMaterialsRepository->all();

        return view('pre_defined_materials.index')
            ->with('preDefinedMaterials', $preDefinedMaterials);
    }

    /**
     * Show the form for creating a new PreDefinedMaterials.
     *
     * @return Response
     */
    public function create()
    {
        return view('pre_defined_materials.create');
    }

    /**
     * Store a newly created PreDefinedMaterials in storage.
     *
     * @param CreatePreDefinedMaterialsRequest $request
     *
     * @return Response
     */
    public function store(CreatePreDefinedMaterialsRequest $request)
    {
        $input = $request->all();

        $preDefinedMaterials = $this->preDefinedMaterialsRepository->create($input);

        Flash::success('Pre Defined Materials saved successfully.');

        return redirect(route('preDefinedMaterials.index'));
    }

    /**
     * Display the specified PreDefinedMaterials.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $preDefinedMaterials = $this->preDefinedMaterialsRepository->find($id);

        if (empty($preDefinedMaterials)) {
            Flash::error('Pre Defined Materials not found');

            return redirect(route('preDefinedMaterials.index'));
        }

        return view('pre_defined_materials.show')->with('preDefinedMaterials', $preDefinedMaterials);
    }

    /**
     * Show the form for editing the specified PreDefinedMaterials.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $preDefinedMaterials = $this->preDefinedMaterialsRepository->find($id);

        if (empty($preDefinedMaterials)) {
            Flash::error('Pre Defined Materials not found');

            return redirect(route('preDefinedMaterials.index'));
        }

        return view('pre_defined_materials.edit')->with('preDefinedMaterials', $preDefinedMaterials);
    }

    /**
     * Update the specified PreDefinedMaterials in storage.
     *
     * @param int $id
     * @param UpdatePreDefinedMaterialsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePreDefinedMaterialsRequest $request)
    {
        $preDefinedMaterials = $this->preDefinedMaterialsRepository->find($id);

        if (empty($preDefinedMaterials)) {
            Flash::error('Pre Defined Materials not found');

            return redirect(route('preDefinedMaterials.index'));
        }

        $preDefinedMaterials = $this->preDefinedMaterialsRepository->update($request->all(), $id);

        Flash::success('Pre Defined Materials updated successfully.');

        return redirect(route('preDefinedMaterials.index'));
    }

    /**
     * Remove the specified PreDefinedMaterials from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $preDefinedMaterials = $this->preDefinedMaterialsRepository->find($id);

        if (empty($preDefinedMaterials)) {
            Flash::error('Pre Defined Materials not found');

            return redirect(route('preDefinedMaterials.index'));
        }

        $this->preDefinedMaterialsRepository->delete($id);

        Flash::success('Pre Defined Materials deleted successfully.');

        return redirect(route('preDefinedMaterials.index'));
    }
}
