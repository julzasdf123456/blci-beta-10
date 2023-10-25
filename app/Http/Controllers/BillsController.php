<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillsRequest;
use App\Http\Requests\UpdateBillsRequest;
use App\Repositories\BillsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Readings;
use App\Models\ReadingImages;
use App\Models\ServiceAccounts;
use App\Models\Rates;
use App\Models\Bills;
use App\Models\BillsOriginal;
use App\Models\BillingMeters;
use App\Models\MeterReaders;
use App\Models\IDGenerator;
use App\Models\PaidBills;
use App\Models\Barangays;
use App\Models\User;
use App\Models\Towns;
use App\Models\ReadingSchedules;
use App\Models\MemberConsumerTypes;
use App\Models\MemberConsumers;
use App\Models\DistributionSystemLoss;
use App\Models\PendingBillAdjustments;
use App\Models\ArrearsLedgerDistribution;
use App\Models\ChangeMeterLogs;
use App\Models\CustomerDepositLogs;
use App\Exports\DynamicExports;
use App\Models\DCRSummaryTransactions;
use App\Repositories\ReadingsRepository;
use Flash;
use Response;

class BillsController extends AppBaseController
{
    /** @var  BillsRepository */
    private $billsRepository;

    public function __construct(BillsRepository $billsRepo)
    {
        $this->middleware('auth');
        $this->billsRepository = $billsRepo;
    }

    /**
     * Display a listing of the Bills.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $bills = $this->billsRepository->all();

        return view('bills.index')
            ->with('bills', $bills);
    }

    /**
     * Show the form for creating a new Bills.
     *
     * @return Response
     */
    public function create()
    {
        return view('bills.create');
    }

    /**
     * Store a newly created Bills in storage.
     *
     * @param CreateBillsRequest $request
     *
     * @return Response
     */
    public function store(CreateBillsRequest $request)
    {
        $input = $request->all();

        $bills = $this->billsRepository->create($input);

        Flash::success('Bills saved successfully.');

        return redirect(route('bills.index'));
    }

    /**
     * Display the specified Bills.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $bills = Bills::find($id);
        $account = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->select('Billing_ServiceAccounts.id',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.ForDistribution',
                    'Billing_ServiceAccounts.Organization',
                    'Billing_ServiceAccounts.Main',
                    'Billing_ServiceAccounts.GroupCode',
                    'Billing_ServiceAccounts.Multiplier',
                    'Billing_ServiceAccounts.Coreloss',
                    'Billing_ServiceAccounts.ConnectionDate',
                    'Billing_ServiceAccounts.ServiceConnectionId',
                    'Billing_ServiceAccounts.SeniorCitizen',
                    'Billing_ServiceAccounts.Evat5Percent',
                    'Billing_ServiceAccounts.Ewt2Percent',
                    'Billing_ServiceAccounts.Contestable',
                    'Billing_ServiceAccounts.NetMetered',
                    'Billing_ServiceAccounts.AccountRetention',
                    'Billing_ServiceAccounts.DurationInMonths',
                    'Billing_ServiceAccounts.AccountExpiration',
                    'CRM_Towns.Town',
                    'Billing_ServiceAccounts.Town as TownCode',
                    'CRM_Barangays.Barangay')
            ->where('Billing_ServiceAccounts.id', $bills->AccountNumber)
            ->first();

        $paidBill = DB::table('Cashier_PaidBills')
            ->whereRaw("AccountNumber='" . $bills->AccountNumber . "' AND ServicePeriod='" . $bills->ServicePeriod . "' AND (Status IS NULL OR Status='Application')")
            ->first();

        $meters = BillingMeters::where('ServiceAccountId', $bills->AccountNumber)
            ->orderByDesc('created_at')
            ->first();

        $rate = Rates::where('ServicePeriod', $bills->ServicePeriod)
            ->where('ConsumerType', Bills::getAccountType($account))
            ->where("AreaCode", $account->TownCode)
            ->first();

        if (empty($bills)) {
            Flash::error('Bills not found');

            return redirect(route('bills.index'));
        }

        return view('bills.show', [
            'bills' => $bills,
            'account' => $account,
            'meters' => $meters,
            'rate' => $rate,
            'paidBill' => $paidBill,
        ]);
    }

    /**
     * Show the form for editing the specified Bills.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $bills = $this->billsRepository->find($id);

        if (empty($bills)) {
            Flash::error('Bills not found');

            return redirect(route('bills.index'));
        }

        return view('bills.edit')->with('bills', $bills);
    }

    /**
     * Update the specified Bills in storage.
     *
     * @param int $id
     * @param UpdateBillsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillsRequest $request)
    {
        $bills = $this->billsRepository->find($id);
        $bills->AdjustmentStatus = 'PENDING ADJUSTMENT APPROVAL';
        $bills->AdjustmentRequestedBy = Auth::id();
        $bills->DateAdjustmentRequested = date('Y-m-d H:i:s');
        $bills->save();

        if (floatval($bills->CustomerDeposit > 0)) {
            $depositLog = new CustomerDepositLogs;
            $depositLog->id = IDGenerator::generateIDandRandString();
            $depositLog->AccountNumber = $bills->AccountNumber;
            $depositLog->LogDetails = "Deposit deduction updated for " . date('F Y', strtotime($bills->ServicePeriod)) . " billing month, amounting " . $bills->CustomerDeposit . ".";
            $depositLog->UserId = Auth::id();
            $depositLog->save();
        }

        if (empty($bills)) {
            Flash::error('Bills not found');

            return redirect(route('bills.index'));
        }

        // BEFORE UPDATE
        // INSERT TO BILLS ORIGINAL
        $billsArr = $request->all();
        $billsArr['id'] = IDGenerator::generateIDandRandString();
        $billsArr['BillNumber'] = $bills->BillNumber;
        $billsArr['AccountNumber'] = $bills->AccountNumber;
        $billsArr['ServicePeriod'] = $bills->ServicePeriod;
        $billsArr['Deductions'] = $bills->Deductions;
        $billsArr['BillingDate'] = $bills->BillingDate;
        $billsArr['MeterNumber'] = $bills->MeterNumber;
        $billsArr['ConsumerType'] = $bills->ConsumerType;
        $billsArr['BillType'] = $bills->BillType;
        $billsArr['UserId'] = $bills->UserId;
        $billsArr['BilledFrom'] = 'WEB';
        $billsArr['AveragedCount'] = $bills->AveragedCount;
        $billsArr['MergedToCollectible'] = $bills->MergedToCollectible;
        $billsArr['IsUnlockedForPayment'] = $bills->IsUnlockedForPayment;
        $billsArr['UnlockedBy'] = $bills->UnlockedBy;
        $billsArr['ForCancellation'] = $bills->ForCancellation;
        $billsArr['CancelRequestedBy'] = $bills->CancelRequestedBy;
        $billsArr['CancelApprovedBy'] = $bills->CancelApprovedBy;
        // $billsArr['PaidAmount'] = $bills->PaidAmount;
        // $billsArr['Balance'] = $bills->Balance;
        $billsArr['AdjustmentStatus'] = 'PENDING ADJUSTMENT APPROVAL';
        $billsArr['AdjustmentRequestedBy'] = Auth::id();
        $billsArr['DateAdjustmentRequested'] = date('Y-m-d H:i:s');
        $billsOriginal = BillsOriginal::create($billsArr);

        // UPDATE BILL
        // $request['AdjustmentStatus'] = 'PENDING ADJUSTMENT APPROVAL';
        // $request['AdjustmentRequestedBy'] = Auth::id();
        // $request['DateAdjustmentRequested'] = date('Y-m-d H:i:s');
        // $bills = $this->billsRepository->update($request->all(), $id);

        // UPDATE READINGS
        $reading = Readings::where('AccountNumber', $bills->AccountNumber)
            ->where('ServicePeriod', $bills->ServicePeriod)
            ->first();
        if ($reading != null) {
            $reading->KwhUsed = $bills->PresentKwh;
            $reading->SolarKwhUsed = $bills->SolarExportPresent;
            $reading->save();
        }

        // Flash::success('Bills updated successfully.');

        return redirect(route('serviceAccounts.show', [$bills->AccountNumber]));
    }

    /**
     * Remove the specified Bills from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $bills = $this->billsRepository->find($id);

        if (empty($bills)) {
            Flash::error('Bills not found');

            return redirect(route('bills.index'));
        }

        $this->billsRepository->delete($id);

        Flash::success('Bills deleted successfully.');

        return redirect(route('bills.index'));
    }

    public function unbilledReadings() {
        $stats = DB::table('Billing_Bills')
            ->select('ServicePeriod',
                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE ServicePeriod=Billing_Bills.ServicePeriod) as 'Bills'"),
                DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE ServicePeriod=Billing_Bills.ServicePeriod) as 'Readings'"))
            ->orderByDesc('ServicePeriod')
            ->groupBy('ServicePeriod')
            ->get();
        return view('/bills/unbilled_readings', [
            'stats' => $stats
        ]);
    }

    public function unbilledReadingsConsole($servicePeriod, Request $request) {
        $area = $request['Area'];
        $meterReader = $request['MeterReader'];
        $groupCode = $request['GroupCode'];

        if ($meterReader == null | $groupCode == null | $area == null) {
            $zeroReadings = null;
            $disconnectedReadings = null;
            $changeMeters = null;
        } else {
            $zeroReadings = DB::table('Billing_Readings')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Readings.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereRaw("Billing_Readings.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $servicePeriod . "')")
                // ->whereNotIn('Billing_Readings.AccountNumber', DB::table('Billing_Bills')->where('Billing_Bills.ServicePeriod', $servicePeriod)->pluck('Billing_Bills.AccountNumber'))
                ->where('Billing_Readings.ServicePeriod', $servicePeriod)
                ->where('Billing_ServiceAccounts.AccountStatus', 'ACTIVE')
                ->where('Billing_ServiceAccounts.MeterReader', $meterReader)
                ->where('Billing_ServiceAccounts.GroupCode', $groupCode)
                ->where('Billing_ServiceAccounts.Town', $area)
                ->whereRaw("Billing_Readings.id NOT IN (SELECT ReadingId FROM Billing_PendingBillAdjustments WHERE Confirmed IS NULL AND ServicePeriod='" . $servicePeriod . "')")
                // ->whereNotIn('Billing_Readings.id', DB::table('Billing_PendingBillAdjustments')->where('ServicePeriod', $servicePeriod)->whereNull('Confirmed')->pluck('ReadingId'))
                ->select('Billing_Readings.AccountNumber',
                    'Billing_Readings.id',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_Readings.FieldStatus')
                ->get();
        
            $disconnectedReadings = DB::table('Billing_Readings')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Readings.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                // ->whereRaw("Billing_Readings.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $servicePeriod . "')")
                // ->whereNotIn('Billing_Readings.AccountNumber', DB::table('Billing_Bills')->where('Billing_Bills.ServicePeriod', $servicePeriod)->pluck('Billing_Bills.AccountNumber'))
                ->where('Billing_Readings.ServicePeriod', $servicePeriod)
                ->where('Billing_ServiceAccounts.AccountStatus', 'DISCONNECTED')
                ->where('Billing_ServiceAccounts.MeterReader', $meterReader)
                ->where('Billing_ServiceAccounts.GroupCode', $groupCode)
                ->where('Billing_ServiceAccounts.Town', $area)
                // ->whereRaw("Billing_Readings.id NOT IN (SELECT ReadingId FROM Billing_PendingBillAdjustments WHERE Confirmed IS NULL AND ServicePeriod='" . $servicePeriod . "')")
                // ->whereNotIn('Billing_Readings.id', DB::table('Billing_PendingBillAdjustments')->where('ServicePeriod', $servicePeriod)->whereNull('Confirmed')->pluck('ReadingId'))
                ->select('Billing_Readings.AccountNumber',
                    'Billing_Readings.id',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_Readings.KwhUsed',
                    'Billing_Readings.FieldStatus')
                ->get();

            $changeMeters = DB::table('Billing_Readings')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Readings.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereRaw("Billing_Readings.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $servicePeriod . "')")
                // ->whereNotIn('Billing_Readings.AccountNumber', DB::table('Billing_Bills')->where('Billing_Bills.ServicePeriod', $servicePeriod)->pluck('Billing_Bills.AccountNumber'))
                ->where('Billing_Readings.ServicePeriod', $servicePeriod)
                ->where('Billing_ServiceAccounts.AccountStatus', 'ACTIVE')
                ->where('Billing_ServiceAccounts.MeterReader', $meterReader)
                ->where('Billing_ServiceAccounts.GroupCode', $groupCode)
                ->where('Billing_ServiceAccounts.Town', $area)
                ->whereRaw("Billing_Readings.id NOT IN (SELECT ReadingId FROM Billing_PendingBillAdjustments WHERE Confirmed IS NULL AND ServicePeriod='" . $servicePeriod . "')")
                ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_ChangeMeterLogs WHERE ServicePeriod='" . $servicePeriod . "')")
                // ->whereNotIn('Billing_Readings.id', DB::table('Billing_PendingBillAdjustments')->where('ServicePeriod', $servicePeriod)->whereNull('Confirmed')->pluck('ReadingId'))
                ->select('Billing_Readings.AccountNumber',
                    'Billing_Readings.id',
                    'Billing_Readings.KwhUsed',
                    'Billing_Readings.ServicePeriod',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_Readings.FieldStatus')
                ->get();
        }        
 
        $meterReaders = DB::table('Billing_Readings')
            ->leftJoin('users', 'Billing_Readings.MeterReader', '=', 'users.id')
            ->select('users.id', 'users.name')
            ->groupBy('users.id', 'users.name')
            ->get();
        
        return view('/bills/unbilled_readings_console', [
            'servicePeriod' => $servicePeriod,
            'zeroReadings' => $zeroReadings,
            'disconnectedReadings' => $disconnectedReadings,
            'meterReaders' => $meterReaders,
            'changeMeters' => $changeMeters,
            'towns' => Towns::all()
        ]);
    }

    public function zeroReadingsView($readingId) {
        $reading = Readings::find($readingId);
        $account = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->select('Billing_ServiceAccounts.id',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_ServiceAccounts.Purok as Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.ServiceConnectionId',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.MeterDetailsId')
            ->where('Billing_ServiceAccounts.id', $reading->AccountNumber)
            ->first();
        $images = ReadingImages::where('ServicePeriod', $reading->ServicePeriod)
            ->where('AccountNumber', $account->id)
            ->get();
        
        $previousBills = DB::table('Billing_Bills')
            ->where('AccountNumber', $reading->AccountNumber)
            ->orderByDesc('ServicePeriod')
            ->limit(3)
            ->get();

        $meterInfo = DB::table('Billing_Meters')
            ->where('ServiceAccountId', $reading->AccountNumber)
            ->orderByDesc('created_at')
            ->first();

        $pendingAdjustments = PendingBillAdjustments::where('ReadingId', $readingId)->first();

        return view('/bills/zero_readings_view', [
            'reading' => $reading,
            'account' => $account,
            'images' => $images,
            'previousBills' => $previousBills,
            'meterInfo' => $meterInfo,
            'pendingAdjustments' => $pendingAdjustments,
        ]);
    }

    /**
     * AVERAGE BILL
     */
    public function averageBill($readingId) {
        $reading = Readings::find($readingId);
        $account = ServiceAccounts::find($reading->AccountNumber);
        $previousBills = DB::table('Billing_Bills')
            ->where('AccountNumber', $reading->AccountNumber)
            ->orderByDesc('ServicePeriod')
            ->limit(3)
            ->get();
        $previousBill = DB::table('Billing_Bills')
            ->where('AccountNumber', $reading->AccountNumber)
            ->orderByDesc('ServicePeriod')
            ->first();
        $rate = Rates::where('ServicePeriod', $reading->ServicePeriod)
            ->where('AreaCode', $account->Town)
            ->where('ConsumerType', $account->AccountType)
            ->first();
        $meterInfo = DB::table('Billing_Meters')
            ->where('ServiceAccountId', $reading->AccountNumber)
            ->orderByDesc('created_at')
            ->first();
        $arrearLedgers = ArrearsLedgerDistribution::where('AccountNumber', $reading->AccountNumber)
            ->where('ServicePeriod', $reading->ServicePeriod)
            ->first();

        if ($rate != null) {
            if (count($previousBills) > 2) {
                // GET AVERAGE KWH
                $average = 0.0;
                foreach($previousBills as $item) {
                    $average += floatval($item->KwhUsed);
                }
                $average = $average/count($previousBills);

                $presKwh = floatval($previousBill->PresentKwh) + intval($average);

                $bill = Bills::computeRegularBill($account, null, intval($average), $previousBill->PresentKwh, $presKwh, $reading->ServicePeriod, date('Y-m-d'), 0, 0, null);

                Flash::success('Bill to account ' . $account->ServiceAccountName . ' for period ' . date('F Y', strtotime($bill->ServicePeriod)) . ' averaged successfully!');

                return redirect(route('bills.unbilled-readings-console', [$reading->ServicePeriod]));
            } else {
                return abort(403, "NO ENOUGH PREVIOUS BILLS FOR AVERAGING");
            }
        } else {
            return abort(403, "No Rate for this service period yet.");
        }        
    }

    public function rebillReadingAdjustment($readingId) {
        $reading = Readings::find($readingId);
        $previousBill = DB::table('Billing_Bills')
            ->where('AccountNumber', $reading->AccountNumber)
            ->orderByDesc('ServicePeriod')
            ->first();

        return view('/bills/rebill_reading_adjustment', [
            'reading' => $reading,
            'previousBill' => $previousBill,
        ]);
    }

    public function rebill($readingId, Request $request) {
        $reading = Readings::find($readingId);
        // UPDATE READING FIRST
        $reading->update($request->all());

        $account = ServiceAccounts::find($reading->AccountNumber);
        $rate = Rates::where('ServicePeriod', $reading->ServicePeriod)
            ->where('AreaCode', $account->AreaCode)
            ->where('ConsumerType', $account->AccountType)
            ->first();
        $meterInfo = DB::table('Billing_Meters')
            ->where('ServiceAccountId', $reading->AccountNumber)
            ->orderByDesc('created_at')
            ->first();
        $arrearLedgers = ArrearsLedgerDistribution::where('AccountNumber', $reading->AccountNumber)
            ->where('ServicePeriod', $reading->ServicePeriod)
            ->first();
        $previousBill = DB::table('Billing_Bills')
            ->where('AccountNumber', $reading->AccountNumber)
            ->where('ServicePeriod', date('Y-m-d', strtotime($reading->ServicePeriod . ' -1 month')))
            ->orderByDesc('ServicePeriod')
            ->first();

        /**
         * CHECK IF PREVIOUS BILL IS NULL
         */
        $previousKwhUsed = 0.0;
        if ($previousBill != null) {
            $previousKwhUsed = floatval($previousBill->PresentKwh);
        } else {
            $previousKwhUsed = 0.0;
        }

        /**
         * CHECK IF RATE IS NOT YET AVAILABLE
         */
        if ($rate != null) {
            // SET IMPORTANT PARAMETERS
            $kwhUsed = floatval($reading->KwhUsed) - $previousKwhUsed;
            $multiplier = $account->Multiplier != null ? floatval($account->Multiplier) : 1;
            $coreloss = $account->Coreloss != null ? floatval($account->Coreloss) : 0;
            $effectiveRate = Rates::floatRate($rate->TotalRateVATIncluded);

            $totalKwh = ($kwhUsed * $multiplier) + $coreloss;

            $netAmount = $totalKwh * $effectiveRate;

            // DEDUCTIONS
            $seniorCitizen = Rates::floatRate($rate->SeniorCitizenSubsidy) * $netAmount;
            $deductions = $seniorCitizen;

            // ADDITIONAL CHARGES
            $arrears = $arrearLedgers != null ? floatval($arrearLedgers->Amount) : 0;
            $addOns = $arrears;

            // CHECK IF Bill for this period
            $bill = Bills::where('ServicePeriod', $reading->ServicePeriod)
                ->where('AccountNumber', $account->id)
                ->first();
            
            if ($bill != null) {
                if ($account->SeniorCitizen != null && $account->SeniorCitizen=="Yes" && $kwhUsed < 101) {
                    $bill->Deductions = $deductions;
                    $netAmount = $netAmount - $deductions;
                }
                $netAmount = $netAmount + $addOns;
                // SET BILLING VALUES
                $bill->KwhUsed = $kwhUsed;
                $bill->PreviousKwh = $previousBill != null ? $previousBill->PresentKwh : 0;
                $bill->PresentKwh = $reading->KwhUsed;
                $bill->KwhAmount = $effectiveRate * $kwhUsed;
                $bill->EffectiveRate = $effectiveRate;
                $bill->AdditionalCharges = $addOns;
                $bill->NetAmount = $netAmount;
                $bill->BillingDate = date('Y-m-d');
                $bill->ServiceDateFrom = $previousBill != null ? date('Y-m-d', strtotime($previousBill->ServiceDateTo . ' +1 day')) : date('Y-m-d');
                $bill->ServiceDateTo = date('Y-m-d', strtotime($reading->ReadingTimestamp));
                $bill->DueDate = Bills::createDueDate($bill->ServiceDateTo);
                $bill->MeterNumber = $meterInfo != null ? $meterInfo->SerialNumber : null;
                $bill->ConsumerType = $account->AccountType;
                $bill->BillType = $bill->ConsumerType;              

                $bill->GenerationSystemCharge = $kwhUsed * Rates::floatRate($rate->GenerationSystemCharge);
                $bill->TransmissionDeliveryChargeKW = $kwhUsed * Rates::floatRate($rate->TransmissionDeliveryChargeKW);
                $bill->TransmissionDeliveryChargeKWH = $kwhUsed * Rates::floatRate($rate->TransmissionDeliveryChargeKWH);
                $bill->SystemLossCharge = $kwhUsed * Rates::floatRate($rate->SystemLossCharge);
                $bill->DistributionDemandCharge = $kwhUsed * Rates::floatRate($rate->DistributionDemandCharge);
                $bill->DistributionSystemCharge = $kwhUsed * Rates::floatRate($rate->DistributionSystemCharge);
                $bill->SupplyRetailCustomerCharge = $kwhUsed * Rates::floatRate($rate->SupplyRetailCustomerCharge);
                $bill->SupplySystemCharge = $kwhUsed * Rates::floatRate($rate->SupplySystemCharge);
                $bill->MeteringRetailCustomerCharge = $kwhUsed * Rates::floatRate($rate->MeteringRetailCustomerCharge);
                $bill->MeteringSystemCharge = $kwhUsed * Rates::floatRate($rate->MeteringSystemCharge);
                $bill->RFSC = $kwhUsed * Rates::floatRate($rate->RFSC);
                $bill->LifelineRate = $kwhUsed * Rates::floatRate($rate->LifelineRate);
                $bill->InterClassCrossSubsidyCharge = $kwhUsed * Rates::floatRate($rate->InterClassCrossSubsidyCharge);
                $bill->PPARefund = $kwhUsed * Rates::floatRate($rate->PPARefund);
                $bill->SeniorCitizenSubsidy = $kwhUsed * Rates::floatRate($rate->SeniorCitizenSubsidy);
                $bill->MissionaryElectrificationCharge = $kwhUsed * Rates::floatRate($rate->MissionaryElectrificationCharge);
                $bill->EnvironmentalCharge = $kwhUsed * Rates::floatRate($rate->EnvironmentalCharge);
                $bill->StrandedContractCosts = $kwhUsed * Rates::floatRate($rate->StrandedContractCosts);
                $bill->NPCStrandedDebt = $kwhUsed * Rates::floatRate($rate->NPCStrandedDebt);
                $bill->FeedInTariffAllowance = $kwhUsed * Rates::floatRate($rate->FeedInTariffAllowance);
                $bill->MissionaryElectrificationREDCI = $kwhUsed * Rates::floatRate($rate->MissionaryElectrificationREDCI);
                $bill->GenerationVAT = $kwhUsed * Rates::floatRate($rate->GenerationVAT);
                $bill->TransmissionVAT = $kwhUsed * Rates::floatRate($rate->TransmissionVAT);
                $bill->SystemLossVAT = $kwhUsed * Rates::floatRate($rate->SystemLossVAT);
                $bill->DistributionVAT = $kwhUsed * Rates::floatRate($rate->DistributionVAT);
                $bill->RealPropertyTax = $kwhUsed * Rates::floatRate($rate->RealPropertyTax);
                $bill->BilledFrom = 'WEB';
                $bill->UserId = Auth::id();

                // SAVE BILL
                $bill->save();
            } else {
                // SET BILLING VALUES
                $bill = new Bills;
                $bill->id = IDGenerator::generateIDandRandString();
                $bill->BillNumber = IDGenerator::generateBillNumber($account->AreaCode);
                $bill->AccountNumber = $account->id;
                $bill->ServicePeriod = $reading->ServicePeriod;
                
                if ($account->SeniorCitizen != null && $account->SeniorCitizen=="Yes" && $kwhUsed < 101) {
                    $bill->Deductions = $deductions;
                    $netAmount = $netAmount - $deductions;
                }
                $netAmount = $netAmount + $addOns;
                $bill->Multiplier = $multiplier;
                $bill->Coreloss = $coreloss;
                $bill->KwhUsed = $kwhUsed;
                $bill->PreviousKwh = $previousBill != null ? $previousBill->PresentKwh : 0;
                $bill->PresentKwh = $reading->KwhUsed;
                $bill->KwhAmount = $effectiveRate * $kwhUsed;
                $bill->EffectiveRate = $effectiveRate;
                $bill->AdditionalCharges = $addOns;
                $bill->NetAmount = $netAmount;
                $bill->BillingDate = date('Y-m-d');
                $bill->ServiceDateFrom = $previousBill != null ? date('Y-m-d', strtotime($previousBill->ServiceDateTo . ' +1 day')) : date('Y-m-d');
                $bill->ServiceDateTo = date('Y-m-d', strtotime($reading->ReadingTimestamp));
                $bill->DueDate = Bills::createDueDate($bill->ServiceDateTo);
                $bill->MeterNumber = $meterInfo != null ? $meterInfo->SerialNumber : null;
                $bill->ConsumerType = $account->AccountType;
                $bill->BillType = $bill->ConsumerType;

                $bill->GenerationSystemCharge = $kwhUsed * Rates::floatRate($rate->GenerationSystemCharge);
                $bill->TransmissionDeliveryChargeKW = $kwhUsed * Rates::floatRate($rate->TransmissionDeliveryChargeKW);
                $bill->TransmissionDeliveryChargeKWH = $kwhUsed * Rates::floatRate($rate->TransmissionDeliveryChargeKWH);
                $bill->SystemLossCharge = $kwhUsed * Rates::floatRate($rate->SystemLossCharge);
                $bill->DistributionDemandCharge = $kwhUsed * Rates::floatRate($rate->DistributionDemandCharge);
                $bill->DistributionSystemCharge = $kwhUsed * Rates::floatRate($rate->DistributionSystemCharge);
                $bill->SupplyRetailCustomerCharge = $kwhUsed * Rates::floatRate($rate->SupplyRetailCustomerCharge);
                $bill->SupplySystemCharge = $kwhUsed * Rates::floatRate($rate->SupplySystemCharge);
                $bill->MeteringRetailCustomerCharge = $kwhUsed * Rates::floatRate($rate->MeteringRetailCustomerCharge);
                $bill->MeteringSystemCharge = $kwhUsed * Rates::floatRate($rate->MeteringSystemCharge);
                $bill->RFSC = $kwhUsed * Rates::floatRate($rate->RFSC);
                $bill->LifelineRate = $kwhUsed * Rates::floatRate($rate->LifelineRate);
                $bill->InterClassCrossSubsidyCharge = $kwhUsed * Rates::floatRate($rate->InterClassCrossSubsidyCharge);
                $bill->PPARefund = $kwhUsed * Rates::floatRate($rate->PPARefund);
                $bill->SeniorCitizenSubsidy = $kwhUsed * Rates::floatRate($rate->SeniorCitizenSubsidy);
                $bill->MissionaryElectrificationCharge = $kwhUsed * Rates::floatRate($rate->MissionaryElectrificationCharge);
                $bill->EnvironmentalCharge = $kwhUsed * Rates::floatRate($rate->EnvironmentalCharge);
                $bill->StrandedContractCosts = $kwhUsed * Rates::floatRate($rate->StrandedContractCosts);
                $bill->NPCStrandedDebt = $kwhUsed * Rates::floatRate($rate->NPCStrandedDebt);
                $bill->FeedInTariffAllowance = $kwhUsed * Rates::floatRate($rate->FeedInTariffAllowance);
                $bill->MissionaryElectrificationREDCI = $kwhUsed * Rates::floatRate($rate->MissionaryElectrificationREDCI);
                $bill->GenerationVAT = $kwhUsed * Rates::floatRate($rate->GenerationVAT);
                $bill->TransmissionVAT = $kwhUsed * Rates::floatRate($rate->TransmissionVAT);
                $bill->SystemLossVAT = $kwhUsed * Rates::floatRate($rate->SystemLossVAT);
                $bill->DistributionVAT = $kwhUsed * Rates::floatRate($rate->DistributionVAT);
                $bill->RealPropertyTax = $kwhUsed * Rates::floatRate($rate->RealPropertyTax);
                $bill->BilledFrom = 'WEB';
                $bill->UserId = Auth::id();

                // SAVE BILL
                $bill->save();
            }            

            Flash::success('Bill to account ' . $account->ServiceAccountName . ' for period ' . date('F Y', strtotime($bill->ServicePeriod)) . ' averaged successfully!');

            return redirect(route('bills.unbilled-readings-console', [$reading->ServicePeriod]));
        } else {
            return abort(404, "No Rate for this service period yet.");
        }
    }

    public function adjustBill($billNumber) {
        $bill = Bills::find($billNumber);

        $account = ServiceAccounts::find($bill->AccountNumber);

        $ocl = ArrearsLedgerDistribution::where('AccountNumber', $account->id)
            ->where('ServicePeriod', $bill->ServicePeriod)
            ->first();

        return view('/bills/adjust_bill', [
            'bill' => $bill,
            'account' => $account,
            'ocl' => $ocl
        ]);
    }

    public function adjustBillNetMetering($billNumber) {
        $bill = Bills::find($billNumber);

        $account = ServiceAccounts::find($bill->AccountNumber);

        $ocl = ArrearsLedgerDistribution::where('AccountNumber', $account->id)
            ->where('ServicePeriod', $bill->ServicePeriod)
            ->first();

        return view('/bills/adjust_bill_net_metering', [
            'bill' => $bill,
            'account' => $account,
            'ocl' => $ocl
        ]);
    }

    public function fetchBillAdjustmentData(Request $request) {
        $bill = Bills::find($request['BillId']);

        $account = ServiceAccounts::find($request['AccountNumber']);

        $additionalCharges = $request['AdditionalCharges'] != null ? floatval($request['AdditionalCharges']) : 0;
        $deductions = $request['Deductions'] != null ? floatval($request['Deductions']) : 0;

        if (Bills::isHighVoltage(Bills::getAccountType($account))) {
            if ($account->Contestable=='Yes') {
                $bills = Bills::computeContestableAndDontSave($account, $bill->id, $request['KwhUsed'], $bill->PreviousKwh, $bill->PresentKwh, $bill->ServicePeriod, $bill->BillingDate, $additionalCharges, $deductions, $request['Is2307'], $request['Demand']);
            } else {
                $bills = Bills::computeHighVoltageBillAndDontSave($account, $bill->id, $request['KwhUsed'], $bill->PreviousKwh, $bill->PresentKwh, $bill->ServicePeriod, $bill->BillingDate, $additionalCharges, $deductions, $request['Is2307'], $request['Demand']);
            }            
        } else {
            if ($account->Contestable=='Yes') {
                $bills = Bills::computeContestableAndDontSave($account, $bill->id, $request['KwhUsed'], $bill->PreviousKwh, $bill->PresentKwh, $bill->ServicePeriod, $bill->BillingDate, $additionalCharges, $deductions, $request['Is2307'], $request['Demand']);
            } else {
                $bills = Bills::getRegularBill($account, $bill->id, $request['KwhUsed'], $bill->PreviousKwh, $bill->PresentKwh, $bill->ServicePeriod, $bill->BillingDate, $additionalCharges, $deductions, $request['Is2307'], false);
            }
        }        

        return response()->json($bills, 200);
    }

    public function fetchNetMeteringBillAdjustmentData(Request $request) {
        $bill = Bills::find($request['BillId']);

        $account = ServiceAccounts::find($request['AccountNumber']);

        $additionalCharges = $request['AdditionalCharges'] != null ? floatval($request['AdditionalCharges']) : 0;
        $deductions = $request['Deductions'] != null ? floatval($request['Deductions']) : 0;

        $bills = Bills::computeNetMeteringBillAndDontSave($account, $bill->id, $request['KwhUsed'], $bill->PreviousKwh, $bill->PresentKwh, $request['SolarExportKwh'], $bill->SolarExportPrevious, $bill->SolarExportPresent, $bill->ServicePeriod, $bill->BillingDate, $additionalCharges, $deductions, $request['Is2307'], $request['Demand']);       

        return response()->json($bills, 200);
    }

    public function allBills(Request $request) {
        if ($request['params'] == null) {
            $bills = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('Cashier_PaidBills', 'Billing_Bills.id', '=', 'Cashier_PaidBills.ObjectSourceId')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("ForCancellation != 'SALES_REPORT' OR ForCancellation IS NULL")
                        ->select('Billing_ServiceAccounts.ServiceAccountName', 
                            'Billing_ServiceAccounts.id as AccountNumber', 
                            'CRM_Towns.Town', 
                            'CRM_Barangays.Barangay', 
                            'Billing_ServiceAccounts.AccountCount',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_Bills.BillNumber',
                            'Billing_Bills.id',
                            'Billing_Bills.ConsumerType',
                            'Cashier_PaidBills.ORNumber',
                            'Billing_Bills.ServicePeriod')
                        ->orderByDesc('Billing_Bills.created_at')
                        ->limit(60)
                        ->paginate(15);
        } else {
            $bills = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('Cashier_PaidBills', 'Billing_Bills.id', '=', 'Cashier_PaidBills.ObjectSourceId')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("ForCancellation != 'SALES_REPORT' OR ForCancellation IS NULL")
                        ->select('Billing_ServiceAccounts.ServiceAccountName', 
                            'Billing_ServiceAccounts.id as AccountNumber', 
                            'CRM_Towns.Town', 
                            'CRM_Barangays.Barangay', 
                            'Billing_ServiceAccounts.AccountCount',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_Bills.BillNumber',
                            'Billing_Bills.id',
                            'Billing_Bills.ConsumerType',
                            'Cashier_PaidBills.ORNumber',
                            'Billing_Bills.ServicePeriod')
                        ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_Bills.BillNumber', 'LIKE', '%' . $request['params'] . '%')
                        ->orderByDesc('Billing_Bills.created_at')
                        ->paginate(15);
        }    

        return view('/bills/all_bills', [
            'bills' => $bills,
        ]);
    }

    public function billArrearsUnlocking() {
        $bills = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('users', 'Billing_Bills.SurchargeWaiveRequestedBy', '=', 'users.id')
            ->where('Billing_Bills.SurchargeWaived', 'PENDING APPROVAL')
            ->select('Billing_ServiceAccounts.ServiceAccountName',
                'users.name',
                'Billing_Bills.*')
            ->get();

        return view('/bills/bill_arrears_unlocking', [
            'bills' => $bills
        ]);
    }

    public function unlockBillArrear($id) {
        $bill = Bills::find($id);

        $bill->SurchargeWaived = 'APPROVED';
        $bill->SurchargeWaiveApprovedBy = Auth::id();
        $bill->save();

        return redirect(route('bills.bill-arrears-unlocking'));
    }

    public function rejectUnlockBillArrear($id) {
        $bill = Bills::find($id);

        $bill->SurchargeWaived = null;
        $bill->save();

        return redirect(route('bills.bill-arrears-unlocking'));
    }

    public function groupedBilling() {
        $accounts = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_MemberConsumers', 'Billing_ServiceAccounts.MemberConsumerId', '=', 'CRM_MemberConsumers.id')
            ->whereNotNull('Billing_ServiceAccounts.MemberConsumerId')
            ->select('CRM_MemberConsumers.FirstName',
                'CRM_MemberConsumers.MiddleName',
                'CRM_MemberConsumers.LastName',
                'CRM_MemberConsumers.Suffix',
                'CRM_MemberConsumers.OrganizationName',
                'CRM_MemberConsumers.MembershipType',
                'Billing_ServiceAccounts.MemberConsumerId',
                DB::raw("COUNT (Billing_ServiceAccounts.id) AS NoOfAccounts"))
            ->groupBy('CRM_MemberConsumers.FirstName',
                'CRM_MemberConsumers.MiddleName',
                'CRM_MemberConsumers.LastName',
                'CRM_MemberConsumers.Suffix',
                'CRM_MemberConsumers.OrganizationName',
                'CRM_MemberConsumers.MembershipType',
                'Billing_ServiceAccounts.MemberConsumerId')
            ->get();

        return view('/bills/grouped_billing', [
            'accounts' => $accounts
        ]);
    }

    public function createGroupBillingStepOne() {
        $types = MemberConsumerTypes::orderByDesc('Id')->pluck('Type', 'Id');

        $barangays = Barangays::orderBy('Barangay')->pluck('Barangay', 'id');

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');
        return view('/bills/create_group_billing_step_one', [
            'types' => $types, 
            'barangays' => $barangays, 
            'towns' => $towns
        ]);
    }

    public function storeGroupBillingStepOne(Request $request) {
        $input = $request->all();

        $memberConsumers = MemberConsumers::create($input);

        return redirect(route('bills.create-group-billing-step-two', [$input['Id']]));
    }

    public function createGroupBillingStepTwo($memberConsumerId) {
        $accounts = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Billing_ServiceAccounts.MemberConsumerId', $memberConsumerId)
            ->select('Billing_ServiceAccounts.ServiceAccountName', 
                'Billing_ServiceAccounts.id', 
                'Billing_ServiceAccounts.Purok', 
                'Billing_ServiceAccounts.OldAccountNo', 
                'CRM_Towns.Town', 
                'CRM_Barangays.Barangay')
            ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
            ->get();

        return view('/bills/create_group_billing_step_two', [
            'memberConsumerId' => $memberConsumerId,
            'accounts' => $accounts,
        ]);
    }

    public function createGroupBillingStepOnePreSelect() {
        return view('/bills/create_group_billing_step_one_pre_select', [
        ]);
    }

    public function fetchMemberConsumers(Request $request) {
        $query = $request['query'];
            
        if ($query != '') {
            $data = DB::table('CRM_MemberConsumers')
                ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                ->select('CRM_MemberConsumers.Id as ConsumerId',
                                'CRM_MemberConsumers.MembershipType as MembershipType', 
                                'CRM_MemberConsumers.FirstName as FirstName', 
                                'CRM_MemberConsumers.MiddleName as MiddleName', 
                                'CRM_MemberConsumers.LastName as LastName', 
                                'CRM_MemberConsumers.OrganizationName as OrganizationName', 
                                'CRM_MemberConsumers.Suffix as Suffix', 
                                'CRM_MemberConsumers.Birthdate as Birthdate', 
                                'CRM_MemberConsumers.Barangay as Barangay', 
                                'CRM_MemberConsumers.ApplicationStatus as ApplicationStatus',
                                'CRM_MemberConsumers.DateApplied as DateApplied', 
                                'CRM_MemberConsumers.CivilStatus as CivilStatus', 
                                'CRM_MemberConsumers.DateApproved as DateApproved', 
                                'CRM_MemberConsumers.ContactNumbers as ContactNumbers', 
                                'CRM_MemberConsumers.EmailAddress as EmailAddress',  
                                'CRM_MemberConsumers.Notes as Notes', 
                                'CRM_MemberConsumers.Gender as Gender', 
                                'CRM_MemberConsumers.Sitio as Sitio', 
                                'CRM_MemberConsumerTypes.*',
                                'CRM_Towns.Town as Town',
                                'CRM_Barangays.Barangay as Barangay')
                ->where('CRM_MemberConsumers.LastName', 'LIKE', '%' . $query . '%')
                ->orWhere('CRM_MemberConsumers.Id', 'LIKE', '%' . $query . '%')
                ->orWhere('CRM_MemberConsumers.OrganizationName', 'LIKE', '%' . $query . '%')
                ->orWhere('CRM_MemberConsumers.MiddleName', 'LIKE', '%' . $query . '%')
                ->orWhere('CRM_MemberConsumers.FirstName', 'LIKE', '%' . $query . '%')
                ->orderBy('CRM_MemberConsumers.FirstName')
                ->get();
        } else {
            $data = DB::table('CRM_MemberConsumers')
                ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
                ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
                ->select('CRM_MemberConsumers.Id as ConsumerId',
                                'CRM_MemberConsumers.MembershipType as MembershipType', 
                                'CRM_MemberConsumers.FirstName as FirstName', 
                                'CRM_MemberConsumers.MiddleName as MiddleName', 
                                'CRM_MemberConsumers.LastName as LastName', 
                                'CRM_MemberConsumers.OrganizationName as OrganizationName', 
                                'CRM_MemberConsumers.Suffix as Suffix', 
                                'CRM_MemberConsumers.Birthdate as Birthdate', 
                                'CRM_MemberConsumers.Barangay as Barangay', 
                                'CRM_MemberConsumers.ApplicationStatus as ApplicationStatus',
                                'CRM_MemberConsumers.DateApplied as DateApplied', 
                                'CRM_MemberConsumers.CivilStatus as CivilStatus', 
                                'CRM_MemberConsumers.DateApproved as DateApproved', 
                                'CRM_MemberConsumers.ContactNumbers as ContactNumbers', 
                                'CRM_MemberConsumers.EmailAddress as EmailAddress',  
                                'CRM_MemberConsumers.Notes as Notes', 
                                'CRM_MemberConsumers.Gender as Gender', 
                                'CRM_MemberConsumers.Sitio as Sitio', 
                                'CRM_MemberConsumerTypes.*',
                                'CRM_Towns.Town as Town',
                                'CRM_Barangays.Barangay as Barangay')
                ->orderByDesc('CRM_MemberConsumers.created_at')
                ->take(30)
                ->get();
        }

        $output = "";
        foreach($data as $item) {
            $output .= '<tr>
                            <td>' . $item->ConsumerId . '</td>
                            <td>' . MemberConsumers::serializeMemberName($item) . '</td>
                            <td>' . $item->Barangay . ', ' . $item->Town . '</td>
                            <td>
                                <a href="' . route('bills.create-group-billing-step-two', [$item->ConsumerId]) . '" class="btn btn-xs btn-primary">Proceed</a>
                            <td>
                        </tr>';
        }
        
        return response()->json($output, 200);
    }

    public function searchAccount(Request $request) {
        $results = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->whereNull('Billing_ServiceAccounts.MemberConsumerId')
            ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['query'] . '%')
            ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['query'] . '%')
            ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['query'] . '%')
            ->select('Billing_ServiceAccounts.id',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
            ->get();

        $output = "";

        if (count($results) > 0) {
            foreach($results as $item) {
                $output .= '
                        <tr>
                            <td>' . $item->id . '</td>
                            <td>' . $item->OldAccountNo . '</td>
                            <td>' . $item->ServiceAccountName . '</td>
                            <td>' . ServiceAccounts::getAddress($item) . '</td>
                            <td>
                                <button class="btn btn-link text-primary" onclick=addToGroup("' . $item->id . '")><i class="fas fa-plus"></i></button>
                            </td>
                        </tr>
                    '; 
            }

            return response()->json($output, 200);
        } else {
            return response()->json([], 200);
        }        
    }

    public function addToGroup(Request $request) {
        $account = ServiceAccounts::find($request['id']);

        if ($account != null) {
            $account->MemberConsumerId = $request['MemberConsumerId'];
            $account->save();
        }

        return response()->json($account, 200);
    }

    public function removeFromGroup(Request $request) {
        $account = ServiceAccounts::find($request['id']);

        if ($account != null) {
            $account->MemberConsumerId = null;
            $account->save();
        }

        return response()->json($account, 200);
    }

    public function groupedBillingView($memberConsumerId) {
        $accounts = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->select('Billing_ServiceAccounts.ServiceAccountName', 
                'Billing_ServiceAccounts.id', 
                'Billing_ServiceAccounts.Purok', 
                'Billing_ServiceAccounts.OldAccountNo', 
                'CRM_Towns.Town', 
                'CRM_Barangays.Barangay')
            ->orderBy('Billing_ServiceAccounts.OldAccountNo')
            ->where('Billing_ServiceAccounts.MemberConsumerId', $memberConsumerId)
            ->get();
        $memberConsumer = DB::table('CRM_MemberConsumers')
            ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
            ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
            ->select('CRM_MemberConsumers.Id as ConsumerId',
                    'CRM_MemberConsumers.MembershipType as MembershipType', 
                    'CRM_MemberConsumers.FirstName as FirstName', 
                    'CRM_MemberConsumers.MiddleName as MiddleName', 
                    'CRM_MemberConsumers.LastName as LastName', 
                    'CRM_MemberConsumers.OrganizationName as OrganizationName', 
                    'CRM_MemberConsumers.Suffix as Suffix', 
                    'CRM_MemberConsumers.Birthdate as Birthdate', 
                    'CRM_MemberConsumers.Barangay as Barangay', 
                    'CRM_MemberConsumers.ApplicationStatus as ApplicationStatus',
                    'CRM_MemberConsumers.DateApplied as DateApplied', 
                    'CRM_MemberConsumers.CivilStatus as CivilStatus', 
                    'CRM_MemberConsumers.DateApproved as DateApproved', 
                    'CRM_MemberConsumers.ContactNumbers as ContactNumbers', 
                    'CRM_MemberConsumers.EmailAddress as EmailAddress',  
                    'CRM_MemberConsumers.Notes as Notes', 
                    'CRM_MemberConsumers.Gender as Gender', 
                    'CRM_MemberConsumers.Sitio as Sitio', 
                    'CRM_MemberConsumerTypes.*',
                    'CRM_Towns.Town as Town',
                    'CRM_Barangays.Barangay as Barangay')
            ->where('CRM_MemberConsumers.Id', $memberConsumerId)
            ->first();
        $ledgers = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_ServiceAccounts.MemberConsumerId', $memberConsumerId)
            ->select('Billing_ServiceAccounts.MemberConsumerId',
                'Billing_Bills.ServicePeriod',
                DB::raw("COUNT(Billing_Bills.id) AS BillCount"))
            ->groupBy('Billing_ServiceAccounts.MemberConsumerId',
                'Billing_Bills.ServicePeriod')
            ->orderByDesc('Billing_Bills.ServicePeriod')
            ->get();

        $rate = Rates::orderByDesc('ServicePeriod')
            ->first();

        return view('/bills/grouped_billing_view', [
            'accounts' => $accounts,
            'memberConsumer' => $memberConsumer,
            'ledgers' => $ledgers,
            'rate' => $rate,
        ]);
    }

    public function groupedBillingBillView($memberConsumerId, $period) {
        $ledgers = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_ServiceAccounts.MemberConsumerId', $memberConsumerId)
            ->where('Billing_Bills.ServicePeriod', $period)
            // ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND Status IS NULL)")
            // ->whereNotIn('Billing_Bills.AccountNumber', DB::table('Cashier_PaidBills')->where('ServicePeriod', $period)->whereNull('Status')->pluck('AccountNumber'))
            ->select('Billing_ServiceAccounts.MemberConsumerId',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_Bills.*',
                'Billing_ServiceAccounts.ServiceAccountName')
            ->get();

        $memberConsumer = DB::table('CRM_MemberConsumers')
            ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
            ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
            ->select('CRM_MemberConsumers.Id as ConsumerId',
                    'CRM_MemberConsumers.MembershipType as MembershipType', 
                    'CRM_MemberConsumers.FirstName as FirstName', 
                    'CRM_MemberConsumers.MiddleName as MiddleName', 
                    'CRM_MemberConsumers.LastName as LastName', 
                    'CRM_MemberConsumers.OrganizationName as OrganizationName', 
                    'CRM_MemberConsumers.Suffix as Suffix', 
                    'CRM_MemberConsumers.Birthdate as Birthdate', 
                    'CRM_MemberConsumers.Barangay as Barangay', 
                    'CRM_MemberConsumers.ApplicationStatus as ApplicationStatus',
                    'CRM_MemberConsumers.DateApplied as DateApplied', 
                    'CRM_MemberConsumers.CivilStatus as CivilStatus', 
                    'CRM_MemberConsumers.DateApproved as DateApproved', 
                    'CRM_MemberConsumers.ContactNumbers as ContactNumbers', 
                    'CRM_MemberConsumers.EmailAddress as EmailAddress',  
                    'CRM_MemberConsumers.Notes as Notes', 
                    'CRM_MemberConsumers.Gender as Gender', 
                    'CRM_MemberConsumers.Sitio as Sitio', 
                    'CRM_MemberConsumerTypes.*',
                    'CRM_Towns.Town as Town',
                    'CRM_Barangays.Barangay as Barangay')
            ->where('CRM_MemberConsumers.Id', $memberConsumerId)
            ->first();

        return view('/bills/group_billing_bill_view', [
            'ledgers' => $ledgers,
            'memberConsumer' => $memberConsumer,
            'servicePeriod' => $period
        ]);
    }

    public function groupBillingBillAll(Request $request) {
        $period = $request['ServicePeriod'];
        $groupId = $request['GroupId'];
        $kwhUsed = $request['KwhUsed'];
        $demand = $request['Demand'];

        if ($groupId != null) {
            $accounts = DB::table('Billing_ServiceAccounts')
                ->select('*')
                ->where('MemberConsumerId', $groupId)
                ->get();

            foreach ($accounts as $item) {
                $prevBill = DB::table("Billing_Bills")
                    ->where('AccountNumber', $item->id)
                    ->whereRaw("ServicePeriod < '" . $period . "'")
                    ->orderByDesc('ServicePeriod')
                    ->first();

                if ($prevBill != null) {
                    $prevReading = $prevBill->PresentKwh != null ? floatval($prevBill->PresentKwh) : 0;
                } else {
                    $prevReading = 0;
                }

                $presReading = $prevReading + floatval($kwhUsed);

                if (Bills::isHighVoltage(Bills::getAccountType($item))) {
                    // CREATE READING
                    $readings = Readings::where('AccountNumber', $item->id)
                        ->where('ServicePeriod', $period)
                        ->first();
    
                    if ($readings != null) {
                        $readings->AccountNumber = $item->id;
                        $readings->KwhUsed = $presReading;
                        $readings->DemandKwhUsed = $demand;
                        // $readings->MeterReader = Auth::id();
                        $readings->save();
                    } else {
                        $readings = new Readings;
                        $readings->id = IDGenerator::generateIDandRandString();
                        $readings->AccountNumber = $item->id;
                        $readings->ServicePeriod = $period;
                        $readings->ReadingTimestamp = date('Y-m-d H:i:s');
                        $readings->KwhUsed = $presReading;
                        $readings->DemandKwhUsed = $demand;
                        $readings->MeterReader = Auth::id();
                        $readings->save();
                    }
                    
                    $bills = Bills::where('AccountNumber', $item->id)
                        ->where('ServicePeriod', $period)
                        ->first();
                    
                    if ($bills != null) {
                        $bills = Bills::computeHighVoltageBill($item, 
                            $bills->id, 
                            $kwhUsed, 
                            $prevReading, 
                            $presReading, 
                            $period, 
                            date('Y-m-d', strtotime($readings->ReadingTimestamp)), 
                            0, 
                            0, 
                            '',
                            $demand);
                    } else {
                        $bills = Bills::computeHighVoltageBill($item, 
                            null, 
                            $kwhUsed, 
                            $prevReading, 
                            $presReading, 
                            $period, 
                            date('Y-m-d', strtotime($readings->ReadingTimestamp)), 
                            0, 
                            0, 
                            '',
                            $demand);
                    }            
                } else {
                    // CREATE READING
                    $readings = Readings::where('AccountNumber', $item->id)
                        ->where('ServicePeriod', $period)
                        ->first();
    
                    if ($readings != null) {
                        $readings->AccountNumber = $item->id;
                        $readings->KwhUsed = $presReading;
                        // $readings->MeterReader = Auth::id();
                        $readings->save();
                    } else {
                        $readings = new Readings;
                        $readings->id = IDGenerator::generateIDandRandString();
                        $readings->AccountNumber = $item->id;
                        $readings->ServicePeriod = $period;
                        $readings->ReadingTimestamp = date('Y-m-d H:i:s');
                        $readings->KwhUsed = $presReading;
                        $readings->MeterReader = Auth::id();
                        $readings->save();
                    }
    
                    $bills = Bills::where('AccountNumber', $item->id)
                        ->where('ServicePeriod', $period)
                        ->first();
                    
                    if ($bills != null) {
                        $bills = Bills::computeRegularBill($item, 
                            $bills->id, 
                            $kwhUsed, 
                            $prevReading, 
                            $presReading, 
                            $period, 
                            date('Y-m-d', strtotime($readings->ReadingTimestamp)), 
                            0, 
                            0, 
                            '');
                    } else {
                        $bills = Bills::computeRegularBill($item, 
                            null, 
                            $kwhUsed, 
                            $prevReading, 
                            $presReading, 
                            $period, 
                            date('Y-m-d', strtotime($readings->ReadingTimestamp)), 
                            0, 
                            0, 
                            '');
                    }            
                }
            }
            return response()->json('ok', 200);
        } else {
            return response()->json('Account not found', 404);
        }
    }

    public function add2Percent(Request $request) {
        $memberConsumerId = $request['MemberConsumerId'];
        $period = $request['Period'];

        $ledgers = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_ServiceAccounts.MemberConsumerId', $memberConsumerId)
            ->where('Billing_Bills.ServicePeriod', $period)
            ->select('Billing_Bills.*')
            ->get();

        foreach($ledgers as $item) {
            Bills::add2Percent($item->id);
        }

        return response()->json('ok', 200);
    }

    public function remove2Percent(Request $request) {
        $memberConsumerId = $request['MemberConsumerId'];
        $period = $request['Period'];

        $ledgers = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_ServiceAccounts.MemberConsumerId', $memberConsumerId)
            ->where('Billing_Bills.ServicePeriod', $period)
            ->select('Billing_Bills.*')
            ->get();

        foreach($ledgers as $item) {
            Bills::remove2Percent($item->id);
        }

        return response()->json('ok', 200);
    }

    public function add5Percent(Request $request) {
        $memberConsumerId = $request['MemberConsumerId'];
        $period = $request['Period'];

        $ledgers = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_ServiceAccounts.MemberConsumerId', $memberConsumerId)
            ->where('Billing_Bills.ServicePeriod', $period)
            ->select('Billing_Bills.*')
            ->get();

        foreach($ledgers as $item) {
            Bills::add5Percent($item->id);
        }

        return response()->json('ok', 200);
    }

    public function remove5Percent(Request $request) {
        $memberConsumerId = $request['MemberConsumerId'];
        $period = $request['Period'];

        $ledgers = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_ServiceAccounts.MemberConsumerId', $memberConsumerId)
            ->where('Billing_Bills.ServicePeriod', $period)
            ->select('Billing_Bills.*')
            ->get();

        foreach($ledgers as $item) {
            Bills::remove5Percent($item->id);
        }

        return response()->json('ok', 200);
    }

    public function printGroupBilling($memberConsumerId, $period, $withSurcharge) {
        $ledgers = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_ServiceAccounts.MemberConsumerId', $memberConsumerId)
            ->where('Billing_Bills.ServicePeriod', $period)
            // ->whereNotIn('Billing_Bills.AccountNumber', DB::table('Cashier_PaidBills')->where('ServicePeriod', $period)->whereNull('Status')->pluck('AccountNumber'))
            ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND Status IS NULL)")
            ->select('Billing_ServiceAccounts.MemberConsumerId',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_Bills.*',
                'Billing_ServiceAccounts.ServiceAccountName')
            ->orderBy('OldAccountNo')
            ->get();

        $memberConsumer = DB::table('CRM_MemberConsumers')
            ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
            ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
            ->select('CRM_MemberConsumers.Id as ConsumerId',
                    'CRM_MemberConsumers.MembershipType as MembershipType', 
                    'CRM_MemberConsumers.FirstName as FirstName', 
                    'CRM_MemberConsumers.MiddleName as MiddleName', 
                    'CRM_MemberConsumers.LastName as LastName', 
                    'CRM_MemberConsumers.OrganizationName as OrganizationName', 
                    'CRM_MemberConsumers.Suffix as Suffix', 
                    'CRM_MemberConsumers.Birthdate as Birthdate', 
                    'CRM_MemberConsumers.Barangay as Barangay', 
                    'CRM_MemberConsumers.ApplicationStatus as ApplicationStatus',
                    'CRM_MemberConsumers.DateApplied as DateApplied', 
                    'CRM_MemberConsumers.CivilStatus as CivilStatus', 
                    'CRM_MemberConsumers.DateApproved as DateApproved', 
                    'CRM_MemberConsumers.ContactNumbers as ContactNumbers', 
                    'CRM_MemberConsumers.EmailAddress as EmailAddress',  
                    'CRM_MemberConsumers.Notes as Notes', 
                    'CRM_MemberConsumers.Gender as Gender', 
                    'CRM_MemberConsumers.Sitio as Sitio', 
                    'CRM_MemberConsumerTypes.*',
                    'CRM_Towns.Town as Town',
                    'CRM_Barangays.Barangay as Barangay')
            ->where('CRM_MemberConsumers.Id', $memberConsumerId)
            ->first();

        return view('/bills/print_group_billing', [
            'ledgers' => $ledgers,
            'memberConsumer' => $memberConsumer,
            'servicePeriod' => $period,
            'withSurcharge' => $withSurcharge,
        ]);
    }

    public function printSingleBillNewFormat($billId) {
        $bills = $this->billsRepository->find($billId);
        $account = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->select('Billing_ServiceAccounts.id',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.ForDistribution',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.Organization',
                    'Billing_ServiceAccounts.Main',
                    'Billing_ServiceAccounts.GroupCode',
                    'Billing_ServiceAccounts.Multiplier',
                    'Billing_ServiceAccounts.Coreloss',
                    'Billing_ServiceAccounts.ConnectionDate',
                    'Billing_ServiceAccounts.ServiceConnectionId',
                    'Billing_ServiceAccounts.SeniorCitizen',
                    'Billing_ServiceAccounts.Evat5Percent',
                    'Billing_ServiceAccounts.Ewt2Percent',
                    'Billing_ServiceAccounts.Contestable',
                    'Billing_ServiceAccounts.NetMetered',
                    'Billing_ServiceAccounts.AccountRetention',
                    'Billing_ServiceAccounts.DurationInMonths',
                    'Billing_ServiceAccounts.AccountExpiration',
                    'CRM_Towns.Town',
                    'Billing_ServiceAccounts.Town as TownCode',
                    'CRM_Barangays.Barangay')
            ->where('Billing_ServiceAccounts.id', $bills->AccountNumber)
            ->first();

        $meters = BillingMeters::where('ServiceAccountId', $bills->AccountNumber)
            ->orderByDesc('created_at')
            ->first();

        $rate = Rates::where('ServicePeriod', $bills->ServicePeriod)
            ->where('ConsumerType', Bills::getAccountType($account))
            ->where('AreaCode', $account->TownCode)
            ->first();

        $arrears = DB::table('Billing_Bills')
            ->where('AccountNumber', $account->id)
            ->whereRaw("AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=Billing_Bills.AccountNumber AND ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application'))")
            ->whereRaw("ServicePeriod != '" . $bills->ServicePeriod . "'")
            ->select(
                DB::raw("COUNT(id) AS Countx"),
                DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) AS Total")
            )
            ->groupBy('AccountNumber')
            ->first();

        return view('/bills/print_single_bill_new_format', [
            'bills' => $bills,
            'account' => $account,
            'meters' => $meters,
            'rate' => $rate,
            'arrears' => $arrears
        ]);
    }

    public function printSingleNetMetering($billId) {
        $bills = $this->billsRepository->find($billId);
        $account = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->select('Billing_ServiceAccounts.id',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.ForDistribution',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.Organization',
                    'Billing_ServiceAccounts.Main',
                    'Billing_ServiceAccounts.GroupCode',
                    'Billing_ServiceAccounts.Multiplier',
                    'Billing_ServiceAccounts.Coreloss',
                    'Billing_ServiceAccounts.ConnectionDate',
                    'Billing_ServiceAccounts.ServiceConnectionId',
                    'Billing_ServiceAccounts.SeniorCitizen',
                    'Billing_ServiceAccounts.Evat5Percent',
                    'Billing_ServiceAccounts.Ewt2Percent',
                    'Billing_ServiceAccounts.Contestable',
                    'Billing_ServiceAccounts.NetMetered',
                    'Billing_ServiceAccounts.AccountRetention',
                    'Billing_ServiceAccounts.DurationInMonths',
                    'Billing_ServiceAccounts.AccountExpiration',
                    'CRM_Towns.Town',
                    'Billing_ServiceAccounts.Town as TownCode',
                    'CRM_Barangays.Barangay')
            ->where('Billing_ServiceAccounts.id', $bills->AccountNumber)
            ->first();

        $meters = BillingMeters::where('ServiceAccountId', $bills->AccountNumber)
            ->orderByDesc('created_at')
            ->first();

        $rate = Rates::where('ServicePeriod', $bills->ServicePeriod)
            ->where('ConsumerType', Bills::getAccountType($account))
            ->where('AreaCode', $account->TownCode)
            ->first();

        $arrears = DB::table('Billing_Bills')
            ->where('AccountNumber', $account->id)
            ->whereRaw("AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber='" . $account->id . "' AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application'))")
            ->whereRaw("ServicePeriod != '" . $bills->ServicePeriod . "'")
            ->select(
                DB::raw("COUNT(id) AS Countx"),
                DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) AS Total")
            )
            ->groupBy('AccountNumber')
            ->first();

        return view('/bills/print_single_net_metering', [
            'bills' => $bills,
            'account' => $account,
            'meters' => $meters,
            'rate' => $rate,
            'arrears' => $arrears
        ]);
    }

    public function printSingleBillOld($billId) {
        $bills = $this->billsRepository->find($billId);
        $account = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->select('Billing_ServiceAccounts.id',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.ForDistribution',
                    'Billing_ServiceAccounts.Organization',
                    'Billing_ServiceAccounts.Main',
                    'Billing_ServiceAccounts.GroupCode',
                    'Billing_ServiceAccounts.Multiplier',
                    'Billing_ServiceAccounts.Coreloss',
                    'Billing_ServiceAccounts.ConnectionDate',
                    'Billing_ServiceAccounts.ServiceConnectionId',
                    'Billing_ServiceAccounts.SeniorCitizen',
                    'Billing_ServiceAccounts.Evat5Percent',
                    'Billing_ServiceAccounts.Ewt2Percent',
                    'Billing_ServiceAccounts.Contestable',
                    'Billing_ServiceAccounts.NetMetered',
                    'Billing_ServiceAccounts.AccountRetention',
                    'Billing_ServiceAccounts.DurationInMonths',
                    'Billing_ServiceAccounts.AccountExpiration',
                    'CRM_Towns.Town',
                    'Billing_ServiceAccounts.Town as TownCode',
                    'CRM_Barangays.Barangay')
            ->where('Billing_ServiceAccounts.id', $bills->AccountNumber)
            ->first();

        $meters = BillingMeters::where('ServiceAccountId', $bills->AccountNumber)
            ->orderByDesc('created_at')
            ->first();

        $rate = Rates::where('ServicePeriod', $bills->ServicePeriod)
            ->where('ConsumerType', Bills::getAccountType($account))
            ->where('AreaCode', $account->TownCode)
            ->first();

        if ($account != null) {
            $arrears = DB::table('Billing_Bills')
                ->whereRaw("Billing_Bills.id NOT IN (SELECT ObjectSourceId FROM Cashier_PaidBills WHERE AccountNumber='" . $account->id . "')")
                ->where('Billing_Bills.AccountNumber', $account->id)
                ->whereNotIN('Billing_Bills.id', [$billId])
                ->select('Billing_Bills.*')
                ->get();
        } else {
            $arrears = null;
        }
        

        return view('/bills/print_single_bill_old', [
            'bills' => $bills,
            'account' => $account,
            'meters' => $meters,
            'rate' => $rate,
            'arrears' => $arrears
        ]);
    }

    public function printBulkBillOldFormat($servicePeriod, $town, $route) {
        $bills = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_Bills.ServicePeriod', $servicePeriod)
            ->where('Billing_ServiceAccounts.Town', $town)
            ->where('Billing_ServiceAccounts.AreaCode', $route)
            ->select('Billing_Bills.*')
            ->orderBy('Billing_Bills.BillNumber')
            ->get();

        return view('/bills/print_bulk_old_format', [
            'bills' => $bills
        ]);
    }

    public function printBulkBillOldFormatGroup($servicePeriod, $groupId) {
        $bills = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_Bills.ServicePeriod', $servicePeriod)
            ->where('Billing_ServiceAccounts.MemberConsumerId', $groupId)
            ->select('Billing_Bills.*')
            ->orderBy('Billing_Bills.BillNumber')
            ->get();

        return view('/bills/print_bulk_old_format', [
            'bills' => $bills
        ]);
    }

    public function printBulkBillOldFormatBapa($servicePeriod, $bapaName, $billNumberStart, $route) {
        $bapaName = urldecode($bapaName);
        if ($billNumberStart == 'All' && $route == 'All') {
            $bills = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                ->select('Billing_Bills.*')
                ->orderBy('Billing_ServiceAccounts.AreaCode')
                ->orderBy('Billing_Bills.BillNumber')
                ->get();
        } elseif ($billNumberStart == 'All' && $route != 'All') {
            $bills = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                ->where('Billing_ServiceAccounts.AreaCode', $route)
                ->select('Billing_Bills.*')
                ->orderBy('Billing_Bills.BillNumber')
                ->get();
        } elseif ($billNumberStart != 'All' && $route == 'All') {
            $getLast = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                ->select('Billing_Bills.*')
                ->orderByDesc('Billing_ServiceAccounts.AreaCode')
                ->orderByDesc('Billing_Bills.BillNumber')
                ->first();

            if ($getLast != null) {
                $bills = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                    ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                    ->whereBetween('Billing_Bills.BillNumber', [$billNumberStart, $getLast->BillNumber])
                    ->select('Billing_Bills.*')
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('Billing_Bills.BillNumber')
                    ->get();
            } else {
                $bills = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                    ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                    ->select('Billing_Bills.*')
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('Billing_Bills.BillNumber')
                    ->get();
            } 
        } else {
            $getLast = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                ->where('Billing_ServiceAccounts.AreaCode', $route)
                ->select('Billing_Bills.*')
                ->orderByDesc('Billing_ServiceAccounts.AreaCode')
                ->orderByDesc('Billing_Bills.BillNumber')
                ->first();

            if ($getLast != null) {
                $bills = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                    ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                    ->where('Billing_ServiceAccounts.AreaCode', $route)
                    ->whereBetween('Billing_Bills.BillNumber', [$billNumberStart, $getLast->BillNumber])
                    ->select('Billing_Bills.*')
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('Billing_Bills.BillNumber')
                    ->get();
            } else {
                $bills = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                    ->where('Billing_ServiceAccounts.AreaCode', $route)
                    ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                    ->select('Billing_Bills.*')
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('Billing_Bills.BillNumber')
                    ->get();
            }     
        }
        

        return view('/bills/print_bulk_old_format', [
            'bills' => $bills
        ]);
    }

    public function printBulkBillNewFormatBapa($servicePeriod, $bapaName, $billNumberStart, $route) {
        $bapaName = urldecode($bapaName);
        if ($billNumberStart == 'All' && $route == 'All') {
            $bills = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                ->select('Billing_Bills.*',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.ForDistribution',
                    'Billing_ServiceAccounts.Organization',
                    'Billing_ServiceAccounts.Main',
                    'Billing_ServiceAccounts.GroupCode',
                    'Billing_ServiceAccounts.Multiplier',
                    'Billing_ServiceAccounts.Coreloss',
                    'Billing_ServiceAccounts.ConnectionDate',
                    'Billing_ServiceAccounts.ServiceConnectionId',
                    'Billing_ServiceAccounts.SeniorCitizen',
                    'Billing_ServiceAccounts.Evat5Percent AS Account5Percent',
                    'Billing_ServiceAccounts.Ewt2Percent',
                    'Billing_ServiceAccounts.Contestable',
                    'Billing_ServiceAccounts.NetMetered',
                    'Billing_ServiceAccounts.AccountRetention',
                    'Billing_ServiceAccounts.DurationInMonths',
                    'Billing_ServiceAccounts.AccountExpiration',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_Bills.AccountNumber ORDER BY created_at DESC) AS MeterNumber"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod != '" . $servicePeriod . "' AND AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application'))) AS ArrearsCount"))
                ->orderBy('Billing_ServiceAccounts.AreaCode')
                ->orderBy('Billing_Bills.BillNumber')
                ->get();
        } elseif ($billNumberStart == 'All' && $route != 'All') {
            $bills = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                ->where('Billing_ServiceAccounts.AreaCode', $route)
                ->select('Billing_Bills.*',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.ForDistribution',
                    'Billing_ServiceAccounts.Organization',
                    'Billing_ServiceAccounts.Main',
                    'Billing_ServiceAccounts.GroupCode',
                    'Billing_ServiceAccounts.Multiplier',
                    'Billing_ServiceAccounts.Coreloss',
                    'Billing_ServiceAccounts.ConnectionDate',
                    'Billing_ServiceAccounts.ServiceConnectionId',
                    'Billing_ServiceAccounts.SeniorCitizen',
                    'Billing_ServiceAccounts.Evat5Percent AS Account5Percent',
                    'Billing_ServiceAccounts.Ewt2Percent',
                    'Billing_ServiceAccounts.Contestable',
                    'Billing_ServiceAccounts.NetMetered',
                    'Billing_ServiceAccounts.AccountRetention',
                    'Billing_ServiceAccounts.DurationInMonths',
                    'Billing_ServiceAccounts.AccountExpiration',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_Bills.AccountNumber ORDER BY created_at DESC) AS MeterNumber"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod != '" . $servicePeriod . "' AND AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application'))) AS ArrearsCount"))
                ->orderBy('Billing_Bills.BillNumber')
                ->get();
        } elseif ($billNumberStart != 'All' && $route == 'All') {
            $getLast = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                ->select('Billing_Bills.*')
                ->orderByDesc('Billing_ServiceAccounts.AreaCode')
                ->orderByDesc('Billing_Bills.BillNumber')
                ->first();

            if ($getLast != null) {
                $bills = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                    ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                    ->whereBetween('Billing_Bills.BillNumber', [$billNumberStart, $getLast->BillNumber])
                    ->select('Billing_Bills.*',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.ForDistribution',
                    'Billing_ServiceAccounts.Organization',
                    'Billing_ServiceAccounts.Main',
                    'Billing_ServiceAccounts.GroupCode',
                    'Billing_ServiceAccounts.Multiplier',
                    'Billing_ServiceAccounts.Coreloss',
                    'Billing_ServiceAccounts.ConnectionDate',
                    'Billing_ServiceAccounts.ServiceConnectionId',
                    'Billing_ServiceAccounts.SeniorCitizen',
                    'Billing_ServiceAccounts.Evat5Percent AS Account5Percent',
                    'Billing_ServiceAccounts.Ewt2Percent',
                    'Billing_ServiceAccounts.Contestable',
                    'Billing_ServiceAccounts.NetMetered',
                    'Billing_ServiceAccounts.AccountRetention',
                    'Billing_ServiceAccounts.DurationInMonths',
                    'Billing_ServiceAccounts.AccountExpiration',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_Bills.AccountNumber ORDER BY created_at DESC) AS MeterNumber"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod != '" . $servicePeriod . "' AND AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application'))) AS ArrearsCount"))
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('Billing_Bills.BillNumber')
                    ->get();
            } else {
                $bills = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                    ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                    ->select('Billing_Bills.*',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.ForDistribution',
                    'Billing_ServiceAccounts.Organization',
                    'Billing_ServiceAccounts.Main',
                    'Billing_ServiceAccounts.GroupCode',
                    'Billing_ServiceAccounts.Multiplier',
                    'Billing_ServiceAccounts.Coreloss',
                    'Billing_ServiceAccounts.ConnectionDate',
                    'Billing_ServiceAccounts.ServiceConnectionId',
                    'Billing_ServiceAccounts.SeniorCitizen',
                    'Billing_ServiceAccounts.Evat5Percent AS Account5Percent',
                    'Billing_ServiceAccounts.Ewt2Percent',
                    'Billing_ServiceAccounts.Contestable',
                    'Billing_ServiceAccounts.NetMetered',
                    'Billing_ServiceAccounts.AccountRetention',
                    'Billing_ServiceAccounts.DurationInMonths',
                    'Billing_ServiceAccounts.AccountExpiration',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_Bills.AccountNumber ORDER BY created_at DESC) AS MeterNumber"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod != '" . $servicePeriod . "' AND AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application'))) AS ArrearsCount"))
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('Billing_Bills.BillNumber')
                    ->get();
            } 
        } else {
            $getLast = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                ->where('Billing_ServiceAccounts.AreaCode', $route)
                ->select('Billing_Bills.*')
                ->orderByDesc('Billing_ServiceAccounts.AreaCode')
                ->orderByDesc('Billing_Bills.BillNumber')
                ->first();

            if ($getLast != null) {
                $bills = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                    ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                    ->where('Billing_ServiceAccounts.AreaCode', $route)
                    ->whereBetween('Billing_Bills.BillNumber', [$billNumberStart, $getLast->BillNumber])
                    ->select('Billing_Bills.*',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.ForDistribution',
                    'Billing_ServiceAccounts.Organization',
                    'Billing_ServiceAccounts.Main',
                    'Billing_ServiceAccounts.GroupCode',
                    'Billing_ServiceAccounts.Multiplier',
                    'Billing_ServiceAccounts.Coreloss',
                    'Billing_ServiceAccounts.ConnectionDate',
                    'Billing_ServiceAccounts.ServiceConnectionId',
                    'Billing_ServiceAccounts.SeniorCitizen',
                    'Billing_ServiceAccounts.Evat5Percent AS Account5Percent',
                    'Billing_ServiceAccounts.Ewt2Percent',
                    'Billing_ServiceAccounts.Contestable',
                    'Billing_ServiceAccounts.NetMetered',
                    'Billing_ServiceAccounts.AccountRetention',
                    'Billing_ServiceAccounts.DurationInMonths',
                    'Billing_ServiceAccounts.AccountExpiration',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_Bills.AccountNumber ORDER BY created_at DESC) AS MeterNumber"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod != '" . $servicePeriod . "' AND AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application'))) AS ArrearsCount"))
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('Billing_Bills.BillNumber')
                    ->get();
            } else {
                $bills = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->where('Billing_Bills.ServicePeriod', $servicePeriod)
                    ->where('Billing_ServiceAccounts.AreaCode', $route)
                    ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
                    ->select('Billing_Bills.*',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.ForDistribution',
                    'Billing_ServiceAccounts.Organization',
                    'Billing_ServiceAccounts.Main',
                    'Billing_ServiceAccounts.GroupCode',
                    'Billing_ServiceAccounts.Multiplier',
                    'Billing_ServiceAccounts.Coreloss',
                    'Billing_ServiceAccounts.ConnectionDate',
                    'Billing_ServiceAccounts.ServiceConnectionId',
                    'Billing_ServiceAccounts.SeniorCitizen',
                    'Billing_ServiceAccounts.Evat5Percent AS Account5Percent',
                    'Billing_ServiceAccounts.Ewt2Percent',
                    'Billing_ServiceAccounts.Contestable',
                    'Billing_ServiceAccounts.NetMetered',
                    'Billing_ServiceAccounts.AccountRetention',
                    'Billing_ServiceAccounts.DurationInMonths',
                    'Billing_ServiceAccounts.AccountExpiration',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_Bills.AccountNumber ORDER BY created_at DESC) AS MeterNumber"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod != Billing_Bills.ServicePeriod AND AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application'))) AS ArrearsCount"))
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('Billing_Bills.BillNumber')
                    ->get();
            }     
        }
        

        return view('/bills/print_bulk_bill_new_format', [
            'bills' => $bills
        ]);
    }

    public function bulkPrintBill() {
        $towns = Towns::orderBy('id')->get();
        return view('/bills/bulk_print_bill', [
            'towns' => $towns,
        ]);
    }

    public function getRoutesFromTown(Request $request) {
        $routes = DB::table('Billing_ServiceAccounts')
            ->where('Town', $request['Town'])
            ->select('AreaCode')
            ->groupBy('AreaCode')
            ->orderBy('AreaCode')
            ->get();

        $output = "";
        foreach($routes as $item) {
            $output .= '<option value="' . $item->AreaCode . '">' . $item->AreaCode . '</option>';
        }

        return response()->json($output, 200);
    }

    public function printBulkBillNewFormat($period, $town, $route, $day) {
        $bills = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Billing_Bills.ServicePeriod', $period)
            ->where('Billing_ServiceAccounts.Town', $town)
            ->where('Billing_ServiceAccounts.AreaCode', $route)
            ->whereRaw("Billing_Bills.UserId='" . Auth::id() ."'")
            ->where('Billing_Bills.BillingDate', $day)
            ->select('Billing_Bills.*',                
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.AccountCount',
                'Billing_ServiceAccounts.Purok',
                'Billing_ServiceAccounts.AccountType',
                'Billing_ServiceAccounts.AccountStatus',
                'Billing_ServiceAccounts.AreaCode',
                'Billing_ServiceAccounts.SequenceCode',
                'Billing_ServiceAccounts.ForDistribution',
                'Billing_ServiceAccounts.Organization',
                'Billing_ServiceAccounts.Main',
                'Billing_ServiceAccounts.GroupCode',
                'Billing_ServiceAccounts.Multiplier',
                'Billing_ServiceAccounts.Coreloss',
                'Billing_ServiceAccounts.ConnectionDate',
                'Billing_ServiceAccounts.ServiceConnectionId',
                'Billing_ServiceAccounts.SeniorCitizen',
                'Billing_ServiceAccounts.Evat5Percent',
                'Billing_ServiceAccounts.Ewt2Percent',
                'Billing_ServiceAccounts.Contestable',
                'Billing_ServiceAccounts.NetMetered',
                'Billing_ServiceAccounts.AccountRetention',
                'Billing_ServiceAccounts.DurationInMonths',
                'Billing_ServiceAccounts.AccountExpiration',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_Bills.AccountNumber ORDER BY created_at DESC) AS MeterNumber"),
                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE AccountNumber=Billing_Bills.AccountNumber AND ServicePeriod != Billing_Bills.ServicePeriod AND AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=Billing_Bills.AccountNumber)) AS ArrearsCount"))
            ->get();

        return view('/bills/print_bulk_bill_new_format', [
            'bills' => $bills
        ]);
    }

    public function printBulkBillNewFormatGroup($period, $groupId) {
        $bills = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Billing_Bills.ServicePeriod', $period)
            ->where('Billing_ServiceAccounts.MemberConsumerId', $groupId)
            ->select('Billing_Bills.*',                
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.AccountCount',
                'Billing_ServiceAccounts.Purok',
                'Billing_ServiceAccounts.AccountType',
                'Billing_ServiceAccounts.AccountStatus',
                'Billing_ServiceAccounts.AreaCode',
                'Billing_ServiceAccounts.SequenceCode',
                'Billing_ServiceAccounts.ForDistribution',
                'Billing_ServiceAccounts.Organization',
                'Billing_ServiceAccounts.Main',
                'Billing_ServiceAccounts.GroupCode',
                'Billing_ServiceAccounts.Multiplier',
                'Billing_ServiceAccounts.Coreloss',
                'Billing_ServiceAccounts.ConnectionDate',
                'Billing_ServiceAccounts.ServiceConnectionId',
                'Billing_ServiceAccounts.SeniorCitizen',
                // 'Billing_ServiceAccounts.Evat5Percent',
                'Billing_ServiceAccounts.Ewt2Percent',
                'Billing_ServiceAccounts.Contestable',
                'Billing_ServiceAccounts.NetMetered',
                'Billing_ServiceAccounts.AccountRetention',
                'Billing_ServiceAccounts.DurationInMonths',
                'Billing_ServiceAccounts.AccountExpiration',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_Bills.AccountNumber ORDER BY created_at DESC) AS MeterNumber"),
                DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE AccountNumber=Billing_Bills.AccountNumber AND ServicePeriod != Billing_Bills.ServicePeriod AND AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=Billing_Bills.AccountNumber)) AS ArrearsCount"))
            ->orderBy('OldAccountNo')
            ->get();

        return view('/bills/print_bulk_bill_new_format', [
            'bills' => $bills
        ]);
    }

    public function bapaManualBilling() {
        $towns = Towns::all();
        return view('/bills/bapa_manual_billing', [
            'towns' => $towns,
        ]);
    }

    public function searchBapaForBilling(Request $request) {
        $param = $request['BAPA'];
        $town = $request['Town'];

        if ($town == 'All') {
            $bapas = DB::table('Billing_ServiceAccounts AS sa')
            ->where('sa.OrganizationParentAccount', 'LIKE', '%' . $param . '%')
            ->select('sa.OrganizationParentAccount', 
                'sa.Town',
                DB::raw("COUNT(sa.id) AS NoOfAccounts"),
                DB::raw("(SELECT SUBSTRING((SELECT ',' + AreaCode AS 'data()' FROM Billing_ServiceAccounts WHERE OrganizationParentAccount=sa.OrganizationParentAccount GROUP BY AreaCode FOR XML PATH('')), 2 , 9999)) As Result"))
            ->groupBy('sa.OrganizationParentAccount', 
                'sa.Town')
            ->orderBy('sa.OrganizationParentAccount')
            ->get();
        } else {
            $bapas = DB::table('Billing_ServiceAccounts AS sa')
            ->where('sa.OrganizationParentAccount', 'LIKE', '%' . $param . '%')
            ->where('sa.Town', $town)
            ->select('sa.OrganizationParentAccount', 
                'sa.Town',
                DB::raw("COUNT(sa.id) AS NoOfAccounts"),
                DB::raw("(SELECT SUBSTRING((SELECT ',' + AreaCode AS 'data()' FROM Billing_ServiceAccounts WHERE OrganizationParentAccount=sa.OrganizationParentAccount GROUP BY AreaCode FOR XML PATH('')), 2 , 9999)) As Result"))
            ->groupBy('sa.OrganizationParentAccount', 
                'sa.Town')
            ->orderBy('sa.OrganizationParentAccount')
            ->get();
        }

        $output = "";
        foreach($bapas as $item) {
            if (strlen($item->OrganizationParentAccount) > 1) {
                $output .= '<tr>
                                <td><a href="' . route('bills.bapa-manual-billing-console', [urlencode($item->OrganizationParentAccount)]) . '">' . $item->OrganizationParentAccount . '</a></td>
                                <td>' . $item->Town . '</td>
                                <td>' . number_format($item->NoOfAccounts) . '</td>
                                <td>' . $item->Result . '</td>
                            </tr>';
            }
            
        }

        return response()->json($output, 200);
    }

    public function bapaManualBillingConsole($bapaName) {
        $latestRate = Rates::orderByDesc('ServicePeriod')
            ->first();

        return view('/bills/bapa_manual_billing_console', [
            'bapaName' => $bapaName,
            'rate' => $latestRate,
        ]);
    }

    public function getBillComputation(Request $request) {
        $account = ServiceAccounts::find($request['id']);

        $bill = Bills::computeRegularBillAndDontSave(
            $account, 
            null, 
            $request['KwhUsed'], 
            $request['PreviousKwh'], 
            $request['PresentKwh'], 
            $request['ServicePeriod'],
            date('Y-m-d'),
            0,
            0,
            false
        );

        return response()->json($bill, 200);
    }

    public function billManually(Request $request) {
        $account = ServiceAccounts::find($request['id']);

        if ($account->AccountStatus == 'ACTIVE') {
            $bill = Bills::computeRegularBill(
                $account, 
                null, 
                $request['KwhUsed'], 
                $request['PreviousKwh'], 
                $request['PresentKwh'], 
                $request['ServicePeriod'],
                date('Y-m-d'),
                0,
                0,
                false
            );
        } else {
            if (floatval($request['KwhUsed']) > 0) {
                $bill = Bills::computeRegularBill(
                    $account, 
                    null, 
                    $request['KwhUsed'], 
                    $request['PreviousKwh'], 
                    $request['PresentKwh'], 
                    $request['ServicePeriod'],
                    date('Y-m-d'),
                    0,
                    0,
                    false
                );
            } else {
                $bill = null;
            }            
        }    

        // save reading
        $reading = new Readings;
        $reading->id = IDGenerator::generateIDandRandString();
        $reading->AccountNumber = $account->id;
        $reading->ServicePeriod = $request['ServicePeriod'];
        $reading->ReadingTimestamp = date('Y-m-d H:i:s');
        $reading->KwhUsed = $request['PresentKwh'];
        $reading->Notes = $request['Remarks'];
        $reading->MeterReader = Auth::id();
        $reading->save();

        if ($bill != null) {
            $response = [];

            array_push($response, [
                'ServiceAccountName' => $account->ServiceAccountName,
                'id' => $account->id,
                'OldAccountNo' => $account->OldAccountNo,
                'PreviousKwh' => $bill->PreviousKwh,
                'PresentKwh' => $bill->PresentKwh,
                'KwhUsed' => $bill->KwhUsed,
                'NetAmount' => $bill->NetAmount,
                'BillId' => $bill->id,
                'ReadingId' => $reading->id,
            ]);

            return response()->json($response, 200);
        } else {
            $response = [];

            array_push($response, [
                'ServiceAccountName' => $account->ServiceAccountName,
                'id' => $account->id,
                'OldAccountNo' => $account->OldAccountNo,
                'PreviousKwh' => $request['PreviousKwh'],
                'PresentKwh' => $request['PresentKwh'],
                'KwhUsed' => $request['KwhUsed'],
                'NetAmount' => null,
                'BillId' => null,
                'ReadingId' => $reading->id,
            ]);

            return response()->json($response, 200);
        }
        
    }

    public function fetchBilledConsumersFromReading(Request $request) {
        $bills = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_ServiceAccounts.OrganizationParentAccount', $request['BAPAName'])
            ->where('Billing_Bills.ServicePeriod', $request['ServicePeriod'])
            ->select('Billing_Bills.*',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo')
            ->get();

        return response()->json($bills, 200);
    }

    public function requestCancelBill(Request $request) {
        $bill = Bills::find($request['id']);

        if ($bill != null) {
            $bill->ForCancellation = 'Yes';
            $bill->CancelRequestedBy = Auth::id();
            $bill->CancelApprovedBy = env('APP_BILLING_ANALYST_ID');
            $bill->Notes = $request['Remarks'];
            $bill->save();
        }

        return response()->json($bill, 200);
    }

    public function billsCancellationApproval() {
        $bills = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_Bills.ForCancellation', 'Yes')
            ->where('Billing_Bills.CancelApprovedBy', env('APP_BILLING_ANALYST_ID'))
            ->select('Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_Bills.*')
            ->get();

        return view('/bills/bills_cancellation_approval', [
            'bills' => $bills,
        ]);
    }

    public function approveBillCancellationRequest($id) {
        $bill = Bills::find($id);

        if ($bill != null) {
            // INSERT TO BILLS ORIGINAL
            $billsArr = $bill->toArray();
            $billsArr['id'] = IDGenerator::generateIDandRandString();
            $billsArr['ForCancellation'] = 'Cancelled';
            $billsArr['CancelApprovedBy'] = Auth::id();
            $billsArr['AdjustmentStatus'] = null;
            $billsOriginal = BillsOriginal::create($billsArr);

            $account = ServiceAccounts::find($bill->AccountNumber);
            $advMatDeposit = $bill->AdvancedMaterialDeposit;
            $customerDeposit = $bill->CustomerDeposit;

            $account->AdvancedMaterialDeposit = floatval($account->AdvancedMaterialDeposit) - floatval($advMatDeposit);
            $account->CustomerDeposit = floatval($account->CustomerDeposit) - floatval($customerDeposit);
            $account->save();

            
            // UPDATE TERMED PAYMENTS
            $ocl = ArrearsLedgerDistribution::where('AccountNumber', $bill->AccountNumber)
                ->where('ServicePeriod', $bill->ServicePeriod)
                ->get();
            foreach($ocl as $item) {
                $item->IsBilled = null;
                $item->save();
            }

            $bill->delete();
        }

        return redirect(route('bills.bills-cancellation-approval'));
    }

    public function rejectBillCancellationRequest($id) {
        $bill = Bills::find($id);

        if ($bill != null) {
            $bill->ForCancellation = null;
            $bill->CancelRequestedBy = null;
            $bill->CancelApprovedBy = null;
            $bill->Notes = null;
            $bill->save();
        }

        return redirect(route('bills.bills-cancellation-approval'));
    }

    public function changeMeterReadings($accountId, $period) {
        $account = ServiceAccounts::find($accountId);
        $reading = Readings::where('AccountNumber', $accountId)
            ->where('ServicePeriod', $period)
            ->first();
        $changeMeterLogs = ChangeMeterLogs::where('AccountNumber', $accountId)
            ->where('ServicePeriod', $period)
            ->first();

        $prevReadings = Readings::where('AccountNumber', $accountId)
            ->whereNotIn('ServicePeriod', [$period])
            ->orderByDesc('ServicePeriod')
            ->limit(15)
            ->get();

        return view('/bills/change_meter_readings', [
            'account' => $account,
            'reading' => $reading,
            'changeMeterLogs' => $changeMeterLogs,
            'prevReadings' => $prevReadings,
        ]);
    }

    public function billChangeMeters(Request $request) {
        $account = ServiceAccounts::find($request['AccountNumber']);
        $reading = Readings::where('AccountNumber', $request['AccountNumber'])
            ->where('ServicePeriod', $request['ServicePeriod'])
            ->first();
        $prevReading = Readings::where('AccountNumber', $request['AccountNumber'])
            ->where('ServicePeriod', date('Y-m-01', strtotime($request['ServicePeriod'] . ' -1 month')))
            ->first();

        $bill = Bills::computeRegularBill(
                $account, 
                null, 
                $request['KwhUsed'], 
                ($prevReading != null ? $prevReading->KwhUsed : '0'), 
                ($reading != null ? $reading->KwhUsed : '0'), 
                $request['ServicePeriod'],
                ($reading != null ? date('Y-m-d', strtotime($reading->ReadingTimestamp)) : date('Y-m-d')),
                0,
                0,
                false
            );
        
        return redirect(route('bills.unbilled-readings-console', $request['ServicePeriod']));
    }

    public function adjustmentReports(Request $request) {
        $type = $request['Type'];
        $period = $request['ServicePeriod'];

        if ($type != null && $period != null) {
            if ($type == 'All') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('users', 'users.id', '=', 'Billing_Bills.UserId')
                    ->where('Billing_Bills.BilledFrom', 'WEB')
                    ->whereRaw("Billing_Bills.UserId='" . Auth::id() . "'")
                    ->where('ServicePeriod', $period)
                    ->select('Billing_Bills.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'Billing_ServiceAccounts.AreaCode',
                        'users.name',
                        'CRM_Towns.Town', 
                        'CRM_Barangays.Barangay')
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('OldAccountNo')
                    ->get();
            } elseif ($type == 'Direct Adjustments') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('users', 'users.id', '=', 'Billing_Bills.UserId')
                    ->whereRaw("AdjustmentType='Direct Adjustment'")
                    ->where('ServicePeriod', $period)
                    ->select('Billing_Bills.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'Billing_ServiceAccounts.AreaCode',
                        'users.name',
                        'CRM_Towns.Town', 
                        'CRM_Barangays.Barangay')
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('OldAccountNo')
                    ->get();
            } elseif ($type == 'DM CM') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('users', 'users.id', '=', 'Billing_Bills.UserId')
                    ->whereRaw("AdjustmentType='DM/CM'")
                    ->where('ServicePeriod', $period)
                    ->select('Billing_Bills.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'Billing_ServiceAccounts.AreaCode',
                        'users.name',
                        'CRM_Towns.Town', 
                        'CRM_Barangays.Barangay')
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('OldAccountNo')
                    ->get();
            } elseif ($type == 'Application') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('users', 'users.id', '=', 'Billing_Bills.UserId')
                    ->whereRaw("AdjustmentType='Application'")
                    ->where('ServicePeriod', $period)
                    ->select('Billing_Bills.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'Billing_ServiceAccounts.AreaCode',
                        'users.name',
                        'CRM_Towns.Town', 
                        'CRM_Barangays.Barangay')
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('OldAccountNo')
                    ->get();
            } else {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('users', 'users.id', '=', 'Billing_Bills.UserId')
                    ->where('Billing_Bills.BilledFrom', 'WEB')
                    ->whereRaw("Billing_Bills.UserId='" . Auth::id() . "'")
                    ->where('ServicePeriod', $period)
                    ->whereNull('AdjustmentType')
                    ->select('Billing_Bills.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'Billing_ServiceAccounts.AreaCode',
                        'users.name',
                        'CRM_Towns.Town', 
                        'CRM_Barangays.Barangay')
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('OldAccountNo')
                    ->get();
            }
        } else {
            $data = [];
        }

        return view('/bills/adjustment_reports', [
            'data' => $data
        ]);
    }

    public function printAdjustmentReport($type, $period) {
        $type = urldecode($type);
        if ($type != null && $period != null) {
            if ($type == 'All') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('users', 'users.id', '=', 'Billing_Bills.UserId')
                    ->where('Billing_Bills.BilledFrom', 'WEB')
                    ->whereRaw("Billing_Bills.UserId='" . Auth::id() . "'")
                    ->where('ServicePeriod', $period)
                    ->select('Billing_Bills.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'Billing_ServiceAccounts.AreaCode',
                        'users.name',
                        'CRM_Towns.Town', 
                        'CRM_Barangays.Barangay')
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('OldAccountNo')
                    ->get();
            } elseif ($type == 'Direct Adjustments') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('users', 'users.id', '=', 'Billing_Bills.UserId')
                    ->whereRaw("AdjustmentType='Direct Adjustment'")
                    ->where('ServicePeriod', $period)
                    ->select('Billing_Bills.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'Billing_ServiceAccounts.AreaCode',
                        'users.name',
                        'CRM_Towns.Town', 
                        'CRM_Barangays.Barangay')
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('OldAccountNo')
                    ->get();
            } elseif ($type == 'DM CM') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('users', 'users.id', '=', 'Billing_Bills.UserId')
                    ->whereRaw("AdjustmentType='DM/CM'")
                    ->where('ServicePeriod', $period)
                    ->select('Billing_Bills.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'Billing_ServiceAccounts.AreaCode',
                        'users.name',
                        'CRM_Towns.Town', 
                        'CRM_Barangays.Barangay')
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('OldAccountNo')
                    ->get();
            } elseif ($type == 'Application') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('users', 'users.id', '=', 'Billing_Bills.UserId')
                    ->whereRaw("AdjustmentType='Application'")
                    ->where('ServicePeriod', $period)
                    ->select('Billing_Bills.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'Billing_ServiceAccounts.AreaCode',
                        'users.name',
                        'CRM_Towns.Town', 
                        'CRM_Barangays.Barangay')
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('OldAccountNo')
                    ->get();
            } else {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('users', 'users.id', '=', 'Billing_Bills.UserId')
                    ->where('Billing_Bills.BilledFrom', 'WEB')
                    ->whereRaw("Billing_Bills.UserId='" . Auth::id() . "'")
                    ->where('ServicePeriod', $period)
                    ->whereNull('AdjustmentType')
                    ->select('Billing_Bills.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'Billing_ServiceAccounts.AreaCode',
                        'users.name',
                        'CRM_Towns.Town', 
                        'CRM_Barangays.Barangay')
                    ->orderBy('Billing_ServiceAccounts.AreaCode')
                    ->orderBy('OldAccountNo')
                    ->get();
            }
        } else {
            $data = [];
        }

        return view('/bills/print_adjustment_report', [
            'data' => $data,
            'type' => $type,
            'period' => $period
        ]);
    }

    public function adjustmentReportsWithGL(Request $request) { 
        $area = $request['Area'];
        $period = $request['ServicePeriod'];

        if (isset($area) && isset($period)) {
            $data = DB::table('Billing_BillsOriginal')
                ->leftJoin('Billing_Bills', function($join) {
                    $join->on('Billing_BillsOriginal.AccountNumber', '=', 'Billing_Bills.AccountNumber')
                        ->on('Billing_BillsOriginal.ServicePeriod', '=', 'Billing_Bills.ServicePeriod');
                })
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('users', 'Billing_BillsOriginal.AdjustedBy', '=', 'users.id')
                ->where('Billing_ServiceAccounts.Town', $area)
                ->where('Billing_Bills.ServicePeriod', $period)
                ->select(
                    DB::raw("Billing_BillsOriginal.AccountNumber AS OriginalAccountNumber"),
                    'Billing_BillsOriginal.id AS Originalid',
                    'Billing_BillsOriginal.BillNumber AS OriginalBillNumber',
                    'Billing_BillsOriginal.ServicePeriod AS OriginalServicePeriod',
                    'Billing_BillsOriginal.Multiplier AS OriginalMultiplier',
                    'Billing_BillsOriginal.Coreloss AS OriginalCoreloss',
                    'Billing_BillsOriginal.KwhUsed AS OriginalKwhUsed',
                    'Billing_BillsOriginal.PreviousKwh AS OriginalPreviousKwh',
                    'Billing_BillsOriginal.PresentKwh AS OriginalPresentKwh',
                    'Billing_BillsOriginal.DemandPreviousKwh AS OriginalDemandPreviousKwh',
                    'Billing_BillsOriginal.DemandPresentKwh AS OriginalDemandPresentKwh',
                    'Billing_BillsOriginal.AdditionalKwh AS OriginalAdditionalKwh',
                    'Billing_BillsOriginal.AdditionalDemandKwh AS OriginalAdditionalDemandKwh',
                    'Billing_BillsOriginal.KwhAmount AS OriginalKwhAmount',
                    'Billing_BillsOriginal.EffectiveRate AS OriginalEffectiveRate',
                    'Billing_BillsOriginal.AdditionalCharges AS OriginalAdditionalCharges',
                    'Billing_BillsOriginal.Deductions AS OriginalDeductions',
                    'Billing_BillsOriginal.NetAmount AS OriginalNetAmount',
                    'Billing_BillsOriginal.BillingDate AS OriginalBillingDate',
                    'Billing_BillsOriginal.ServiceDateFrom AS OriginalServiceDateFrom',
                    'Billing_BillsOriginal.ServiceDateTo AS OriginalServiceDateTo',
                    'Billing_BillsOriginal.DueDate AS OriginalDueDate',
                    'Billing_BillsOriginal.MeterNumber AS OriginalMeterNumber',
                    'Billing_BillsOriginal.ConsumerType AS OriginalConsumerType',
                    'Billing_BillsOriginal.BillType AS OriginalBillType',
                    'Billing_BillsOriginal.GenerationSystemCharge AS OriginalGenerationSystemCharge',
                    'Billing_BillsOriginal.TransmissionDeliveryChargeKW AS OriginalTransmissionDeliveryChargeKW',
                    'Billing_BillsOriginal.TransmissionDeliveryChargeKWH AS OriginalTransmissionDeliveryChargeKWH',
                    'Billing_BillsOriginal.SystemLossCharge AS OriginalSystemLossCharge',
                    'Billing_BillsOriginal.DistributionDemandCharge AS OriginalDistributionDemandCharge',
                    'Billing_BillsOriginal.DistributionSystemCharge AS OriginalDistributionSystemCharge',
                    'Billing_BillsOriginal.SupplyRetailCustomerCharge AS OriginalSupplyRetailCustomerCharge',
                    'Billing_BillsOriginal.SupplySystemCharge AS OriginalSupplySystemCharge',
                    'Billing_BillsOriginal.MeteringRetailCustomerCharge AS OriginalMeteringRetailCustomerCharge',
                    'Billing_BillsOriginal.MeteringSystemCharge AS OriginalMeteringSystemCharge',
                    'Billing_BillsOriginal.RFSC AS OriginalRFSC',
                    'Billing_BillsOriginal.LifelineRate AS OriginalLifelineRate',
                    'Billing_BillsOriginal.InterClassCrossSubsidyCharge AS OriginalInterClassCrossSubsidyCharge',
                    'Billing_BillsOriginal.PPARefund AS OriginalPPARefund',
                    'Billing_BillsOriginal.SeniorCitizenSubsidy AS OriginalSeniorCitizenSubsidy',
                    'Billing_BillsOriginal.MissionaryElectrificationCharge AS OriginalMissionaryElectrificationCharge',
                    'Billing_BillsOriginal.EnvironmentalCharge AS OriginalEnvironmentalCharge',
                    'Billing_BillsOriginal.StrandedContractCosts AS OriginalStrandedContractCosts',
                    'Billing_BillsOriginal.NPCStrandedDebt AS OriginalNPCStrandedDebt',
                    'Billing_BillsOriginal.FeedInTariffAllowance AS OriginalFeedInTariffAllowance',
                    'Billing_BillsOriginal.MissionaryElectrificationREDCI AS OriginalMissionaryElectrificationREDCI',
                    'Billing_BillsOriginal.GenerationVAT AS OriginalGenerationVAT',
                    'Billing_BillsOriginal.TransmissionVAT AS OriginalTransmissionVAT',
                    'Billing_BillsOriginal.SystemLossVAT AS OriginalSystemLossVAT',
                    'Billing_BillsOriginal.DistributionVAT AS OriginalDistributionVAT',
                    'Billing_BillsOriginal.RealPropertyTax AS OriginalRealPropertyTax',
                    'Billing_BillsOriginal.OtherGenerationRateAdjustment AS OriginalOtherGenerationRateAdjustment',
                    'Billing_BillsOriginal.OtherTransmissionCostAdjustmentKW AS OriginalOtherTransmissionCostAdjustmentKW',
                    'Billing_BillsOriginal.OtherTransmissionCostAdjustmentKWH AS OriginalOtherTransmissionCostAdjustmentKWH',
                    'Billing_BillsOriginal.OtherSystemLossCostAdjustment AS OriginalOtherSystemLossCostAdjustment',
                    'Billing_BillsOriginal.OtherLifelineRateCostAdjustment AS OriginalOtherLifelineRateCostAdjustment',
                    'Billing_BillsOriginal.SeniorCitizenDiscountAndSubsidyAdjustment AS OriginalSeniorCitizenDiscountAndSubsidyAdjustment',
                    'Billing_BillsOriginal.FranchiseTax AS OriginalFranchiseTax',
                    'Billing_BillsOriginal.BusinessTax AS OriginalBusinessTax',
                    'Billing_BillsOriginal.AdjustmentType AS OriginalAdjustmentType',
                    'Billing_BillsOriginal.AdjustmentNumber AS OriginalAdjustmentNumber',
                    'Billing_BillsOriginal.AdjustedBy AS OriginalAdjustedBy',
                    'Billing_BillsOriginal.DateAdjusted AS OriginalDateAdjusted',
                    'Billing_BillsOriginal.Notes AS OriginalNotes',
                    'Billing_BillsOriginal.UserId AS OriginalUserId',
                    'Billing_BillsOriginal.BilledFrom AS OriginalBilledFrom',
                    'Billing_BillsOriginal.Form2307Amount AS OriginalForm2307Amount',
                    'Billing_BillsOriginal.Evat2Percent AS OriginalEvat2Percent',
                    'Billing_BillsOriginal.Evat5Percent AS OriginalEvat5Percent',
                    'Billing_BillsOriginal.MergedToCollectible AS OriginalMergedToCollectible',
                    'Billing_BillsOriginal.DeductedDeposit AS OriginalDeductedDeposit',
                    'Billing_BillsOriginal.ExcessDeposit AS OriginalExcessDeposit',
                    'Billing_BillsOriginal.AveragedCount AS OriginalAveragedCount',
                    'Billing_BillsOriginal.IsUnlockedForPayment AS OriginalIsUnlockedForPayment',
                    'Billing_BillsOriginal.UnlockedBy AS OriginalUnlockedBy',
                    'Billing_BillsOriginal.ForCancellation AS OriginalForCancellation',
                    'Billing_BillsOriginal.CancelRequestedBy AS OriginalCancelRequestedBy',
                    'Billing_BillsOriginal.CancelApprovedBy AS OriginalCancelApprovedBy',
                    'Billing_BillsOriginal.created_at AS DateAdjusted',
                    'users.name',
                    // BREAK
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_Bills.id AS Billsid',
                    'Billing_Bills.BillNumber AS BillsBillNumber',
                    'Billing_Bills.AccountNumber AS BillsAccountNumber',
                    'Billing_Bills.ServicePeriod AS BillsServicePeriod',
                    'Billing_Bills.Multiplier AS BillsMultiplier',
                    'Billing_Bills.Coreloss AS BillsCoreloss',
                    'Billing_Bills.KwhUsed AS BillsKwhUsed',
                    'Billing_Bills.PreviousKwh AS BillsPreviousKwh',
                    'Billing_Bills.PresentKwh AS BillsPresentKwh',
                    'Billing_Bills.DemandPreviousKwh AS BillsDemandPreviousKwh',
                    'Billing_Bills.DemandPresentKwh AS BillsDemandPresentKwh',
                    'Billing_Bills.AdditionalKwh AS BillsAdditionalKwh',
                    'Billing_Bills.AdditionalDemandKwh AS BillsAdditionalDemandKwh',
                    'Billing_Bills.KwhAmount AS BillsKwhAmount',
                    'Billing_Bills.EffectiveRate AS BillsEffectiveRate',
                    'Billing_Bills.AdditionalCharges AS BillsAdditionalCharges',
                    'Billing_Bills.Deductions AS BillsDeductions',
                    'Billing_Bills.NetAmount AS BillsNetAmount',
                    'Billing_Bills.BillingDate AS BillsBillingDate',
                    'Billing_Bills.ServiceDateFrom AS BillsServiceDateFrom',
                    'Billing_Bills.ServiceDateTo AS BillsServiceDateTo',
                    'Billing_Bills.DueDate AS BillsDueDate',
                    'Billing_Bills.MeterNumber AS BillsMeterNumber',
                    'Billing_Bills.ConsumerType AS BillsConsumerType',
                    'Billing_Bills.BillType AS BillsBillType',
                    'Billing_Bills.GenerationSystemCharge AS BillsGenerationSystemCharge',
                    'Billing_Bills.TransmissionDeliveryChargeKW AS BillsTransmissionDeliveryChargeKW',
                    'Billing_Bills.TransmissionDeliveryChargeKWH AS BillsTransmissionDeliveryChargeKWH',
                    'Billing_Bills.SystemLossCharge AS BillsSystemLossCharge',
                    'Billing_Bills.DistributionDemandCharge AS BillsDistributionDemandCharge',
                    'Billing_Bills.DistributionSystemCharge AS BillsDistributionSystemCharge',
                    'Billing_Bills.SupplyRetailCustomerCharge AS BillsSupplyRetailCustomerCharge',
                    'Billing_Bills.SupplySystemCharge AS BillsSupplySystemCharge',
                    'Billing_Bills.MeteringRetailCustomerCharge AS BillsMeteringRetailCustomerCharge',
                    'Billing_Bills.MeteringSystemCharge AS BillsMeteringSystemCharge',
                    'Billing_Bills.RFSC AS BillsRFSC',
                    'Billing_Bills.LifelineRate AS BillsLifelineRate',
                    'Billing_Bills.InterClassCrossSubsidyCharge AS BillsInterClassCrossSubsidyCharge',
                    'Billing_Bills.PPARefund AS BillsPPARefund',
                    'Billing_Bills.SeniorCitizenSubsidy AS BillsSeniorCitizenSubsidy',
                    'Billing_Bills.MissionaryElectrificationCharge AS BillsMissionaryElectrificationCharge',
                    'Billing_Bills.EnvironmentalCharge AS BillsEnvironmentalCharge',
                    'Billing_Bills.StrandedContractCosts AS BillsStrandedContractCosts',
                    'Billing_Bills.NPCStrandedDebt AS BillsNPCStrandedDebt',
                    'Billing_Bills.FeedInTariffAllowance AS BillsFeedInTariffAllowance',
                    'Billing_Bills.MissionaryElectrificationREDCI AS BillsMissionaryElectrificationREDCI',
                    'Billing_Bills.GenerationVAT AS BillsGenerationVAT',
                    'Billing_Bills.TransmissionVAT AS BillsTransmissionVAT',
                    'Billing_Bills.SystemLossVAT AS BillsSystemLossVAT',
                    'Billing_Bills.DistributionVAT AS BillsDistributionVAT',
                    'Billing_Bills.RealPropertyTax AS BillsRealPropertyTax',
                    'Billing_Bills.Notes AS BillsNotes',
                    'Billing_Bills.UserId AS BillsUserId',
                    'Billing_Bills.BilledFrom AS BillsBilledFrom',
                    'Billing_Bills.AveragedCount AS BillsAveragedCount',
                    'Billing_Bills.MergedToCollectible AS BillsMergedToCollectible',
                    'Billing_Bills.OtherGenerationRateAdjustment AS BillsOtherGenerationRateAdjustment',
                    'Billing_Bills.OtherTransmissionCostAdjustmentKW AS BillsOtherTransmissionCostAdjustmentKW',
                    'Billing_Bills.OtherTransmissionCostAdjustmentKWH AS BillsOtherTransmissionCostAdjustmentKWH',
                    'Billing_Bills.OtherSystemLossCostAdjustment AS BillsOtherSystemLossCostAdjustment',
                    'Billing_Bills.OtherLifelineRateCostAdjustment AS BillsOtherLifelineRateCostAdjustment',
                    'Billing_Bills.SeniorCitizenDiscountAndSubsidyAdjustment AS BillsSeniorCitizenDiscountAndSubsidyAdjustment',
                    'Billing_Bills.FranchiseTax AS BillsFranchiseTax',
                    'Billing_Bills.BusinessTax AS BillsBusinessTax',
                    'Billing_Bills.AdjustmentType AS BillsAdjustmentType',
                    'Billing_Bills.Form2307Amount AS BillsForm2307Amount',
                    'Billing_Bills.DeductedDeposit AS BillsDeductedDeposit',
                    'Billing_Bills.ExcessDeposit AS BillsExcessDeposit',
                    'Billing_Bills.IsUnlockedForPayment AS BillsIsUnlockedForPayment',
                    'Billing_Bills.UnlockedBy AS BillsUnlockedBy',
                    'Billing_Bills.Evat2Percent AS BillsEvat2Percent',
                    'Billing_Bills.Evat5Percent AS BillsEvat5Percent',
                    'Billing_Bills.AdjustmentNumber AS BillsAdjustmentNumber',
                    'Billing_Bills.AdjustedBy AS BillsAdjustedBy',
                    'Billing_Bills.DateAdjusted AS BillsDateAdjusted',
                    'Billing_Bills.ForCancellation AS BillsForCancellation',
                    'Billing_Bills.CancelRequestedBy AS BillsCancelRequestedBy',
                    'Billing_Bills.CancelApprovedBy AS BillsCancelApprovedBy',
                    'Billing_Bills.KatasNgVat AS BillsKatasNgVat',
                    'Billing_Bills.SolarImportPresent AS BillsSolarImportPresent',
                    'Billing_Bills.SolarImportPrevious AS BillsSolarImportPrevious',
                    'Billing_Bills.SolarExportPresent AS BillsSolarExportPresent',
                    'Billing_Bills.SolarExportPrevious AS BillsSolarExportPrevious',
                    'Billing_Bills.SolarImportKwh AS BillsSolarImportKwh',
                    'Billing_Bills.SolarExportKwh AS BillsSolarExportKwh',
                    'Billing_Bills.GenerationChargeSolarExport AS BillsGenerationChargeSolarExport',
                    'Billing_Bills.SolarResidualCredit', // IF NEGATIVE ANG AMOU AS BillsSolarResidualCredit', // IF NEGATIVE ANG AMOUNT
                    'Billing_Bills.SolarDemandChargeKW AS BillsSolarDemandChargeKW',
                    'Billing_Bills.SolarDemandChargeKWH AS BillsSolarDemandChargeKWH',
                    'Billing_Bills.SolarRetailCustomerCharge AS BillsSolarRetailCustomerCharge',
                    'Billing_Bills.SolarSupplySystemCharge AS BillsSolarSupplySystemCharge',
                    'Billing_Bills.SolarMeteringRetailCharge AS BillsSolarMeteringRetailCharge',
                    'Billing_Bills.SolarMeteringSystemCharge AS BillsSolarMeteringSystemCharge',
                    'Billing_Bills.Item2 AS BillsItem2',
                    'Billing_Bills.Item3 AS BillsItem3',
                    'Billing_Bills.Item5 AS BillsItem5',
                )
                ->orderByDesc('Billing_BillsOriginal.created_at')
                ->get();
        } else {
            $data = [];
        }
        
        return view('/bills/adjustment_reports_with_gl', [
            'data' => $data,
            'towns' => Towns::all()
        ]);
    }

    public function detailedAdjustments(Request $request) {
        $area = $request['Area'];
        $period = $request['ServicePeriod'];

        if (isset($area) && isset($period)) {

        } else {

        }

        return view('/bills/detailed_adjustments', [
            'towns' => Towns::orderBy('id')->get(),
        ]);
    }

    public function markAsPaid(Request $request) {
        $bill = Bills::find($request['id']);

        if ($bill != null) {
            $adjNumber = IDGenerator::generateID();
            $account = ServiceAccounts::find($bill->AccountNumber);

            $bill->AdjustmentType='Application';
            $bill->AdjustedBy = Auth::id();
            $bill->DateAdjusted = date('Y-m-d');
            $bill->AdjustmentNumber = $adjNumber;
            $bill->save();

            // mark as paid in paid bills
            $paidBill = new PaidBills();
            $paidBill->id = IDGenerator::generateIDandRandString();
            $paidBill->BillNumber = $bill->BillNumber;
            $paidBill->AccountNumber = $bill->AccountNumber;
            $paidBill->ServicePeriod = $bill->ServicePeriod;
            $paidBill->ORNumber = $adjNumber;
            $paidBill->ORDate = date('Y-m-d');
            $paidBill->KwhUsed = $bill->KwhUsed;
            $paidBill->Teller = Auth::id();
            $paidBill->OfficeTransacted = env('APP_LOCATION');
            $paidBill->PostingDate = date('Y-m-d');
            $paidBill->PostingTime = date('H:i:s');
            $paidBill->NetAmount = $bill->NetAmount;
            $paidBill->Source = 'APPLICATION ADJUSTMENT';
            $paidBill->ObjectSourceId = $bill->id;
            $paidBill->UserId = Auth::id();
            // $paidBill->Status = 'Application';
            $paidBill->save();

            /**
                 * SAVE DCR AND SALES REPORT
                 */
                if ($account != null) {                    
                    if ($account->ForDistribution == 'Yes') {
                        // IF ACCOUNT IS MARKED AS FOR DISTRIBUTION
                        if ($account->DistributionAccountCode != null) {
                            // GET AR CONSUMERS
                            $dcrSum = new DCRSummaryTransactions;
                            $dcrSum->id = IDGenerator::generateIDandRandString();
                            $dcrSum->GLCode = $account->DistributionAccountCode;
                            $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                            $dcrSum->Day = date('Y-m-d');
                            $dcrSum->NEACode = $bill->ServicePeriod;
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->ORNumber = $adjNumber;
                            $dcrSum->Status = 'Application';
                            $dcrSum->ReportDestination = 'BOTH';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
                            $dcrSum->save();
                        }                        
                    } else {
                        // GET AR CONSUMERS
                        $dcrSum = new DCRSummaryTransactions;
                        $dcrSum->id = IDGenerator::generateIDandRandString();
                        $dcrSum->GLCode = DCRSummaryTransactions::getARConsumers($account->Town);
                        $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                        $dcrSum->Day = date('Y-m-d');
                        $dcrSum->NEACode = $bill->ServicePeriod;
                        $dcrSum->Time = date('H:i:s');
                        $dcrSum->Teller = Auth::id();
                        $dcrSum->ORNumber = $adjNumber;
                        $dcrSum->Status = 'Application';
                        $dcrSum->ReportDestination = 'COLLECTION';
                        $dcrSum->Office = env('APP_LOCATION');
                        $dcrSum->AccountNumber = $bill->AccountNumber;
                        $dcrSum->save();

                        // GET RPT FOR DCR
                        $dcrSum = new DCRSummaryTransactions;
                        $dcrSum->id = IDGenerator::generateIDandRandString();
                        $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersRPT($account->Town);
                        $dcrSum->Amount = $bill->RealPropertyTax;
                        $dcrSum->Day = date('Y-m-d');
                        $dcrSum->NEACode = $bill->ServicePeriod;
                        $dcrSum->Time = date('H:i:s');
                        $dcrSum->Teller = Auth::id();
                        $dcrSum->ORNumber = $adjNumber;
                        $dcrSum->Status = 'Application';
                        $dcrSum->ReportDestination = 'COLLECTION';
                        $dcrSum->Office = env('APP_LOCATION');
                        $dcrSum->AccountNumber = $bill->AccountNumber;
                        $dcrSum->save();

                        // GET RPT  FOR SALES
                        $dcrSum = new DCRSummaryTransactions;
                        $dcrSum->id = IDGenerator::generateIDandRandString();
                        $dcrSum->GLCode = '140-143-30';
                        $dcrSum->Amount = $bill->RealPropertyTax;
                        $dcrSum->Day = date('Y-m-d');
                        $dcrSum->NEACode = $bill->ServicePeriod;
                        $dcrSum->Time = date('H:i:s');
                        $dcrSum->Teller = Auth::id();
                        $dcrSum->ORNumber = $adjNumber;
                        $dcrSum->Status = 'Application';
                        $dcrSum->ReportDestination = 'SALES';
                        $dcrSum->Office = env('APP_LOCATION');
                        $dcrSum->AccountNumber = $bill->AccountNumber;
                        $dcrSum->save();

                        // GET FRANCHISE TAX FOR DCR
                        $dcrSum = new DCRSummaryTransactions;
                        $dcrSum->id = IDGenerator::generateIDandRandString();
                        $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersRPT($account->Town);
                        $dcrSum->Amount = $bill->FranchiseTax;
                        $dcrSum->Day = date('Y-m-d');
                        $dcrSum->NEACode = $bill->ServicePeriod;
                        $dcrSum->Time = date('H:i:s');
                        $dcrSum->Teller = Auth::id();
                        $dcrSum->ORNumber = $adjNumber;
                        $dcrSum->Status = 'Application';
                        $dcrSum->ReportDestination = 'COLLECTION';
                        $dcrSum->Office = env('APP_LOCATION');
                        $dcrSum->AccountNumber = $bill->AccountNumber;
                        $dcrSum->save();

                        // GET FRANCHISE TAX FOR SALES
                        $dcrSum = new DCRSummaryTransactions;
                        $dcrSum->id = IDGenerator::generateIDandRandString();
                        $dcrSum->GLCode = '140-143-30';
                        $dcrSum->Amount = $bill->FranchiseTax;
                        $dcrSum->Day = date('Y-m-d');
                        $dcrSum->NEACode = $bill->ServicePeriod;
                        $dcrSum->Time = date('H:i:s');
                        $dcrSum->Teller = Auth::id();
                        $dcrSum->ORNumber = $adjNumber;
                        $dcrSum->Status = 'Application';
                        $dcrSum->ReportDestination = 'SALES';
                        $dcrSum->Office = env('APP_LOCATION');
                        $dcrSum->AccountNumber = $bill->AccountNumber;
                        $dcrSum->save();

                        // GET BUSINESS TAX TAX FOR DCR
                        $dcrSum = new DCRSummaryTransactions;
                        $dcrSum->id = IDGenerator::generateIDandRandString();
                        $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersRPT($account->Town);
                        $dcrSum->Amount = $bill->BusinessTax;
                        $dcrSum->Day = date('Y-m-d');
                        $dcrSum->NEACode = $bill->ServicePeriod;
                        $dcrSum->Time = date('H:i:s');
                        $dcrSum->Teller = Auth::id();
                        $dcrSum->ORNumber = $adjNumber;
                        $dcrSum->Status = 'Application';
                        $dcrSum->ReportDestination = 'COLLECTION';
                        $dcrSum->Office = env('APP_LOCATION');
                        $dcrSum->AccountNumber = $bill->AccountNumber;
                        $dcrSum->save();

                        // GET BUSINESS TAX TAX FOR SALES
                        $dcrSum = new DCRSummaryTransactions;
                        $dcrSum->id = IDGenerator::generateIDandRandString();
                        $dcrSum->GLCode = '140-143-30';
                        $dcrSum->Amount = $bill->BusinessTax;
                        $dcrSum->Day = date('Y-m-d');
                        $dcrSum->NEACode = $bill->ServicePeriod;
                        $dcrSum->Time = date('H:i:s');
                        $dcrSum->Teller = Auth::id();
                        $dcrSum->ORNumber = $adjNumber;
                        $dcrSum->Status = 'Application';
                        $dcrSum->ReportDestination = 'SALES';
                        $dcrSum->Office = env('APP_LOCATION');
                        $dcrSum->AccountNumber = $bill->AccountNumber;
                        $dcrSum->save();

                        // GET SALES AR BY CONSUMER TYPE 
                        if ($account->OrganizationParentAccount != null) {
                            // GET BAPA
                            $dcrSum = new DCRSummaryTransactions;
                            $dcrSum->id = IDGenerator::generateIDandRandString();
                            $dcrSum->GLCode = '311-448-00';
                            $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                            $dcrSum->Day = date('Y-m-d');
                            $dcrSum->NEACode = $bill->ServicePeriod;
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->ORNumber = $adjNumber;
                            $dcrSum->Status = 'Application';
                            $dcrSum->ReportDestination = 'SALES';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
                            $dcrSum->save();
                        } else {
                            // GET NOT BAPA
                            if ($account->AccountType == 'RURAL RESIDENTIAL' || $account->AccountType == 'RESIDENTIAL') {
                                // GET RESIDENTIALS
                                $dcrSum = new DCRSummaryTransactions;
                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                $dcrSum->GLCode = DCRSummaryTransactions::getARConsumers($account->Town);;
                                $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                                $dcrSum->Day = date('Y-m-d');
                                $dcrSum->NEACode = $bill->ServicePeriod;
                                $dcrSum->Time = date('H:i:s');
                                $dcrSum->Teller = Auth::id();
                                $dcrSum->ORNumber = $adjNumber;
                                $dcrSum->Status = 'Application';
                                $dcrSum->ReportDestination = 'SALES';
                                $dcrSum->Office = env('APP_LOCATION');
                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                $dcrSum->save();
                            } else {
                                // GET NOT RESIDENTIALS
                                $dcrSum = new DCRSummaryTransactions;
                                $dcrSum->id = IDGenerator::generateIDandRandString();
                                $dcrSum->GLCode = DCRSummaryTransactions::getGLCodePerAccountType($account->AccountType);;
                                $dcrSum->Amount = DCRSummaryTransactions::getARConsumersAmount($bill);
                                $dcrSum->Day = date('Y-m-d');
                                $dcrSum->NEACode = $bill->ServicePeriod;
                                $dcrSum->Time = date('H:i:s');
                                $dcrSum->Teller = Auth::id();
                                $dcrSum->ORNumber = $adjNumber;
                                $dcrSum->Status = 'Application';
                                $dcrSum->ReportDestination = 'SALES';
                                $dcrSum->Office = env('APP_LOCATION');
                                $dcrSum->AccountNumber = $bill->AccountNumber;
                                $dcrSum->save();
                            }
                        }
                    }

                    // GET TERMED PAYMENT BUNDLES
                    if ($bill->AdditionalCharges != null) {
                        // GET TERMED PAYMENT
                        $termedPayment = ArrearsLedgerDistribution::where('AccountNumber', $account->id)
                            ->where('ServicePeriod', $bill->ServicePeriod)
                            ->whereNull('IsPaid')
                            ->first();

                        if ($termedPayment != null) {
                            $dcrSum = new DCRSummaryTransactions;
                            $dcrSum->id = IDGenerator::generateIDandRandString();
                            $dcrSum->GLCode = DCRSummaryTransactions::getARConsumersTermedPayments($account->Town);
                            $dcrSum->Amount = $termedPayment->Amount;
                            $dcrSum->Day = date('Y-m-d');
                            $dcrSum->NEACode = $bill->ServicePeriod;
                            $dcrSum->Time = date('H:i:s');
                            $dcrSum->Teller = Auth::id();
                            $dcrSum->ORNumber = $adjNumber;
                            $dcrSum->Status = 'Application';
                            $dcrSum->ReportDestination = 'COLLECTION';
                            $dcrSum->Office = env('APP_LOCATION');
                            $dcrSum->AccountNumber = $bill->AccountNumber;
                            $dcrSum->save();

                            $termedPayment->IsPaid = 'Yes';
                            $termedPayment->save();
                        }
                    }
                }

                // GET UC-NPC Stranded Debt COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-87';
                $dcrSum->Amount = $bill->NPCStrandedDebt;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UC-NPC Stranded Debt Sales
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-65';
                $dcrSum->Amount = $bill->NPCStrandedDebt;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET STRANDED CONTRACT COST COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-92';
                $dcrSum->Amount = $bill->StrandedContractCosts;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET STRANDED CONTRACT COST SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-62';
                $dcrSum->Amount = $bill->StrandedContractCosts;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET FIT ALL COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-88';
                $dcrSum->Amount = $bill->FeedInTariffAllowance;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET FIT ALL SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-64';
                $dcrSum->Amount = $bill->FeedInTariffAllowance;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UCME REDCI COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-89';
                $dcrSum->Amount = $bill->MissionaryElectrificationREDCI;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UCME REDCI SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-63';
                $dcrSum->Amount = $bill->MissionaryElectrificationREDCI;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET GENCO
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-94';
                $dcrSum->Amount = $bill->GenerationVAT;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET TRANSCO
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-95';
                $dcrSum->Amount = $bill->TransmissionVAT;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET SYSLOSS VAT
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-96';
                $dcrSum->Amount = $bill->SystemLossVAT;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET DIST/OTHERS VAT
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-97';
                $dcrSum->Amount = $bill->DistributionVAT;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET GENVAT, TRANSVAT, SYSLOSSVAT SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '170-184-40';
                $dcrSum->Amount = DCRSummaryTransactions::getSalesGenTransSysLossVatAmount($bill);
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET DIST AND OTHERS SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '250-255-00';
                $dcrSum->Amount = DCRSummaryTransactions::getSalesDistOthersVatAmount($bill);
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UCME COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-98';
                $dcrSum->Amount = $bill->MissionaryElectrificationCharge;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET UCME SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-60';
                $dcrSum->Amount = $bill->MissionaryElectrificationCharge;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET EWT 2%
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-160-00';
                $dcrSum->Amount = $paidBill->Form2307TwoPercent;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET EVAT 5%
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-170-00';
                $dcrSum->Amount = $paidBill->Form2307FivePercent;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET ENVIRONMENT CHARGE COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-99';
                $dcrSum->Amount = $bill->EnvironmentalCharge;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET ENVIRONMENT CHARGE SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '230-232-90';
                $dcrSum->Amount = $bill->EnvironmentalCharge;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET RFSC COLLECTION
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '140-142-93';
                $dcrSum->Amount = $bill->RFSC;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'COLLECTION';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();

                // GET RFSC SALES
                $dcrSum = new DCRSummaryTransactions;
                $dcrSum->id = IDGenerator::generateIDandRandString();
                $dcrSum->GLCode = '211-211-10';
                $dcrSum->Amount = $bill->RFSC;
                $dcrSum->Day = date('Y-m-d');
                $dcrSum->NEACode = $bill->ServicePeriod;
                $dcrSum->Time = date('H:i:s');
                $dcrSum->Teller = Auth::id();
                $dcrSum->ORNumber = $adjNumber;
                $dcrSum->Status = 'Application';
                $dcrSum->ReportDestination = 'SALES';
                $dcrSum->Office = env('APP_LOCATION');
                $dcrSum->AccountNumber = $bill->AccountNumber;
                $dcrSum->save();
        }

        return response()->json('ok', 200);
    }

    public function dashboard() {
        $latestRate = Rates::orderByDesc('ServicePeriod')->first();
        if (env("APP_AREA_CODE") == '15') {
            $todaysReading = DB::table('Billing_ServiceAccounts')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->whereNotNull('Billing_ServiceAccounts.MeterReader')
                ->select('users.name', 'users.id',
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE CAST(ReadingTimestamp AS DATE)='" . date('Y-m-d') . "' AND MeterReader=CAST(users.id AS varchar)) AS TotalReading"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE BillingDate='" . date('Y-m-d') . "'  AND UserId=CAST(users.id AS varchar)) AS TotalBills"),
                )
                ->groupBy('users.name', 'users.id')
                ->orderBy('users.name')
                ->get();
        } else {
            $todaysReading = DB::table('Billing_ServiceAccounts')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->whereIn("Billing_ServiceAccounts.Town", MeterReaders::getMeterAreaCodeScope(env('APP_AREA_CODE')))
                ->whereNotNull('Billing_ServiceAccounts.MeterReader')
                ->select('users.name', 'users.id',
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE CAST(ReadingTimestamp AS DATE)='" . date('Y-m-d') . "' AND MeterReader=CAST(users.id AS varchar)) AS TotalReading"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE BillingDate='" . date('Y-m-d') . "'  AND UserId=CAST(users.id AS varchar)) AS TotalBills"),
                )
                ->groupBy('users.name', 'users.id')
                ->orderBy('users.name')
                ->get();
        }

        return view('/bills/dashboard', [
            'todaysReading' => $todaysReading,
            'latestRate' => $latestRate,
        ]);
    }

    public function dashboardReadingMonitor(Request $request) {
        $latestRate = Rates::orderByDesc('ServicePeriod')
            ->first();

        $period = $request['Period'] != null ? $request['Period'] : ($latestRate != null ? date('Y-m-d', strtotime($latestRate->ServicePeriod)) : date('Y-m-01'));
        $day = $request['Day'] != null ? $request['Day'] : 'All';
        $town = $request['Town'] != null ? $request['Town'] : 'All';

        if ($town == 'All') {
            if ($day == 'All') {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                    ->whereRaw("Billing_ServiceAccounts.MeterReader IS NOT NULL")
                    ->whereIn('Billing_ServiceAccounts.AccountStatus', ['ACTIVE', 'DISCONNECTED'])
                    ->select('users.name', 'users.id',
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND r.ServicePeriod='" . $period . "' AND r.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=r.AccountNumber AND ServicePeriod='" . $period . "')) AS TotalUnbilledBasedFromReadings"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_ServiceAccounts WHERE AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND MeterReader=CAST(users.id AS varchar) AND id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='". $period ."' AND AccountNumber IS NOT NULL)) AS AllUnbilled"),
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND r.AccountNumber IS NOT NULL AND r.MeterReader=CAST(users.id AS varchar) AND r.ServicePeriod='" . $period . "') AS TotalReading"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE AccountNumber IS NULL AND ServicePeriod='" . $period . "' AND MeterReader=CAST(users.id AS varchar)) AS Captured"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalBills"),
                        DB::raw("(SELECT SUM(TRY_CONVERT(DECIMAL(10,2), KwhUsed)) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND b.KwhUsed IS NOT NULL AND ISNUMERIC(b.KwhUsed)=1 AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalKwh"),
                        DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.AccountNumber IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application')) 
                            AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalCollected"),
                        DB::raw("(SELECT COUNT(b.id) FROM Disconnection_History b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.Status='DISCONNECTED' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS DisconnectedCount")
                    )
                    ->groupBy('users.name', 'users.id')
                    ->orderBy('users.name')
                    ->get();
            } else {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                    ->whereRaw("Billing_ServiceAccounts.GroupCode='" . $day . "' AND Billing_ServiceAccounts.MeterReader IS NOT NULL")
                    ->whereIn('Billing_ServiceAccounts.AccountStatus', ['ACTIVE', 'DISCONNECTED'])
                    ->select('users.name', 'users.id',
                        DB::raw("(SELECT TOP 1 Town FROM Billing_ServiceAccounts WHERE GroupCode='" . $day . "' AND MeterReader=CAST(users.id AS varchar)) AS Town"),
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND sa.GroupCode='" . $day . "' AND r.ServicePeriod='" . $period . "' AND r.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=r.AccountNumber AND ServicePeriod='" . $period . "')) AS TotalUnbilledBasedFromReadings"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_ServiceAccounts WHERE AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND MeterReader=CAST(users.id AS varchar) AND GroupCode='" . $day . "' AND id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='". $period ."' AND AccountNumber IS NOT NULL)) AS AllUnbilled"),
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND r.AccountNumber IS NOT NULL AND sa.GroupCode='" . $day . "' AND r.MeterReader=CAST(users.id AS varchar) AND r.ServicePeriod='" . $period . "') AS TotalReading"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE AccountNumber IS NULL AND ServicePeriod='" . $period . "' AND MeterReader=CAST(users.id AS varchar) AND CAST(ReadingTimestamp AS DATE)=(SELECT TOP 1 ScheduledDate FROM Billing_ReadingSchedules WHERE MeterReader=CAST(users.id AS varchar) AND ServicePeriod='" . $period . "' AND GroupCode='" . $day ."')) AS Captured"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalBills"),
                        DB::raw("(SELECT SUM(TRY_CONVERT(DECIMAL(10,2), KwhUsed)) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND b.KwhUsed IS NOT NULL AND ISNUMERIC(b.KwhUsed)=1 AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalKwh"),
                        DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.AccountNumber IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application')) 
                            AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalCollected"),
                        DB::raw("(SELECT COUNT(b.id) FROM Disconnection_History b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.Status='DISCONNECTED' AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS DisconnectedCount")
                    )
                    ->groupBy('users.name', 'users.id')
                    ->orderBy('users.name')
                    ->get();
            }
        } else {
            if ($day == 'All') {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                    ->whereRaw("Billing_ServiceAccounts.Town IN " . MeterReaders::getMeterAreaCodeScopeSql($town))
                    ->whereRaw("Billing_ServiceAccounts.MeterReader IS NOT NULL")
                    ->whereIn('Billing_ServiceAccounts.AccountStatus', ['ACTIVE', 'DISCONNECTED'])
                    ->select('users.name', 'users.id',
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND r.ServicePeriod='" . $period . "' AND r.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=r.AccountNumber AND ServicePeriod='" . $period . "')) AS TotalUnbilledBasedFromReadings"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_ServiceAccounts WHERE AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND MeterReader=CAST(users.id AS varchar) AND id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='". $period ."' AND AccountNumber IS NOT NULL)) AS AllUnbilled"),
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND r.AccountNumber IS NOT NULL AND r.MeterReader=CAST(users.id AS varchar) AND r.ServicePeriod='" . $period . "') AS TotalReading"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE AccountNumber IS NULL AND ServicePeriod='" . $period . "' AND MeterReader=CAST(users.id AS varchar)) AS Captured"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalBills"),
                        DB::raw("(SELECT SUM(TRY_CONVERT(DECIMAL(10,2), KwhUsed)) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND b.KwhUsed IS NOT NULL AND ISNUMERIC(b.KwhUsed)=1 AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalKwh"),
                        DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.AccountNumber IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application')) 
                            AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalCollected"),
                        DB::raw("(SELECT COUNT(b.id) FROM Disconnection_History b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.Status='DISCONNECTED' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS DisconnectedCount")
                    )
                    ->groupBy('users.name', 'users.id')
                    ->orderBy('users.name')
                    ->get();
            } else {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                    ->whereRaw("Billing_ServiceAccounts.Town IN " . MeterReaders::getMeterAreaCodeScopeSql($town))
                    ->whereRaw("Billing_ServiceAccounts.GroupCode='" . $day . "' AND Billing_ServiceAccounts.MeterReader IS NOT NULL")
                    ->whereIn('Billing_ServiceAccounts.AccountStatus', ['ACTIVE', 'DISCONNECTED'])
                    ->select('users.name', 'users.id',
                        DB::raw("(SELECT TOP 1 Town FROM Billing_ServiceAccounts WHERE GroupCode='" . $day . "' AND MeterReader=CAST(users.id AS varchar)) AS Town"),
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND sa.GroupCode='" . $day . "' AND r.ServicePeriod='" . $period . "' AND r.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=r.AccountNumber AND ServicePeriod='" . $period . "')) AS TotalUnbilledBasedFromReadings"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_ServiceAccounts WHERE AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND MeterReader=CAST(users.id AS varchar) AND GroupCode='" . $day . "' AND id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='". $period ."' AND AccountNumber IS NOT NULL)) AS AllUnbilled"),
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND r.AccountNumber IS NOT NULL AND sa.GroupCode='" . $day . "' AND r.MeterReader=CAST(users.id AS varchar) AND r.ServicePeriod='" . $period . "') AS TotalReading"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE AccountNumber IS NULL AND ServicePeriod='" . $period . "' AND MeterReader=CAST(users.id AS varchar) AND CAST(ReadingTimestamp AS DATE)=(SELECT TOP 1 ScheduledDate FROM Billing_ReadingSchedules WHERE MeterReader=CAST(users.id AS varchar) AND ServicePeriod='" . $period . "' AND GroupCode='" . $day ."')) AS Captured"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalBills"),
                        DB::raw("(SELECT SUM(TRY_CONVERT(DECIMAL(10,2), KwhUsed)) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND b.KwhUsed IS NOT NULL AND ISNUMERIC(b.KwhUsed)=1 AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalKwh"),
                        DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.AccountNumber IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application')) 
                            AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalCollected"),
                        DB::raw("(SELECT COUNT(b.id) FROM Disconnection_History b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.Status='DISCONNECTED' AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS DisconnectedCount")
                    )
                    ->groupBy('users.name', 'users.id')
                    ->orderBy('users.name')
                    ->get();
            }
        }        

        $output = "";
        $totalUnbilledFromReadings = 0;
        $totalUnbilled = 0;
        $totalReading = 0;
        $totalCaputured = 0;
        $totalBills = 0;
        $totalKwh = 0;
        $totalAmnt = 0;
        $totalCollectedCount = 0;
        $collectedPercentage = 0;
        $totalUnollectedCount = 0;
        $unCollectedPercentage = 0;
        $discoCount = 0;
        $discoPercentage = 0;
        foreach($data as $item) {
            if ($day == 'All' | $item->TotalReading==0) {
                $output .= "<tr>
                    <th>" . $item->name . "</th>
                    <td class='text-right'>" . number_format($item->TotalUnbilledBasedFromReadings) . "</td>
                    <td class='text-right text-primary' onclick=showUnbilled('" . $item->id . "')>" . number_format($item->AllUnbilled) . "</td>
                    <th class='text-right text-danger'>" . number_format($item->Captured) . "</th>
                    <th class='text-right text-info'>" . number_format($item->TotalReading) . "</th>
                    <th class='text-right text-primary'>" . number_format($item->TotalKwh) . "</th>
                    <th class='text-right'>" . number_format($item->TotalBills) . "</th>
                    <th class='text-right text-success'>₱" . number_format($item->TotalAmount, 2) . "</th>
                    <td class='text-right'>" . number_format($item->TotalCollected) . "</td>
                    <td class='text-right text-primary'>" . round(IDGenerator::getPercentage($item->TotalCollected, $item->TotalBills), 2) . " %</td>
                    <td class='text-right text-primary' onclick=showUncollected('" . $item->id . "')>" . number_format(IDGenerator::getDifference($item->TotalBills, $item->TotalCollected)) . " </td>
                    <td class='text-right text-danger'>" . round(IDGenerator::getPercentage(IDGenerator::getDifference($item->TotalBills, $item->TotalCollected), $item->TotalBills), 2) . " %</td>
                    <td class='text-right'>" . number_format($item->DisconnectedCount) . "</td>
                    <td class='text-right text-danger'>" . round(IDGenerator::getPercentage($item->DisconnectedCount, IDGenerator::getDifference($item->TotalBills, $item->TotalCollected)), 2) . " %</td>
                </tr>";
            } else {
                $output .= "<tr>
                    <th><a href='" . route('readings.view-full-report', [$period, $item->id, $day, $item->Town]) . "'>" . $item->name . "</a></th>
                    <td class='text-right'>" . number_format($item->TotalUnbilledBasedFromReadings) . "</td>
                    <td class='text-right text-primary' onclick=showUnbilled('" . $item->id . "')>" . number_format($item->AllUnbilled) . "</td>
                    <th class='text-right text-danger'>" . number_format($item->Captured) . "</th>
                    <th class='text-right text-info'>" . number_format($item->TotalReading) . "</th>
                    <th class='text-right text-primary'>" . number_format($item->TotalKwh) . "</th>
                    <th class='text-right'>" . number_format($item->TotalBills) . "</th>
                    <th class='text-right text-success'>₱" . number_format($item->TotalAmount, 2) . "</th>
                    <td class='text-right'>" . number_format($item->TotalCollected) . "</td>
                    <td class='text-right text-primary'>" . round(IDGenerator::getPercentage($item->TotalCollected, $item->TotalBills), 2) . " %</td>
                    <td class='text-right text-primary' onclick=showUncollected('" . $item->id . "')>" . number_format(IDGenerator::getDifference($item->TotalBills, $item->TotalCollected)) . " </td>
                    <td class='text-right text-danger'>" . round(IDGenerator::getPercentage(IDGenerator::getDifference($item->TotalBills, $item->TotalCollected), $item->TotalBills), 2) . " %</td>
                    <td class='text-right'>" . number_format($item->DisconnectedCount) . "</td>
                    <td class='text-right text-danger'>" . round(IDGenerator::getPercentage($item->DisconnectedCount, IDGenerator::getDifference($item->TotalBills, $item->TotalCollected)), 2) . " %</td>
                </tr>";
            }
            
            $totalUnbilledFromReadings += floatval($item->TotalUnbilledBasedFromReadings);
            $totalUnbilled += floatval($item->AllUnbilled);
            $totalReading += floatval($item->TotalReading);
            $totalCaputured += floatval($item->Captured);
            $totalBills += floatval($item->TotalBills);
            $totalKwh += floatval($item->TotalKwh);
            $totalAmnt += floatval($item->TotalAmount);
            $totalCollectedCount += floatval($item->TotalCollected);
            $collectedPercentage += floatval(IDGenerator::getPercentage($item->TotalCollected, $item->TotalBills));
            $totalUnollectedCount += floatval(IDGenerator::getDifference($item->TotalBills, $item->TotalCollected));
            $unCollectedPercentage += floatval(IDGenerator::getPercentage(IDGenerator::getDifference($item->TotalBills, $item->TotalCollected), $item->TotalBills));
            $discoCount += floatval($item->DisconnectedCount);
            $discoPercentage += floatval(IDGenerator::getPercentage($item->DisconnectedCount, IDGenerator::getDifference($item->TotalBills, $item->TotalCollected)));
        }

        $output .= "<tr>
                    <th>Total</th>
                    <th class='text-right'>" . number_format($totalUnbilledFromReadings) . "</th>
                    <th class='text-right'>" . number_format($totalUnbilled) . "</th>
                    <th class='text-right text-danger'>" . number_format($totalCaputured) . "</th>
                    <th class='text-right text-info'>" . number_format($totalReading) . "</th>
                    <th class='text-right text-primary'>" . number_format($totalKwh) . "</th>
                    <th class='text-right'>" . number_format($totalBills) . "</th>
                    <th class='text-right text-success'>₱" . number_format($totalAmnt, 2) . "</th>
                    <th class='text-right'>" . number_format($totalCollectedCount) . "</th>
                    <th class='text-right text-primary'>" . number_format(IDGenerator::getAverage($collectedPercentage, count($data)), 2) . "%</th>
                    <th class='text-right'>" . number_format($totalUnollectedCount) . "</th>
                    <th class='text-right text-danger'>" . number_format(IDGenerator::getAverage($unCollectedPercentage, count($data)), 2) . "%</th>
                    <th class='text-right'>" . number_format($discoCount) . "</th>
                    <th class='text-right text-danger'>" . number_format(IDGenerator::getAverage($discoPercentage, count($data)), 2) . "%</th>
                </tr>";

        return response()->json($output, 200);
    }

    public function showUncollectedDashboard(Request $request) {
        $meterReader = $request['MeterReader'];
        $period = $request['Period'] != null ? $request['Period'] : ($latestRate != null ? date('Y-m-d', strtotime($latestRate->ServicePeriod)) : date('Y-m-01'));
        $day = $request['Day'] != null ? $request['Day'] : 'All';
        $town = $request['Town'] != null ? $request['Town'] : 'All';

        if ($town=='All') {
            if ($day == 'All') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.MeterReader='" . $meterReader . "'  
                        AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application'))")
                    ->select('Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok', 
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'Billing_ServiceAccounts.AccountStatus',
                        'Billing_Bills.NetAmount',
                        'Billing_Bills.DueDate')
                    ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                    ->get();
            } else {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.MeterReader='" . $meterReader . "' AND Billing_ServiceAccounts.GroupCode='" . $day . "'
                        AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application'))")
                    ->select('Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok', 
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'Billing_ServiceAccounts.AccountStatus',
                        'Billing_Bills.NetAmount',
                        'Billing_Bills.DueDate')
                    ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                    ->get();
            }
            
        } else {
            if ($day == 'All') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.MeterReader='" . $meterReader . "' AND Billing_ServiceAccounts.Town='" . $town . "' 
                        AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application'))")
                    ->select('Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok', 
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'Billing_ServiceAccounts.AccountStatus',
                        'Billing_Bills.NetAmount',
                        'Billing_Bills.DueDate')
                    ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                    ->get();
            } else {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.MeterReader='" . $meterReader . "' AND Billing_ServiceAccounts.GroupCode='" . $day . "' AND Billing_ServiceAccounts.Town='" . $town . "' 
                        AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application'))")
                    ->select('Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok', 
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'Billing_ServiceAccounts.AccountStatus',
                        'Billing_Bills.NetAmount',
                        'Billing_Bills.DueDate')
                    ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                    ->get();
            }            
        }
        
        $output = "";
        $i=1;
        foreach($data as $item) {
            $output .= "<tr>
                            <td>" . $i . "</td>
                            <td>" . $item->OldAccountNo . "</td>
                            <td>" . $item->ServiceAccountName . "</td>
                            <td>" . ServiceAccounts::getAddress($item) . "</td>
                            <td>" . $item->AccountStatus . "</td>
                            <td>" . (is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : "0") . "</td>
                            <td>" . date('M d, Y', strtotime($item->DueDate)) . "</td>
                        </tr>";
            $i++;
        }

        return response()->json($output, 200);
    }

    public function showUnbilledDashboard(Request $request) {
        $meterReader = $request['MeterReader'];
        $period = $request['Period'] != null ? $request['Period'] : ($latestRate != null ? date('Y-m-d', strtotime($latestRate->ServicePeriod)) : date('Y-m-01'));
        $day = $request['Day'] != null ? $request['Day'] : 'All';
        $town = $request['Town'] != null ? $request['Town'] : 'All';

        if ($town=='All') {
            if ($day == 'All') {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.MeterReader='" . $meterReader . "' AND AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND Billing_ServiceAccounts.id NOT IN 
                        (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                    ->select('Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok', 
                        'Billing_ServiceAccounts.id', 
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'Billing_ServiceAccounts.AccountStatus',)
                    ->orderBy('Billing_ServiceAccounts.AccountStatus')
                    ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                    ->get();
            } else {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.MeterReader='" . $meterReader . "' AND AccountStatus IN ('ACTIVE', 'DISCONNECTED')  AND Billing_ServiceAccounts.GroupCode='" . $day . "' AND Billing_ServiceAccounts.id NOT IN 
                    (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                    ->select('Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok', 
                        'Billing_ServiceAccounts.id', 
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'Billing_ServiceAccounts.AccountStatus',)
                    ->orderBy('Billing_ServiceAccounts.AccountStatus')
                    ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                    ->get();
            }
            
        } else {
            if ($day == 'All') {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.MeterReader='" . $meterReader . "' AND AccountStatus IN ('ACTIVE', 'DISCONNECTED')  AND Billing_ServiceAccounts.Town='" . $town . "' AND Billing_ServiceAccounts.id NOT IN 
                    (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                    ->select('Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok', 
                        'Billing_ServiceAccounts.id', 
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'Billing_ServiceAccounts.AccountStatus',)
                    ->orderBy('Billing_ServiceAccounts.AccountStatus')
                    ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                    ->get();
            } else {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.MeterReader='" . $meterReader . "' AND AccountStatus IN ('ACTIVE', 'DISCONNECTED')  AND Billing_ServiceAccounts.GroupCode='" . $day . "' AND Billing_ServiceAccounts.Town='" . $town . "' AND Billing_ServiceAccounts.id NOT IN 
                    (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                    ->select('Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok', 
                        'Billing_ServiceAccounts.id', 
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'Billing_ServiceAccounts.AccountStatus',)
                    ->orderBy('Billing_ServiceAccounts.AccountStatus')
                    ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                    ->get();
            }            
        }
        
        $output = "";
        $i=1;
        foreach($data as $item) {
            $output .= "<tr>
                            <td>" . $i . "</td>
                            <td><a href='" . route('serviceAccounts.show', [$item->id]) . "'>" . $item->OldAccountNo . "</a></td>
                            <td>" . $item->ServiceAccountName . "</td>
                            <td>" . ServiceAccounts::getAddress($item) . "</td>
                            <td>" . $item->AccountStatus . "</td>
                        </tr>";
            $i++;
        }

        return response()->json($output, 200);
    }

    public function getMinifiedCollectionEfficiency(Request $request) {
        $latestRate = Rates::orderByDesc('ServicePeriod')
            ->first();

        $period = $request['Period'] != null ? $request['Period'] : ($latestRate != null ? date('Y-m-d', strtotime($latestRate->ServicePeriod)) : date('Y-m-01'));
        $day = $request['Day'] != null ? $request['Day'] : 'All';

        if (env('APP_AREA_CODE') == '15') {
            // MAIN OFFICE
            if ($day == 'All') {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                    ->whereRaw("Billing_ServiceAccounts.MeterReader IS NOT NULL")
                    ->whereIn('Billing_ServiceAccounts.AccountStatus', ['ACTIVE', 'DISCONNECTED'])
                    ->select('users.name', 'users.id',
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND r.ServicePeriod='" . $period . "' AND r.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=r.AccountNumber AND ServicePeriod='" . $period . "')) AS TotalUnbilledBasedFromReadings"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_ServiceAccounts WHERE AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND MeterReader=CAST(users.id AS varchar) AND id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='". $period ."')) AS AllUnbilled"),
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND r.AccountNumber IS NOT NULL AND r.MeterReader=CAST(users.id AS varchar) AND r.ServicePeriod='" . $period . "') AS TotalReading"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE AccountNumber IS NULL AND ServicePeriod='" . $period . "' AND MeterReader=CAST(users.id AS varchar)) AS Captured"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalBills"),
                        DB::raw("(SELECT SUM(TRY_CONVERT(DECIMAL(10,2), KwhUsed)) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND b.KwhUsed IS NOT NULL AND ISNUMERIC(b.KwhUsed)=1 AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalKwh"),
                        DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.AccountNumber IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application')) 
                                AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalCollected"),
                        DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Cashier_PaidBills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalCollectedAmount"),
                        // DB::raw("(SELECT COUNT(b.id) FROM Disconnection_History b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.Status='DISCONNECTED' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS DisconnectedCount")
                    )
                    ->groupBy('users.name', 'users.id')
                    ->orderBy('users.name')
                    ->get();
            } else {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                    ->whereRaw("Billing_ServiceAccounts.GroupCode='" . $day . "' AND Billing_ServiceAccounts.MeterReader IS NOT NULL")
                    ->whereIn('Billing_ServiceAccounts.AccountStatus', ['ACTIVE', 'DISCONNECTED'])
                    ->select('users.name', 'users.id',
                        DB::raw("(SELECT TOP 1 Town FROM Billing_ServiceAccounts WHERE GroupCode='" . $day . "' AND MeterReader=CAST(users.id AS varchar)) AS Town"),
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND sa.GroupCode='" . $day . "' AND r.ServicePeriod='" . $period . "' AND r.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=r.AccountNumber AND ServicePeriod='" . $period . "')) AS TotalUnbilledBasedFromReadings"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_ServiceAccounts WHERE AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND MeterReader=CAST(users.id AS varchar) AND GroupCode='" . $day . "' AND id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='". $period ."')) AS AllUnbilled"),
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND r.AccountNumber IS NOT NULL AND sa.GroupCode='" . $day . "' AND r.MeterReader=CAST(users.id AS varchar) AND r.ServicePeriod='" . $period . "') AS TotalReading"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE AccountNumber IS NULL AND ServicePeriod='" . $period . "' AND MeterReader=CAST(users.id AS varchar) AND CAST(ReadingTimestamp AS DATE)=(SELECT TOP 1 ScheduledDate FROM Billing_ReadingSchedules WHERE MeterReader=CAST(users.id AS varchar) AND ServicePeriod='" . $period . "' AND GroupCode='" . $day ."')) AS Captured"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalBills"),
                        DB::raw("(SELECT SUM(TRY_CONVERT(DECIMAL(10,2), KwhUsed)) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND b.KwhUsed IS NOT NULL AND ISNUMERIC(b.KwhUsed)=1 AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalKwh"),
                        DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.AccountNumber IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application')) 
                                AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalCollected"),
                        DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Cashier_PaidBills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalCollectedAmount"),
                        // DB::raw("(SELECT COUNT(b.id) FROM Disconnection_History b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.Status='DISCONNECTED' AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS DisconnectedCount")
                    )
                    ->groupBy('users.name', 'users.id')
                    ->orderBy('users.name')
                    ->get();
            }
        } else {
            // AREA OFFICES
            if ($day == 'All') {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                    ->whereRaw("Billing_ServiceAccounts.Town IN " . MeterReaders::getMeterAreaCodeScopeSql(env('APP_AREA_CODE')))
                    ->whereRaw("Billing_ServiceAccounts.MeterReader IS NOT NULL")
                    ->whereIn('Billing_ServiceAccounts.AccountStatus', ['ACTIVE', 'DISCONNECTED'])
                    ->select('users.name', 'users.id',
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND r.ServicePeriod='" . $period . "' AND r.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=r.AccountNumber AND ServicePeriod='" . $period . "')) AS TotalUnbilledBasedFromReadings"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_ServiceAccounts WHERE AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND MeterReader=CAST(users.id AS varchar) AND id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='". $period ."')) AS AllUnbilled"),
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND r.AccountNumber IS NOT NULL AND r.MeterReader=CAST(users.id AS varchar) AND r.ServicePeriod='" . $period . "') AS TotalReading"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE AccountNumber IS NULL AND ServicePeriod='" . $period . "' AND MeterReader=CAST(users.id AS varchar)) AS Captured"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalBills"),
                        DB::raw("(SELECT SUM(TRY_CONVERT(DECIMAL(10,2), KwhUsed)) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND b.KwhUsed IS NOT NULL AND ISNUMERIC(b.KwhUsed)=1 AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalKwh"),
                        DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.AccountNumber IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application')) 
                                AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalCollected"),
                        DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Cashier_PaidBills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalCollectedAmount"),
                        // DB::raw("(SELECT COUNT(b.id) FROM Disconnection_History b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.Status='DISCONNECTED' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS DisconnectedCount")
                    )
                    ->groupBy('users.name', 'users.id')
                    ->orderBy('users.name')
                    ->get();
            } else {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                    ->whereRaw("Billing_ServiceAccounts.Town IN " . MeterReaders::getMeterAreaCodeScopeSql(env('APP_AREA_CODE')))
                    ->whereRaw("Billing_ServiceAccounts.GroupCode='" . $day . "' AND Billing_ServiceAccounts.MeterReader IS NOT NULL")
                    ->whereIn('Billing_ServiceAccounts.AccountStatus', ['ACTIVE', 'DISCONNECTED'])
                    ->select('users.name', 'users.id',
                        DB::raw("(SELECT TOP 1 Town FROM Billing_ServiceAccounts WHERE GroupCode='" . $day . "' AND MeterReader=CAST(users.id AS varchar)) AS Town"),
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.MeterReader=CAST(users.id AS varchar) AND sa.GroupCode='" . $day . "' AND r.ServicePeriod='" . $period . "' AND r.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=r.AccountNumber AND ServicePeriod='" . $period . "')) AS TotalUnbilledBasedFromReadings"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_ServiceAccounts WHERE AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND MeterReader=CAST(users.id AS varchar) AND GroupCode='" . $day . "' AND id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='". $period ."')) AS AllUnbilled"),
                        DB::raw("(SELECT COUNT(r.id) FROM Billing_Readings r LEFT JOIN Billing_ServiceAccounts sa ON sa.id=r.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND r.AccountNumber IS NOT NULL AND sa.GroupCode='" . $day . "' AND r.MeterReader=CAST(users.id AS varchar) AND r.ServicePeriod='" . $period . "') AS TotalReading"),
                        DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE AccountNumber IS NULL AND ServicePeriod='" . $period . "' AND MeterReader=CAST(users.id AS varchar) AND CAST(ReadingTimestamp AS DATE)=(SELECT TOP 1 ScheduledDate FROM Billing_ReadingSchedules WHERE MeterReader=CAST(users.id AS varchar) AND ServicePeriod='" . $period . "' AND GroupCode='" . $day ."')) AS Captured"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalBills"),
                        DB::raw("(SELECT SUM(TRY_CONVERT(DECIMAL(10,2), KwhUsed)) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND b.KwhUsed IS NOT NULL AND ISNUMERIC(b.KwhUsed)=1 AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalKwh"),
                        DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalAmount"),
                        DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.AccountNumber IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application')) 
                                AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalCollected"),
                        DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Cashier_PaidBills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS TotalCollectedAmount"),
                        // DB::raw("(SELECT COUNT(b.id) FROM Disconnection_History b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE b.Status='DISCONNECTED' AND sa.GroupCode='" . $day . "' AND sa.MeterReader=CAST(users.id AS varchar) AND b.ServicePeriod='" . $period . "') AS DisconnectedCount")
                    )
                    ->groupBy('users.name', 'users.id')
                    ->orderBy('users.name')
                    ->get();
            }
        }
        

        $output = "";
        foreach($data as $item) {
            if ($day == 'All' | $item->TotalReading==0) {
                $output .= "<tr>
                                <th rowspan='2'>" . $item->name . "</th>
                                <td>No. of Bills</td>
                                <th class='text-right'>" . number_format($item->TotalBills) . "</th>
                                <th class='text-right text-success'>" . number_format($item->TotalCollected) . "</th>
                                <th class='text-right text-danger'>" . number_format(IDGenerator::getDifference($item->TotalBills, $item->TotalCollected)) . "</th>
                                <th class='text-right text-primary'>" . number_format(IDGenerator::getPercentage($item->TotalCollected, $item->TotalBills), 2) . "%</th>
                                <th class='text-right text-primary'>" . number_format(IDGenerator::getPercentage(IDGenerator::getDifference($item->TotalBills, $item->TotalCollected), $item->TotalBills), 2) . "%</th>
                            </tr>
                            <tr>
                                <td>Amount</td>
                                <th class='text-right'>" . number_format($item->TotalAmount, 2) . "</th>
                                <th class='text-right text-success'>" . number_format($item->TotalCollectedAmount, 2) . "</th>
                                <th class='text-right text-danger'>" . number_format(IDGenerator::getDifference($item->TotalAmount, $item->TotalCollectedAmount), 2) . "</th>
                                <th class='text-right text-primary'>" . number_format(IDGenerator::getPercentage($item->TotalCollectedAmount, $item->TotalAmount), 2) . "%</th>
                                <th class='text-right text-primary'>" . number_format(IDGenerator::getPercentage(IDGenerator::getDifference($item->TotalAmount, $item->TotalCollectedAmount), $item->TotalAmount), 2) . "%</th>
                            </tr>";
            } else {
                $output .= "<tr>
                                <th rowspan='2'><a href='" . route('readings.view-full-report', [$period, $item->id, $day, $item->Town]) . "'>" . $item->name . "</a></th>
                                <td>No. of Bills</td>
                                <th class='text-right'>" . number_format($item->TotalBills) . "</th>
                                <th class='text-right text-success'>" . number_format($item->TotalCollected) . "</th>
                                <th class='text-right text-danger'>" . number_format(IDGenerator::getDifference($item->TotalBills, $item->TotalCollected)) . "</th>
                                <th class='text-right text-primary'>" . number_format(IDGenerator::getPercentage($item->TotalCollected, $item->TotalBills), 2) . "%</th>
                                <th class='text-right text-primary'>" . number_format(IDGenerator::getPercentage(IDGenerator::getDifference($item->TotalBills, $item->TotalCollected), $item->TotalBills), 2) . "%</th>
                            </tr>
                            <tr>
                                <td>Amount</td>
                                <th class='text-right'>" . number_format($item->TotalAmount, 2) . "</th>
                                <th class='text-right text-success'>" . number_format($item->TotalCollectedAmount, 2) . "</th>
                                <th class='text-right text-danger'>" . number_format(IDGenerator::getDifference($item->TotalAmount, $item->TotalCollectedAmount), 2) . "</th>
                                <th class='text-right text-primary'>" . number_format(IDGenerator::getPercentage($item->TotalCollectedAmount, $item->TotalAmount), 2) . "%</th>
                                <th class='text-right text-primary'>" . number_format(IDGenerator::getPercentage(IDGenerator::getDifference($item->TotalAmount, $item->TotalCollectedAmount), $item->TotalAmount), 2) . "%</th>
                            </tr>";
            }
        }

        return response()->json($output, 200);
    }

    public function changeBapaDueDate(Request $request) {
        $period = $request['Period'];
        $bapaName = $request['BAPAName'];
        $newDueDate = $request['NewDueDate'];
        $route = $request['Route'];

        if ($route == 'All') {
            $bills = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_ServiceAccounts.OrganizationParentAccount='" . $bapaName . "'")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND AccountNumber=Billing_Bills.AccountNumber AND Status IS NULL)")
                ->update(['Billing_Bills.DueDate' => $newDueDate]);
        } else {
            $bills = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_ServiceAccounts.OrganizationParentAccount='" . $bapaName . "' AND Billing_ServiceAccounts.AreaCode='" . $route . "'")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND AccountNumber=Billing_Bills.AccountNumber AND Status IS NULL)")
                ->update(['Billing_Bills.DueDate' => $newDueDate]);
        }

        return response()->json('ok', 200);
    }

    public function deleteBillAndReadingAjax(Request $request) {        
        $bill = Bills::find($request['id']);

        if ($bill != null) {
            Readings::where('AccountNumber', $bill->AccountNumber)
                ->where('ServicePeriod', $bill->ServicePeriod)
                ->delete();

            $bill->delete();
        } 
        return response()->json('ok', 200);
    }

    public function kwhMonitoring() {
        $towns = Towns::all();
        return view('/bills/kwh_monitoring', [
            'towns' => $towns,
        ]);
    }

    public function fetchKwhData(Request $request) {
        $period = $request['ServicePeriod'];
        $town = $request['Town'];
        $route = $request['Route'];

        if ($route != null) {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->where('Billing_Bills.ServicePeriod', $period)
                ->where('Billing_ServiceAccounts.Town', $town)
                ->where('Billing_ServiceAccounts.AreaCode', $route)
                ->select('Billing_Bills.ConsumerType',
                    DB::raw("(COUNT(Billing_Bills.id)) AS NoOfConsumers"),
                    DB::raw("(SUM(TRY_CONVERT(NUMERIC(10,2), Billing_Bills.KwhUsed))) AS TotalKwhUsed"),
                    DB::raw("(SUM(TRY_CONVERT(NUMERIC(10,2), Billing_Bills.NetAmount))) AS TotalAmount"),)
                ->groupBy('Billing_Bills.ConsumerType')
                ->orderBy('Billing_Bills.ConsumerType')
                ->get();
        } else {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->where('Billing_Bills.ServicePeriod', $period)
                ->where('Billing_ServiceAccounts.Town', $town)
                ->select('Billing_Bills.ConsumerType',
                    DB::raw("(COUNT(Billing_Bills.id)) AS NoOfConsumers"),
                    DB::raw("(SUM(TRY_CONVERT(NUMERIC(10,2), Billing_Bills.KwhUsed))) AS TotalKwhUsed"),
                    DB::raw("(SUM(TRY_CONVERT(NUMERIC(10,2), Billing_Bills.NetAmount))) AS TotalAmount"),)
                ->groupBy('Billing_Bills.ConsumerType')
                ->orderBy('Billing_Bills.ConsumerType')
                ->get();
        }

        $output = '';
        $totalKwh = 0;
        $totalAmount = 0;
        $totalCount = 0;
        foreach($data as $item) {
            $output .= "<tr>
                            <td>" . $item->ConsumerType . "</td>
                            <td class='text-right text-primary'>" . number_format($item->NoOfConsumers) . "</td>
                            <td class='text-right text-info'>" . number_format($item->TotalKwhUsed, 2) . "</td>
                            <td class='text-right text-success'>" . number_format($item->TotalAmount, 2) . "</td>
                        </tr>";

            $totalCount += floatval($item->NoOfConsumers);
            $totalKwh += floatval($item->TotalKwhUsed);
            $totalAmount += floatval($item->TotalAmount);
        }

        $output .= "<tr>
                        <th>Total</th>
                        <th class='text-right text-primary'>" . number_format($totalCount) . "</th>
                        <th class='text-right text-info'>" . number_format($totalKwh, 2) . "</th>
                        <th class='text-right text-success'>" . number_format($totalAmount, 2) . "</th>
                    </tr>";

        return response()->json($output, 200);
    }

    public function closeBilling($period) {
        $sales = DistributionSystemLoss::where('ServicePeriod', $period)->first();
        
        Bills::where('ServicePeriod', $period)
            ->update(['IsUnlockedForPayment' => 'CLOSED', 'UnlockedBy' => Auth::id(), 'DemandPreviousKwh' => ($sales != null ? $sales->id : 'x')]);

        // LOCK KWH SALES        
        if ($sales != null) {
            $sales->Status = 'CLOSED';
            $sales->save();
        }

        // save final report
        $data = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->select(
                DB::raw("Billing_ServiceAccounts.Town AS TownCode"),
                DB::raw("Billing_Bills.ConsumerType"),
                DB::raw("COUNT(AccountNumber) AS NoOfConsumers"),
                DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(15,2))) AS KwhUsed"),
                DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(15,2))) AS DemandPresentKwh"),
                DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) AS NetAmount"),
                DB::raw("SUM(TRY_CAST(GenerationSystemCharge AS DECIMAL(15,2))) AS GenerationSystemCharge"),
                DB::raw("SUM(TRY_CAST(TransmissionDeliveryChargeKW AS DECIMAL(15,2))) AS TransmissionDeliveryChargeKW"),
                DB::raw("SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS DECIMAL(15,2))) AS TransmissionDeliveryChargeKWH"),
                DB::raw("SUM(TRY_CAST(SystemLossCharge AS DECIMAL(15,2))) AS SystemLossCharge"),
                DB::raw("SUM(TRY_CAST(DistributionDemandCharge AS DECIMAL(15,2))) AS DistributionDemandCharge"),
                DB::raw("SUM(TRY_CAST(DistributionSystemCharge AS DECIMAL(15,2))) AS DistributionSystemCharge"),
                DB::raw("SUM(TRY_CAST(SupplyRetailCustomerCharge AS DECIMAL(15,2))) AS SupplyRetailCustomerCharge"),
                DB::raw("SUM(TRY_CAST(SupplySystemCharge AS DECIMAL(15,2))) AS SupplySystemCharge"),
                DB::raw("SUM(TRY_CAST(MeteringRetailCustomerCharge AS DECIMAL(15,2))) AS MeteringRetailCustomerCharge"),
                DB::raw("SUM(TRY_CAST(MeteringSystemCharge AS DECIMAL(15,2))) AS MeteringSystemCharge"),
                DB::raw("SUM(TRY_CAST(RFSC AS DECIMAL(15,2))) AS RFSC"),
                DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(15,2))) AS LifelineRate"),
                DB::raw("SUM(TRY_CAST(InterClassCrossSubsidyCharge AS DECIMAL(15,2))) AS InterClassCrossSubsidyCharge"),
                DB::raw("SUM(TRY_CAST(PPARefund AS DECIMAL(15,2))) AS PPARefund"),
                DB::raw("SUM(TRY_CAST(SeniorCitizenSubsidy AS DECIMAL(15,2))) AS SeniorCitizenSubsidy"),
                DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS DECIMAL(15,2))) FROM Billing_Bills b WHERE TRY_CAST(b.SeniorCitizenSubsidy AS DECIMAL(15,2)) < 0 AND b.ServicePeriod='" . $period . "' AND b.AccountNumber LIKE Billing_ServiceAccounts.Town + '-%' AND b.ConsumerType=Billing_Bills.ConsumerType) AS SeniorCitizenDiscount"),
                DB::raw("SUM(TRY_CAST(MissionaryElectrificationCharge AS DECIMAL(15,2))) AS MissionaryElectrificationCharge"),
                DB::raw("SUM(TRY_CAST(EnvironmentalCharge AS DECIMAL(15,2))) AS EnvironmentalCharge"),
                DB::raw("SUM(TRY_CAST(StrandedContractCosts AS DECIMAL(15,2))) AS StrandedContractCosts"),
                DB::raw("SUM(TRY_CAST(NPCStrandedDebt AS DECIMAL(15,2))) AS NPCStrandedDebt"),
                DB::raw("SUM(TRY_CAST(FeedInTariffAllowance AS DECIMAL(15,2))) AS FeedInTariffAllowance"),
                DB::raw("SUM(TRY_CAST(MissionaryElectrificationREDCI AS DECIMAL(15,2))) AS MissionaryElectrificationREDCI"),
                DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(15,2))) AS GenerationVAT"),
                DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(15,2))) AS TransmissionVAT"),
                DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(15,2))) AS SystemLossVAT"),
                DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(15,2))) AS DistributionVAT"),
                DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(15,2))) AS RealPropertyTax"),
                DB::raw("SUM(TRY_CAST(OtherGenerationRateAdjustment AS DECIMAL(15,2))) AS OtherGenerationRateAdjustment"),
                DB::raw("SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS DECIMAL(15,2))) AS OtherTransmissionCostAdjustmentKW"),
                DB::raw("SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS DECIMAL(15,2))) AS OtherTransmissionCostAdjustmentKWH"),
                DB::raw("SUM(TRY_CAST(OtherSystemLossCostAdjustment AS DECIMAL(15,2))) AS OtherSystemLossCostAdjustment"),
                DB::raw("SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS DECIMAL(15,2))) AS OtherLifelineRateCostAdjustment"),
                DB::raw("SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS DECIMAL(15,2))) AS SeniorCitizenDiscountAndSubsidyAdjustment"),
                DB::raw("SUM(TRY_CAST(FranchiseTax AS DECIMAL(15,2))) AS FranchiseTax"),
                DB::raw("SUM(TRY_CAST(BusinessTax AS DECIMAL(15,2))) AS BusinessTax"),
                DB::raw("SUM(TRY_CAST(KatasNgVat AS DECIMAL(15,2))) AS KatasNgVat")
            )
            ->where('ServicePeriod', $period)
            ->whereRaw("(Billing_Bills.AdjustmentType IS NULL OR Billing_Bills.AdjustmentType NOT IN ('DM/CM'))")
            ->groupByRaw("Billing_ServiceAccounts.Town, Billing_Bills.ConsumerType")
            ->orderByRaw("Billing_ServiceAccounts.Town, Billing_Bills.ConsumerType")
            ->get();

        // INSERT TO BILLS
        foreach($data as $item) {
            $bill = new Bills([
                'id' => IDGenerator::generateIDandRandString(),
                'DemandPreviousKwh' => ($sales != null ? $sales->id : 'x'),
                'ServicePeriod' => $period,
                'AccountNumber' => $item->TownCode,
                'ConsumerType' => $item->ConsumerType,
                'KwhUsed' => $item->KwhUsed,
                'DemandPresentKwh' => $item->DemandPresentKwh,
                'NetAmount' => $item->NetAmount,
                'GenerationSystemCharge' => $item->GenerationSystemCharge,
                'TransmissionDeliveryChargeKW' => $item->TransmissionDeliveryChargeKW,
                'TransmissionDeliveryChargeKWH' => $item->TransmissionDeliveryChargeKWH,
                'SystemLossCharge' => $item->SystemLossCharge,
                'DistributionDemandCharge' => $item->DistributionDemandCharge,
                'DistributionSystemCharge' => $item->DistributionSystemCharge,
                'SupplyRetailCustomerCharge' => $item->SupplyRetailCustomerCharge,
                'SupplySystemCharge' => $item->SupplySystemCharge,
                'MeteringRetailCustomerCharge' => $item->MeteringRetailCustomerCharge,
                'MeteringSystemCharge' => $item->MeteringSystemCharge,
                'RFSC' => $item->RFSC,
                'LifelineRate' => $item->LifelineRate,
                'InterClassCrossSubsidyCharge' => $item->InterClassCrossSubsidyCharge,
                'PPARefund' => $item->PPARefund,
                'SeniorCitizenSubsidy' => $item->SeniorCitizenSubsidy,
                'MissionaryElectrificationCharge' => $item->MissionaryElectrificationCharge,
                'EnvironmentalCharge' => $item->EnvironmentalCharge,
                'StrandedContractCosts' => $item->StrandedContractCosts,
                'NPCStrandedDebt' => $item->NPCStrandedDebt,
                'FeedInTariffAllowance' => $item->FeedInTariffAllowance,
                'MissionaryElectrificationREDCI' => $item->MissionaryElectrificationREDCI,
                'GenerationVAT' => $item->GenerationVAT,
                'TransmissionVAT' => $item->TransmissionVAT,
                'SystemLossVAT' => $item->SystemLossVAT,
                'DistributionVAT' => $item->DistributionVAT,
                'RealPropertyTax' => $item->RealPropertyTax,
                'OtherGenerationRateAdjustment' => $item->OtherGenerationRateAdjustment,
                'OtherTransmissionCostAdjustmentKW' => $item->OtherTransmissionCostAdjustmentKW,
                'OtherTransmissionCostAdjustmentKWH' => $item->OtherTransmissionCostAdjustmentKWH,
                'OtherSystemLossCostAdjustment' => $item->OtherSystemLossCostAdjustment,
                'OtherLifelineRateCostAdjustment' => $item->OtherLifelineRateCostAdjustment,
                'SeniorCitizenDiscountAndSubsidyAdjustment' => $item->SeniorCitizenDiscountAndSubsidyAdjustment,
                'FranchiseTax' => $item->FranchiseTax,
                'BusinessTax' => $item->BusinessTax,
                'KatasNgVat' => $item->KatasNgVat,
                'ForCancellation' => 'SALES_REPORT',
                'BillNumber' => $item->NoOfConsumers,
                'AdditionalKwh' => $item->SeniorCitizenDiscount,
            ]);

            $bill->save();
        }

        /**
         * INSERT LIFELINERS
         */
        $lifeliners = DB::table('Billing_Bills')
            ->whereRaw("ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(KwhUsed AS INTEGER) < 26")
            ->select('*')
            ->get();
        foreach ($lifeliners as $item) {
            $billsArr = (array) $item;
            $billsArr['id'] = IDGenerator::generateIDandRandString();
            $billsArr['UnlockedBy'] = 'SALES_CLOSED_LIFELINERS';
            $billsOriginal = BillsOriginal::create($billsArr);
        }

        /**
         * INSERT SENIOR CITIZEN
         */
        $sc = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
            ->select('Billing_Bills.*',
            )
            ->get();
        foreach ($sc as $item) {
            $billsArr = (array) $item;
            $billsArr['id'] = IDGenerator::generateIDandRandString();
            $billsArr['UnlockedBy'] = 'SALES_CLOSED_SC';
            $billsOriginal = BillsOriginal::create($billsArr);
        }

        /**
         * INSERT TSD KWH SALES EXPANDED
         */
        $expanded = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereNotNull('AreaCode')
            ->whereNotNull('Town')
            ->whereRaw("(Billing_Bills.AdjustmentType IS NULL OR Billing_Bills.AdjustmentType NOT IN ('DM/CM'))")
            ->whereRaw("ServicePeriod='" . $period . "'")
            ->select('AreaCode',
                'Town',
                DB::raw("COUNT(Billing_ServiceAccounts.id) AS TotalBilledConsumers"),
                DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltage"),
                DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS HighVoltage"),
                DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(15,2))) AS TotalKwhUsed"),
                DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) AS TotalAmount"),
                DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Missionary"),
                DB::raw("(SELECT SUM(TRY_CAST(b.EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Environmental"),
                DB::raw("(SELECT SUM(TRY_CAST(b.NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS NPC"),
                DB::raw("(SELECT SUM(TRY_CAST(b.StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS StrandedCC"),
                DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS Redci"),
                DB::raw("(SELECT SUM(TRY_CAST(b.FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS FITAll"),
                DB::raw("(SELECT SUM(TRY_CAST(b.RFSC AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS RFSC"),
                DB::raw("(SELECT SUM(TRY_CAST(b.RealPropertyTax AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS RPT"),
                DB::raw("(SELECT SUM(TRY_CAST(b.GenerationVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS GenVat"),
                DB::raw("(SELECT SUM(TRY_CAST(b.TransmissionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS TransVat"),
                DB::raw("(SELECT SUM(TRY_CAST(b.SystemLossVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS SysLossVat"),
                DB::raw("(SELECT SUM(TRY_CAST(b.DistributionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "') AS DistVat"),
                DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode=Billing_ServiceAccounts.AreaCode AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
            )
            ->groupBy('AreaCode', 'Town')
            ->orderBy('Town')
            ->orderBy('AreaCode')
            ->get();
        foreach ($expanded as $item) {
            $bo = new BillsOriginal;
            $bo->id = IDGenerator::generateIDandRandString();
            $bo->AccountNumber = $item->AreaCode;
            $bo->KwhAmount = $item->Town;
            $bo->ServicePeriod = $period;
            $bo->BillNumber = $item->TotalBilledConsumers;
            $bo->Multiplier = $item->Residentials;
            $bo->Coreloss = $item->LowVoltage;
            $bo->DemandPreviousKwh = $item->HighVoltage;
            $bo->KwhUsed = $item->TotalKwhUsed;
            $bo->NetAmount = $item->TotalAmount;
            $bo->MissionaryElectrificationCharge = $item->Missionary;
            $bo->EnvironmentalCharge = $item->Environmental;
            $bo->NPCStrandedDebt = $item->NPC;
            $bo->StrandedContractCosts = $item->StrandedCC;
            $bo->MissionaryElectrificationREDCI = $item->Redci;
            $bo->FeedInTariffAllowance = $item->FITAll;
            $bo->RFSC = $item->RFSC;
            $bo->RealPropertyTax = $item->RPT;
            $bo->GenerationVAT = $item->GenVat;
            $bo->TransmissionVAT = $item->TransVat;
            $bo->SystemLossVAT = $item->SysLossVat;
            $bo->DistributionVAT = $item->DistVat;
            $bo->SeniorCitizenSubsidy = $item->SCSubsidy;
            $bo->PPARefund = $item->SCDsc;
            $bo->UnlockedBy = 'SALES_CLOSED_TSD_EXPANDED';
            $bo->save();
        }

        $expandedNulls = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereNull('AreaCode')
            ->whereNotNull('Town')
            ->whereRaw("(Billing_Bills.AdjustmentType IS NULL OR Billing_Bills.AdjustmentType NOT IN ('DM/CM'))")
            ->whereRaw("ServicePeriod='" . $period . "'")
            ->select('AreaCode',
                'Town',
                DB::raw("COUNT(Billing_ServiceAccounts.id) AS TotalBilledConsumers"),
                DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL', 'BAPA')) AS Residentials"),
                DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltage"),
                DB::raw("(SELECT SUM(TRY_CAST(b.KwhUsed AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.Town=Billing_ServiceAccounts.Town AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND b.ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS HighVoltage"),
                DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(15,2))) AS TotalKwhUsed"),
                DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) AS TotalAmount"),
                DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Missionary"),
                DB::raw("(SELECT SUM(TRY_CAST(b.EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Environmental"),
                DB::raw("(SELECT SUM(TRY_CAST(b.NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS NPC"),
                DB::raw("(SELECT SUM(TRY_CAST(b.StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS StrandedCC"),
                DB::raw("(SELECT SUM(TRY_CAST(b.MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS Redci"),
                DB::raw("(SELECT SUM(TRY_CAST(b.FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS FITAll"),
                DB::raw("(SELECT SUM(TRY_CAST(b.RFSC AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS RFSC"),
                DB::raw("(SELECT SUM(TRY_CAST(b.RealPropertyTax AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS RPT"),
                DB::raw("(SELECT SUM(TRY_CAST(b.GenerationVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS GenVat"),
                DB::raw("(SELECT SUM(TRY_CAST(b.TransmissionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS TransVat"),
                DB::raw("(SELECT SUM(TRY_CAST(b.SystemLossVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS SysLossVat"),
                DB::raw("(SELECT SUM(TRY_CAST(b.DistributionVAT AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "') AS DistVat"),
                DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS SCSubsidy"),
                DB::raw("(SELECT SUM(TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON b.AccountNumber=sa.id WHERE sa.Town=Billing_ServiceAccounts.Town AND (b.AdjustmentType IS NULL OR b.AdjustmentType NOT IN ('DM/CM')) AND sa.AreaCode IS NULL AND b.ServicePeriod='" . $period . "' AND TRY_CAST(b.SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS SCDsc")
            )
            ->groupBy('AreaCode', 'Town')
            ->orderBy('Town')
            ->orderBy('AreaCode')
            ->get();
        foreach ($expandedNulls as $item) {
            $bo = new BillsOriginal;
            $bo->id = IDGenerator::generateIDandRandString();
            $bo->AccountNumber = $item->AreaCode;
            $bo->KwhAmount = $item->Town;
            $bo->ServicePeriod = $period;
            $bo->BillNumber = $item->TotalBilledConsumers;
            $bo->Multiplier = $item->Residentials;
            $bo->Coreloss = $item->LowVoltage;
            $bo->DemandPreviousKwh = $item->HighVoltage;
            $bo->KwhUsed = $item->TotalKwhUsed;
            $bo->NetAmount = $item->TotalAmount;
            $bo->MissionaryElectrificationCharge = $item->Missionary;
            $bo->EnvironmentalCharge = $item->Environmental;
            $bo->NPCStrandedDebt = $item->NPC;
            $bo->StrandedContractCosts = $item->StrandedCC;
            $bo->MissionaryElectrificationREDCI = $item->Redci;
            $bo->FeedInTariffAllowance = $item->FITAll;
            $bo->RFSC = $item->RFSC;
            $bo->RealPropertyTax = $item->RPT;
            $bo->GenerationVAT = $item->GenVat;
            $bo->TransmissionVAT = $item->TransVat;
            $bo->SystemLossVAT = $item->SysLossVat;
            $bo->DistributionVAT = $item->DistVat;
            $bo->SeniorCitizenSubsidy = $item->SCSubsidy;
            $bo->PPARefund = $item->SCDsc;
            $bo->UnlockedBy = 'SALES_CLOSED_TSD_EXPANDED';
            $bo->save();
        }

        /**
         * INSERT COOP CONSUMPTION
         */
        $coopConsumptions = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereRaw("Billing_ServiceAccounts.Item1='Yes' AND Billing_Bills.ServicePeriod='" . $period . "'")
            ->select(
                'Billing_Bills.*'
            )
            ->get();
        foreach ($coopConsumptions as $item) {
            $billsArr = (array) $item;
            $billsArr['id'] = IDGenerator::generateIDandRandString();
            $billsArr['UnlockedBy'] = 'SALES_CLOSED_COOP_CONSUMPTION';
            $billsOriginal = BillsOriginal::create($billsArr);
        }

        return response()->json('closed', 200);
    }

    public function lifelinersReport(Request $request) {
        $town = $request['Town'];
        $period = $request['ServicePeriod'];
        $kwhUsed = $request['KwhUsed'];

        $sales = DistributionSystemLoss::where('ServicePeriod', $period)->first();

        if ($sales != null && $sales->Status=='CLOSED') {
            /**
             * START OF CLOSED ===========================
             */
            if ($town == 'All') {
                if ($kwhUsed == 'All') {
                    $bills = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER) < 26")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('KwhUsed')
                        ->get();

                    $summary = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER) < 26")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.KwhUsed',
                            DB::raw("COUNT(Billing_BillsOriginal.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                } else {
                    $bills = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('OldAccountNo')
                        ->get();

                    $summary = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.KwhUsed',
                            DB::raw("COUNT(Billing_BillsOriginal.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                }
            } else {
                if ($kwhUsed == 'All') {
                    $bills = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER) < 26")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('KwhUsed')
                        ->get();

                    $summary = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER) < 26")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.KwhUsed',
                            DB::raw("COUNT(Billing_BillsOriginal.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                } else {
                    $bills = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('OldAccountNo')
                        ->get();

                    $summary = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.KwhUsed',
                            DB::raw("COUNT(Billing_BillsOriginal.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                }
            }
            /**
             * END OF CLOSED ==========================
             */
        } else {
            if ($town == 'All') {
                if ($kwhUsed == 'All') {
                    $bills = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER) < 26")
                        ->select('Billing_Bills.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('KwhUsed')
                        ->get();

                    $summary = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER) < 26")
                        ->select('Billing_Bills.KwhUsed',
                            DB::raw("COUNT(Billing_Bills.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                } else {
                    $bills = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->select('Billing_Bills.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('OldAccountNo')
                        ->get();

                    $summary = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->select('Billing_Bills.KwhUsed',
                            DB::raw("COUNT(Billing_Bills.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                }
            } else {
                if ($kwhUsed == 'All') {
                    $bills = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER) < 26")
                        ->select('Billing_Bills.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('KwhUsed')
                        ->get();

                    $summary = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER) < 26")
                        ->select('Billing_Bills.KwhUsed',
                            DB::raw("COUNT(Billing_Bills.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                } else {
                    $bills = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->select('Billing_Bills.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('OldAccountNo')
                        ->get();

                    $summary = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->select('Billing_Bills.KwhUsed',
                            DB::raw("COUNT(Billing_Bills.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                }
            }
        }

        $towns = Towns::all();

        return view('/bills/lifeliners_report', [
            'bills' => $bills,
            'towns' => $towns,
            'summary' => $summary
        ]);
    }

    public function printLifeliners($town, $period, $kwhUsed) {
        $sales = DistributionSystemLoss::where('ServicePeriod', $period)->first();

        if ($sales != null && $sales->Status=='CLOSED') {
            /**
             * START OF CLOSED ===========================
             */
            if ($town == 'All') {
                if ($kwhUsed == 'All') {
                    $bills = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER) < 26")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'Billing_ServiceAccounts.AccountStatus',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('KwhUsed')
                        ->get();

                    $summary = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER) < 26")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.KwhUsed',
                            DB::raw("COUNT(Billing_BillsOriginal.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                } else {
                    $bills = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'Billing_ServiceAccounts.AccountStatus',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('OldAccountNo')
                        ->get();

                    $summary = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.KwhUsed',
                            DB::raw("COUNT(Billing_BillsOriginal.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                }
            } else {
                if ($kwhUsed == 'All') {
                    $bills = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER) < 26")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'Billing_ServiceAccounts.AccountStatus',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('KwhUsed')
                        ->get();

                    $summary = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER) < 26")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.KwhUsed',
                            DB::raw("COUNT(Billing_BillsOriginal.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                } else {
                    $bills = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'Billing_ServiceAccounts.AccountStatus',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('OldAccountNo')
                        ->get();

                    $summary = DB::table('Billing_BillsOriginal')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_BillsOriginal.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_LIFELINERS'")
                        ->select('Billing_BillsOriginal.KwhUsed',
                            DB::raw("COUNT(Billing_BillsOriginal.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                }
            }
            /**
             * END OF CLOSED ==========================
             */
        } else {
            if ($town == 'All') {
                if ($kwhUsed == 'All') {
                    $bills = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER) < 26")
                        ->select('Billing_Bills.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'Billing_ServiceAccounts.AccountStatus',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('KwhUsed')
                        ->get();

                    $summary = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER) < 26")
                        ->select('Billing_Bills.KwhUsed',
                            DB::raw("COUNT(Billing_Bills.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                } else {
                    $bills = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->select('Billing_Bills.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'Billing_ServiceAccounts.AccountStatus',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('OldAccountNo')
                        ->get();

                    $summary = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->select('Billing_Bills.KwhUsed',
                            DB::raw("COUNT(Billing_Bills.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                }
            } else {
                if ($kwhUsed == 'All') {
                    $bills = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER) < 26")
                        ->select('Billing_Bills.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'Billing_ServiceAccounts.AccountStatus',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('KwhUsed')
                        ->get();

                    $summary = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER) < 26")
                        ->select('Billing_Bills.KwhUsed',
                            DB::raw("COUNT(Billing_Bills.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                } else {
                    $bills = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->select('Billing_Bills.*',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'Billing_ServiceAccounts.AccountStatus',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay'
                        )
                        ->orderBy('OldAccountNo')
                        ->get();

                    $summary = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND TRY_CAST(Billing_Bills.KwhUsed AS INTEGER)='" . $kwhUsed . "'")
                        ->select('Billing_Bills.KwhUsed',
                            DB::raw("COUNT(Billing_Bills.id) AS TotalCount"),
                            DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(20,2))) AS TotalKwhUsed"),
                            DB::raw("SUM(TRY_CAST(LifelineRate AS DECIMAL(20,2))) AS TotalDsc"),
                            DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(20,2))) AS TotalAmount")
                        )
                        ->groupBy('KwhUsed')
                        ->orderByRaw('TRY_CAST(KwhUsed AS INTEGER)')
                        ->get();
                }
            }
        }

        $town = Towns::find($town);

        return view('/bills/print_lifeliners', [
            'bills' => $bills,
            'period' => $period,
            'town' => $town != null ? $town->Town : '-',
            'kwhUsed' => $kwhUsed,
            'summary' => $summary,
        ]);
    }

    public function seniorCitizenReport(Request $request) {
        $town = $request['Town'];
        $period = $request['ServicePeriod'];

        $sales = DistributionSystemLoss::where('ServicePeriod', $period)->first();

        if ($sales != null && $sales->Status=='CLOSED') {
            if ($town == 'All') {
                $bills = DB::table('Billing_BillsOriginal')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_SC'")
                    ->whereRaw("Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select('Billing_BillsOriginal.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay'
                    )
                    ->orderBy('KwhUsed')
                    ->get();

                $summary = DB::table('Billing_BillsOriginal')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_SC'")
                    ->whereRaw("Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select(
                        DB::raw("TRY_CAST(KwhUsed AS DECIMAL(10,1)) AS KwhUsed"),
                        DB::raw("COUNT(Billing_BillsOriginal.id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(10,2))) AS TotalKwhUsed"),
                        DB::raw("SUM(TRY_CAST(SeniorCitizenSubsidy AS DECIMAL(10,2))) AS TotalDiscount"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) AS TotalAmount")
                    )
                    ->groupByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->orderByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->get();
            } else {
                $bills = DB::table('Billing_BillsOriginal')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_SC'")
                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select('Billing_BillsOriginal.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay'
                    )
                    ->orderBy('KwhUsed')
                    ->get();

                $summary = DB::table('Billing_BillsOriginal')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_SC'")
                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select(
                        DB::raw("TRY_CAST(KwhUsed AS DECIMAL(10,1)) AS KwhUsed"),
                        DB::raw("COUNT(Billing_BillsOriginal.id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(10,2))) AS TotalKwhUsed"),
                        DB::raw("SUM(TRY_CAST(SeniorCitizenSubsidy AS DECIMAL(10,2))) AS TotalDiscount"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) AS TotalAmount")
                    )
                    ->groupByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->orderByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->get();
            }  
        } else {
            if ($town == 'All') {
                $bills = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select('Billing_Bills.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay'
                    )
                    ->orderBy('KwhUsed')
                    ->get();

                $summary = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select(
                        DB::raw("TRY_CAST(KwhUsed AS DECIMAL(10,1)) AS KwhUsed"),
                        DB::raw("COUNT(Billing_Bills.id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(10,2))) AS TotalKwhUsed"),
                        DB::raw("SUM(TRY_CAST(SeniorCitizenSubsidy AS DECIMAL(10,2))) AS TotalDiscount"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) AS TotalAmount")
                    )
                    ->groupByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->orderByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->get();
            } else {
                $bills = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select('Billing_Bills.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay'
                    )
                    ->orderBy('KwhUsed')
                    ->get();

                $summary = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select(
                        DB::raw("TRY_CAST(KwhUsed AS DECIMAL(10,1)) AS KwhUsed"),
                        DB::raw("COUNT(Billing_Bills.id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(10,2))) AS TotalKwhUsed"),
                        DB::raw("SUM(TRY_CAST(SeniorCitizenSubsidy AS DECIMAL(10,2))) AS TotalDiscount"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) AS TotalAmount")
                    )
                    ->groupByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->orderByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->get();
            }  
        }              

        $towns = Towns::all();

        return view('/bills/senior_citizen_report', [
            'bills' => $bills,
            'towns' => $towns,
            'summary' => $summary,
        ]);
    }

    public function printSeniorCitizen($town, $period) {
        $sales = DistributionSystemLoss::where('ServicePeriod', $period)->first();

        if ($sales != null && $sales->Status=='CLOSED') {
            if ($town == 'All') {
                $bills = DB::table('Billing_BillsOriginal')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_SC'")
                    ->whereRaw("Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select('Billing_BillsOriginal.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.AccountStatus',
                        'Billing_ServiceAccounts.Purok',
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay'
                    )
                    ->orderBy('KwhUsed')
                    ->get();

                $summary = DB::table('Billing_BillsOriginal')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_SC'")
                    ->whereRaw("Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select(
                        DB::raw("TRY_CAST(KwhUsed AS DECIMAL(10,1)) AS KwhUsed"),
                        DB::raw("COUNT(Billing_BillsOriginal.id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(10,2))) AS TotalKwhUsed"),
                        DB::raw("SUM(TRY_CAST(SeniorCitizenSubsidy AS DECIMAL(10,2))) AS TotalDiscount"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) AS TotalAmount")
                    )
                    ->groupByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->orderByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->get();
            } else {
                $bills = DB::table('Billing_BillsOriginal')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_SC'")
                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select('Billing_BillsOriginal.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.AccountStatus',
                        'Billing_ServiceAccounts.Purok',
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay'
                    )
                    ->orderBy('KwhUsed')
                    ->get();

                $summary = DB::table('Billing_BillsOriginal')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_BillsOriginal.UnlockedBy='SALES_CLOSED_SC'")
                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_BillsOriginal.ServicePeriod='" . $period . "' AND Billing_BillsOriginal.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select(
                        DB::raw("TRY_CAST(KwhUsed AS DECIMAL(10,1)) AS KwhUsed"),
                        DB::raw("COUNT(Billing_BillsOriginal.id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(10,2))) AS TotalKwhUsed"),
                        DB::raw("SUM(TRY_CAST(SeniorCitizenSubsidy AS DECIMAL(10,2))) AS TotalDiscount"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) AS TotalAmount")
                    )
                    ->groupByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->orderByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->get();
            }  
        } else {
            if ($town == 'All') {
                $bills = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select('Billing_Bills.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.AccountStatus',
                        'Billing_ServiceAccounts.Purok',
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay'
                    )
                    ->orderBy('KwhUsed')
                    ->get();

                $summary = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select(
                        DB::raw("TRY_CAST(KwhUsed AS DECIMAL(10,1)) AS KwhUsed"),
                        DB::raw("COUNT(Billing_Bills.id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(10,2))) AS TotalKwhUsed"),
                        DB::raw("SUM(TRY_CAST(SeniorCitizenSubsidy AS DECIMAL(10,2))) AS TotalDiscount"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) AS TotalAmount")
                    )
                    ->groupByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->orderByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->get();
            } else {
                $bills = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select('Billing_Bills.*',
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.AccountStatus',
                        'Billing_ServiceAccounts.Purok',
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay'
                    )
                    ->orderBy('KwhUsed')
                    ->get();

                $summary = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_Bills.ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND SeniorCitizen='Yes' AND TRY_CAST(KwhUsed AS DECIMAL(10,1)) < 101")
                    ->select(
                        DB::raw("TRY_CAST(KwhUsed AS DECIMAL(10,1)) AS KwhUsed"),
                        DB::raw("COUNT(Billing_Bills.id) AS NoOfConsumers"),
                        DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(10,2))) AS TotalKwhUsed"),
                        DB::raw("SUM(TRY_CAST(SeniorCitizenSubsidy AS DECIMAL(10,2))) AS TotalDiscount"),
                        DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) AS TotalAmount")
                    )
                    ->groupByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->orderByRaw("TRY_CAST(KwhUsed AS DECIMAL(10,1))")
                    ->get();
            }  
        } 

        return view('/bills/print_senior_citizen', [
            'bills' => $bills,
            'town' => $town != null ? ($town=='All' ? 'All' : $town->Town) : '-',
            'period' => $period,
            'summary' => $summary,
        ]);
    }

    public function governmentTaxReport(Request $request) {
        $period = $request['ServicePeriod'];
        $town = $request['Town'];
        $route = $request['Route'];

        if ($route != null) {
            $data = DB::table('Billing_Bills')                
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_Bills.ServicePeriod='" . $period . "' AND AreaCode='" . $route . "'")
                ->select('Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Barangays.Barangay',
                    'CRM_Towns.Town',
                    'Billing_Bills.*')
                ->orderBy("Billing_ServiceAccounts.OldAccountNo")
                ->get();
        } else {
            $data = [];
        }
        
        return view('/bills/government_tax_report', [
            'towns' => Towns::all(),
            'data' => $data
        ]);
    }

    public function printGovernmentTaxReport($period, $town, $route) {
        if ($route != null) {
            $data = DB::table('Billing_Bills')                
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND Billing_Bills.ServicePeriod='" . $period . "' AND AreaCode='" . $route . "'")
                ->select('Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Barangays.Barangay',
                    'CRM_Towns.Town',
                    'Billing_Bills.*')
                ->orderBy("Billing_ServiceAccounts.OldAccountNo")
                ->get();
        } else {
            $data = [];
        }
        
        $towns = Towns::find($town);
        return view('/bills/print_government_tax_report', [
            'data' => $data,
            'period' => $period,
            'towns' => $towns,
            'route' => $route,
        ]);
    }

    public function outstandingReport(Request $request) {
        $asOf = $request['AsOf'];
        $town = $request['Town'];
        $status = $request['Status'];

        $currentRate = Rates::orderByDesc('ServicePeriod')->first();

        if ($asOf != null) {
            if ($status == 'All') {
                if ($town == 'All') {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'users.name',
                            'AccountStatus',
                            'Billing_Bills.*')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                } else {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' AND Town='" . $town . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'users.name',
                            'AccountStatus',
                            'Billing_Bills.*')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                }
            } else {
                if ($town == 'All') {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' 
                                AND Billing_ServiceAccounts.AccountStatus='" . $status . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'users.name',
                            'AccountStatus',
                            'Billing_Bills.*')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                } else {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' AND Town='" . $town . "' 
                                AND Billing_ServiceAccounts.AccountStatus='" . $status . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'users.name',
                            'AccountStatus',
                            'Billing_Bills.*')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                }
            }            
        } else {
            $data = [];
        }
        
        return view('/bills/outstanding_report', [
            'towns' => Towns::all(),
            'data' => $data,
        ]);
    }

    public function downloadOutstandingReport($asOf, $town, $status) {
        $currentRate = Rates::orderByDesc('ServicePeriod')->first();

        if ($asOf != null) {
            if ($status == 'All') {
                if ($town == 'All') {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'ServicePeriod',
                            'NetAmount',
                            'AccountStatus',
                            'GenerationSystemCharge',
                            'TransmissionDeliveryChargeKW',
                            'TransmissionDeliveryChargeKWH',
                            'SystemLossCharge',
                            'DistributionDemandCharge',
                            'DistributionSystemCharge',
                            'SupplyRetailCustomerCharge',
                            'SupplySystemCharge',
                            'MeteringRetailCustomerCharge',
                            'MeteringSystemCharge',
                            'RFSC',
                            'LifelineRate',
                            'InterClassCrossSubsidyCharge',
                            'PPARefund',
                            'SeniorCitizenSubsidy',
                            'MissionaryElectrificationCharge',
                            'EnvironmentalCharge',
                            'StrandedContractCosts',
                            'NPCStrandedDebt',
                            'FeedInTariffAllowance',
                            'MissionaryElectrificationREDCI',
                            'OtherGenerationRateAdjustment',
                            'OtherTransmissionCostAdjustmentKW',
                            'OtherTransmissionCostAdjustmentKWH',
                            'OtherSystemLossCostAdjustment',
                            'OtherLifelineRateCostAdjustment',
                            'SeniorCitizenDiscountAndSubsidyAdjustment',
                            'RealPropertyTax',
                            'GenerationVAT',
                            'TransmissionVAT',
                            'SystemLossVAT',
                            'DistributionVAT')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                } else {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' AND Town='" . $town . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'ServicePeriod',
                            'NetAmount',
                            'AccountStatus',                            
                            'GenerationSystemCharge',
                            'TransmissionDeliveryChargeKW',
                            'TransmissionDeliveryChargeKWH',
                            'SystemLossCharge',
                            'DistributionDemandCharge',
                            'DistributionSystemCharge',
                            'SupplyRetailCustomerCharge',
                            'SupplySystemCharge',
                            'MeteringRetailCustomerCharge',
                            'MeteringSystemCharge',
                            'RFSC',
                            'LifelineRate',
                            'InterClassCrossSubsidyCharge',
                            'PPARefund',
                            'SeniorCitizenSubsidy',
                            'MissionaryElectrificationCharge',
                            'EnvironmentalCharge',
                            'StrandedContractCosts',
                            'NPCStrandedDebt',
                            'FeedInTariffAllowance',
                            'MissionaryElectrificationREDCI',
                            'OtherGenerationRateAdjustment',
                            'OtherTransmissionCostAdjustmentKW',
                            'OtherTransmissionCostAdjustmentKWH',
                            'OtherSystemLossCostAdjustment',
                            'OtherLifelineRateCostAdjustment',
                            'SeniorCitizenDiscountAndSubsidyAdjustment',
                            'RealPropertyTax',
                            'GenerationVAT',
                            'TransmissionVAT',
                            'SystemLossVAT',
                            'DistributionVAT')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                }
            } else {
                if ($town == 'All') {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' 
                                AND Billing_ServiceAccounts.AccountStatus='" . $status . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'ServicePeriod',
                            'NetAmount',
                            'AccountStatus',
                            'GenerationSystemCharge',
                            'TransmissionDeliveryChargeKW',
                            'TransmissionDeliveryChargeKWH',
                            'SystemLossCharge',
                            'DistributionDemandCharge',
                            'DistributionSystemCharge',
                            'SupplyRetailCustomerCharge',
                            'SupplySystemCharge',
                            'MeteringRetailCustomerCharge',
                            'MeteringSystemCharge',
                            'RFSC',
                            'LifelineRate',
                            'InterClassCrossSubsidyCharge',
                            'PPARefund',
                            'SeniorCitizenSubsidy',
                            'MissionaryElectrificationCharge',
                            'EnvironmentalCharge',
                            'StrandedContractCosts',
                            'NPCStrandedDebt',
                            'FeedInTariffAllowance',
                            'MissionaryElectrificationREDCI',
                            'OtherGenerationRateAdjustment',
                            'OtherTransmissionCostAdjustmentKW',
                            'OtherTransmissionCostAdjustmentKWH',
                            'OtherSystemLossCostAdjustment',
                            'OtherLifelineRateCostAdjustment',
                            'SeniorCitizenDiscountAndSubsidyAdjustment',
                            'RealPropertyTax',
                            'GenerationVAT',
                            'TransmissionVAT',
                            'SystemLossVAT',
                            'DistributionVAT')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                } else {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' AND Town='" . $town . "' 
                                AND Billing_ServiceAccounts.AccountStatus='" . $status . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'ServicePeriod',
                            'NetAmount',
                            'AccountStatus',
                            'GenerationSystemCharge',
                            'TransmissionDeliveryChargeKW',
                            'TransmissionDeliveryChargeKWH',
                            'SystemLossCharge',
                            'DistributionDemandCharge',
                            'DistributionSystemCharge',
                            'SupplyRetailCustomerCharge',
                            'SupplySystemCharge',
                            'MeteringRetailCustomerCharge',
                            'MeteringSystemCharge',
                            'RFSC',
                            'LifelineRate',
                            'InterClassCrossSubsidyCharge',
                            'PPARefund',
                            'SeniorCitizenSubsidy',
                            'MissionaryElectrificationCharge',
                            'EnvironmentalCharge',
                            'StrandedContractCosts',
                            'NPCStrandedDebt',
                            'FeedInTariffAllowance',
                            'MissionaryElectrificationREDCI',
                            'OtherGenerationRateAdjustment',
                            'OtherTransmissionCostAdjustmentKW',
                            'OtherTransmissionCostAdjustmentKWH',
                            'OtherSystemLossCostAdjustment',
                            'OtherLifelineRateCostAdjustment',
                            'SeniorCitizenDiscountAndSubsidyAdjustment',
                            'RealPropertyTax',
                            'GenerationVAT',
                            'TransmissionVAT',
                            'SystemLossVAT',
                            'DistributionVAT')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                }
            }            
        } else {
            $data = [];
        }

        $headers = [
            'Account Number',
            'Consumer Name',
            'Address',
            'Billing Month',
            'Net Amount',
            'Status',
            'Generation',
            'Transmission (KW)',
            'Transmission (KWH)',
            'Systems Loss',
            'Distribution Demand',
            'Distribution System',
            'Supply Retail',
            'Supply System',
            'Metering Retail',
            'Metering System',
            'RFSC',
            'Lifeline Rate',
            'ICCS',
            'PPA Refund',
            'Senior Citizen',
            'Missionary Electrification',
            'Environmental',
            'Stranded Contract Costs',
            'NPC Stranded Debt',
            'FIT All',
            'REDCI',
            'OGA',
            'OTCA (KW)',
            'OTCA (KWH)',
            'OSLA',
            'OLRA',
            'SCSA',
            'RPT',
            'Generation VAT',
            'Transmission VAT',
            'System Loss VAT',
            'Distribution VAT'
        ];

        $styles = [
            // Style the first row as bold text.
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            2 => [
                'alignment' => ['horizontal' => 'center'],
            ],
            4 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            7 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
        
        $export = new DynamicExports($data->toArray(), 
                                    null, 
                                    $town,
                                    $headers, 
                                    [],
                                    'A8',
                                    $styles,
                                    'OUTSTANDING REPORT AS OF ' . date('F d, Y', strtotime($asOf))
                                );

        return Excel::download($export, 'Outstanding-Report.xlsx');
    }

    public function disconnectedReports(Request $request) {
        $from = $request['From'];
        $to = $request['To'];
        $town = $request['Town'];

        if ($town == 'All') {
            $data = DB::table('Cashier_TransactionDetails')
                ->leftJoin('Cashier_TransactionIndex', 'Cashier_TransactionDetails.TransactionIndexId', '=', 'Cashier_TransactionIndex.id')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_ServiceAccounts.id', '=', 'Cashier_TransactionIndex.AccountNumber')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->where('AccountCode', '312-456-00')
                ->where('AccountStatus', 'DISCONNECTED')
                ->whereNotNull('Cashier_TransactionIndex.AccountNumber')
                ->whereRaw("TRY_CAST(Cashier_TransactionDetails.Total AS DECIMAL(10,2))=60")
                ->select('Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.Purok',
                    'Cashier_TransactionIndex.AccountNumber',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Cashier_TransactionIndex.ORDate')
                ->get();
        } else {
            $data = DB::table('Cashier_TransactionDetails')
                ->leftJoin('Cashier_TransactionIndex', 'Cashier_TransactionDetails.TransactionIndexId', '=', 'Cashier_TransactionIndex.id')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_ServiceAccounts.id', '=', 'Cashier_TransactionIndex.AccountNumber')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->where('AccountCode', '312-456-00')
                ->where('AccountStatus', 'DISCONNECTED')
                ->where('Billing_ServiceAccounts.Town', $town)
                ->whereNotNull('Cashier_TransactionIndex.AccountNumber')
                ->whereRaw("TRY_CAST(Cashier_TransactionDetails.Total AS DECIMAL(10,2))=60")
                ->select('Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.Purok',
                    'Cashier_TransactionIndex.AccountNumber',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Cashier_TransactionIndex.ORDate')
                ->get();
        }        

        return view('/bills/disconnected_reports', [
            'data' => $data,
            'towns' => Towns::all()
        ]);
    }

    public function getBillingAdjustmentHistory(Request $request) {
        $id = $request['id'];
        $bill = Bills::find($id);

        if ($bill != null) {
            $adjustments = DB::table('Billing_BillsOriginal')
                ->leftJoin('users', 'Billing_BillsOriginal.AdjustedBy', '=', 'users.id')
                ->whereRaw("Billing_BillsOriginal.AccountNumber='" . $bill->AccountNumber . "' AND Billing_BillsOriginal.ServicePeriod='" . $bill->ServicePeriod . "'")
                ->select('Billing_BillsOriginal.*', 'users.name')
                ->orderByDesc('Billing_BillsOriginal.updated_at')
                ->get();

            $output = "";
            foreach($adjustments as $item) {
                $output .= "<tr>
                                <td>" . date('M Y', strtotime($item->ServicePeriod)) . "</td>
                                <td>" . $item->PresentKwh . "</td>
                                <td>" . $item->PreviousKwh . "</td>
                                <td>" . $item->KwhUsed . "</td>
                                <td>" . number_format($item->NetAmount, 2) . "</td>
                                <td>" . $item->name . "</td>
                                <td>" . date('M d, Y, h:i:s A', strtotime($item->updated_at)) . "</td>
                            </tr>";
            }

            return response()->json($output, 200);
        } else {
            return response()->json([], 200);
        }
    }

    public function unbilledNoMeterReaders(Request $request) {
        $town = $request['Town'];
        $status = $request['Status'];

        if ($town == 'All') {
            if ($status == 'All') {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Bills WHERE AccountNumber IS NOT NULL) AND (MeterReader IS NULL OR LEN(MeterReader) < 2)")
                    ->select('Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.id',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok', 
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'Billing_ServiceAccounts.AccountStatus',
                        'Billing_ServiceAccounts.GroupCode',
                        'Billing_ServiceAccounts.AccountType',
                    )
                    ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                    ->orderBy('AccountStatus')
                    ->get();
            } else {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Bills WHERE AccountNumber IS NOT NULL) 
                        AND (MeterReader IS NULL OR LEN(MeterReader) < 2) AND AccountStatus='" . $status . "'")
                    ->select('Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.id',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok', 
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'Billing_ServiceAccounts.AccountStatus',
                        'Billing_ServiceAccounts.GroupCode',
                        'Billing_ServiceAccounts.AccountType',
                    )
                    ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                    ->get();
            }            
        } else {
            if ($status == 'All') {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Bills WHERE AccountNumber IS NOT NULL) AND Billing_ServiceAccounts.Town='" . $town . "' AND (MeterReader IS NULL OR LEN(MeterReader) < 2)")
                    ->select('Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.id',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok', 
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'Billing_ServiceAccounts.AccountStatus',
                        'Billing_ServiceAccounts.GroupCode',
                        'Billing_ServiceAccounts.AccountType',
                    )
                    ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                    ->orderBy('AccountStatus')
                    ->get();
            } else {
                $data = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Bills WHERE AccountNumber IS NOT NULL) 
                        AND Billing_ServiceAccounts.Town='" . $town . "' AND (MeterReader IS NULL OR LEN(MeterReader) < 2) AND AccountStatus='" . $status . "'")
                    ->select('Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.id',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok', 
                        'CRM_Towns.Town',
                        'CRM_Barangays.Barangay',
                        'Billing_ServiceAccounts.AccountStatus',
                        'Billing_ServiceAccounts.GroupCode',
                        'Billing_ServiceAccounts.AccountType',
                    )
                    ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                    ->get();
            }            
        }

        $status = DB::table('Billing_ServiceAccounts')
            ->whereNotNull('AccountStatus')
            ->select('AccountStatus')
            ->groupBy('AccountStatus')
            ->orderBy('AccountStatus')
            ->get();

        return view('/bills/unbilled_no_meter_readers', [
            'data' => $data,
            'towns' => Towns::all(),
            'status' => $status
        ]);
    }

    public function allBilled(Request $request) {
        $period = $request['ServicePeriod'];
        $town = $request['Town'];
        $accountType = $request['AccountType'];

        if ($town == 'All') {
            if ($accountType=='All') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->whereRaw("ServicePeriod='" . $period . "' AND (ForCancellation IS NULL OR ForCancellation != 'SALES REPORT')")
                    ->select(DB::raw("Billing_ServiceAccounts.id as 'AccountId'"), 'Billing_ServiceAccounts.OldAccountNo', 'Billing_ServiceAccounts.ServiceAccountName', 'Billing_Bills.*')
                    ->orderBy('ConsumerType')
                    ->orderBy('OldAccountNo')
                    ->get();
            } else {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->whereRaw("ServicePeriod='" . $period . "' AND (ForCancellation IS NULL OR ForCancellation != 'SALES REPORT') AND AccountType='" . $accountType . "'")
                    ->select(DB::raw("Billing_ServiceAccounts.id as 'AccountId'"), 'Billing_ServiceAccounts.OldAccountNo', 'Billing_ServiceAccounts.ServiceAccountName', 'Billing_Bills.*')
                    ->orderBy('ConsumerType')
                    ->orderBy('OldAccountNo')
                    ->get();
            }
        } else {
            if ($accountType=='All') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->whereRaw("ServicePeriod='" . $period . "' AND (ForCancellation IS NULL OR ForCancellation != 'SALES REPORT') AND Town='" . $town . "'")
                    ->select(DB::raw("Billing_ServiceAccounts.id as 'AccountId'"), 'Billing_ServiceAccounts.OldAccountNo', 'Billing_ServiceAccounts.ServiceAccountName', 'Billing_Bills.*')
                    ->orderBy('ConsumerType')
                    ->orderBy('OldAccountNo')
                    ->get();
            } else {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->whereRaw("ServicePeriod='" . $period . "' AND (ForCancellation IS NULL OR ForCancellation != 'SALES REPORT') AND Town='" . $town . "' AND AccountType='" . $accountType . "'")
                    ->select(DB::raw("Billing_ServiceAccounts.id as 'AccountId'"), 'Billing_ServiceAccounts.OldAccountNo', 'Billing_ServiceAccounts.ServiceAccountName', 'Billing_Bills.*')
                    ->orderBy('ConsumerType')
                    ->orderBy('OldAccountNo')
                    ->get();
            }
        }

        return view('/bills/all_billed', [
            'data' => $data,
            'towns' => Towns::all(),
            'accountTypes' => DB::table('Billing_ServiceAccounts')->whereNotNull('AccountType')->select('AccountType')->groupBy('AccountType')->orderBy('AccountType')->get(),
        ]);
    }

    public function downloadAllBilled($town, $period, $accountType) {
        $accountType = urldecode($accountType);
        if ($town == 'All') {
            if ($accountType=='All') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->whereRaw("ServicePeriod='" . $period . "' AND (ForCancellation IS NULL OR ForCancellation != 'SALES REPORT')")
                    ->select('Billing_ServiceAccounts.ServiceAccountName', 
                        'Billing_ServiceAccounts.OldAccountNo', 
                        'Billing_Bills.ConsumerType',
                        'Billing_Bills.DemandPresentKwh',
                        'Billing_Bills.KwhUsed',
                        'Billing_Bills.Multiplier',
                        'Billing_Bills.NetAmount',
                        'GenerationSystemCharge',
                        'TransmissionDeliveryChargeKW',
                        'TransmissionDeliveryChargeKWH',
                        'SystemLossCharge',
                        'DistributionDemandCharge',
                        'DistributionSystemCharge',
                        'SupplyRetailCustomerCharge',
                        'SupplySystemCharge',
                        'MeteringRetailCustomerCharge',
                        'MeteringSystemCharge',
                        'RFSC',
                        'LifelineRate',
                        'InterClassCrossSubsidyCharge',
                        'PPARefund',
                        'SeniorCitizenSubsidy',
                        'MissionaryElectrificationCharge',
                        'EnvironmentalCharge',
                        'StrandedContractCosts',
                        'NPCStrandedDebt',
                        'FeedInTariffAllowance',
                        'MissionaryElectrificationREDCI',
                        'GenerationVAT',
                        'TransmissionVAT',
                        'SystemLossVAT',
                        'DistributionVAT',
                        'RealPropertyTax',
                        'FranchiseTax',
                        'BusinessTax',
                        'OtherGenerationRateAdjustment',
                        'OtherTransmissionCostAdjustmentKW',
                        'OtherTransmissionCostAdjustmentKWH',
                        'OtherSystemLossCostAdjustment',
                        'OtherLifelineRateCostAdjustment',
                        'SeniorCitizenDiscountAndSubsidyAdjustment',)
                    ->orderBy('ConsumerType')
                    ->orderBy('OldAccountNo')
                    ->get();
            } else {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->whereRaw("ServicePeriod='" . $period . "' AND (ForCancellation IS NULL OR ForCancellation != 'SALES REPORT') AND AccountType='" . $accountType . "'")
                    ->select('Billing_ServiceAccounts.ServiceAccountName', 
                        'Billing_ServiceAccounts.OldAccountNo', 
                        'Billing_Bills.ConsumerType',
                        'Billing_Bills.DemandPresentKwh',
                        'Billing_Bills.KwhUsed',
                        'Billing_Bills.Multiplier',
                        'Billing_Bills.NetAmount',
                        'GenerationSystemCharge',
                        'TransmissionDeliveryChargeKW',
                        'TransmissionDeliveryChargeKWH',
                        'SystemLossCharge',
                        'DistributionDemandCharge',
                        'DistributionSystemCharge',
                        'SupplyRetailCustomerCharge',
                        'SupplySystemCharge',
                        'MeteringRetailCustomerCharge',
                        'MeteringSystemCharge',
                        'RFSC',
                        'LifelineRate',
                        'InterClassCrossSubsidyCharge',
                        'PPARefund',
                        'SeniorCitizenSubsidy',
                        'MissionaryElectrificationCharge',
                        'EnvironmentalCharge',
                        'StrandedContractCosts',
                        'NPCStrandedDebt',
                        'FeedInTariffAllowance',
                        'MissionaryElectrificationREDCI',
                        'GenerationVAT',
                        'TransmissionVAT',
                        'SystemLossVAT',
                        'DistributionVAT',
                        'RealPropertyTax',
                        'FranchiseTax',
                        'BusinessTax',
                        'OtherGenerationRateAdjustment',
                        'OtherTransmissionCostAdjustmentKW',
                        'OtherTransmissionCostAdjustmentKWH',
                        'OtherSystemLossCostAdjustment',
                        'OtherLifelineRateCostAdjustment',
                        'SeniorCitizenDiscountAndSubsidyAdjustment',)
                    ->orderBy('ConsumerType')
                    ->orderBy('OldAccountNo')
                    ->get();
            }
        } else {
            if ($accountType=='All') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->whereRaw("ServicePeriod='" . $period . "' AND (ForCancellation IS NULL OR ForCancellation != 'SALES REPORT') AND Town='" . $town . "'")
                    ->select('Billing_ServiceAccounts.ServiceAccountName', 
                        'Billing_ServiceAccounts.OldAccountNo', 
                        'Billing_Bills.ConsumerType',
                        'Billing_Bills.DemandPresentKwh',
                        'Billing_Bills.KwhUsed',
                        'Billing_Bills.Multiplier',
                        'Billing_Bills.NetAmount',
                        'GenerationSystemCharge',
                        'TransmissionDeliveryChargeKW',
                        'TransmissionDeliveryChargeKWH',
                        'SystemLossCharge',
                        'DistributionDemandCharge',
                        'DistributionSystemCharge',
                        'SupplyRetailCustomerCharge',
                        'SupplySystemCharge',
                        'MeteringRetailCustomerCharge',
                        'MeteringSystemCharge',
                        'RFSC',
                        'LifelineRate',
                        'InterClassCrossSubsidyCharge',
                        'PPARefund',
                        'SeniorCitizenSubsidy',
                        'MissionaryElectrificationCharge',
                        'EnvironmentalCharge',
                        'StrandedContractCosts',
                        'NPCStrandedDebt',
                        'FeedInTariffAllowance',
                        'MissionaryElectrificationREDCI',
                        'GenerationVAT',
                        'TransmissionVAT',
                        'SystemLossVAT',
                        'DistributionVAT',
                        'RealPropertyTax',
                        'FranchiseTax',
                        'BusinessTax',
                        'OtherGenerationRateAdjustment',
                        'OtherTransmissionCostAdjustmentKW',
                        'OtherTransmissionCostAdjustmentKWH',
                        'OtherSystemLossCostAdjustment',
                        'OtherLifelineRateCostAdjustment',
                        'SeniorCitizenDiscountAndSubsidyAdjustment',)
                    ->orderBy('ConsumerType')
                    ->orderBy('OldAccountNo')
                    ->get();
            } else {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->whereRaw("ServicePeriod='" . $period . "' AND (ForCancellation IS NULL OR ForCancellation != 'SALES REPORT') AND Town='" . $town . "' AND AccountType='" . $accountType . "'")
                    ->select('Billing_ServiceAccounts.ServiceAccountName', 
                        'Billing_ServiceAccounts.OldAccountNo', 
                        'Billing_Bills.ConsumerType',
                        'Billing_Bills.DemandPresentKwh',
                        'Billing_Bills.KwhUsed',
                        'Billing_Bills.Multiplier',
                        'Billing_Bills.NetAmount',
                        'GenerationSystemCharge',
                        'TransmissionDeliveryChargeKW',
                        'TransmissionDeliveryChargeKWH',
                        'SystemLossCharge',
                        'DistributionDemandCharge',
                        'DistributionSystemCharge',
                        'SupplyRetailCustomerCharge',
                        'SupplySystemCharge',
                        'MeteringRetailCustomerCharge',
                        'MeteringSystemCharge',
                        'RFSC',
                        'LifelineRate',
                        'InterClassCrossSubsidyCharge',
                        'PPARefund',
                        'SeniorCitizenSubsidy',
                        'MissionaryElectrificationCharge',
                        'EnvironmentalCharge',
                        'StrandedContractCosts',
                        'NPCStrandedDebt',
                        'FeedInTariffAllowance',
                        'MissionaryElectrificationREDCI',
                        'GenerationVAT',
                        'TransmissionVAT',
                        'SystemLossVAT',
                        'DistributionVAT',
                        'RealPropertyTax',
                        'FranchiseTax',
                        'BusinessTax',
                        'OtherGenerationRateAdjustment',
                        'OtherTransmissionCostAdjustmentKW',
                        'OtherTransmissionCostAdjustmentKWH',
                        'OtherSystemLossCostAdjustment',
                        'OtherLifelineRateCostAdjustment',
                        'SeniorCitizenDiscountAndSubsidyAdjustment',)
                    ->orderBy('ConsumerType')
                    ->orderBy('OldAccountNo')
                    ->get();
            }
        }

        $headers = [
            'Consumer Name',
            'Account Number',
            'Account Type',
            'kW Demand',
            'kWh Energy',
            'Multiplier',
            'Net Amount',
            'Generation System',
            'Transmission Delivery KW',
            'Transmission Delivery KWH',
            'System Loss',
            'Distribution Demand',
            'Distribution System',
            'Supply Retail Customer',
            'Supply System',
            'Metering Retail Customer',
            'Metering System',
            'RFSC',
            'Lifeline',
            'ICCS',
            'PPA Refund',
            'Senior Citizen',
            'Missionary',
            'Environmental',
            'SCC',
            'NPC',
            'FIT All.',
            'REDCI',
            'Generation VAT',
            'Transmission VAT',
            'SystemLoss VAT',
            'Distribution VAT',
            'RPT',
            'Franchise Tax',
            'Business Tax',
            'Other Generation Rate Adjustment',
            'Other Transmission Adjustment KW',
            'Other Transmission Adjustment KWH',
            'Other System Loss Adjustment',
            'Other Lifeline Rate Adjustment',
            'SC Discount & Subsidy Adjustment',
        ];

        $styles = [
            // Style the first row as bold text.
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            2 => [
                'alignment' => ['horizontal' => 'center'],
            ],
            4 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            8 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
        
        $export = new DynamicExports($data->toArray(), 
                                    $period, 
                                    $town,
                                    $headers, 
                                    [],
                                    'A8',
                                    $styles,
                                    'BILLED CONSUMERS REPORT AS OF ' . date('F Y', strtotime($period))
                                );

        return Excel::download($export, 'Billed-Consumers-Report.xlsx');
    }

    public function newlyEnergizedConsumers(Request $request) {
        $period = $request['ServicePeriod'];
        $town = $request['Town'];

        if ($town == 'All') {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereRaw("ServicePeriod='" . $period . "' AND AccountNumber IN 
                    (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod <= '" . $period . "' GROUP BY AccountNumber HAVING COUNT(AccountNumber)=1)")
                ->select(DB::raw("Billing_ServiceAccounts.id as 'AccountId'"), 'Billing_ServiceAccounts.OldAccountNo', 'Billing_ServiceAccounts.ServiceAccountName', 'Billing_Bills.*')
                ->orderBy('ConsumerType')
                ->orderBy('OldAccountNo')
                ->get();
        } else {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereRaw("ServicePeriod='" . $period . "' AND Town='" . $town . "' AND AccountNumber IN 
                    (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod <= '" . $period . "' GROUP BY AccountNumber HAVING COUNT(AccountNumber)=1)")
                ->select(DB::raw("Billing_ServiceAccounts.id as 'AccountId'"), 'Billing_ServiceAccounts.OldAccountNo', 'Billing_ServiceAccounts.ServiceAccountName', 'Billing_Bills.*')
                ->orderBy('ConsumerType')
                ->orderBy('OldAccountNo')
                ->get();
        }

        return view('/bills/newly_energized', [
            'towns' => Towns::all(),
            'data' => $data,
        ]);
    }

    public function downloadNewlyEnergized($town, $period) {
        if ($town == 'All') {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereRaw("ServicePeriod='" . $period . "' AND AccountNumber IN 
                    (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod <= '" . $period . "' GROUP BY AccountNumber HAVING COUNT(AccountNumber)=1)")
                ->select('Billing_ServiceAccounts.ServiceAccountName', 
                    'Billing_ServiceAccounts.OldAccountNo', 
                    'Billing_Bills.ConsumerType',
                    'Billing_Bills.DemandPresentKwh',
                    'Billing_Bills.KwhUsed',
                    'Billing_Bills.Multiplier',
                    'Billing_Bills.NetAmount',
                    'GenerationSystemCharge',
                    'TransmissionDeliveryChargeKW',
                    'TransmissionDeliveryChargeKWH',
                    'SystemLossCharge',
                    'DistributionDemandCharge',
                    'DistributionSystemCharge',
                    'SupplyRetailCustomerCharge',
                    'SupplySystemCharge',
                    'MeteringRetailCustomerCharge',
                    'MeteringSystemCharge',
                    'RFSC',
                    'LifelineRate',
                    'InterClassCrossSubsidyCharge',
                    'PPARefund',
                    'SeniorCitizenSubsidy',
                    'MissionaryElectrificationCharge',
                    'EnvironmentalCharge',
                    'StrandedContractCosts',
                    'NPCStrandedDebt',
                    'FeedInTariffAllowance',
                    'MissionaryElectrificationREDCI',
                    'GenerationVAT',
                    'TransmissionVAT',
                    'SystemLossVAT',
                    'DistributionVAT',
                    'RealPropertyTax',
                    'FranchiseTax',
                    'BusinessTax',
                    'OtherGenerationRateAdjustment',
                    'OtherTransmissionCostAdjustmentKW',
                    'OtherTransmissionCostAdjustmentKWH',
                    'OtherSystemLossCostAdjustment',
                    'OtherLifelineRateCostAdjustment',
                    'SeniorCitizenDiscountAndSubsidyAdjustment',)
                ->orderBy('ConsumerType')
                ->orderBy('OldAccountNo')
                ->get();
        } else {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->whereRaw("ServicePeriod='" . $period . "' AND Town='" . $town . "' AND AccountNumber IN 
                    (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod <= '" . $period . "' GROUP BY AccountNumber HAVING COUNT(AccountNumber)=1)")
                ->select('Billing_ServiceAccounts.ServiceAccountName', 
                    'Billing_ServiceAccounts.OldAccountNo', 
                    'Billing_Bills.ConsumerType',
                    'Billing_Bills.DemandPresentKwh',
                    'Billing_Bills.KwhUsed',
                    'Billing_Bills.Multiplier',
                    'Billing_Bills.NetAmount',
                    'GenerationSystemCharge',
                    'TransmissionDeliveryChargeKW',
                    'TransmissionDeliveryChargeKWH',
                    'SystemLossCharge',
                    'DistributionDemandCharge',
                    'DistributionSystemCharge',
                    'SupplyRetailCustomerCharge',
                    'SupplySystemCharge',
                    'MeteringRetailCustomerCharge',
                    'MeteringSystemCharge',
                    'RFSC',
                    'LifelineRate',
                    'InterClassCrossSubsidyCharge',
                    'PPARefund',
                    'SeniorCitizenSubsidy',
                    'MissionaryElectrificationCharge',
                    'EnvironmentalCharge',
                    'StrandedContractCosts',
                    'NPCStrandedDebt',
                    'FeedInTariffAllowance',
                    'MissionaryElectrificationREDCI',
                    'GenerationVAT',
                    'TransmissionVAT',
                    'SystemLossVAT',
                    'DistributionVAT',
                    'RealPropertyTax',
                    'FranchiseTax',
                    'BusinessTax',
                    'OtherGenerationRateAdjustment',
                    'OtherTransmissionCostAdjustmentKW',
                    'OtherTransmissionCostAdjustmentKWH',
                    'OtherSystemLossCostAdjustment',
                    'OtherLifelineRateCostAdjustment',
                    'SeniorCitizenDiscountAndSubsidyAdjustment',)
                ->orderBy('ConsumerType')
                ->orderBy('OldAccountNo')
                ->get();
        }

        $headers = [
            'Consumer Name',
            'Account Number',
            'Account Type',
            'kW Demand',
            'kWh Energy',
            'Multiplier',
            'Net Amount',
            'Generation System',
            'Transmission Delivery KW',
            'Transmission Delivery KWH',
            'System Loss',
            'Distribution Demand',
            'Distribution System',
            'Supply Retail Customer',
            'Supply System',
            'Metering Retail Customer',
            'Metering System',
            'RFSC',
            'Lifeline',
            'ICCS',
            'PPA Refund',
            'Senior Citizen',
            'Missionary',
            'Environmental',
            'SCC',
            'NPC',
            'FIT All.',
            'REDCI',
            'Generation VAT',
            'Transmission VAT',
            'SystemLoss VAT',
            'Distribution VAT',
            'RPT',
            'Franchise Tax',
            'Business Tax',
            'Other Generation Rate Adjustment',
            'Other Transmission Adjustment KW',
            'Other Transmission Adjustment KWH',
            'Other System Loss Adjustment',
            'Other Lifeline Rate Adjustment',
            'SC Discount & Subsidy Adjustment',
        ];

        $styles = [
            // Style the first row as bold text.
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            2 => [
                'alignment' => ['horizontal' => 'center'],
            ],
            4 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            8 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
        
        $export = new DynamicExports($data->toArray(), 
                                    $period, 
                                    $town,
                                    $headers, 
                                    [],
                                    'A8',
                                    $styles,
                                    'NEWLY ENERGIZED CONSUMERS AS OF ' . date('F Y', strtotime($period))
                                );

        return Excel::download($export, 'Newly-Energized-Consumers-Report.xlsx');
    }

    public function outstandingReportMreader(Request $request) {
        $asOf = $request['AsOf'];
        $status = $request['Status'];
        $meterReader = $request['MeterReader'];

        $currentRate = Rates::orderByDesc('ServicePeriod')->first();

        $meterReaders = User::role('Meter Reader Inhouse')->orderBy('name')->get();

        if ($asOf != null) {
            if ($status == 'All') {
                if ($meterReader == 'All') {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'users.name',
                            'AccountStatus',
                            'AccountType',
                            'Billing_Bills.*')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                } else {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' AND MeterReader='" . $meterReader . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'users.name',
                            'AccountStatus',
                            'AccountType',
                            'Billing_Bills.*')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                }
            } else {
                if ($meterReader == 'All') {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' 
                                AND Billing_ServiceAccounts.AccountStatus='" . $status . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'users.name',
                            'AccountStatus',
                            'AccountType',
                            'Billing_Bills.*')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                } else {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' AND MeterReader='" . $meterReader . "' 
                                AND Billing_ServiceAccounts.AccountStatus='" . $status . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'users.name',
                            'AccountStatus',
                            'AccountType',
                            'Billing_Bills.*')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                }
            }            
        } else {
            $data = [];
        }
        
        return view('/bills/outstanding_report_mreader', [
            'meterReaders' => $meterReaders,
            'data' => $data,
        ]);
    }

    public function downloadOutstandingReportMreader($asOf, $meterReader, $status) {
        $currentRate = Rates::orderByDesc('ServicePeriod')->first();

        if ($asOf != null) {
            if ($status == 'All') {
                if ($meterReader == 'All') {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'ServicePeriod',
                            'NetAmount',
                            'AccountStatus',
                            'GenerationSystemCharge',
                            'TransmissionDeliveryChargeKW',
                            'TransmissionDeliveryChargeKWH',
                            'SystemLossCharge',
                            'DistributionDemandCharge',
                            'DistributionSystemCharge',
                            'SupplyRetailCustomerCharge',
                            'SupplySystemCharge',
                            'MeteringRetailCustomerCharge',
                            'MeteringSystemCharge',
                            'RFSC',
                            'LifelineRate',
                            'InterClassCrossSubsidyCharge',
                            'PPARefund',
                            'SeniorCitizenSubsidy',
                            'MissionaryElectrificationCharge',
                            'EnvironmentalCharge',
                            'StrandedContractCosts',
                            'NPCStrandedDebt',
                            'FeedInTariffAllowance',
                            'MissionaryElectrificationREDCI',
                            'OtherGenerationRateAdjustment',
                            'OtherTransmissionCostAdjustmentKW',
                            'OtherTransmissionCostAdjustmentKWH',
                            'OtherSystemLossCostAdjustment',
                            'OtherLifelineRateCostAdjustment',
                            'SeniorCitizenDiscountAndSubsidyAdjustment',
                            'RealPropertyTax',
                            'GenerationVAT',
                            'TransmissionVAT',
                            'SystemLossVAT',
                            'DistributionVAT')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                } else {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' AND MeterReader='" . $meterReader . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'ServicePeriod',
                            'NetAmount',
                            'AccountStatus',                            
                            'GenerationSystemCharge',
                            'TransmissionDeliveryChargeKW',
                            'TransmissionDeliveryChargeKWH',
                            'SystemLossCharge',
                            'DistributionDemandCharge',
                            'DistributionSystemCharge',
                            'SupplyRetailCustomerCharge',
                            'SupplySystemCharge',
                            'MeteringRetailCustomerCharge',
                            'MeteringSystemCharge',
                            'RFSC',
                            'LifelineRate',
                            'InterClassCrossSubsidyCharge',
                            'PPARefund',
                            'SeniorCitizenSubsidy',
                            'MissionaryElectrificationCharge',
                            'EnvironmentalCharge',
                            'StrandedContractCosts',
                            'NPCStrandedDebt',
                            'FeedInTariffAllowance',
                            'MissionaryElectrificationREDCI',
                            'OtherGenerationRateAdjustment',
                            'OtherTransmissionCostAdjustmentKW',
                            'OtherTransmissionCostAdjustmentKWH',
                            'OtherSystemLossCostAdjustment',
                            'OtherLifelineRateCostAdjustment',
                            'SeniorCitizenDiscountAndSubsidyAdjustment',
                            'RealPropertyTax',
                            'GenerationVAT',
                            'TransmissionVAT',
                            'SystemLossVAT',
                            'DistributionVAT')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                }
            } else {
                if ($meterReader == 'All') {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' 
                                AND Billing_ServiceAccounts.AccountStatus='" . $status . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'ServicePeriod',
                            'NetAmount',
                            'AccountStatus',
                            'GenerationSystemCharge',
                            'TransmissionDeliveryChargeKW',
                            'TransmissionDeliveryChargeKWH',
                            'SystemLossCharge',
                            'DistributionDemandCharge',
                            'DistributionSystemCharge',
                            'SupplyRetailCustomerCharge',
                            'SupplySystemCharge',
                            'MeteringRetailCustomerCharge',
                            'MeteringSystemCharge',
                            'RFSC',
                            'LifelineRate',
                            'InterClassCrossSubsidyCharge',
                            'PPARefund',
                            'SeniorCitizenSubsidy',
                            'MissionaryElectrificationCharge',
                            'EnvironmentalCharge',
                            'StrandedContractCosts',
                            'NPCStrandedDebt',
                            'FeedInTariffAllowance',
                            'MissionaryElectrificationREDCI',
                            'OtherGenerationRateAdjustment',
                            'OtherTransmissionCostAdjustmentKW',
                            'OtherTransmissionCostAdjustmentKWH',
                            'OtherSystemLossCostAdjustment',
                            'OtherLifelineRateCostAdjustment',
                            'SeniorCitizenDiscountAndSubsidyAdjustment',
                            'RealPropertyTax',
                            'GenerationVAT',
                            'TransmissionVAT',
                            'SystemLossVAT',
                            'DistributionVAT')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                } else {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' AND MeterReader='" . $meterReader . "' 
                                AND Billing_ServiceAccounts.AccountStatus='" . $status . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'ServicePeriod',
                            'NetAmount',
                            'AccountStatus',
                            'GenerationSystemCharge',
                            'TransmissionDeliveryChargeKW',
                            'TransmissionDeliveryChargeKWH',
                            'SystemLossCharge',
                            'DistributionDemandCharge',
                            'DistributionSystemCharge',
                            'SupplyRetailCustomerCharge',
                            'SupplySystemCharge',
                            'MeteringRetailCustomerCharge',
                            'MeteringSystemCharge',
                            'RFSC',
                            'LifelineRate',
                            'InterClassCrossSubsidyCharge',
                            'PPARefund',
                            'SeniorCitizenSubsidy',
                            'MissionaryElectrificationCharge',
                            'EnvironmentalCharge',
                            'StrandedContractCosts',
                            'NPCStrandedDebt',
                            'FeedInTariffAllowance',
                            'MissionaryElectrificationREDCI',
                            'OtherGenerationRateAdjustment',
                            'OtherTransmissionCostAdjustmentKW',
                            'OtherTransmissionCostAdjustmentKWH',
                            'OtherSystemLossCostAdjustment',
                            'OtherLifelineRateCostAdjustment',
                            'SeniorCitizenDiscountAndSubsidyAdjustment',
                            'RealPropertyTax',
                            'GenerationVAT',
                            'TransmissionVAT',
                            'SystemLossVAT',
                            'DistributionVAT')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                }
            }            
        } else {
            $data = [];
        }

        $headers = [
            'Account Number',
            'Consumer Name',
            'Address',
            'Billing Month',
            'Net Amount',
            'Status',
            'Generation',
            'Transmission (KW)',
            'Transmission (KWH)',
            'Systems Loss',
            'Distribution Demand',
            'Distribution System',
            'Supply Retail',
            'Supply System',
            'Metering Retail',
            'Metering System',
            'RFSC',
            'Lifeline Rate',
            'ICCS',
            'PPA Refund',
            'Senior Citizen',
            'Missionary Electrification',
            'Environmental',
            'Stranded Contract Costs',
            'NPC Stranded Debt',
            'FIT All',
            'REDCI',
            'OGA',
            'OTCA (KW)',
            'OTCA (KWH)',
            'OSLA',
            'OLRA',
            'SCSA',
            'RPT',
            'Generation VAT',
            'Transmission VAT',
            'System Loss VAT',
            'Distribution VAT'
        ];

        $styles = [
            // Style the first row as bold text.
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            2 => [
                'alignment' => ['horizontal' => 'center'],
            ],
            4 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            7 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
        
        $export = new DynamicExports($data->toArray(), 
                                    null, 
                                    '-',
                                    $headers, 
                                    [],
                                    'A8',
                                    $styles,
                                    'OUTSTANDING REPORT PER METER READER AS OF ' . date('F d, Y', strtotime($asOf))
                                );

        return Excel::download($export, 'Outstanding-Report-Meter-Reader.xlsx');
    }

    public function outstandingReportBAPA(Request $request) {
        $asOf = $request['AsOf'];
        $status = $request['Status'];
        $bapa = urldecode($request['BAPA']);

        $currentRate = Rates::orderByDesc('ServicePeriod')->first();

        $bapas = DB::table('Billing_ServiceAccounts')
            ->whereRaw("Organization='BAPA'")
            ->select('OrganizationParentAccount')
            ->groupBy('OrganizationParentAccount')
            ->orderBy('OrganizationParentAccount')
            ->get();

        if ($asOf != null) {
            if ($status == 'All') {
                if ($bapa == 'All') {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'OrganizationParentAccount',
                            'AccountStatus',
                            'Billing_Bills.*')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                } else {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' AND OrganizationParentAccount='" . $bapa . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'OrganizationParentAccount',
                            'AccountStatus',
                            'Billing_Bills.*')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                }
            } else {
                if ($bapa == 'All') {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' 
                                AND Billing_ServiceAccounts.AccountStatus='" . $status . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'OrganizationParentAccount',
                            'AccountStatus',
                            'Billing_Bills.*')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                } else {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' AND OrganizationParentAccount='" . $bapa . "' 
                                AND Billing_ServiceAccounts.AccountStatus='" . $status . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'OrganizationParentAccount',
                            'AccountStatus',
                            'Billing_Bills.*')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                }
            }            
        } else {
            $data = [];
        }
        
        return view('/bills/outstanding_report_bapa', [
            'bapas' => $bapas,
            'data' => $data,
        ]);
    }

    public function downloadOutstandingReportBAPA($asOf, $bapa, $status) {
        $currentRate = Rates::orderByDesc('ServicePeriod')->first();
        $bapa = urldecode($bapa);

        if ($asOf != null) {
            if ($status == 'All') {
                if ($bapa == 'All') {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'ServicePeriod',
                            'NetAmount',
                            'AccountStatus',
                            'GenerationSystemCharge',
                            'TransmissionDeliveryChargeKW',
                            'TransmissionDeliveryChargeKWH',
                            'SystemLossCharge',
                            'DistributionDemandCharge',
                            'DistributionSystemCharge',
                            'SupplyRetailCustomerCharge',
                            'SupplySystemCharge',
                            'MeteringRetailCustomerCharge',
                            'MeteringSystemCharge',
                            'RFSC',
                            'LifelineRate',
                            'InterClassCrossSubsidyCharge',
                            'PPARefund',
                            'SeniorCitizenSubsidy',
                            'MissionaryElectrificationCharge',
                            'EnvironmentalCharge',
                            'StrandedContractCosts',
                            'NPCStrandedDebt',
                            'FeedInTariffAllowance',
                            'MissionaryElectrificationREDCI',
                            'OtherGenerationRateAdjustment',
                            'OtherTransmissionCostAdjustmentKW',
                            'OtherTransmissionCostAdjustmentKWH',
                            'OtherSystemLossCostAdjustment',
                            'OtherLifelineRateCostAdjustment',
                            'SeniorCitizenDiscountAndSubsidyAdjustment',
                            'RealPropertyTax',
                            'GenerationVAT',
                            'TransmissionVAT',
                            'SystemLossVAT',
                            'DistributionVAT')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                } else {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' AND OrganizationParentAccount='" . $bapa . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'ServicePeriod',
                            'NetAmount',
                            'AccountStatus',                            
                            'GenerationSystemCharge',
                            'TransmissionDeliveryChargeKW',
                            'TransmissionDeliveryChargeKWH',
                            'SystemLossCharge',
                            'DistributionDemandCharge',
                            'DistributionSystemCharge',
                            'SupplyRetailCustomerCharge',
                            'SupplySystemCharge',
                            'MeteringRetailCustomerCharge',
                            'MeteringSystemCharge',
                            'RFSC',
                            'LifelineRate',
                            'InterClassCrossSubsidyCharge',
                            'PPARefund',
                            'SeniorCitizenSubsidy',
                            'MissionaryElectrificationCharge',
                            'EnvironmentalCharge',
                            'StrandedContractCosts',
                            'NPCStrandedDebt',
                            'FeedInTariffAllowance',
                            'MissionaryElectrificationREDCI',
                            'OtherGenerationRateAdjustment',
                            'OtherTransmissionCostAdjustmentKW',
                            'OtherTransmissionCostAdjustmentKWH',
                            'OtherSystemLossCostAdjustment',
                            'OtherLifelineRateCostAdjustment',
                            'SeniorCitizenDiscountAndSubsidyAdjustment',
                            'RealPropertyTax',
                            'GenerationVAT',
                            'TransmissionVAT',
                            'SystemLossVAT',
                            'DistributionVAT')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                }
            } else {
                if ($bapa == 'All') {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' 
                                AND Billing_ServiceAccounts.AccountStatus='" . $status . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'ServicePeriod',
                            'NetAmount',
                            'AccountStatus',
                            'GenerationSystemCharge',
                            'TransmissionDeliveryChargeKW',
                            'TransmissionDeliveryChargeKWH',
                            'SystemLossCharge',
                            'DistributionDemandCharge',
                            'DistributionSystemCharge',
                            'SupplyRetailCustomerCharge',
                            'SupplySystemCharge',
                            'MeteringRetailCustomerCharge',
                            'MeteringSystemCharge',
                            'RFSC',
                            'LifelineRate',
                            'InterClassCrossSubsidyCharge',
                            'PPARefund',
                            'SeniorCitizenSubsidy',
                            'MissionaryElectrificationCharge',
                            'EnvironmentalCharge',
                            'StrandedContractCosts',
                            'NPCStrandedDebt',
                            'FeedInTariffAllowance',
                            'MissionaryElectrificationREDCI',
                            'OtherGenerationRateAdjustment',
                            'OtherTransmissionCostAdjustmentKW',
                            'OtherTransmissionCostAdjustmentKWH',
                            'OtherSystemLossCostAdjustment',
                            'OtherLifelineRateCostAdjustment',
                            'SeniorCitizenDiscountAndSubsidyAdjustment',
                            'RealPropertyTax',
                            'GenerationVAT',
                            'TransmissionVAT',
                            'SystemLossVAT',
                            'DistributionVAT')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                } else {
                    $data = DB::table('Billing_Bills')
                        ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                        ->whereRaw("Billing_Bills.AccountNumber NOT IN 
                                (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_Bills.AccountNumber AND 
                                (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod AND ORDate <='" . $asOf . "') AND Billing_Bills.created_at < '" . $asOf . "' AND OrganizationParentAccount='" . $bapa . "' 
                                AND Billing_ServiceAccounts.AccountStatus='" . $status . "'")
                        ->select('OldAccountNo',
                            'ServiceAccountName', 
                            'Purok', 
                            'ServicePeriod',
                            'NetAmount',
                            'AccountStatus',
                            'GenerationSystemCharge',
                            'TransmissionDeliveryChargeKW',
                            'TransmissionDeliveryChargeKWH',
                            'SystemLossCharge',
                            'DistributionDemandCharge',
                            'DistributionSystemCharge',
                            'SupplyRetailCustomerCharge',
                            'SupplySystemCharge',
                            'MeteringRetailCustomerCharge',
                            'MeteringSystemCharge',
                            'RFSC',
                            'LifelineRate',
                            'InterClassCrossSubsidyCharge',
                            'PPARefund',
                            'SeniorCitizenSubsidy',
                            'MissionaryElectrificationCharge',
                            'EnvironmentalCharge',
                            'StrandedContractCosts',
                            'NPCStrandedDebt',
                            'FeedInTariffAllowance',
                            'MissionaryElectrificationREDCI',
                            'OtherGenerationRateAdjustment',
                            'OtherTransmissionCostAdjustmentKW',
                            'OtherTransmissionCostAdjustmentKWH',
                            'OtherSystemLossCostAdjustment',
                            'OtherLifelineRateCostAdjustment',
                            'SeniorCitizenDiscountAndSubsidyAdjustment',
                            'RealPropertyTax',
                            'GenerationVAT',
                            'TransmissionVAT',
                            'SystemLossVAT',
                            'DistributionVAT')
                        ->orderBy('ServicePeriod')
                        ->orderBy('Billing_ServiceAccounts.OldAccountNo')
                        ->get();
                }
            }            
        } else {
            $data = [];
        }

        $headers = [
            'Account Number',
            'Consumer Name',
            'Address',
            'Billing Month',
            'Net Amount',
            'Status',
            'Generation',
            'Transmission (KW)',
            'Transmission (KWH)',
            'Systems Loss',
            'Distribution Demand',
            'Distribution System',
            'Supply Retail',
            'Supply System',
            'Metering Retail',
            'Metering System',
            'RFSC',
            'Lifeline Rate',
            'ICCS',
            'PPA Refund',
            'Senior Citizen',
            'Missionary Electrification',
            'Environmental',
            'Stranded Contract Costs',
            'NPC Stranded Debt',
            'FIT All',
            'REDCI',
            'OGA',
            'OTCA (KW)',
            'OTCA (KWH)',
            'OSLA',
            'OLRA',
            'SCSA',
            'RPT',
            'Generation VAT',
            'Transmission VAT',
            'System Loss VAT',
            'Distribution VAT'
        ];

        $styles = [
            // Style the first row as bold text.
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            2 => [
                'alignment' => ['horizontal' => 'center'],
            ],
            4 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            7 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
        
        $export = new DynamicExports($data->toArray(), 
                                    null, 
                                    $bapa,
                                    $headers, 
                                    [],
                                    'A8',
                                    $styles,
                                    'OUTSTANDING REPORT PER BAPA AS OF ' . date('F d, Y', strtotime($asOf))
                                );

        return Excel::download($export, 'Outstanding-Report-BAPA.xlsx');
    }

    public function cancelledBills(Request $request) {
        $from = $request['From'];
        $to = $request['To'];
        $area = $request['Area'];

        if ($area == 'All') {
            $bills = DB::table('Billing_BillsOriginal')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('users', 'Billing_BillsOriginal.CancelApprovedBy', '=', 'users.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("(TRY_CAST(Billing_BillsOriginal.created_at AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND ForCancellation='Cancelled'")
                ->select('Billing_ServiceAccounts.OldAccountNo', 
                    'Billing_ServiceAccounts.id',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_BillsOriginal.*',
                    DB::raw("(SELECT name FROM users WHERE id=Billing_BillsOriginal.CancelRequestedBy) AS Requested"),
                    'users.name')
                ->orderBy('Billing_BillsOriginal.created_at')
                ->get();
        } else {
            $bills = DB::table('Billing_BillsOriginal')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('users', 'Billing_BillsOriginal.CancelApprovedBy', '=', 'users.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("(TRY_CAST(Billing_BillsOriginal.created_at AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND ForCancellation='Cancelled'")
                ->whereRaw("Billing_ServiceAccounts.Town='" . $area . "'")
                ->select('Billing_ServiceAccounts.OldAccountNo', 
                    'Billing_ServiceAccounts.id',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_BillsOriginal.*',
                    DB::raw("(SELECT name FROM users WHERE id=Billing_BillsOriginal.CancelRequestedBy) AS Requested"),
                    'users.name')
                ->orderBy('Billing_BillsOriginal.created_at')
                ->get();
        }

        return view('/bills/cancelled_bills', [
            'bills' => $bills,
            'towns' => Towns::all()
        ]);
    }

    public function printCancelledBills($from, $to, $area) {
        if ($area == 'All') {
            $bills = DB::table('Billing_BillsOriginal')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('users', 'Billing_BillsOriginal.CancelApprovedBy', '=', 'users.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("(TRY_CAST(Billing_BillsOriginal.created_at AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND ForCancellation='Cancelled'")
                ->select('Billing_ServiceAccounts.OldAccountNo', 
                    'Billing_ServiceAccounts.id',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_BillsOriginal.*',
                    DB::raw("(SELECT name FROM users WHERE id=Billing_BillsOriginal.CancelRequestedBy) AS Requested"),
                    'users.name')
                ->orderBy('Billing_BillsOriginal.created_at')
                ->get();
        } else {
            $bills = DB::table('Billing_BillsOriginal')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('users', 'Billing_BillsOriginal.CancelApprovedBy', '=', 'users.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("(TRY_CAST(Billing_BillsOriginal.created_at AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND ForCancellation='Cancelled'")
                ->whereRaw("Billing_ServiceAccounts.Town='" . $area . "'")
                ->select('Billing_ServiceAccounts.OldAccountNo', 
                    'Billing_ServiceAccounts.id',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_BillsOriginal.*',
                    DB::raw("(SELECT name FROM users WHERE id=Billing_BillsOriginal.CancelRequestedBy) AS Requested"),
                    'users.name')
                ->orderBy('Billing_BillsOriginal.created_at')
                ->get();
        }

        return view('/bills/print_cancelled_bills', [
            'bills' => $bills,
            'area' => $area == 'All' ? 'All' : Towns::find($area)->Town,
            'from' => $from,
            'to' => $to
        ]);
    }

    public function billAdjustmentsApproval() {
        $bills = DB::table('Billing_BillsOriginal')
            ->leftJoin('Billing_ServiceAccounts', function($join) {
                $join->on('Billing_BillsOriginal.AccountNumber', '=', 'Billing_ServiceAccounts.id');
            })
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('users', 'Billing_BillsOriginal.AdjustmentRequestedBy', '=', 'users.id')
            ->whereRaw("AdjustmentStatus='PENDING ADJUSTMENT APPROVAL'")
            ->select('Billing_BillsOriginal.*',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.OldAccountNo',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'users.name',
            )
            ->orderByDesc('Billing_BillsOriginal.updated_at')
            ->get();

        return view('/bills/bill_adjustment_approvals', [
            'bills' => $bills,
        ]);
    }

    public function billAdjustmentsApprovalView($origId) {
        $billsOriginal = BillsOriginal::find($origId);

        if ($billsOriginal != null) {
            $account = ServiceAccounts::find($billsOriginal->AccountNumber);

            $bill = Bills::where('AccountNumber', $account->id)
                ->where('ServicePeriod', $billsOriginal->ServicePeriod)
                ->first();
        } else {
            $account = null;
            $bill = null;
        }

        return view('/bills/bill_adjustment_approvals_view', [
            'bill' => $bill,
            'account' => $account,
            'billsOriginal' => $billsOriginal,
        ]);
    }

    public function billAdjustmentsApprove($origId) {
        $billsOriginal = BillsOriginal::find($origId);
        $billNo = $billsOriginal->BillNumber;

        if ($billsOriginal != null) {

            $account = ServiceAccounts::find($billsOriginal->AccountNumber);

            $bill = Bills::where('AccountNumber', $billsOriginal->AccountNumber)
                ->where('ServicePeriod', $billsOriginal->ServicePeriod)
                ->orderByDesc('created_at')
                ->first();

            if ($bill != null) {
                // ADD BILL TO BILLS ORIGNAL
                $billId = $bill->id;
                $advMatDeposit = $bill->AdvancedMaterialDeposit;
                $customerDeposit = $bill->CustomerDeposit;
                $bill = $bill->toArray();
                $bill['id'] = IDGenerator::generateIDandRandString();
                $bill['AdjustmentStatus'] = 'ADJUSTMENT APPROVED';
                $bill['AdjustmentApprovedBy'] = Auth::id();
                $bill['DateAdjusted'] = date('Y-m-d H:i:s');
                BillsOriginal::create($bill);
                Bills::where('id', $billId)->delete();
                //E ULI FIRST ANG DEPOSITS SA ACCOUNT
                $account->AdvancedMaterialDeposit = floatval($account->AdvancedMaterialDeposit) - floatval($advMatDeposit);
                $account->CustomerDeposit = floatval($account->CustomerDeposit) - floatval($customerDeposit);
                $account->save();

                // INSERT BILLS ORIGINAL DATA TO BILLS
                $advMatDeposit = $billsOriginal->AdvancedMaterialDeposit;
                $customerDeposit = $billsOriginal->CustomerDeposit;
                $billsOriginal = $billsOriginal->toArray();
                $billsOriginal['id'] = IDGenerator::generateIDandRandString();
                $billsOriginal['AdjustmentStatus'] = 'ADJUSTMENT APPROVED';
                $billsOriginal['AdjustmentApprovedBy'] = Auth::id();
                $billsOriginal['DateAdjusted'] = date('Y-m-d H:i:s');
                Bills::create($billsOriginal);
                BillsOriginal::where('id', $origId)->delete();
                //E KALTAS ANG DEPOSITS SA ACCOUNT
                $account->AdvancedMaterialDeposit = floatval($account->AdvancedMaterialDeposit) + floatval($advMatDeposit);
                $account->CustomerDeposit = floatval($account->CustomerDeposit) + floatval($customerDeposit);
                $account->save();
            } else {
                // INSERT BILLS ORIGINAL DATA TO BILLS
                $billsOriginal = $billsOriginal->toArray();
                $billsOriginal['AdjustmentStatus'] = 'ADJUSTMENT APPROVED';
                $billsOriginal['AdjustmentApprovedBy'] = Auth::id();
                $billsOriginal['DateAdjusted'] = date('Y-m-d H:i:s');
                Bills::create($billsOriginal);
            }
        
        } else {
            $bill = null;
        }

        Flash::success('Bill adjustment approved for bill number ' . $billNo . '.');

        return redirect(route('bills.bill-adjustments-approval'));
    }

    public function billAdjustmentsReject($origId) {
        $billsOriginal = BillsOriginal::find($origId);
        $billsOriginal->AdjustmentStatus = 'ADJUSTMENT REJECTED';
        $billsOriginal->AdjustmentApprovedBy = Auth::id();
        $billsOriginal->DateAdjusted = date('Y-m-d H:i:s');
        $billsOriginal->save();

        $bill = Bills::where('AccountNumber', $billsOriginal->AccountNumber)
            ->where('ServicePeriod', $billsOriginal->ServicePeriod)
            ->update(['AdjustmentStatus' => 'ADJUSTMENT REJECTED', 'AdjustmentApprovedBy' => Auth::id(), 'DateAdjusted' => date('Y-m-d H:i:s')]);

        Flash::error('Bill adjustment rejected for bill number ' . $billsOriginal->BillNumber . '.');

        return redirect(route('bills.bill-adjustments-approval'));
    }

    public function allowSkip(Request $request) {
        $id = $request['id'];
        $skipStatus = $request['SkipStatus'];

        Bills::where('id', $id)
            ->update(['Item3' => $skipStatus]);

        return response()->json('ok', 200);
    }

    public function requestWaiveSurcharges(Request $request) {
        $id = $request['id'];

        Bills::where('id', $id)
            ->update(['SurchargeWaived' => 'PENDING APPROVAL', 'SurchargeWaiveRequestedBy' => Auth::id(), 'SurchargeWaiveRequestDate' => date('Y-m-d H:i:s')]);

        return response()->json('ok', 200);
    }

    public function getBillAjax(Request $request) {
        $id = $request['id'];

        return response()->json(Bills::find($id), 200);
    }

    public function get2307FivePercentAjax(Request $request) {
        $id = $request['id'];

        $bill = Bills::find($id);

        return response()->json(Bills::getFivePercent($bill), 200);
    }

    public function saveWitholdingTaxes(Request $request) {
        $id = $request['id'];
        $twoPercent = $request['TwoPercent'];
        $fivePercent = $request['FivePercent'];
        $amount = $request['NetAmount'];

        $bill = Bills::find($id);
        
        if ($bill != null) {
            if ($twoPercent != null) {
                $bill->Evat2Percent = $twoPercent;
            }

            if ($fivePercent != null) {
                $bill->Evat5Percent = $fivePercent;
            }

            // $bill->NetAmount = $amount;
            $bill->Balance = $amount;
            $bill->save();
        }

        return response()->json($bill, 200);
    }

    public function unwaiveSurcharges(Request $request) {
        $id = $request['id'];

        Bills::where('id', $id)
            ->update(['SurchargeWaived' => null, 'SurchargeWaiveRequestedBy' => null, 'SurchargeWaiveRequestDate' => null]);

        return response()->json('ok', 200);
    }
}
