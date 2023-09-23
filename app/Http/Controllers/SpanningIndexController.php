<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSpanningIndexRequest;
use App\Http\Requests\UpdateSpanningIndexRequest;
use App\Repositories\SpanningIndexRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class SpanningIndexController extends AppBaseController
{
    /** @var  SpanningIndexRepository */
    private $spanningIndexRepository;

    public function __construct(SpanningIndexRepository $spanningIndexRepo)
    {
        $this->middleware('auth');
        $this->spanningIndexRepository = $spanningIndexRepo;
    }

    /**
     * Display a listing of the SpanningIndex.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $spanningIndices = $this->spanningIndexRepository->all();

        return view('spanning_indices.index')
            ->with('spanningIndices', $spanningIndices);
    }

    /**
     * Show the form for creating a new SpanningIndex.
     *
     * @return Response
     */
    public function create()
    {
        return view('spanning_indices.create');
    }

    /**
     * Store a newly created SpanningIndex in storage.
     *
     * @param CreateSpanningIndexRequest $request
     *
     * @return Response
     */
    public function store(CreateSpanningIndexRequest $request)
    {
        $input = $request->all();

        $spanningIndex = $this->spanningIndexRepository->create($input);

        Flash::success('Spanning Index saved successfully.');

        return redirect(route('spanningIndices.index'));
    }

    /**
     * Display the specified SpanningIndex.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $spanningIndex = $this->spanningIndexRepository->find($id);

        if (empty($spanningIndex)) {
            Flash::error('Spanning Index not found');

            return redirect(route('spanningIndices.index'));
        }

        return view('spanning_indices.show')->with('spanningIndex', $spanningIndex);
    }

    /**
     * Show the form for editing the specified SpanningIndex.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $spanningIndex = $this->spanningIndexRepository->find($id);

        if (empty($spanningIndex)) {
            Flash::error('Spanning Index not found');

            return redirect(route('spanningIndices.index'));
        }

        return view('spanning_indices.edit')->with('spanningIndex', $spanningIndex);
    }

    /**
     * Update the specified SpanningIndex in storage.
     *
     * @param int $id
     * @param UpdateSpanningIndexRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSpanningIndexRequest $request)
    {
        $spanningIndex = $this->spanningIndexRepository->find($id);

        if (empty($spanningIndex)) {
            Flash::error('Spanning Index not found');

            return redirect(route('spanningIndices.index'));
        }

        $spanningIndex = $this->spanningIndexRepository->update($request->all(), $id);

        Flash::success('Spanning Index updated successfully.');

        return redirect(route('spanningIndices.index'));
    }

    /**
     * Remove the specified SpanningIndex from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $spanningIndex = $this->spanningIndexRepository->find($id);

        if (empty($spanningIndex)) {
            Flash::error('Spanning Index not found');

            return redirect(route('spanningIndices.index'));
        }

        $this->spanningIndexRepository->delete($id);

        Flash::success('Spanning Index deleted successfully.');

        return redirect(route('spanningIndices.index'));
    }
}
