<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePrePaymentTransHistoryRequest;
use App\Http\Requests\UpdatePrePaymentTransHistoryRequest;
use App\Repositories\PrePaymentTransHistoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class PrePaymentTransHistoryController extends AppBaseController
{
    /** @var  PrePaymentTransHistoryRepository */
    private $prePaymentTransHistoryRepository;

    public function __construct(PrePaymentTransHistoryRepository $prePaymentTransHistoryRepo)
    {
        $this->middleware('auth');
        $this->prePaymentTransHistoryRepository = $prePaymentTransHistoryRepo;
    }

    /**
     * Display a listing of the PrePaymentTransHistory.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $prePaymentTransHistories = $this->prePaymentTransHistoryRepository->all();

        return view('pre_payment_trans_histories.index')
            ->with('prePaymentTransHistories', $prePaymentTransHistories);
    }

    /**
     * Show the form for creating a new PrePaymentTransHistory.
     *
     * @return Response
     */
    public function create()
    {
        return view('pre_payment_trans_histories.create');
    }

    /**
     * Store a newly created PrePaymentTransHistory in storage.
     *
     * @param CreatePrePaymentTransHistoryRequest $request
     *
     * @return Response
     */
    public function store(CreatePrePaymentTransHistoryRequest $request)
    {
        $input = $request->all();

        $prePaymentTransHistory = $this->prePaymentTransHistoryRepository->create($input);

        Flash::success('Pre Payment Trans History saved successfully.');

        return redirect(route('prePaymentTransHistories.index'));
    }

    /**
     * Display the specified PrePaymentTransHistory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $prePaymentTransHistory = $this->prePaymentTransHistoryRepository->find($id);

        if (empty($prePaymentTransHistory)) {
            Flash::error('Pre Payment Trans History not found');

            return redirect(route('prePaymentTransHistories.index'));
        }

        return view('pre_payment_trans_histories.show')->with('prePaymentTransHistory', $prePaymentTransHistory);
    }

    /**
     * Show the form for editing the specified PrePaymentTransHistory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $prePaymentTransHistory = $this->prePaymentTransHistoryRepository->find($id);

        if (empty($prePaymentTransHistory)) {
            Flash::error('Pre Payment Trans History not found');

            return redirect(route('prePaymentTransHistories.index'));
        }

        return view('pre_payment_trans_histories.edit')->with('prePaymentTransHistory', $prePaymentTransHistory);
    }

    /**
     * Update the specified PrePaymentTransHistory in storage.
     *
     * @param int $id
     * @param UpdatePrePaymentTransHistoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePrePaymentTransHistoryRequest $request)
    {
        $prePaymentTransHistory = $this->prePaymentTransHistoryRepository->find($id);

        if (empty($prePaymentTransHistory)) {
            Flash::error('Pre Payment Trans History not found');

            return redirect(route('prePaymentTransHistories.index'));
        }

        $prePaymentTransHistory = $this->prePaymentTransHistoryRepository->update($request->all(), $id);

        Flash::success('Pre Payment Trans History updated successfully.');

        return redirect(route('prePaymentTransHistories.index'));
    }

    /**
     * Remove the specified PrePaymentTransHistory from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $prePaymentTransHistory = $this->prePaymentTransHistoryRepository->find($id);

        $acctNo = $prePaymentTransHistory->AccountNumber;

        if (empty($prePaymentTransHistory)) {
            Flash::error('Pre Payment Trans History not found');

            return redirect(route('prePaymentTransHistories.index'));
        }

        $this->prePaymentTransHistoryRepository->delete($id);

        Flash::success('Pre Payment Trans History deleted successfully.');

        return redirect(route('serviceAccounts.show', [$acctNo]));
    }
}
