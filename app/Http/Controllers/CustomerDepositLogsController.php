<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCustomerDepositLogsRequest;
use App\Http\Requests\UpdateCustomerDepositLogsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\CustomerDepositLogsRepository;
use Illuminate\Http\Request;
use Flash;

class CustomerDepositLogsController extends AppBaseController
{
    /** @var CustomerDepositLogsRepository $customerDepositLogsRepository*/
    private $customerDepositLogsRepository;

    public function __construct(CustomerDepositLogsRepository $customerDepositLogsRepo)
    {
        $this->middleware('auth');
        $this->customerDepositLogsRepository = $customerDepositLogsRepo;
    }

    /**
     * Display a listing of the CustomerDepositLogs.
     */
    public function index(Request $request)
    {
        $customerDepositLogs = $this->customerDepositLogsRepository->paginate(10);

        return view('customer_deposit_logs.index')
            ->with('customerDepositLogs', $customerDepositLogs);
    }

    /**
     * Show the form for creating a new CustomerDepositLogs.
     */
    public function create()
    {
        return view('customer_deposit_logs.create');
    }

    /**
     * Store a newly created CustomerDepositLogs in storage.
     */
    public function store(CreateCustomerDepositLogsRequest $request)
    {
        $input = $request->all();

        $customerDepositLogs = $this->customerDepositLogsRepository->create($input);

        Flash::success('Customer Deposit Logs saved successfully.');

        return redirect(route('customerDepositLogs.index'));
    }

    /**
     * Display the specified CustomerDepositLogs.
     */
    public function show($id)
    {
        $customerDepositLogs = $this->customerDepositLogsRepository->find($id);

        if (empty($customerDepositLogs)) {
            Flash::error('Customer Deposit Logs not found');

            return redirect(route('customerDepositLogs.index'));
        }

        return view('customer_deposit_logs.show')->with('customerDepositLogs', $customerDepositLogs);
    }

    /**
     * Show the form for editing the specified CustomerDepositLogs.
     */
    public function edit($id)
    {
        $customerDepositLogs = $this->customerDepositLogsRepository->find($id);

        if (empty($customerDepositLogs)) {
            Flash::error('Customer Deposit Logs not found');

            return redirect(route('customerDepositLogs.index'));
        }

        return view('customer_deposit_logs.edit')->with('customerDepositLogs', $customerDepositLogs);
    }

    /**
     * Update the specified CustomerDepositLogs in storage.
     */
    public function update($id, UpdateCustomerDepositLogsRequest $request)
    {
        $customerDepositLogs = $this->customerDepositLogsRepository->find($id);

        if (empty($customerDepositLogs)) {
            Flash::error('Customer Deposit Logs not found');

            return redirect(route('customerDepositLogs.index'));
        }

        $customerDepositLogs = $this->customerDepositLogsRepository->update($request->all(), $id);

        Flash::success('Customer Deposit Logs updated successfully.');

        return redirect(route('customerDepositLogs.index'));
    }

    /**
     * Remove the specified CustomerDepositLogs from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $customerDepositLogs = $this->customerDepositLogsRepository->find($id);

        if (empty($customerDepositLogs)) {
            Flash::error('Customer Deposit Logs not found');

            return redirect(route('customerDepositLogs.index'));
        }

        $this->customerDepositLogsRepository->delete($id);

        Flash::success('Customer Deposit Logs deleted successfully.');

        return redirect(route('customerDepositLogs.index'));
    }
}
