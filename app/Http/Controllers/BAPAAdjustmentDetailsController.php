<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBAPAAdjustmentDetailsRequest;
use App\Http\Requests\UpdateBAPAAdjustmentDetailsRequest;
use App\Repositories\BAPAAdjustmentDetailsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class BAPAAdjustmentDetailsController extends AppBaseController
{
    /** @var  BAPAAdjustmentDetailsRepository */
    private $bAPAAdjustmentDetailsRepository;

    public function __construct(BAPAAdjustmentDetailsRepository $bAPAAdjustmentDetailsRepo)
    {
        $this->middleware('auth');
        $this->bAPAAdjustmentDetailsRepository = $bAPAAdjustmentDetailsRepo;
    }

    /**
     * Display a listing of the BAPAAdjustmentDetails.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $bAPAAdjustmentDetails = $this->bAPAAdjustmentDetailsRepository->all();

        return view('b_a_p_a_adjustment_details.index')
            ->with('bAPAAdjustmentDetails', $bAPAAdjustmentDetails);
    }

    /**
     * Show the form for creating a new BAPAAdjustmentDetails.
     *
     * @return Response
     */
    public function create()
    {
        return view('b_a_p_a_adjustment_details.create');
    }

    /**
     * Store a newly created BAPAAdjustmentDetails in storage.
     *
     * @param CreateBAPAAdjustmentDetailsRequest $request
     *
     * @return Response
     */
    public function store(CreateBAPAAdjustmentDetailsRequest $request)
    {
        $input = $request->all();

        $bAPAAdjustmentDetails = $this->bAPAAdjustmentDetailsRepository->create($input);

        Flash::success('B A P A Adjustment Details saved successfully.');

        return redirect(route('bAPAAdjustmentDetails.index'));
    }

    /**
     * Display the specified BAPAAdjustmentDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $bAPAAdjustmentDetails = $this->bAPAAdjustmentDetailsRepository->find($id);

        if (empty($bAPAAdjustmentDetails)) {
            Flash::error('B A P A Adjustment Details not found');

            return redirect(route('bAPAAdjustmentDetails.index'));
        }

        return view('b_a_p_a_adjustment_details.show')->with('bAPAAdjustmentDetails', $bAPAAdjustmentDetails);
    }

    /**
     * Show the form for editing the specified BAPAAdjustmentDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $bAPAAdjustmentDetails = $this->bAPAAdjustmentDetailsRepository->find($id);

        if (empty($bAPAAdjustmentDetails)) {
            Flash::error('B A P A Adjustment Details not found');

            return redirect(route('bAPAAdjustmentDetails.index'));
        }

        return view('b_a_p_a_adjustment_details.edit')->with('bAPAAdjustmentDetails', $bAPAAdjustmentDetails);
    }

    /**
     * Update the specified BAPAAdjustmentDetails in storage.
     *
     * @param int $id
     * @param UpdateBAPAAdjustmentDetailsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBAPAAdjustmentDetailsRequest $request)
    {
        $bAPAAdjustmentDetails = $this->bAPAAdjustmentDetailsRepository->find($id);

        if (empty($bAPAAdjustmentDetails)) {
            Flash::error('B A P A Adjustment Details not found');

            return redirect(route('bAPAAdjustmentDetails.index'));
        }

        $bAPAAdjustmentDetails = $this->bAPAAdjustmentDetailsRepository->update($request->all(), $id);

        Flash::success('B A P A Adjustment Details updated successfully.');

        return redirect(route('bAPAAdjustmentDetails.index'));
    }

    /**
     * Remove the specified BAPAAdjustmentDetails from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $bAPAAdjustmentDetails = $this->bAPAAdjustmentDetailsRepository->find($id);

        if (empty($bAPAAdjustmentDetails)) {
            Flash::error('B A P A Adjustment Details not found');

            return redirect(route('bAPAAdjustmentDetails.index'));
        }

        $this->bAPAAdjustmentDetailsRepository->delete($id);

        Flash::success('B A P A Adjustment Details deleted successfully.');

        return redirect(route('bAPAAdjustmentDetails.index'));
    }
}
