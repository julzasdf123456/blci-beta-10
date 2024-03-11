<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLocalWarehouseHeadRequest;
use App\Http\Requests\UpdateLocalWarehouseHeadRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\LocalWarehouseHeadRepository;
use Illuminate\Http\Request;
use Flash;

class LocalWarehouseHeadController extends AppBaseController
{
    /** @var LocalWarehouseHeadRepository $localWarehouseHeadRepository*/
    private $localWarehouseHeadRepository;

    public function __construct(LocalWarehouseHeadRepository $localWarehouseHeadRepo)
    {
        $this->middleware('auth');
        $this->localWarehouseHeadRepository = $localWarehouseHeadRepo;
    }

    /**
     * Display a listing of the LocalWarehouseHead.
     */
    public function index(Request $request)
    {
        $localWarehouseHeads = $this->localWarehouseHeadRepository->paginate(10);

        return view('local_warehouse_heads.index')
            ->with('localWarehouseHeads', $localWarehouseHeads);
    }

    /**
     * Show the form for creating a new LocalWarehouseHead.
     */
    public function create()
    {
        return view('local_warehouse_heads.create');
    }

    /**
     * Store a newly created LocalWarehouseHead in storage.
     */
    public function store(CreateLocalWarehouseHeadRequest $request)
    {
        $input = $request->all();

        $localWarehouseHead = $this->localWarehouseHeadRepository->create($input);

        Flash::success('Local Warehouse Head saved successfully.');

        return redirect(route('localWarehouseHeads.index'));
    }

    /**
     * Display the specified LocalWarehouseHead.
     */
    public function show($id)
    {
        $localWarehouseHead = $this->localWarehouseHeadRepository->find($id);

        if (empty($localWarehouseHead)) {
            Flash::error('Local Warehouse Head not found');

            return redirect(route('localWarehouseHeads.index'));
        }

        return view('local_warehouse_heads.show')->with('localWarehouseHead', $localWarehouseHead);
    }

    /**
     * Show the form for editing the specified LocalWarehouseHead.
     */
    public function edit($id)
    {
        $localWarehouseHead = $this->localWarehouseHeadRepository->find($id);

        if (empty($localWarehouseHead)) {
            Flash::error('Local Warehouse Head not found');

            return redirect(route('localWarehouseHeads.index'));
        }

        return view('local_warehouse_heads.edit')->with('localWarehouseHead', $localWarehouseHead);
    }

    /**
     * Update the specified LocalWarehouseHead in storage.
     */
    public function update($id, UpdateLocalWarehouseHeadRequest $request)
    {
        $localWarehouseHead = $this->localWarehouseHeadRepository->find($id);

        if (empty($localWarehouseHead)) {
            Flash::error('Local Warehouse Head not found');

            return redirect(route('localWarehouseHeads.index'));
        }

        $localWarehouseHead = $this->localWarehouseHeadRepository->update($request->all(), $id);

        Flash::success('Local Warehouse Head updated successfully.');

        return redirect(route('localWarehouseHeads.index'));
    }

    /**
     * Remove the specified LocalWarehouseHead from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $localWarehouseHead = $this->localWarehouseHeadRepository->find($id);

        if (empty($localWarehouseHead)) {
            Flash::error('Local Warehouse Head not found');

            return redirect(route('localWarehouseHeads.index'));
        }

        $this->localWarehouseHeadRepository->delete($id);

        Flash::success('Local Warehouse Head deleted successfully.');

        return redirect(route('localWarehouseHeads.index'));
    }
}
