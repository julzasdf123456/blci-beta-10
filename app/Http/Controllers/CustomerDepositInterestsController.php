<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCustomerDepositInterestsRequest;
use App\Http\Requests\UpdateCustomerDepositInterestsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\CustomerDepositInterestsRepository;
use Illuminate\Http\Request;
use Flash;

class CustomerDepositInterestsController extends AppBaseController
{
    /** @var CustomerDepositInterestsRepository $customerDepositInterestsRepository*/
    private $customerDepositInterestsRepository;

    public function __construct(CustomerDepositInterestsRepository $customerDepositInterestsRepo)
    {
        $this->middleware('auth');
        $this->customerDepositInterestsRepository = $customerDepositInterestsRepo;
    }

    /**
     * Display a listing of the CustomerDepositInterests.
     */
    public function index(Request $request)
    {
        $customerDepositInterests = $this->customerDepositInterestsRepository->paginate(10);

        return view('customer_deposit_interests.index')
            ->with('customerDepositInterests', $customerDepositInterests);
    }

    /**
     * Show the form for creating a new CustomerDepositInterests.
     */
    public function create()
    {
        return view('customer_deposit_interests.create');
    }

    /**
     * Store a newly created CustomerDepositInterests in storage.
     */
    public function store(CreateCustomerDepositInterestsRequest $request)
    {
        $input = $request->all();

        $customerDepositInterests = $this->customerDepositInterestsRepository->create($input);

        Flash::success('Customer Deposit Interests saved successfully.');

        return redirect(route('customerDepositInterests.index'));
    }

    /**
     * Display the specified CustomerDepositInterests.
     */
    public function show($id)
    {
        $customerDepositInterests = $this->customerDepositInterestsRepository->find($id);

        if (empty($customerDepositInterests)) {
            Flash::error('Customer Deposit Interests not found');

            return redirect(route('customerDepositInterests.index'));
        }

        return view('customer_deposit_interests.show')->with('customerDepositInterests', $customerDepositInterests);
    }

    /**
     * Show the form for editing the specified CustomerDepositInterests.
     */
    public function edit($id)
    {
        $customerDepositInterests = $this->customerDepositInterestsRepository->find($id);

        if (empty($customerDepositInterests)) {
            Flash::error('Customer Deposit Interests not found');

            return redirect(route('customerDepositInterests.index'));
        }

        return view('customer_deposit_interests.edit')->with('customerDepositInterests', $customerDepositInterests);
    }

    /**
     * Update the specified CustomerDepositInterests in storage.
     */
    public function update($id, UpdateCustomerDepositInterestsRequest $request)
    {
        $customerDepositInterests = $this->customerDepositInterestsRepository->find($id);

        if (empty($customerDepositInterests)) {
            Flash::error('Customer Deposit Interests not found');

            return redirect(route('customerDepositInterests.index'));
        }

        $customerDepositInterests = $this->customerDepositInterestsRepository->update($request->all(), $id);

        Flash::success('Customer Deposit Interests updated successfully.');

        return redirect(route('customerDepositInterests.index'));
    }

    /**
     * Remove the specified CustomerDepositInterests from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $customerDepositInterests = $this->customerDepositInterestsRepository->find($id);

        if (empty($customerDepositInterests)) {
            Flash::error('Customer Deposit Interests not found');

            return redirect(route('customerDepositInterests.index'));
        }

        $this->customerDepositInterestsRepository->delete($id);

        Flash::success('Customer Deposit Interests deleted successfully.');

        return redirect(route('customerDepositInterests.index'));
    }
}
