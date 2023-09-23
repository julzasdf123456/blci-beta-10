<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceConnections;
use App\Models\ServiceConnectionInspections;
use App\Models\ServiceConnectionTimeframes;
use App\Models\IDGenerator;
use Illuminate\Support\Facades\DB;
use Validator;

class TelleringController extends Controller {

    public $successStatus = 200;

    public function fetchApprovedServiceConnections(Request $request) {
        $data = DB::table('CRM_ServiceConnections')
                ->join('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->join('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->join('CRM_ServiceConnectionTotalPayments', 'CRM_ServiceConnectionTotalPayments.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
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
                    DB::raw("CONCAT(CRM_Barangays.Barangay, ', ', CRM_Towns.Town) AS Address"))
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();

        if ($data == null) {
            return response()->json(['error' => 'No data'], 404); 
        } else {
            return response()->json($data, $this->successStatus); 
        }  
    }
}