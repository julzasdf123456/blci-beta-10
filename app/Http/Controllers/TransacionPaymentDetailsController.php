<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransacionPaymentDetailsRequest;
use App\Http\Requests\UpdateTransacionPaymentDetailsRequest;
use App\Repositories\TransacionPaymentDetailsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class TransacionPaymentDetailsController extends AppBaseController
{
    /** @var  TransacionPaymentDetailsRepository */
    private $transacionPaymentDetailsRepository;

    public function __construct(TransacionPaymentDetailsRepository $transacionPaymentDetailsRepo)
    {
        $this->middleware('auth');
        $this->transacionPaymentDetailsRepository = $transacionPaymentDetailsRepo;
    }

    /**
     * Display a listing of the TransacionPaymentDetails.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $transacionPaymentDetails = $this->transacionPaymentDetailsRepository->all();

        return view('transacion_payment_details.index')
            ->with('transacionPaymentDetails', $transacionPaymentDetails);
    }

    /**
     * Show the form for creating a new TransacionPaymentDetails.
     *
     * @return Response
     */
    public function create()
    {
        return view('transacion_payment_details.create');
    }

    /**
     * Store a newly created TransacionPaymentDetails in storage.
     *
     * @param CreateTransacionPaymentDetailsRequest $request
     *
     * @return Response
     */
    public function store(CreateTransacionPaymentDetailsRequest $request)
    {
        $input = $request->all();

        $transacionPaymentDetails = $this->transacionPaymentDetailsRepository->create($input);

        Flash::success('Transacion Payment Details saved successfully.');

        return redirect(route('transacionPaymentDetails.index'));
    }

    /**
     * Display the specified TransacionPaymentDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $transacionPaymentDetails = $this->transacionPaymentDetailsRepository->find($id);

        if (empty($transacionPaymentDetails)) {
            Flash::error('Transacion Payment Details not found');

            return redirect(route('transacionPaymentDetails.index'));
        }

        return view('transacion_payment_details.show')->with('transacionPaymentDetails', $transacionPaymentDetails);
    }

    /**
     * Show the form for editing the specified TransacionPaymentDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $transacionPaymentDetails = $this->transacionPaymentDetailsRepository->find($id);

        if (empty($transacionPaymentDetails)) {
            Flash::error('Transacion Payment Details not found');

            return redirect(route('transacionPaymentDetails.index'));
        }

        return view('transacion_payment_details.edit')->with('transacionPaymentDetails', $transacionPaymentDetails);
    }

    /**
     * Update the specified TransacionPaymentDetails in storage.
     *
     * @param int $id
     * @param UpdateTransacionPaymentDetailsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTransacionPaymentDetailsRequest $request)
    {
        $transacionPaymentDetails = $this->transacionPaymentDetailsRepository->find($id);

        if (empty($transacionPaymentDetails)) {
            Flash::error('Transacion Payment Details not found');

            return redirect(route('transacionPaymentDetails.index'));
        }

        $transacionPaymentDetails = $this->transacionPaymentDetailsRepository->update($request->all(), $id);

        Flash::success('Transacion Payment Details updated successfully.');

        return redirect(route('transacionPaymentDetails.index'));
    }

    /**
     * Remove the specified TransacionPaymentDetails from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $transacionPaymentDetails = $this->transacionPaymentDetailsRepository->find($id);

        if (empty($transacionPaymentDetails)) {
            Flash::error('Transacion Payment Details not found');

            return redirect(route('transacionPaymentDetails.index'));
        }

        $this->transacionPaymentDetailsRepository->delete($id);

        Flash::success('Transacion Payment Details deleted successfully.');

        return redirect(route('transacionPaymentDetails.index'));
    }
}
