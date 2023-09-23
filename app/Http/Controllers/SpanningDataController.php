<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSpanningDataRequest;
use App\Http\Requests\UpdateSpanningDataRequest;
use App\Repositories\SpanningDataRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\BillOfMaterialsMatrix;
use App\Models\StructureAssignments;
use Flash;
use Response;

class SpanningDataController extends AppBaseController
{
    /** @var  SpanningDataRepository */
    private $spanningDataRepository;

    public function __construct(SpanningDataRepository $spanningDataRepo)
    {
        $this->middleware('auth');
        $this->spanningDataRepository = $spanningDataRepo;
    }

    /**
     * Display a listing of the SpanningData.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $spanningDatas = $this->spanningDataRepository->all();

        return view('spanning_datas.index')
            ->with('spanningDatas', $spanningDatas);
    }

    /**
     * Show the form for creating a new SpanningData.
     *
     * @return Response
     */
    public function create()
    {
        return view('spanning_datas.create');
    }

    /**
     * Store a newly created SpanningData in storage.
     *
     * @param CreateSpanningDataRequest $request
     *
     * @return Response
     */
    public function store(CreateSpanningDataRequest $request)
    {
        $input = $request->all();

        $spanningData = $this->spanningDataRepository->create($input);

        Flash::success('Spanning Data saved successfully.');

        return redirect(route('spanningDatas.index'));
    }

    /**
     * Display the specified SpanningData.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $spanningData = $this->spanningDataRepository->find($id);

        if (empty($spanningData)) {
            Flash::error('Spanning Data not found');

            return redirect(route('spanningDatas.index'));
        }

        return view('spanning_datas.show')->with('spanningData', $spanningData);
    }

    /**
     * Show the form for editing the specified SpanningData.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $spanningData = $this->spanningDataRepository->find($id);

        if (empty($spanningData)) {
            Flash::error('Spanning Data not found');

            return redirect(route('spanningDatas.index'));
        }

        return view('spanning_datas.edit')->with('spanningData', $spanningData);
    }

    /**
     * Update the specified SpanningData in storage.
     *
     * @param int $id
     * @param UpdateSpanningDataRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSpanningDataRequest $request)
    {
        $spanningData = $this->spanningDataRepository->find($id);

        if (empty($spanningData)) {
            Flash::error('Spanning Data not found');

            return redirect(route('spanningDatas.index'));
        }

        $spanningData = $this->spanningDataRepository->update($request->all(), $id);

        Flash::success('Spanning Data updated successfully.');

        return redirect(route('spanningDatas.index'));
    }

    /**
     * Remove the specified SpanningData from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $spanningData = $this->spanningDataRepository->find($id);

        // DELETE BillOfMaterialsMatrix
        BillOfMaterialsMatrix::where('ServiceConnectionId', $spanningData->ServiceConnectionId)
                        ->where('StructureType', 'SPAN')
                        ->delete();

        // DELETE Structure Assignments
        StructureAssignments::where('ServiceConnectionId', $spanningData->ServiceConnectionId)
                        ->where('ConAssGrouping', '9')
                        ->delete();

        if (empty($spanningData)) {
            Flash::error('Spanning Data not found');

            return redirect(route('spanningDatas.index'));
        }

        $this->spanningDataRepository->delete($id);

        // Flash::success('Spanning Data deleted successfully.');
        
        // return redirect(route('spanningDatas.index'));
        return json_encode(['response' => true]);
    }
}
