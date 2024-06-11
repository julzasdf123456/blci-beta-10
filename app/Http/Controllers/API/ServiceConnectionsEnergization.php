<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceConnections;
use App\Models\ServiceConnectionTimeframes;
use App\Models\ServiceConnectionImages;
use App\Models\ServiceConnectionCrew;
use App\Models\MastPoles;
use App\Models\IDGenerator;
use App\Models\MeterReaders;
use App\Models\MeterInstallation;
use App\Models\LineAndMeteringServices;
use File;

class ServiceConnectionsEnergization extends Controller {

    public $successStatus = 200;

    public function getForEnergizationData(Request $request) {
        $crew = $request['CrewAssigned'];

        $serviceConnections = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionInspections.ServiceConnectionId')
                ->leftJoin('users', 'users.id', '=', 'CRM_ServiceConnectionInspections.Inspector')
                ->whereRaw("(Trash IS NULL OR Trash='No')")
                ->whereRaw("(CRM_ServiceConnections.Status='Approved for Energization' OR CRM_ServiceConnections.Status='For Re-Energization') AND CRM_ServiceConnections.StationCrewAssigned='" . $crew . "'")
                ->select(
                    'CRM_ServiceConnections.*', 
                    'users.name AS Verifier',
                    )
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get(); 

        if ($serviceConnections == null) {
            return response()->json(['error' => 'No data'], 404); 
        } else {
            return response()->json($serviceConnections, $this->successStatus); 
        } 
    }

    public function updateDownloadedServiceConnectionStatus(Request $request) {
        $crew = $request['CrewAssigned'];

        $serviceConnections = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionInspections.ServiceConnectionId')
                ->leftJoin('users', 'users.id', '=', 'CRM_ServiceConnectionInspections.Inspector')
                ->whereRaw("(Trash IS NULL OR Trash='No')")
                ->whereRaw("(CRM_ServiceConnections.Status='Approved for Energization' OR CRM_ServiceConnections.Status='For Re-Energization') AND CRM_ServiceConnections.StationCrewAssigned='" . $crew . "'")
                ->select('CRM_ServiceConnections.*', 
                    'users.name AS Verifier',
                    )
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();  

        $crew = ServiceConnectionCrew::find($request['CrewAssigned']);

        $dateTimeDownloaded = date('Y-m-d H:i:s');

        foreach($serviceConnections as $item) {
            // CREATE LOG
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateIDandRandString();
            $timeFrame->ServiceConnectionId = $item->id;
            $timeFrame->UserId = $request['User'];
            $timeFrame->Status = '[WEB]' . $request['Status'];
            $timeFrame->Notes = 'Application downloaded by crew ' . $request['CrewAssigned'];
            $timeFrame->save();
        }

        return response()->json(['res' => 'ok'], $this->successStatus);   
    }

    public function getInspectionsForEnergizationData(Request $request) {
        $crew = $request['CrewAssigned'];

        $serviceConnections = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionInspections.ServiceConnectionId')
                // ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId')
                ->leftJoin('users', 'users.id', '=', 'CRM_ServiceConnectionInspections.Inspector')
                ->whereRaw("(Trash IS NULL OR Trash='No')")
                ->whereRaw("(CRM_ServiceConnections.Status='Approved for Energization' OR CRM_ServiceConnections.Status='For Re-Energization') AND CRM_ServiceConnections.StationCrewAssigned='" . $crew . "'")
                ->select(
                    'CRM_ServiceConnectionInspections.*'
                    )
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();   

        if ($serviceConnections == null) {
            return response()->json(['error' => 'No data'], 404); 
        } else {
            return response()->json($serviceConnections, $this->successStatus); 
        } 
    }

    public function updateEnergized(Request $request) {
        $serviceConnections = ServiceConnections::find($request['id']);
        $serviceConnections->Status = $request['Status'];
        // $serviceConnections->DateTimeLinemenArrived = $request['DateTimeLinemenArrived'];
        $serviceConnections->DateTimeOfEnergization = $request['DateTimeOfEnergization'];
        $serviceConnections->PoleNumber = $request['PoleNumber'];
        $serviceConnections->TransformerID = $request['TransformerID'];
        $serviceConnections->Notes = $request['Notes'];

        // CREATE LOG
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateIDandRandString();
        $timeFrame->ServiceConnectionId = $request['id'];
        $timeFrame->UserId = "0";
        $timeFrame->Status = '[WEB] Energization Uploaded By Linemen';
        $timeFrame->Notes = "Application uploaded by linemen/crew from Linemen's App.";
        $timeFrame->save();

        if ($serviceConnections->save()) {

            return response()->json(['success' => 'Upload Success'], $this->successStatus);             
        } else {
            return response()->json(['error' => 'Error Uploading Data ID ' . $request['id']], 404); 
        }
    }

    public function createTimeFrames(Request $request) {
        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = $request['id'];
        $timeFrame->ServiceConnectionId = $request['ServiceConnectionId'];
        $timeFrame->UserId = $request['User'];
        $timeFrame->Status = $request['Status'];
        $timeFrame->created_at = $request['created_at'];
        $timeFrame->updated_at = $request['updated_at'];
        $timeFrame->Notes = 'Crew arrived at ' . date('F d, Y h:i:s A', strtotime($request['ArrivalDate'])) . '<br>' . 'Performed energization attempt at ' . date('F d, Y h:i:s A', strtotime($request['EnergizationDate'])) . '<br>' . $request['Reason'];
            
        if ($timeFrame->save()) {
            return response()->json(['success' => 'Upload Success'], $this->successStatus);             
        } else {
            return response()->json(['error' => 'Error Uploading Data ID ' . $request['ServiceConnectionId']], 404); 
        }
    }

    public function saveUploadedImages(Request $request) {
        $files = $_FILES['files'];

        $path = ServiceConnections::filePath() . $request['svcId'] . "/images/";
        File::makeDirectory($path, $mode = 0777, true, true);

        foreach ($_FILES["files"]["name"] as $key => $fileName) {
            $tempFileName = $_FILES["files"]["tmp_name"][$key];
            $targetFileName = $path . basename($fileName);
    
            // Move the uploaded file to the target directory
            move_uploaded_file($tempFileName, $targetFileName);
        }

        return response()->json('ok', 200);
    }

    public function receiveMastPoles(Request $request) {
        $mastPole = MastPoles::where('ServiceConnectionId', $request['ServiceConnectionId'])
            ->where('Latitude', $request['Latitude'])
            ->where('Longitude', $request['Longitude'])
            ->first();

        if ($mastPole != null) {

        } else {
            $mastPole = new MastPoles;
            $mastPole->id = $request['id'];
            $mastPole->ServiceConnectionId = $request['ServiceConnectionId'];
            $mastPole->Latitude = $request['Latitude'];
            $mastPole->Longitude = $request['Longitude'];
            $mastPole->PoleRemarks = $request['PoleRemarks'];
            $mastPole->DateTimeTaken = $request['DateTimeTaken'];
            $mastPole->save();
        }

        return response()->json($mastPole, 200);
    }

    public function receiveMeterInstallations(Request $request) {
        $input = $request->all();

        $id = $input['id'];
        $input['CustomerSignature'] = null; // temporarily hold signature upload

        $sc = MeterInstallation::where('ServiceConnectionId', $input['ServiceConnectionId'])->first();

        if ($sc != null) {
            $sc->Type = $input['Type'];
            $sc->NewMeterNumber = $input['NewMeterNumber'];
            $sc->NewMeterBrand = $input['NewMeterBrand'];
            $sc->NewMeterSize = $input['NewMeterSize'];
            $sc->NewMeterType = $input['NewMeterType'];
            $sc->NewMeterAmperes = $input['NewMeterAmperes'];
            $sc->NewMeterInitialReading = $input['NewMeterInitialReading'];
            $sc->NewMeterLineToNeutral = $input['NewMeterLineToNeutral'];
            $sc->NewMeterLineToGround = $input['NewMeterLineToGround'];
            $sc->NewMeterNeutralToGround = $input['NewMeterNeutralToGround'];
            $sc->DateInstalled = $input['DateInstalled'];
            $sc->NewMeterMultiplier = $input['NewMeterMultiplier'];
            $sc->TransfomerCapacity = $input['TransfomerCapacity'];
            $sc->TransformerID = $input['TransformerID'];
            $sc->PoleID = $input['PoleID'];
            $sc->CTSerialNumber = $input['CTSerialNumber'];
            $sc->NewMeterRemarks = $input['NewMeterRemarks'];
            $sc->OldMeterNumber = $input['OldMeterNumber'];
            $sc->OldMeterBrand = $input['OldMeterBrand'];
            $sc->OldMeterSize = $input['OldMeterSize'];
            $sc->OldMeterType = $input['OldMeterType'];
            $sc->DateRemoved = $input['DateRemoved'];
            $sc->ReasonForChanging = $input['ReasonForChanging'];
            $sc->OldMeterMultiplier = $input['OldMeterMultiplier'];
            $sc->OldMeterRemarks = $input['OldMeterRemarks'];
            $sc->save();
            
            // CREATE LOG
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateIDandRandString();
            $timeFrame->ServiceConnectionId = $request['ServiceConnectionId'];
            $timeFrame->UserId = "0";
            $timeFrame->Status = '[WEB] Meter Installation Updated';
            $timeFrame->Notes = "Meter installation updated by linemen/crew from Linemen's App.";
            $timeFrame->save();
        } else {
            $meterInstallation = MeterInstallation::find($id);

            if ($meterInstallation != null) {
                $meterInstallation->Type = $input['Type'];
                $meterInstallation->NewMeterNumber = $input['NewMeterNumber'];
                $meterInstallation->NewMeterBrand = $input['NewMeterBrand'];
                $meterInstallation->NewMeterSize = $input['NewMeterSize'];
                $meterInstallation->NewMeterType = $input['NewMeterType'];
                $meterInstallation->NewMeterAmperes = $input['NewMeterAmperes'];
                $meterInstallation->NewMeterInitialReading = $input['NewMeterInitialReading'];
                $meterInstallation->NewMeterLineToNeutral = $input['NewMeterLineToNeutral'];
                $meterInstallation->NewMeterLineToGround = $input['NewMeterLineToGround'];
                $meterInstallation->NewMeterNeutralToGround = $input['NewMeterNeutralToGround'];
                $meterInstallation->DateInstalled = $input['DateInstalled'];
                $meterInstallation->NewMeterMultiplier = $input['NewMeterMultiplier'];
                $meterInstallation->TransfomerCapacity = $input['TransfomerCapacity'];
                $meterInstallation->TransformerID = $input['TransformerID'];
                $meterInstallation->PoleID = $input['PoleID'];
                $meterInstallation->CTSerialNumber = $input['CTSerialNumber'];
                $meterInstallation->NewMeterRemarks = $input['NewMeterRemarks'];
                $meterInstallation->OldMeterNumber = $input['OldMeterNumber'];
                $meterInstallation->OldMeterBrand = $input['OldMeterBrand'];
                $meterInstallation->OldMeterSize = $input['OldMeterSize'];
                $meterInstallation->OldMeterType = $input['OldMeterType'];
                $meterInstallation->DateRemoved = $input['DateRemoved'];
                $meterInstallation->ReasonForChanging = $input['ReasonForChanging'];
                $meterInstallation->OldMeterMultiplier = $input['OldMeterMultiplier'];
                $meterInstallation->OldMeterRemarks = $input['OldMeterRemarks'];
                $meterInstallation->save();

                // CREATE LOG
                $timeFrame = new ServiceConnectionTimeframes;
                $timeFrame->id = IDGenerator::generateIDandRandString();
                $timeFrame->ServiceConnectionId = $request['ServiceConnectionId'];
                $timeFrame->UserId = "0";
                $timeFrame->Status = '[WEB] Meter Installation Updated';
                $timeFrame->Notes = "Meter installation updated by linemen/crew from Linemen's App.";
                $timeFrame->save();
            } else {
                $meterInstallation = MeterInstallation::create($input);

                // CREATE LOG
                $timeFrame = new ServiceConnectionTimeframes;
                $timeFrame->id = IDGenerator::generateIDandRandString();
                $timeFrame->ServiceConnectionId = $request['ServiceConnectionId'];
                $timeFrame->UserId = "0";
                $timeFrame->Status = '[WEB] Meter Installation Created';
                $timeFrame->Notes = "Meter installation created by linemen/crew from Linemen's App.";
                $timeFrame->save();
            }
        }
        
        return response()->json($meterInstallation, 200);
    }

    public function receiveLineAndMetering(Request $request) {
        $input = $request->all();

        $id = $input['id'];

        $sc = LineAndMeteringServices::where('ServiceConnectionId', $input['ServiceConnectionId'])->first();

        if ($sc != null) {
            $sc->TypeOfService = ['TypeOfService'];
            $sc->MeterSealNumber = ['MeterSealNumber'];
            $sc->IsLeadSeal = ['IsLeadSeal'];
            $sc->MeterStatus = ['MeterStatus'];
            $sc->MeterNumber = ['MeterNumber'];
            $sc->Multiplier = ['Multiplier'];
            $sc->MeterType = ['MeterType'];
            $sc->MeterBrand = ['MeterBrand'];
            $sc->Notes = ['Notes'];
            $sc->ServiceDate = ['ServiceDate'];
            $sc->UserId = ['UserId'];
            $sc->PrivateElectrician = ['PrivateElectrician'];
            $sc->LineLength = ['LineLength'];
            $sc->ConductorType = ['ConductorType'];
            $sc->ConductorSize = ['ConductorSize'];
            $sc->ConductorUnit = ['ConductorUnit'];
            $sc->Status = ['Status'];
            $sc->AccountNumber = ['AccountNumber'];
            $sc->save();
            
            // CREATE LOG
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateIDandRandString();
            $timeFrame->ServiceConnectionId = $request['ServiceConnectionId'];
            $timeFrame->UserId = "0";
            $timeFrame->Status = '[WEB] Line & Metering Updated';
            $timeFrame->Notes = "Line and metering services data updated by linemen/crew from Linemen's App.";
            $timeFrame->save();
        } else {
            $lineAndMetering = LineAndMeteringServices::find($id);

            if ($lineAndMetering != null) {
                $lineAndMetering->TypeOfService = ['TypeOfService'];
                $lineAndMetering->MeterSealNumber = ['MeterSealNumber'];
                $lineAndMetering->IsLeadSeal = ['IsLeadSeal'];
                $lineAndMetering->MeterStatus = ['MeterStatus'];
                $lineAndMetering->MeterNumber = ['MeterNumber'];
                $lineAndMetering->Multiplier = ['Multiplier'];
                $lineAndMetering->MeterType = ['MeterType'];
                $lineAndMetering->MeterBrand = ['MeterBrand'];
                $lineAndMetering->Notes = ['Notes'];
                $lineAndMetering->ServiceDate = ['ServiceDate'];
                $lineAndMetering->UserId = ['UserId'];
                $lineAndMetering->PrivateElectrician = ['PrivateElectrician'];
                $lineAndMetering->LineLength = ['LineLength'];
                $lineAndMetering->ConductorType = ['ConductorType'];
                $lineAndMetering->ConductorSize = ['ConductorSize'];
                $lineAndMetering->ConductorUnit = ['ConductorUnit'];
                $lineAndMetering->Status = ['Status'];
                $lineAndMetering->AccountNumber = ['AccountNumber'];
                $lineAndMetering->save();

                // CREATE LOG
                $timeFrame = new ServiceConnectionTimeframes;
                $timeFrame->id = IDGenerator::generateIDandRandString();
                $timeFrame->ServiceConnectionId = $request['ServiceConnectionId'];
                $timeFrame->UserId = "0";
                $timeFrame->Status = '[WEB] Line & Metering Updated';
                $timeFrame->Notes = "Line and metering services data updated by linemen/crew from Linemen's App.";
                $timeFrame->save();
            } else {
                $lineAndMetering = LineAndMeteringServices::create($input);

                // CREATE LOG
                $timeFrame = new ServiceConnectionTimeframes;
                $timeFrame->id = IDGenerator::generateIDandRandString();
                $timeFrame->ServiceConnectionId = $request['ServiceConnectionId'];
                $timeFrame->UserId = "0";
                $timeFrame->Status = '[WEB] Line & Metering Created';
                $timeFrame->Notes = "Line and metering services data created by linemen/crew from Linemen's App.";
                $timeFrame->save();
            }
        }
        
        return response()->json($lineAndMetering, 200);
    }
}