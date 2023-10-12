<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCollectionDateAdjustmentsRequest;
use App\Http\Requests\UpdateCollectionDateAdjustmentsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\CollectionDateAdjustmentsRepository;
use App\Models\User;
use App\Models\IDGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Flash;

class CollectionDateAdjustmentsController extends AppBaseController
{
    /** @var CollectionDateAdjustmentsRepository $collectionDateAdjustmentsRepository*/
    private $collectionDateAdjustmentsRepository;

    public function __construct(CollectionDateAdjustmentsRepository $collectionDateAdjustmentsRepo)
    {
        $this->middleware('auth');
        $this->collectionDateAdjustmentsRepository = $collectionDateAdjustmentsRepo;
    }

    /**
     * Display a listing of the CollectionDateAdjustments.
     */
    public function index(Request $request)
    {
        $tellers = User::role('Teller')->orderBy('name')->get();

        return view('collection_date_adjustments.index', [
            'tellers' => $tellers,
        ]);
    }

    /**
     * Show the form for creating a new CollectionDateAdjustments.
     */
    public function create()
    {
        return view('collection_date_adjustments.create');
    }

    /**
     * Store a newly created CollectionDateAdjustments in storage.
     */
    public function store(CreateCollectionDateAdjustmentsRequest $request)
    {
        $input = $request->all();
        $input['id'] = IDGenerator::generateID();
        $input['AssignedBy'] = Auth::id();

        $collectionDateAdjustments = $this->collectionDateAdjustmentsRepository->create($input);

        // Flash::success('Collection Date Adjustments saved successfully.');

        // return redirect(route('collectionDateAdjustments.index'));
        return response()->json($collectionDateAdjustments, 200);
    }

    /**
     * Display the specified CollectionDateAdjustments.
     */
    public function show($id)
    {
        $collectionDateAdjustments = $this->collectionDateAdjustmentsRepository->find($id);

        if (empty($collectionDateAdjustments)) {
            Flash::error('Collection Date Adjustments not found');

            return redirect(route('collectionDateAdjustments.index'));
        }

        return view('collection_date_adjustments.show')->with('collectionDateAdjustments', $collectionDateAdjustments);
    }

    /**
     * Show the form for editing the specified CollectionDateAdjustments.
     */
    public function edit($id)
    {
        $collectionDateAdjustments = $this->collectionDateAdjustmentsRepository->find($id);

        if (empty($collectionDateAdjustments)) {
            Flash::error('Collection Date Adjustments not found');

            return redirect(route('collectionDateAdjustments.index'));
        }

        return view('collection_date_adjustments.edit')->with('collectionDateAdjustments', $collectionDateAdjustments);
    }

    /**
     * Update the specified CollectionDateAdjustments in storage.
     */
    public function update($id, UpdateCollectionDateAdjustmentsRequest $request)
    {
        $collectionDateAdjustments = $this->collectionDateAdjustmentsRepository->find($id);

        if (empty($collectionDateAdjustments)) {
            Flash::error('Collection Date Adjustments not found');

            return redirect(route('collectionDateAdjustments.index'));
        }

        $collectionDateAdjustments = $this->collectionDateAdjustmentsRepository->update($request->all(), $id);

        Flash::success('Collection Date Adjustments updated successfully.');

        return redirect(route('collectionDateAdjustments.index'));
    }

    /**
     * Remove the specified CollectionDateAdjustments from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $collectionDateAdjustments = $this->collectionDateAdjustmentsRepository->find($id);

        if (empty($collectionDateAdjustments)) {
            Flash::error('Collection Date Adjustments not found');

            return redirect(route('collectionDateAdjustments.index'));
        }

        $this->collectionDateAdjustmentsRepository->delete($id);

        Flash::success('Collection Date Adjustments deleted successfully.');

        return redirect(route('collectionDateAdjustments.index'));
    }
}
