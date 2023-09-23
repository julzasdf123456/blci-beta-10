<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePoleIndexRequest;
use App\Http\Requests\UpdatePoleIndexRequest;
use App\Repositories\PoleIndexRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class PoleIndexController extends AppBaseController
{
    /** @var  PoleIndexRepository */
    private $poleIndexRepository;

    public function __construct(PoleIndexRepository $poleIndexRepo)
    {
        $this->middleware('auth');
        $this->poleIndexRepository = $poleIndexRepo;
    }

    /**
     * Display a listing of the PoleIndex.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $poleIndices = $this->poleIndexRepository->all();

        return view('pole_indices.index')
            ->with('poleIndices', $poleIndices);
    }

    /**
     * Show the form for creating a new PoleIndex.
     *
     * @return Response
     */
    public function create()
    {
        return view('pole_indices.create');
    }

    /**
     * Store a newly created PoleIndex in storage.
     *
     * @param CreatePoleIndexRequest $request
     *
     * @return Response
     */
    public function store(CreatePoleIndexRequest $request)
    {
        $input = $request->all();

        $poleIndex = $this->poleIndexRepository->create($input);

        Flash::success('Pole Index saved successfully.');

        return redirect(route('poleIndices.index'));
    }

    /**
     * Display the specified PoleIndex.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $poleIndex = $this->poleIndexRepository->find($id);

        if (empty($poleIndex)) {
            Flash::error('Pole Index not found');

            return redirect(route('poleIndices.index'));
        }

        return view('pole_indices.show')->with('poleIndex', $poleIndex);
    }

    /**
     * Show the form for editing the specified PoleIndex.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $poleIndex = $this->poleIndexRepository->find($id);

        if (empty($poleIndex)) {
            Flash::error('Pole Index not found');

            return redirect(route('poleIndices.index'));
        }

        return view('pole_indices.edit')->with('poleIndex', $poleIndex);
    }

    /**
     * Update the specified PoleIndex in storage.
     *
     * @param int $id
     * @param UpdatePoleIndexRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePoleIndexRequest $request)
    {
        $poleIndex = $this->poleIndexRepository->find($id);

        if (empty($poleIndex)) {
            Flash::error('Pole Index not found');

            return redirect(route('poleIndices.index'));
        }

        $poleIndex = $this->poleIndexRepository->update($request->all(), $id);

        Flash::success('Pole Index updated successfully.');

        return redirect(route('poleIndices.index'));
    }

    /**
     * Remove the specified PoleIndex from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $poleIndex = $this->poleIndexRepository->find($id);

        if (empty($poleIndex)) {
            Flash::error('Pole Index not found');

            return redirect(route('poleIndices.index'));
        }

        $this->poleIndexRepository->delete($id);

        Flash::success('Pole Index deleted successfully.');

        return redirect(route('poleIndices.index'));
    }
}
