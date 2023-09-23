<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCostCentersRequest;
use App\Http\Requests\UpdateCostCentersRequest;
use App\Repositories\CostCentersRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class CostCentersController extends AppBaseController
{
    /** @var  CostCentersRepository */
    private $costCentersRepository;

    public function __construct(CostCentersRepository $costCentersRepo)
    {
        $this->middleware('auth');
        $this->costCentersRepository = $costCentersRepo;
    }

    /**
     * Display a listing of the CostCenters.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $costCenters = $this->costCentersRepository->all();

        return view('cost_centers.index')
            ->with('costCenters', $costCenters);
    }

    /**
     * Show the form for creating a new CostCenters.
     *
     * @return Response
     */
    public function create()
    {
        return view('cost_centers.create');
    }

    /**
     * Store a newly created CostCenters in storage.
     *
     * @param CreateCostCentersRequest $request
     *
     * @return Response
     */
    public function store(CreateCostCentersRequest $request)
    {
        $input = $request->all();

        $costCenters = $this->costCentersRepository->create($input);

        Flash::success('Cost Centers saved successfully.');

        return redirect(route('costCenters.index'));
    }

    /**
     * Display the specified CostCenters.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $costCenters = $this->costCentersRepository->find($id);

        if (empty($costCenters)) {
            Flash::error('Cost Centers not found');

            return redirect(route('costCenters.index'));
        }

        return view('cost_centers.show')->with('costCenters', $costCenters);
    }

    /**
     * Show the form for editing the specified CostCenters.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $costCenters = $this->costCentersRepository->find($id);

        if (empty($costCenters)) {
            Flash::error('Cost Centers not found');

            return redirect(route('costCenters.index'));
        }

        return view('cost_centers.edit')->with('costCenters', $costCenters);
    }

    /**
     * Update the specified CostCenters in storage.
     *
     * @param int $id
     * @param UpdateCostCentersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCostCentersRequest $request)
    {
        $costCenters = $this->costCentersRepository->find($id);

        if (empty($costCenters)) {
            Flash::error('Cost Centers not found');

            return redirect(route('costCenters.index'));
        }

        $costCenters = $this->costCentersRepository->update($request->all(), $id);

        Flash::success('Cost Centers updated successfully.');

        return redirect(route('costCenters.index'));
    }

    /**
     * Remove the specified CostCenters from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $costCenters = $this->costCentersRepository->find($id);

        if (empty($costCenters)) {
            Flash::error('Cost Centers not found');

            return redirect(route('costCenters.index'));
        }

        $this->costCentersRepository->delete($id);

        Flash::success('Cost Centers deleted successfully.');

        return redirect(route('costCenters.index'));
    }
}
