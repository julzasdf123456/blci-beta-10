<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWarehouseHeadRequest;
use App\Http\Requests\UpdateWarehouseHeadRequest;
use App\Repositories\WarehouseHeadRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class WarehouseHeadController extends AppBaseController
{
    /** @var  WarehouseHeadRepository */
    private $warehouseHeadRepository;

    public function __construct(WarehouseHeadRepository $warehouseHeadRepo)
    {
        $this->middleware('auth');
        $this->warehouseHeadRepository = $warehouseHeadRepo;
    }

    /**
     * Display a listing of the WarehouseHead.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $warehouseHeads = $this->warehouseHeadRepository->all();

        return view('warehouse_heads.index')
            ->with('warehouseHeads', $warehouseHeads);
    }

    /**
     * Show the form for creating a new WarehouseHead.
     *
     * @return Response
     */
    public function create()
    {
        return view('warehouse_heads.create');
    }

    /**
     * Store a newly created WarehouseHead in storage.
     *
     * @param CreateWarehouseHeadRequest $request
     *
     * @return Response
     */
    public function store(CreateWarehouseHeadRequest $request)
    {
        $input = $request->all();

        $warehouseHead = $this->warehouseHeadRepository->create($input);

        Flash::success('Warehouse Head saved successfully.');

        return redirect(route('warehouseHeads.index'));
    }

    /**
     * Display the specified WarehouseHead.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $warehouseHead = $this->warehouseHeadRepository->find($id);

        if (empty($warehouseHead)) {
            Flash::error('Warehouse Head not found');

            return redirect(route('warehouseHeads.index'));
        }

        return view('warehouse_heads.show')->with('warehouseHead', $warehouseHead);
    }

    /**
     * Show the form for editing the specified WarehouseHead.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $warehouseHead = $this->warehouseHeadRepository->find($id);

        if (empty($warehouseHead)) {
            Flash::error('Warehouse Head not found');

            return redirect(route('warehouseHeads.index'));
        }

        return view('warehouse_heads.edit')->with('warehouseHead', $warehouseHead);
    }

    /**
     * Update the specified WarehouseHead in storage.
     *
     * @param int $id
     * @param UpdateWarehouseHeadRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWarehouseHeadRequest $request)
    {
        $warehouseHead = $this->warehouseHeadRepository->find($id);

        if (empty($warehouseHead)) {
            Flash::error('Warehouse Head not found');

            return redirect(route('warehouseHeads.index'));
        }

        $warehouseHead = $this->warehouseHeadRepository->update($request->all(), $id);

        Flash::success('Warehouse Head updated successfully.');

        return redirect(route('warehouseHeads.index'));
    }

    /**
     * Remove the specified WarehouseHead from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $warehouseHead = $this->warehouseHeadRepository->find($id);

        if (empty($warehouseHead)) {
            Flash::error('Warehouse Head not found');

            return redirect(route('warehouseHeads.index'));
        }

        $this->warehouseHeadRepository->delete($id);

        Flash::success('Warehouse Head deleted successfully.');

        return redirect(route('warehouseHeads.index'));
    }
}
