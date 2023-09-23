<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBlocksRequest;
use App\Http\Requests\UpdateBlocksRequest;
use App\Repositories\BlocksRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class BlocksController extends AppBaseController
{
    /** @var  BlocksRepository */
    private $blocksRepository;

    public function __construct(BlocksRepository $blocksRepo)
    {
        $this->middleware('auth');
        $this->blocksRepository = $blocksRepo;
    }

    /**
     * Display a listing of the Blocks.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $blocks = $this->blocksRepository->all();

        return view('blocks.index')
            ->with('blocks', $blocks);
    }

    /**
     * Show the form for creating a new Blocks.
     *
     * @return Response
     */
    public function create()
    {
        return view('blocks.create');
    }

    /**
     * Store a newly created Blocks in storage.
     *
     * @param CreateBlocksRequest $request
     *
     * @return Response
     */
    public function store(CreateBlocksRequest $request)
    {
        $input = $request->all();

        $blocks = $this->blocksRepository->create($input);

        Flash::success('Blocks saved successfully.');

        return redirect(route('blocks.index'));
    }

    /**
     * Display the specified Blocks.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $blocks = $this->blocksRepository->find($id);

        if (empty($blocks)) {
            Flash::error('Blocks not found');

            return redirect(route('blocks.index'));
        }

        return view('blocks.show')->with('blocks', $blocks);
    }

    /**
     * Show the form for editing the specified Blocks.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $blocks = $this->blocksRepository->find($id);

        if (empty($blocks)) {
            Flash::error('Blocks not found');

            return redirect(route('blocks.index'));
        }

        return view('blocks.edit')->with('blocks', $blocks);
    }

    /**
     * Update the specified Blocks in storage.
     *
     * @param int $id
     * @param UpdateBlocksRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBlocksRequest $request)
    {
        $blocks = $this->blocksRepository->find($id);

        if (empty($blocks)) {
            Flash::error('Blocks not found');

            return redirect(route('blocks.index'));
        }

        $blocks = $this->blocksRepository->update($request->all(), $id);

        Flash::success('Blocks updated successfully.');

        return redirect(route('blocks.index'));
    }

    /**
     * Remove the specified Blocks from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $blocks = $this->blocksRepository->find($id);

        if (empty($blocks)) {
            Flash::error('Blocks not found');

            return redirect(route('blocks.index'));
        }

        $this->blocksRepository->delete($id);

        Flash::success('Blocks deleted successfully.');

        return redirect(route('blocks.index'));
    }
}
