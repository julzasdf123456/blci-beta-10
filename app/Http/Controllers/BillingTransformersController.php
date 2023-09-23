<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillingTransformersRequest;
use App\Http\Requests\UpdateBillingTransformersRequest;
use App\Repositories\BillingTransformersRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\ServiceAccounts;
use App\Models\ServiceConnections;
use Illuminate\Support\Facades\Auth;
use App\Models\IDGenerator;
use Flash;
use Response;

class BillingTransformersController extends AppBaseController
{
    /** @var  BillingTransformersRepository */
    private $billingTransformersRepository;

    public function __construct(BillingTransformersRepository $billingTransformersRepo)
    {
        $this->middleware('auth');
        $this->billingTransformersRepository = $billingTransformersRepo;
    }

    /**
     * Display a listing of the BillingTransformers.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $billingTransformers = $this->billingTransformersRepository->all();

        return view('billing_transformers.index')
            ->with('billingTransformers', $billingTransformers);
    }

    /**
     * Show the form for creating a new BillingTransformers.
     *
     * @return Response
     */
    public function create()
    {
        return view('billing_transformers.create');
    }

    /**
     * Store a newly created BillingTransformers in storage.
     *
     * @param CreateBillingTransformersRequest $request
     *
     * @return Response
     */
    public function store(CreateBillingTransformersRequest $request)
    {
        $input = $request->all();
        $input['id'] = IDGenerator::generateRandString(30);
        $billingTransformers = $this->billingTransformersRepository->create($input);

        // UPDATE SERVICE ACCOUNT
        $serviceAccount = ServiceAccounts::find($request['ServiceAccountId']);
        $serviceAccount->Coreloss = $request['Coreloss'];
        $serviceAccount->UserId = Auth::id();
        $serviceAccount->Main = $request['Main'];
        $serviceAccount->Organization = $request['BAPA'];
        $serviceAccount->OrganizationParentAccount = $request['OrganizationParentAccount'];
        $serviceAccount->TransformerDetailsId = $input['id'];
        $serviceAccount->Locked = 'Yes';
        $serviceAccount->Evat5Percent = $request['Evat5Percent'];
        $serviceAccount->Ewt2Percent = $request['Ewt2Percent'];
        $serviceAccount->save();

        // UPDATE SERVICE CONNECTION STATUS
        $serviceConnection = ServiceConnections::find($serviceAccount->ServiceConnectionId);
        $serviceConnection->Status = 'Closed';
        $serviceConnection->save();

        Flash::success('Account migrated successfully.');

        return redirect(route('serviceAccounts.pending-accounts'))->with('success', 'Account migrated!');
    }

    /**
     * Display the specified BillingTransformers.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $billingTransformers = $this->billingTransformersRepository->find($id);

        if (empty($billingTransformers)) {
            Flash::error('Billing Transformers not found');

            return redirect(route('billingTransformers.index'));
        }

        return view('billing_transformers.show')->with('billingTransformers', $billingTransformers);
    }

    /**
     * Show the form for editing the specified BillingTransformers.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $billingTransformers = $this->billingTransformersRepository->find($id);

        if (empty($billingTransformers)) {
            Flash::error('Billing Transformers not found');

            return redirect(route('billingTransformers.index'));
        }

        return view('billing_transformers.update_step_three')->with('billingTransformers', $billingTransformers);
    }

    /**
     * Update the specified BillingTransformers in storage.
     *
     * @param int $id
     * @param UpdateBillingTransformersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillingTransformersRequest $request)
    {
        $billingTransformers = $this->billingTransformersRepository->find($id);

        if (empty($billingTransformers)) {
            Flash::error('Billing Transformers not found');

            return redirect(route('billingTransformers.index'));
        }

        $billingTransformers = $this->billingTransformersRepository->update($request->all(), $id);

        Flash::success('Billing Transformers updated successfully.');

        return redirect(route('serviceAccounts.show', [$billingTransformers->ServiceAccountId]));
    }

    /**
     * Remove the specified BillingTransformers from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $billingTransformers = $this->billingTransformersRepository->find($id);

        if (empty($billingTransformers)) {
            Flash::error('Billing Transformers not found');

            return redirect(route('billingTransformers.index'));
        }

        $this->billingTransformersRepository->delete($id);

        Flash::success('Billing Transformers deleted successfully.');

        return redirect(route('billingTransformers.index'));
    }
    
}
