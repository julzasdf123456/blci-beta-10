<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDistributionSystemLossRequest;
use App\Http\Requests\UpdateDistributionSystemLossRequest;
use App\Repositories\DistributionSystemLossRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\DistributionSystemLoss;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class DistributionSystemLossController extends AppBaseController
{
    /** @var  DistributionSystemLossRepository */
    private $distributionSystemLossRepository;

    public function __construct(DistributionSystemLossRepository $distributionSystemLossRepo)
    {
        $this->middleware('auth');
        $this->distributionSystemLossRepository = $distributionSystemLossRepo;
    }

    /**
     * Display a listing of the DistributionSystemLoss.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $distributionSystemLosses = $this->distributionSystemLossRepository->all();

        return view('distribution_system_losses.index')
            ->with('distributionSystemLosses', $distributionSystemLosses);
    }

    /**
     * Show the form for creating a new DistributionSystemLoss.
     *
     * @return Response
     */
    public function create()
    {
        return view('distribution_system_losses.create');
    }

    /**
     * Store a newly created DistributionSystemLoss in storage.
     *
     * @param CreateDistributionSystemLossRequest $request
     *
     * @return Response
     */
    public function store(CreateDistributionSystemLossRequest $request)
    {
        $input = $request->all();

        $salesRep = DistributionSystemLoss::where('ServicePeriod', $input['ServicePeriod'])->first();
        if ($salesRep != null) {
            $distributionSystemLoss = $this->distributionSystemLossRepository->update($request->all(), $salesRep->id);
        } else {
            $distributionSystemLoss = $this->distributionSystemLossRepository->create($input);
        }
        Flash::success('Distribution System Loss saved successfully.');

        return redirect(route('kwhSales.index'));        
    }

    /**
     * Display the specified DistributionSystemLoss.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $distributionSystemLoss = $this->distributionSystemLossRepository->find($id);

        if (empty($distributionSystemLoss)) {
            Flash::error('Distribution System Loss not found');

            return redirect(route('distributionSystemLosses.index'));
        }

        return view('distribution_system_losses.show')->with('distributionSystemLoss', $distributionSystemLoss);
    }

    /**
     * Show the form for editing the specified DistributionSystemLoss.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $distributionSystemLoss = $this->distributionSystemLossRepository->find($id);

        $data = DB::table('Billing_Bills')
            ->select(
                DB::raw("(SELECT SUM(TRY_CAST(Billing_Bills.KwhUsed AS decimal(10,2))) FROM Billing_Bills LEFT JOIN Billing_ServiceAccounts ON Billing_Bills.AccountNumber=Billing_ServiceAccounts.id WHERE Billing_Bills.ServicePeriod='" . $distributionSystemLoss->ServicePeriod . "' AND Billing_Bills.AdjustmentType IS NULL) AS TotalKwhConsumption"),
                DB::raw("(SELECT SUM(TRY_CAST(Billing_Bills.KwhUsed AS decimal(10,2))) FROM Billing_Bills LEFT JOIN Billing_ServiceAccounts ON Billing_Bills.AccountNumber=Billing_ServiceAccounts.id WHERE Billing_Bills.ServicePeriod='" . $distributionSystemLoss->ServicePeriod . "' AND Billing_Bills.AdjustmentType IS NOT NULL) AS Adjustments"),)
            ->limit(1)
            ->first();

        if (empty($distributionSystemLoss)) {
            Flash::error('Distribution System Loss not found');

            return redirect(route('distributionSystemLosses.index'));
        }

        return view('distribution_system_losses.edit', [
            'distributionSystemLoss' => $distributionSystemLoss,
            'data' => $data,
        ]);
    }

    /**
     * Update the specified DistributionSystemLoss in storage.
     *
     * @param int $id
     * @param UpdateDistributionSystemLossRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDistributionSystemLossRequest $request)
    {
        $distributionSystemLoss = $this->distributionSystemLossRepository->find($id);

        if (empty($distributionSystemLoss)) {
            Flash::error('Distribution System Loss not found');

            return redirect(route('distributionSystemLosses.index'));
        }

        $distributionSystemLoss = $this->distributionSystemLossRepository->update($request->all(), $id);

        Flash::success('Distribution System Loss updated successfully.');

        return redirect(route('kwhSales.view-sales', [$distributionSystemLoss->id]));
    }

    /**
     * Remove the specified DistributionSystemLoss from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $distributionSystemLoss = $this->distributionSystemLossRepository->find($id);

        if (empty($distributionSystemLoss)) {
            Flash::error('Distribution System Loss not found');

            return redirect(route('distributionSystemLosses.index'));
        }

        $this->distributionSystemLossRepository->delete($id);

        Flash::success('Distribution System Loss deleted successfully.');

        return redirect(route('distributionSystemLosses.index'));
    }
}
