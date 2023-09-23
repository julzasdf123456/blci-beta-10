<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountLocationHistoryRequest;
use App\Http\Requests\UpdateAccountLocationHistoryRequest;
use App\Repositories\AccountLocationHistoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AccountLocationHistoryController extends AppBaseController
{
    /** @var  AccountLocationHistoryRepository */
    private $accountLocationHistoryRepository;

    public function __construct(AccountLocationHistoryRepository $accountLocationHistoryRepo)
    {
        $this->accountLocationHistoryRepository = $accountLocationHistoryRepo;
    }

    /**
     * Display a listing of the AccountLocationHistory.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $accountLocationHistories = $this->accountLocationHistoryRepository->all();

        return view('account_location_histories.index')
            ->with('accountLocationHistories', $accountLocationHistories);
    }

    /**
     * Show the form for creating a new AccountLocationHistory.
     *
     * @return Response
     */
    public function create()
    {
        return view('account_location_histories.create');
    }

    /**
     * Store a newly created AccountLocationHistory in storage.
     *
     * @param CreateAccountLocationHistoryRequest $request
     *
     * @return Response
     */
    public function store(CreateAccountLocationHistoryRequest $request)
    {
        $input = $request->all();

        $accountLocationHistory = $this->accountLocationHistoryRepository->create($input);

        Flash::success('Account Location History saved successfully.');

        return redirect(route('accountLocationHistories.index'));
    }

    /**
     * Display the specified AccountLocationHistory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $accountLocationHistory = $this->accountLocationHistoryRepository->find($id);

        if (empty($accountLocationHistory)) {
            Flash::error('Account Location History not found');

            return redirect(route('accountLocationHistories.index'));
        }

        return view('account_location_histories.show')->with('accountLocationHistory', $accountLocationHistory);
    }

    /**
     * Show the form for editing the specified AccountLocationHistory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $accountLocationHistory = $this->accountLocationHistoryRepository->find($id);

        if (empty($accountLocationHistory)) {
            Flash::error('Account Location History not found');

            return redirect(route('accountLocationHistories.index'));
        }

        return view('account_location_histories.edit')->with('accountLocationHistory', $accountLocationHistory);
    }

    /**
     * Update the specified AccountLocationHistory in storage.
     *
     * @param int $id
     * @param UpdateAccountLocationHistoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAccountLocationHistoryRequest $request)
    {
        $accountLocationHistory = $this->accountLocationHistoryRepository->find($id);

        if (empty($accountLocationHistory)) {
            Flash::error('Account Location History not found');

            return redirect(route('accountLocationHistories.index'));
        }

        $accountLocationHistory = $this->accountLocationHistoryRepository->update($request->all(), $id);

        Flash::success('Account Location History updated successfully.');

        return redirect(route('accountLocationHistories.index'));
    }

    /**
     * Remove the specified AccountLocationHistory from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $accountLocationHistory = $this->accountLocationHistoryRepository->find($id);

        if (empty($accountLocationHistory)) {
            Flash::error('Account Location History not found');

            return redirect(route('accountLocationHistories.index'));
        }

        $this->accountLocationHistoryRepository->delete($id);

        Flash::success('Account Location History deleted successfully.');

        return redirect(route('accountLocationHistories.index'));
    }
}
