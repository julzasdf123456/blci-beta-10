<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillsOfMaterialsSummaryRequest;
use App\Http\Requests\UpdateBillsOfMaterialsSummaryRequest;
use App\Repositories\BillsOfMaterialsSummaryRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\BillsOfMaterialsSummary;
use Illuminate\Http\Request;
use Flash;
use Response;

class BillsOfMaterialsSummaryController extends AppBaseController
{
    /** @var  BillsOfMaterialsSummaryRepository */
    private $billsOfMaterialsSummaryRepository;

    public function __construct(BillsOfMaterialsSummaryRepository $billsOfMaterialsSummaryRepo)
    {
        $this->middleware('auth');
        $this->billsOfMaterialsSummaryRepository = $billsOfMaterialsSummaryRepo;
    }

    /**
     * Display a listing of the BillsOfMaterialsSummary.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $billsOfMaterialsSummaries = $this->billsOfMaterialsSummaryRepository->all();

        return view('bills_of_materials_summaries.index')
            ->with('billsOfMaterialsSummaries', $billsOfMaterialsSummaries);
    }

    /**
     * Show the form for creating a new BillsOfMaterialsSummary.
     *
     * @return Response
     */
    public function create()
    {
        return view('bills_of_materials_summaries.create');
    }

    /**
     * Store a newly created BillsOfMaterialsSummary in storage.
     *
     * @param CreateBillsOfMaterialsSummaryRequest $request
     *
     * @return Response
     */
    public function store(CreateBillsOfMaterialsSummaryRequest $request)
    {
        $input = $request->all();

        $billsOfMaterialsSummary = $this->billsOfMaterialsSummaryRepository->create($input);

        Flash::success('Bills Of Materials Summary saved successfully.');

        return redirect(route('billsOfMaterialsSummaries.index'));
    }

    /**
     * Display the specified BillsOfMaterialsSummary.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $billsOfMaterialsSummary = $this->billsOfMaterialsSummaryRepository->find($id);

        if (empty($billsOfMaterialsSummary)) {
            Flash::error('Bills Of Materials Summary not found');

            return redirect(route('billsOfMaterialsSummaries.index'));
        }

        return view('bills_of_materials_summaries.show')->with('billsOfMaterialsSummary', $billsOfMaterialsSummary);
    }

    /**
     * Show the form for editing the specified BillsOfMaterialsSummary.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $billsOfMaterialsSummary = $this->billsOfMaterialsSummaryRepository->find($id);

        if (empty($billsOfMaterialsSummary)) {
            Flash::error('Bills Of Materials Summary not found');

            return redirect(route('billsOfMaterialsSummaries.index'));
        }

        return view('bills_of_materials_summaries.edit')->with('billsOfMaterialsSummary', $billsOfMaterialsSummary);
        
    }

    /**
     * Update the specified BillsOfMaterialsSummary in storage.
     *
     * @param int $id
     * @param UpdateBillsOfMaterialsSummaryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillsOfMaterialsSummaryRequest $request)
    {
        $billsOfMaterialsSummary = $this->billsOfMaterialsSummaryRepository->find($id);
        $boMSummary = BillsOfMaterialsSummary::find($id);

        if (empty($billsOfMaterialsSummary)) {
            Flash::error('Bills Of Materials Summary not found');

            return redirect(route('billsOfMaterialsSummaries.index'));
        }

        // CONFIGURE EXCLUSION OF TRANSFORMER LABOR COST FROM CHECKBOX
        if ($request['ExcludeTransformerLaborCost'] == null) {
            $request['ExcludeTransformerLaborCost'] = null;

            // RECALCULATE TransformerLaborCost BASED ON NEW TransformerLaborCostPercentage
            $request['TransformerLaborCost'] = floatval($boMSummary->TransformerTotal) * floatval($request['TransformerLaborCostPercentage']);

            $request['LaborCost'] = floatval($boMSummary->MaterialLaborCost) + floatval($request['TransformerLaborCost']);
        } else {
            if ($request['ExcludeTransformerLaborCost'] == 'Yes') {
                $request['TransformerLaborCost'] = null;

                $request['LaborCost'] = $boMSummary->MaterialLaborCost;
            } else {
                // RECALCULATE TransformerLaborCost BASED ON NEW TransformerLaborCostPercentage
                $request['TransformerLaborCost'] = floatval($boMSummary->TransformerTotal) * floatval($request['TransformerLaborCostPercentage']);

                $request['LaborCost'] = floatval($boMSummary->MaterialLaborCost) + floatval($request['TransformerLaborCost']);                
            }          
        }

        // RECALCULATE MaterialsLaborCost  BASED ON NEW MaterialsLaborCostPercentage
        $request['MaterialLaborCost'] = (floatval($boMSummary->SubTotal) - floatval($boMSummary->TransformerTotal)) * floatval($request['MaterialLaborCostPercentage']);

        // RECALCULATE HandlingCost  BASED ON NEW HandlingCostPercentage
        $request['HandlingCost'] = (floatval($boMSummary->LaborCost)) * floatval($request['HandlingCostPercentage']);

        // RECALCULATE Total
        $total = floatval($billsOfMaterialsSummary->SubTotal) + floatval($request['HandlingCost']) + floatval($request['LaborCost']);
        $vat = BillsOfMaterialsSummary::getVat() * floatval($total);
        $request['Total'] = $total + $vat;
        $request['TotalVAT'] = $vat;

        $billsOfMaterialsSummary = $this->billsOfMaterialsSummaryRepository->update($request->all(), $id);

        Flash::success('Bills Of Materials Summary updated successfully.');

        return redirect(route('serviceConnections.quotation-summary', [$request['ServiceConnectionId']]));
    }

    /**
     * Remove the specified BillsOfMaterialsSummary from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $billsOfMaterialsSummary = $this->billsOfMaterialsSummaryRepository->find($id);

        if (empty($billsOfMaterialsSummary)) {
            Flash::error('Bills Of Materials Summary not found');

            return redirect(route('billsOfMaterialsSummaries.index'));
        }

        $this->billsOfMaterialsSummaryRepository->delete($id);

        Flash::success('Bills Of Materials Summary deleted successfully.');

        return redirect(route('billsOfMaterialsSummaries.index'));
    }
}
