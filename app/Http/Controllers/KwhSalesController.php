<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateKwhSalesRequest;
use App\Http\Requests\UpdateKwhSalesRequest;
use App\Repositories\KwhSalesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Towns;
use App\Models\IDGenerator;
use App\Models\KwhSales;
use App\Models\Users;
use App\Models\Bills;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DistributionSystemLoss;
use App\Exports\KwhSalesTsdRoutesExport;
use App\Exports\SummaryOfSalesPerAreaExport;
use App\Exports\SummaryOfSalesExport;
use App\Exports\SummaryOfSalesPerConsumerTypeExport;
use App\Exports\SalesConsolidatedExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Flash;
use Response;

class KwhSalesController extends AppBaseController
{
    /** @var  KwhSalesRepository */
    private $kwhSalesRepository;

    public function __construct(KwhSalesRepository $kwhSalesRepo)
    {
        $this->middleware('auth');
        $this->kwhSalesRepository = $kwhSalesRepo;
    }

    /**
     * Display a listing of the KwhSales.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $kwhSales = DistributionSystemLoss::orderByDesc('ServicePeriod')->get();

        return view('kwh_sales.index', [
            'kwhSales' => $kwhSales,
        ]);
    }

    /**
     * Show the form for creating a new KwhSales.
     *
     * @return Response
     */
    public function create()
    {
        return view('kwh_sales.create');
    }

    /**
     * Store a newly created KwhSales in storage.
     *
     * @param CreateKwhSalesRequest $request
     *
     * @return Response
     */
    public function store(CreateKwhSalesRequest $request)
    {
        $input = $request->all();

        $kwhSales = $this->kwhSalesRepository->create($input);

        Flash::success('Kwh Sales saved successfully.');

        return redirect(route('kwhSales.index'));
    }

    /**
     * Display the specified KwhSales.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $kwhSales = $this->kwhSalesRepository->find($id);

        if (empty($kwhSales)) {
            Flash::error('Kwh Sales not found');

            return redirect(route('kwhSales.index'));
        }

        return view('kwh_sales.show')->with('kwhSales', $kwhSales);
    }

    /**
     * Show the form for editing the specified KwhSales.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $kwhSales = $this->kwhSalesRepository->find($id);

        if (empty($kwhSales)) {
            Flash::error('Kwh Sales not found');

            return redirect(route('kwhSales.index'));
        }

        return view('kwh_sales.edit')->with('kwhSales', $kwhSales);
    }

    /**
     * Update the specified KwhSales in storage.
     *
     * @param int $id
     * @param UpdateKwhSalesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateKwhSalesRequest $request)
    {
        $kwhSales = $this->kwhSalesRepository->find($id);

        if (empty($kwhSales)) {
            Flash::error('Kwh Sales not found');

            return redirect(route('kwhSales.index'));
        }

        $kwhSales = $this->kwhSalesRepository->update($request->all(), $id);

        Flash::success('Kwh Sales updated successfully.');

        return redirect(route('kwhSales.index'));
    }

    /**
     * Remove the specified KwhSales from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $kwhSales = $this->kwhSalesRepository->find($id);

        if (empty($kwhSales)) {
            Flash::error('Kwh Sales not found');

            return redirect(route('kwhSales.index'));
        }

        $this->kwhSalesRepository->delete($id);

        Flash::success('Kwh Sales deleted successfully.');

        return redirect(route('kwhSales.index'));
    }

    public function generateNew(Request $request) {
        $period = $request['ServicePeriod'];

        $data = DB::table('Billing_Bills')
            ->select(
                DB::raw("(SELECT SUM(TRY_CAST(Billing_Bills.KwhUsed AS decimal(10,2))) FROM Billing_Bills LEFT JOIN Billing_ServiceAccounts ON Billing_Bills.AccountNumber=Billing_ServiceAccounts.id WHERE Billing_Bills.ServicePeriod='" . $period . "') AS TotalKwhConsumption"),
                DB::raw("(SELECT SUM(TRY_CAST(Billing_Bills.KwhUsed AS decimal(10,2))) FROM Billing_Bills LEFT JOIN Billing_ServiceAccounts ON Billing_Bills.AccountNumber=Billing_ServiceAccounts.id WHERE Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.AdjustmentType IS NOT NULL) AS Adjustments"),)
            ->limit(1)
            ->first();

        return view('/kwh_sales/generate_new', [
            'data' => $data,
            'period' => $period,
        ]);
    }

    public function saveSalesReport(Request $request) {
        $periods = $request->ServicePeriod;
        foreach($periods as $key => $value) {
            $kwhSales = new KwhSales;
            $kwhSales->id = IDGenerator::generateIDandRandString();
            $kwhSales->ServicePeriod = $value;
            $kwhSales->Town = $request->Town[$key];
            $kwhSales->NoOfConsumers = $request->NoOfConsumers[$key];
            $kwhSales->ConsumedKwh = $request->ConsumedKwh[$key];
            $kwhSales->BilledKwh = $request->BilledKwh[$key];
            $kwhSales->save();
        }

        return redirect(route('kwhSales.index'));
    }

    public function viewSales($id) {
        $sales = DistributionSystemLoss::find($id);
        return view('/kwh_sales/view_sales', [
            'sales' => $sales,
        ]);
    }

    public function printReport($id) {
        $sales = DistributionSystemLoss::find($id);

        return view('/kwh_sales/print_report', [
            'sales' => $sales,
        ]);
    }

    public function salesDistribution() {
        $periods = DB::table('Billing_Bills')
            ->select('ServicePeriod')
            ->whereNotNull('ServicePeriod')
            ->groupBy('ServicePeriod')
            ->orderByDesc('ServicePeriod')
            ->get();

        return view('/kwh_sales/sales_distribution', [
            'periods' => $periods
        ]);
    }

    public function salesDistributionView($period) {
        $sales = DistributionSystemLoss::where('ServicePeriod', $period)->first();
        
        if ($sales != null && $sales->Status=='CLOSED') {
            $allData = DB::table('CRM_Towns AS t')
                ->select('t.Town',
                    DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS ConsumerCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL', 'COMMERCIAL HIGH VOLTAGE')) AS Commercial"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL', 'INDUSTRIAL HIGH VOLTAGE')) AS Industrial"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING', 'PUBLIC BUILDING HIGH VOLTAGE')) AS PublicBldg"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS Streetlights"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS DemandKwh"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltKwh"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS KwhSold"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS TotalAmount"),
                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS Missionary"),
                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS Environmental"),
                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS NPC"),
                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS StrandedCC"),
                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS Redci"),
                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS FITAll"),
                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS RPT"),
                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS RFSC"),
                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS GenVat"),
                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS TransVat"),
                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS SysLossVat"),
                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS DistVat"),
                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
                )
                ->orderBy("t.id")
                ->get();

            $demandTotal = DB::table('Billing_Bills')
                ->select(
                    DB::raw("(SELECT SUM(TRY_CAST(b.DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE ForCancellation='SALES_REPORT' AND b.ServicePeriod='" . $period . "') AS Demand"),
                )
                ->first();

            $coopConsumptions = DB::table('Billing_BillsOriginal')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereRaw("Billing_ServiceAccounts.Item1='Yes' AND Billing_BillsOriginal.ServicePeriod='" . $period . "' AND UnlockedBy='SALES_CLOSED_COOP_CONSUMPTION'")
                ->select(
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_BillsOriginal.*'
                )
                ->orderBy('OldAccountNo')
                ->get();
        } else {
            $allData = DB::table('CRM_Towns AS t')
                ->select('t.Town',
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS ConsumerCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'COMMERCIAL HIGH VOLTAGE')) AS Commercial"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('INDUSTRIAL', 'INDUSTRIAL HIGH VOLTAGE')) AS Industrial"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('PUBLIC BUILDING', 'PUBLIC BUILDING HIGH VOLTAGE')) AS PublicBldg"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('STREET LIGHTS')) AS Streetlights"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS DemandKwh"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltKwh"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS KwhSold"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS Missionary"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS Environmental"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS NPC"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS StrandedCC"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS Redci"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS FITAll"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.RealPropertyTax AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS RPT"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.RFSC AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS RFSC"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.GenerationVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS GenVat"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.TransmissionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS TransVat"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.SystemLossVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS SysLossVat"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.DistributionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS DistVat"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
                )
                ->orderBy("t.id")
                ->get();

            $demandTotal = DB::table('Billing_Bills')
                ->select(
                    DB::raw("(SELECT SUM(TRY_CAST(b.DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "') AS Demand"),
                )
                ->first();

            $coopConsumptions = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->whereRaw("Billing_ServiceAccounts.Item1='Yes' AND Billing_Bills.ServicePeriod='" . $period . "'")
                    ->select(
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_Bills.*'
                    )
                    ->orderBy('OldAccountNo')
                    ->get();
        }

        return view('/kwh_sales/sales_distribution_view', [
            'period' => $period,
            'allData' => $allData,
            'sales' => $sales,
            'demandTotal' => $demandTotal,
            'coopConsumptions' => $coopConsumptions,
        ]);
    }

    public function consolidatedPerTown($period, Request $request) {
        $town = $request['Town'];
        $towns = Towns::orderBy('id')->get();

        $sales = DistributionSystemLoss::where('ServicePeriod', $period)->first();

        if ($sales != null && $sales->Status=='CLOSED') {
            return view('/kwh_sales/closed_consolidated_per_town', [
                'town' => $town,
                'period' => $period,
                'towns' => $towns,
            ]);
        } else {
            return view('/kwh_sales/attach_consolidated_per_town', [
                'town' => $town,
                'period' => $period,
                'towns' => $towns,
            ]);
        }
    }

    public function summaryOfSales($period) {
        $sales = DistributionSystemLoss::where('ServicePeriod', $period)->first();

        return view('/kwh_sales/summary_of_sales', [
            'period' => $period,
            'sales' => $sales,
        ]);
    }

    public function printSummaryOfSales($period) {
        $sales = DistributionSystemLoss::where('ServicePeriod', $period)->first();

        return view('/kwh_sales/print_summary_of_sales', [
            'period' => $period,
            'sales' => $sales,
        ]);
    }

    public function dashboardGetAnnualSalesGraph(Request $request) {
        $year = $request['Year'] != null ? $request['Year'] : date('Y');
        $prevYear = date('Y', strtotime($year . ' 1 year'));

        $data = DB::table('Reports_DistributionSystemLoss')
            ->select(
                'ServicePeriod',
                DB::raw("TRY_CAST(TotalEnergyOutput AS DECIMAL(16,2)) AS KwhSales"),
                'TotalSystemLossPercentage',
            )
            ->orderBy('ServicePeriod')
            ->limit(12)
            ->get();

        return response()->json($data, 200);
    }

    public function dashboardGetAnnualSalesPieGraph(Request $request) {
        $data = DB::table('Reports_DistributionSystemLoss')
            ->select('Reports_DistributionSystemLoss.*')
            ->orderBy('ServicePeriod')
            ->first();

        return response()->json($data, 200);
    }

    public function kwhSalesExpanded(Request $request) {
        $billingMonths = DB::table('Billing_Bills')
            ->select('ServicePeriod')
            ->whereNotNull('ServicePeriod')
            ->groupBy('ServicePeriod')
            ->orderByDesc('ServicePeriod')
            ->get();

        $period = $request['ServicePeriod'];
        $town = $request['Town'];

        $sales = DistributionSystemLoss::where('ServicePeriod', $period)->first();

        if ($sales != null && $sales->Status=='CLOSED') {
            if ($town != null && $period != null) {
                if ($town == 'All') {
                    $data = DB::table('Billing_BillsOriginal')
                        ->whereRaw("UnlockedBy='SALES_CLOSED_TSD_EXPANDED' AND ServicePeriod='" . $period . "'")
                        ->whereNotNull('AccountNumber')
                        ->select('AccountNumber AS AreaCode', 
                            'KwhAmount AS Town',
                            DB::raw("'" . $period . "' AS ServicePeriod"),
                            // DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "' AND sa.AreaCode=Billing_ServiceAccounts.AreaCode) AS TotalBilledConsumers"),
                            DB::raw("BillNumber AS TotalBilledConsumers"),
                            DB::raw("KwhUsed AS TotalKwhUsed"),
                            DB::raw("BillNumber AS ConsumerCount"),
                            DB::raw("Multiplier AS Residentials"),
                            DB::raw("DemandPreviousKwh AS DemandKwh"),
                            DB::raw("Coreloss AS LowVoltKwh"),
                            DB::raw("KwhUsed AS KwhSold"),
                            DB::raw("NetAmount AS TotalAmount"),
                            DB::raw("MissionaryElectrificationCharge AS Missionary"),
                            DB::raw("EnvironmentalCharge AS Environmental"),
                            DB::raw("NPCStrandedDebt AS NPC"),
                            DB::raw("StrandedContractCosts AS StrandedCC"),
                            DB::raw("MissionaryElectrificationREDCI AS Redci"),
                            DB::raw("FeedInTariffAllowance AS FITAll"),
                            DB::raw("RealPropertyTax AS RPT"),
                            DB::raw("GenerationVAT AS GenVat"),
                            DB::raw("TransmissionVAT AS TransVat"),
                            DB::raw("SystemLossVAT AS SysLossVat"),
                            DB::raw("DistributionVAT AS DistVat"),
                            DB::raw("SeniorCitizenSubsidy AS SCSubsidy"),
                            DB::raw("PPARefund AS SCDsc")
                        )
                        ->orderBy('KwhAmount')
                        ->orderBy('AccountNumber')
                        ->get();

                    $nullRouteData = DB::table('Billing_BillsOriginal')
                        ->whereRaw("UnlockedBy='SALES_CLOSED_TSD_EXPANDED' AND ServicePeriod='" . $period . "'")
                        ->whereNull('AccountNumber')
                        ->select('AccountNumber AS AreaCode', 
                            'KwhAmount AS Town',
                            DB::raw("'" . $period . "' AS ServicePeriod"),
                            // DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "' AND sa.AreaCode=Billing_ServiceAccounts.AreaCode) AS TotalBilledConsumers"),
                            DB::raw("BillNumber AS TotalBilledConsumers"),
                            DB::raw("KwhUsed AS TotalKwhUsed"),
                            DB::raw("BillNumber AS ConsumerCount"),
                            DB::raw("Multiplier AS Residentials"),
                            DB::raw("DemandPreviousKwh AS DemandKwh"),
                            DB::raw("Coreloss AS LowVoltKwh"),
                            DB::raw("KwhUsed AS KwhSold"),
                            DB::raw("NetAmount AS TotalAmount"),
                            DB::raw("MissionaryElectrificationCharge AS Missionary"),
                            DB::raw("EnvironmentalCharge AS Environmental"),
                            DB::raw("NPCStrandedDebt AS NPC"),
                            DB::raw("StrandedContractCosts AS StrandedCC"),
                            DB::raw("MissionaryElectrificationREDCI AS Redci"),
                            DB::raw("FeedInTariffAllowance AS FITAll"),
                            DB::raw("RealPropertyTax AS RPT"),
                            DB::raw("GenerationVAT AS GenVat"),
                            DB::raw("TransmissionVAT AS TransVat"),
                            DB::raw("SystemLossVAT AS SysLossVat"),
                            DB::raw("DistributionVAT AS DistVat"),
                            DB::raw("SeniorCitizenSubsidy AS SCSubsidy"),
                            DB::raw("PPARefund AS SCDsc")
                        )
                        ->orderBy('KwhAmount')
                        ->orderBy('AccountNumber')
                        ->get();
                } else {
                    $data = DB::table('Billing_BillsOriginal')
                        ->whereRaw("UnlockedBy='SALES_CLOSED_TSD_EXPANDED' AND ServicePeriod='" . $period . "' AND KwhAmount='" . $town . "'")
                        ->whereNotNull('AccountNumber')
                        ->select('AccountNumber AS AreaCode', 
                            'KwhAmount AS Town',
                            DB::raw("'" . $period . "' AS ServicePeriod"),
                            // DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "' AND sa.AreaCode=Billing_ServiceAccounts.AreaCode) AS TotalBilledConsumers"),
                            DB::raw("BillNumber AS TotalBilledConsumers"),
                            DB::raw("KwhUsed AS TotalKwhUsed"),
                            DB::raw("BillNumber AS ConsumerCount"),
                            DB::raw("Multiplier AS Residentials"),
                            DB::raw("DemandPreviousKwh AS DemandKwh"),
                            DB::raw("Coreloss AS LowVoltKwh"),
                            DB::raw("KwhUsed AS KwhSold"),
                            DB::raw("NetAmount AS TotalAmount"),
                            DB::raw("MissionaryElectrificationCharge AS Missionary"),
                            DB::raw("EnvironmentalCharge AS Environmental"),
                            DB::raw("NPCStrandedDebt AS NPC"),
                            DB::raw("StrandedContractCosts AS StrandedCC"),
                            DB::raw("MissionaryElectrificationREDCI AS Redci"),
                            DB::raw("FeedInTariffAllowance AS FITAll"),
                            DB::raw("RealPropertyTax AS RPT"),
                            DB::raw("GenerationVAT AS GenVat"),
                            DB::raw("TransmissionVAT AS TransVat"),
                            DB::raw("SystemLossVAT AS SysLossVat"),
                            DB::raw("DistributionVAT AS DistVat"),
                            DB::raw("SeniorCitizenSubsidy AS SCSubsidy"),
                            DB::raw("PPARefund AS SCDsc")
                        )
                        ->orderBy('KwhAmount')
                        ->orderBy('AccountNumber')
                        ->get();

                    $nullRouteData = DB::table('Billing_BillsOriginal')
                        ->whereRaw("UnlockedBy='SALES_CLOSED_TSD_EXPANDED' AND ServicePeriod='" . $period . "' AND KwhAmount='" . $town . "'")
                        ->whereNull('AccountNumber')
                        ->select('AccountNumber AS AreaCode', 
                            'KwhAmount AS Town',
                            DB::raw("'" . $period . "' AS ServicePeriod"),
                            // DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "' AND sa.AreaCode=Billing_ServiceAccounts.AreaCode) AS TotalBilledConsumers"),
                            DB::raw("BillNumber AS TotalBilledConsumers"),
                            DB::raw("KwhUsed AS TotalKwhUsed"),
                            DB::raw("BillNumber AS ConsumerCount"),
                            DB::raw("Multiplier AS Residentials"),
                            DB::raw("DemandPreviousKwh AS DemandKwh"),
                            DB::raw("Coreloss AS LowVoltKwh"),
                            DB::raw("KwhUsed AS KwhSold"),
                            DB::raw("NetAmount AS TotalAmount"),
                            DB::raw("MissionaryElectrificationCharge AS Missionary"),
                            DB::raw("EnvironmentalCharge AS Environmental"),
                            DB::raw("NPCStrandedDebt AS NPC"),
                            DB::raw("StrandedContractCosts AS StrandedCC"),
                            DB::raw("MissionaryElectrificationREDCI AS Redci"),
                            DB::raw("FeedInTariffAllowance AS FITAll"),
                            DB::raw("RealPropertyTax AS RPT"),
                            DB::raw("GenerationVAT AS GenVat"),
                            DB::raw("TransmissionVAT AS TransVat"),
                            DB::raw("SystemLossVAT AS SysLossVat"),
                            DB::raw("DistributionVAT AS DistVat"),
                            DB::raw("SeniorCitizenSubsidy AS SCSubsidy"),
                            DB::raw("PPARefund AS SCDsc")
                        )
                        ->orderBy('KwhAmount')
                        ->orderBy('AccountNumber')
                        ->get();
                }
                
            } else {
                $data = [];
                $nullRouteData = [];
            }
        } else {
            if ($town != null && $period != null) {
                if ($town == 'All') {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereNotNull('AreaCode')
                        ->select('AreaCode', 'Town',
                            DB::raw("'" . $period . "' AS ServicePeriod"),
                            // DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "' AND sa.AreaCode=Billing_ServiceAccounts.AreaCode) AS TotalBilledConsumers"),
                            DB::raw("COUNT(Billing_ServiceAccounts.id) AS TotalBilledConsumers"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(15,2))) AS TotalKwhUsed"),
                            DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS ConsumerCount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'COMMERCIAL HIGH VOLTAGE')) AS Commercial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('INDUSTRIAL', 'INDUSTRIAL HIGH VOLTAGE')) AS Industrial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('PUBLIC BUILDING', 'PUBLIC BUILDING HIGH VOLTAGE')) AS PublicBldg"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('STREET LIGHTS')) AS Streetlights"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS DemandKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS KwhSold"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Missionary"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Environmental"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS NPC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS StrandedCC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Redci"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS FITAll"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.RealPropertyTax AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS RPT"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.GenerationVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS GenVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.TransmissionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS TransVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SystemLossVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS SysLossVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.DistributionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS DistVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
                        )
                        ->groupBy('AreaCode', 'Town')
                        ->orderBy('Town')
                        ->orderBy('AreaCode')
                        ->get();

                    $nullRouteData = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereNull('AreaCode')
                        ->select('AreaCode', 'Town',
                            DB::raw("'" . $period . "' AS ServicePeriod"),
                            // DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "' AND sa.AreaCode IS NULL) AS TotalBilledConsumers"),
                            DB::raw("COUNT(Billing_ServiceAccounts.id) AS TotalBilledConsumers"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(15,2))) AS TotalKwhUsed"),
                            DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS ConsumerCount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'COMMERCIAL HIGH VOLTAGE')) AS Commercial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('INDUSTRIAL', 'INDUSTRIAL HIGH VOLTAGE')) AS Industrial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('PUBLIC BUILDING', 'PUBLIC BUILDING HIGH VOLTAGE')) AS PublicBldg"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('STREET LIGHTS')) AS Streetlights"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS DemandKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS KwhSold"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Missionary"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Environmental"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS NPC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS StrandedCC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Redci"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS FITAll"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.RealPropertyTax AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS RPT"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.GenerationVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS GenVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.TransmissionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS TransVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SystemLossVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS SysLossVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.DistributionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS DistVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
                        )
                        ->groupBy('AreaCode', 'Town')
                        ->orderBy('Town')
                        ->orderBy('AreaCode')
                        ->get();
                } else {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->where('Town', $town)
                        ->whereNotNull('AreaCode')
                        ->select('AreaCode', 'Town',
                            DB::raw("'" . $period . "' AS ServicePeriod"),
                            DB::raw("COUNT(Billing_ServiceAccounts.id) AS TotalBilledConsumers"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(15,2))) AS TotalKwhUsed"),
                            DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS ConsumerCount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'COMMERCIAL HIGH VOLTAGE')) AS Commercial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('INDUSTRIAL', 'INDUSTRIAL HIGH VOLTAGE')) AS Industrial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('PUBLIC BUILDING', 'PUBLIC BUILDING HIGH VOLTAGE')) AS PublicBldg"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('STREET LIGHTS')) AS Streetlights"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS DemandKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS KwhSold"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Missionary"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Environmental"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS NPC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS StrandedCC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Redci"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS FITAll"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.RealPropertyTax AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS RPT"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.GenerationVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS GenVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.TransmissionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS TransVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SystemLossVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS SysLossVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.DistributionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS DistVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
                        )
                        ->groupBy('AreaCode', 'Town')
                        ->orderBy('Town')
                        ->orderBy('AreaCode')
                        ->get();

                    $nullRouteData = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereNull('AreaCode')
                        ->where('Town', $town)
                        ->select('AreaCode', 'Town',
                            DB::raw("'" . $period . "' AS ServicePeriod"),
                            // DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "' AND sa.AreaCode IS NULL) AS TotalBilledConsumers"),
                            DB::raw("COUNT(Billing_ServiceAccounts.id) AS TotalBilledConsumers"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(15,2))) AS TotalKwhUsed"),
                            DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS ConsumerCount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'COMMERCIAL HIGH VOLTAGE')) AS Commercial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('INDUSTRIAL', 'INDUSTRIAL HIGH VOLTAGE')) AS Industrial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('PUBLIC BUILDING', 'PUBLIC BUILDING HIGH VOLTAGE')) AS PublicBldg"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('STREET LIGHTS')) AS Streetlights"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS DemandKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS KwhSold"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Missionary"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Environmental"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS NPC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS StrandedCC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Redci"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS FITAll"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.RealPropertyTax AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS RPT"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.GenerationVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS GenVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.TransmissionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS TransVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SystemLossVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS SysLossVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.DistributionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS DistVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
                        )
                        ->groupBy('AreaCode', 'Town')
                        ->orderBy('Town')
                        ->orderBy('AreaCode')
                        ->get();
                }
                
            } else {
                $data = [];
                $nullRouteData = [];
            }
        }

        return view('/kwh_sales/kwh_sales_expanded', [
            'billingMonths' => $billingMonths,
            'towns' => Towns::all(),
            'data' => $data,
            'nullRouteData' => $nullRouteData,
        ]);
    }

    public function kwhSalesExpandedView($route, $town, $servicePeriod) {
        $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("AreaCode='" . $route . "' AND Billing_ServiceAccounts.Town='" . $town . "' AND ServicePeriod='" . $servicePeriod . "' AND (AdjustmentType NOT IN('DM/CM') OR AdjustmentType IS NULL)")
                ->select('Billing_Bills.*',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                )
                ->orderBy('AreaCode')
                ->orderBy('OldAccountNo')
                ->get();

        return view('/kwh_sales/kwh_sales_expanded_view', [
            'data' => $data,
            'period' => $servicePeriod,
            'route' => $route,
            'town' => $town
        ]);
    }

    public function downloadKwhSalesExpanded($period, $town) {
        $billingMonths = DB::table('Billing_Bills')
            ->select('ServicePeriod')
            ->whereNotNull('ServicePeriod')
            ->groupBy('ServicePeriod')
            ->orderByDesc('ServicePeriod')
            ->get();

        $sales = DistributionSystemLoss::where('ServicePeriod', $period)->first();

        if ($sales != null && $sales->Status=='CLOSED') {
            if ($town != null && $period != null) {
                if ($town == 'All') {
                    $data = DB::table('Billing_BillsOriginal')
                        ->whereRaw("UnlockedBy='SALES_CLOSED_TSD_EXPANDED' AND ServicePeriod='" . $period . "'")
                        ->whereNotNull('AccountNumber')
                        ->select('AccountNumber AS AreaCode', 
                            // DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "' AND sa.AreaCode=Billing_ServiceAccounts.AreaCode) AS TotalBilledConsumers"),
                            DB::raw("BillNumber AS TotalBilledConsumers"),
                            DB::raw("Multiplier AS Residentials"),
                            DB::raw("Coreloss AS LowVoltKwh"),
                            DB::raw("DemandPreviousKwh AS DemandKwh"),
                            DB::raw("KwhUsed AS KwhSold"),
                            DB::raw("NetAmount AS TotalAmount"),
                            DB::raw("MissionaryElectrificationCharge AS Missionary"),
                            DB::raw("EnvironmentalCharge AS Environmental"),
                            DB::raw("NPCStrandedDebt AS NPC"),
                            DB::raw("StrandedContractCosts AS StrandedCC"),
                            DB::raw("MissionaryElectrificationREDCI AS Redci"),
                            DB::raw("FeedInTariffAllowance AS FITAll"),
                            DB::raw("RealPropertyTax AS RPT"),
                            DB::raw("RFSC AS RFSC"),
                            DB::raw("GenerationVAT AS GenVat"),
                            DB::raw("TransmissionVAT AS TransVat"),
                            DB::raw("SystemLossVAT AS SysLossVat"),
                            DB::raw("DistributionVAT AS DistVat"),
                            DB::raw("SeniorCitizenSubsidy AS SCSubsidy"),
                            DB::raw("PPARefund AS SCDsc")
                        )
                        ->orderBy('KwhAmount')
                        ->orderBy('AccountNumber')
                        ->get();

                    $nullRouteData = DB::table('Billing_BillsOriginal')
                        ->whereRaw("UnlockedBy='SALES_CLOSED_TSD_EXPANDED' AND ServicePeriod='" . $period . "'")
                        ->whereNull('AccountNumber')
                        ->select('AccountNumber AS AreaCode', 
                            // DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "' AND sa.AreaCode=Billing_ServiceAccounts.AreaCode) AS TotalBilledConsumers"),
                            DB::raw("BillNumber AS TotalBilledConsumers"),
                            DB::raw("Multiplier AS Residentials"),
                            DB::raw("Coreloss AS LowVoltKwh"),
                            DB::raw("DemandPreviousKwh AS DemandKwh"),
                            DB::raw("KwhUsed AS KwhSold"),
                            DB::raw("NetAmount AS TotalAmount"),
                            DB::raw("MissionaryElectrificationCharge AS Missionary"),
                            DB::raw("EnvironmentalCharge AS Environmental"),
                            DB::raw("NPCStrandedDebt AS NPC"),
                            DB::raw("StrandedContractCosts AS StrandedCC"),
                            DB::raw("MissionaryElectrificationREDCI AS Redci"),
                            DB::raw("FeedInTariffAllowance AS FITAll"),
                            DB::raw("RealPropertyTax AS RPT"),
                            DB::raw("RFSC AS RFSC"),
                            DB::raw("GenerationVAT AS GenVat"),
                            DB::raw("TransmissionVAT AS TransVat"),
                            DB::raw("SystemLossVAT AS SysLossVat"),
                            DB::raw("DistributionVAT AS DistVat"),
                            DB::raw("SeniorCitizenSubsidy AS SCSubsidy"),
                            DB::raw("PPARefund AS SCDsc")
                        )
                        ->orderBy('KwhAmount')
                        ->orderBy('AccountNumber')
                        ->get();
                } else {
                    $data = DB::table('Billing_BillsOriginal')
                        ->whereRaw("UnlockedBy='SALES_CLOSED_TSD_EXPANDED' AND ServicePeriod='" . $period . "' AND KwhAmount='" . $town . "'")
                        ->whereNotNull('AccountNumber')
                        ->select('AccountNumber AS AreaCode', 
                            // DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "' AND sa.AreaCode=Billing_ServiceAccounts.AreaCode) AS TotalBilledConsumers"),
                            DB::raw("BillNumber AS TotalBilledConsumers"),
                            DB::raw("Multiplier AS Residentials"),
                            DB::raw("Coreloss AS LowVoltKwh"),
                            DB::raw("DemandPreviousKwh AS DemandKwh"),
                            DB::raw("KwhUsed AS KwhSold"),
                            DB::raw("NetAmount AS TotalAmount"),
                            DB::raw("MissionaryElectrificationCharge AS Missionary"),
                            DB::raw("EnvironmentalCharge AS Environmental"),
                            DB::raw("NPCStrandedDebt AS NPC"),
                            DB::raw("StrandedContractCosts AS StrandedCC"),
                            DB::raw("MissionaryElectrificationREDCI AS Redci"),
                            DB::raw("FeedInTariffAllowance AS FITAll"),
                            DB::raw("RealPropertyTax AS RPT"),
                            DB::raw("RFSC AS RFSC"),
                            DB::raw("GenerationVAT AS GenVat"),
                            DB::raw("TransmissionVAT AS TransVat"),
                            DB::raw("SystemLossVAT AS SysLossVat"),
                            DB::raw("DistributionVAT AS DistVat"),
                            DB::raw("SeniorCitizenSubsidy AS SCSubsidy"),
                            DB::raw("PPARefund AS SCDsc")
                        )
                        ->orderBy('KwhAmount')
                        ->orderBy('AccountNumber')
                        ->get();

                    $nullRouteData = DB::table('Billing_BillsOriginal')
                        ->whereRaw("UnlockedBy='SALES_CLOSED_TSD_EXPANDED' AND ServicePeriod='" . $period . "' AND KwhAmount='" . $town . "'")
                        ->whereNull('AccountNumber')
                        ->select('AccountNumber AS AreaCode', 
                            // DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "' AND sa.AreaCode=Billing_ServiceAccounts.AreaCode) AS TotalBilledConsumers"),
                            DB::raw("BillNumber AS TotalBilledConsumers"),
                            DB::raw("Multiplier AS Residentials"),
                            DB::raw("Coreloss AS LowVoltKwh"),
                            DB::raw("DemandPreviousKwh AS DemandKwh"),
                            DB::raw("KwhUsed AS KwhSold"),
                            DB::raw("NetAmount AS TotalAmount"),
                            DB::raw("MissionaryElectrificationCharge AS Missionary"),
                            DB::raw("EnvironmentalCharge AS Environmental"),
                            DB::raw("NPCStrandedDebt AS NPC"),
                            DB::raw("StrandedContractCosts AS StrandedCC"),
                            DB::raw("MissionaryElectrificationREDCI AS Redci"),
                            DB::raw("FeedInTariffAllowance AS FITAll"),
                            DB::raw("RealPropertyTax AS RPT"),
                            DB::raw("RFSC AS RFSC"),
                            DB::raw("GenerationVAT AS GenVat"),
                            DB::raw("TransmissionVAT AS TransVat"),
                            DB::raw("SystemLossVAT AS SysLossVat"),
                            DB::raw("DistributionVAT AS DistVat"),
                            DB::raw("SeniorCitizenSubsidy AS SCSubsidy"),
                            DB::raw("PPARefund AS SCDsc")
                        )
                        ->orderBy('KwhAmount')
                        ->orderBy('AccountNumber')
                        ->get();
                }
                
            } else {
                $data = [];
                $nullRouteData = [];
            }
        } else {
            if ($town != null && $period != null) {
                if ($town == 'All') {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereNotNull('AreaCode')
                        ->select('AreaCode', 
                            // DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "' AND sa.AreaCode=Billing_ServiceAccounts.AreaCode) AS TotalBilledConsumers"),
                            DB::raw("COUNT(Billing_ServiceAccounts.id) AS TotalBilledConsumers"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(15,2))) AS TotalKwhUsed"),
                            DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS ConsumerCount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'COMMERCIAL HIGH VOLTAGE')) AS Commercial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('INDUSTRIAL', 'INDUSTRIAL HIGH VOLTAGE')) AS Industrial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('PUBLIC BUILDING', 'PUBLIC BUILDING HIGH VOLTAGE')) AS PublicBldg"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('STREET LIGHTS')) AS Streetlights"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS DemandKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS KwhSold"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Missionary"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Environmental"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS NPC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS StrandedCC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Redci"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS FITAll"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.RealPropertyTax AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS RPT"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.GenerationVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS GenVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.TransmissionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS TransVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SystemLossVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS SysLossVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.DistributionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS DistVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
                        )
                        ->groupBy('AreaCode', 'Town')
                        ->orderBy('Town')
                        ->orderBy('AreaCode')
                        ->get();

                    $nullRouteData = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereNull('AreaCode')
                        ->select('AreaCode', 
                            // DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "' AND sa.AreaCode IS NULL) AS TotalBilledConsumers"),
                            DB::raw("COUNT(Billing_ServiceAccounts.id) AS TotalBilledConsumers"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(15,2))) AS TotalKwhUsed"),
                            DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS ConsumerCount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'COMMERCIAL HIGH VOLTAGE')) AS Commercial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('INDUSTRIAL', 'INDUSTRIAL HIGH VOLTAGE')) AS Industrial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('PUBLIC BUILDING', 'PUBLIC BUILDING HIGH VOLTAGE')) AS PublicBldg"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('STREET LIGHTS')) AS Streetlights"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS DemandKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS KwhSold"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Missionary"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Environmental"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS NPC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS StrandedCC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Redci"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS FITAll"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.RealPropertyTax AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS RPT"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.GenerationVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS GenVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.TransmissionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS TransVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SystemLossVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS SysLossVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.DistributionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS DistVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
                        )
                        ->groupBy('AreaCode', 'Town')
                        ->orderBy('Town')
                        ->orderBy('AreaCode')
                        ->get();
                } else {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->where('Town', $town)
                        ->whereNotNull('AreaCode')
                        ->select('AreaCode', 
                            DB::raw("COUNT(Billing_ServiceAccounts.id) AS TotalBilledConsumers"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(15,2))) AS TotalKwhUsed"),
                            DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS ConsumerCount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'COMMERCIAL HIGH VOLTAGE')) AS Commercial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('INDUSTRIAL', 'INDUSTRIAL HIGH VOLTAGE')) AS Industrial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('PUBLIC BUILDING', 'PUBLIC BUILDING HIGH VOLTAGE')) AS PublicBldg"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('STREET LIGHTS')) AS Streetlights"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS DemandKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS KwhSold"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Missionary"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Environmental"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS NPC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS StrandedCC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Redci"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS FITAll"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.RealPropertyTax AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS RPT"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.GenerationVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS GenVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.TransmissionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS TransVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SystemLossVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS SysLossVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.DistributionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS DistVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
                        )
                        ->groupBy('AreaCode', 'Town')
                        ->orderBy('Town')
                        ->orderBy('AreaCode')
                        ->get();

                    $nullRouteData = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereNull('AreaCode')
                        ->where('Town', $town)
                        ->select('AreaCode', 
                            // DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "' AND sa.AreaCode IS NULL) AS TotalBilledConsumers"),
                            DB::raw("COUNT(Billing_ServiceAccounts.id) AS TotalBilledConsumers"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(15,2))) AS TotalKwhUsed"),
                            DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS ConsumerCount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'COMMERCIAL HIGH VOLTAGE')) AS Commercial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('INDUSTRIAL', 'INDUSTRIAL HIGH VOLTAGE')) AS Industrial"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('PUBLIC BUILDING', 'PUBLIC BUILDING HIGH VOLTAGE')) AS PublicBldg"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('STREET LIGHTS')) AS Streetlights"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS DemandKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltKwh"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS KwhSold"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Missionary"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Environmental"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS NPC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS StrandedCC"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Redci"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS FITAll"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.RealPropertyTax AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS RPT"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.GenerationVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS GenVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.TransmissionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS TransVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SystemLossVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS SysLossVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.DistributionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS DistVat"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                            DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
                        )
                        ->groupBy('AreaCode', 'Town')
                        ->orderBy('Town')
                        ->orderBy('AreaCode')
                        ->get();
                }
                
            } else {
                $data = [];
                $nullRouteData = [];
            }
        }

        $townName = $town=='All' ? 'All' : Towns::find($town)->Town;

        $export = new KwhSalesTsdRoutesExport($data->toArray(), $period, $townName);

        return Excel::download($export, 'Kwh-Sales-' . $period . '.xlsx');
    }

    public function downloadMergedSales($period) {
        $sales = DistributionSystemLoss::where('ServicePeriod', $period)->first();

        if ($sales != null && $sales->Status=='CLOSED') {
            $data = DB::table('CRM_Towns AS t')
                ->select('t.Town',
                    DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS ConsumerCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL', 'COMMERCIAL HIGH VOLTAGE')) AS Commercial"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL', 'INDUSTRIAL HIGH VOLTAGE')) AS Industrial"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING', 'PUBLIC BUILDING HIGH VOLTAGE')) AS PublicBldg"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS Streetlights"),
                    // DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS DemandKwh"),
                    // DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltKwh"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS KwhSold"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS TotalAmount"),
                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS Missionary"),
                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS Environmental"),
                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS StrandedCC"),
                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS NPC"),
                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS Redci"),
                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS RFSC"),
                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS FITAll"),
                    DB::raw("'0' AS FranchiseTax"),
                    DB::raw("'0' AS BusinessTax"),
                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS RPT"),
                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2)) + TRY_CAST(TransmissionVAT AS decimal(10,2)) + TRY_CAST(SystemLossVAT AS decimal(10,2)) + TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS Vat"),
                    // DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS TransVat"),
                    // DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS SysLossVat"),
                    // DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS DistVat"),
                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
                )
                ->orderBy("t.id")
                ->get();

            $dataERC = DB::table('CRM_Towns AS t')
                ->select('t.Town',
                    DB::raw("(SELECT SUM(TRY_CAST(BillNumber AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS ConsumerCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL', '', 'IRRIGATION/WATER SYSTEMS', 'PUBLIC BUILDING', 'INDUSTRIAL', 'STREET LIGHTS')) AS LowVoltage"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE', 'COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE')) AS HighVoltage"),
                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS KwhSold"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS TotalAmount"),
                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS Missionary"),
                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS Environmental"),
                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS StrandedCC"),
                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS NPC"),
                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS Redci"),
                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS RFSC"),
                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS FITAll"),
                    DB::raw("'0' AS FranchiseTax"),
                    DB::raw("'0' AS BusinessTax"),
                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS RPT"),
                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2)) + TRY_CAST(TransmissionVAT AS decimal(10,2)) + TRY_CAST(SystemLossVAT AS decimal(10,2)) + TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "') AS Vat"),
                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber=t.id AND ServicePeriod='" . $period . "' AND TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
                )
                ->orderBy("t.id")
                ->get();

            $demandTotal = DB::table('Billing_Bills')
                ->select(
                    DB::raw("(SELECT SUM(TRY_CAST(b.DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE ForCancellation='SALES_REPORT' AND b.ServicePeriod='" . $period . "') AS Demand"),
                )
                ->first();
        } else {
            $data = DB::table('CRM_Towns AS t')
                ->select('t.Town',
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS ConsumerCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'COMMERCIAL HIGH VOLTAGE')) AS Commercial"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('INDUSTRIAL', 'INDUSTRIAL HIGH VOLTAGE')) AS Industrial"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('PUBLIC BUILDING', 'PUBLIC BUILDING HIGH VOLTAGE')) AS PublicBldg"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('STREET LIGHTS')) AS Streetlights"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS KwhSold"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS Missionary"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS Environmental"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS StrandedCC"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS NPC"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS Redci"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.RFSC AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS RFSC"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS FITAll"),
                    DB::raw("'0' AS FranchiseTax"),
                    DB::raw("'0' AS BusinessTax"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.RealPropertyTax AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS RPT"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.GenerationVAT AS decimal(10,2)) + TRY_CAST(b.TransmissionVAT AS decimal(10,2)) + TRY_CAST(b.SystemLossVAT AS decimal(10,2)) + TRY_CAST(b.DistributionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS Vat"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
                )
                ->orderBy("t.id")
                ->get();

            $dataERC = DB::table('CRM_Towns AS t')
                ->select('t.Town',
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS ConsumerCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', '', 'IRRIGATION/WATER SYSTEMS', 'PUBLIC BUILDING', 'INDUSTRIAL', 'STREET LIGHTS')) AS LowVoltage"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE', 'COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE')) AS HighVoltage"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS KwhSold"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS Missionary"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS Environmental"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS StrandedCC"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS NPC"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS Redci"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.RFSC AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS RFSC"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS FITAll"),
                    DB::raw("'0' AS FranchiseTax"),
                    DB::raw("'0' AS BusinessTax"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.RealPropertyTax AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS RPT"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.GenerationVAT AS decimal(10,2)) + TRY_CAST(b.TransmissionVAT AS decimal(10,2)) + TRY_CAST(b.SystemLossVAT AS decimal(10,2)) + TRY_CAST(b.DistributionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "') AS Vat"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=t.id AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
                )
                ->orderBy("t.id")
                ->get();

            $demandTotal = DB::table('Billing_Bills')
                ->select(
                    DB::raw("(SELECT SUM(TRY_CAST(b.DemandPresentKwh AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND b.ServicePeriod='" . $period . "') AS Demand"),
                )
                ->first();
        }

        

        $export = new SummaryOfSalesExport($data->toArray(), $dataERC->toArray(), $period, $sales, $demandTotal);

        return Excel::download($export, 'Summary of Sales Per Area - ' . date('F Y', strtotime($period)) . '.xlsx');
    }

    public function downloadSummaryPerConsumerType($period) {
        $sales = DistributionSystemLoss::where('ServicePeriod', $period)->first();
        
        if ($sales != null && $sales->Status=='CLOSED') {
            $residential = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $commercial = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('COMMERCIAL') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $irrigation = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('IRRIGATION/WATER SYSTEMS') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $industrial = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('INDUSTRIAL') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $streetlights = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('STREET LIGHTS') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $publicbuilding = DB::table('Billing_Bills')
                    ->whereRaw("ConsumerType IN ('PUBLIC BUILDING') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                    ->select(
                        DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                        DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                        DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                        DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                        DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                        DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                        DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                    )
                    ->first();

            $totallv = DB::table('Billing_Bills')
                    ->whereRaw("ConsumerType IN ('PUBLIC BUILDING', 'STREET LIGHTS', 'INDUSTRIAL', 'IRRIGATION/WATER SYSTEMS', 'COMMERCIAL') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                    ->select(
                        DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                        DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                        DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                        DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                        DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                        DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                        DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                    )
                    ->first();

            $commercialhv = DB::table('Billing_Bills')
                    ->whereRaw("ConsumerType IN ('COMMERCIAL HIGH VOLTAGE') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                    ->select(
                        DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                        DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                        DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                        DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                        DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                        DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                        DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                    )
                    ->first();

            $industrialhv = DB::table('Billing_Bills')
                    ->whereRaw("ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                    ->select(
                        DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                        DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                        DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                        DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                        DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                        DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                        DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                    )
                    ->first();

            $publicbldghv = DB::table('Billing_Bills')
                    ->whereRaw("ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                    ->select(
                        DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                        DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                        DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                        DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                        DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                        DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                        DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                    )
                    ->first();

            $totalhv = DB::table('Billing_Bills')
                    ->whereRaw("ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'COMMERCIAL HIGH VOLTAGE') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                    ->select(
                        DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                        DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                        DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                        DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                        DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                        DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                        DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                    )
                    ->first();

            $grandTotal = DB::table('Billing_Bills')
                    ->whereRaw("ServicePeriod='" . $period . "' AND ForCancellation='SALES_REPORT'")
                    ->select(
                        DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                        DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                        DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                        DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                        DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                        DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                        DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                    )
                    ->first();
        } else {
            $residential = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("COUNT(id) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $commercial = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('COMMERCIAL') AND (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("COUNT(id) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $irrigation = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('IRRIGATION/WATER SYSTEMS') AND (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("COUNT(id) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $industrial = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('INDUSTRIAL') AND (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("COUNT(id) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $streetlights = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('STREET LIGHTS') AND (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("COUNT(id) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $publicbuilding = DB::table('Billing_Bills')
                    ->whereRaw("ConsumerType IN ('PUBLIC BUILDING') AND (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "'")
                    ->select(
                        DB::raw("COUNT(id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                        DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                        DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                        DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                        DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                        DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                        DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                    )
                    ->first();

            $totallv = DB::table('Billing_Bills')
                    ->whereRaw("ConsumerType IN ('PUBLIC BUILDING', 'STREET LIGHTS', 'INDUSTRIAL', 'IRRIGATION/WATER SYSTEMS', 'COMMERCIAL') AND (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "'")
                    ->select(
                        DB::raw("COUNT(id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                        DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                        DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                        DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                        DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                        DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                        DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                    )
                    ->first();

            $commercialhv = DB::table('Billing_Bills')
                    ->whereRaw("ConsumerType IN ('COMMERCIAL HIGH VOLTAGE') AND (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "'")
                    ->select(
                        DB::raw("COUNT(id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                        DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                        DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                        DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                        DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                        DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                        DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                    )
                    ->first();

            $industrialhv = DB::table('Billing_Bills')
                    ->whereRaw("ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE') AND (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "'")
                    ->select(
                        DB::raw("COUNT(id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                        DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                        DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                        DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                        DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                        DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                        DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                    )
                    ->first();

            $publicbldghv = DB::table('Billing_Bills')
                    ->whereRaw("ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE') AND (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "'")
                    ->select(
                        DB::raw("COUNT(id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                        DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                        DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                        DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                        DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                        DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                        DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                    )
                    ->first();

            $totalhv = DB::table('Billing_Bills')
                    ->whereRaw("ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'COMMERCIAL HIGH VOLTAGE') AND (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "'")
                    ->select(
                        DB::raw("COUNT(id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                        DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                        DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                        DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                        DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                        DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                        DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                    )
                    ->first();

            $grandTotal = DB::table('Billing_Bills')
                    ->whereRaw("ServicePeriod='" . $period . "' AND (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM'))")
                    ->select(
                        DB::raw("COUNT(id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                        DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                        DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                        DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                        DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                        DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                        DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                    )
                    ->first();
        }
    
        $export = new SummaryOfSalesPerConsumerTypeExport($period, 
            $residential, 
            $commercial, 
            $irrigation,
            $industrial,
            $streetlights,
            $publicbuilding,
            $totallv,
            $commercialhv,
            $industrialhv,
            $publicbldghv,
            $totalhv,
            $grandTotal);

        return Excel::download($export, 'Summary of Sales Per Consumer Type - ' . date('F Y', strtotime($period)) . '.xlsx');
    }

    public function downloadConsolidatedPerDistrict($period) {
        $sales = DistributionSystemLoss::where('ServicePeriod', $period)->first();
        $export = new SalesConsolidatedExport($period, $sales);

        return Excel::download($export, 'Consolidated Sales Summary Per District - ' . date('F Y', strtotime($period)) . '.xlsx');
    }

    public function validateConfirmUser(Request $request) {
        $password = $request['Password'];
        $id = urldecode($request['id']);

        $users = Users::find(env('KWH_SALES_CLOSING_CONFIRM_ID'));

        if ($users != null) {
            if(!Hash::check($password, $users->password)) {
                return response()->json('Incorrect Password ' . $users->password . ' - ' . $password, 401);
            } else {
                // FINALIZE KWH SALES
                $sales = DistributionSystemLoss::find($id);

                if ($sales != null) {
                    $sales->CalatravaSubstation = 'FINALIZED';
                    $sales->save();

                    return response()->json('ok', 200);
                } else {
                    return response()->json('Sales not found', 404);
                }
            }
        } else {
            return response()->json('User not found!', 404);
        }
    }
}
