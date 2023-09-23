<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountNameHistoryRequest;
use App\Http\Requests\UpdateAccountNameHistoryRequest;
use App\Repositories\AccountNameHistoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AccountNameHistoryController extends AppBaseController
{
    /** @var  AccountNameHistoryRepository */
    private $accountNameHistoryRepository;

    public function __construct(AccountNameHistoryRepository $accountNameHistoryRepo)
    {
        $this->middleware('auth');
        $this->accountNameHistoryRepository = $accountNameHistoryRepo;
    }

    /**
     * Display a listing of the AccountNameHistory.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $accountNameHistories = $this->accountNameHistoryRepository->all();

        return view('account_name_histories.index')
            ->with('accountNameHistories', $accountNameHistories);
    }

    /**
     * Show the form for creating a new AccountNameHistory.
     *
     * @return Response
     */
    public function create()
    {
        return view('account_name_histories.create');
    }

    /**
     * Store a newly created AccountNameHistory in storage.
     *
     * @param CreateAccountNameHistoryRequest $request
     *
     * @return Response
     */
    public function store(CreateAccountNameHistoryRequest $request)
    {
        $input = $request->all();

        $accountNameHistory = $this->accountNameHistoryRepository->create($input);

        Flash::success('Account Name History saved successfully.');

        return redirect(route('accountNameHistories.index'));
    }

    /**
     * Display the specified AccountNameHistory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $accountNameHistory = $this->accountNameHistoryRepository->find($id);

        if (empty($accountNameHistory)) {
            Flash::error('Account Name History not found');

            return redirect(route('accountNameHistories.index'));
        }

        return view('account_name_histories.show')->with('accountNameHistory', $accountNameHistory);
    }

    /**
     * Show the form for editing the specified AccountNameHistory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $accountNameHistory = $this->accountNameHistoryRepository->find($id);

        if (empty($accountNameHistory)) {
            Flash::error('Account Name History not found');

            return redirect(route('accountNameHistories.index'));
        }

        return view('account_name_histories.edit')->with('accountNameHistory', $accountNameHistory);
    }

    /**
     * Update the specified AccountNameHistory in storage.
     *
     * @param int $id
     * @param UpdateAccountNameHistoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAccountNameHistoryRequest $request)
    {
        $accountNameHistory = $this->accountNameHistoryRepository->find($id);

        if (empty($accountNameHistory)) {
            Flash::error('Account Name History not found');

            return redirect(route('accountNameHistories.index'));
        }

        $accountNameHistory = $this->accountNameHistoryRepository->update($request->all(), $id);

        Flash::success('Account Name History updated successfully.');

        return redirect(route('accountNameHistories.index'));
    }

    /**
     * Remove the specified AccountNameHistory from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $accountNameHistory = $this->accountNameHistoryRepository->find($id);

        if (empty($accountNameHistory)) {
            Flash::error('Account Name History not found');

            return redirect(route('accountNameHistories.index'));
        }

        $this->accountNameHistoryRepository->delete($id);

        Flash::success('Account Name History deleted successfully.');

        return redirect(route('accountNameHistories.index'));
    }
}
