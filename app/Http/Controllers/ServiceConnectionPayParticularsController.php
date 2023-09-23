<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionPayParticularsRequest;
use App\Http\Requests\UpdateServiceConnectionPayParticularsRequest;
use App\Repositories\ServiceConnectionPayParticularsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServiceConnectionPayParticularsController extends AppBaseController
{
    /** @var  ServiceConnectionPayParticularsRepository */
    private $serviceConnectionPayParticularsRepository;

    public function __construct(ServiceConnectionPayParticularsRepository $serviceConnectionPayParticularsRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionPayParticularsRepository = $serviceConnectionPayParticularsRepo;
    }

    /**
     * Display a listing of the ServiceConnectionPayParticulars.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionPayParticulars = $this->serviceConnectionPayParticularsRepository->all();

        return view('service_connection_pay_particulars.index')
            ->with('serviceConnectionPayParticulars', $serviceConnectionPayParticulars);
    }

    /**
     * Show the form for creating a new ServiceConnectionPayParticulars.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_connection_pay_particulars.create');
    }

    /**
     * Store a newly created ServiceConnectionPayParticulars in storage.
     *
     * @param CreateServiceConnectionPayParticularsRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionPayParticularsRequest $request)
    {
        $input = $request->all();

        $serviceConnectionPayParticulars = $this->serviceConnectionPayParticularsRepository->create($input);

        Flash::success('Service Connection Pay Particulars saved successfully.');

        return redirect(route('serviceConnectionPayParticulars.index'));
    }

    /**
     * Display the specified ServiceConnectionPayParticulars.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionPayParticulars = $this->serviceConnectionPayParticularsRepository->find($id);

        if (empty($serviceConnectionPayParticulars)) {
            Flash::error('Service Connection Pay Particulars not found');

            return redirect(route('serviceConnectionPayParticulars.index'));
        }

        return view('service_connection_pay_particulars.show')->with('serviceConnectionPayParticulars', $serviceConnectionPayParticulars);
    }

    /**
     * Show the form for editing the specified ServiceConnectionPayParticulars.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionPayParticulars = $this->serviceConnectionPayParticularsRepository->find($id);

        if (empty($serviceConnectionPayParticulars)) {
            Flash::error('Service Connection Pay Particulars not found');

            return redirect(route('serviceConnectionPayParticulars.index'));
        }

        return view('service_connection_pay_particulars.edit')->with('serviceConnectionPayParticulars', $serviceConnectionPayParticulars);
    }

    /**
     * Update the specified ServiceConnectionPayParticulars in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionPayParticularsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionPayParticularsRequest $request)
    {
        $serviceConnectionPayParticulars = $this->serviceConnectionPayParticularsRepository->find($id);

        if (empty($serviceConnectionPayParticulars)) {
            Flash::error('Service Connection Pay Particulars not found');

            return redirect(route('serviceConnectionPayParticulars.index'));
        }

        $serviceConnectionPayParticulars = $this->serviceConnectionPayParticularsRepository->update($request->all(), $id);

        Flash::success('Service Connection Pay Particulars updated successfully.');

        return redirect(route('serviceConnectionPayParticulars.index'));
    }

    /**
     * Remove the specified ServiceConnectionPayParticulars from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionPayParticulars = $this->serviceConnectionPayParticularsRepository->find($id);

        if (empty($serviceConnectionPayParticulars)) {
            Flash::error('Service Connection Pay Particulars not found');

            return redirect(route('serviceConnectionPayParticulars.index'));
        }

        $this->serviceConnectionPayParticularsRepository->delete($id);

        Flash::success('Service Connection Pay Particulars deleted successfully.');

        return redirect(route('serviceConnectionPayParticulars.index'));
    }
}
