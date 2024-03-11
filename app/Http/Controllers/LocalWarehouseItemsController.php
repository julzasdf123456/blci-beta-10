<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLocalWarehouseItemsRequest;
use App\Http\Requests\UpdateLocalWarehouseItemsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\LocalWarehouseItemsRepository;
use Illuminate\Http\Request;
use Flash;

class LocalWarehouseItemsController extends AppBaseController
{
    /** @var LocalWarehouseItemsRepository $localWarehouseItemsRepository*/
    private $localWarehouseItemsRepository;

    public function __construct(LocalWarehouseItemsRepository $localWarehouseItemsRepo)
    {
        $this->middleware('auth');
        $this->localWarehouseItemsRepository = $localWarehouseItemsRepo;
    }

    /**
     * Display a listing of the LocalWarehouseItems.
     */
    public function index(Request $request)
    {
        $localWarehouseItems = $this->localWarehouseItemsRepository->paginate(10);

        return view('local_warehouse_items.index')
            ->with('localWarehouseItems', $localWarehouseItems);
    }

    /**
     * Show the form for creating a new LocalWarehouseItems.
     */
    public function create()
    {
        return view('local_warehouse_items.create');
    }

    /**
     * Store a newly created LocalWarehouseItems in storage.
     */
    public function store(CreateLocalWarehouseItemsRequest $request)
    {
        $input = $request->all();

        $localWarehouseItems = $this->localWarehouseItemsRepository->create($input);

        Flash::success('Local Warehouse Items saved successfully.');

        return redirect(route('localWarehouseItems.index'));
    }

    /**
     * Display the specified LocalWarehouseItems.
     */
    public function show($id)
    {
        $localWarehouseItems = $this->localWarehouseItemsRepository->find($id);

        if (empty($localWarehouseItems)) {
            Flash::error('Local Warehouse Items not found');

            return redirect(route('localWarehouseItems.index'));
        }

        return view('local_warehouse_items.show')->with('localWarehouseItems', $localWarehouseItems);
    }

    /**
     * Show the form for editing the specified LocalWarehouseItems.
     */
    public function edit($id)
    {
        $localWarehouseItems = $this->localWarehouseItemsRepository->find($id);

        if (empty($localWarehouseItems)) {
            Flash::error('Local Warehouse Items not found');

            return redirect(route('localWarehouseItems.index'));
        }

        return view('local_warehouse_items.edit')->with('localWarehouseItems', $localWarehouseItems);
    }

    /**
     * Update the specified LocalWarehouseItems in storage.
     */
    public function update($id, UpdateLocalWarehouseItemsRequest $request)
    {
        $localWarehouseItems = $this->localWarehouseItemsRepository->find($id);

        if (empty($localWarehouseItems)) {
            Flash::error('Local Warehouse Items not found');

            return redirect(route('localWarehouseItems.index'));
        }

        $localWarehouseItems = $this->localWarehouseItemsRepository->update($request->all(), $id);

        Flash::success('Local Warehouse Items updated successfully.');

        return redirect(route('localWarehouseItems.index'));
    }

    /**
     * Remove the specified LocalWarehouseItems from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $localWarehouseItems = $this->localWarehouseItemsRepository->find($id);

        if (empty($localWarehouseItems)) {
            Flash::error('Local Warehouse Items not found');

            return redirect(route('localWarehouseItems.index'));
        }

        $this->localWarehouseItemsRepository->delete($id);

        Flash::success('Local Warehouse Items deleted successfully.');

        return redirect(route('localWarehouseItems.index'));
    }
}
