<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionCommentsRequest;
use App\Http\Requests\UpdateServiceConnectionCommentsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ServiceConnectionCommentsRepository;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use Illuminate\Support\Facades\DB;
use Flash;

class ServiceConnectionCommentsController extends AppBaseController
{
    /** @var ServiceConnectionCommentsRepository $serviceConnectionCommentsRepository*/
    private $serviceConnectionCommentsRepository;

    public function __construct(ServiceConnectionCommentsRepository $serviceConnectionCommentsRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionCommentsRepository = $serviceConnectionCommentsRepo;
    }

    /**
     * Display a listing of the ServiceConnectionComments.
     */
    public function index(Request $request)
    {
        $serviceConnectionComments = $this->serviceConnectionCommentsRepository->paginate(10);

        return view('service_connection_comments.index')
            ->with('serviceConnectionComments', $serviceConnectionComments);
    }

    /**
     * Show the form for creating a new ServiceConnectionComments.
     */
    public function create()
    {
        return view('service_connection_comments.create');
    }

    /**
     * Store a newly created ServiceConnectionComments in storage.
     */
    public function store(CreateServiceConnectionCommentsRequest $request)
    {
        $input = $request->all();
        $input['id'] = IDGenerator::generateIDandRandString();

        $serviceConnectionComments = $this->serviceConnectionCommentsRepository->create($input);

        // Flash::success('Service Connection Comments saved successfully.');

        // return redirect(route('serviceConnectionComments.index'));
        return response()->json($serviceConnectionComments, 200);
    }

    /**
     * Display the specified ServiceConnectionComments.
     */
    public function show($id)
    {
        $serviceConnectionComments = $this->serviceConnectionCommentsRepository->find($id);

        if (empty($serviceConnectionComments)) {
            Flash::error('Service Connection Comments not found');

            return redirect(route('serviceConnectionComments.index'));
        }

        return view('service_connection_comments.show')->with('serviceConnectionComments', $serviceConnectionComments);
    }

    /**
     * Show the form for editing the specified ServiceConnectionComments.
     */
    public function edit($id)
    {
        $serviceConnectionComments = $this->serviceConnectionCommentsRepository->find($id);

        if (empty($serviceConnectionComments)) {
            Flash::error('Service Connection Comments not found');

            return redirect(route('serviceConnectionComments.index'));
        }

        return view('service_connection_comments.edit')->with('serviceConnectionComments', $serviceConnectionComments);
    }

    /**
     * Update the specified ServiceConnectionComments in storage.
     */
    public function update($id, UpdateServiceConnectionCommentsRequest $request)
    {
        $serviceConnectionComments = $this->serviceConnectionCommentsRepository->find($id);

        if (empty($serviceConnectionComments)) {
            Flash::error('Service Connection Comments not found');

            return redirect(route('serviceConnectionComments.index'));
        }

        $serviceConnectionComments = $this->serviceConnectionCommentsRepository->update($request->all(), $id);

        Flash::success('Service Connection Comments updated successfully.');

        return redirect(route('serviceConnectionComments.index'));
    }

    /**
     * Remove the specified ServiceConnectionComments from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $serviceConnectionComments = $this->serviceConnectionCommentsRepository->find($id);

        if (empty($serviceConnectionComments)) {
            Flash::error('Service Connection Comments not found');

            return redirect(route('serviceConnectionComments.index'));
        }

        $this->serviceConnectionCommentsRepository->delete($id);

        Flash::success('Service Connection Comments deleted successfully.');

        return redirect(route('serviceConnectionComments.index'));
    }

    public function getComments(Request $request) {
        $scId = $request['ServiceConnectionId'];

        $data = DB::table('CRM_ServiceConnectionComments')
            ->leftJoin('users', 'CRM_ServiceConnectionComments.UserId', '=', 'users.id')
            ->whereRaw("CRM_ServiceConnectionComments.ServiceConnectionId='" . $scId . "'")
            ->select(
                'CRM_ServiceConnectionComments.*',
                'users.name'
            )
            ->orderByDesc('CRM_ServiceConnectionComments.created_at')
            ->get();

        return response()->json($data, 200);
    }
}
