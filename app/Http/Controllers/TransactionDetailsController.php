<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionDetailsRequest;
use App\Http\Requests\UpdateTransactionDetailsRequest;
use App\Repositories\TransactionDetailsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class TransactionDetailsController extends AppBaseController
{
    /** @var  TransactionDetailsRepository */
    private $transactionDetailsRepository;

    public function __construct(TransactionDetailsRepository $transactionDetailsRepo)
    {
        $this->middleware('auth');
        $this->transactionDetailsRepository = $transactionDetailsRepo;
    }

    /**
     * Display a listing of the TransactionDetails.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $transactionDetails = $this->transactionDetailsRepository->all();

        return view('transaction_details.index')
            ->with('transactionDetails', $transactionDetails);
    }

    /**
     * Show the form for creating a new TransactionDetails.
     *
     * @return Response
     */
    public function create()
    {
        return view('transaction_details.create');
    }

    /**
     * Store a newly created TransactionDetails in storage.
     *
     * @param CreateTransactionDetailsRequest $request
     *
     * @return Response
     */
    public function store(CreateTransactionDetailsRequest $request)
    {
        $input = $request->all();

        $transactionDetails = $this->transactionDetailsRepository->create($input);

        Flash::success('Transaction Details saved successfully.');

        return redirect(route('transactionDetails.index'));
    }

    /**
     * Display the specified TransactionDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $transactionDetails = $this->transactionDetailsRepository->find($id);

        if (empty($transactionDetails)) {
            Flash::error('Transaction Details not found');

            return redirect(route('transactionDetails.index'));
        }

        return view('transaction_details.show')->with('transactionDetails', $transactionDetails);
    }

    /**
     * Show the form for editing the specified TransactionDetails.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $transactionDetails = $this->transactionDetailsRepository->find($id);

        if (empty($transactionDetails)) {
            Flash::error('Transaction Details not found');

            return redirect(route('transactionDetails.index'));
        }

        return view('transaction_details.edit')->with('transactionDetails', $transactionDetails);
    }

    /**
     * Update the specified TransactionDetails in storage.
     *
     * @param int $id
     * @param UpdateTransactionDetailsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTransactionDetailsRequest $request)
    {
        $transactionDetails = $this->transactionDetailsRepository->find($id);

        if (empty($transactionDetails)) {
            Flash::error('Transaction Details not found');

            return redirect(route('transactionDetails.index'));
        }

        $transactionDetails = $this->transactionDetailsRepository->update($request->all(), $id);

        Flash::success('Transaction Details updated successfully.');

        return redirect(route('transactionDetails.index'));
    }

    /**
     * Remove the specified TransactionDetails from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $transactionDetails = $this->transactionDetailsRepository->find($id);

        if (empty($transactionDetails)) {
            Flash::error('Transaction Details not found');

            return redirect(route('transactionDetails.index'));
        }

        $this->transactionDetailsRepository->delete($id);

        Flash::success('Transaction Details deleted successfully.');

        return redirect(route('transactionDetails.index'));
    }
}
