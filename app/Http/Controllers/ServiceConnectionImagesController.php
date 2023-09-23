<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionImagesRequest;
use App\Http\Requests\UpdateServiceConnectionImagesRequest;
use App\Repositories\ServiceConnectionImagesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServiceConnectionImagesController extends AppBaseController
{
    /** @var  ServiceConnectionImagesRepository */
    private $serviceConnectionImagesRepository;

    public function __construct(ServiceConnectionImagesRepository $serviceConnectionImagesRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionImagesRepository = $serviceConnectionImagesRepo;
    }

    /**
     * Display a listing of the ServiceConnectionImages.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionImages = $this->serviceConnectionImagesRepository->all();

        return view('service_connection_images.index')
            ->with('serviceConnectionImages', $serviceConnectionImages);
    }

    /**
     * Show the form for creating a new ServiceConnectionImages.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_connection_images.create');
    }

    /**
     * Store a newly created ServiceConnectionImages in storage.
     *
     * @param CreateServiceConnectionImagesRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionImagesRequest $request)
    {
        $input = $request->all();

        $serviceConnectionImages = $this->serviceConnectionImagesRepository->create($input);

        Flash::success('Service Connection Images saved successfully.');

        return redirect(route('serviceConnectionImages.index'));
    }

    /**
     * Display the specified ServiceConnectionImages.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionImages = $this->serviceConnectionImagesRepository->find($id);

        if (empty($serviceConnectionImages)) {
            Flash::error('Service Connection Images not found');

            return redirect(route('serviceConnectionImages.index'));
        }

        return view('service_connection_images.show')->with('serviceConnectionImages', $serviceConnectionImages);
    }

    /**
     * Show the form for editing the specified ServiceConnectionImages.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionImages = $this->serviceConnectionImagesRepository->find($id);

        if (empty($serviceConnectionImages)) {
            Flash::error('Service Connection Images not found');

            return redirect(route('serviceConnectionImages.index'));
        }

        return view('service_connection_images.edit')->with('serviceConnectionImages', $serviceConnectionImages);
    }

    /**
     * Update the specified ServiceConnectionImages in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionImagesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionImagesRequest $request)
    {
        $serviceConnectionImages = $this->serviceConnectionImagesRepository->find($id);

        if (empty($serviceConnectionImages)) {
            Flash::error('Service Connection Images not found');

            return redirect(route('serviceConnectionImages.index'));
        }

        $serviceConnectionImages = $this->serviceConnectionImagesRepository->update($request->all(), $id);

        Flash::success('Service Connection Images updated successfully.');

        return redirect(route('serviceConnectionImages.index'));
    }

    /**
     * Remove the specified ServiceConnectionImages from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionImages = $this->serviceConnectionImagesRepository->find($id);

        if (empty($serviceConnectionImages)) {
            Flash::error('Service Connection Images not found');

            return redirect(route('serviceConnectionImages.index'));
        }

        $this->serviceConnectionImagesRepository->delete($id);

        Flash::success('Service Connection Images deleted successfully.');

        return redirect(route('serviceConnectionImages.index'));
    }
}
