<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBoholWaterCollectionRequest;
use App\Http\Requests\UpdateBoholWaterCollectionRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BoholWaterCollectionRepository;
use Illuminate\Http\Request;
use Flash;

class BoholWaterCollectionController extends AppBaseController
{
    /** @var BoholWaterCollectionRepository $boholWaterCollectionRepository*/
    private $boholWaterCollectionRepository;

    public function __construct(BoholWaterCollectionRepository $boholWaterCollectionRepo)
    {
        $this->boholWaterCollectionRepository = $boholWaterCollectionRepo;
    }

    /**
     * Display a listing of the BoholWaterCollection.
     */
    public function index(Request $request)
    {
        $boholWaterCollections = $this->boholWaterCollectionRepository->paginate(10);

        return view('bohol_water_collections.index')
            ->with('boholWaterCollections', $boholWaterCollections);
    }

    /**
     * Show the form for creating a new BoholWaterCollection.
     */
    public function create()
    {
        return view('bohol_water_collections.create');
    }

    /**
     * Store a newly created BoholWaterCollection in storage.
     */
    public function store(CreateBoholWaterCollectionRequest $request)
    {
        $input = $request->all();

        $boholWaterCollection = $this->boholWaterCollectionRepository->create($input);

        Flash::success('Bohol Water Collection saved successfully.');

        return redirect(route('boholWaterCollections.index'));
    }

    /**
     * Display the specified BoholWaterCollection.
     */
    public function show($id)
    {
        $boholWaterCollection = $this->boholWaterCollectionRepository->find($id);

        if (empty($boholWaterCollection)) {
            Flash::error('Bohol Water Collection not found');

            return redirect(route('boholWaterCollections.index'));
        }

        return view('bohol_water_collections.show')->with('boholWaterCollection', $boholWaterCollection);
    }

    /**
     * Show the form for editing the specified BoholWaterCollection.
     */
    public function edit($id)
    {
        $boholWaterCollection = $this->boholWaterCollectionRepository->find($id);

        if (empty($boholWaterCollection)) {
            Flash::error('Bohol Water Collection not found');

            return redirect(route('boholWaterCollections.index'));
        }

        return view('bohol_water_collections.edit')->with('boholWaterCollection', $boholWaterCollection);
    }

    /**
     * Update the specified BoholWaterCollection in storage.
     */
    public function update($id, UpdateBoholWaterCollectionRequest $request)
    {
        $boholWaterCollection = $this->boholWaterCollectionRepository->find($id);

        if (empty($boholWaterCollection)) {
            Flash::error('Bohol Water Collection not found');

            return redirect(route('boholWaterCollections.index'));
        }

        $boholWaterCollection = $this->boholWaterCollectionRepository->update($request->all(), $id);

        Flash::success('Bohol Water Collection updated successfully.');

        return redirect(route('boholWaterCollections.index'));
    }

    /**
     * Remove the specified BoholWaterCollection from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $boholWaterCollection = $this->boholWaterCollectionRepository->find($id);

        if (empty($boholWaterCollection)) {
            Flash::error('Bohol Water Collection not found');

            return redirect(route('boholWaterCollections.index'));
        }

        $this->boholWaterCollectionRepository->delete($id);

        Flash::success('Bohol Water Collection deleted successfully.');

        return redirect(route('boholWaterCollections.index'));
    }
}
