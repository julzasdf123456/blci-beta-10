<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateItemsCostRequest;
use App\Http\Requests\UpdateItemsCostRequest;
use App\Repositories\ItemsCostRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ItemsCostController extends AppBaseController
{
    /** @var  ItemsCostRepository */
    private $itemsCostRepository;

    public function __construct(ItemsCostRepository $itemsCostRepo)
    {
        $this->middleware('auth');
        $this->itemsCostRepository = $itemsCostRepo;
    }

    /**
     * Display a listing of the ItemsCost.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $itemsCosts = $this->itemsCostRepository->all();

        return view('items_costs.index')
            ->with('itemsCosts', $itemsCosts);
    }

    /**
     * Show the form for creating a new ItemsCost.
     *
     * @return Response
     */
    public function create()
    {
        return view('items_costs.create');
    }

    /**
     * Store a newly created ItemsCost in storage.
     *
     * @param CreateItemsCostRequest $request
     *
     * @return Response
     */
    public function store(CreateItemsCostRequest $request)
    {
        $input = $request->all();

        $itemsCost = $this->itemsCostRepository->create($input);

        Flash::success('Items Cost saved successfully.');

        return redirect(route('itemsCosts.index'));
    }

    /**
     * Display the specified ItemsCost.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $itemsCost = $this->itemsCostRepository->find($id);

        if (empty($itemsCost)) {
            Flash::error('Items Cost not found');

            return redirect(route('itemsCosts.index'));
        }

        return view('items_costs.show')->with('itemsCost', $itemsCost);
    }

    /**
     * Show the form for editing the specified ItemsCost.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $itemsCost = $this->itemsCostRepository->find($id);

        if (empty($itemsCost)) {
            Flash::error('Items Cost not found');

            return redirect(route('itemsCosts.index'));
        }

        return view('items_costs.edit')->with('itemsCost', $itemsCost);
    }

    /**
     * Update the specified ItemsCost in storage.
     *
     * @param int $id
     * @param UpdateItemsCostRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateItemsCostRequest $request)
    {
        $itemsCost = $this->itemsCostRepository->find($id);

        if (empty($itemsCost)) {
            Flash::error('Items Cost not found');

            return redirect(route('itemsCosts.index'));
        }

        $itemsCost = $this->itemsCostRepository->update($request->all(), $id);

        Flash::success('Items Cost updated successfully.');

        return redirect(route('itemsCosts.index'));
    }

    /**
     * Remove the specified ItemsCost from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $itemsCost = $this->itemsCostRepository->find($id);

        if (empty($itemsCost)) {
            Flash::error('Items Cost not found');

            return redirect(route('itemsCosts.index'));
        }

        $this->itemsCostRepository->delete($id);

        Flash::success('Items Cost deleted successfully.');

        return redirect(route('itemsCosts.index'));
    }
}
