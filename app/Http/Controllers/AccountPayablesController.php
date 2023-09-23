<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountPayablesRequest;
use App\Http\Requests\UpdateAccountPayablesRequest;
use App\Repositories\AccountPayablesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AccountPayablesController extends AppBaseController
{
    /** @var  AccountPayablesRepository */
    private $accountPayablesRepository;

    public function __construct(AccountPayablesRepository $accountPayablesRepo)
    {
        $this->middleware('auth');
        $this->accountPayablesRepository = $accountPayablesRepo;
    }

    /**
     * Display a listing of the AccountPayables.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $accountPayables = $this->accountPayablesRepository->all();

        return view('account_payables.index')
            ->with('accountPayables', $accountPayables);
    }

    /**
     * Show the form for creating a new AccountPayables.
     *
     * @return Response
     */
    public function create()
    {
        return view('account_payables.create');
    }

    /**
     * Store a newly created AccountPayables in storage.
     *
     * @param CreateAccountPayablesRequest $request
     *
     * @return Response
     */
    public function store(CreateAccountPayablesRequest $request)
    {
        $input = $request->all();

        $accountPayables = $this->accountPayablesRepository->create($input);

        Flash::success('Account Payables saved successfully.');

        return redirect(route('accountPayables.index'));
    }

    /**
     * Display the specified AccountPayables.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $accountPayables = $this->accountPayablesRepository->find($id);

        if (empty($accountPayables)) {
            Flash::error('Account Payables not found');

            return redirect(route('accountPayables.index'));
        }

        return view('account_payables.show')->with('accountPayables', $accountPayables);
    }

    /**
     * Show the form for editing the specified AccountPayables.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $accountPayables = $this->accountPayablesRepository->find($id);

        if (empty($accountPayables)) {
            Flash::error('Account Payables not found');

            return redirect(route('accountPayables.index'));
        }

        return view('account_payables.edit')->with('accountPayables', $accountPayables);
    }

    /**
     * Update the specified AccountPayables in storage.
     *
     * @param int $id
     * @param UpdateAccountPayablesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAccountPayablesRequest $request)
    {
        $accountPayables = $this->accountPayablesRepository->find($id);

        if (empty($accountPayables)) {
            Flash::error('Account Payables not found');

            return redirect(route('accountPayables.index'));
        }

        $accountPayables = $this->accountPayablesRepository->update($request->all(), $id);

        Flash::success('Account Payables updated successfully.');

        return redirect(route('accountPayables.index'));
    }

    /**
     * Remove the specified AccountPayables from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $accountPayables = $this->accountPayablesRepository->find($id);

        if (empty($accountPayables)) {
            Flash::error('Account Payables not found');

            return redirect(route('accountPayables.index'));
        }

        $this->accountPayablesRepository->delete($id);

        Flash::success('Account Payables deleted successfully.');

        return redirect(route('accountPayables.index'));
    }
}
