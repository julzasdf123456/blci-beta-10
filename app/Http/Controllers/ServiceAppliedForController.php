<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceAppliedForRequest;
use App\Http\Requests\UpdateServiceAppliedForRequest;
use App\Repositories\ServiceAppliedForRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use Flash;
use Response;

class ServiceAppliedForController extends AppBaseController
{
    /** @var  ServiceAppliedForRepository */
    private $serviceAppliedForRepository;

    public function __construct(ServiceAppliedForRepository $serviceAppliedForRepo)
    {
        $this->middleware('auth');
        $this->serviceAppliedForRepository = $serviceAppliedForRepo;
    }

    /**
     * Display a listing of the ServiceAppliedFor.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceAppliedFors = $this->serviceAppliedForRepository->all();

        return view('service_applied_fors.index')
            ->with('serviceAppliedFors', $serviceAppliedFors);
    }

    /**
     * Show the form for creating a new ServiceAppliedFor.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_applied_fors.create');
    }

    /**
     * Store a newly created ServiceAppliedFor in storage.
     *
     * @param CreateServiceAppliedForRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceAppliedForRequest $request)
    {
        $input = $request->all();
        $input['id'] = IDGenerator::generateID();

        $serviceAppliedFor = $this->serviceAppliedForRepository->create($input);

        Flash::success('Service Applied For saved successfully.');

        return redirect(route('serviceAppliedFors.index'));
    }

    /**
     * Display the specified ServiceAppliedFor.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceAppliedFor = $this->serviceAppliedForRepository->find($id);

        if (empty($serviceAppliedFor)) {
            Flash::error('Service Applied For not found');

            return redirect(route('serviceAppliedFors.index'));
        }

        return view('service_applied_fors.show')->with('serviceAppliedFor', $serviceAppliedFor);
    }

    /**
     * Show the form for editing the specified ServiceAppliedFor.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceAppliedFor = $this->serviceAppliedForRepository->find($id);

        if (empty($serviceAppliedFor)) {
            Flash::error('Service Applied For not found');

            return redirect(route('serviceAppliedFors.index'));
        }

        return view('service_applied_fors.edit')->with('serviceAppliedFor', $serviceAppliedFor);
    }

    /**
     * Update the specified ServiceAppliedFor in storage.
     *
     * @param int $id
     * @param UpdateServiceAppliedForRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceAppliedForRequest $request)
    {
        $serviceAppliedFor = $this->serviceAppliedForRepository->find($id);

        if (empty($serviceAppliedFor)) {
            Flash::error('Service Applied For not found');

            return redirect(route('serviceAppliedFors.index'));
        }

        $serviceAppliedFor = $this->serviceAppliedForRepository->update($request->all(), $id);

        Flash::success('Service Applied For updated successfully.');

        return redirect(route('serviceAppliedFors.index'));
    }

    /**
     * Remove the specified ServiceAppliedFor from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceAppliedFor = $this->serviceAppliedForRepository->find($id);

        if (empty($serviceAppliedFor)) {
            Flash::error('Service Applied For not found');

            return redirect(route('serviceAppliedFors.index'));
        }

        $this->serviceAppliedForRepository->delete($id);

        Flash::success('Service Applied For deleted successfully.');

        return redirect(route('serviceAppliedFors.index'));
    }
}
