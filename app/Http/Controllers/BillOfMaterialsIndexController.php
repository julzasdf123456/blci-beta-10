<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillOfMaterialsIndexRequest;
use App\Http\Requests\UpdateBillOfMaterialsIndexRequest;
use App\Repositories\BillOfMaterialsIndexRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class BillOfMaterialsIndexController extends AppBaseController
{
    /** @var  BillOfMaterialsIndexRepository */
    private $billOfMaterialsIndexRepository;

    public function __construct(BillOfMaterialsIndexRepository $billOfMaterialsIndexRepo)
    {
        $this->middleware('auth');
        $this->billOfMaterialsIndexRepository = $billOfMaterialsIndexRepo;
    }

    /**
     * Display a listing of the BillOfMaterialsIndex.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $billOfMaterialsIndices = $this->billOfMaterialsIndexRepository->all();

        return view('bill_of_materials_indices.index')
            ->with('billOfMaterialsIndices', $billOfMaterialsIndices);
    }

    /**
     * Show the form for creating a new BillOfMaterialsIndex.
     *
     * @return Response
     */
    public function create()
    {
        return view('bill_of_materials_indices.create');
    }

    /**
     * Store a newly created BillOfMaterialsIndex in storage.
     *
     * @param CreateBillOfMaterialsIndexRequest $request
     *
     * @return Response
     */
    public function store(CreateBillOfMaterialsIndexRequest $request)
    {
        $input = $request->all();

        $billOfMaterialsIndex = $this->billOfMaterialsIndexRepository->create($input);

        Flash::success('Bill Of Materials Index saved successfully.');

        return redirect(route('billOfMaterialsIndices.index'));
    }

    /**
     * Display the specified BillOfMaterialsIndex.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $billOfMaterialsIndex = $this->billOfMaterialsIndexRepository->find($id);

        if (empty($billOfMaterialsIndex)) {
            Flash::error('Bill Of Materials Index not found');

            return redirect(route('billOfMaterialsIndices.index'));
        }

        return view('bill_of_materials_indices.show')->with('billOfMaterialsIndex', $billOfMaterialsIndex);
    }

    /**
     * Show the form for editing the specified BillOfMaterialsIndex.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $billOfMaterialsIndex = $this->billOfMaterialsIndexRepository->find($id);

        if (empty($billOfMaterialsIndex)) {
            Flash::error('Bill Of Materials Index not found');

            return redirect(route('billOfMaterialsIndices.index'));
        }

        return view('bill_of_materials_indices.edit')->with('billOfMaterialsIndex', $billOfMaterialsIndex);
    }

    /**
     * Update the specified BillOfMaterialsIndex in storage.
     *
     * @param int $id
     * @param UpdateBillOfMaterialsIndexRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillOfMaterialsIndexRequest $request)
    {
        $billOfMaterialsIndex = $this->billOfMaterialsIndexRepository->find($id);

        if (empty($billOfMaterialsIndex)) {
            Flash::error('Bill Of Materials Index not found');

            return redirect(route('billOfMaterialsIndices.index'));
        }

        $billOfMaterialsIndex = $this->billOfMaterialsIndexRepository->update($request->all(), $id);

        Flash::success('Bill Of Materials Index updated successfully.');

        return redirect(route('billOfMaterialsIndices.index'));
    }

    /**
     * Remove the specified BillOfMaterialsIndex from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $billOfMaterialsIndex = $this->billOfMaterialsIndexRepository->find($id);

        if (empty($billOfMaterialsIndex)) {
            Flash::error('Bill Of Materials Index not found');

            return redirect(route('billOfMaterialsIndices.index'));
        }

        $this->billOfMaterialsIndexRepository->delete($id);

        Flash::success('Bill Of Materials Index deleted successfully.');

        return redirect(route('billOfMaterialsIndices.index'));
    }
}
