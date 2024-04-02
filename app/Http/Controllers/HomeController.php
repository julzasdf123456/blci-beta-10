<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\ServiceAccounts;
use App\Models\SmsSettings;
use App\Models\IDGenerator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function fetchUnassignedMeters(Request $request) {
        if ($request->ajax()) {            
            $data = DB::table('CRM_ServiceConnections')
                    ->whereNotNull('ORNumber')
                    ->whereNotIn('id', DB::table('CRM_ServiceConnectionMeterAndTransformer')->pluck('ServiceConnectionId'))
                    ->where(function ($query) {
                        $query->where('Trash', 'No')
                            ->orWhereNull('Trash');
                    })
                    ->select('*')
                    ->orderBy('ServiceAccountName')
                    ->get();

            echo json_encode($data);
        }
    }

    public function fetchNewServiceConnections(Request $request) {
        if ($request->ajax()) {            
            $data = DB::table('CRM_ServiceConnections')
                    ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                    ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
                    ->leftJoin('CRM_PaymentOrder', 'CRM_PaymentOrder.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                    ->where(function($query) {
                        $query->where('CRM_ServiceConnections.Status', "For Inspection")
                            ->orWhere('CRM_ServiceConnections.Status', "For Re-Inspection");
                    })
                    ->where(function ($query) {
                        $query->where('Trash', 'No')
                            ->orWhereNull('Trash');
                    })
                    ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName', 
                        'CRM_ServiceConnections.AccountApplicationType', 
                        'CRM_Towns.Town as Town',
                        'CRM_PaymentOrder.OverAllTotal',
                        'CRM_PaymentOrder.ORNumber',
                        'users.name',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnections.Status',
                        'CRM_ServiceConnectionInspections.InspectionSchedule')
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();

            echo json_encode($data);
        }
    }

    public function fetchApprovedServiceConnections(Request $request) {
        $data = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_PaymentOrder', 'CRM_PaymentOrder.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
                ->where('CRM_ServiceConnections.Status', 'Approved')
                ->whereNull('CRM_ServiceConnections.ORNumber')
                ->where(function ($query) {
                    $query->where('CRM_ServiceConnections.Trash', 'No')
                        ->orWhereNull('CRM_ServiceConnections.Trash');
                })
                ->select('CRM_ServiceConnections.id as id',
                    'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName', 
                    'CRM_Towns.Town as Town',
                    'CRM_Barangays.Barangay as Barangay',
                    'CRM_PaymentOrder.OverAllTotal',
                    'CRM_PaymentOrder.ORNumber',
                    'users.name',
                    'CRM_ServiceConnections.Status',
                    'CRM_ServiceConnectionInspections.InspectionSchedule')
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();

        return response()->json($data, 200);
    }

    public function fetchForPaymentApprovals(Request $request) {
        $data = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->leftJoin('CRM_PaymentOrder', 'CRM_PaymentOrder.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
            ->whereRaw("(Trash IS NULL OR Trash='No')")
            ->whereRaw("CRM_ServiceConnections.Status='Approved' AND CRM_ServiceConnections.ORNumber IS NULL")
            ->select(
                'CRM_ServiceConnections.id',
                'CRM_ServiceConnections.ServiceAccountName',
                'CRM_ServiceConnections.Sitio',
                'CRM_ServiceConnections.Status',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'CRM_ServiceConnections.DateOfApplication',
                'CRM_ServiceConnections.AccountApplicationType',
                'CRM_ServiceConnectionInspections.DateOfVerification',
                'users.name',
                'CRM_PaymentOrder.OverAllTotal'
            )
            ->orderBy('ServiceAccountName')
            ->get();

        return response()->json($data);
    }

    public function fetchForPaymentOrders(Request $request) {
        $data = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->leftJoin('CRM_PaymentOrder', 'CRM_PaymentOrder.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
            ->whereRaw("(Trash IS NULL OR Trash='No')")
            ->whereRaw("CRM_ServiceConnections.Status='Payment Approved' AND (CRM_ServiceConnections.ORNumber IS NULL OR CRM_ServiceConnections.ORNumber = 0)")
            ->select(
                'CRM_ServiceConnections.id',
                'CRM_ServiceConnections.ServiceAccountName',
                'CRM_ServiceConnections.Sitio',
                'CRM_ServiceConnections.Status',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'CRM_ServiceConnections.DateOfApplication',
                'CRM_ServiceConnections.AccountApplicationType',
                'CRM_ServiceConnectionInspections.DateOfVerification',
                'users.name',
                'CRM_PaymentOrder.OverAllTotal'
            )
            ->orderBy('ServiceAccountName')
            ->get();

        return response()->json($data);
    }

    public function fetchForTurnOnApprovals(Request $request) {
        $data = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
            ->whereRaw("(Trash IS NULL OR Trash='No')")
            ->whereRaw("CRM_ServiceConnections.Status='For Energization' AND CRM_ServiceConnections.ORNumber IS NOT NULL")
            ->select(
                'CRM_ServiceConnections.id',
                'CRM_ServiceConnections.ServiceAccountName',
                'CRM_ServiceConnections.Sitio',
                'CRM_ServiceConnections.Status',
                'CRM_ServiceConnections.ORNumber',
                'CRM_ServiceConnections.ORDate',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'CRM_ServiceConnections.DateOfApplication',
                'CRM_ServiceConnections.AccountApplicationType',
                'CRM_ServiceConnectionInspections.DateOfVerification',
                'CRM_ServiceConnections.ConnectionSchedule',
                'CRM_ServiceConnections.StationCrewAssigned',
                'users.name',
            )
            ->orderBy('ConnectionSchedule')
            ->orderBy('ServiceAccountName')
            ->get();

        return response()->json($data);
    }

    public function fetchForEnergization(Request $request) {
       $data = DB::table('CRM_ServiceConnections')
        ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
        ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
        ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
        ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
        ->whereRaw("(Trash IS NULL OR Trash='No')")
        ->whereRaw("CRM_ServiceConnections.Status='Approved for Energization' AND CRM_ServiceConnections.ORNumber IS NOT NULL AND AccountApplicationType IN ('NEW INSTALLATION', 'TEMPORARY')")
        ->select(
            'CRM_ServiceConnections.id',
            'CRM_ServiceConnections.ServiceAccountName',
            'CRM_ServiceConnections.Sitio',
            'CRM_ServiceConnections.Status',
            'CRM_Towns.Town',
            'CRM_Barangays.Barangay',
            'CRM_ServiceConnections.DateOfApplication',
            'CRM_ServiceConnections.AccountApplicationType',
            'CRM_ServiceConnectionInspections.DateOfVerification',
            'CRM_ServiceConnections.ConnectionSchedule',
            'CRM_ServiceConnections.StationCrewAssigned',
            'users.name',
        )
        ->orderBy('ConnectionSchedule')
        ->orderBy('ServiceAccountName')
        ->get();
        
        return response()->json($data);
    }

    public function fetchOtherServices(Request $request) {
        $data = DB::table('CRM_ServiceConnections')
         ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
         ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
         ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
         ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
         ->whereRaw("(Trash IS NULL OR Trash='No')")
         ->whereRaw("CRM_ServiceConnections.Status='Approved for Energization' AND CRM_ServiceConnections.ORNumber IS NOT NULL AND AccountApplicationType NOT IN ('NEW INSTALLATION', 'TEMPORARY')")
         ->select(
             'CRM_ServiceConnections.id',
             'CRM_ServiceConnections.ServiceAccountName',
             'CRM_ServiceConnections.Sitio',
             'CRM_ServiceConnections.Status',
             'CRM_Towns.Town',
             'CRM_Barangays.Barangay',
             'CRM_ServiceConnections.DateOfApplication',
             'CRM_ServiceConnections.AccountApplicationType',
             'CRM_ServiceConnectionInspections.DateOfVerification',
             'CRM_ServiceConnections.ConnectionSchedule',
             'CRM_ServiceConnections.StationCrewAssigned',
             'users.name',
         )
         ->orderBy('ConnectionSchedule')
         ->orderBy('ServiceAccountName')
         ->get();
         
         return response()->json($data);
     }

    public function fetchInspectionReport(Request $request) {
        if ($request->ajax()) {            
            $data = DB::table('users')
                    ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->where('roles.name', 'Inspector')
                    ->select([
                        'users.name',
                        DB::raw("(SELECT COUNT(x.id) FROM CRM_ServiceConnections x 
                        LEFT JOIN CRM_ServiceConnectionInspections y ON x.id=y.ServiceConnectionId
                        WHERE x.Status='For Inspection' AND x.Trash IS NULL AND y.Inspector=users.id) AS Total")    
                    ])
                    ->get();

            echo json_encode($data);
        }
    }

    public function fetchInspectionLargeLoad(Request $request) {
        if ($request->ajax()) {            
            $data = DB::table('CRM_ServiceConnections')
                    ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                    ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                    ->where('CRM_ServiceConnections.Status', 'Forwarded To Planning')
                    ->where(function ($query) {
                        $query->where('Trash', 'No')
                            ->orWhereNull('Trash');
                    })
                    ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',)
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();

            echo json_encode($data);
        }
    }

    public function fetchBomLargeLoad(Request $request) {
        if ($request->ajax()) {            
            $data = DB::table('CRM_ServiceConnections')
                    ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                    ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                    ->where('CRM_ServiceConnections.Status', 'For BoM')
                    ->where(function ($query) {
                        $query->where('Trash', 'No')
                            ->orWhereNull('Trash');
                    })
                    ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',)
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();

            echo json_encode($data);
        }
    }

    public function fetchTransformerLargeLoad(Request $request) {
        if ($request->ajax()) {            
            $data = DB::table('CRM_ServiceConnections')
                    ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                    ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                    ->where('CRM_ServiceConnections.Status', 'For Transformer and Pole Assigning')
                    ->where(function ($query) {
                        $query->where('Trash', 'No')
                            ->orWhereNull('Trash');
                    })
                    ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',)
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();

            echo json_encode($data);
        }
    }

    public function dashGetCollectionSummary() {
        $data = DB::table('Cashier_PaidBills')
            ->select(
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS TodaysPowerBill"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE ORDate='" . date('Y-m-d', strtotime('yesterday')) . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS YesterdaysPowerBill"),
                DB::raw("(SELECT SUM(TRY_CAST(Total AS DECIMAL(14,2))) FROM Cashier_TransactionIndex WHERE ORDate='" . date('Y-m-d') . "' AND Status IS NULL) AS TodaysNonPowerBill"),
                DB::raw("(SELECT SUM(TRY_CAST(Total AS DECIMAL(14,2))) FROM Cashier_TransactionIndex WHERE ORDate='" . date('Y-m-d', strtotime('yesterday')) . "' AND Status IS NULL) AS YesterdaysNonPowerBill"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '01-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS Cadiz"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '02-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS Magalona"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '03-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS Manapla"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '04-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS Victorias"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '05-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS SanCarlos"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '06-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS Sagay"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '07-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS Escalante"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '08-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS Calatrava"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '09-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS Toboso"),
            )
            ->first();

        return response()->json($data, 200);
    }

    public function dashGetCollectionSummaryGraph() {
        $data = DB::table('Cashier_PaidBills')
            ->select(
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '01-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS TodayCadiz"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '02-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS TodayMagalona"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '03-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS TodayManapla"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '04-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS TodayVictorias"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '05-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS TodaySanCarlos"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '06-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS TodaySagay"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '07-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS TodayEscalante"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '08-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS TodayCalatrava"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '09-%' AND ORDate='" . date('Y-m-d') . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS TodayToboso"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '01-%' AND ORDate='" . date('Y-m-d', strtotime('yesterday')) . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS YesterdayCadiz"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '02-%' AND ORDate='" . date('Y-m-d', strtotime('yesterday')) . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS YesterdayMagalona"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '03-%' AND ORDate='" . date('Y-m-d', strtotime('yesterday')) . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS YesterdayManapla"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '04-%' AND ORDate='" . date('Y-m-d', strtotime('yesterday')) . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS YesterdayVictorias"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '05-%' AND ORDate='" . date('Y-m-d', strtotime('yesterday')) . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS YesterdaySanCarlos"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '06-%' AND ORDate='" . date('Y-m-d', strtotime('yesterday')) . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS YesterdaySagay"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '07-%' AND ORDate='" . date('Y-m-d', strtotime('yesterday')) . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS YesterdayEscalante"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '08-%' AND ORDate='" . date('Y-m-d', strtotime('yesterday')) . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS YesterdayCalatrava"),
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS DECIMAL(14,2))) FROM Cashier_PaidBills WHERE AccountNumber LIKE '09-%' AND ORDate='" . date('Y-m-d', strtotime('yesterday')) . "' AND Status IS NULL AND AccountNumber IS NOT NULL) AS YesterdayToboso"),
            )
            ->first();

        return response()->json($data, 200);
    }

    public function settings() {
        if(Auth::user()->hasAnyPermission(['Super Admin'])) {
            return view('/settings/settings', [
                'smsSettings' => SmsSettings::orderByDesc('created_at')->first(),
            ]);
        } else {
            return abort(403, "You're not authorized to create a service connection application.");
        }
    }

    public function saveGeneralSettings(Request $request) {
        // SMS SETTINGS
        $smsSettings = SmsSettings::orderByDesc('created_at')->first();

        if ($smsSettings != null) {
            $smsSettings->Bills = trim($request['Bills']);
            $smsSettings->NoticeOfDisconnection = trim($request['NoticeOfDisconnection']);
            $smsSettings->ServiceConnectionReception = trim($request['ServiceConnectionReception']);
            $smsSettings->InspectionCreation = trim($request['InspectionCreation']);
            $smsSettings->PaymentApproved = trim($request['PaymentApproved']);
            $smsSettings->InspectionToday = trim($request['InspectionToday']);
            $smsSettings->save();
        } else {
            $smsSettings = new SmsSettings;
            $smsSettings->id = IDGenerator::generateIDandRandString();
            $smsSettings->Bills = trim($request['Bills']);
            $smsSettings->NoticeOfDisconnection = trim($request['NoticeOfDisconnection']);
            $smsSettings->ServiceConnectionReception = trim($request['ServiceConnectionReception']);
            $smsSettings->InspectionCreation = trim($request['InspectionCreation']);
            $smsSettings->PaymentApproved = trim($request['PaymentApproved']);
            $smsSettings->InspectionToday = trim($request['InspectionToday']);
            $smsSettings->save();
        }

        return response()->json('ok', 200);
    }
}
