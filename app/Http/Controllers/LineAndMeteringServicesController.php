<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLineAndMeteringServicesRequest;
use App\Http\Requests\UpdateLineAndMeteringServicesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\LineAndMeteringServicesRepository;
use Illuminate\Http\Request;
use Flash;

class LineAndMeteringServicesController extends AppBaseController
{
    /** @var LineAndMeteringServicesRepository $lineAndMeteringServicesRepository*/
    private $lineAndMeteringServicesRepository;

    public function __construct(LineAndMeteringServicesRepository $lineAndMeteringServicesRepo)
    {
        $this->middleware('auth');
        $this->lineAndMeteringServicesRepository = $lineAndMeteringServicesRepo;
    }

    /**
     * Display a listing of the LineAndMeteringServices.
     */
    public function index(Request $request)
    {
        $lineAndMeteringServices = $this->lineAndMeteringServicesRepository->paginate(10);

        return view('line_and_metering_services.index')
            ->with('lineAndMeteringServices', $lineAndMeteringServices);
    }

    /**
     * Show the form for creating a new LineAndMeteringServices.
     */
    public function create()
    {
        return view('line_and_metering_services.create');
    }

    /**
     * Store a newly created LineAndMeteringServices in storage.
     */
    public function store(CreateLineAndMeteringServicesRequest $request)
    {
        $input = $request->all();

        $lineAndMeteringServices = $this->lineAndMeteringServicesRepository->create($input);

        Flash::success('Line And Metering Services saved successfully.');

        return redirect(route('lineAndMeteringServices.index'));
    }

    /**
     * Display the specified LineAndMeteringServices.
     */
    public function show($id)
    {
        $lineAndMeteringServices = $this->lineAndMeteringServicesRepository->find($id);

        if (empty($lineAndMeteringServices)) {
            Flash::error('Line And Metering Services not found');

            return redirect(route('lineAndMeteringServices.index'));
        }

        return view('line_and_metering_services.show')->with('lineAndMeteringServices', $lineAndMeteringServices);
    }

    /**
     * Show the form for editing the specified LineAndMeteringServices.
     */
    public function edit($id)
    {
        $lineAndMeteringServices = $this->lineAndMeteringServicesRepository->find($id);

        if (empty($lineAndMeteringServices)) {
            Flash::error('Line And Metering Services not found');

            return redirect(route('lineAndMeteringServices.index'));
        }

        return view('line_and_metering_services.edit')->with('lineAndMeteringServices', $lineAndMeteringServices);
    }

    /**
     * Update the specified LineAndMeteringServices in storage.
     */
    public function update($id, UpdateLineAndMeteringServicesRequest $request)
    {
        $lineAndMeteringServices = $this->lineAndMeteringServicesRepository->find($id);

        if (empty($lineAndMeteringServices)) {
            Flash::error('Line And Metering Services not found');

            return redirect(route('lineAndMeteringServices.index'));
        }

        $lineAndMeteringServices = $this->lineAndMeteringServicesRepository->update($request->all(), $id);

        Flash::success('Line And Metering Services updated successfully.');

        return redirect(route('lineAndMeteringServices.index'));
    }

    /**
     * Remove the specified LineAndMeteringServices from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $lineAndMeteringServices = $this->lineAndMeteringServicesRepository->find($id);

        if (empty($lineAndMeteringServices)) {
            Flash::error('Line And Metering Services not found');

            return redirect(route('lineAndMeteringServices.index'));
        }

        $this->lineAndMeteringServicesRepository->delete($id);

        Flash::success('Line And Metering Services deleted successfully.');

        return redirect(route('lineAndMeteringServices.index'));
    }
}
