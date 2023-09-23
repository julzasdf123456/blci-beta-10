<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DisconnectionHistory;
use App\Models\ServiceAccounts;
use App\Models\IDGenerator;
use App\Models\Tickets;
use App\Models\TicketLogs;
use App\Http\Requests\CreateReadingsRequest;

class DisconnectionAPI extends Controller {
    public $successStatus = 200;

    public function getDisconnectionListByMeterReader(Request $request) {        
        $disconnectionList = DB::table('Billing_ServiceAccounts')
            ->leftJoin('Billing_Bills', 'Billing_ServiceAccounts.id', '=', 'Billing_Bills.AccountNumber')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->where('Billing_ServiceAccounts.MeterReader', $request['MeterReader'])
            ->where('Billing_ServiceAccounts.GroupCode', $request['GroupCode'])
            ->where('Billing_Bills.DueDate', '<=', date('Y-m-d'))
            ->where('Billing_ServiceAccounts.AccountStatus', 'ACTIVE')
            ->where('Billing_Bills.ServicePeriod', $request['ServicePeriod'])
            ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber=Billing_Bills.AccountNumber AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
            ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
            ->select("Billing_Bills.AccountNumber",
                'Billing_ServiceAccounts.ServiceAccountName',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'Billing_ServiceAccounts.Purok',
                'Billing_ServiceAccounts.AccountType as ConsumerType',
                'Billing_ServiceAccounts.AreaCode',
                'Billing_ServiceAccounts.MeterReader',
                'Billing_ServiceAccounts.GroupCode',
                'Billing_ServiceAccounts.Latitude',
                'Billing_ServiceAccounts.Longitude',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_Bills.MeterNumber',
                'Billing_ServiceAccounts.SequenceCode',
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(15,2))) FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_ServiceAccounts.id AND (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod)) AS Arrears"),
                DB::raw("'No' AS IsUploaded"))
            ->get();

        $arr = [];
        foreach($disconnectionList as $item) {
            array_push($arr, [
                'AccountNumber' => $item->AccountNumber,
                'ServiceAccountName' => $item->ServiceAccountName,
                'Town' => $item->Town,
                'Address' => ServiceAccounts::getAddress($item),
                'ConsumerType' => $item->ConsumerType,
                'AreaCode' => $item->AreaCode,
                'Latitude' => $item->Latitude,
                'Longitude' => $item->Longitude,
                'SequenceCode' => $item->SequenceCode,
                'OldAccountNo' => $item->OldAccountNo,
                'MeterNumber' => $item->MeterNumber,
                'ServicePeriod' => $request['ServicePeriod'],
                'Arrears' => $item->Arrears,
                'MeterReader' => $item->MeterReader,
                'GroupCode' => $item->GroupCode,
                'IsUploaded' => $item->IsUploaded,
            ]);
        }

        return response()->json($arr, $this->successStatus);
    }

    public function updateDisconnectionListByMeterReader(Request $request) {        
        $disconnectionList = DB::table('Billing_ServiceAccounts')
            ->leftJoin('Billing_Bills', 'Billing_ServiceAccounts.id', '=', 'Billing_Bills.AccountNumber')
            ->where('Billing_ServiceAccounts.MeterReader', $request['MeterReader'])
            ->where('Billing_ServiceAccounts.GroupCode', $request['GroupCode'])
            ->where('Billing_Bills.DueDate', '<=', date('Y-m-d'))
            ->where('Billing_ServiceAccounts.AccountStatus', 'ACTIVE')
            ->where('Billing_Bills.ServicePeriod', $request['ServicePeriod'])
            ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber=Billing_Bills.AccountNumber AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
            ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
            ->update(['DownloadedByDisco' => 'Yes']);

        return response()->json(['res' => 'Done'], $this->successStatus);
    }

    public function getDisconnectionListByRoute(Request $request) {        
        $disconnectionList = DB::table('Billing_ServiceAccounts')
            ->leftJoin('Billing_Bills', 'Billing_ServiceAccounts.id', '=', 'Billing_Bills.AccountNumber')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->where('Billing_ServiceAccounts.AreaCode', $request['Route'])
            ->where('Billing_ServiceAccounts.Town', $request['Town'])
            ->where('Billing_Bills.DueDate', '<=', date('Y-m-d'))
            ->where('Billing_ServiceAccounts.AccountStatus', 'ACTIVE')
            ->where('Billing_Bills.ServicePeriod', $request['ServicePeriod'])
            ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber=Billing_Bills.AccountNumber AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
            ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
            ->select("Billing_Bills.AccountNumber",
                'Billing_ServiceAccounts.ServiceAccountName',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'Billing_ServiceAccounts.Purok',
                'Billing_ServiceAccounts.AccountType as ConsumerType',
                'Billing_ServiceAccounts.AreaCode',
                'Billing_ServiceAccounts.MeterReader',
                'Billing_ServiceAccounts.GroupCode',
                'Billing_ServiceAccounts.Latitude',
                'Billing_ServiceAccounts.Longitude',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_Bills.MeterNumber',
                'Billing_ServiceAccounts.SequenceCode',
                DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(15,2))) FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND AccountNumber NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE AccountNumber IS NOT NULL AND AccountNumber=Billing_ServiceAccounts.id AND (Status IS NULL OR Status='Application') AND ServicePeriod=Billing_Bills.ServicePeriod)) AS Arrears"),
                DB::raw("'No' AS IsUploaded"))
            ->get();

        $arr = [];
        foreach($disconnectionList as $item) {
            array_push($arr, [
                'AccountNumber' => $item->AccountNumber,
                'ServiceAccountName' => $item->ServiceAccountName,
                'Town' => $item->Town,
                'Address' => ServiceAccounts::getAddress($item),
                'ConsumerType' => $item->ConsumerType,
                'AreaCode' => $item->AreaCode,
                'Latitude' => $item->Latitude,
                'Longitude' => $item->Longitude,
                'OldAccountNo' => $item->OldAccountNo,
                'MeterNumber' => $item->MeterNumber,
                'SequenceCode' => $item->SequenceCode,
                'Arrears' => $item->Arrears,
                'MeterReader' => $item->MeterReader,
                'GroupCode' => $item->GroupCode,
                'ServicePeriod' => $request['ServicePeriod'],
                'IsUploaded' => $item->IsUploaded,
            ]);
        }

        return response()->json($arr, $this->successStatus);
    }

    public function updateDisconnectionListByRoute(Request $request) {        
        $disconnectionList = DB::table('Billing_ServiceAccounts')
            ->leftJoin('Billing_Bills', 'Billing_ServiceAccounts.id', '=', 'Billing_Bills.AccountNumber')
            ->where('Billing_ServiceAccounts.AreaCode', $request['Route'])
            ->where('Billing_ServiceAccounts.Town', $request['Town'])
            ->where('Billing_Bills.DueDate', '<=', date('Y-m-d'))
            ->where('Billing_ServiceAccounts.AccountStatus', 'ACTIVE')
            ->whereRaw("Billing_Bills.ServicePeriod <= '" . $request['ServicePeriod'] . "'")
            ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Cashier_PaidBills WHERE ServicePeriod=Billing_Bills.ServicePeriod AND AccountNumber=Billing_Bills.AccountNumber AND (Status IS NULL OR Status='Application') AND AccountNumber IS NOT NULL)")
            ->whereRaw("Billing_Bills.AccountNumber NOT IN (SELECT DISTINCT AccountNumber FROM Billing_Excemptions WHERE AccountNumber IS NOT NULL)")
            ->update(['DownloadedByDisco' => 'Yes']);

        return response()->json(['res' => 'Done'], $this->successStatus);
    }

    public function getDisconnectionList(Request $request) {
        $disconnectionList = DB::table('CRM_Tickets')
            ->leftJoin('Billing_ServiceAccounts', 'CRM_Tickets.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->leftJoin('CRM_Barangays', 'CRM_Tickets.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_Tickets.Town', '=', 'CRM_Towns.id')
            ->where('CRM_Tickets.Ticket', Tickets::getDisconnectionDelinquencyId())
            ->where('CRM_Tickets.Status', 'Received')
            ->where('CRM_Tickets.Office', $request['Office'])
            ->whereNotNull('CRM_Tickets.ServicePeriod')
            ->select('Billing_ServiceAccounts.id as AccountNumber',
                    'CRM_Tickets.id as TicketId',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'Billing_ServiceAccounts.Purok as Sitio',
                    'Billing_ServiceAccounts.AccountType as ConsumerType',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.Latitude',
                    'Billing_ServiceAccounts.Longitude',
                    'Billing_ServiceAccounts.SequenceCode',
                    'CRM_Tickets.ServicePeriod',
                    DB::raw("'No' AS IsUploaded"))
            ->get();

        $arr = [];
        foreach($disconnectionList as $item) {
            array_push($arr, [
                'AccountNumber' => $item->AccountNumber,
                'TicketId' => $item->TicketId,
                'ServiceAccountName' => $item->ServiceAccountName,
                'Address' => Tickets::getAddress($item),
                'ConsumerType' => $item->ConsumerType,
                'AreaCode' => $item->AreaCode,
                'Latitude' => $item->Latitude,
                'Longitude' => $item->Longitude,
                'SequenceCode' => $item->SequenceCode,
                'ServicePeriod' => $item->ServicePeriod,
                'IsUploaded' => $item->IsUploaded,
            ]);
        }

        return response()->json($arr, $this->successStatus);
    }

    public function receiveDisconnectionUploads(Request $request) {
        // UPDATE ACCOUNT
        $account = ServiceAccounts::find($request['AccountNumber']);

        if ($account != null) {
            $account->AccountStatus = 'DISCONNECTED';
            $account->DateDisconnected = $request['DateDisconnected'];
            $account->DownloadedByDisco = null;
            $account->save();
        }

        // CREATE DISCONNECTION HISTORY
        $discoHist = new DisconnectionHistory;
        $discoHist->id = IDGenerator::generateIDandRandString();
        $discoHist->AccountNumber = $request['AccountNumber'];
        $discoHist->ServicePeriod = $request['ServicePeriod'];
        $discoHist->Latitude = $request['LatitudeCaptured'];
        $discoHist->Longitude = $request['LongitudeCaptured'];
        $discoHist->Status = 'DISCONNECTED';
        $discoHist->UserId = $request['UserId'];
        $discoHist->DateDisconnected = $request['DateDisconnected'];
        $discoHist->TimeDisconnected = $request['TimeDisconnected'];
        $discoHist->BillId = $request['LastReading'];
        $discoHist->save();

        // UPDATE TICKETS
        $ticket = Tickets::find($request['TicketId']);

        if ($ticket != null) {
            $ticket->DateTimeLinemanArrived = $request['DateDisconnected'] . ' ' . $request['TimeDisconnected'];
            $ticket->DateTimeLinemanExecuted = $request['DateDisconnected'] . ' ' . $request['TimeDisconnected'];
            $ticket->Status = 'Executed';
            // ASSIGN CREW LATER
            $ticket->save();

            // CREATE LOG
            $ticketLog = new TicketLogs;
            $ticketLog->id = IDGenerator::generateIDandRandString();
            $ticketLog->TicketId = $ticket->id;
            $ticketLog->Log = "Disconnected and Uploaded";
            $ticketLog->LogDetails = "Ticket automatically updated via Disconnection App Upload Module";
            $ticketLog->UserId = $request['UserId'];
            $ticketLog->save();
        }

        return response()->json($discoHist, $this->successStatus);
    }
}