<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceConnections;
use App\Models\ServiceConnectionInspections;
use App\Models\ServiceConnectionTimeframes;
use App\Models\IDGenerator;
use App\Models\ServiceConnectionPayTransaction;
use App\Models\ServiceConnectionTotalPayments;
use App\Models\Notifications;
use App\Models\Zones;
use App\Models\Blocks;
use App\Models\SmsSettings;
use App\Models\MaterialPresets;
use Illuminate\Support\Facades\DB;
use Validator;

class ServiceConnectionInspectionsAPI extends Controller {

    public $successStatus = 200;

    public function getServiceConnections(Request $request) {
        $serviceConnections = DB::table('CRM_ServiceConnectionInspections')
            ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->select('CRM_ServiceConnections.*',
                'CRM_Barangays.Barangay AS BarangayFull',
                'CRM_Towns.Town AS TownFull')
            // ->where('CRM_ServiceConnections.Status', "For Inspection")
            // ->where(function($query) {
            //     $query->where('CRM_ServiceConnections.Status', "For Inspection")
            //         ->orWhere('CRM_ServiceConnections.Status', "Re-Inspection");
            // })
            ->where('CRM_ServiceConnectionInspections.Inspector', $request['userid'])
            ->whereRaw("(Trash IS NULL OR Trash='No')")
            ->whereRaw("(InspectionSchedule <= '" . date('Y-m-d') . "' AND CRM_ServiceConnections.Status IN ('For Inspection') AND ReInspectionSchedule IS NULL) OR 
                (ReInspectionSchedule IS NOT NULL AND ReInspectionSchedule <= '" . date('Y-m-d') . "' AND CRM_ServiceConnections.Status='Re-Inspection' AND CRM_ServiceConnectionInspections.Inspector='" . $request['userid'] . "')")
            ->get(); 

        if ($serviceConnections == null) {
            return response()->json(['error' => 'No data'], 404); 
        } else {
            return response()->json($serviceConnections, $this->successStatus); 
        }  
    }

    public function getServiceInspections(Request $request) {
        $serviceConnections = DB::table('CRM_ServiceConnectionInspections')
            ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->select('CRM_ServiceConnectionInspections.*',
                'CRM_Barangays.Barangay AS BarangayFull',
                'CRM_Towns.Town AS TownFull',
                'CRM_ServiceConnections.Zone',
                'CRM_ServiceConnections.Block',
                'CRM_ServiceConnections.Feeder')
            // ->where('CRM_ServiceConnections.Status', "For Inspection")
            // ->where(function($query) {
            //     $query->where('CRM_ServiceConnections.Status', "For Inspection")
            //         ->orWhere('CRM_ServiceConnections.Status', "Re-Inspection");
            // })
            ->where('CRM_ServiceConnectionInspections.Inspector', $request['userid'])
            ->whereRaw("(Trash IS NULL OR Trash='No')")
            ->whereRaw("(InspectionSchedule <= '" . date('Y-m-d') . "' AND CRM_ServiceConnections.Status IN ('For Inspection') AND ReInspectionSchedule IS NULL) OR 
                (ReInspectionSchedule IS NOT NULL AND ReInspectionSchedule <= '" . date('Y-m-d') . "' AND CRM_ServiceConnections.Status='Re-Inspection' AND CRM_ServiceConnectionInspections.Inspector='" . $request['userid'] . "')")
            ->get(); 

        if ($serviceConnections == null) {
            return response()->json(['error' => 'No data'], 404); 
        } else {
            return response()->json($serviceConnections, $this->successStatus); 
        }  
    }

    public function updateServiceInspections(Request $request) {
        $serviceConnectionInspections = ServiceConnectionInspections::find($request['id']);
        $serviceConnections = ServiceConnections::find($request['ServiceConnectionId']);

        $serviceConnectionInspections->SEMainCircuitBreakerAsInstalled = $request['SEMainCircuitBreakerAsInstalled'];
        $serviceConnectionInspections->SENoOfBranchesAsInstalled = $request['SENoOfBranchesAsInstalled'];
        $serviceConnectionInspections->PoleGIEstimatedDiameter = $request['PoleGIEstimatedDiameter'];
        $serviceConnectionInspections->PoleGIHeight = $request['PoleGIHeight'];
        $serviceConnectionInspections->PoleGINoOfLiftPoles = $request['PoleGINoOfLiftPoles'];
        $serviceConnectionInspections->PoleConcreteEstimatedDiameter = $request['PoleConcreteEstimatedDiameter'];
        $serviceConnectionInspections->PoleConcreteHeight = $request['PoleConcreteHeight'];
        $serviceConnectionInspections->PoleConcreteNoOfLiftPoles = $request['PoleConcreteNoOfLiftPoles'];
        $serviceConnectionInspections->PoleHardwoodEstimatedDiameter = $request['PoleHardwoodEstimatedDiameter'];
        $serviceConnectionInspections->PoleHardwoodHeight = $request['PoleHardwoodHeight'];
        $serviceConnectionInspections->PoleHardwoodNoOfLiftPoles = $request['PoleHardwoodNoOfLiftPoles'];
        $serviceConnectionInspections->PoleRemarks = $request['PoleRemarks'];
        $serviceConnectionInspections->SDWSizeAsInstalled = $request['SDWSizeAsInstalled'];
        $serviceConnectionInspections->SDWLengthAsInstalled = $request['SDWLengthAsInstalled'];
        $serviceConnectionInspections->GeoBuilding = $request['GeoBuilding'];
        $serviceConnectionInspections->GeoTappingPole = $request['GeoTappingPole'];
        $serviceConnectionInspections->GeoMeteringPole = $request['GeoMeteringPole'];
        $serviceConnectionInspections->GeoSEPole = $request['GeoSEPole'];
        $serviceConnectionInspections->FirstNeighborName = $request['FirstNeighborName'];
        $serviceConnectionInspections->FirstNeighborMeterSerial = $request['FirstNeighborMeterSerial'];
        $serviceConnectionInspections->SecondNeighborName = $request['SecondNeighborName'];
        $serviceConnectionInspections->SecondNeighborMeterSerial = $request['SecondNeighborMeterSerial'];
        $serviceConnectionInspections->Status = $request['Status'];
        $serviceConnectionInspections->DateOfVerification = $request['DateOfVerification'];
        $serviceConnectionInspections->EstimatedDateForReinspection = $request['EstimatedDateForReinspection'];
        $serviceConnectionInspections->Notes = $request['Notes'];
        $serviceConnectionInspections->Inspector = $request['Inspector'];

        $serviceConnections->Status = $request['Status'];
        $serviceConnections->Zone = $request['Zone'];
        $serviceConnections->Block = $request['Block'];
        $serviceConnections->Feeder = $request['Feeder'];
        $serviceConnections->LoadType = $request['LoadType'];
        $serviceConnections->PoleNumber = $request['PoleNo'];
        
        $serviceConnectionInspections->Rate = $request['Rate'];
        $serviceConnectionInspections->LightingOutlets = $request['LightingOutlets'];
        $serviceConnectionInspections->ConvenienceOutlets = $request['ConvenienceOutlets'];
        $serviceConnectionInspections->Motor = $request['Motor'];
        $serviceConnectionInspections->TotalLoad = $request['TotalLoad'];
        $serviceConnectionInspections->ContractedDemand = $request['ContractedDemand'];
        $serviceConnectionInspections->ContractedEnergy = $request['ContractedEnergy'];
        $serviceConnectionInspections->DistanceFromSecondaryLine = $request['DistanceFromSecondaryLine'];
        $serviceConnectionInspections->SizeOfSecondary = $request['SizeOfSecondary'];
        $serviceConnectionInspections->SizeOfSDW = $request['SizeOfSDW'];
        $serviceConnectionInspections->TypeOfSDW = $request['TypeOfSDW'];
        $serviceConnectionInspections->ServiceEntranceStatus = $request['ServiceEntranceStatus'];
        $serviceConnectionInspections->HeightOfSDW = $request['HeightOfSDW'];
        $serviceConnectionInspections->DistanceFromTransformer = $request['DistanceFromTransformer'];
        $serviceConnectionInspections->SizeOfTransformer = $request['SizeOfTransformer'];
        $serviceConnectionInspections->TransformerNo = $request['TransformerNo'];
        $serviceConnectionInspections->PoleNo = $request['PoleNo'];
        $serviceConnectionInspections->ConnectedFeeder = $request['ConnectedFeeder'];
        $serviceConnectionInspections->SizeOfSvcPoles = $request['SizeOfSvcPoles'];
        $serviceConnectionInspections->HeightOfSvcPoles = $request['HeightOfSvcPoles'];
        $serviceConnectionInspections->LinePassingPrivateProperty = $request['LinePassingPrivateProperty'];
        $serviceConnectionInspections->WrittenConsentByPropertyOwner = $request['WrittenConsentByPropertyOwner'];
        $serviceConnectionInspections->ObstructionOfLines = $request['ObstructionOfLines'];
        $serviceConnectionInspections->LinePassingRoads = $request['LinePassingRoads'];
        $serviceConnectionInspections->Recommendation = $request['Recommendation'];
        $serviceConnectionInspections->ForPayment = $request['ForPayment'];
        $serviceConnectionInspections->BillDeposit = $request['BillDeposit'];
        $serviceConnectionInspections->MeteringType = $request['MeteringType'];

        if ($request['Status'] === 'Re-Inspection') {
            $serviceConnectionInspections->ReInspectionSchedule = null;
        }

        $serviceConnectionInspections->save();

        $serviceConnections->save();

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateIDandRandString();
        $timeFrame->ServiceConnectionId = $request['ServiceConnectionId'];
        $timeFrame->UserId = $request['Inspector'];
        $timeFrame->Status = '[WEB] ' . $request['Status'];
        if ($request['Status'] == 'Approved') {
            $timeFrame->Notes = 'Inspection approved and is waiting for payment';
        } else {
            $timeFrame->Notes = $request['Notes'];
        }
        
        $timeFrame->save();

        return response()->json(['ok' => 'ok'], $this->successStatus);
    }

    public function updateDownloadedInspection(Request $request) {
        $data = $request['Data'];

        $arr = explode(",", $data);

        $len = count($arr);
        for ($i=0; $i<$len; $i++) {
            $serviceConnections = ServiceConnections::find($arr[$i]);

            // SEND SMS
            $smsSettings = SmsSettings::orderByDesc('created_at')->first();
            // InspectionToday
            if ($serviceConnections != null) {
                $inspection = ServiceConnectionInspections::where('ServiceConnectionId', $serviceConnections->id)->first();
                if ($inspection != null) {
                    // CREATE Timeframes
                    $timeFrame = new ServiceConnectionTimeframes;
                    $timeFrame->id = IDGenerator::generateIDandRandString();
                    $timeFrame->ServiceConnectionId = $arr[$i];
                    $timeFrame->Status = '[WEB] Inspection Downloaded';
                    $timeFrame->UserId = $inspection->Inspector;
                    $timeFrame->Notes = "Inspection data downloaded to Inspector's App.";
                    $timeFrame->save();
                }

                if ($serviceConnections->ContactNumber != null) {
                    if (strlen($serviceConnections->ContactNumber) > 10 && strlen($serviceConnections->ContactNumber) < 13) {
                        if ($smsSettings != null && $smsSettings->InspectionToday=='Yes') {
                            $msg = "BLCI Notification\n\nHello " . $serviceConnections->ServiceAccountName . ", \nYour " . $serviceConnections->AccountApplicationType . " application with control no. " . $arr[$i] . " is due for inspection today. " .
                                "Expect a BLCI Inspector to visit your household until 5PM. \nHave a great day!";
                            Notifications::createFreshSms($serviceConnections->ContactNumber, $msg, 'SERVICE CONNECTION INSPECTION', $arr[$i]);
                        }
                    }                    
                } 
            }
        }

        return response()->json($arr, 200);
    }

    public function receiveBillDeposits(Request $request) {
        if ($request['ServiceConnectionId'] != null) {
            $depostData = ServiceConnectionPayTransaction::where('ServiceConnectionId', $request['ServiceConnectionId'])
                ->where('Particular', ServiceConnections::getBillDepositId())
                ->first();

            if ($depostData != null) {
                //update bill deposit
                $depostData->Amount = $request['Amount'];
                $depostData->Total = $request['Total'];
                $depostData->save();
            } else {
                // insert bill deposit
                $depostData = new ServiceConnectionPayTransaction;
                $depostData->id = $request['id'];
                $depostData->ServiceConnectionId = $request['ServiceConnectionId'];
                $depostData->Particular = $request['Particular'];
                $depostData->Amount = $request['Amount'];
                $depostData->Vat = $request['Vat'];
                $depostData->Total = $request['Total'];
                $depostData->save();
            }

            // update total payment
            $totalPayments = ServiceConnectionTotalPayments::where('ServiceConnectionId', $request['ServiceConnectionId'])
                ->first();

            if ($totalPayments != null) {
                $transactions = ServiceConnectionPayTransaction::where('ServiceConnectionId', $request['ServiceConnectionId'])
                    ->get();
                
                $amnt = 0;
                $vat = 0;
                $ttl = 0;
                foreach($transactions as $item) {
                    $amnt += floatval($item->Amount);
                    $vat += floatval($item->Vat);
                    $ttl += floatval($item->Total);
                }

                $totalPayments->SubTotal = round($amnt, 2);
                $totalPayments->TotalVat = round($vat, 2);
                $totalPayments->Total = round($ttl, 2);
                $totalPayments->save();
            } else {
                $transactions = new ServiceConnectionPayTransaction;
                $transactions->id = IDGenerator::generateIDandRandString();
                $transactions->ServiceConnectionId = $request['ServiceConnectionId'];
                $transactions->SubTotal = $request['Amount'];
                $transactions->Total = $request['Total'];
                $transactions->save();
            }

            return response()->json($depostData, 200);
        } else {
            return response()->json(['res' => 'empty sc id'], 200);
        }
    }

    public function getZones() {
        return response()->json(Zones::all(), 200);
    }

    public function getBlocks() {
        return response()->json(Blocks::all(), 200);
    }

    public function getFiles(Request $request) {
        $id = $request['id'];

        $folderPath  = ServiceConnections::filePath() . $id;
        $zipFileName = $id . '.zip';
        $tempZipPath = tempnam(sys_get_temp_dir(), $zipFileName);

        if (file_exists($folderPath) && is_dir($folderPath)) {
            $zip = new \ZipArchive();
            if ($zip->open($tempZipPath, \ZipArchive::CREATE) === TRUE) {
                // Add files to the ZIP file
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($folderPath),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );
            
                foreach ($files as $name => $file) {
                    // Skip directories (they would be added automatically)
                    if (!$file->isDir()) {
                        // Get real and relative path for current file
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($folderPath) + 1);
            
                        // Add current file to archive
                        $zip->addFile($filePath, $relativePath);
                    }
                }
                // Zip archive will be created only after closing object
                $zip->close();
            
                // Send the file to the client
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . basename($zipFileName) . '"');
                header('Content-Length: ' . filesize($tempZipPath));
                readfile($tempZipPath);
                // Delete the temporary file
                unlink($tempZipPath);

                // CREATE Timeframes
                $timeFrame = new ServiceConnectionTimeframes;
                $timeFrame->id = IDGenerator::generateIDandRandString();
                $timeFrame->ServiceConnectionId = $request['id'];
                $timeFrame->Status = '[WEB] Files Sent to Device';
                $timeFrame->UserId = '0';
                $timeFrame->Notes = 'Attached files successfully compressed and sent to device.';
                $timeFrame->save();
            } else {
                // CREATE Timeframes
                $timeFrame = new ServiceConnectionTimeframes;
                $timeFrame->id = IDGenerator::generateIDandRandString();
                $timeFrame->ServiceConnectionId = $request['id'];
                $timeFrame->Status = '[WEB] Error Zipping Files';
                $timeFrame->UserId = '0';
                $timeFrame->Notes = 'Unable to zip attached files.';
                $timeFrame->save();

                return response()->json('Error zipping file', 403);
            }
        } else {
            // CREATE Timeframes
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateIDandRandString();
            $timeFrame->ServiceConnectionId = $request['id'];
            $timeFrame->Status = '[WEB] Error Attaching Files';
            $timeFrame->UserId = '0';
            $timeFrame->Notes = 'File folder not found.';
            $timeFrame->save();

            return response()->json('File folder not found', 200);
        }
    }

    public function receiveMaterialPresets(Request $request) {
        $input = $request->all();

        $scId = $input['ServiceConnectionId'];

        $materials = MaterialPresets::where('ServiceConnectionId', $scId)->first();

        if ($materials != null) {
            $materials->MeterBaseSocket = isset($input['MeterBaseSocket']) ? $input['MeterBaseSocket'] : '';
            $materials->MetalboxTypeA = isset($input['MetalboxTypeA']) ? $input['MetalboxTypeA'] : '';
            $materials->MetalboxTypeB = isset($input['MetalboxTypeB']) ? $input['MetalboxTypeB'] : '';
            $materials->Pipe = isset($input['Pipe']) ? $input['Pipe'] : '';
            $materials->EntranceCap = isset($input['EntranceCap']) ? $input['EntranceCap'] : '';
            $materials->Adapter = isset($input['Adapter']) ? $input['Adapter'] : '';
            $materials->Locknot = isset($input['Locknot']) ? $input['Locknot'] : '';
            $materials->Mailbox = isset($input['Mailbox']) ? $input['Mailbox'] : '';
            $materials->Buckle = isset($input['Buckle']) ? $input['Buckle'] : '';
            $materials->PvcElbow = isset($input['PvcElbow']) ? $input['PvcElbow'] : '';
            $materials->StainlessStrap = isset($input['StainlessStrap']) ? $input['StainlessStrap'] : '';
            $materials->Plyboard = isset($input['Plyboard']) ? $input['Plyboard'] : '';
            $materials->StrainInsulator = isset($input['StrainInsulator']) ? $input['StrainInsulator'] : '';
            $materials->StraindedWireEight = isset($input['StraindedWireEight']) ? $input['StraindedWireEight'] : '';
            $materials->StrandedWireSix = isset($input['StrandedWireSix']) ? $input['StrandedWireSix'] : '';
            $materials->TwistedWireSix = isset($input['TwistedWireSix']) ? $input['TwistedWireSix'] : '';
            $materials->TwistedWireFour = isset($input['TwistedWireFour']) ? $input['TwistedWireFour'] : '';
            $materials->CompressionTapAsu = isset($input['CompressionTapAsu']) ? $input['CompressionTapAsu'] : '';
            $materials->CompressionTapYtdTwoFifty = isset($input['CompressionTapYtdTwoFifty']) ? $input['CompressionTapYtdTwoFifty'] : '';
            $materials->CompressionTapYtdThreeHundred = isset($input['CompressionTapYtdThreeHundred']) ? $input['CompressionTapYtdThreeHundred'] : '';
            $materials->CompressionTapYtdTwoHundred = isset($input['CompressionTapYtdTwoHundred']) ? $input['CompressionTapYtdTwoHundred'] : '';
            $materials->CompressionTapYtdOneFifty = isset($input['CompressionTapYtdOneFifty']) ? $input['CompressionTapYtdOneFifty'] : '';
            $materials->MetalScrew = isset($input['MetalScrew']) ? $input['MetalScrew'] : '';
            $materials->ElectricalTape = isset($input['ElectricalTape']) ? $input['ElectricalTape'] : '';
            $materials->CompressionTapYtdOneHundred = isset($input['CompressionTapYtdOneHundred']) ? $input['CompressionTapYtdOneHundred'] : '';
            $materials->save();
        } else {
            $materials = MaterialPresets::create($input);
        }

        return response()->json($materials, 200);
    }

    public function applicationsLogger(Request $request) {
        $scId = $request['ServiceConnectionId'];
        $status = $request['Status'];
        $notes = $request['Notes'];
        $userId = $request['UserId'];

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateIDandRandString();
        $timeFrame->ServiceConnectionId = $scId;
        $timeFrame->Status = $status;
        $timeFrame->UserId = $userId;
        $timeFrame->Notes = $notes;
        $timeFrame->save();

        return response()->json($timeFrame, 200);
    }
}