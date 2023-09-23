<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionTimeframesRequest;
use App\Http\Requests\UpdateServiceConnectionTimeframesRequest;
use App\Repositories\ServiceConnectionTimeframesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServiceConnectionTimeframesController extends AppBaseController
{
    /** @var  ServiceConnectionTimeframesRepository */
    private $serviceConnectionTimeframesRepository;

    public function __construct(ServiceConnectionTimeframesRepository $serviceConnectionTimeframesRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionTimeframesRepository = $serviceConnectionTimeframesRepo;
    }

    /**
     * Display a listing of the ServiceConnectionTimeframes.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionTimeframes = $this->serviceConnectionTimeframesRepository->all();

        return view('service_connection_timeframes.index')
            ->with('serviceConnectionTimeframes', $serviceConnectionTimeframes);
    }

    /**
     * Show the form for creating a new ServiceConnectionTimeframes.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_connection_timeframes.create');
    }

    /**
     * Store a newly created ServiceConnectionTimeframes in storage.
     *
     * @param CreateServiceConnectionTimeframesRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionTimeframesRequest $request)
    {
        $input = $request->all();

        $serviceConnectionTimeframes = $this->serviceConnectionTimeframesRepository->create($input);

        Flash::success('Service Connection Timeframes saved successfully.');

        return redirect(route('serviceConnectionTimeframes.index'));
    }

    /**
     * Display the specified ServiceConnectionTimeframes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionTimeframes = $this->serviceConnectionTimeframesRepository->find($id);

        if (empty($serviceConnectionTimeframes)) {
            Flash::error('Service Connection Timeframes not found');

            return redirect(route('serviceConnectionTimeframes.index'));
        }

        return view('service_connection_timeframes.show')->with('serviceConnectionTimeframes', $serviceConnectionTimeframes);
    }

    /**
     * Show the form for editing the specified ServiceConnectionTimeframes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionTimeframes = $this->serviceConnectionTimeframesRepository->find($id);

        if (empty($serviceConnectionTimeframes)) {
            Flash::error('Service Connection Timeframes not found');

            return redirect(route('serviceConnectionTimeframes.index'));
        }

        return view('service_connection_timeframes.edit')->with('serviceConnectionTimeframes', $serviceConnectionTimeframes);
    }

    /**
     * Update the specified ServiceConnectionTimeframes in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionTimeframesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionTimeframesRequest $request)
    {
        $serviceConnectionTimeframes = $this->serviceConnectionTimeframesRepository->find($id);

        if (empty($serviceConnectionTimeframes)) {
            Flash::error('Service Connection Timeframes not found');

            return redirect(route('serviceConnectionTimeframes.index'));
        }

        $serviceConnectionTimeframes = $this->serviceConnectionTimeframesRepository->update($request->all(), $id);

        Flash::success('Service Connection Timeframes updated successfully.');

        return redirect(route('serviceConnectionTimeframes.index'));
    }

    /**
     * Remove the specified ServiceConnectionTimeframes from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionTimeframes = $this->serviceConnectionTimeframesRepository->find($id);

        if (empty($serviceConnectionTimeframes)) {
            Flash::error('Service Connection Timeframes not found');

            return redirect(route('serviceConnectionTimeframes.index'));
        }

        $this->serviceConnectionTimeframesRepository->delete($id);

        Flash::success('Service Connection Timeframes deleted successfully.');

        return redirect(route('serviceConnectionTimeframes.index'));
    }
}
