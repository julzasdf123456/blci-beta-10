<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDCRIndexRequest;
use App\Http\Requests\UpdateDCRIndexRequest;
use App\Repositories\DCRIndexRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class DCRIndexController extends AppBaseController
{
    /** @var  DCRIndexRepository */
    private $dCRIndexRepository;

    public function __construct(DCRIndexRepository $dCRIndexRepo)
    {
        $this->middleware('auth');
        $this->dCRIndexRepository = $dCRIndexRepo;
    }

    /**
     * Display a listing of the DCRIndex.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $dCRIndices = $this->dCRIndexRepository->all();

        return view('d_c_r_indices.index')
            ->with('dCRIndices', $dCRIndices);
    }

    /**
     * Show the form for creating a new DCRIndex.
     *
     * @return Response
     */
    public function create()
    {
        return view('d_c_r_indices.create');
    }

    /**
     * Store a newly created DCRIndex in storage.
     *
     * @param CreateDCRIndexRequest $request
     *
     * @return Response
     */
    public function store(CreateDCRIndexRequest $request)
    {
        $input = $request->all();

        $dCRIndex = $this->dCRIndexRepository->create($input);

        Flash::success('D C R Index saved successfully.');

        return redirect(route('dCRIndices.index'));
    }

    /**
     * Display the specified DCRIndex.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $dCRIndex = $this->dCRIndexRepository->find($id);

        if (empty($dCRIndex)) {
            Flash::error('D C R Index not found');

            return redirect(route('dCRIndices.index'));
        }

        return view('d_c_r_indices.show')->with('dCRIndex', $dCRIndex);
    }

    /**
     * Show the form for editing the specified DCRIndex.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $dCRIndex = $this->dCRIndexRepository->find($id);

        if (empty($dCRIndex)) {
            Flash::error('D C R Index not found');

            return redirect(route('dCRIndices.index'));
        }

        return view('d_c_r_indices.edit')->with('dCRIndex', $dCRIndex);
    }

    /**
     * Update the specified DCRIndex in storage.
     *
     * @param int $id
     * @param UpdateDCRIndexRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDCRIndexRequest $request)
    {
        $dCRIndex = $this->dCRIndexRepository->find($id);

        if (empty($dCRIndex)) {
            Flash::error('D C R Index not found');

            return redirect(route('dCRIndices.index'));
        }

        $dCRIndex = $this->dCRIndexRepository->update($request->all(), $id);

        Flash::success('D C R Index updated successfully.');

        return redirect(route('dCRIndices.index'));
    }

    /**
     * Remove the specified DCRIndex from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $dCRIndex = $this->dCRIndexRepository->find($id);

        if (empty($dCRIndex)) {
            Flash::error('D C R Index not found');

            return redirect(route('dCRIndices.index'));
        }

        $this->dCRIndexRepository->delete($id);

        Flash::success('D C R Index deleted successfully.');

        return redirect(route('dCRIndices.index'));
    }
}
