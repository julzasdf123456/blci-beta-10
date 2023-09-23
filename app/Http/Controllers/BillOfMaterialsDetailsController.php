<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillOfMaterialsDetailsRequest;
use App\Http\Requests\UpdateBillOfMaterialsDetailsRequest;
use App\Repositories\BillOfMaterialsDetailsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class BillOfMaterialsDetailsController extends AppBaseController
{
    /** @var  BillOfMaterialsDetailsRepository */
    private $billOfMaterialsDetailsRepository;

    public function __construct(BillOfMaterialsDetailsRepository $billOfMaterialsDetailsRepo)
    {
        $this->middleware('auth');
        $this->billOfMaterialsDetailsRepository = $billOfMaterialsDetailsRepo;
    }

    /**
     * Display a listing of the BillOfMaterialsDetails.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $billOfMaterialsDetails = $this->billOfMaterialsDetailsRepository->all();

        return view('bill_of_materials_details.index')
            ->with('billOfMaterialsDetails', $billOfMaterialsDetails);
    }

    /**
     * Show the form for creating a new BillOfMaterialsDetails.
     *
     * @return Response
     */
    public function create()
    {
        return view('bill_of_materials_details.create');
    }

    /**
     * Store a newly created BillOfMaterialsDetails in storage.
     *
     * @param CreateBillOfMaterialsDetailsRequest $request
     *
     * @return Response
     */
    public function store(CreateBillOfMaterialsDetailsRequest $request)
    {
        $input = $request->all();

        $billOfMaterialsDetails = $this->billOfMaterialsDetailsRepository->create($input);

        Flash::success('Bill Of Materials Details saved successfully.');

        return redirect(route('billOfMaterialsDetails.index'));
    }

    /**
     * Display the specified BillOfMaterialsDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $billOfMaterialsDetails = $this->billOfMaterialsDetailsRepository->find($id);

        if (empty($billOfMaterialsDetails)) {
            Flash::error('Bill Of Materials Details not found');

            return redirect(route('billOfMaterialsDetails.index'));
        }

        return view('bill_of_materials_details.show')->with('billOfMaterialsDetails', $billOfMaterialsDetails);
    }

    /**
     * Show the form for editing the specified BillOfMaterialsDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $billOfMaterialsDetails = $this->billOfMaterialsDetailsRepository->find($id);

        if (empty($billOfMaterialsDetails)) {
            Flash::error('Bill Of Materials Details not found');

            return redirect(route('billOfMaterialsDetails.index'));
        }

        return view('bill_of_materials_details.edit')->with('billOfMaterialsDetails', $billOfMaterialsDetails);
    }

    /**
     * Update the specified BillOfMaterialsDetails in storage.
     *
     * @param int $id
     * @param UpdateBillOfMaterialsDetailsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillOfMaterialsDetailsRequest $request)
    {
        $billOfMaterialsDetails = $this->billOfMaterialsDetailsRepository->find($id);

        if (empty($billOfMaterialsDetails)) {
            Flash::error('Bill Of Materials Details not found');

            return redirect(route('billOfMaterialsDetails.index'));
        }

        $billOfMaterialsDetails = $this->billOfMaterialsDetailsRepository->update($request->all(), $id);

        Flash::success('Bill Of Materials Details updated successfully.');

        return redirect(route('billOfMaterialsDetails.index'));
    }

    /**
     * Remove the specified BillOfMaterialsDetails from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $billOfMaterialsDetails = $this->billOfMaterialsDetailsRepository->find($id);

        if (empty($billOfMaterialsDetails)) {
            Flash::error('Bill Of Materials Details not found');

            return redirect(route('billOfMaterialsDetails.index'));
        }

        $this->billOfMaterialsDetailsRepository->delete($id);

        Flash::success('Bill Of Materials Details deleted successfully.');

        return redirect(route('billOfMaterialsDetails.index'));
    }
}
