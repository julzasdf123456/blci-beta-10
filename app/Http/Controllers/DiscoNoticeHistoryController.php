<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDiscoNoticeHistoryRequest;
use App\Http\Requests\UpdateDiscoNoticeHistoryRequest;
use App\Repositories\DiscoNoticeHistoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ServiceAccounts;
use App\Models\Towns;
use App\Models\DiscoNoticeHistory;
use App\Models\MeterReaders;
use App\Models\User;
use App\Models\IDGenerator;
use Flash;
use Response;

class DiscoNoticeHistoryController extends AppBaseController
{
    /** @var  DiscoNoticeHistoryRepository */
    private $discoNoticeHistoryRepository;

    public function __construct(DiscoNoticeHistoryRepository $discoNoticeHistoryRepo)
    {
        $this->middleware('auth');
        $this->discoNoticeHistoryRepository = $discoNoticeHistoryRepo;
    }

    /**
     * Display a listing of the DiscoNoticeHistory.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $discoNoticeHistories = $this->discoNoticeHistoryRepository->all();

        return view('disco_notice_histories.index')
            ->with('discoNoticeHistories', $discoNoticeHistories);
    }

    /**
     * Show the form for creating a new DiscoNoticeHistory.
     *
     * @return Response
     */
    public function create()
    {
        return view('disco_notice_histories.create');
    }

    /**
     * Store a newly created DiscoNoticeHistory in storage.
     *
     * @param CreateDiscoNoticeHistoryRequest $request
     *
     * @return Response
     */
    public function store(CreateDiscoNoticeHistoryRequest $request)
    {
        $input = $request->all();

        $discoNoticeHistory = $this->discoNoticeHistoryRepository->create($input);

        Flash::success('Disco Notice History saved successfully.');

        return redirect(route('discoNoticeHistories.index'));
    }

    /**
     * Display the specified DiscoNoticeHistory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $discoNoticeHistory = $this->discoNoticeHistoryRepository->find($id);

        if (empty($discoNoticeHistory)) {
            Flash::error('Disco Notice History not found');

            return redirect(route('discoNoticeHistories.index'));
        }

        return view('disco_notice_histories.show')->with('discoNoticeHistory', $discoNoticeHistory);
    }

    /**
     * Show the form for editing the specified DiscoNoticeHistory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $discoNoticeHistory = $this->discoNoticeHistoryRepository->find($id);

        if (empty($discoNoticeHistory)) {
            Flash::error('Disco Notice History not found');

            return redirect(route('discoNoticeHistories.index'));
        }

        return view('disco_notice_histories.edit')->with('discoNoticeHistory', $discoNoticeHistory);
    }

    /**
     * Update the specified DiscoNoticeHistory in storage.
     *
     * @param int $id
     * @param UpdateDiscoNoticeHistoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDiscoNoticeHistoryRequest $request)
    {
        $discoNoticeHistory = $this->discoNoticeHistoryRepository->find($id);

        if (empty($discoNoticeHistory)) {
            Flash::error('Disco Notice History not found');

            return redirect(route('discoNoticeHistories.index'));
        }

        $discoNoticeHistory = $this->discoNoticeHistoryRepository->update($request->all(), $id);

        Flash::success('Disco Notice History updated successfully.');

        return redirect(route('discoNoticeHistories.index'));
    }

    /**
     * Remove the specified DiscoNoticeHistory from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $discoNoticeHistory = $this->discoNoticeHistoryRepository->find($id);

        if (empty($discoNoticeHistory)) {
            // Flash::error('Disco Notice History not found');

            // return redirect(route('discoNoticeHistories.index'));
        return response()->json('error', 403);
        }

        $this->discoNoticeHistoryRepository->delete($id);

        // Flash::success('Disco Notice History deleted successfully.');

        // return redirect(route('discoNoticeHistories.index'));
        return response()->json('ok', 200);
    }

    public function generateNod() {
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

        return view('/disco_notice_histories/generate_nod', [
            'towns' => $towns,
            'meterReaders' => $meterReaders
        ]);
    }

    public function getDiscoListPreview(Request $request) {
        // QUERY UNPAID WITH DUE PAYMENTS
        if ($request['Town'] == 'All') {
            $list = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.id NOT IN (SELECT BillId FROM Disconnection_NoticeHistory WHERE ServicePeriod='" . $request['ServicePeriod'] . "')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $request['ServicePeriod'] . "' AND AccountNumber IS NOT NULL AND Status IS NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $request['ServicePeriod'])
                ->where('Billing_ServiceAccounts.MeterReader', $request['MeterReader'])
                ->where('Billing_ServiceAccounts.GroupCode', $request['Day'])
                ->where('Billing_Bills.DueDate', '<=', date('Y-m-d'))
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.id')
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        } else {
            $list = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.id NOT IN (SELECT BillId FROM Disconnection_NoticeHistory WHERE ServicePeriod='" . $request['ServicePeriod'] . "')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $request['ServicePeriod'] . "' AND AccountNumber IS NOT NULL AND Status IS NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $request['ServicePeriod'])
                ->where('Billing_Bills.DueDate', '<=', date('Y-m-d'))
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
                    'Billing_Bills.id')
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        }       

        // INSERT INTO DiscoNoticeHistory
        foreach($list as $item) {           
            $discoHist = new DiscoNoticeHistory;
            $discoHist->id = IDGenerator::generateIDandRandString();
            $discoHist->AccountNumber = $item->AccountNumber;
            $discoHist->ServicePeriod = $request['ServicePeriod'];
            $discoHist->BillId = $item->id;
            $discoHist->save();
        }

        if ($request['Town'] == 'All') {
            // SELECT ALL FROM DISCO NOTICE HISTORY
            $discoList = DB::table('Disconnection_NoticeHistory')
                ->leftJoin('Billing_Bills', 'Disconnection_NoticeHistory.BillId', '=', 'Billing_Bills.id')
                ->leftJoin('Billing_ServiceAccounts', 'Disconnection_NoticeHistory.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->where('Disconnection_NoticeHistory.ServicePeriod', $request['ServicePeriod'])
                ->where('Billing_ServiceAccounts.MeterReader', $request['MeterReader'])
                ->where('Billing_ServiceAccounts.GroupCode', $request['Day'])
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $request['ServicePeriod'] . "' AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application'))")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.id',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $request['ServicePeriod'] . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    'Disconnection_NoticeHistory.id as NoticeId')
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        } else {
            // SELECT ALL FROM DISCO NOTICE HISTORY
            $discoList = DB::table('Disconnection_NoticeHistory')
                ->leftJoin('Billing_Bills', 'Disconnection_NoticeHistory.BillId', '=', 'Billing_Bills.id')
                ->leftJoin('Billing_ServiceAccounts', 'Disconnection_NoticeHistory.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->where('Disconnection_NoticeHistory.ServicePeriod', $request['ServicePeriod'])
                ->where('Billing_ServiceAccounts.Town', $request['Town'])
                ->where('Billing_ServiceAccounts.MeterReader', $request['MeterReader'])
                ->where('Billing_ServiceAccounts.GroupCode', $request['Day'])
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $request['ServicePeriod'] . "' AND AccountNumber IS NOT NULL AND (Status IS NULL OR Status='Application'))")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.id',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $request['ServicePeriod'] . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    'Disconnection_NoticeHistory.id as NoticeId')                    
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        }
        
        $output = "";

        $i=1;
        foreach($discoList as $item) {
            $output .= '
                <tr id="' . $item->NoticeId . '">
                    <td>' . $i . '</td>
                    <td>' . $item->BillNumber . '</td>
                    <td><a href="' . route('serviceAccounts.show', [$item->AccountNumber]) . '">' . $item->OldAccountNo . '</a></td>
                    <td>' . $item->ServiceAccountName . '</td>
                    <td>' . ServiceAccounts::getAddress($item) . '</td>
                    <td class="text-right">' . number_format($item->NetAmount, 2) . '</td>   
                    <td class="text-right">' . number_format($item->Arrears, 2) . '</td>                    
                    <td>
                        <button class="btn btn-sm text-danger" onclick=deleteNotice("' . $item->NoticeId . '")><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            ';
            $i++;
        }

        return response()->json($output, 200);
    }

    public function getDiscoListPreviewRoute(Request $request) {
        // QUERY UNPAID WITH DUE PAYMENTS
        if ($request['Town'] == 'All') {
            $list = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.id NOT IN (SELECT BillId FROM Disconnection_NoticeHistory WHERE ServicePeriod='" . $request['ServicePeriod'] . "')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $request['ServicePeriod'] . "' AND AccountNumber IS NOT NULL AND Status IS NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $request['ServicePeriod'])
                ->where('Billing_ServiceAccounts.AreaCode', $request['Route'])
                ->where('Billing_Bills.DueDate', '<=', date('Y-m-d'))
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.id')
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        } else {
            $list = DB::table('Billing_Bills')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Bills.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->whereRaw("Billing_Bills.id NOT IN (SELECT BillId FROM Disconnection_NoticeHistory WHERE ServicePeriod='" . $request['ServicePeriod'] . "')")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $request['ServicePeriod'] . "' AND AccountNumber IS NOT NULL AND Status IS NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->where('Billing_Bills.ServicePeriod', $request['ServicePeriod'])
                ->where('Billing_Bills.DueDate', '<=', date('Y-m-d'))
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
                    'Billing_Bills.id')                    
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        }       

        // INSERT INTO DiscoNoticeHistory
        foreach($list as $item) {           
            $discoHist = new DiscoNoticeHistory;
            $discoHist->id = IDGenerator::generateIDandRandString();
            $discoHist->AccountNumber = $item->AccountNumber;
            $discoHist->ServicePeriod = $request['ServicePeriod'];
            $discoHist->BillId = $item->id;
            $discoHist->save();
        }

        if ($request['Town'] == 'All') {
            // SELECT ALL FROM DISCO NOTICE HISTORY
            $discoList = DB::table('Disconnection_NoticeHistory')
                ->leftJoin('Billing_Bills', 'Disconnection_NoticeHistory.BillId', '=', 'Billing_Bills.id')
                ->leftJoin('Billing_ServiceAccounts', 'Disconnection_NoticeHistory.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->where('Disconnection_NoticeHistory.ServicePeriod', $request['ServicePeriod'])
                ->where('Billing_ServiceAccounts.AreaCode', $request['Route'])
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $request['ServicePeriod'] . "' AND AccountNumber IS NOT NULL AND Status IS NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.id',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $request['ServicePeriod'] . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    'Disconnection_NoticeHistory.id as NoticeId')                    
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        } else {
            // SELECT ALL FROM DISCO NOTICE HISTORY
            $discoList = DB::table('Disconnection_NoticeHistory')
                ->leftJoin('Billing_Bills', 'Disconnection_NoticeHistory.BillId', '=', 'Billing_Bills.id')
                ->leftJoin('Billing_ServiceAccounts', 'Disconnection_NoticeHistory.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->where('Disconnection_NoticeHistory.ServicePeriod', $request['ServicePeriod'])
                ->where('Billing_ServiceAccounts.Town', $request['Town'])
                ->where('Billing_ServiceAccounts.AreaCode', $request['Route'])
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $request['ServicePeriod'] . "' AND AccountNumber IS NOT NULL AND Status IS NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.id',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $request['ServicePeriod'] . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    'Disconnection_NoticeHistory.id as NoticeId')                    
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        }        
        
        $output = "";
        $i=1;
        foreach($discoList as $item) {
            $output .= '
                <tr id="' . $item->NoticeId . '">
                    <td>' . $i . '</td>
                    <td>' . $item->BillNumber . '</td>
                    <td>' . $item->OldAccountNo . '</td>
                    <td>' . $item->ServiceAccountName . '</td>
                    <td>' . ServiceAccounts::getAddress($item) . '</td>
                    <td class="text-right">' . number_format($item->NetAmount, 2) . '</td>   
                    <td class="text-right">' . number_format($item->Arrears, 2) . '</td>                     
                    <td>
                        <button class="btn btn-sm text-danger" onclick=deleteNotice("' . $item->NoticeId . '")><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            ';
            $i++;
        }

        return response()->json($output, 200);
    }

    public function printReroute(Request $request) {
        return response()->json(['area' => $request['Town'], 'period' => $request['ServicePeriod'], 'meterReader' => $request['MeterReader'], 'day' => $request['Day']], 200);
    }

    public function printDisconnectionList($period, $area, $meterReader, $day) {
        if ($area == 'All') {
            // SELECT ALL FROM DISCO NOTICE HISTORY
            $discoList = DB::table('Disconnection_NoticeHistory')
                ->leftJoin('Billing_Bills', 'Disconnection_NoticeHistory.BillId', '=', 'Billing_Bills.id')
                ->leftJoin('Billing_ServiceAccounts', 'Disconnection_NoticeHistory.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->where('Disconnection_NoticeHistory.ServicePeriod', $period)
                ->where('Billing_ServiceAccounts.MeterReader', $meterReader)
                ->where('Billing_ServiceAccounts.GroupCode', $day)
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND Status IS NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountType',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.id',
                    'Billing_Bills.MeterNumber',
                    'Billing_Bills.ServicePeriod',
                    'Billing_Bills.DueDate',
                    'Billing_Bills.KwhUsed',
                    'Billing_Bills.ConsumerType',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $period . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    'Disconnection_NoticeHistory.id as NoticeId')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                // ->orderBy('Billing_ServiceAccounts.SequenceCode')  
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        } else {
            // SELECT ALL FROM DISCO NOTICE HISTORY
            $discoList = DB::table('Disconnection_NoticeHistory')
                ->leftJoin('Billing_Bills', 'Disconnection_NoticeHistory.BillId', '=', 'Billing_Bills.id')
                ->leftJoin('Billing_ServiceAccounts', 'Disconnection_NoticeHistory.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->where('Disconnection_NoticeHistory.ServicePeriod', $period)
                ->where('Billing_ServiceAccounts.Town', $area)
                ->where('Billing_ServiceAccounts.MeterReader', $meterReader)
                ->where('Billing_ServiceAccounts.GroupCode', $day)
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND Status IS NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountType',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.id',
                    'Billing_Bills.MeterNumber',
                    'Billing_Bills.ServicePeriod',
                    'Billing_Bills.DueDate',
                    'Billing_Bills.KwhUsed',
                    'Billing_Bills.ConsumerType',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $period . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    'Disconnection_NoticeHistory.id as NoticeId')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                // ->orderBy('Billing_ServiceAccounts.SequenceCode')                
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        }

        return view('/disco_notice_histories/batch_print_disco_hist', [
            'discoList' => $discoList
        ]);
    }

    public function printDisconnectionListRoute($period, $area, $route) {
        if ($area == 'All') {
            // SELECT ALL FROM DISCO NOTICE HISTORY
            $discoList = DB::table('Disconnection_NoticeHistory')
                ->leftJoin('Billing_Bills', 'Disconnection_NoticeHistory.BillId', '=', 'Billing_Bills.id')
                ->leftJoin('Billing_ServiceAccounts', 'Disconnection_NoticeHistory.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->where('Disconnection_NoticeHistory.ServicePeriod', $period)
                ->where('Billing_ServiceAccounts.AreaCode', $route)
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND Status IS NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountType',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.id',
                    'Billing_Bills.MeterNumber',
                    'Billing_Bills.ServicePeriod',
                    'Billing_Bills.DueDate',
                    'Billing_Bills.KwhUsed',
                    'Billing_Bills.ConsumerType',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $period . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    'Disconnection_NoticeHistory.id as NoticeId')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                // ->orderBy('Billing_ServiceAccounts.SequenceCode')
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        } else {
            // SELECT ALL FROM DISCO NOTICE HISTORY
            $discoList = DB::table('Disconnection_NoticeHistory')
                ->leftJoin('Billing_Bills', 'Disconnection_NoticeHistory.BillId', '=', 'Billing_Bills.id')
                ->leftJoin('Billing_ServiceAccounts', 'Disconnection_NoticeHistory.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->whereRaw("Billing_ServiceAccounts.AccountStatus IN ('ACTIVE')")
                ->where('Disconnection_NoticeHistory.ServicePeriod', $period)
                ->where('Billing_ServiceAccounts.Town', $area)
                ->where('Billing_ServiceAccounts.AreaCode', $route)
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod='" . $period . "' AND AccountNumber IS NOT NULL AND Status IS NULL)")
                ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
                ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.SequenceCode',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountType',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_Bills.NetAmount',
                    'Billing_Bills.BillNumber',
                    'Billing_Bills.id',
                    'Billing_Bills.MeterNumber',
                    'Billing_Bills.ServicePeriod',
                    'Billing_Bills.DueDate',
                    'Billing_Bills.KwhUsed',
                    'Billing_Bills.ConsumerType',
                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(15,2))) FROM Billing_Bills b WHERE ServicePeriod<'" . $period . "' AND AccountNumber=Billing_Bills.AccountNumber AND AccountNumber NOT IN 
                            (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber=b.AccountNumber AND ServicePeriod=b.ServicePeriod AND (Status IS NULL OR Status='Application'))
                        ) AS Arrears"),
                    'Disconnection_NoticeHistory.id as NoticeId')
                // ->orderBy('Billing_ServiceAccounts.AreaCode')
                // ->orderBy('Billing_ServiceAccounts.SequenceCode')
                ->orderByRaw('TRY_CAST(Billing_ServiceAccounts.SequenceCode AS DECIMAL(10,1))')
                ->get();
        }

        return view('/disco_notice_histories/batch_print_disco_hist', [
            'discoList' => $discoList
        ]);
    }
}
