<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReadingsRequest;
use App\Http\Requests\UpdateReadingsRequest;
use App\Repositories\ReadingsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Bills;
use App\Models\Rates;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Users;
use App\Models\Readings;
use App\Models\Towns;
use App\Models\BillingMeters;
use App\Models\PrePaymentBalance;
use App\Models\ServiceAccounts;
use App\Models\IDGenerator;
use Flash;
use Response;

class ReadingsController extends AppBaseController
{
    /** @var  ReadingsRepository */
    private $readingsRepository;

    public function __construct(ReadingsRepository $readingsRepo)
    {
        $this->middleware('auth');
        $this->readingsRepository = $readingsRepo;
    }

    /**
     * Display a listing of the Readings.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $readings = $this->readingsRepository->all();

        return view('readings.index')
            ->with('readings', $readings);
    }

    /**
     * Show the form for creating a new Readings.
     *
     * @return Response
     */
    public function create()
    {
        return view('readings.create');
    }

    /**
     * Store a newly created Readings in storage.
     *
     * @param CreateReadingsRequest $request
     *
     * @return Response
     */
    public function store(CreateReadingsRequest $request)
    {
        $input = $request->all();

        $readings = $this->readingsRepository->create($input);

        Flash::success('Readings saved successfully.');

        return redirect(route('readings.index'));
    }

    /**
     * Display the specified Readings.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $readings = $this->readingsRepository->find($id);

        if (empty($readings)) {
            Flash::error('Readings not found');

            return redirect(route('readings.index'));
        }

        return view('readings.show')->with('readings', $readings);
    }

    /**
     * Show the form for editing the specified Readings.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $readings = $this->readingsRepository->find($id);

        if (empty($readings)) {
            Flash::error('Readings not found');

            return redirect(route('readings.index'));
        }

        return view('readings.edit')->with('readings', $readings);
    }

    /**
     * Update the specified Readings in storage.
     *
     * @param int $id
     * @param UpdateReadingsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateReadingsRequest $request)
    {
        $readings = $this->readingsRepository->find($id);

        if (empty($readings)) {
            Flash::error('Readings not found');

            return redirect(route('readings.index'));
        }

        $readings = $this->readingsRepository->update($request->all(), $id);

        Flash::success('Readings updated successfully.');

        return redirect(route('readings.index'));
    }

    /**
     * Remove the specified Readings from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $readings = $this->readingsRepository->find($id);

        if (empty($readings)) {
            Flash::error('Readings not found');

            return redirect(route('readings.index'));
        }

        $this->readingsRepository->delete($id);

        Flash::success('Readings deleted successfully.');

        return redirect(route('readings.index'));
    }

    public function readingMonitor() {
        $servicePeriods = DB::table('Billing_Readings')
            ->select('ServicePeriod')
            ->groupBy('ServicePeriod')
            ->orderByDesc('ServicePeriod')
            ->limit(30)
            ->get();

        return view('/readings/reading_monitor', [
            'servicePeriods' => $servicePeriods,
        ]);
    }

    public function readingMonitorView($servicePeriod) {
        $meterReaders = User::role('Meter Reader Inhouse')->orderBy('name')->get();
        $towns = Towns::orderBy('id')->get();

        return view('/readings/reading_monitor_view', [
            'meterReaders' => $meterReaders,
            'servicePeriod' => $servicePeriod,
            'towns' => $towns,
        ]);
    }

    public function getReadingsFromMeterReader(Request $request) {
        $readings = DB::table('Billing_Readings')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Readings.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_Readings.MeterReader', $request['MeterReader'])
            ->where('Billing_Readings.ServicePeriod', $request['ServicePeriod'])
            ->where('Billing_ServiceAccounts.GroupCode', $request['Day'])
            ->where('Billing_ServiceAccounts.Town', $request['Town'])
            ->select('Billing_Readings.*',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.SequenceCode')
            ->orderBy('Billing_Readings.ReadingTimestamp')
            ->get();

        return response()->json($readings, 200);
    }

    public function manualReading(Request $request) {
        if ($request['params'] == null) {
            $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->whereRaw("(NetMetered IS NULL OR NetMetered != 'Yes')")
                        ->select('Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.id', 'Billing_ServiceAccounts.Contestable', 'Billing_ServiceAccounts.AccountType', 'Billing_ServiceAccounts.Purok', 'Billing_ServiceAccounts.OldAccountNo', 'CRM_Towns.Town', 'CRM_Barangays.Barangay', 'Billing_ServiceAccounts.AccountCount')
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->paginate(18);
        } else {
            $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->select('Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.id', 'Billing_ServiceAccounts.Contestable', 'Billing_ServiceAccounts.AccountType', 'Billing_ServiceAccounts.Purok', 'Billing_ServiceAccounts.OldAccountNo', 'CRM_Towns.Town', 'CRM_Barangays.Barangay', 'Billing_ServiceAccounts.AccountCount')
                        ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['params'] . '%')
                        ->whereRaw("(NetMetered IS NULL OR NetMetered != 'Yes')")
                        ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['params'] . '%')
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->paginate(18);
        }    

        return view('/readings/manual_reading', [
            'serviceAccounts' => $serviceAccounts
        ]);
    }

    public function manualReadingConsole($id) {
        $serviceAccounts = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
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
                    'Billing_ServiceAccounts.OrganizationParentAccount',
                    'Billing_ServiceAccounts.Main',
                    'Billing_ServiceAccounts.GroupCode',
                    'Billing_ServiceAccounts.Multiplier',
                    'Billing_ServiceAccounts.Town as TownId',
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
                    'users.name as MeterReader')
            ->where('Billing_ServiceAccounts.id', $id)
            ->first();

        $meters = BillingMeters::where('ServiceAccountId', $id)
            ->orderByDesc('created_at')
            ->first();

        $bills = DB::table('Billing_Bills')
            ->where('Billing_Bills.AccountNumber', $id)
            ->select('Billing_Bills.*',
                DB::raw("(SELECT TOP 1 ORNumber FROM Cashier_PaidBills WHERE ObjectSourceId=Billing_Bills.id AND Status IS NULL) AS ORNumber"),
                DB::raw("(SELECT TOP 1 ORDate FROM Cashier_PaidBills WHERE ObjectSourceId=Billing_Bills.id AND Status IS NULL) AS ORDate"),
                DB::raw("(SELECT TOP 1 id FROM Cashier_PaidBills WHERE ObjectSourceId=Billing_Bills.id AND Status IS NULL) AS PaidBillId"))
            ->orderByDesc('Billing_Bills.ServicePeriod')
            ->get();

        if ($serviceAccounts != null) {
            $rate = Rates::where('ConsumerType', Bills::getAccountType($serviceAccounts))
                ->where('AreaCode', $serviceAccounts->TownId)
                ->orderByDesc('ServicePeriod')
                ->first();

            $presentReading = Readings::where('AccountNumber', $id)
                ->orderByDesc('ServicePeriod')
                ->first();

            $latestBill = Bills::where('AccountNumber', $id)
                ->orderByDesc('ServicePeriod')
                ->first();

            $prepaymentBalance = PrePaymentBalance::where('AccountNumber', $id)->first();

            return view('/readings/manual_reading_console', [
                'account' => $serviceAccounts,
                'meter' => $meters,
                'bills' => $bills,
                'rate' => $rate,
                'presentReading' => $presentReading,
                'latestBill' => $latestBill,
                'prepaymentBalance' => $prepaymentBalance,
            ]);
        } else {
            return abort(404, 'Account not found!');
        }
    }

    public function getComputedBill(Request $request) {
        $account = ServiceAccounts::find($request['AccountNumber']);

        if (Bills::isHighVoltage(Bills::getAccountType($account))) {
            // CHECK IF CONTESTABLE
            if ($account->Contestable=='Yes') {
                $bills = Bills::computeContestableAndDontSave($account, 
                    null, 
                    $request['KwhUsed'], 
                    $request['PreviousKwh'], 
                    $request['PresentKwh'], 
                    $request['ServicePeriod'], 
                    $request['BillingDate'], 
                    0, 
                    0, 
                    $request['Is2307'],
                    $request['Demand']);
            } else {
                $bills = Bills::computeHighVoltageBillAndDontSave($account, 
                    null, 
                    $request['KwhUsed'], 
                    $request['PreviousKwh'], 
                    $request['PresentKwh'], 
                    $request['ServicePeriod'], 
                    $request['BillingDate'], 
                    0, 
                    0, 
                    $request['Is2307'],
                    $request['Demand']);
            }            
        } else {
            if ($account->Contestable=='Yes') {
                $bills = Bills::computeContestableAndDontSave($account, 
                    null, 
                    $request['KwhUsed'], 
                    $request['PreviousKwh'], 
                    $request['PresentKwh'], 
                    $request['ServicePeriod'], 
                    $request['BillingDate'], 
                    0, 
                    0, 
                    $request['Is2307'],
                    $request['Demand']);
            } else {
                $bills = Bills::getRegularBill($account, 
                    null, 
                    $request['KwhUsed'], 
                    $request['PreviousKwh'], 
                    $request['PresentKwh'], 
                    $request['ServicePeriod'], 
                    $request['BillingDate'], 
                    0, 
                    0, 
                    $request['Is2307'],
                    false);
            }
        }
        
        return response()->json($bills, 200);
    }

    public function createManualBilling(Request $request) {
        $account = ServiceAccounts::find($request['AccountNumber']);

        if (floatval($request['KwhUsed']) > -1) {
            if (Bills::isHighVoltage(Bills::getAccountType($account))) {
                // CREATE READING
                $readings = Readings::where('AccountNumber', $account->id)
                    ->where('ServicePeriod', $request['ServicePeriod'])
                    ->first();

                if ($readings != null) {
                    $readings->AccountNumber = $account->id;
                    $readings->ServicePeriod = $request['ServicePeriod'];
                    $readings->ReadingTimestamp = date('Y-m-d H:i:s');
                    $readings->KwhUsed = $request['PresentKwh'];
                    $readings->DemandKwhUsed = $request['Demand'];
                    $readings->MeterReader = Auth::id();
                    $readings->Notes = $request['Notes'];
                    $readings->save();
                } else {
                    $readings = new Readings;
                    $readings->id = IDGenerator::generateIDandRandString();
                    $readings->AccountNumber = $account->id;
                    $readings->ServicePeriod = $request['ServicePeriod'];
                    $readings->ReadingTimestamp = date('Y-m-d H:i:s');
                    $readings->KwhUsed = $request['PresentKwh'];
                    $readings->DemandKwhUsed = $request['Demand'];
                    $readings->MeterReader = Auth::id();
                    $readings->Notes = $request['Notes'];
                    $readings->save();
                }
                
                $bills = Bills::where('AccountNumber', $account->id)
                    ->where('ServicePeriod', $request['ServicePeriod'])
                    ->first();
                
                if ($bills != null) {
                    if ($account->Contestable=='Yes') {
                        $bills = Bills::computeContestable($account, 
                            $bills->id, 
                            $request['KwhUsed'], 
                            $request['PreviousKwh'], 
                            $request['PresentKwh'], 
                            $request['ServicePeriod'], 
                            $request['ReadingDate'], 
                            0, 
                            0, 
                            $request['Is2307'],
                            $request['Demand']);
                        $bills->Notes = $request['Notes'];
                        $bills->save();
                    } else {
                        $bills = Bills::computeHighVoltageBill($account, 
                            $bills->id, 
                            $request['KwhUsed'], 
                            $request['PreviousKwh'], 
                            $request['PresentKwh'], 
                            $request['ServicePeriod'], 
                            $request['ReadingDate'], 
                            0, 
                            0, 
                            $request['Is2307'],
                            $request['Demand']);
                        
                        $bills->Notes = $request['Notes'];
                        $bills->save();
                    }                    
                } else {
                    if ($account->Contestable=='Yes') {
                        $bills = Bills::computeContestable($account, 
                            null, 
                            $request['KwhUsed'], 
                            $request['PreviousKwh'], 
                            $request['PresentKwh'], 
                            $request['ServicePeriod'], 
                            $request['ReadingDate'], 
                            0, 
                            0, 
                            $request['Is2307'],
                            $request['Demand']);

                        $bills->Notes = $request['Notes'];
                        $bills->save();
                    } else {
                        $bills = Bills::computeHighVoltageBill($account, 
                            null, 
                            $request['KwhUsed'], 
                            $request['PreviousKwh'], 
                            $request['PresentKwh'], 
                            $request['ServicePeriod'], 
                            $request['ReadingDate'], 
                            0, 
                            0, 
                            $request['Is2307'],
                            $request['Demand']);

                        $bills->Notes = $request['Notes'];
                        $bills->save();
                    }                    
                }            
            } else {
                // CREATE READING
                $readings = Readings::where('AccountNumber', $account->id)
                    ->where('ServicePeriod', $request['ServicePeriod'])
                    ->first();

                if ($readings != null) {
                    $readings->AccountNumber = $account->id;
                    $readings->ServicePeriod = $request['ServicePeriod'];
                    $readings->ReadingTimestamp = date('Y-m-d H:i:s');
                    $readings->KwhUsed = $request['PresentKwh'];
                    $readings->MeterReader = Auth::id();
                    $readings->Notes = $request['Notes'];
                    $readings->save();
                } else {
                    $readings = new Readings;
                    $readings->id = IDGenerator::generateIDandRandString();
                    $readings->AccountNumber = $account->id;
                    $readings->ServicePeriod = $request['ServicePeriod'];
                    $readings->ReadingTimestamp = date('Y-m-d H:i:s');
                    $readings->KwhUsed = $request['PresentKwh'];
                    $readings->MeterReader = Auth::id();
                    $readings->Notes = $request['Notes'];
                    $readings->save();
                }

                $bills = Bills::where('AccountNumber', $account->id)
                    ->where('ServicePeriod', $request['ServicePeriod'])
                    ->first();
                
                if ($bills != null) {
                    if ($account->Contestable=='Yes') {
                        $bills = Bills::computeContestable($account, 
                            null, 
                            $request['KwhUsed'], 
                            $request['PreviousKwh'], 
                            $request['PresentKwh'], 
                            $request['ServicePeriod'], 
                            $request['ReadingDate'], 
                            0, 
                            0, 
                            $request['Is2307'],
                            $request['Demand']);

                        $bills->Notes = $request['Notes'];
                        $bills->save();
                    } else {
                        $bills = Bills::getRegularBill($account, 
                            $bills->id, 
                            $request['KwhUsed'], 
                            $request['PreviousKwh'], 
                            $request['PresentKwh'], 
                            $request['ServicePeriod'], 
                            $request['ReadingDate'], 
                            0, 
                            0, 
                            $request['Is2307'],
                            false);

                        $bills->Notes = $request['Notes'];
                        $bills->save();

                        // DEDUCT MATERIAL DEPOSIT TO BALANCE
                        if ($account->AdvancedMaterialDeposit > 0) {
                            $account->AdvancedMaterialDeposit = round(floatval($account->AdvancedMaterialDeposit) + floatval($bills->AdvancedMaterialDeposit), 2);
                            $account->save();
                        }

                        // DEDUCT CUSTOMER DEPOSIT TO BALANCE
                        if ($account->CustomerDeposit > 0) {
                            $account->CustomerDeposit = round(floatval($account->CustomerDeposit) + floatval($bills->CustomerDeposit), 2);
                            $account->save();
                        }
                    }
                } else {
                    if ($account->Contestable=='Yes') {
                        $bills = Bills::computeContestable($account, 
                            null, 
                            $request['KwhUsed'], 
                            $request['PreviousKwh'], 
                            $request['PresentKwh'], 
                            $request['ServicePeriod'], 
                            $request['ReadingDate'], 
                            0, 
                            0, 
                            $request['Is2307'],
                            $request['Demand']);

                        $bills->Notes = $request['Notes'];
                        $bills->save();
                    } else {
                        $bills = Bills::getRegularBill($account, 
                            null, 
                            $request['KwhUsed'], 
                            $request['PreviousKwh'], 
                            $request['PresentKwh'], 
                            $request['ServicePeriod'], 
                            $request['ReadingDate'], 
                            0, 
                            0, 
                            $request['Is2307'],
                            false);

                        $bills->Notes = $request['Notes'];
                        $bills->save();

                        // DEDUCT MATERIAL DEPOSIT TO BALANCE
                        if ($account->AdvancedMaterialDeposit > 0) {
                            $account->AdvancedMaterialDeposit = round(floatval($account->AdvancedMaterialDeposit) + floatval($bills->AdvancedMaterialDeposit), 2);
                            $account->save();
                        }

                        // DEDUCT CUSTOMER DEPOSIT TO BALANCE
                        if ($account->CustomerDeposit > 0) {
                            $account->CustomerDeposit = round(floatval($account->CustomerDeposit) + floatval($bills->CustomerDeposit), 2);
                            $account->save();
                        }
                    }
                }            
            }

            Flash::success('Billing and reading saved!.');

            return redirect(route('readings.manual-reading'));
        } else {
            return abort(403, 'Invalid Reading. Your inputted reading is less than the previous one.');
        }
    }

    public function createManualBillingAjax(Request $request) {
        $account = ServiceAccounts::find($request['AccountNumber']);

        if (floatval($request['KwhUsed']) > -1) {
            if (Bills::isHighVoltage(Bills::getAccountType($account))) {
                // CREATE READING
                $readings = Readings::where('AccountNumber', $account->id)
                    ->where('ServicePeriod', $request['ServicePeriod'])
                    ->first();

                if ($readings != null) {
                    $readings->AccountNumber = $account->id;
                    $readings->KwhUsed = $request['PresentKwh'];
                    $readings->DemandKwhUsed = $request['Demand'];
                    // $readings->MeterReader = Auth::id();
                    $readings->save();
                } else {
                    $readings = new Readings;
                    $readings->id = IDGenerator::generateIDandRandString();
                    $readings->AccountNumber = $account->id;
                    $readings->ServicePeriod = $request['ServicePeriod'];
                    $readings->ReadingTimestamp = date('Y-m-d H:i:s');
                    $readings->KwhUsed = $request['PresentKwh'];
                    $readings->DemandKwhUsed = $request['Demand'];
                    $readings->MeterReader = Auth::id();
                    $readings->save();
                }
                
                $bills = Bills::where('AccountNumber', $account->id)
                    ->where('ServicePeriod', $request['ServicePeriod'])
                    ->first();
                
                if ($bills != null) {
                    $bills = Bills::computeHighVoltageBill($account, 
                        $bills->id, 
                        $request['KwhUsed'], 
                        $request['PreviousKwh'], 
                        $request['PresentKwh'], 
                        $request['ServicePeriod'], 
                        date('Y-m-d', strtotime($readings->ReadingTimestamp)), 
                        0, 
                        0, 
                        '',
                        $request['Demand']);
                } else {
                    $bills = Bills::computeHighVoltageBill($account, 
                        null, 
                        $request['KwhUsed'], 
                        $request['PreviousKwh'], 
                        $request['PresentKwh'], 
                        $request['ServicePeriod'], 
                        date('Y-m-d', strtotime($readings->ReadingTimestamp)), 
                        0, 
                        0, 
                        '',
                        $request['Demand']);
                }            
            } else {
                // CREATE READING
                $readings = Readings::where('AccountNumber', $account->id)
                    ->where('ServicePeriod', $request['ServicePeriod'])
                    ->first();

                if ($readings != null) {
                    $readings->AccountNumber = $account->id;
                    $readings->KwhUsed = $request['PresentKwh'];
                    // $readings->MeterReader = Auth::id();
                    $readings->save();
                } else {
                    $readings = new Readings;
                    $readings->id = IDGenerator::generateIDandRandString();
                    $readings->AccountNumber = $account->id;
                    $readings->ServicePeriod = $request['ServicePeriod'];
                    $readings->ReadingTimestamp = date('Y-m-d H:i:s');
                    $readings->KwhUsed = $request['PresentKwh'];
                    $readings->MeterReader = Auth::id();
                    $readings->save();
                }

                $bills = Bills::where('AccountNumber', $account->id)
                    ->where('ServicePeriod', $request['ServicePeriod'])
                    ->first();
                
                if ($bills != null) {
                    $bills = Bills::computeRegularBill($account, 
                        $bills->id, 
                        $request['KwhUsed'], 
                        $request['PreviousKwh'], 
                        $request['PresentKwh'], 
                        $request['ServicePeriod'], 
                        date('Y-m-d', strtotime($readings->ReadingTimestamp)), 
                        0, 
                        0, 
                        '');
                } else {
                    $bills = Bills::computeRegularBill($account, 
                        null, 
                        $request['KwhUsed'], 
                        $request['PreviousKwh'], 
                        $request['PresentKwh'], 
                        $request['ServicePeriod'], 
                        date('Y-m-d', strtotime($readings->ReadingTimestamp)), 
                        0, 
                        0, 
                        '');
                }            
            }

            return response()->json('ok', 200);
        } else {
            return response()->json('amount negative', 200);
        }
    }

    public function capturedReadings(Request $request) {
        // $area = $request['Area'];
        // $groupCode = $request['GroupCode'];
        $meterReader = $request['MeterReader'];
        $period = $request['ServicePeriod'];

        if ($meterReader==null | $period==null) {
            $readings = null;
        } else {
            $readings = Readings::whereNull('AccountNumber')
                ->where('ServicePeriod', $period)
                ->where('MeterReader', $meterReader)
                ->get();
        }

        $meterReaders = User::role('Meter Reader')->get();

        return view('/readings/captured_readings', [
            'meterReaders' => $meterReaders,
            'readings' => $readings,
        ]);
    }

    public function markAsDone(Request $request) {
        $readings = Readings::find($request['id']);

        if ($readings != null) {
            $readings->AccountNumber = 'ERRONEOUS';
            $readings->save();
        }

        return response()->json($readings, 200);
    }

    public function fetchAccount(Request $request) {
        $account = ServiceAccounts::where('OldAccountNo', $request['OldAccountNo'])
            ->first();

        return response()->json($account, 200);
    }

    public function viewFullReportBapa($period, $bapaName) {
        $bapaName = urldecode($bapaName);

        // GET READING DAY FROM TIMESTAMP
        $reading = DB::table('Billing_Readings')
            ->whereRaw("Billing_Readings.AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE OrganizationParentAccount='" . $bapaName . "' AND Billing_Readings.AccountNumber IS NOT NULL AND Billing_Readings.ServicePeriod='" . $period . "')")
            ->first();

        if ($reading != null) {
            $summary = DB::table('Billing_Readings')
                ->select(
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NULL AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE OrganizationParentAccount='" . $bapaName . "')) AS Captured"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE ServicePeriod='" . $period . "' AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE OrganizationParentAccount='" . $bapaName . "')) AS Total"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE ServicePeriod='" . $period . "' AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE AccountStatus='DISCONNECTED' AND OrganizationParentAccount='" . $bapaName . "')) AS Disconnected"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE ServicePeriod='" . $period . "' AND FieldStatus='STUCK-UP' AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE OrganizationParentAccount='" . $bapaName . "')) AS StuckUp"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE ServicePeriod='" . $period . "' AND FieldStatus='CHANGE METER' AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE OrganizationParentAccount='" . $bapaName . "')) AS ChangeMeter"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE ServicePeriod='" . $period . "' AND FieldStatus='NOT IN USE' AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE OrganizationParentAccount='" . $bapaName . "')) AS NotInUse"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE ServicePeriod='" . $period . "' AND FieldStatus='NO DISPLAY' AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE OrganizationParentAccount='" . $bapaName . "')) AS NoDisplay"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE AccountStatus='DISCONNECTED' AND OrganizationParentAccount='" . $bapaName . "') AND AccountNumber IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')) AS DiscoActive"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND FieldStatus NOT IN ('STUCK-UP', 'CHANGE METER', 'NOT IN USE', 'NO DISPLAY') AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE OrganizationParentAccount='" . $bapaName . "') AND AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')) AS OtherUnbilled"),
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.OrganizationParentAccount='" . $bapaName . "' AND b.ServicePeriod='" . $period . "') AS TotalBilled")
                )
                ->first();
        } else {
            $summary = null;
        } 

        // $readingReport = DB::table('Billing_Readings')
        //     ->leftJoin('Billing_ServiceAccounts', 'Billing_Readings.AccountNumber', '=', 'Billing_ServiceAccounts.id')
        //     ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND Billing_Readings.AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE OrganizationParentAccount='" . $bapaName . "' AND Billing_Readings.ServicePeriod='" . $period . "')")
        //     ->select('Billing_Readings.*',
        //         'Billing_ServiceAccounts.id AS AccountId',
        //         'Billing_ServiceAccounts.OldAccountNo',
        //         'Billing_ServiceAccounts.ServiceAccountName',
        //         'Billing_ServiceAccounts.SequenceCode',
        //         'Billing_ServiceAccounts.AccountStatus',
        //         'Billing_ServiceAccounts.Multiplier',
        //         DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "' ORDER BY ServicePeriod DESC) AS PrevReadingTimestamp"),
        //         DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "' ORDER BY ServicePeriod DESC) AS PrevReading"),
        //         DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
        //         DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "') AS PrevKwh"),
        //         DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
        //         DB::raw("(SELECT TOP 1 id FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS BillId"),
        //         )
        //     ->orderBy('AccountStatus')
        //     ->orderBy('CurrentKwh')
        //     ->orderBy('FieldStatus')
        //     ->get();

        $readingReport = DB::table('Billing_Readings')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Readings.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('Billing_Bills', function($join){
                $join->on('Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->on('Billing_Bills.ServicePeriod', '=', 'Billing_Readings.ServicePeriod');
            }) 
            ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE', 'DISCONNECTED') AND Billing_Readings.AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE OrganizationParentAccount='" . $bapaName . "' AND Billing_Readings.ServicePeriod='" . $period . "')")
            ->select('Billing_Readings.*',
                'Billing_ServiceAccounts.id AS AccountId',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.SequenceCode',
                'Billing_ServiceAccounts.AccountStatus',
                'Billing_ServiceAccounts.Multiplier',
                'Billing_Bills.KwhUsed AS CurrentKwh',
                'Billing_Bills.id AS BillId',
                'Billing_Bills.MeterNumber',
                // DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "' ORDER BY ServicePeriod DESC) AS PrevReadingTimestamp"),
                DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "' ORDER BY ServicePeriod DESC) AS PrevReading"),
                // DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "') AS PrevKwh"),
                // DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                )
            ->orderBy('AccountStatus')
            ->orderBy('CurrentKwh')
            ->orderBy('FieldStatus')
            ->get();

        return view('/readings/view_full_report', [
            'period' => $period,
            'day' => '-',
            'meterReader' => null,
            'summary' => $summary,
            'reading' => $reading,
            'readingReport' => $readingReport,
            'town' => '-',
            'bapaName' => $bapaName,
        ]);
    }

    public function viewFullReport($period, $meterReader, $day, $town) {
        $meterReader = User::find($meterReader);

        // GET READING DAY FROM TIMESTAMP
        $reading = DB::table('Billing_Readings')
            ->whereNotNull('Billing_Readings.AccountNumber')
            ->whereRaw("Billing_Readings.AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE id=Billing_Readings.AccountNumber AND GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "')")
            ->where('Billing_Readings.ServicePeriod', $period)
            ->whereRaw("Billing_Readings.MeterReader = '" . $meterReader->id . "'")
            ->first();

        if ($reading != null) {
            $summary = DB::table('Billing_Readings')
                ->select(
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE MeterReader='" . $meterReader->id . "' AND ServicePeriod='" . $period . "' AND AccountNumber IS NULL AND (ReadingTimestamp BETWEEN '" . date('Y-m-d', strtotime($reading->ReadingTimestamp)) . "' AND '" . date('Y-m-d', strtotime($reading->ReadingTimestamp . ' +1 day')) . "')) AS Captured"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE MeterReader='" . $meterReader->id . "' AND ServicePeriod='" . $period . "' AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE Town='" . $town . "' AND GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "')) AS Total"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE MeterReader='" . $meterReader->id . "' AND ServicePeriod='" . $period . "' AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE AccountStatus='DISCONNECTED' AND Town='" . $town . "' AND GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "')) AS Disconnected"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE MeterReader='" . $meterReader->id . "' AND ServicePeriod='" . $period . "' AND FieldStatus='STUCK-UP' AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE Town='" . $town . "' AND GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "')) AS StuckUp"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE MeterReader='" . $meterReader->id . "' AND ServicePeriod='" . $period . "' AND FieldStatus='CHANGE METER' AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE Town='" . $town . "' AND GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "')) AS ChangeMeter"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE MeterReader='" . $meterReader->id . "' AND ServicePeriod='" . $period . "' AND FieldStatus='NOT IN USE' AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE Town='" . $town . "' AND GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "')) AS NotInUse"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE MeterReader='" . $meterReader->id . "' AND ServicePeriod='" . $period . "' AND FieldStatus='NO DISPLAY' AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE Town='" . $town . "' AND GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "')) AS NoDisplay"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE MeterReader='" . $meterReader->id . "' AND ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE AccountStatus='DISCONNECTED' AND Town='" . $town . "' AND GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "') AND AccountNumber IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')) AS DiscoActive"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Readings WHERE MeterReader='" . $meterReader->id . "' AND ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND FieldStatus NOT IN ('STUCK-UP', 'CHANGE METER', 'NOT IN USE', 'NO DISPLAY') AND AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE Town='" . $town . "' AND GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "') AND AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')) AS OtherUnbilled"),
                    DB::raw("(SELECT COUNT(b.id) FROM Billing_Bills b LEFT JOIN Billing_ServiceAccounts sa ON sa.id=b.AccountNumber WHERE sa.Town='" . $town . "' AND sa.GroupCode='" . $day . "' AND sa.MeterReader='" . $meterReader->id . "' AND b.ServicePeriod='" . $period . "') AS TotalBilled")
                )
                ->first();
        } else {
            $summary = null;
        } 

        $readingReport = DB::table('Billing_Readings')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Readings.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereRaw("Billing_Readings.MeterReader = '" . $meterReader->id . "'")
            ->where('Billing_Readings.ServicePeriod', $period)
            ->whereIn('Billing_ServiceAccounts.AccountStatus', ['ACTIVE', 'DISCONNECTED'])
            ->where(function ($query) use ($town, $day, $reading, $meterReader) {
                $query->whereRaw("Billing_Readings.AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE id=Billing_Readings.AccountNumber AND Town='" . $town . "' AND GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "')")
                        ->orWhereRaw("Billing_Readings.AccountNumber IS NULL AND (ReadingTimestamp BETWEEN '" . date('Y-m-d', strtotime($reading->ReadingTimestamp)) . "' AND '" . date('Y-m-d', strtotime($reading->ReadingTimestamp . ' +1 day')) . "')");
            })
            ->select('Billing_Readings.*',
                'Billing_ServiceAccounts.id AS AccountId',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.SequenceCode',
                'Billing_ServiceAccounts.AccountStatus',
                'Billing_ServiceAccounts.Multiplier',
                DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "' ORDER BY ServicePeriod DESC) AS PrevReadingTimestamp"),
                DB::raw("(SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "' ORDER BY ServicePeriod DESC) AS PrevReading"),
                DB::raw("(SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                DB::raw("(SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "') AS PrevKwh"),
                DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                DB::raw("(SELECT TOP 1 id FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS BillId"),
                )
            ->orderBy('AccountStatus')
            ->orderBy('CurrentKwh')
            ->orderBy('FieldStatus')
            ->get();
        
        return view('/readings/view_full_report', [
            'period' => $period,
            'day' => $day,
            'meterReader' => $meterReader,
            'summary' => $summary,
            'reading' => $reading,
            'readingReport' => $readingReport,
            'town' => $town,
            'bapaName' => null,
        ]);
    }

    public function getPreviousReadings(Request $request) {
        $readings = DB::table('Billing_Readings')
            ->leftJoin('users', 'Billing_Readings.MeterReader', '=', 'users.id')
            ->where('AccountNumber', $request['AccountNumber'])
            ->select('Billing_Readings.*', 'users.name')
            ->orderByDesc('ServicePeriod')
            ->get();

        return response()->json($readings, 200);
    }

    public function checkIfAccountHasBill(Request $request) {
        $bills = Bills::where('ServicePeriod', $request['ServicePeriod'])
            ->where('AccountNumber', $request['AccountNumber'])
            ->first();
        
        if ($bills != null) {
            return response()->json('has bill', 200);
        } else {
            return response()->json('ok', 200);
        }
    }

    public function capturedReadingsConsole($id, $readId, $day, $bapaName) {
        $serviceAccounts = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
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
                    'Billing_ServiceAccounts.OrganizationParentAccount',
                    'Billing_ServiceAccounts.Main',
                    'Billing_ServiceAccounts.GroupCode',
                    'Billing_ServiceAccounts.Multiplier',
                    'Billing_ServiceAccounts.Town as TownId',
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
                    'users.name as MeterReader')
            ->where('Billing_ServiceAccounts.id', $id)
            ->first();

        $meters = BillingMeters::where('ServiceAccountId', $id)
            ->orderByDesc('created_at')
            ->first();

        $bills = DB::table('Billing_Bills')
            ->where('Billing_Bills.AccountNumber', $id)
            ->select('Billing_Bills.*',
                DB::raw("(SELECT TOP 1 ORNumber FROM Cashier_PaidBills WHERE ObjectSourceId=Billing_Bills.id AND Status IS NULL) AS ORNumber"),
                DB::raw("(SELECT TOP 1 ORDate FROM Cashier_PaidBills WHERE ObjectSourceId=Billing_Bills.id AND Status IS NULL) AS ORDate"),
                DB::raw("(SELECT TOP 1 id FROM Cashier_PaidBills WHERE ObjectSourceId=Billing_Bills.id AND Status IS NULL) AS PaidBillId"))
            ->orderByDesc('Billing_Bills.ServicePeriod')
            ->get();

        $reading = Readings::find($readId);

        if ($serviceAccounts != null) {
            $rate = Rates::where('ConsumerType', Bills::getAccountType($serviceAccounts))
                ->where('AreaCode', $serviceAccounts->TownId)
                ->orderByDesc('ServicePeriod')
                ->first();

            $prevReading = Readings::where('AccountNumber', $id)
                ->where('ServicePeriod', date('Y-m-01', strtotime($reading->ServicePeriod . ' -1 month')))
                ->orderByDesc('ServicePeriod')
                ->first();

            $latestBill = Bills::where('AccountNumber', $id)
                ->orderByDesc('ServicePeriod')
                ->first();

            return view('/readings/captured_readings_console', [
                'account' => $serviceAccounts,
                'meter' => $meters,
                'bills' => $bills,
                'rate' => $rate,
                'prevReading' => $prevReading,
                'reading' => $reading,
                'latestBill' => $latestBill,
                'day' => $day,
                'bapaName' => $bapaName
            ]);
        } else {
            return abort(404, 'Account not found!');
        }
    }

    public function createBillForCapturedReading(Request $request) {
        $account = ServiceAccounts::find($request['AccountNumber']);

        $readings = Readings::find($request['ReadingId']);

        if (floatval($request['KwhUsed']) > -1) {
            if (Bills::isHighVoltage(Bills::getAccountType($account))) {

                if ($readings != null) {
                    $readings->AccountNumber = $account->id;
                    $readings->ReadingTimestamp = date('Y-m-d H:i:s');
                    $readings->KwhUsed = $request['PresentKwh'];
                    $readings->DemandKwhUsed = $request['Demand'];
                    $readings->save();
                } else {
                    $readings = new Readings;
                    $readings->id = IDGenerator::generateIDandRandString();
                    $readings->AccountNumber = $account->id;
                    $readings->ServicePeriod = $request['ServicePeriod'];
                    $readings->ReadingTimestamp = date('Y-m-d H:i:s');
                    $readings->KwhUsed = $request['PresentKwh'];
                    $readings->DemandKwhUsed = $request['Demand'];
                    $readings->MeterReader = Auth::id();
                    $readings->save();
                }
                
                $bills = Bills::where('AccountNumber', $account->id)
                    ->where('ServicePeriod', $request['ServicePeriod'])
                    ->first();
                
                if ($bills != null) {
                    $bills = Bills::computeHighVoltageBill($account, 
                        $bills->id, 
                        $request['KwhUsed'], 
                        $request['PreviousKwh'], 
                        $request['PresentKwh'], 
                        $request['ServicePeriod'], 
                        $request['ReadingDate'], 
                        0, 
                        0, 
                        $request['Is2307'],
                        $request['Demand']);
                } else {
                    $bills = Bills::computeHighVoltageBill($account, 
                        null, 
                        $request['KwhUsed'], 
                        $request['PreviousKwh'], 
                        $request['PresentKwh'], 
                        $request['ServicePeriod'], 
                        $request['ReadingDate'], 
                        0, 
                        0, 
                        $request['Is2307'],
                        $request['Demand']);
                }   
                $bills->Notes = 'CAPTURED';
                $bills->save();             
            } else {
                if ($readings != null) {
                    $readings->AccountNumber = $account->id;
                    $readings->ReadingTimestamp = date('Y-m-d H:i:s');
                    $readings->KwhUsed = $request['PresentKwh'];
                    $readings->save();
                } else {
                    $readings = new Readings;
                    $readings->id = IDGenerator::generateIDandRandString();
                    $readings->AccountNumber = $account->id;
                    $readings->ServicePeriod = $request['ServicePeriod'];
                    $readings->ReadingTimestamp = date('Y-m-d H:i:s');
                    $readings->KwhUsed = $request['PresentKwh'];
                    $readings->MeterReader = Auth::id();
                    $readings->save();
                }

                $bills = Bills::where('AccountNumber', $account->id)
                    ->where('ServicePeriod', $request['ServicePeriod'])
                    ->first();
                
                if ($bills != null) {
                    $bills = Bills::computeRegularBill($account, 
                        $bills->id, 
                        $request['KwhUsed'], 
                        $request['PreviousKwh'], 
                        $request['PresentKwh'], 
                        $request['ServicePeriod'], 
                        $request['ReadingDate'], 
                        0, 
                        0, 
                        $request['Is2307']);
                } else {
                    $bills = Bills::computeRegularBill($account, 
                        null, 
                        $request['KwhUsed'], 
                        $request['PreviousKwh'], 
                        $request['PresentKwh'], 
                        $request['ServicePeriod'], 
                        $request['ReadingDate'], 
                        0, 
                        0, 
                        $request['Is2307']);                    
                }  
                $bills->Notes = 'CAPTURED';
                $bills->save();          
            }

            Flash::success('Billing and reading saved!.');

            if ($request['BapaName'] == 'mreader') {
                return redirect(route('readings.view-full-report', [$request['ServicePeriod'], $readings->MeterReader, $request['Day'], $account->Town]));
            } else {
                return redirect(route('readings.view-full-report-bapa', [$request['ServicePeriod'], $request['BapaName']]));
            }            
        } else {
            return abort(403, 'Invalid Reading. Your inputted reading is less than the previous one.');
        }
    }

    public function printOldFormatAdjusted($period, $day, $town, $meterReader) {
        $bills = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_Bills.ServicePeriod', $period)
            ->where('Billing_ServiceAccounts.Town', $town)
            ->where('Billing_ServiceAccounts.GroupCode', $day)
            ->whereRaw("Billing_ServiceAccounts.MeterReader='" . $meterReader . "'")
            ->whereRaw("Billing_Bills.UserId ='". Auth::id() . "'")
            ->whereRaw("Billing_Bills.AccountNumber IN (SELECT AccountNumber FROM Billing_Readings WHERE FieldStatus IS NOT NULL AND ServicePeriod='" . $period . "' AND MeterReader='" . $meterReader . "' AND AccountNumber IS NOT NULL)")
            ->select('Billing_Bills.*')
            ->orderBy('Billing_Bills.BillNumber')
            ->get();

        return view('/bills/print_bulk_old_format', [
            'bills' => $bills
        ]);
    }

    public function printNewFormatAdjusted($period, $day, $town, $meterReader) {
        $bills = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')            
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Billing_Bills.ServicePeriod', $period)
            ->where('Billing_ServiceAccounts.Town', $town)
            ->where('Billing_ServiceAccounts.GroupCode', $day)
            ->whereRaw("Billing_ServiceAccounts.MeterReader ='" . $meterReader . "'")
            ->whereRaw("Billing_Bills.UserId ='". Auth::id() . "'")
            ->whereRaw("Billing_Bills.AccountNumber IN (SELECT AccountNumber FROM Billing_Readings WHERE FieldStatus IS NOT NULL AND ServicePeriod='" . $period . "' AND MeterReader='" . $meterReader . "' AND AccountNumber IS NOT NULL)")
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
            ->orderBy('Billing_Bills.BillNumber')
            ->get();

        return view('/bills/print_bulk_bill_new_format', [
            'bills' => $bills
        ]);
    }

    public function printOldFormatAdjustedBapa($period, $bapaName) {
        $bills = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_Bills.ServicePeriod', $period)
            ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
            ->whereRaw("Billing_Bills.UserId ='". Auth::id() . "'")
            ->select('Billing_Bills.*')
            ->orderBy('Billing_Bills.BillNumber')
            ->get();

        return view('/bills/print_bulk_old_format', [
            'bills' => $bills
        ]);
    }

    public function printNewFormatAdjustedBapa($period, $bapaName) {
        $bills = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')    
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Billing_Bills.ServicePeriod', $period)
            ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
            ->whereRaw("Billing_Bills.UserId ='". Auth::id() . "'")
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
            ->orderBy('Billing_Bills.BillNumber')
            ->get();

        return view('/bills/print_bulk_bill_new_format', [
            'bills' => $bills
        ]);
    }

    public function printUnbilledList($period, $day, $town, $meterReader, $status) {
        $meterReader = User::find($meterReader);

        $reading = DB::table('Billing_Readings')
            ->whereNotNull('Billing_Readings.AccountNumber')
            ->whereRaw("Billing_Readings.AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "')")
            ->where('Billing_Readings.ServicePeriod', $period)
            ->whereRaw("Billing_Readings.MeterReader='" . $meterReader->id . "'")
            ->first();

        $readingReport = DB::table('Billing_Readings')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Readings.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereRaw("Billing_Readings.MeterReader = '" . $meterReader->id . "'")
            ->where('Billing_Readings.ServicePeriod', $period)
            ->where('Billing_Readings.FieldStatus', $status)
            ->where(function ($query) use ($town, $day, $reading, $meterReader) {
                $query->whereRaw("Billing_Readings.AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE Town='" . $town . "' AND GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "')")
                        ->orWhereRaw("Billing_Readings.AccountNumber IS NULL AND (ReadingTimestamp BETWEEN '" . date('Y-m-d', strtotime($reading->ReadingTimestamp)) . "' AND '" . date('Y-m-d', strtotime($reading->ReadingTimestamp . ' +1 day')) . "')");
            })
            ->select('Billing_Readings.*',
                'Billing_ServiceAccounts.id AS AccountId',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.SequenceCode',
                'Billing_ServiceAccounts.AccountStatus',
                'Billing_ServiceAccounts.Multiplier',
                DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "' ORDER BY ServicePeriod DESC) AS PrevReadingTimestamp"),
                DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "' ORDER BY ServicePeriod DESC) AS PrevReading"),
                DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                )
            ->orderBy('AccountStatus')
            ->orderBy('FieldStatus')
            ->get();

        return view('/readings/print_unbilled_by_status', [
            'period' => $period,
            'day' => $day,
            'town' => $town,
            'status' => $status,
            'meterReader' => $meterReader,
            'readingReport' => $readingReport,
        ]);
    }

    public function printOtherUnbilledList($period, $day, $town, $meterReader) {
        $meterReader = User::find($meterReader);

        $reading = DB::table('Billing_Readings')
            ->whereNotNull('Billing_Readings.AccountNumber')
            ->whereRaw("Billing_Readings.AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "')")
            ->where('Billing_Readings.ServicePeriod', $period)
            ->whereRaw("Billing_Readings.MeterReader='" . $meterReader->id . "'")
            ->first();

        $readingReport = DB::table('Billing_Readings')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Readings.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereRaw("Billing_Readings.MeterReader = '" . $meterReader->id . "'")
            ->where('Billing_Readings.ServicePeriod', $period)
            ->whereNotIn('Billing_Readings.FieldStatus', ['STUCK-UP', 'NO DISPLAY', 'NOT IN USE', 'CHANGE METER'])
            ->where(function ($query) use ($town, $day, $reading, $meterReader) {
                $query->whereRaw("Billing_Readings.AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE Town='" . $town . "' AND GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "')")
                        ->orWhereRaw("Billing_Readings.AccountNumber IS NULL AND (ReadingTimestamp BETWEEN '" . date('Y-m-d', strtotime($reading->ReadingTimestamp)) . "' AND '" . date('Y-m-d', strtotime($reading->ReadingTimestamp . ' +1 day')) . "')");
            })
            ->select('Billing_Readings.*',
                'Billing_ServiceAccounts.id AS AccountId',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.SequenceCode',
                'Billing_ServiceAccounts.AccountStatus',
                'Billing_ServiceAccounts.Multiplier',
                DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "' ORDER BY ServicePeriod DESC) AS PrevReadingTimestamp"),
                DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "' ORDER BY ServicePeriod DESC) AS PrevReading"),
                DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                )
            ->orderBy('AccountStatus')
            ->orderBy('FieldStatus')
            ->get();

        return view('/readings/print_unbilled_by_status', [
            'period' => $period,
            'day' => $day,
            'town' => $town,
            'status' => 'OTHER UNBILLED',
            'meterReader' => $meterReader,
            'readingReport' => $readingReport,
        ]);
    }

    public function billAndUnbilledReport(Request $request) {
        $type = $request['Type']; // billed, unbilled
        $meterReader = $request['MeterReader'];
        $day = $request['Day'];
        $period = $request['ServicePeriod'];
        $town = $request['Office'];
        $status = $request['AccountStatus'];

        $meterReaders = User::role('Meter Reader Inhouse')->orderBy('name')->get();

        if ($type == null && $meterReader == null && $day == null && $period == null && $town == null) {
            $readingReport = [];
        } else {
            if ($status == 'All') {
                if ($town == 'All') {
                    if ($meterReader == 'All') {
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } else {
                        $meterReader = User::find($meterReader);
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } 
                } else {
                    if ($meterReader == 'All') {
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } else {
                        $meterReader = User::find($meterReader);
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } 
                } 
            } else {
                if ($town == 'All') {
                    if ($meterReader == 'All') {
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } else {
                        $meterReader = User::find($meterReader);
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } 
                } else {
                    if ($meterReader == 'All') {
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } else {
                        $meterReader = User::find($meterReader);
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } 
                } 
            }                                  
        }  
        
        $acctStatus = DB::table('Billing_ServiceAccounts')
            ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
            ->select('AccountStatus')
            ->groupBy('AccountStatus')
            ->orderBy('AccountStatus')
            ->get();

        return view('/readings/reports_billed_unbilled', [
            'meterReaders' => $meterReaders,
            'readingReport' => $readingReport,
            'towns' => Towns::all(),
            'acctStatus' => $acctStatus
        ]);
    }

    public function printBilledUnbilled($type, $meterReader, $day, $period, $town, $status) {     
        if ($type == null && $meterReader == null && $day == null && $period == null && $town == null) {
            return abort(404, 'MISSIG PARAMETERS');
        } else {
            if ($status == 'All') {
                if ($town == 'All') {
                    if ($meterReader == 'All') {
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } else {
                        $meterReader = User::find($meterReader);
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } 
                } else {
                    if ($meterReader == 'All') {
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } else {
                        $meterReader = User::find($meterReader);
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } 
                } 
            } else {
                if ($town == 'All') {
                    if ($meterReader == 'All') {
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } else {
                        $meterReader = User::find($meterReader);
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } 
                } else {
                    if ($meterReader == 'All') {
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } else {
                        $meterReader = User::find($meterReader);
                        if ($type == 'Billed') {
                            if ($day == 'All') {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                        

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            }
                        } else {
                            // UNBILLED
                            if ($day == 'All') {                    
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();

                            } else {
                                $readingReport = DB::table('Billing_ServiceAccounts')
                                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                                    ->whereRaw("MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.Town='" . $town . "' AND GroupCode='" . $day . "'")
                                    ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")
                                    ->whereRaw("AccountStatus='" . $status . "'")
                                    ->select('Billing_ServiceAccounts.id AS AccountId',
                                            'Billing_ServiceAccounts.OldAccountNo',
                                            'Billing_ServiceAccounts.ServiceAccountName',
                                            'Billing_ServiceAccounts.SequenceCode',
                                            'Billing_ServiceAccounts.AccountStatus',
                                            'Billing_ServiceAccounts.Multiplier',
                                            'Billing_ServiceAccounts.AreaCode',
                                            'CRM_Towns.Town', 
                                            'CRM_Barangays.Barangay',
                                            'Billing_ServiceAccounts.Purok',
                                            DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                            DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                            DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                            DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                            DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                        )
                                    ->orderBy('AreaCode')
                                    ->orderBy('SequenceCode')
                                    ->orderBy('AccountStatus')
                                    ->orderBy('FieldStatus')
                                    ->get();                  
                            }            
                        }
                    } 
                } 
            }    

            return view('/readings/print_billed_unbilled_report', [
                'meterReader' => $meterReader=='All' ? 'All' : $meterReader->name,
                'readingReport' => $readingReport,
                'day' => $day,
                'town' => $town,
                'period' => $period,
                'type' => $type,
            ]);
        }  
    }

    public function billAndUnbilledReportBapa(Request $request) {
        $type = $request['Type']; // billed, unbilled
        $bapaName = $request['BAPAName'];
        $period = $request['ServicePeriod'];
        $town = $request['Office'];
        $status = $request['AccountStatus'];

        $bapas = DB::table('Billing_ServiceAccounts')
                ->whereNotNull('OrganizationParentAccount')
                ->select('OrganizationParentAccount')
                ->groupBy('OrganizationParentAccount')
                ->orderBy('OrganizationParentAccount')
                ->get();        

        if ($type == null && $period == null) {
            $readingReport = [];
        } else {
            if ($status == 'All') {
                if ($type == 'Billed') {
                    $readingReport = DB::table('Billing_ServiceAccounts')
                            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                            ->whereRaw("OrganizationParentAccount='" . $bapaName . "'")
                            ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                            ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                            ->select('Billing_ServiceAccounts.id AS AccountId',
                                    'Billing_ServiceAccounts.OldAccountNo',
                                    'Billing_ServiceAccounts.ServiceAccountName',
                                    'Billing_ServiceAccounts.SequenceCode',
                                    'Billing_ServiceAccounts.AccountStatus',
                                    'Billing_ServiceAccounts.Multiplier',
                                    'Billing_ServiceAccounts.AreaCode',
                                    'CRM_Towns.Town', 
                                    'CRM_Barangays.Barangay',
                                    'Billing_ServiceAccounts.Purok',
                                    DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                    DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                    DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                )
                            ->orderBy('AreaCode')
                            ->orderBy('SequenceCode')
                            ->orderBy('AccountStatus')
                            ->orderBy('FieldStatus')
                            ->get();        
                } else {     
                    $readingReport = DB::table('Billing_ServiceAccounts')
                            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                            ->whereRaw("OrganizationParentAccount='" . $bapaName . "'")
                            ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                            ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                            ->select('Billing_ServiceAccounts.id AS AccountId',
                                    'Billing_ServiceAccounts.OldAccountNo',
                                    'Billing_ServiceAccounts.ServiceAccountName',
                                    'Billing_ServiceAccounts.SequenceCode',
                                    'Billing_ServiceAccounts.AccountStatus',
                                    'Billing_ServiceAccounts.Multiplier',
                                    'Billing_ServiceAccounts.AreaCode',
                                    'CRM_Towns.Town', 
                                    'CRM_Barangays.Barangay',
                                    'Billing_ServiceAccounts.Purok',
                                    DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                    DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                    DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                )
                            ->orderBy('AreaCode')
                            ->orderBy('SequenceCode')
                            ->orderBy('AccountStatus')
                            ->orderBy('FieldStatus')
                            ->get();  
                }
            } else {
                if ($type == 'Billed') {
                    $readingReport = DB::table('Billing_ServiceAccounts')
                            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                            ->whereRaw("OrganizationParentAccount='" . $bapaName . "'")
                            ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                            ->whereRaw("AccountStatus='" . $status . "'")
                            ->select('Billing_ServiceAccounts.id AS AccountId',
                                    'Billing_ServiceAccounts.OldAccountNo',
                                    'Billing_ServiceAccounts.ServiceAccountName',
                                    'Billing_ServiceAccounts.SequenceCode',
                                    'Billing_ServiceAccounts.AccountStatus',
                                    'Billing_ServiceAccounts.Multiplier',
                                    'Billing_ServiceAccounts.AreaCode',
                                    'CRM_Towns.Town', 
                                    'CRM_Barangays.Barangay',
                                    'Billing_ServiceAccounts.Purok',
                                    DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                    DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                    DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                )
                            ->orderBy('AreaCode')
                            ->orderBy('SequenceCode')
                            ->orderBy('AccountStatus')
                            ->orderBy('FieldStatus')
                            ->get();        
                } else {     
                    $readingReport = DB::table('Billing_ServiceAccounts')
                            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                            ->whereRaw("OrganizationParentAccount='" . $bapaName . "'")
                            ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                            ->whereRaw("AccountStatus='" . $status . "'")
                            ->select('Billing_ServiceAccounts.id AS AccountId',
                                    'Billing_ServiceAccounts.OldAccountNo',
                                    'Billing_ServiceAccounts.ServiceAccountName',
                                    'Billing_ServiceAccounts.SequenceCode',
                                    'Billing_ServiceAccounts.AccountStatus',
                                    'Billing_ServiceAccounts.Multiplier',
                                    'Billing_ServiceAccounts.AreaCode',
                                    'CRM_Towns.Town', 
                                    'CRM_Barangays.Barangay',
                                    'Billing_ServiceAccounts.Purok',
                                    DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                    DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                    DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                )
                            ->orderBy('AreaCode')
                            ->orderBy('SequenceCode')
                            ->orderBy('AccountStatus')
                            ->orderBy('FieldStatus')
                            ->get();  
                }
            }
        }   
        
        $acctStatus = DB::table('Billing_ServiceAccounts')
            ->select('AccountStatus')
            ->groupBy('AccountStatus')
            ->orderBy('AccountStatus')
            ->get();

        return view('/readings/reports_billed_unbilled_bapa', [
            'bapas' => $bapas,
            'readingReport' => $readingReport,
            'towns' => Towns::all(),
            'acctStatus' => $acctStatus
        ]);
    }

    public function printBilledUnbilledBapa($type, $bapaName, $period, $status) {   
        $bapaName = urldecode($bapaName);
        if ($type == null && $bapaName==null && $period == null) {
            return abort(404, 'MISSING PARAMETERS');
        } else {    
            if ($status == 'All') {
                if ($type == 'Billed') {
                    $readingReport = DB::table('Billing_ServiceAccounts')
                            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                            ->whereRaw("OrganizationParentAccount='" . $bapaName . "'")
                            ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                            ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                            ->select('Billing_ServiceAccounts.id AS AccountId',
                                    'Billing_ServiceAccounts.OldAccountNo',
                                    'Billing_ServiceAccounts.ServiceAccountName',
                                    'Billing_ServiceAccounts.SequenceCode',
                                    'Billing_ServiceAccounts.AccountStatus',
                                    'Billing_ServiceAccounts.Multiplier',
                                    'Billing_ServiceAccounts.AreaCode',
                                    'CRM_Towns.Town', 
                                    'CRM_Barangays.Barangay',
                                    'Billing_ServiceAccounts.Purok',
                                    DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                    DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                    DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                )
                            ->orderBy('AreaCode')
                            ->orderBy('SequenceCode')
                            ->orderBy('AccountStatus')
                            ->orderBy('FieldStatus')
                            ->get();        
                } else {     
                    $readingReport = DB::table('Billing_ServiceAccounts')
                            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                            ->whereRaw("OrganizationParentAccount='" . $bapaName . "'")
                            ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                            ->whereRaw("AccountStatus IN ('ACTIVE', 'DISCONNECTED')")
                            ->select('Billing_ServiceAccounts.id AS AccountId',
                                    'Billing_ServiceAccounts.OldAccountNo',
                                    'Billing_ServiceAccounts.ServiceAccountName',
                                    'Billing_ServiceAccounts.SequenceCode',
                                    'Billing_ServiceAccounts.AccountStatus',
                                    'Billing_ServiceAccounts.Multiplier',
                                    'Billing_ServiceAccounts.AreaCode',
                                    'CRM_Towns.Town', 
                                    'CRM_Barangays.Barangay',
                                    'Billing_ServiceAccounts.Purok',
                                    DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                    DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                    DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                )
                            ->orderBy('AreaCode')
                            ->orderBy('SequenceCode')
                            ->orderBy('AccountStatus')
                            ->orderBy('FieldStatus')
                            ->get();  
                }
            } else {
                if ($type == 'Billed') {
                    $readingReport = DB::table('Billing_ServiceAccounts')
                            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                            ->whereRaw("OrganizationParentAccount='" . $bapaName . "'")
                            ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                            ->whereRaw("AccountStatus='" . $status . "'")
                            ->select('Billing_ServiceAccounts.id AS AccountId',
                                    'Billing_ServiceAccounts.OldAccountNo',
                                    'Billing_ServiceAccounts.ServiceAccountName',
                                    'Billing_ServiceAccounts.SequenceCode',
                                    'Billing_ServiceAccounts.AccountStatus',
                                    'Billing_ServiceAccounts.Multiplier',
                                    'Billing_ServiceAccounts.AreaCode',
                                    'CRM_Towns.Town', 
                                    'CRM_Barangays.Barangay',
                                    'Billing_ServiceAccounts.Purok',
                                    DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                    DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                    DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                )
                            ->orderBy('AreaCode')
                            ->orderBy('SequenceCode')
                            ->orderBy('AccountStatus')
                            ->orderBy('FieldStatus')
                            ->get();        
                } else {     
                    $readingReport = DB::table('Billing_ServiceAccounts')
                            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                            ->whereRaw("OrganizationParentAccount='" . $bapaName . "'")
                            ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                            ->whereRaw("AccountStatus='" . $status . "'")
                            ->select('Billing_ServiceAccounts.id AS AccountId',
                                    'Billing_ServiceAccounts.OldAccountNo',
                                    'Billing_ServiceAccounts.ServiceAccountName',
                                    'Billing_ServiceAccounts.SequenceCode',
                                    'Billing_ServiceAccounts.AccountStatus',
                                    'Billing_ServiceAccounts.Multiplier',
                                    'Billing_ServiceAccounts.AreaCode',
                                    'CRM_Towns.Town', 
                                    'CRM_Barangays.Barangay',
                                    'Billing_ServiceAccounts.Purok',
                                    DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS ReadingTimestamp"),
                                    DB::raw("(SELECT TOP 1 FieldStatus FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS FieldStatus"),
                                    DB::raw("(SELECT TOP 1 Notes FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period  . "' ORDER BY ServicePeriod DESC) AS Notes"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "' ORDER BY ServicePeriod DESC) AS Reading"),
                                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                                )
                            ->orderBy('AreaCode')
                            ->orderBy('SequenceCode')
                            ->orderBy('AccountStatus')
                            ->orderBy('FieldStatus')
                            ->get();  
                }
            }

            return view('/readings/print_billed_unbilled_report_bapa', [
                'bapaName' => $bapaName,
                'readingReport' => $readingReport,
                'period' => $period,
                'type' => $type,
            ]);
        }  
    }

    public function printDiscoActive($meterReader, $day, $period, $town) {
        $meterReader = User::find($meterReader);

        $reading = DB::table('Billing_Readings')
            ->whereNotNull('Billing_Readings.AccountNumber')
            ->whereRaw("Billing_Readings.AccountNumber IN (SELECT id FROM Billing_ServiceAccounts WHERE GroupCode='" . $day . "' AND MeterReader='" . $meterReader->id . "')")
            ->where('Billing_Readings.ServicePeriod', $period)
            ->whereRaw("Billing_Readings.MeterReader='" . $meterReader->id . "'")
            ->first();

        $readingReport = DB::table('Billing_Readings')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Readings.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereRaw("Billing_Readings.MeterReader = '" . $meterReader->id . "'")
            ->where('Billing_Readings.ServicePeriod', $period)
            ->whereRaw("Town='" . $town . "' AND GroupCode='" . $day . "' AND Billing_ServiceAccounts.MeterReader='" . $meterReader->id . "' AND Billing_ServiceAccounts.AccountStatus='DISCONNECTED'")
            ->whereRaw("Billing_ServiceAccounts.id IN (SELECT AccountNumber FROM Billing_Bills WHERE ServicePeriod='" . $period . "')")            
            ->select('Billing_Readings.*',
                'Billing_ServiceAccounts.id AS AccountId',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.SequenceCode',
                'Billing_ServiceAccounts.AccountStatus',
                'Billing_ServiceAccounts.Multiplier',
                DB::raw("(SELECT TOP 1 ReadingTimestamp FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "' ORDER BY ServicePeriod DESC) AS PrevReadingTimestamp"),
                DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . date('Y-m-01', strtotime($period . ' -1 month')) . "' ORDER BY ServicePeriod DESC) AS PrevReading"),
                DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS CurrentKwh"),
                DB::raw("(SELECT TOP 1 NetAmount FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS NetAmount"),
                )
            ->orderBy('AccountStatus')
            ->orderBy('FieldStatus')
            ->get();

        return view('/readings/print_disco_active', [
            'period' => $period,
            'day' => $day,
            'town' => $town,
            'meterReader' => $meterReader,
            'readingReport' => $readingReport,
        ]);
    }

    public function printBapaReadingList() {
        return view('/readings/print_bapa_reading_list', [
            'towns' => Towns::all(),
        ]);
    }

    public function searchPrintBapaReadingList(Request $request) {
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
                                <td><a href="' . route('serviceAccounts.bapa-view', [urlencode($item->OrganizationParentAccount)]) . '">' . $item->OrganizationParentAccount . '</a></td>
                                <td style="width: 10%;">
                                    <button class="btn btn-warning btn-sm" onclick=selectPeriod("' . urlencode($item->OrganizationParentAccount) . '")><i class="fas fa-print ico-tab"></i>Print</button>
                                </td>
                                <td>' . $item->Town . '</td>
                                <td>' . number_format($item->NoOfAccounts) . '</td>
                                <td style="width: 30%;">' . $item->Result . '</td>
                            </tr>';
            }
            
        }

        return response()->json($output, 200);
    }

    public function printBapaReadingListToPaper($bapaName, $period) {
        $bapaName = urldecode($bapaName);

        $prevMonth = date('Y-m-01', strtotime($period . ' -1 month'));

        $routes = ServiceAccounts::where('OrganizationParentAccount', $bapaName)
            ->select('AreaCode')
            ->groupBy('AreaCode')
            ->orderBy('AreaCode')
            ->get();

        $accounts = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
            ->whereIn('Billing_ServiceAccounts.AccountStatus', ['ACTIVE', 'DISCONNECTED'])
            ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Readings WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
            ->where(function ($query) {
                $query->where(function($queryX) {
                        $queryX->where('Billing_ServiceAccounts.AccountExpiration', '>', date('Y-m-d'))
                            ->where('Billing_ServiceAccounts.AccountRetention', 'Temporary');
                    })
                    ->orWhere('Billing_ServiceAccounts.AccountRetention', 'Permanent')
                    ->orWhereNull('Billing_ServiceAccounts.AccountExpiration');
            })
            ->select('Billing_ServiceAccounts.id', 
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.Multiplier',
                'Billing_ServiceAccounts.AccountType',
                'Billing_ServiceAccounts.AccountStatus',
                'Billing_ServiceAccounts.AreaCode',
                'Billing_ServiceAccounts.GroupCode',
                'Billing_ServiceAccounts.OldAccountNo',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'Billing_ServiceAccounts.Purok',
                DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) AS PreviousKwhUsed"),
            )
            ->orderBy('Billing_ServiceAccounts.AreaCode')
            ->orderBy('Billing_ServiceAccounts.OldAccountNo')
            ->get();

        return view('/readings/print_bapa_reading_list_to_paper', [
            'period' => $period,
            'accounts' => $accounts,
            'bapaName' => $bapaName,
            'routes' => $routes,
        ]);
    }

    public function efficiencyReport(Request $request) {
        $office = $request['Office'] != null ? $request['Office'] : env("APP_AREA_CODE");
        $latestRate = Rates::where('AreaCode', $office)
                ->orderByDesc('ServicePeriod')
                ->first();
        $month = $request['ServicePeriod'] != null ? $request['ServicePeriod'] : ($latestRate != null ? $latestRate->ServicePeriod : date('Y-m-01')); // present
        $period = date('Y-m-01', strtotime($month . ' -1 month')); // previous
        $meterReader = $request['MeterReader'];
        // $from = $request['From'] != null ? $request['From'] : $period;
        // $to = $request['To'] != null ? $request['To'] : $month;
        $fromPrev = date('Y-m-d', strtotime('first day of ' . $period));
        $toPrev = date('Y-m-d', strtotime('last day of ' . $period));
        $thisMonthFrom = $month;
        $thisMonthTo= date('Y-m-d', strtotime('last day of ' . $month));
        // $thisMonthTo= date('Y-m-d');

        if ($office == 'All') {
            $meterReaders = DB::table('Billing_ServiceAccounts')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->whereNotNull('MeterReader')
                ->select('Billing_ServiceAccounts.MeterReader', 'users.name')
                ->groupBy('Billing_ServiceAccounts.MeterReader', 'users.name')
                ->orderBy('users.name')
                ->get();
        } else {
            $meterReaders = DB::table('Billing_ServiceAccounts')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->where('Billing_ServiceAccounts.Town', $office)
                ->whereNotNull('MeterReader')
                ->select('Billing_ServiceAccounts.MeterReader', 'users.name')
                ->groupBy('Billing_ServiceAccounts.MeterReader', 'users.name')
                ->orderBy('users.name')
                ->get();
        }        

        if ($meterReader != null) {
            $data = DB::table('Billing_ServiceAccounts')
                ->whereRaw("Town='" . $office . "' AND MeterReader='" . $meterReader . "'")
                ->select('AreaCode',
                    DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ServicePeriod='" . $period . "') AS PeriodNoOfBillsSales"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ServicePeriod='" . $period . "') AS PeriodBillAmountSales"),
                    DB::raw("(SELECT SUM(TRY_CAST(Total AS DECIMAL(10,2))) FROM Cashier_TransactionIndex WHERE Status IS NULL AND AccountNumber IS NOT NULL AND AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ORDate BETWEEN '" . $fromPrev . "' AND '" . $toPrev . "') AS PeriodOthersSales"),

                    DB::raw("(SELECT COUNT(b.id) FROM Cashier_PaidBills pb LEFT JOIN Billing_Bills b ON pb.AccountNumber=b.AccountNumber AND pb.ServicePeriod=b.ServicePeriod 
                        WHERE (pb.Status IS NULL OR pb.Status='Application') AND pb.ServicePeriod='" . $period . "' AND pb.AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND pb.ORDate <= '" . $toPrev . "') AS PeriodNoOfBillsPrevMonthCollection"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(10,2))) FROM Cashier_PaidBills pb LEFT JOIN Billing_Bills b ON pb.AccountNumber=b.AccountNumber AND pb.ServicePeriod=b.ServicePeriod
                         WHERE (pb.Status IS NULL OR pb.Status='Application') AND pb.ServicePeriod='" . $period . "' AND pb.AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND pb.ORDate <= '" . $toPrev . "') AS PeriodAmountPrevMonthCollection"),

                    DB::raw("(SELECT COUNT(b.id) FROM Cashier_PaidBills pb LEFT JOIN Billing_Bills b ON pb.AccountNumber=b.AccountNumber AND pb.ServicePeriod=b.ServicePeriod 
                         WHERE (pb.Status IS NULL OR pb.Status='Application') AND pb.ServicePeriod='" . $period . "' AND pb.AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND pb.ORDate BETWEEN '" . $thisMonthFrom . "' AND '" . $thisMonthTo . "') AS PeriodNoOfBillsCurrentMonthCollection"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(10,2))) FROM Cashier_PaidBills pb LEFT JOIN Billing_Bills b ON pb.AccountNumber=b.AccountNumber AND pb.ServicePeriod=b.ServicePeriod
                        WHERE (pb.Status IS NULL OR pb.Status='Application') AND pb.ServicePeriod='" . $period . "' AND pb.AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND pb.ORDate BETWEEN '" . $thisMonthFrom . "' AND '" . $thisMonthTo . "') AS PeriodAmountCurrentMonthCollection"),

                    DB::raw("(SELECT COUNT(id) FROM Cashier_PaidBills WHERE (Status IS NULL OR Status='Application') AND ServicePeriod<'" . $period . "' AND AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ORDate BETWEEN '" . $fromPrev . "' AND '" . $toPrev . "') AS PeriodNoOfBillsArrearsCollected"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Cashier_PaidBills WHERE (Status IS NULL OR Status='Application') AND ServicePeriod<'" . $period . "' AND AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ORDate BETWEEN '" . $fromPrev . "' AND '" . $toPrev . "') AS PeriodAmountArrearsCollected"),
                    DB::raw("(SELECT COUNT(id) FROM Cashier_PaidBills WHERE (Status IS NULL OR Status='Application') AND ServicePeriod='" . $month . "' AND AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ORDate BETWEEN '" . $month . "' AND '" . $thisMonthTo . "') AS CurrentNoOfBillsSales"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Cashier_PaidBills WHERE (Status IS NULL OR Status='Application') AND ServicePeriod='" . $month . "' AND AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ORDate BETWEEN '" . $month . "' AND '" . $thisMonthTo . "') AS CurrentAmountSales"),
                    DB::raw("(SELECT SUM(TRY_CAST(Total AS DECIMAL(10,2))) FROM Cashier_TransactionIndex WHERE (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL AND AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ORDate BETWEEN '" . $month . "' AND '" . $thisMonthTo . "') AS CurrentOthersSales"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE ServicePeriod='" . $period . "' AND (AccountNumber IN
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "' AND sa.AccountStatus='DISCONNECTED') OR AccountNumber IN
                        (SELECT AccountNumber FROM Disconnection_History WHERE Status='DISCONNECTED' AND ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND AccountNumber IN 
                            (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "'))) AND AccountNumber NOT IN 
                        (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND AccountNumber=Billing_Bills.AccountNumber 
                            AND (Status IS NULL OR Status='Application') AND ORDate <= '" . $thisMonthTo . "')) AS DiscoCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ServicePeriod='" . $period . "' AND (AccountNumber IN
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "' AND sa.AccountStatus='DISCONNECTED') OR AccountNumber IN
                        (SELECT AccountNumber FROM Disconnection_History WHERE Status='DISCONNECTED' AND ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND AccountNumber IN 
                            (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "'))) AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND AccountNumber=Billing_Bills.AccountNumber 
                                AND (Status IS NULL OR Status='Application') AND ORDate <= '" . $thisMonthTo . "')) AS DiscoAmount"),
                    DB::raw("(SELECT COUNT(DISTINCT AccountNumber) FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL AND AccountNumber IN
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') AND AccountNumber NOT IN 
                        (SELECT pb.AccountNumber FROM Cashier_PaidBills pb WHERE pb.AccountNumber IS NOT NULL AND pb.ServicePeriod='" . $period . "' AND pb.AccountNumber=Billing_Excemptions.AccountNumber AND (pb.Status IS NULL OR pb.Status='Application') AND ORDate <= '" . $thisMonthTo . "')) AS AdjustmentCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE AccountNumber IN 
                        (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL AND AccountNumber IN
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') AND AccountNumber NOT IN 
                        (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND AccountNumber=Billing_Excemptions.AccountNumber AND (Status IS NULL OR Status='Application') AND ORDate <= '" . $thisMonthTo . "')) 
                        AND ServicePeriod='" . $period . "') AS AdjustmentAmount"),

                )
                ->groupBy('AreaCode')
                ->orderBy('AreaCode')
                ->get();
        } else {
            $data = [];
        }     
        
        $towns = Towns::all();

        return view('/readings/efficiency_report', [
            'month' => $month,
            'office' => $office,
            'meterReaders' => $meterReaders,
            'meterReader' => $meterReader,
            'data' => $data,
            'period' => $period,
            'towns' => $towns,
            'latestRate' => $latestRate
        ]);
    }

    public function printEfficiencyReport($meterReader, $month, $office) {
        $latestRate = Rates::where('AreaCode', $office)
                ->orderByDesc('ServicePeriod')
                ->first();
        $period = date('Y-m-01', strtotime($month . ' -1 month'));
        $fromPrev = date('Y-m-d', strtotime('first day of ' . $period));
        $toPrev = date('Y-m-d', strtotime('last day of ' . $period));
        $thisMonthFrom = $month;
        $thisMonthTo= date('Y-m-d', strtotime('last day of ' . $month));
        // $thisMonthTo= date('Y-m-d');

        $townData = Towns::find($office);

        if ($meterReader != null) {
            $data = DB::table('Billing_ServiceAccounts')
                ->whereRaw("Town='" . $office . "' AND MeterReader='" . $meterReader . "'")
                ->select('AreaCode',
                    DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ServicePeriod='" . $period . "') AS PeriodNoOfBillsSales"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ServicePeriod='" . $period . "') AS PeriodBillAmountSales"),
                    DB::raw("(SELECT SUM(TRY_CAST(Total AS DECIMAL(10,2))) FROM Cashier_TransactionIndex WHERE Status IS NULL AND AccountNumber IS NOT NULL AND AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ORDate BETWEEN '" . $fromPrev . "' AND '" . $toPrev . "') AS PeriodOthersSales"),

                    DB::raw("(SELECT COUNT(b.id) FROM Cashier_PaidBills pb LEFT JOIN Billing_Bills b ON pb.AccountNumber=b.AccountNumber AND pb.ServicePeriod=b.ServicePeriod 
                        WHERE (pb.Status IS NULL OR pb.Status='Application') AND pb.ServicePeriod='" . $period . "' AND pb.AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND pb.ORDate <= '" . $toPrev . "') AS PeriodNoOfBillsPrevMonthCollection"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(10,2))) FROM Cashier_PaidBills pb LEFT JOIN Billing_Bills b ON pb.AccountNumber=b.AccountNumber AND pb.ServicePeriod=b.ServicePeriod
                         WHERE (pb.Status IS NULL OR pb.Status='Application') AND pb.ServicePeriod='" . $period . "' AND pb.AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND pb.ORDate <= '" . $toPrev . "') AS PeriodAmountPrevMonthCollection"),

                    DB::raw("(SELECT COUNT(b.id) FROM Cashier_PaidBills pb LEFT JOIN Billing_Bills b ON pb.AccountNumber=b.AccountNumber AND pb.ServicePeriod=b.ServicePeriod 
                         WHERE (pb.Status IS NULL OR pb.Status='Application') AND pb.ServicePeriod='" . $period . "' AND pb.AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND pb.ORDate BETWEEN '" . $thisMonthFrom . "' AND '" . $thisMonthTo . "') AS PeriodNoOfBillsCurrentMonthCollection"),
                    DB::raw("(SELECT SUM(TRY_CAST(b.NetAmount AS DECIMAL(10,2))) FROM Cashier_PaidBills pb LEFT JOIN Billing_Bills b ON pb.AccountNumber=b.AccountNumber AND pb.ServicePeriod=b.ServicePeriod
                        WHERE (pb.Status IS NULL OR pb.Status='Application') AND pb.ServicePeriod='" . $period . "' AND pb.AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND pb.ORDate BETWEEN '" . $thisMonthFrom . "' AND '" . $thisMonthTo . "') AS PeriodAmountCurrentMonthCollection"),

                    DB::raw("(SELECT COUNT(id) FROM Cashier_PaidBills WHERE (Status IS NULL OR Status='Application') AND ServicePeriod<'" . $period . "' AND AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ORDate BETWEEN '" . $fromPrev . "' AND '" . $toPrev . "') AS PeriodNoOfBillsArrearsCollected"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Cashier_PaidBills WHERE (Status IS NULL OR Status='Application') AND ServicePeriod<'" . $period . "' AND AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ORDate BETWEEN '" . $fromPrev . "' AND '" . $toPrev . "') AS PeriodAmountArrearsCollected"),
                    DB::raw("(SELECT COUNT(id) FROM Cashier_PaidBills WHERE (Status IS NULL OR Status='Application') AND ServicePeriod='" . $month . "' AND AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ORDate BETWEEN '" . $month . "' AND '" . $thisMonthTo . "') AS CurrentNoOfBillsSales"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Cashier_PaidBills WHERE (Status IS NULL OR Status='Application') AND ServicePeriod='" . $month . "' AND AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ORDate BETWEEN '" . $month . "' AND '" . $thisMonthTo . "') AS CurrentAmountSales"),
                    DB::raw("(SELECT SUM(TRY_CAST(Total AS DECIMAL(10,2))) FROM Cashier_TransactionIndex WHERE (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL AND AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') 
                        AND ORDate BETWEEN '" . $month . "' AND '" . $thisMonthTo . "') AS CurrentOthersSales"),
                    // DB::raw("(SELECT COUNT(id) FROM Disconnection_History WHERE Status='DISCONNECTED' AND ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND AccountNumber IN
                    //     (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') AND AccountNumber NOT IN 
                    //     (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND AccountNumber=Disconnection_History.AccountNumber 
                    //         AND (Status IS NULL OR Status='Application') AND ORDate <= '" . $thisMonthTo . "')) AS DiscoCount"),
                    DB::raw("(SELECT COUNT(id) FROM Billing_Bills WHERE ServicePeriod='" . $period . "' AND (AccountNumber IN
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "' AND sa.AccountStatus='DISCONNECTED') OR AccountNumber IN
                        (SELECT AccountNumber FROM Disconnection_History WHERE Status='DISCONNECTED' AND ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND AccountNumber IN 
                            (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "'))) AND AccountNumber NOT IN 
                        (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND AccountNumber=Billing_Bills.AccountNumber 
                            AND (Status IS NULL OR Status='Application') AND ORDate <= '" . $thisMonthTo . "')) AS DiscoCount"),
                    // DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills b LEFT JOIN Disconnection_History d ON b.AccountNumber=d.AccountNumber AND b.ServicePeriod=d.ServicePeriod 
                    //     WHERE d.Status='DISCONNECTED' AND d.ServicePeriod='" . $period . "' AND d.AccountNumber IS NOT NULL AND d.AccountNumber IN
                    //     (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') AND b.AccountNumber NOT IN 
                    //     (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND AccountNumber=b.AccountNumber 
                    //     AND (Status IS NULL OR Status='Application')  AND ORDate <= '" . $thisMonthTo . "')) AS DiscoAmount"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE ServicePeriod='" . $period . "' AND (AccountNumber IN
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "' AND sa.AccountStatus='DISCONNECTED') OR AccountNumber IN
                        (SELECT AccountNumber FROM Disconnection_History WHERE Status='DISCONNECTED' AND ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND AccountNumber IN 
                            (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "'))) AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND AccountNumber=Billing_Bills.AccountNumber 
                                AND (Status IS NULL OR Status='Application') AND ORDate <= '" . $thisMonthTo . "')) AS DiscoAmount"),
                    DB::raw("(SELECT COUNT(DISTINCT AccountNumber) FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL AND AccountNumber IN
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') AND AccountNumber NOT IN 
                        (SELECT pb.AccountNumber FROM Cashier_PaidBills pb WHERE pb.AccountNumber IS NOT NULL AND pb.ServicePeriod='" . $period . "' AND pb.AccountNumber=Billing_Excemptions.AccountNumber AND (pb.Status IS NULL OR pb.Status='Application') AND ORDate <= '" . $thisMonthTo . "')) AS AdjustmentCount"),
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(10,2))) FROM Billing_Bills WHERE AccountNumber IN 
                        (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL AND AccountNumber IN
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "') AND AccountNumber NOT IN 
                        (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND AccountNumber=Billing_Excemptions.AccountNumber AND (Status IS NULL OR Status='Application') AND ORDate <= '" . $thisMonthTo . "')) 
                        AND ServicePeriod='" . $period . "') AS AdjustmentAmount"),

                )
                ->groupBy('AreaCode')
                ->orderBy('AreaCode')
                ->get();
        } else {
            $data = [];
        } 

        $mreader = Users::find($meterReader);

        return view('/readings/print_efficiency_report', [
            'month' => $month,
            'office' => $office,
            'meterReader' => $meterReader,
            'data' => $data,
            'period' => $period,
            'mreader' => $mreader,
            'townData' => $townData,
            'latestRate' => $latestRate
        ]);
    }

    public function getMeterReaders(Request $request) {
        $town = $request['Town'];

        if ($town=='All') {
            $meterReaders = DB::table('Billing_ServiceAccounts')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->whereNotNull('MeterReader')
                ->select('Billing_ServiceAccounts.MeterReader', 'users.name')
                ->groupBy('Billing_ServiceAccounts.MeterReader', 'users.name')
                ->orderBy('users.name')
                ->get();
        } else {
            $meterReaders = DB::table('Billing_ServiceAccounts')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->where('Billing_ServiceAccounts.Town', $town)
                ->whereNotNull('MeterReader')
                ->select('Billing_ServiceAccounts.MeterReader', 'users.name')
                ->groupBy('Billing_ServiceAccounts.MeterReader', 'users.name')
                ->orderBy('users.name')
                ->get();
        }

        return response()->json($meterReaders, 200);
    }

    public function printBulkBillNewFormatMreader($period, $day, $town, $mreader) {
        $bills = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Billing_Bills.ServicePeriod', $period)
            ->where('Billing_ServiceAccounts.Town', $town)
            ->where('Billing_ServiceAccounts.GroupCode', $day)
            ->where('Billing_ServiceAccounts.MeterReader', $mreader)
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

    public function printBulkBillOldFormatMreader($period, $day, $town, $mreader) {
        $bills = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->where('Billing_Bills.ServicePeriod', $period)
            ->where('Billing_ServiceAccounts.Town', $town)
            ->where('Billing_ServiceAccounts.GroupCode', $day)
            ->where('Billing_ServiceAccounts.MeterReader', $mreader)
            ->select('Billing_Bills.*')
            ->orderBy('Billing_Bills.BillNumber')
            ->get();

        return view('/bills/print_bulk_old_format', [
            'bills' => $bills
        ]);
    }

    public function printGroupReadingList($memConsumerId, $period) {
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
            ->where('CRM_MemberConsumers.Id', $memConsumerId)
            ->first();

        $prevMonth = date('Y-m-01', strtotime($period . ' -1 month'));

        $accounts = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Billing_ServiceAccounts.MemberConsumerId', $memConsumerId)
            ->whereIn('Billing_ServiceAccounts.AccountStatus', ['ACTIVE', 'DISCONNECTED'])
            ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Readings WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
            ->where(function ($query) {
                $query->where(function($queryX) {
                        $queryX->where('Billing_ServiceAccounts.AccountExpiration', '>', date('Y-m-d'))
                            ->where('Billing_ServiceAccounts.AccountRetention', 'Temporary');
                    })
                    ->orWhere('Billing_ServiceAccounts.AccountRetention', 'Permanent')
                    ->orWhereNull('Billing_ServiceAccounts.AccountExpiration');
            })
            ->select('Billing_ServiceAccounts.id', 
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.Multiplier',
                'Billing_ServiceAccounts.AccountType',
                'Billing_ServiceAccounts.AccountStatus',
                'Billing_ServiceAccounts.AreaCode',
                'Billing_ServiceAccounts.GroupCode',
                'Billing_ServiceAccounts.OldAccountNo',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'Billing_ServiceAccounts.Purok',
                DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Readings WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) AS PreviousKwhUsed"),
            )
            ->orderBy('Billing_ServiceAccounts.AreaCode')
            ->orderBy('Billing_ServiceAccounts.OldAccountNo')
            ->get();

        return view('/readings/print_group_reading_list', [
            'memberConsumer' => $memberConsumer,
            'period' => $period,
            'accounts' => $accounts,
        ]);
    }

    public function erroneousReading(Request $request) {
        $period = $request['ServicePeriod'];
        $town = $request['Town'];
        $count = $request['Count'];

        if ($town == 'All') {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->where('ServicePeriod', $period)
                ->select('Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.*')
                ->orderByRaw("TRY_CAST(KwhUsed AS DECIMAL(15,2)) DESC")
                ->limit($count)
                ->get();
        } else {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->where('Billing_ServiceAccounts.Town', $town)
                ->where('ServicePeriod', $period)
                ->select('Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.*')
                ->orderByRaw("TRY_CAST(KwhUsed AS DECIMAL(15,2)) DESC")
                ->limit($count)
                ->get();
        }

        $billingMonths = DB::table('Billing_Readings')
            ->whereNotNull('ServicePeriod')
            ->select('ServicePeriod')
            ->groupBy('ServicePeriod')
            ->orderByDesc('ServicePeriod')
            ->get();
        $towns = Towns::all();

        return view('/readings/erroneous_readings', [
            'billingMonths' => $billingMonths,
            'towns' => $towns,
            'data' => $data,
        ]);
    }

    public function abruptIncreaseDecrease() {
        $billingMonths = DB::table('Billing_Readings')
            ->whereNotNull('ServicePeriod')
            ->select('ServicePeriod')
            ->groupBy('ServicePeriod')
            ->orderByDesc('ServicePeriod')
            ->get();
        $towns = Towns::all();

        return view('/readings/abrupt_increase_decrease', [
            'billingMonths' => $billingMonths,
            'towns' => $towns,
        ]);
    }

    public function analyzeAbruptIncreaseDecrease(Request $request) {
        set_time_limit(300);

        $town = $request['Town'];
        $period = $request['ServicePeriod'];
        $direction = $request['Direction'];
        $percentage = $request['Percent'];
        $percentage = floatval($percentage)/100;
        $prevMonth = date('Y-m-01', strtotime($period . ' -1 month'));

        if ($town == 'All') {
            if ($direction == 'Increase') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("ServicePeriod='" . $period . "' AND TRY_CAST(KwhUsed AS DECIMAL(15,2)) > 0")
                    ->whereRaw("(SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) > 0")
                    ->whereRaw("(TRY_CAST(KwhUsed AS DECIMAL(15,2)) - (SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id)) > TRY_CAST(KwhUsed AS DECIMAL(15,2))*'" . $percentage . "'")
                    ->select('Billing_Bills.*',
                        DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) AS PrevConsumption"),
                        DB::raw("(SELECT TOP 1 id FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) AS PrevId"),
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'Billing_ServiceAccounts.AccountType',
                        'Billing_ServiceAccounts.AccountStatus',
                        'CRM_Barangays.Barangay',
                        'CRM_Towns.Town',
                    )
                    ->orderByRaw("((TRY_CAST(KwhUsed AS DECIMAL(15,2)) - (SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id)) / TRY_CAST(KwhUsed AS DECIMAL(15,2))) DESC")
                    ->get();
            } else {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("ServicePeriod='" . $period . "' AND TRY_CAST(KwhUsed AS DECIMAL(15,2)) > 0")
                    ->whereRaw("(SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) > 0")
                    ->whereRaw("((SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) - TRY_CAST(KwhUsed AS DECIMAL(15,2))) > TRY_CAST(KwhUsed AS DECIMAL(15,2))*'" . $percentage . "'")
                    ->select('Billing_Bills.*',
                        DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) AS PrevConsumption"),
                        DB::raw("(SELECT TOP 1 id FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) AS PrevId"),
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'Billing_ServiceAccounts.AccountType',
                        'Billing_ServiceAccounts.AccountStatus',
                        'CRM_Barangays.Barangay',
                        'CRM_Towns.Town',
                    )
                    ->orderByRaw("(TRY_CAST((SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) AS DECIMAL(15,2)) - TRY_CAST(KwhUsed AS DECIMAL(15,2))) / TRY_CAST(KwhUsed AS DECIMAL(15,2)) DESC")
                    ->get();
            }
        } else {
            if ($direction == 'Increase') {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("ServicePeriod='" . $period . "' AND TRY_CAST(KwhUsed AS DECIMAL(15,2)) > 0 AND Billing_ServiceAccounts.Town='" . $town . "'")
                    ->whereRaw("(SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) > 0")
                    ->whereRaw("(TRY_CAST(KwhUsed AS DECIMAL(15,2)) - (SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id)) > TRY_CAST(KwhUsed AS DECIMAL(15,2))*'" . $percentage . "'")
                    ->select('Billing_Bills.*',
                        DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) AS PrevConsumption"),
                        DB::raw("(SELECT TOP 1 id FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) AS PrevId"),
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'Billing_ServiceAccounts.AccountType',
                        'Billing_ServiceAccounts.AccountStatus',
                        'CRM_Barangays.Barangay',
                        'CRM_Towns.Town',
                    )
                    ->orderByRaw("((TRY_CAST(KwhUsed AS DECIMAL(15,2)) - (SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id)) / TRY_CAST(KwhUsed AS DECIMAL(15,2))) DESC")
                    ->get();
            } else {
                $data = DB::table('Billing_Bills')
                    ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->whereRaw("ServicePeriod='" . $period . "' AND TRY_CAST(KwhUsed AS DECIMAL(15,2)) > 0 AND Billing_ServiceAccounts.Town='" . $town . "'")
                    ->whereRaw("(SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) > 0")
                    ->whereRaw("((SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) - TRY_CAST(KwhUsed AS DECIMAL(15,2))) > TRY_CAST(KwhUsed AS DECIMAL(15,2))*'" . $percentage . "'")
                    ->select('Billing_Bills.*',
                        DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) AS PrevConsumption"),
                        DB::raw("(SELECT TOP 1 id FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) AS PrevId"),
                        'Billing_ServiceAccounts.OldAccountNo',
                        'Billing_ServiceAccounts.ServiceAccountName',
                        'Billing_ServiceAccounts.Purok',
                        'Billing_ServiceAccounts.AccountType',
                        'Billing_ServiceAccounts.AccountStatus',
                        'CRM_Barangays.Barangay',
                        'CRM_Towns.Town',
                    )
                    ->orderByRaw("(TRY_CAST((SELECT TOP 1 TRY_CAST(KwhUsed AS DECIMAL(15,2)) FROM Billing_Bills WHERE ServicePeriod='" . $prevMonth . "' AND AccountNumber=Billing_ServiceAccounts.id) AS DECIMAL(15,2)) - TRY_CAST(KwhUsed AS DECIMAL(15,2))) / TRY_CAST(KwhUsed AS DECIMAL(15,2)) DESC")
                    ->get();
            }
        }

        $output = "";
        $i = 0;
        foreach($data as $item) {
            if (floatval($item->PrevConsumption) > 0) {
                if ($direction == 'Increase') {
                    $output .= "<tr>
                                    <td>" . ($i+1) . "</td>
                                    <td><a href='" . route('serviceAccounts.show', [$item->AccountNumber]) . "'>" . $item->OldAccountNo . "</a></td>
                                    <td>" . $item->ServiceAccountName . "</td>
                                    <td>" . ServiceAccounts::getAddress($item) . "</td>
                                    <td>" . $item->AccountStatus . "</td>
                                    <td class='text-right'><a href='" . route('bills.show', [$item->PrevId]) . "'>" . number_format($item->PrevConsumption, 2) . "</a></td>
                                    <td class='text-right'><a href='" . route('bills.show', [$item->id]) . "'>" . number_format($item->KwhUsed, 2) . "</a></td>
                                    <td class='text-right text-danger  '>" . number_format(IDGenerator::getDifference($item->KwhUsed, $item->PrevConsumption), 2) . "</td>
                                    <th class='text-right text-danger'><i class='fas fa-caret-up ico-tab-mini'></i>" . number_format(IDGenerator::getPercentage(IDGenerator::getDifference($item->KwhUsed, $item->PrevConsumption), $item->KwhUsed), 2) . " %</th>
                                </tr>";
                } else {
                    $output .= "<tr>
                                    <td>" . ($i+1) . "</td>
                                    <td><a href='" . route('serviceAccounts.show', [$item->AccountNumber]) . "'>" . $item->OldAccountNo . "</a></td>
                                    <td>" . $item->ServiceAccountName . "</td>
                                    <td>" . ServiceAccounts::getAddress($item) . "</td>
                                    <td>" . $item->AccountStatus . "</td>
                                    <td class='text-right'><a href='" . route('bills.show', [$item->PrevId]) . "'>" . number_format($item->PrevConsumption, 2) . "</a></td>
                                    <td class='text-right'><a href='" . route('bills.show', [$item->id]) . "'>" . number_format($item->KwhUsed, 2) . "</a></td>
                                    <td class='text-right text-danger  '>" . number_format(IDGenerator::getDifference($item->PrevConsumption, $item->KwhUsed), 2) . "</td>
                                    <th class='text-right text-success'><i class='fas fa-caret-down ico-tab-mini'></i>" . number_format(IDGenerator::getPercentage(IDGenerator::getDifference($item->PrevConsumption, $item->KwhUsed), $item->KwhUsed), 2) . " %</th>
                                </tr>";
                }
                

                $i++;
            }            
        }

        return response()->json($output, 200);
    }

    public function showExcemptionsPerRoute(Request $request) {
        $meterReader = $request['MeterReader'];
        $route = $request['Route'];
        $month = $request['Month'];
        $period = date('Y-m-01', strtotime($month . ' -1 month')); // previous
        $thisMonthTo= date('Y-m-d', strtotime('last day of ' . $month));

        $data = DB::table('Billing_Excemptions')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Excemptions.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->whereRaw("Billing_ServiceAccounts.MeterReader='" . $meterReader . "' AND Billing_ServiceAccounts.AreaCode='" . $route . "'")
            ->whereRaw("AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' 
                AND (Status IS NULL OR Status='Application') AND ORDate <= '" . $thisMonthTo . "')")
            ->select(
                DB::raw("DISTINCT Billing_ServiceAccounts.id"),
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.Purok',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                DB::raw("(SELECT NetAmount FROM Billing_Bills WHERE AccountNumber=Billing_Excemptions.AccountNumber AND ServicePeriod='" . $period . "') AS NetAmount")
            )
            ->get();
        
        $output = "";
        $i = 1;
        foreach($data as $item) {
            $output .= "<tr>
                            <td>" . $i . "</td>
                            <td><a href='" . route('serviceAccounts.show', [$item->id]) . "'>" . $item->OldAccountNo . "</a></td>
                            <td>" . $item->ServiceAccountName . "</td>
                            <td>" . ServiceAccounts::getAddress($item) . "</td>
                            <td>" . (is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : 0) . "</td>
                        </tr>";
            $i++;
        }

        return response()->json($output, 200);
    }

    public function showDisconnectedPerRoute(Request $request) {
        $meterReader = $request['MeterReader'];
        $route = $request['Route'];
        $month = $request['Period'];
        $period = date('Y-m-01', strtotime($month . ' -1 month')); // previous
        $thisMonthFrom = $month;
        $thisMonthTo= date('Y-m-d', strtotime('last day of ' . $month));

        $data = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->whereRaw("Billing_ServiceAccounts.MeterReader='" . $meterReader . "' AND Billing_ServiceAccounts.AreaCode='" . $route . "'AND Billing_Bills.ServicePeriod='" . $period . "' AND 
                    (Billing_ServiceAccounts.AccountStatus='DISCONNECTED' OR Billing_Bills.AccountNumber IN
                    (SELECT AccountNumber FROM Disconnection_History WHERE Status='DISCONNECTED' AND ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "'))) AND Billing_Bills.AccountNumber NOT IN 
                    (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application')
                        AND ORDate <= '" . $thisMonthTo . "')")
            ->select(
                'Billing_ServiceAccounts.id',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.Purok',
                'Billing_ServiceAccounts.AccountStatus',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'Billing_Bills.NetAmount',
            )
            ->get();
        
        $output = "";
        $i = 1;
        foreach($data as $item) {
            $output .= "<tr>
                            <td>" . $i . "</td>
                            <td><a href='" . route('serviceAccounts.show', [$item->id]) . "'>" . $item->OldAccountNo . "</a></td>
                            <td>" . $item->ServiceAccountName . "</td>
                            <td>" . $item->AccountStatus . "</td>
                            <td>" . ServiceAccounts::getAddress($item) . "</td>
                            <td>" . (is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : 0) . "</td>
                        </tr>";
            $i++;
        }

        return response()->json($output, 200);
    }

    public function showOutstandingPerRoute(Request $request) {
        $meterReader = $request['MeterReader'];
        $route = $request['Route'];
        $month = $request['Period'];
        $period = date('Y-m-01', strtotime($month . ' -1 month')); // previous
        $thisMonthFrom = $month;
        $thisMonthTo= date('Y-m-d', strtotime('last day of ' . $month));

        $data = DB::table('Billing_Bills')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('Cashier_PaidBills', function($join) {
                $join->on('Cashier_PaidBills.AccountNumber', '=', 'Billing_Bills.AccountNumber')
                    ->on('Cashier_PaidBills.ServicePeriod', '=', 'Billing_Bills.ServicePeriod');
            })
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->whereRaw("(Cashier_PaidBills.Status IS NULL OR Cashier_PaidBills.Status='Application') AND Billing_Bills.ServicePeriod='" . $period . "' AND Billing_ServiceAccounts.AccountStatus IN ('ACTIVE', 'PULLOUT') AND Billing_ServiceAccounts.MeterReader='" . $meterReader . "' AND Billing_ServiceAccounts.AreaCode='" . $route . "' 
                    AND Billing_ServiceAccounts.id NOT IN 
                        (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application') AND ORDate <= '" . $thisMonthTo . "')     
                    AND Billing_ServiceAccounts.id NOT IN ((SELECT AccountNumber FROM Disconnection_History WHERE Status='DISCONNECTED' AND ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND AccountNumber IN 
                        (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "')))
                    AND Billing_ServiceAccounts.id NOT IN 
                        (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL AND AccountNumber NOT IN
                            (SELECT pb.AccountNumber FROM Cashier_PaidBills pb WHERE pb.AccountNumber IS NOT NULL AND pb.ServicePeriod='" . $period . "' AND pb.AccountNumber=Billing_Excemptions.AccountNumber 
                                AND (pb.Status IS NULL OR pb.Status='Application') AND ORDate <= '" . $thisMonthTo . "'))")
            ->select(
                'Billing_ServiceAccounts.id AS AccountNumber',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.Purok',
                'Billing_ServiceAccounts.AccountStatus',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'Billing_Bills.NetAmount',
                'Billing_Bills.id'
            )
            ->get();

        // $data = DB::table('Billing_Bills')
        //     ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
        //     ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
        //     ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
        //     ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND Billing_ServiceAccounts.AccountStatus='ACTIVE' AND Billing_ServiceAccounts.MeterReader='" . $meterReader . "' AND Billing_ServiceAccounts.AreaCode='" . $route . "' 
        //             AND Billing_ServiceAccounts.id NOT IN ((SELECT AccountNumber FROM Disconnection_History WHERE Status='DISCONNECTED' AND ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND AccountNumber IN 
        //                 (SELECT sa.id FROM Billing_ServiceAccounts sa WHERE sa.AreaCode=Billing_ServiceAccounts.AreaCode AND sa.MeterReader='" . $meterReader . "')))
        //             AND Billing_ServiceAccounts.id NOT IN 
        //                 (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application')) AND Billing_ServiceAccounts.id NOT IN 
        //                 (SELECT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL AND AccountNumber NOT IN
        //                     (SELECT pb.AccountNumber FROM Cashier_PaidBills pb WHERE pb.AccountNumber IS NOT NULL AND pb.ServicePeriod='" . $period . "' AND pb.AccountNumber=Billing_Excemptions.AccountNumber 
        //                         AND (pb.Status IS NULL OR pb.Status='Application') AND ORDate <= '" . $thisMonthTo . "'))")
        //     ->select(
        //         'Billing_ServiceAccounts.id AS AccountNumber',
        //         'Billing_ServiceAccounts.ServiceAccountName',
        //         'Billing_ServiceAccounts.OldAccountNo',
        //         'Billing_ServiceAccounts.Purok',
        //         'Billing_ServiceAccounts.AccountStatus',
        //         'CRM_Towns.Town',
        //         'CRM_Barangays.Barangay',
        //         'Billing_Bills.NetAmount',
        //         'Billing_Bills.id'
        //     )
        //     ->get();
        
        $output = "";
        $i=1;
        foreach($data as $item) {
            $output .= "<tr>
                            <td>" . $i . "</td>
                            <td><a href='" . route('serviceAccounts.show', [$item->AccountNumber]) . "'>" . $item->OldAccountNo . "</a></td>
                            <td>" . $item->ServiceAccountName . "</td>
                            <td>" . ServiceAccounts::getAddress($item) . "</td>
                            <td>" . $item->AccountStatus . "</td>
                            <td>" . (is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : 0) . "</td>
                        </tr>";
            $i++;           
        }

        return response()->json($output, 200);
    }

    public function discoPerMeterReader(Request $request) {
        $period = $request['ServicePeriod'];
        $meterReader = $request['MeterReader'];
        $dateTo = date('Y-m-d', strtotime('last day of ' . $period));

        if ($meterReader == 'All') {
            $data = DB::table('Disconnection_History')
                ->leftJoin('Billing_ServiceAccounts', 'Disconnection_History.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('Billing_Bills', function($join) {
                    $join->on('Billing_Bills.ServicePeriod', '=', 'Disconnection_History.ServicePeriod')
                        ->on('Billing_Bills.AccountNumber', '=', 'Disconnection_History.AccountNumber');
                })
                ->leftJoin('users', 'Disconnection_History.UserId', '=', 'users.id')
                ->whereRaw("Disconnection_History.ServicePeriod='" . $period . "' AND Disconnection_History.Status='DISCONNECTED'")
                ->select('OldAccountNo',
                    'ServiceAccountName', 
                    'AccountStatus',
                    'Disconnection_History.*',
                    'Billing_Bills.NetAmount',
                    'users.name',
                    DB::raw("(SELECT TOP 1 DateDisconnected FROM Disconnection_History WHERE AccountNumber=Billing_ServiceAccounts.id AND Status='RECONNECTED') AS DateReconnected"), 
                )
                ->orderBy('OldAccountNo')
                ->get();
        } else {
            $data = DB::table('Disconnection_History')
                ->leftJoin('Billing_ServiceAccounts', 'Disconnection_History.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('Billing_Bills', function($join) {
                    $join->on('Billing_Bills.ServicePeriod', '=', 'Disconnection_History.ServicePeriod')
                        ->on('Billing_Bills.AccountNumber', '=', 'Disconnection_History.AccountNumber');
                })
                ->leftJoin('users', 'Disconnection_History.UserId', '=', 'users.id')
                ->whereRaw("Disconnection_History.ServicePeriod='" . $period . "' AND Disconnection_History.UserId='" . $meterReader . "'  AND Disconnection_History.Status='DISCONNECTED'")
                ->select('OldAccountNo',
                    'ServiceAccountName', 
                    'AccountStatus',
                    'Disconnection_History.*',
                    'Billing_Bills.NetAmount',
                    'users.name',
                    DB::raw("(SELECT TOP 1 DateDisconnected FROM Disconnection_History WHERE AccountNumber=Billing_ServiceAccounts.id AND Status='RECONNECTED') AS DateReconnected")    
                )
                ->orderBy('OldAccountNo')
                ->get();
        }

        return view('/readings/disco_per_mreader', [
            'meterReaders' => User::role('Meter Reader Inhouse')->orderBy('name')->get(),
            'data' => $data,
        ]);
    }

    public function printDiscoPerMeterReader($period, $meterReader) {
        $dateTo = date('Y-m-d', strtotime('last day of ' . $period));

        if ($meterReader == 'All') {
            $data = DB::table('Disconnection_History')
                ->leftJoin('Billing_ServiceAccounts', 'Disconnection_History.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('Billing_Bills', function($join) {
                    $join->on('Billing_Bills.ServicePeriod', '=', 'Disconnection_History.ServicePeriod')
                        ->on('Billing_Bills.AccountNumber', '=', 'Disconnection_History.AccountNumber');
                })
                ->leftJoin('users', 'Disconnection_History.UserId', '=', 'users.id')
                ->whereRaw("Disconnection_History.ServicePeriod='" . $period . "' AND Disconnection_History.Status='DISCONNECTED'")
                ->select('OldAccountNo',
                    'ServiceAccountName', 
                    'AccountStatus',
                    'Disconnection_History.*',
                    'Billing_Bills.NetAmount',
                    'users.name',
                    DB::raw("(SELECT TOP 1 DateDisconnected FROM Disconnection_History WHERE AccountNumber=Billing_ServiceAccounts.id AND Status='RECONNECTED') AS DateReconnected"), 
                )
                ->orderBy('OldAccountNo')
                ->get();
            
            $mreader = 'All';
        } else {
            $data = DB::table('Disconnection_History')
                ->leftJoin('Billing_ServiceAccounts', 'Disconnection_History.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('Billing_Bills', function($join) {
                    $join->on('Billing_Bills.ServicePeriod', '=', 'Disconnection_History.ServicePeriod')
                        ->on('Billing_Bills.AccountNumber', '=', 'Disconnection_History.AccountNumber');
                })
                ->leftJoin('users', 'Disconnection_History.UserId', '=', 'users.id')
                ->whereRaw("Disconnection_History.ServicePeriod='" . $period . "' AND Disconnection_History.UserId='" . $meterReader . "'  AND Disconnection_History.Status='DISCONNECTED'")
                ->select('OldAccountNo',
                    'ServiceAccountName', 
                    'AccountStatus',
                    'Disconnection_History.*',
                    'Billing_Bills.NetAmount',
                    'users.name',
                    DB::raw("(SELECT TOP 1 DateDisconnected FROM Disconnection_History WHERE AccountNumber=Billing_ServiceAccounts.id AND Status='RECONNECTED') AS DateReconnected")    
                )
                ->orderBy('OldAccountNo')
                ->get();

            $mreader = Users::find($meterReader)->name;
        }

        return view('/readings/print_disco_per_mreader', [
            'meterReader' => $mreader,
            'period' => $period,
            'data' => $data,
        ]);
    }

    public function uncollectedPerMeterReader(Request $request) {
        $period = $request['ServicePeriod'];
        $meterReader = $request['MeterReader'];
        $dateFrom = date('Y-m-22', strtotime($period)); // UNTIL THE NEXT BILLING CYCLE
        $dateTo = date('Y-m-21', strtotime($period . ' +1 month')); // UNTIL THE NEXT BILLING CYCLE

        if ($meterReader == 'All') {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id') 
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->whereRaw("ServicePeriod='" . $period . "' AND Billing_Bills.AccountNumber NOT IN 
                    (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber=Billing_Bills.AccountNumber
                        AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->select(
                    'OldAccountNo',
                    'ServiceAccountName',
                    'Purok',
                    'AccountStatus',
                    'CRM_Towns.Town',
                    'users.name',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.*'
                )
                ->get();
        } else {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id') 
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->whereRaw("ServicePeriod='" . $period . "' AND Billing_ServiceAccounts.MeterReader='" . $meterReader . "' AND Billing_Bills.AccountNumber NOT IN 
                    (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber=Billing_Bills.AccountNumber
                        AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->select(
                    'OldAccountNo',
                    'ServiceAccountName',
                    'Purok',
                    'AccountStatus',
                    'CRM_Towns.Town',
                    'users.name',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.*'
                )
                ->get();
        }

        return view('/readings/uncollected_per_mreader', [
            'meterReaders' => User::role('Meter Reader Inhouse')->orderBy('name')->get(),
            'data' => $data,
        ]);
    }

    public function printUncollectedPerMeterReader($period, $meterReader) {
        $dateFrom = date('Y-m-22', strtotime($period)); // UNTIL THE NEXT BILLING CYCLE
        $dateTo = date('Y-m-21', strtotime($period . ' +1 month')); // UNTIL THE NEXT BILLING CYCLE

        if ($meterReader == 'All') {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id') 
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->whereRaw("ServicePeriod='" . $period . "' AND Billing_Bills.AccountNumber NOT IN 
                    (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber=Billing_Bills.AccountNumber
                        AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->select(
                    'OldAccountNo',
                    'ServiceAccountName',
                    'Purok',
                    'AccountStatus',
                    'CRM_Towns.Town',
                    'users.name',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.*'
                )
                ->get();
            
            $mreader = 'All';
        } else {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id') 
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->whereRaw("ServicePeriod='" . $period . "' AND Billing_ServiceAccounts.MeterReader='" . $meterReader . "' AND Billing_Bills.AccountNumber NOT IN 
                    (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber=Billing_Bills.AccountNumber
                        AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->select(
                    'OldAccountNo',
                    'ServiceAccountName',
                    'Purok',
                    'AccountStatus',
                    'CRM_Towns.Town',
                    'users.name',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.*'
                )
                ->get();

            $mreader = Users::find($meterReader)->name;
        }

        return view('/readings/print_uncollected_per_mreader', [
            'meterReader' => $mreader,
            'period' => $period,
            'data' => $data,
        ]);
    }

    public function excemptionsPerMeterReader(Request $request) {
        $period = $request['ServicePeriod'];
        $meterReader = $request['MeterReader'];

        if ($meterReader == 'All') {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND AccountNumber IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->select(
                    'OldAccountNo',
                    'ServiceAccountName',
                    'Purok',
                    'AccountStatus',
                    'CRM_Towns.Town',
                    'users.name',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.*'
                )
                ->get();
        } else {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND AccountNumber IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL) AND 
                    MeterReader='" . $meterReader . "'")
                ->select(
                    'OldAccountNo',
                    'ServiceAccountName',
                    'Purok',
                    'AccountStatus',
                    'CRM_Towns.Town',
                    'users.name',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.*'
                )
                ->get();
        }        

        return view('/readings/excemptions_per_mreader', [
            'data' => $data,
            'meterReaders' => User::role('Meter Reader Inhouse')->orderBy('name')->get(),
        ]);
    }

    public function printExcemptionsPerMeterReader($period, $meterReader) {
        $dateFrom = date('Y-m-22', strtotime($period)); // UNTIL THE NEXT BILLING CYCLE
        $dateTo = date('Y-m-21', strtotime($period . ' +1 month')); // UNTIL THE NEXT BILLING CYCLE

        if ($meterReader == 'All') {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND AccountNumber IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->select(
                    'OldAccountNo',
                    'ServiceAccountName',
                    'Purok',
                    'AccountStatus',
                    'CRM_Towns.Town',
                    'users.name',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.*'
                )
                ->get();
            
            $mreader = 'All';
        } else {
            $data = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_Bills.ServicePeriod='" . $period . "' AND AccountNumber IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL) AND 
                    MeterReader='" . $meterReader . "'")
                ->select(
                    'OldAccountNo',
                    'ServiceAccountName',
                    'Purok',
                    'AccountStatus',
                    'CRM_Towns.Town',
                    'users.name',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.*'
                )
                ->get();

            $mreader = Users::find($meterReader)->name;
        }

        return view('/readings/print_excemptions_per_mreader', [
            'meterReader' => $mreader,
            'period' => $period,
            'data' => $data,
        ]);
    }

    public function discoPerBapa(Request $request) {
        $bapa = $request['Bapa'];
        $period = $request['ServicePeriod'];
        $dateTo = date('Y-m-d', strtotime('last day of ' . $period));

        $bapas = DB::table('Billing_ServiceAccounts')
            ->select('OrganizationParentAccount')
            ->whereNotNull('OrganizationParentAccount')
            ->groupBy('OrganizationParentAccount')
            ->orderBy('OrganizationParentAccount')
            ->get();

        if ($bapa == 'All') {
            $data = DB::table('Disconnection_History')
                ->leftJoin('Billing_ServiceAccounts', 'Disconnection_History.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('Billing_Bills', function($join) {
                    $join->on('Billing_Bills.ServicePeriod', '=', 'Disconnection_History.ServicePeriod')
                        ->on('Billing_Bills.AccountNumber', '=', 'Disconnection_History.AccountNumber');
                })
                ->whereRaw("Disconnection_History.ServicePeriod='" . $period . "' AND Disconnection_History.Status='DISCONNECTED' AND OrganizationParentAccount IS NOT NULL")
                ->select('OldAccountNo',
                    'ServiceAccountName', 
                    'AccountStatus',
                    'OrganizationParentAccount',
                    'Disconnection_History.*',
                    'Billing_Bills.NetAmount',
                    DB::raw("(SELECT TOP 1 DateDisconnected FROM Disconnection_History WHERE AccountNumber=Billing_ServiceAccounts.id AND Status='RECONNECTED') AS DateReconnected"), 
                )
                ->orderBy('OldAccountNo')
                ->get();
        } else {
            $data = DB::table('Disconnection_History')
                ->leftJoin('Billing_ServiceAccounts', 'Disconnection_History.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('Billing_Bills', function($join) {
                    $join->on('Billing_Bills.ServicePeriod', '=', 'Disconnection_History.ServicePeriod')
                        ->on('Billing_Bills.AccountNumber', '=', 'Disconnection_History.AccountNumber');
                })
                ->whereRaw("Disconnection_History.ServicePeriod='" . $period . "'  AND Disconnection_History.Status='DISCONNECTED' AND OrganizationParentAccount='" . $bapa . "'")
                ->select('OldAccountNo',
                    'ServiceAccountName', 
                    'AccountStatus',
                    'OrganizationParentAccount',
                    'Disconnection_History.*',
                    'Billing_Bills.NetAmount',
                    DB::raw("(SELECT TOP 1 DateDisconnected FROM Disconnection_History WHERE AccountNumber=Billing_ServiceAccounts.id AND Status='RECONNECTED') AS DateReconnected")    
                )
                ->orderBy('OldAccountNo')
                ->get();
        }

        return view('/readings/disco_per_bapa', [
            'bapas' => $bapas,
            'data' => $data,
        ]);
    }

    public function getPreviousReading(Request $request) {
        $accountNumber = $request['AccountNumber'];
        $period = $request['Period'];

        $reading = Readings::where('AccountNumber', $accountNumber)
            ->whereRaw("ServicePeriod < '" . $period . "'")
            ->orderByDesc('ServicePeriod')
            ->first();

        return response()->json($reading, 200);
    }
}
