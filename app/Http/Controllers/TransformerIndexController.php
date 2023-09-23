<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransformerIndexRequest;
use App\Http\Requests\UpdateTransformerIndexRequest;
use App\Repositories\TransformerIndexRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\TransformerIndex;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class TransformerIndexController extends AppBaseController
{
    /** @var  TransformerIndexRepository */
    private $transformerIndexRepository;

    public function __construct(TransformerIndexRepository $transformerIndexRepo)
    {
        $this->middleware('auth');
        $this->transformerIndexRepository = $transformerIndexRepo;
    }

    /**
     * Display a listing of the TransformerIndex.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $transformerIndices = DB::table('CRM_TransformerIndex')
            ->leftJoin('CRM_MaterialAssets', 'CRM_TransformerIndex.NEACode', '=', 'CRM_MaterialAssets.id')
            ->select('CRM_TransformerIndex.id',
                    'CRM_TransformerIndex.NEACode',
                    'CRM_MaterialAssets.Description',
                    'CRM_TransformerIndex.LinkFuseCode')
            ->get();

        return view('transformer_indices.index')
            ->with('transformerIndices', $transformerIndices);
    }

    /**
     * Show the form for creating a new TransformerIndex.
     *
     * @return Response
     */
    public function create()
    {
        return view('transformer_indices.create');
    }

    /**
     * Store a newly created TransformerIndex in storage.
     *
     * @param CreateTransformerIndexRequest $request
     *
     * @return Response
     */
    public function store(CreateTransformerIndexRequest $request)
    {
        $input = $request->all();

        $transformerIndex = $this->transformerIndexRepository->create($input);

        Flash::success('Transformer Index saved successfully.');

        return redirect(route('transformerIndices.index'));
    }

    /**
     * Display the specified TransformerIndex.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $transformerIndex = $this->transformerIndexRepository->find($id);

        if (empty($transformerIndex)) {
            Flash::error('Transformer Index not found');

            return redirect(route('transformerIndices.index'));
        }

        return view('transformer_indices.show')->with('transformerIndex', $transformerIndex);
    }

    /**
     * Show the form for editing the specified TransformerIndex.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $transformerIndex = $this->transformerIndexRepository->find($id);

        if (empty($transformerIndex)) {
            Flash::error('Transformer Index not found');

            return redirect(route('transformerIndices.index'));
        }

        return view('transformer_indices.edit')->with('transformerIndex', $transformerIndex);
    }

    /**
     * Update the specified TransformerIndex in storage.
     *
     * @param int $id
     * @param UpdateTransformerIndexRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTransformerIndexRequest $request)
    {
        $transformerIndex = $this->transformerIndexRepository->find($id);

        if (empty($transformerIndex)) {
            Flash::error('Transformer Index not found');

            return redirect(route('transformerIndices.index'));
        }

        $transformerIndex = $this->transformerIndexRepository->update($request->all(), $id);

        Flash::success('Transformer Index updated successfully.');

        return redirect(route('transformerIndices.index'));
    }

    /**
     * Remove the specified TransformerIndex from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $transformerIndex = $this->transformerIndexRepository->find($id);

        if (empty($transformerIndex)) {
            Flash::error('Transformer Index not found');

            return redirect(route('transformerIndices.index'));
        }

        $this->transformerIndexRepository->delete($id);

        Flash::success('Transformer Index deleted successfully.');

        return redirect(route('transformerIndices.index'));
    }
}
