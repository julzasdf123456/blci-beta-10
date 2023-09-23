<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use App\Models\Tickets;
use App\Models\TicketLogs;
use App\Models\IDGenerator;
use App\Models\ServiceConnectionCrew;
use App\Models\ServiceAccounts;
use App\Models\DisconnectionHistory;
use Illuminate\Support\Facades\DB;
use App\Models\BillingMeters;
use App\Models\Bills;
use App\Models\ChangeMeterLogs;
use App\Models\Signatories;
use Validator;

class TicketsController extends Controller {

    public $successStatus = 200;

    public function getDownloadableTickets(Request $request) {
        $tickets = DB::table('CRM_Tickets')
            ->leftJoin('Billing_ServiceAccounts', 'CRM_Tickets.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->select('CRM_Tickets.*', 'Billing_ServiceAccounts.OldAccountNo')
            ->where('CrewAssigned', $request['CrewAssigned'])
            ->whereNotNull('CrewAssigned')
            ->where('Status', 'Received')
            ->where(function ($query) {
                $query->where('Trash', 'No')
                    ->orWhereNull('Trash');
            })
            ->get();

        if ($tickets) {
            return response()->json($tickets, $this->successStatus);
        } else {
            return response()->json(['response' => 'Error fetching tickets', 401]);
        }
    }

    public function updateDownloadedTicketsStatus(Request $request) {
        $tickets = Tickets::where('CrewAssigned', $request['CrewAssigned'])
            ->where('Status', 'Received')
            ->where(function ($query) {
                $query->where('Trash', 'No')
                    ->orWhereNull('Trash');
            })
            ->get();

        $crew = ServiceConnectionCrew::find($request['CrewAssigned']);

        $dateTimeDownloaded = date('Y-m-d H:i:s');

        foreach($tickets as $item) {
            // CREATE LOG
            $ticketLog = new TicketLogs;
            $ticketLog->id = IDGenerator::generateRandString();
            $ticketLog->TicketId = $item->id;
            $ticketLog->Log = "Ticket downloaded by lineman";
            $ticketLog->LogDetails = "Downloaded by " . ($crew != null ? $crew->StationName : "-") . " at " . $dateTimeDownloaded;
            $ticketLog->UserId = $request['UserId'];
            $ticketLog->save();
        }

        DB::table('CRM_Tickets')
            ->where('CrewAssigned', $request['CrewAssigned'])
            ->where('Status', 'Received')
            ->where(function ($query) {
                $query->where('Trash', 'No')
                    ->orWhereNull('Trash');
            })
            ->update(['Status' => 'Downloaded by Crew', 'DateTimeDownloaded' => $dateTimeDownloaded]);

        return response()->json(['response' => 'ok'], $this->successStatus);
    }

    public function updateDownloadedTicketsListStatus(Request $request) {
        $crew = ServiceConnectionCrew::find($request['CrewAssigned']);
        $list = $request['TicketIds'];

        $arrs = explode(",", $list);

        for($i=0; $i<count($arrs); $i++) {
            $ticket = Tickets::find($arrs[$i]);

            if ($ticket != null) {
                $ticketLog = new TicketLogs;
                $ticketLog->id = IDGenerator::generateRandString();
                $ticketLog->TicketId = $ticket->id;
                $ticketLog->Log = "Ticket downloaded by lineman";
                $ticketLog->LogDetails = "Downloaded by " . ($crew != null ? $crew->StationName : "-") . " at " . date('Y-m-d H:i:s');
                $ticketLog->UserId = $request['UserId'];
                $ticketLog->save();

                $ticket->Status = 'Downloaded by Crew';
                $ticket->DateTimeDownloaded = date('Y-m-d H:i:s');
                $ticket->save();
            }            
        }

        return response()->json(['response' => 'ok'], $this->successStatus);
    }

    public function uploadTickets(Request $request) {
        $tickets = Tickets::find($request['id']);

        if ($tickets != null) {
            $tickets->DateTimeLinemanArrived = $request['DateTimeLinemanArrived'];
            $tickets->DateTimeLinemanExecuted = $request['DateTimeLinemanExecuted'];
            $tickets->Status = $request['Status'];
            $tickets->Notes = $request['Notes'];
            $tickets->CurrentMeterReading = $request['CurrentMeterReading'];
            $tickets->NewMeterBrand = $request['NewMeterBrand'];
            $tickets->NewMeterNo = $request['NewMeterNo'];
            $tickets->NewMeterReading = $request['NewMeterReading'];
            $tickets->PercentError = $request['PercentError'];
            $tickets->save();

            // CREATE LOG
            $ticketLog = new TicketLogs;
            $ticketLog->id = IDGenerator::generateIDandRandString();
            $ticketLog->TicketId = $request['id'];
            $ticketLog->Log = "Ticket uploadd by crew";
            $ticketLog->LogDetails = $tickets->Notes;
            $ticketLog->UserId = $request['UserId'];
            $ticketLog->save();

            // FILTER TICKETS
            if ($tickets->Ticket == Tickets::getDisconnectionDelinquencyId()) {
                /*
                 * -----------------------
                 * FOR DISCONNECTION
                 * -----------------------
                 */
                // UPDATE ACCOUNT
                $account = ServiceAccounts::find($tickets->AccountNumber);

                if ($account != null) {
                    $account->AccountStatus = 'DISCONNECTED';
                    $account->DateDisconnected = date('Y-m-d', strtotime($tickets->DateTimeLinemanExecuted));
                    $account->save();

                    // CREATE DISCONNECTION HISTORY
                    $discoHist = new DisconnectionHistory;
                    $discoHist->id = IDGenerator::generateIDandRandString();
                    $discoHist->AccountNumber = $account->id;
                    $discoHist->ServicePeriod = $tickets->ServicePeriod;
                    $discoHist->Latitude = $account->Latitude;
                    $discoHist->Longitude = $account->Longitude;
                    $discoHist->Status = 'DISCONNECTED';
                    $discoHist->UserId = $request['UserId'];
                    $discoHist->DateDisconnected = date('Y-m-d', strtotime($tickets->DateTimeLinemanExecuted));
                    $discoHist->TimeDisconnected = date('H:i:s', strtotime($tickets->DateTimeLinemanExecuted));
                    $discoHist->save();
                }
            } else if ($tickets->Ticket == Tickets::getChangeMeter()) {
                /**
                 * -----------------------
                 * FOR CHANGE METERS
                 * -----------------------
                 */
                // SAVE NEW METER
                $meter = new BillingMeters;
                $meter->id = IDGenerator::generateIDandRandString();
                $meter->ServiceAccountId = $tickets->AccountNumber;
                $meter->SerialNumber = $tickets->NewMeterNo;
                $meter->Brand = $tickets->NewMeterBrand;
                $meter->Multiplier = "1";
                $meter->InitialReading = $tickets->NewMeterReading;
                $meter->ConnectionDate = $tickets->DateTimeLinemanExecuted;
                $meter->save();

                if ($tickets->PercentError=='FOR AVERAGING') {
                    // ------------------------------------
                    // 1. GET LATEST BILL
                    $latestBill = Bills::where('AccountNumber', $tickets->AccountNumber)
                        ->orderByDesc('ServicePeriod')
                        ->first();

                    if ($latestBill != null) {
                        // ------------------------------------
                        // 2. AVERAGE LATEST BILLS
                        $latestBills = Bills::where('AccountNumber', $tickets->AccountNumber)
                        ->orderByDesc('ServicePeriod')
                        ->limit(3)
                        ->get();

                        $averageKwh = 0;
                        foreach($latestBills as $item) {
                            $averageKwh += floatval($item->KwhUsed);
                        }
                        $averageKwh = $averageKwh/count($latestBills);
                        
                        // ------------------------------------
                        // 3. COMPUTE DAYS INCURED
                        $lastReadingDate = strtotime($latestBill->BillingDate);
                        $now = strtotime($tickets->DateTimeLinemanExecuted);
                        $daysIncured = $now - $lastReadingDate;
                        $daysIncured = round($daysIncured / (60 * 60 * 24));

                        // ------------------------------------
                        // 4. GET DAILY AVERAGE
                        $averageDaily = ($averageKwh/30) * $daysIncured;

                        // ------------------------------------
                        // 5. CREATE CHANGE METER LOGS
                        $changeMeterLogs = new ChangeMeterLogs;
                        $changeMeterLogs->id = IDGenerator::generateIDandRandString();
                        $changeMeterLogs->AccountNumber = $tickets->AccountNumber;
                        $changeMeterLogs->OldMeterSerial = $tickets->CurrentMeterNo;
                        $changeMeterLogs->NewMeterSerial = $tickets->NewMeterNo;
                        $changeMeterLogs->PullOutReading = $tickets->CurrentMeterReading;  
                        $changeMeterLogs->NewMeterStartKwh = $tickets->NewMeterReading;  
                        $changeMeterLogs->AdditionalKwhForNextBilling = round($averageDaily, 2);
                        $changeMeterLogs->ServicePeriod = date('Y-m-01', strtotime($latestBill->ServicePeriod . ' +1 month')); 
                        $changeMeterLogs->save(); 
                    } else {
                        $svPeriod = date('Y-m-01');

                        $changeMeterLogs = new ChangeMeterLogs;
                        $changeMeterLogs->id = IDGenerator::generateIDandRandString();
                        $changeMeterLogs->AccountNumber = $tickets->AccountNumber;
                        $changeMeterLogs->OldMeterSerial = $tickets->CurrentMeterNo;
                        $changeMeterLogs->NewMeterSerial = $tickets->NewMeterNo;
                        $changeMeterLogs->PullOutReading = $tickets->CurrentMeterReading;  
                        $changeMeterLogs->NewMeterStartKwh = $tickets->NewMeterReading;   
                        $changeMeterLogs->AdditionalKwhForNextBilling = $tickets->NewMeterReading;
                        $changeMeterLogs->ServicePeriod = $svPeriod; 
                        $changeMeterLogs->save(); 
                    }                    
                } else {
                    // ------------------------------------
                    // 1. GET LATEST BILL
                    $latestBill = Bills::where('AccountNumber', $tickets->AccountNumber)
                        ->orderByDesc('ServicePeriod')
                        ->first();

                    if ($latestBill != null) {
                        // ------------------------------------
                        // 2. Get KWH Difference
                        $dif = floatval($tickets->CurrentMeterReading) - floatval($latestBill->KwhUsed);

                        $changeMeterLogs = new ChangeMeterLogs;
                        $changeMeterLogs->id = IDGenerator::generateIDandRandString();
                        $changeMeterLogs->AccountNumber = $tickets->AccountNumber;
                        $changeMeterLogs->OldMeterSerial = $tickets->CurrentMeterNo;
                        $changeMeterLogs->NewMeterSerial = $tickets->NewMeterNo;
                        $changeMeterLogs->PullOutReading = $tickets->CurrentMeterReading;  
                        $changeMeterLogs->NewMeterStartKwh = $tickets->NewMeterReading;   
                        $changeMeterLogs->AdditionalKwhForNextBilling = round($dif, 2);
                        $changeMeterLogs->ServicePeriod = date('Y-m-01', strtotime($latestBill->ServicePeriod . ' +1 month')); 
                        $changeMeterLogs->save(); 
                    } else {
                        $svPeriod = date('Y-m-01');

                        $changeMeterLogs = new ChangeMeterLogs;
                        $changeMeterLogs->id = IDGenerator::generateIDandRandString();
                        $changeMeterLogs->AccountNumber = $tickets->AccountNumber;
                        $changeMeterLogs->OldMeterSerial = $tickets->CurrentMeterNo;
                        $changeMeterLogs->NewMeterSerial = $tickets->NewMeterNo;
                        $changeMeterLogs->PullOutReading = $tickets->CurrentMeterReading;  
                        $changeMeterLogs->NewMeterStartKwh = $tickets->NewMeterReading;  
                        $changeMeterLogs->AdditionalKwhForNextBilling = $tickets->NewMeterReading;
                        $changeMeterLogs->ServicePeriod = $svPeriod; 
                        $changeMeterLogs->save(); 
                    }
                }
            } else if ($tickets->Ticket == Tickets::getReconnection() && $tickets->Status == 'Executed') {
                $account = ServiceAccounts::find($tickets->AccountNumber);
                if ($account != null) {
                    $account->AccountStatus = 'ACTIVE';
                    $account->save();

                    // ADD TO DISCO/RECO HISTORY
                    $recoHist = new DisconnectionHistory;
                    $recoHist->id = IDGenerator::generateIDandRandString();
                    $recoHist->AccountNumber = $account->id;
                    // $recoHist->ServicePeriod = $ticket->ServicePeriod;
                    $recoHist->Status = 'RECONNECTED';
                    $recoHist->UserId = $request['UserId'];
                    $recoHist->DateDisconnected = date('Y-m-d', strtotime($tickets->DateTimeLinemanExecuted));
                    $recoHist->TimeDisconnected = date('H:i:s', strtotime($tickets->DateTimeLinemanExecuted));
                    $recoHist->save();
                }
            }
        }

        return response()->json($tickets, $this->successStatus);
    }

    public function uploadImages(Request $request) {
        $signatories = new Signatories;
        $signatories->id = $request['id'];
        $signatories->Name = $request['sourceId'];
        $signatories->Signature = $request['image'];
        $signatories->Notes = $request['source'];
        $signatories->save();

        return response()->json($signatories, 200);
    }
}