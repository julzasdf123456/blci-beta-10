<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRateItemsRequest;
use App\Http\Requests\UpdateRateItemsRequest;
use App\Repositories\RateItemsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class RateItemsController extends AppBaseController
{
    /** @var  RateItemsRepository */
    private $rateItemsRepository;

    public function __construct(RateItemsRepository $rateItemsRepo)
    {
        $this->middleware('auth');
        $this->rateItemsRepository = $rateItemsRepo;
    }

    /**
     * Display a listing of the RateItems.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $rateItems = $this->rateItemsRepository->all();

        return view('rate_items.index')
            ->with('rateItems', $rateItems);
    }

    /**
     * Show the form for creating a new RateItems.
     *
     * @return Response
     */
    public function create()
    {
        return view('rate_items.create');
    }

    /**
     * Store a newly created RateItems in storage.
     *
     * @param CreateRateItemsRequest $request
     *
     * @return Response
     */
    public function store(CreateRateItemsRequest $request)
    {
        $input = $request->all();

        $rateItems = $this->rateItemsRepository->create($input);

        Flash::success('Rate Items saved successfully.');

        return redirect(route('rateItems.index'));
    }

    /**
     * Display the specified RateItems.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $rateItems = $this->rateItemsRepository->find($id);

        if (empty($rateItems)) {
            Flash::error('Rate Items not found');

            return redirect(route('rateItems.index'));
        }

        return view('rate_items.show')->with('rateItems', $rateItems);
    }

    /**
     * Show the form for editing the specified RateItems.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $rateItems = $this->rateItemsRepository->find($id);

        if (empty($rateItems)) {
            Flash::error('Rate Items not found');

            return redirect(route('rateItems.index'));
        }

        return view('rate_items.edit')->with('rateItems', $rateItems);
    }

    /**
     * Update the specified RateItems in storage.
     *
     * @param int $id
     * @param UpdateRateItemsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRateItemsRequest $request)
    {
        $rateItems = $this->rateItemsRepository->find($id);

        if (empty($rateItems)) {
            Flash::error('Rate Items not found');

            return redirect(route('rateItems.index'));
        }

        $rateItems = $this->rateItemsRepository->update($request->all(), $id);

        Flash::success('Rate Items updated successfully.');

        return redirect(route('rateItems.index'));
    }

    /**
     * Remove the specified RateItems from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $rateItems = $this->rateItemsRepository->find($id);

        if (empty($rateItems)) {
            Flash::error('Rate Items not found');

            return redirect(route('rateItems.index'));
        }

        $this->rateItemsRepository->delete($id);

        Flash::success('Rate Items deleted successfully.');

        return redirect(route('rateItems.index'));
    }
}
