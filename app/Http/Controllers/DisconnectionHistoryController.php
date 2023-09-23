<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDisconnectionHistoryRequest;
use App\Http\Requests\UpdateDisconnectionHistoryRequest;
use App\Repositories\DisconnectionHistoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Towns;
use App\Models\ServiceAccounts;
use App\Models\MeterReaders;
use App\Models\User;
use App\Models\Users;
use App\Models\IDGenerator;
use App\Models\Bills;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class DisconnectionHistoryController extends AppBaseController
{
    /** @var  DisconnectionHistoryRepository */
    private $disconnectionHistoryRepository;

    public function __construct(DisconnectionHistoryRepository $disconnectionHistoryRepo)
    {
        $this->middleware('auth');
        $this->disconnectionHistoryRepository = $disconnectionHistoryRepo;
    }

    /**
     * Display a listing of the DisconnectionHistory.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $disconnectionHistories = $this->disconnectionHistoryRepository->all();

        return view('disconnection_histories.index')
            ->with('disconnectionHistories', $disconnectionHistories);
    }

    /**
     * Show the form for creating a new DisconnectionHistory.
     *
     * @return Response
     */
    public function create()
    {
        return view('disconnection_histories.create');
    }

    /**
     * Store a newly created DisconnectionHistory in storage.
     *
     * @param CreateDisconnectionHistoryRequest $request
     *
     * @return Response
     */
    public function store(CreateDisconnectionHistoryRequest $request)
    {
        $input = $request->all();

        $disconnectionHistory = $this->disconnectionHistoryRepository->create($input);

        Flash::success('Disconnection History saved successfully.');

        return redirect(route('disconnectionHistories.index'));
    }

    /**
     * Display the specified DisconnectionHistory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $disconnectionHistory = $this->disconnectionHistoryRepository->find($id);

        if (empty($disconnectionHistory)) {
            Flash::error('Disconnection History not found');

            return redirect(route('disconnectionHistories.index'));
        }

        return view('disconnection_histories.show')->with('disconnectionHistory', $disconnectionHistory);
    }

    /**
     * Show the form for editing the specified DisconnectionHistory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $disconnectionHistory = $this->disconnectionHistoryRepository->find($id);

        if (empty($disconnectionHistory)) {
            Flash::error('Disconnection History not found');

            return redirect(route('disconnectionHistories.index'));
        }

        return view('disconnection_histories.edit', [
            'disconnectionHistory' => $disconnectionHistory,
            'periods' => Bills::select('ServicePeriod')
                ->groupBy('ServicePeriod')
                ->orderByDesc('ServicePeriod')
                ->get(),
        ]);
    }

    /**
     * Update the specified DisconnectionHistory in storage.
     *
     * @param int $id
     * @param UpdateDisconnectionHistoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDisconnectionHistoryRequest $request)
    {
        $disconnectionHistory = $this->disconnectionHistoryRepository->find($id);

        if (empty($disconnectionHistory)) {
            Flash::error('Disconnection History not found');

            return redirect(route('disconnectionHistories.index'));
        }

        $disconnectionHistory = $this->disconnectionHistoryRepository->update($request->all(), $id);

        Flash::success('Disconnection History updated successfully.');

        return redirect(route('serviceAccounts.show', [$disconnectionHistory->AccountNumber]));
    }

    /**
     * Remove the specified DisconnectionHistory from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $disconnectionHistory = $this->disconnectionHistoryRepository->find($id);

        if (empty($disconnectionHistory)) {
            Flash::error('Disconnection History not found');

            return redirect(route('disconnectionHistories.index'));
        }

        $this->disconnectionHistoryRepository->delete($id);

        Flash::success('Disconnection History deleted successfully.');

        return redirect(route('disconnectionHistories.index'));
    }

    public function generateTurnOffList() {
        $towns = Towns::orderBy('Town')->get();
        if (env('APP_AREA_CODE') == '15') {
            $meterReaders = DB::table('Billing_ServiceAccounts')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->whereNotNull('MeterReader')
                ->select('users.name', 'users.id')
                ->groupBy('users.name', 'users.id')
                ->orderBy('users.name')
                ->get();
        } else {
            $meterReaders = DB::table('Billing_ServiceAccounts')
                ->leftJoin('users', 'Billing_ServiceAccounts.MeterReader', '=', 'users.id')
                ->whereIn('Billing_ServiceAccounts.Town', MeterReaders::getMeterAreaCodeScope(env("APP_AREA_CODE")))
                ->whereNotNull('MeterReader')
                ->select('users.name', 'users.id')
                ->groupBy('users.name', 'users.id')
                ->orderBy('users.name')
                ->get();
        }

        return view('/disconnection_histories/generate_turn_off_list', [
            'towns' => $towns,
            'meterReaders' => $meterReaders
        ]);
    }

    public function getTurnOffListPreview(Request $request) {
        // QUERY UNPAID WITH DUE PAYMENTS
        if ($request['Town'] == 'All') {
            $list = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $request['ServicePeriod'] . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $request['ServicePeriod'])
                ->where('Billing_ServiceAccounts.MeterReader', $request['MeterReader'])
                ->where('Billing_ServiceAccounts.GroupCode', $request['Day'])
                ->where('Billing_Bills.DueDate', '<', date('Y-m-d'))
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.DueDate',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $request['ServicePeriod'] . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterSerial"),
                    'Billing_Bills.id')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        } else {
            $list = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $request['ServicePeriod'] . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $request['ServicePeriod'])
                ->where('Billing_Bills.DueDate', '<', date('Y-m-d'))
                ->where('Billing_ServiceAccounts.MeterReader', $request['MeterReader'])
                ->where('Billing_ServiceAccounts.GroupCode', $request['Day'])
                ->where('Billing_ServiceAccounts.Town', $request['Town'])
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.DueDate',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $request['ServicePeriod'] . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterSerial"),
                    'Billing_Bills.id')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        }       

        $output = "";
        $i=1;
        foreach($list as $item) {
            $output .= '
                <tr>
                    <td>' . $i . '</td>
                    <td>' . $item->BillNumber . '</td>
                    <td><a href="' . route('serviceAccounts.show', [$item->AccountNumber]) . '">' . $item->OldAccountNo . '</a></td>
                    <td>' . $item->ServiceAccountName . '</td>
                    <td>' . ServiceAccounts::getAddress($item) . '</td>
                    <td>' . $item->MeterSerial . '</td>
                    <td class="text-right">' . ($item->NetAmount != null && is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : $item->NetAmount) . '</td> 
                    <td>' . date('M d, Y', strtotime($item->DueDate)) . '</td>
                    <td class="text-right">' . ($item->Arrears != null && is_numeric($item->Arrears) ? number_format($item->Arrears, 2) : $item->Arrears) . '</td> 
                </tr>
            ';
            $i++;
        }

        return response()->json($output, 200);
    }

    public function getTurnOffListPreviewRoute(Request $request) {
        // QUERY UNPAID WITH DUE PAYMENTS
        if ($request['Town'] == 'All') {
            $list = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $request['ServicePeriod'] . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $request['ServicePeriod'])
                ->where('Billing_ServiceAccounts.AreaCode', $request['Route'])
                ->where('Billing_Bills.DueDate', '<', date('Y-m-d'))
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.DueDate',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $request['ServicePeriod'] . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterSerial"),
                    'Billing_Bills.id')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        } else {
            $list = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $request['ServicePeriod'] . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $request['ServicePeriod'])
                ->where('Billing_Bills.DueDate', '<', date('Y-m-d'))
                ->where('Billing_ServiceAccounts.AreaCode', $request['Route'])
                ->where('Billing_ServiceAccounts.Town', $request['Town'])
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.DueDate',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $request['ServicePeriod'] . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterSerial"),
                    'Billing_Bills.id')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        }       

        $output = "";
        $i = 1;
        foreach($list as $item) {
            $output .= '
                <tr>
                    <td>' . $i . '</td>
                    <td>' . $item->BillNumber . '</td>
                    <td><a href="' . route('serviceAccounts.show', [$item->AccountNumber]) . '">' . $item->OldAccountNo . '</a></td>
                    <td>' . $item->ServiceAccountName . '</td>
                    <td>' . ServiceAccounts::getAddress($item) . '</td>
                    <td>' . $item->MeterSerial . '</td>
                    <td class="text-right">' . number_format($item->NetAmount, 2) . '</td> 
                    <td>' . date('M d, Y', strtotime($item->DueDate)) . '</td>
                    <td class="text-right">' . number_format($item->Arrears, 2) . '</td> 
                </tr>
            ';
            $i++;
        }

        return response()->json($output, 200);
    }

    public function printTurnOffList($period, $area, $meterReader, $day) {
        // QUERY UNPAID WITH DUE PAYMENTS
        $meterReaderDetails = Users::find($meterReader);
        if ($area == 'All') {
            $routes = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Excemptions WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $period)
                ->where('Billing_ServiceAccounts.MeterReader', $meterReader)
                ->where('Billing_ServiceAccounts.GroupCode', $day)
                ->where('Billing_Bills.DueDate', '<', date('Y-m-d'))
                ->select('Billing_ServiceAccounts.AreaCode')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                ->groupBy('Billing_ServiceAccounts.AreaCode')
                ->get();

            $list = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Excemptions WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $period)
                ->where('Billing_ServiceAccounts.MeterReader', $meterReader)
                ->where('Billing_ServiceAccounts.GroupCode', $day)
                ->where('Billing_Bills.DueDate', '<', date('Y-m-d'))
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.DueDate',
                    'Billing_Bills.PresentKwh',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.AccountStatus',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $period . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                    'Billing_Bills.id')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        } else {
            $routes = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Excemptions WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $period)
                ->where('Billing_Bills.DueDate', '<', date('Y-m-d'))
                ->where('Billing_ServiceAccounts.MeterReader', $meterReader)
                ->where('Billing_ServiceAccounts.GroupCode', $day)
                ->where('Billing_ServiceAccounts.Town', $area)
                ->select('Billing_ServiceAccounts.AreaCode')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                ->groupBy('Billing_ServiceAccounts.AreaCode')
                ->get();

            $list = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Excemptions WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $period)
                ->where('Billing_Bills.DueDate', '<', date('Y-m-d'))
                ->where('Billing_ServiceAccounts.MeterReader', $meterReader)
                ->where('Billing_ServiceAccounts.GroupCode', $day)
                ->where('Billing_ServiceAccounts.Town', $area)
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.PresentKwh',
                    'Billing_Bills.DueDate',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.AccountStatus',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $period . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                    'Billing_Bills.id')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        }       

        return view('/disconnection_histories/print_turn_off_list', [
            'list' => $list,
            'routes' => $routes,
            'period' => $period,
            'meterReader' => $meterReaderDetails,
            'day' => $day
        ]);
    }

    public function printTurnOffListRoute($period, $area, $route) {
        // QUERY UNPAID WITH DUE PAYMENTS
        if ($area == 'All') {
            $routes = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Excemptions WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $period)
                ->where('Billing_ServiceAccounts.AreaCode', $route)
                ->where('Billing_Bills.DueDate', '<', date('Y-m-d'))
                ->select('Billing_ServiceAccounts.AreaCode')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                ->groupBy('Billing_ServiceAccounts.AreaCode')
                ->get();

            $list = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Excemptions WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $period)
                ->where('Billing_ServiceAccounts.AreaCode', $route)
                ->where('Billing_Bills.DueDate', '<', date('Y-m-d'))
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.DueDate',
                    'Billing_Bills.PresentKwh',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.AccountStatus',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $period . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                    'Billing_Bills.id')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        } else {
            $routes = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Excemptions WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $period)
                ->where('Billing_Bills.DueDate', '<', date('Y-m-d'))
                ->where('Billing_ServiceAccounts.AreaCode', $route)
                ->where('Billing_ServiceAccounts.Town', $area)
                ->select('Billing_ServiceAccounts.AreaCode')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                ->groupBy('Billing_ServiceAccounts.AreaCode')
                ->get();

            $list = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Billing_Excemptions WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $period)
                ->where('Billing_Bills.DueDate', '<', date('Y-m-d'))
                ->where('Billing_ServiceAccounts.AreaCode', $route)
                ->where('Billing_ServiceAccounts.Town', $area)
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.PresentKwh',
                    'Billing_Bills.DueDate',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.AccountStatus',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $period . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    DB::raw("(SELECT TOP 1 SerialNumber FROM Billing_Meters WHERE ServiceAccountId=Billing_ServiceAccounts.id ORDER BY created_at DESC) AS MeterNumber"),
                    'Billing_Bills.id')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        }       

        return view('/disconnection_histories/print_turn_off_list_route', [
            'list' => $list,
            'routes' => $routes,
            'period' => $period,
            'route' => $route,
        ]);
    }
}
