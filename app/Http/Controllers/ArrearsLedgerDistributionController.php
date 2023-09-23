<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArrearsLedgerDistributionRequest;
use App\Http\Requests\UpdateArrearsLedgerDistributionRequest;
use App\Repositories\ArrearsLedgerDistributionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ArrearsLedgerDistributionController extends AppBaseController
{
    /** @var  ArrearsLedgerDistributionRepository */
    private $arrearsLedgerDistributionRepository;

    public function __construct(ArrearsLedgerDistributionRepository $arrearsLedgerDistributionRepo)
    {
        $this->middleware('auth');
        $this->arrearsLedgerDistributionRepository = $arrearsLedgerDistributionRepo;
    }

    /**
     * Display a listing of the ArrearsLedgerDistribution.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $arrearsLedgerDistributions = $this->arrearsLedgerDistributionRepository->all();

        return view('arrears_ledger_distributions.index')
            ->with('arrearsLedgerDistributions', $arrearsLedgerDistributions);
    }

    /**
     * Show the form for creating a new ArrearsLedgerDistribution.
     *
     * @return Response
     */
    public function create()
    {
        return view('arrears_ledger_distributions.create');
    }

    /**
     * Store a newly created ArrearsLedgerDistribution in storage.
     *
     * @param CreateArrearsLedgerDistributionRequest $request
     *
     * @return Response
     */
    public function store(CreateArrearsLedgerDistributionRequest $request)
    {
        $input = $request->all();

        $arrearsLedgerDistribution = $this->arrearsLedgerDistributionRepository->create($input);

        Flash::success('Arrears Ledger Distribution saved successfully.');

        return redirect(route('arrearsLedgerDistributions.index'));
    }

    /**
     * Display the specified ArrearsLedgerDistribution.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $arrearsLedgerDistribution = $this->arrearsLedgerDistributionRepository->find($id);

        if (empty($arrearsLedgerDistribution)) {
            Flash::error('Arrears Ledger Distribution not found');

            return redirect(route('arrearsLedgerDistributions.index'));
        }

        return view('arrears_ledger_distributions.show')->with('arrearsLedgerDistribution', $arrearsLedgerDistribution);
    }

    /**
     * Show the form for editing the specified ArrearsLedgerDistribution.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $arrearsLedgerDistribution = $this->arrearsLedgerDistributionRepository->find($id);

        if (empty($arrearsLedgerDistribution)) {
            Flash::error('Arrears Ledger Distribution not found');

            return redirect(route('arrearsLedgerDistributions.index'));
        }

        return view('arrears_ledger_distributions.edit')->with('arrearsLedgerDistribution', $arrearsLedgerDistribution);
    }

    /**
     * Update the specified ArrearsLedgerDistribution in storage.
     *
     * @param int $id
     * @param UpdateArrearsLedgerDistributionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateArrearsLedgerDistributionRequest $request)
    {
        $arrearsLedgerDistribution = $this->arrearsLedgerDistributionRepository->find($id);

        if (empty($arrearsLedgerDistribution)) {
            Flash::error('Arrears Ledger Distribution not found');

            return redirect(route('arrearsLedgerDistributions.index'));
        }

        $arrearsLedgerDistribution = $this->arrearsLedgerDistributionRepository->update($request->all(), $id);

        Flash::success('Arrears Ledger Distribution updated successfully.');

        return redirect(route('arrearsLedgerDistributions.index'));
    }

    /**
     * Remove the specified ArrearsLedgerDistribution from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $arrearsLedgerDistribution = $this->arrearsLedgerDistributionRepository->find($id);

        if (empty($arrearsLedgerDistribution)) {
            Flash::error('Arrears Ledger Distribution not found');

            return redirect(route('arrearsLedgerDistributions.index'));
        }

        $this->arrearsLedgerDistributionRepository->delete($id);

        Flash::success('Arrears Ledger Distribution deleted successfully.');

        return redirect(route('arrearsLedgerDistributions.index'));
    }
}
