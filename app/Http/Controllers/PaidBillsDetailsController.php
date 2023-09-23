<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaidBillsDetailsRequest;
use App\Http\Requests\UpdatePaidBillsDetailsRequest;
use App\Repositories\PaidBillsDetailsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class PaidBillsDetailsController extends AppBaseController
{
    /** @var  PaidBillsDetailsRepository */
    private $paidBillsDetailsRepository;

    public function __construct(PaidBillsDetailsRepository $paidBillsDetailsRepo)
    {
        $this->middleware('auth');
        $this->paidBillsDetailsRepository = $paidBillsDetailsRepo;
    }

    /**
     * Display a listing of the PaidBillsDetails.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $paidBillsDetails = $this->paidBillsDetailsRepository->all();

        return view('paid_bills_details.index')
            ->with('paidBillsDetails', $paidBillsDetails);
    }

    /**
     * Show the form for creating a new PaidBillsDetails.
     *
     * @return Response
     */
    public function create()
    {
        return view('paid_bills_details.create');
    }

    /**
     * Store a newly created PaidBillsDetails in storage.
     *
     * @param CreatePaidBillsDetailsRequest $request
     *
     * @return Response
     */
    public function store(CreatePaidBillsDetailsRequest $request)
    {
        $input = $request->all();

        $paidBillsDetails = $this->paidBillsDetailsRepository->create($input);

        Flash::success('Paid Bills Details saved successfully.');

        return redirect(route('paidBillsDetails.index'));
    }

    /**
     * Display the specified PaidBillsDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $paidBillsDetails = $this->paidBillsDetailsRepository->find($id);

        if (empty($paidBillsDetails)) {
            Flash::error('Paid Bills Details not found');

            return redirect(route('paidBillsDetails.index'));
        }

        return view('paid_bills_details.show')->with('paidBillsDetails', $paidBillsDetails);
    }

    /**
     * Show the form for editing the specified PaidBillsDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $paidBillsDetails = $this->paidBillsDetailsRepository->find($id);

        if (empty($paidBillsDetails)) {
            Flash::error('Paid Bills Details not found');

            return redirect(route('paidBillsDetails.index'));
        }

        return view('paid_bills_details.edit')->with('paidBillsDetails', $paidBillsDetails);
    }

    /**
     * Update the specified PaidBillsDetails in storage.
     *
     * @param int $id
     * @param UpdatePaidBillsDetailsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePaidBillsDetailsRequest $request)
    {
        $paidBillsDetails = $this->paidBillsDetailsRepository->find($id);

        if (empty($paidBillsDetails)) {
            Flash::error('Paid Bills Details not found');

            return redirect(route('paidBillsDetails.index'));
        }

        $paidBillsDetails = $this->paidBillsDetailsRepository->update($request->all(), $id);

        Flash::success('Paid Bills Details updated successfully.');

        return redirect(route('paidBillsDetails.index'));
    }

    /**
     * Remove the specified PaidBillsDetails from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $paidBillsDetails = $this->paidBillsDetailsRepository->find($id);

        if (empty($paidBillsDetails)) {
            Flash::error('Paid Bills Details not found');

            return redirect(route('paidBillsDetails.index'));
        }

        $this->paidBillsDetailsRepository->delete($id);

        Flash::success('Paid Bills Details deleted successfully.');

        return redirect(route('paidBillsDetails.index'));
    }
}
