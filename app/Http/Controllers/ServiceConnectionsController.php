<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionsRequest;
use App\Http\Requests\UpdateServiceConnectionsRequest;
use App\Repositories\ServiceConnectionsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\MemberConsumers;
use App\Models\MemberConsumerSpouse;
use App\Models\Towns;
use App\Models\Barangays;
use App\Models\ServiceAppliedFor;
use App\Models\ServiceConnectionAccountTypes;
use App\Models\ServiceConnections;
use App\Models\ServiceConnectionInspections;
use App\Models\ServiceConnectionMtrTrnsfrmr;
use App\Models\ServiceConnectionPayTransaction;
use App\Models\ServiceConnectionTotalPayments;
use App\Models\ServiceConnectionTimeframes;
use App\Models\IDGenerator;
use App\Models\ServiceConnectionChecklistsRep;
use App\Models\ServiceConnectionChecklists;
use App\Models\ServiceConnectionCrew;
use App\Models\ServiceConnectionLgLoadInsp;
use App\Models\ServiceConnectionPayParticulars;
use App\Models\StructureAssignments;
use App\Models\Structures;
use App\Models\MaterialAssets;
use App\Models\MeterReaders;
use App\Models\BillsOfMaterialsSummary;
use App\Models\SpanningData;
use App\Models\PoleIndex;
use App\Models\User;
use App\Models\Users;
use App\Models\PreDefinedMaterials;
use App\Models\PreDefinedMaterialsMatrix;
use App\Models\TransactionDetails;
use App\Models\TransactionIndex;
use App\Models\ServiceAccounts;
use App\Models\MemberConsumerTypes;
use App\Models\Signatories;
use App\Models\WarehouseHead;
use App\Models\WarehouseItems;
use App\Models\PaymentOrder;
use App\Models\CostCenters;
use App\Models\ProjectCodes;
use App\Models\MeterInstallation;
use App\Models\Notifications;
use App\Models\SmsSettings;
use App\Exports\ServiceConnectionApplicationsReportExport;
use App\Exports\ServiceConnectionEnergizationReportExport;
use App\Exports\DynamicExportsNoBillingMonth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Flash;
use Response;
use File;

class ServiceConnectionsController extends AppBaseController
{
    /** @var  ServiceConnectionsRepository */
    private $serviceConnectionsRepository;

    public function __construct(ServiceConnectionsRepository $serviceConnectionsRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionsRepository = $serviceConnectionsRepo;
    }

    /**
     * Display a listing of the ServiceConnections.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $params = $request['params'];

        if ($params == null) {
            $data = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId')
                ->select('CRM_ServiceConnections.id as ConsumerId',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.Status as Status',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.ConnectionApplicationType',
                                'CRM_ServiceConnections.Office',
                                'CRM_ServiceConnections.AccountApplicationType',
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnections.ORNumber',
                                'CRM_ServiceConnections.AccountNumber',
                                'CRM_ServiceConnections.LoadCategory',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber')
                ->whereRaw("(CRM_ServiceConnections.Trash IS NULL OR CRM_ServiceConnections.Trash='No')")
                ->orderByDesc('CRM_ServiceConnections.created_at')
                ->paginate(15);
        } else {
            $data = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId')
                ->select('CRM_ServiceConnections.id as ConsumerId',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.Status as Status',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.ConnectionApplicationType',
                                'CRM_ServiceConnections.Office',
                                'CRM_ServiceConnections.AccountApplicationType',
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnections.ORNumber',
                                'CRM_ServiceConnections.AccountNumber',
                                'CRM_ServiceConnections.LoadCategory',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber')
                ->whereRaw("(CRM_ServiceConnections.Trash IS NULL OR CRM_ServiceConnections.Trash='No') 
                    AND (ServiceAccountName LIKE '%" . $params . "%' OR CRM_ServiceConnections.id LIKE '%" . $params . "%' OR CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber LIKE '%" . $params . "%')")
                ->orderBy('CRM_ServiceConnections.created_at')
                ->paginate(15);
        }

        return view('/service_connections/search_applications', [
            'data' => $data
        ]);
    }

    public function dashboard() {
        return view('/service_connections/dashboard');
    }

    /**
     * Show the form for creating a new ServiceConnections.
     *
     * @return Response
     */
    public function create()
    {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc create', 'Super Admin'])) {
            return view('service_connections.create');
        } else {
            return abort(403, "You're not authorized to create a service connection application.");
        }
        
    }

    /**
     * Store a newly created ServiceConnections in storage.
     *
     * @param CreateServiceConnectionsRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionsRequest $request)
    {
        $input = $request->all();

        if ($input['id'] != null) {
            $sc = ServiceConnections::find($input['id']);

            if ($sc != null) {
                $serviceConnections = $this->serviceConnectionsRepository->find($sc->id);

                if (empty($serviceConnections)) {
                    Flash::error('Service Connections not found');
        
                    return redirect(route('serviceConnections.all-applications'));
                }
        
                $serviceConnections = $this->serviceConnectionsRepository->update($request->all(), $sc->id);

                // CREATE INSPECTION DATA
                $inspection = ServiceConnectionInspections::where('ServiceConnectionId', $sc->id)->first();
                if ($inspection == null) {
                    $inspection = new ServiceConnectionInspections;
                    $inspection->id = IDGenerator::generateID();
                    $inspection->ServiceConnectionId = $input['id'];
                    $inspection->Inspector = $input['Inspector'];
                    $inspection->Status = 'FOR INSPECTION';
                    $inspection->InspectionSchedule = $input['InspectionSchedule'];
                    $inspection->save();

                    $inspectorUser = Users::find($input['Inspector']);

                    // CREATE Timeframes
                    $timeFrame = new ServiceConnectionTimeframes;
                    $timeFrame->id = IDGenerator::generateID();
                    $timeFrame->ServiceConnectionId = $input['id'];
                    $timeFrame->UserId = Auth::id();
                    $timeFrame->Status = 'Inspection Scheduled';
                    $timeFrame->Notes = 'Inspection scheduled on ' . date('F d, Y', strtotime($input['InspectionSchedule'])) . ', by ' .  ($inspectorUser != null ? $inspectorUser->name : 'n/a');
                    $timeFrame->save();
                }

                // CREATE Timeframes
                $timeFrame = new ServiceConnectionTimeframes;
                $timeFrame->id = IDGenerator::generateID();
                $timeFrame->ServiceConnectionId = $input['id'];
                $timeFrame->UserId = Auth::id();
                $timeFrame->Status = $sc->Status;
                $timeFrame->Notes = 'Data Updated';
                $timeFrame->save();
                
                // CREATE FOLDER FIRST
                // if (!file_exists('/CRM_FILES//' . $input['id'])) {
                //     mkdir('/CRM_FILES//' . $input['id'], 0777, true);
                // }

                Flash::success('Service Connections saved successfully.');

                return redirect(route('serviceConnections.show', [$input['id']]));
                // return redirect(route('serviceConnections.assess-checklists', [$input['id']]));
            } else {
                $serviceConnections = $this->serviceConnectionsRepository->create($input);

                // SEND SMS
                $smsSettings = SmsSettings::orderByDesc('created_at')->first();
                // ServiceConnectionReception
                if ($input['ContactNumber'] != null) {
                    if (strlen($input['ContactNumber']) > 10 && strlen($input['ContactNumber']) < 13) {
                        if ($smsSettings != null && $smsSettings->ServiceConnectionReception=='Yes') {
                            $msg = "BLCI Notification\n\nHello " . $serviceConnections->ServiceAccountName . ", \nYour " . $input['AccountApplicationType'] . " application with control no. " . $input['id'] . " has been received and will be processed shortly. " .
                            "You will receive several SMS notifications in the future regarding the progress of your application. \nHave a great day!";
                            Notifications::createFreshSms($input['ContactNumber'], $msg, 'SERVICE CONNECTIONS', $input['id']);
                        }                        
                    }                    
                } 

                // CREATE INSPECTION
                $inspection = new ServiceConnectionInspections;
                $inspection->id = IDGenerator::generateID();
                $inspection->ServiceConnectionId = $input['id'];
                $inspection->Inspector = $input['Inspector'];
                $inspection->Status = 'FOR INSPECTION';
                $inspection->InspectionSchedule = $input['InspectionSchedule'];
                $inspection->save();

                // SEND SMS
                // InspectionCreation
                if ($input['ContactNumber'] != null) {
                    if (strlen($input['ContactNumber']) > 10 && strlen($input['ContactNumber']) < 13) {
                        if ($smsSettings != null && $smsSettings->InspectionCreation=='Yes') {
                            $msg = "BLCI Notification\n\nHello " . $serviceConnections->ServiceAccountName . ", \nYour " . $input['AccountApplicationType'] . " application with control no. " . $input['id'] . " has been schedule for inspection on " . date('F d, Y', strtotime($inspection->InspectionSchedule)) . ". " .
                                "Please make sure that your household should have at least one (1) representative during the inspection process. \nHave a great day!";
                            Notifications::createFreshSms($input['ContactNumber'], $msg, 'SERVICE CONNECTIONS', $input['id']);
                        }
                    }                    
                } 

                // CREATE Timeframes
                $timeFrame = new ServiceConnectionTimeframes;
                $timeFrame->id = IDGenerator::generateID();
                $timeFrame->ServiceConnectionId = $input['id'];
                $timeFrame->UserId = Auth::id();
                $timeFrame->Status = 'Received';
                $timeFrame->save();

                $inspectorUser = Users::find($input['Inspector']);

                // CREATE Timeframes
                $timeFrame = new ServiceConnectionTimeframes;
                $timeFrame->id = IDGenerator::generateID();
                $timeFrame->ServiceConnectionId = $input['id'];
                $timeFrame->UserId = Auth::id();
                $timeFrame->Status = 'Inspection Scheduled';
                $timeFrame->Notes = 'Inspection scheduled on ' . date('F d, Y', strtotime($input['InspectionSchedule'])) . ', by ' .  ($inspectorUser != null ? $inspectorUser->name : 'n/a');
                $timeFrame->save();
                
                // CREATE FOLDER FIRST
                if (!file_exists('/CRM_FILES//' . $input['id'])) {
                    mkdir('/CRM_FILES//' . $input['id'], 0777, true);
                }

                Flash::success('Service Connections saved successfully.');

                return redirect(route('serviceConnections.show', [$input['id']]));
                // return redirect(route('serviceConnections.assess-checklists', [$input['id']]));
            }
        } else {
            return abort('ID Not found!', 404);
        }

        
    }

    /**
     * Display the specified ServiceConnections.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('users', 'CRM_ServiceConnections.UserId', '=', 'users.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.AccountCount as AccountCount', 
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication', 
                        'CRM_ServiceConnections.TimeOfApplication', 
                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.AccountOrganization as AccountOrganization', 
                        'CRM_ServiceConnections.ConnectionApplicationType as ConnectionApplicationType',
                        'CRM_ServiceConnections.MemberConsumerId as MemberConsumerId',
                        'CRM_ServiceConnections.Status as Status',  
                        'CRM_ServiceConnections.Notes as Notes', 
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.LongSpan', 
                        'CRM_ServiceConnections.ORNumber as ORNumber',
                        'CRM_ServiceConnections.ORDate', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.LoadCategory as LoadCategory', 
                        'CRM_ServiceConnections.DateTimeOfEnergization as DateTimeOfEnergization', 
                        'CRM_ServiceConnections.DateTimeLinemenArrived as DateTimeLinemenArrived', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnections.AccountType',
                        'CRM_ServiceConnections.ServiceNumber',
                        'CRM_ServiceConnections.ConnectionSchedule',
                        'CRM_ServiceConnections.LoadType',
                        'CRM_ServiceConnections.Zone',
                        'CRM_ServiceConnections.Block',
                        'CRM_ServiceConnections.TransformerID',
                        'CRM_ServiceConnections.LoadInKva',
                        'CRM_ServiceConnections.PoleNumber',
                        'CRM_ServiceConnections.Feeder',
                        'CRM_ServiceConnections.ChargeTo',
                        'CRM_ServiceConnections.AccountNumber',
                        'CRM_ServiceConnections.CertificateOfConnectionIssuedOn',
                        'users.name'
                        )
        ->where('CRM_ServiceConnections.id', $id)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        $serviceConnectionInspections = DB::table('CRM_ServiceConnectionInspections')
            ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
            ->whereRaw("ServiceConnectionId='" . $id . "'")
            ->select('CRM_ServiceConnectionInspections.*', 'users.name')
            ->orderByDesc('CRM_ServiceConnectionInspections.created_at')
            ->first();
        
        $serviceConnectionMeter = MeterInstallation::where('ServiceConnectionId', $id)->first();

        $timeFrame = DB::table('CRM_ServiceConnectionTimeframes')
                ->leftJoin('users', 'CRM_ServiceConnectionTimeframes.UserId', '=', 'users.id')
                ->select('CRM_ServiceConnectionTimeframes.id',
                        'CRM_ServiceConnectionTimeframes.Status',
                        'CRM_ServiceConnectionTimeframes.created_at',
                        'CRM_ServiceConnectionTimeframes.ServiceConnectionId',
                        'CRM_ServiceConnectionTimeframes.UserId',
                        'CRM_ServiceConnectionTimeframes.Notes',
                        'users.name')
                ->where('CRM_ServiceConnectionTimeframes.ServiceConnectionId', $id)
                ->orderByDesc('created_at')
                ->get();

        
        if (empty($serviceConnections)) {
            Flash::error('Service Connections not found');

            return redirect(route('serviceConnections.all-applications'));
        }

        $paymentOrder = PaymentOrder::where('ServiceConnectionId', $id)->first();

        // ITEMS
        $whHead = WarehouseHead::where('appl_no', $id)->whereRaw("orderno NOT LIKE 'M%'")->first();
        if ($whHead != null) {
            $whItems = DB::connection('mysql')
                ->table('tblor_line')
                ->leftJoin('tblitems', 'tblor_line.itemcd', '=', 'tblitems.itm_code')
                ->whereRaw("reqno='" . $whHead->orderno . "'")
                ->select(
                    'tblor_line.*', 
                    'tblitems.itm_desc'
                    )
                ->orderBy('itemno')
                ->get();
        } else {
            $whItems = [];
        }

        // METERS
        $whHeadMeters = WarehouseHead::where('appl_no', $id)->whereRaw("orderno LIKE 'M%'")->first();
        if ($whHeadMeters != null) {
            $whItemsMeters = DB::connection('mysql')
                ->table('tblor_line')
                ->leftJoin('tblitems', 'tblor_line.itemcd', '=', 'tblitems.itm_code')
                ->whereRaw("reqno='" . $whHeadMeters->orderno . "'")
                ->select(
                    'tblor_line.*', 
                    'tblitems.itm_desc'
                    )
                ->orderBy('itemno')
                ->get();
        } else {
            $whItemsMeters = [];
        }

        $serviceConnectionChecklistsRep = ServiceConnectionChecklistsRep::all();
        
        $serviceConnectionChecklists = ServiceConnectionChecklists::where('ServiceConnectionId', $id)->pluck('ChecklistId')->all();

        $images = Signatories::where('Name', $id)->where('Notes', 'SERVICE CONNECTIONS')->get();

        // FILES
        $path = ServiceConnections::filePath() . "$id/";
        if (file_exists($path) && is_dir($path)) {
            $fileNames = scandir($path);
            $fileNames = array_diff($fileNames, array('.', '..'));
        } else {
            $fileNames = [];
        }

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['view membership', 'sc view', 'Super Admin'])) {
            return view('service_connections.show', [
                            'serviceConnections' => $serviceConnections, 
                            'serviceConnectionInspections' => $serviceConnectionInspections, 
                            'serviceConnectionMeter' => $serviceConnectionMeter, 
                            'timeFrame' => $timeFrame,
                            'serviceConnectionChecklistsRep' => $serviceConnectionChecklistsRep,
                            'serviceConnectionChecklists' => $serviceConnectionChecklists,
                            'images' => $images,
                            'paymentOrder' => $paymentOrder,
                            'whHead' => $whHead,
                            'whItems' => $whItems,
                            'whHeadMeters' => $whHeadMeters,
                            'whItemsMeters' => $whItemsMeters,
                            'fileNames' => $fileNames,
                        ]);
        } else {
            return abort(403, "You're not authorized to view a service connection application.");
        }        
    }

    /**
     * Show the form for editing the specified ServiceConnections.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnections = $this->serviceConnectionsRepository->find($id);

        $cond = 'edit';

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        $accountTypes = ServiceConnectionAccountTypes::orderBy('id')->get();

        $serviceAppliedFor = ServiceAppliedFor::orderBy('ServiceAppliedFor')->get();

        $inspectors = User::role('Inspector')->get();

        $inspection = ServiceConnectionInspections::where('ServiceConnectionId', $id)->first();

        if (empty($serviceConnections)) {
            Flash::error('Service Connections not found');

            return redirect(route('serviceConnections.all-applications'));
        }

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['update membership', 'sc update', 'Super Admin'])) {
            return view('service_connections.edit', [
                'serviceConnections' => $serviceConnections, 
                'cond' => $cond, 
                'towns' => $towns, 
                'accountTypes' => $accountTypes, 
                'serviceAppliedFor' => $serviceAppliedFor,
                'inspectors' => $inspectors,
                'inspection' => $inspection,
            ]);
        } else {
            return abort(403, "You're not authorized to update a service connection application.");
        }         
    }

    /**
     * Update the specified ServiceConnections in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionsRequest $request)
    {
        $serviceConnections = $this->serviceConnectionsRepository->find($id);

        if (empty($serviceConnections)) {
            Flash::error('Service Connections not found');

            return redirect(route('serviceConnections.all-applications'));
        }

        $serviceConnections = $this->serviceConnectionsRepository->update($request->all(), $id);

        // UPDATE SERVICE INSPECTIONS
        $inspection = ServiceConnectionInspections::where('ServiceConnectionId', $id)->first();
        if ($inspection != null) {
            $inspection->Inspector = $request['Inspector'];
            $inspection->InspectionSchedule = $request['InspectionSchedule'];
            $inspection->save();
        }

        Flash::success('Service Connections updated successfully.');

        // return redirect(route('serviceConnections.index'));
        return redirect()->action([ServiceConnectionsController::class, 'show'], [$id]);
    }

    /**
     * Remove the specified ServiceConnections from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['delete membership', 'sc delete', 'Super Admin'])) {
            $serviceConnections = $this->serviceConnectionsRepository->find($id);

            if (empty($serviceConnections)) {
                Flash::error('Service Connections not found');

                return redirect(route('serviceConnections.all-applications'));
            }

            // $this->serviceConnectionsRepository->delete($id);
            $serviceConnections->Trash='Yes';
            $serviceConnections->save();

            Flash::success('Service Connections deleted successfully.');

            return redirect(route('serviceConnections.all-applications'));
        } else {
            return abort(403, "You're not authorized to delete a service connection application.");
        }          
    }

    public function moveToTrashAjax(Request $request) {
        $id = $request['id'];
        if(Auth::user()->hasAnyPermission(['delete membership', 'sc delete', 'Super Admin'])) {
            $serviceConnections = $this->serviceConnectionsRepository->find($id);

            $serviceConnections->Trash='Yes';
            $serviceConnections->save();

            return response()->json($serviceConnections, 200);
        } else {
            return abort(403, "You're not authorized to delete a service connection application.");
        }          
    }

    public function selectMembership() {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc create', 'Super Admin'])) {
            return view('/service_connections/selectmembership');
        } else {
            return abort(403, "You're not authorized to create a service connection application.");
        }
    }

    public function selectApplicationType($consumerId) {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc create', 'Super Admin'])) {
            return view('/service_connections/select_application_type', ['consumerId' => $consumerId]);
        } else {
            return abort(403, "You're not authorized to create a service connection application.");
        }        
    }

    public function relayApplicationType($consumerId, Request $request) {
        return redirect(route('serviceConnections.create_new', [$consumerId, $request['type']]));
    }

    public function fetchmemberconsumer(Request $request) {
        if ($request->ajax()) {
            $query = $request->get('query');
            
            if ($query != '' ) {
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
                    ->whereRaw("CRM_MemberConsumers.Notes IS NULL OR CRM_MemberConsumers.Notes NOT IN ('BILLING ACCOUNT GROUPING PARENT')")
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
                    ->whereRaw("CRM_MemberConsumers.Notes IS NULL OR CRM_MemberConsumers.Notes NOT IN ('BILLING ACCOUNT GROUPING PARENT')")
                    ->orderByDesc('CRM_MemberConsumers.created_at')
                    ->take(10)
                    ->get();
            }

            $total_row = $data->count();
            if ($total_row > 0) {
                $output = '';
                foreach ($data as $row) {

                    $output .= '
                        <div class="col-md-10 offset-md-1 col-lg-10 offset-lg-1" style="margin-top: 10px;">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div>
                                                <h4>' . MemberConsumers::serializeMemberName($row) . '</h4>
                                                <p class="text-muted" style="margin-bottom: 0;">ID: ' . $row->ConsumerId . '</p>
                                                <p class="text-muted" style="margin-bottom: 0;">' . $row->Barangay . ', ' . $row->Town  . '</p>
                                                <a href="' . route('serviceConnections.create_new', [$row->ConsumerId]) . '" class="btn btn-sm btn-primary" style="margin-top: 5px;">Proceed</a>
                                            </div>     
                                        </div> 

                                        <div class="col-md-6 col-lg-6 d-sm-none d-md-block d-none d-sm-block" style="border-left: 2px solid #007bff; padding-left: 15px;">
                                            <div>
                                                <p class="text-muted" style="margin-bottom: 0;">Birthdate: <strong>' . date('F d, Y', strtotime($row->Birthdate)) . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">Contact No: <strong>' . $row->ContactNumbers . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">Email Add: <strong>' . $row->EmailAddress . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">Membership Type: <strong>' . $row->Type . '</strong></p>
                                            </div>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    ';
                }                
            } else {
                $output = '
                    <p class="text-center">No data found.</p>';
            }

            $data = [
                'table_data' => $output
            ];

            echo json_encode($data);
        }
    }

    public function createNew() {
        $towns = Towns::orderBy('Town')->pluck('Town', 'id');
        $barangays = Barangays::orderBy('Barangay')->get();
        
        $cond = 'new';

        $accountTypes = ServiceConnectionAccountTypes::orderBy('id')->get();

        $serviceAppliedFor = ServiceAppliedFor::orderBy('ServiceAppliedFor')->get();

        $inspectors = User::role('Inspector')->get();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc create', 'Super Admin'])) {
            return view('/service_connections/create_new', [
                'cond' => $cond, 
                'towns' => $towns, 
                'accountTypes' => $accountTypes, 
                'barangays' => $barangays,
                'serviceAppliedFor' => $serviceAppliedFor,
                'inspectors' => $inspectors,
            ]);
        } else {
            return abort(403, "You're not authorized to create a service connection application.");
        }        
    }

    public function fetchserviceconnections(Request $request) {
        if ($request->ajax()) {
            $query = $request->get('query');
            
            if (env('APP_AREA_CODE') == '15') { // IF MAIN OFFICE
                if ($query != '' ) {
                    $data = DB::table('CRM_ServiceConnections')
                        ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                        ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                        ->select('CRM_ServiceConnections.id as ConsumerId',
                                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                        'CRM_ServiceConnections.Status as Status',
                                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                        'CRM_ServiceConnections.AccountCount as AccountCount',  
                                        'CRM_ServiceConnections.Sitio as Sitio', 
                                        'CRM_Towns.Town as Town',
                                        'CRM_ServiceConnections.LoadCategory',
                                        'CRM_ServiceConnections.ConnectionApplicationType',
                                        'CRM_Barangays.Barangay as Barangay')
                        ->where(function ($query) {
                                            $query->where('CRM_ServiceConnections.Trash', 'No')
                                                ->orWhereNull('CRM_ServiceConnections.Trash');
                                        })
                        ->where('CRM_ServiceConnections.ServiceAccountName', 'LIKE', '%' . $query . '%')
                        ->orWhere('CRM_ServiceConnections.Id', 'LIKE', '%' . $query . '%')
                        
                        ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                        ->get();
                } else {
                    $data = DB::table('CRM_ServiceConnections')
                        ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                        ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                        ->select('CRM_ServiceConnections.id as ConsumerId',
                                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                        'CRM_ServiceConnections.Status as Status',
                                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                        'CRM_ServiceConnections.AccountCount as AccountCount',  
                                        'CRM_ServiceConnections.Sitio as Sitio', 
                                        'CRM_Towns.Town as Town',
                                        'CRM_ServiceConnections.LoadCategory',
                                        'CRM_ServiceConnections.ConnectionApplicationType',
                                        'CRM_Barangays.Barangay as Barangay')
                        ->where(function ($query) {
                                            $query->where('CRM_ServiceConnections.Trash', 'No')
                                                ->orWhereNull('CRM_ServiceConnections.Trash');
                                        })
                        ->orderByDesc('CRM_ServiceConnections.created_at')
                        ->take(10)
                        ->get();
                }
            } else {
                if ($query != '' ) {
                    $data = DB::table('CRM_ServiceConnections')
                        ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                        ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                        ->select('CRM_ServiceConnections.id as ConsumerId',
                                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                        'CRM_ServiceConnections.Status as Status',
                                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                        'CRM_ServiceConnections.AccountCount as AccountCount',  
                                        'CRM_ServiceConnections.Sitio as Sitio', 
                                        'CRM_Towns.Town as Town',
                                        'CRM_ServiceConnections.LoadCategory',
                                        'CRM_ServiceConnections.ConnectionApplicationType',
                                        'CRM_Barangays.Barangay as Barangay')
                        ->where(function ($query) {
                                            $query->where('CRM_ServiceConnections.Trash', 'No')
                                                ->orWhereNull('CRM_ServiceConnections.Trash');
                                        })
                        ->where('CRM_ServiceConnections.ServiceAccountName', 'LIKE', '%' . $query . '%')
                        ->orWhere('CRM_ServiceConnections.Id', 'LIKE', '%' . $query . '%')
                        ->whereRaw("CRM_ServiceConnections.Town IN " . MeterReaders::getMeterAreaCodeScopeSql(env('APP_AREA_CODE')))
                        ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                        ->get();
                } else {
                    $data = DB::table('CRM_ServiceConnections')
                        ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                        ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                        ->select('CRM_ServiceConnections.id as ConsumerId',
                                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                        'CRM_ServiceConnections.Status as Status',
                                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                        'CRM_ServiceConnections.AccountCount as AccountCount',  
                                        'CRM_ServiceConnections.Sitio as Sitio', 
                                        'CRM_Towns.Town as Town',
                                        'CRM_ServiceConnections.LoadCategory',
                                        'CRM_ServiceConnections.ConnectionApplicationType',
                                        'CRM_Barangays.Barangay as Barangay')
                        ->where(function ($query) {
                                            $query->where('CRM_ServiceConnections.Trash', 'No')
                                                ->orWhereNull('CRM_ServiceConnections.Trash');
                                        })
                        ->whereRaw("CRM_ServiceConnections.Town IN " . MeterReaders::getMeterAreaCodeScopeSql(env('APP_AREA_CODE')))
                        ->orderByDesc('CRM_ServiceConnections.created_at')
                        ->take(10)
                        ->get();
                }
            }

            $total_row = $data->count();
            if ($total_row > 0) {
                $output = '';
                foreach ($data as $row) {
                    if ($row->LoadCategory == 'above 5kVa') {
                        $output .= '
                            <div class="col-md-10 offset-md-1 col-lg-10 offset-lg-1" style="margin-top: 10px;">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <div>
                                                    <h4>' .$row->ServiceAccountName . '</h4>
                                                    <p class="text-muted" style="margin-bottom: 0;">Application Number: ' . $row->ConsumerId . '</p>
                                                    <p class="text-muted" style="margin-bottom: 0;">' . $row->Barangay . ', ' . $row->Town  . '</p>
                                                    <a href="' . route('serviceConnections.show', [$row->ConsumerId]) . '" class="text-primary" style="margin-top: 5px; padding: 8px;" title="View"><i class="fas fa-eye"></i></a>
                                                    <a href="' . route('serviceConnections.edit', [$row->ConsumerId]) . '" class="text-warning" style="margin-top: 5px; padding: 8px;" title="Edit"><i class="fas fa-pen"></i></a>
                                                </div>     
                                            </div> 

                                            <div class="col-md-6 col-lg-6 d-sm-none d-md-block d-none d-sm-block" style="border-left: 2px solid #f44336; padding-left: 15px;">
                                                <div>
                                                    <p class="text-muted" style="margin-bottom: 0;">Date of Application: <strong>' . date('F d, Y', strtotime($row->DateOfApplication)) . '</strong></p>
                                                    <p class="text-muted" style="margin-bottom: 0;">Type: <strong>' . $row->ConnectionApplicationType . '</strong></p>
                                                    <p class="text-muted" style="margin-bottom: 0;">Status: <strong>' . $row->Status . '</strong></p>
                                                </div>     
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                        ';
                    } else {
                        $output .= '
                            <div class="col-md-10 offset-md-1 col-lg-10 offset-lg-1" style="margin-top: 10px;">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <div>
                                                    <h4>' .$row->ServiceAccountName . '</h4>
                                                    <p class="text-muted" style="margin-bottom: 0;">Application Number: ' . $row->ConsumerId . '</p>
                                                    <p class="text-muted" style="margin-bottom: 0;">' . $row->Barangay . ', ' . $row->Town  . '</p>
                                                    <a href="' . route('serviceConnections.show', [$row->ConsumerId]) . '" class="text-primary" style="margin-top: 5px; padding: 8px;" title="View"><i class="fas fa-eye"></i></a>
                                                    <a href="' . route('serviceConnections.edit', [$row->ConsumerId]) . '" class="text-warning" style="margin-top: 5px; padding: 8px;" title="Edit"><i class="fas fa-pen"></i></a>
                                                </div>     
                                            </div> 

                                            <div class="col-md-6 col-lg-6 d-sm-none d-md-block d-none d-sm-block" style="border-left: 2px solid #007bff; padding-left: 15px;">
                                                <div>
                                                    <p class="text-muted" style="margin-bottom: 0;">Date of Application: <strong>' . date('F d, Y', strtotime($row->DateOfApplication)) . '</strong></p>
                                                    <p class="text-muted" style="margin-bottom: 0;">Type: <strong>' . $row->ConnectionApplicationType . '</strong></p>
                                                    <p class="text-muted" style="margin-bottom: 0;">Status: <strong>' . $row->Status . '</strong></p>
                                                </div>     
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                        ';
                    }                    
                }                
            } else {
                $output = '<p class="text-center">No data found.</p>';
            }

            $data = [
                'table_data' => $output
            ];

            echo json_encode($data);
        }
    }

    public function assessChecklists($id) {
        $serviceConnections = $this->serviceConnectionsRepository->find($id);

        $checklist = ServiceConnectionChecklistsRep::all();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc create', 'Super Admin'])) {
            return view('/service_connections/assess_checklists', ['serviceConnections' => $serviceConnections, 'checklist' => $checklist]);
        } else {
            return abort(403, "You're not authorized to create/update a service connection application.");
        }        
    }

    public function updateChecklists($id) {
        $serviceConnections = $this->serviceConnectionsRepository->find($id);

        $checklist = ServiceConnectionChecklistsRep::all();

        $checklistCompleted = ServiceConnectionChecklists::where('ServiceConnectionId', $id)->pluck('ChecklistId')->all();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['update membership', 'sc update', 'Super Admin'])) {
            return view('/service_connections/update_checklists', ['serviceConnections' => $serviceConnections, 'checklist' => $checklist, 'checklistCompleted' => $checklistCompleted]);
        } else {
            return abort(403, "You're not authorized to create/update a service connection application.");
        }         
    }

    public function moveToTrash($id) {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['delete membership', 'sc delete', 'Super Admin'])) {
            $serviceConnections = $this->serviceConnectionsRepository->find($id);

            $serviceConnections->Trash = 'Yes';
    
            $serviceConnections->save();
    
            return redirect(route('serviceConnections.all-applications'));
        } else {
            return abort(403, "You're not authorized to delete a service connection application.");
        }          
    }

    public function trash() {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['delete membership', 'sc delete', 'Super Admin'])) {
            return view('/service_connections/trash');
        } else {
            return abort(403, "You're not authorized to delete a service connection application.");
        }         
    }

    public function fetchserviceconnectiontrash(Request $request) {
        if ($request->ajax()) {
            $query = $request->get('query');
            
            if ($query != '' ) {
                $data = DB::table('CRM_ServiceConnections')
                    ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
                    ->select('CRM_ServiceConnections.id as ConsumerId',
                                    'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                    'CRM_ServiceConnections.Status as Status',
                                    'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                    'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                    'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                    'CRM_ServiceConnections.AccountCount as AccountCount',  
                                    'CRM_ServiceConnections.Sitio as Sitio', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where('CRM_ServiceConnections.Trash', 'Yes')
                    ->where('CRM_ServiceConnections.ServiceAccountName', 'LIKE', '%' . $query . '%')
                    ->orWhere('CRM_ServiceConnections.Id', 'LIKE', '%' . $query . '%')                    
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();
            } else {
                $data = DB::table('CRM_ServiceConnections')
                    ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
                    ->select('CRM_ServiceConnections.id as ConsumerId',
                                    'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                    'CRM_ServiceConnections.Status as Status',
                                    'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                    'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                    'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                    'CRM_ServiceConnections.AccountCount as AccountCount',  
                                    'CRM_ServiceConnections.Sitio as Sitio', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                                    'CRM_Barangays.Barangay as Barangay')
                    ->where('CRM_ServiceConnections.Trash', 'Yes')
                    ->orderByDesc('CRM_ServiceConnections.created_at')
                    ->take(10)
                    ->get();
            }

            $total_row = $data->count();
            if ($total_row > 0) {
                $output = '';
                foreach ($data as $row) {

                    $output .= '
                        <div class="col-md-10 offset-md-1 col-lg-10 offset-lg-1" style="margin-top: 10px;">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div>
                                                <h4>' .$row->ServiceAccountName . '</h4>
                                                <p class="text-muted" style="margin-bottom: 0;">Acount Number: ' . $row->ConsumerId . '</p>
                                                <p class="text-muted" style="margin-bottom: 0;">' . $row->Barangay . ', ' . $row->Town  . '</p>
                                                <a href="' . route('serviceConnections.restore', [$row->ConsumerId]) . '" class="text-primary" style="margin-top: 5px; padding: 8px;" title="Restore">
                                                    <i class="fas fa-recycle"></i>
                                                </a>
                                            </div>     
                                        </div> 

                                        <div class="col-md-6 col-lg-6 d-sm-none d-md-block d-none d-sm-block" style="border-left: 2px solid #007bff; padding-left: 15px;">
                                            <div>
                                                <p class="text-muted" style="margin-bottom: 0;">Date of Application: <strong>' . date('F d, Y', strtotime($row->DateOfApplication)) . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">AccountCount: <strong>' . $row->AccountCount . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">Status: <strong>' . $row->Status . '</strong></p>
                                                <p class="text-muted" style="margin-bottom: 0;">Account Type: <strong>' . $row->AccountType . '</strong></p>
                                            </div>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    ';
                }                
            } else {
                $output = '<p class="text-center">No data found.</p>';
            }

            $data = [
                'table_data' => $output
            ];

            echo json_encode($data);
        }
    }

    public function restore($id) {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['delete membership', 'sc delete', 'Super Admin'])) {
            $serviceConnections = $this->serviceConnectionsRepository->find($id);

            $serviceConnections->Trash = null;

            $serviceConnections->save();

            return redirect(route('serviceConnections.trash'));
        } else {
            return abort(403, "You're not authorized to delete a service connection application.");
        }         
    }

    public function energization() {
        if (Auth::user()->hasAnyPermission(['sc update energization', 'sc update', 'Super Admin'])) {
            if (env('APP_AREA_CODE') == '15') {
                $serviceConnections = DB::table('CRM_ServiceConnections')
                    ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')                        
                    ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                    ->select('CRM_ServiceConnections.id as id',
                                    'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                    'CRM_ServiceConnections.Status as Status',
                                    'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                    'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                    'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                    'CRM_ServiceConnections.AccountCount as AccountCount',  
                                    'CRM_ServiceConnections.Sitio as Sitio', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                                    'CRM_ServiceConnections.EnergizationOrderIssued as EnergizationOrderIssued', 
                                    'CRM_ServiceConnections.StationCrewAssigned as StationCrewAssigned',
                                    'CRM_ServiceConnectionCrew.StationName as StationName',
                                    'CRM_ServiceConnectionCrew.CrewLeader as CrewLeader',
                                    'CRM_ServiceConnectionCrew.Members as Members',
                                    'CRM_Barangays.Barangay as Barangay')
                    ->whereNotNull('CRM_ServiceConnections.ORNumber')
                    ->where(function ($query) {
                        $query->where('CRM_ServiceConnections.Status', 'Approved')
                            ->orWhere('CRM_ServiceConnections.Status', 'Not Energized');
                    })
                    ->whereIn('CRM_ServiceConnections.id', DB::table('CRM_ServiceConnectionMeterAndTransformer')->pluck('ServiceConnectionId'))
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();
            } else {
                $serviceConnections = DB::table('CRM_ServiceConnections')
                    ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                    ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')                        
                    ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                    ->select('CRM_ServiceConnections.id as id',
                                    'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                    'CRM_ServiceConnections.Status as Status',
                                    'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                    'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                    'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                    'CRM_ServiceConnections.AccountCount as AccountCount',  
                                    'CRM_ServiceConnections.Sitio as Sitio', 
                                    'CRM_Towns.Town as Town',
                                    'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                                    'CRM_ServiceConnections.EnergizationOrderIssued as EnergizationOrderIssued', 
                                    'CRM_ServiceConnections.StationCrewAssigned as StationCrewAssigned',
                                    'CRM_ServiceConnectionCrew.StationName as StationName',
                                    'CRM_ServiceConnectionCrew.CrewLeader as CrewLeader',
                                    'CRM_ServiceConnectionCrew.Members as Members',
                                    'CRM_Barangays.Barangay as Barangay')
                    ->whereNotNull('CRM_ServiceConnections.ORNumber')
                    ->whereRaw("CRM_ServiceConnections.Town IN " . MeterReaders::getMeterAreaCodeScopeSql(env('APP_AREA_CODE')))
                    ->where(function ($query) {
                        $query->where('CRM_ServiceConnections.Status', 'Approved')
                            ->orWhere('CRM_ServiceConnections.Status', 'Not Energized');
                    })
                    ->whereIn('CRM_ServiceConnections.id', DB::table('CRM_ServiceConnectionMeterAndTransformer')->pluck('ServiceConnectionId'))
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();
            }
            

            $crew = ServiceConnectionCrew::orderBy('StationName')->get();

            return view('/service_connections/energization', ['serviceConnections' => $serviceConnections, 'crew' => $crew]);
        } else {
            abort(403, 'Access denied');
        }      
    }

    public function changeStationCrew(Request $request) {
        if(request()->ajax()){
            $serviceConnection = ServiceConnections::find($request['id']);

            $serviceConnection->StationCrewAssigned = $request['StationCrewAssigned'];
            $serviceConnection->DateTimeOfEnergizationIssue = date('Y-m-d H:i:s');

            $serviceConnection->save();

            // CREATE Timeframes
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateID();
            $timeFrame->ServiceConnectionId = $request['id'];
            $timeFrame->UserId = Auth::id();
            $timeFrame->Status = 'Station Crew Re-assigned';
            $timeFrame->Notes = 'From ' . $request['FromStationCrewName'] . ' to ' . $request['ToStationCrewName'];
            $timeFrame->save();

            return response()->json([ 'success' => true ]);
        }        
    }

    public function updateEnergizationStatus(Request $request) {
        $serviceConnection = ServiceConnections::find($request['id']);

        $serviceConnection->Status = $request['Status'];
        $serviceConnection->DateTimeOfEnergization = $request['EnergizationDate'];
        $serviceConnection->DateTimeLinemenArrived = $request['ArrivalDate'];
        $serviceConnection->StationCrewAssigned = $request['Crew'];

        $serviceConnection->save();

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $request['id'];
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = $request['Status'];
        $timeFrame->Notes = 'Crew arrived at ' . date('F d, Y h:i:s A', strtotime($request['ArrivalDate'])) . '<br>' . 'Performed energization attempt at ' . date('F d, Y h:i:s A', strtotime($request['EnergizationDate'])) . '<br>' . $request['Reason'];
        $timeFrame->save();

        return response()->json('ok', 200);
    }

    public function printOrder($id) {
        $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.AccountCount as AccountCount', 
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.AccountOrganization as AccountOrganization', 
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.ConnectionApplicationType as ConnectionApplicationType',
                        'CRM_ServiceConnections.MemberConsumerId as MemberConsumerId',
                        'CRM_ServiceConnections.Status as Status',  
                        'CRM_ServiceConnections.BuildingType',
                        'CRM_ServiceConnections.Notes as Notes', 
                        'CRM_ServiceConnections.ORNumber as ORNumber', 
                        'CRM_ServiceConnections.ORDate as ORDate', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType')
        ->where('CRM_ServiceConnections.id', $id)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        $serviceConnectionInspections = ServiceConnectionInspections::where('ServiceConnectionId', $id)
                                ->first();

        $serviceConnectionMeter = ServiceConnectionMtrTrnsfrmr::where('ServiceConnectionId', $id)->first();

        $transactionIndex = TransactionIndex::where('ServiceConnectionId', $id)->first();

        if ($transactionIndex != null) {
            $transactionDetails = TransactionDetails::where('TransactionIndexId', $transactionIndex->id)->get();
        } else {
            $transactionDetails = null;
        }

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $id;
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Energization Order Issued';
        $timeFrame->save();

        // UPDATE ENERGIZATION COLUMN IN SEVICE CONNECTIONS;
        $scUpdate = ServiceConnections::find($id);
        $scUpdate->EnergizationOrderIssued = 'Yes';
        $scUpdate->DateTimeOfEnergizationIssue = date('Y-m-d H:i:s');
        $scUpdate->save();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['create membership', 'sc update energization', 'sc create', 'Super Admin'])) {
            return view('/service_connections/print_order', [
                'serviceConnection' => $serviceConnections, 
                'serviceConnectionInspections' => $serviceConnectionInspections, 
                'serviceConnectionMeter' => $serviceConnectionMeter,
                'transactionIndex' => $transactionIndex,
                'transactionDetails' => $transactionDetails,
            ]);
        } else {
            return abort(403, "You're not authorized to print an energization order.");
        }         
    }

    public function largeLoadInspections() {
        $serviceConnections = DB::table('CRM_ServiceConnections')
                    ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')                    
                    ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')  
                    ->where('CRM_ServiceConnections.Status', 'Forwarded To Planning')
                    ->where(function ($query) {
                        $query->where('Trash', 'No')
                            ->orWhereNull('Trash');
                    })
                    ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.Status as Status', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_Towns.Town as Town',
                        'CRM_ServiceConnections.LoadCategory as LoadCategory', 
                        'CRM_ServiceConnections.LongSpan as LongSpan', 
                        'CRM_Barangays.Barangay as Barangay')
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();

        $accountTypes = ServiceConnectionAccountTypes::orderBy('id')->get();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/large_load_inspections', ['serviceConnections' => $serviceConnections, 'accountTypes' => $accountTypes]);
        } else {
            return abort(403, "You're not authorized to view power load inspections.");
        }           
    }

    public function largeLoadInspectionUpdate(Request $request) {
        if ($request->ajax()) {
            // ADD INSPECTION DATA
            $largeLoadInspections = new ServiceConnectionLgLoadInsp;

            $largeLoadInspections->id = IDGenerator::generateID();
            $largeLoadInspections->ServiceConnectionId = $request['ServiceConnectionId'];
            $largeLoadInspections->Assessment = $request['Assessment'];
            $largeLoadInspections->DateOfInspection = $request['DateOfInspection'];
            $largeLoadInspections->Notes = $request['Notes'];
            $largeLoadInspections->Options = $request['Options'];

            $largeLoadInspections->save();

            // UPDATE SERVICE CONNECTION STATUS
            $serviceConnection = ServiceConnections::find($request['ServiceConnectionId']);

            $serviceConnection->Status = 'For BoM';
            $serviceConnection->AccountType = $request['AccountType'];

            $serviceConnection->save();

            // CREATE Timeframes
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateID();
            $timeFrame->ServiceConnectionId = $request['ServiceConnectionId'];
            $timeFrame->UserId = Auth::id();
            $timeFrame->Status = $request['Assessment'];
            $timeFrame->Notes = '(Power load inspection) See inspection log # <a href="' . route('serviceConnectionLgLoadInsps.show', [$largeLoadInspections->id]) . '">' . $largeLoadInspections->id . '</a> for further details';
            $timeFrame->save();

            return response()->json([ 'success' => true ]);
        }
    }

    public function bomIndex() {
        $serviceConnections = DB::table('CRM_ServiceConnections')
                    ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')                    
                    ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')  
                    ->leftJoin('CRM_LargeLoadInspections', 'CRM_ServiceConnections.id', '=', 'CRM_LargeLoadInspections.ServiceConnectionId')
                    ->where('CRM_ServiceConnections.Status', 'For BoM')
                    ->where(function ($query) {
                        $query->where('Trash', 'No')
                            ->orWhereNull('Trash');
                    })
                    ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.Status as Status', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_Towns.Town as Town',
                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_LargeLoadInspections.Options')
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/bom_index', ['serviceConnections' => $serviceConnections]);
        } else {
            return abort(403, "You're not authorized to view power load inspections.");
        }         
    }

    public function bomAssigning($scId) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.ContactNumber',
                    'CRM_ServiceConnections.BuildingType',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->where('CRM_ServiceConnections.id', $scId)
            ->first();

        $materials = MaterialAssets::orderBy('Description')->get();

        $structures = Structures::orderBy('Data')->get();

        $billOfMaterials = DB::table('CRM_BillOfMaterialsMatrix')
            ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
            ->leftJoin('CRM_Structures', 'CRM_BillOfMaterialsMatrix.StructureId', '=', 'CRM_Structures.id')    
            ->select('CRM_MaterialAssets.id',
                    'CRM_MaterialAssets.Description',
                    'CRM_BillOfMaterialsMatrix.Amount',
                    DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),
                    DB::raw('(CAST(CRM_BillOfMaterialsMatrix.Amount As Money) * SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer))) AS ExtendedCost'))
            ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
            ->whereNull('CRM_BillOfMaterialsMatrix.StructureType')
            ->groupBy('CRM_MaterialAssets.Description', 'CRM_BillOfMaterialsMatrix.Amount', 'CRM_MaterialAssets.id')
            ->orderBy('CRM_MaterialAssets.Description')
            ->get(); 

        $structuresAssigned = StructureAssignments::where('ServiceConnectionId', $scId)
            ->whereNotIn('ConAssGrouping', ['9', '1', '3'])            
            ->orderBy('StructureId')->get();
            
        
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/bom_assigning', ['serviceConnection' => $serviceConnection, 
                'structuresAssigned' => $structuresAssigned, 
                'billOfMaterials' => $billOfMaterials,
                'materials' => $materials,
                'structures' => $structures,
            ]);
        } else {
            return abort(403, "You're not authorized to update power load inspections.");
        }         
    }

    public function forwardToTransformerAssigning($scId) {
        $serviceConnection = ServiceConnections::find($scId);

        $serviceConnection->Status = 'For Transformer and Pole Assigning';

        $serviceConnection->save();

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $scId;
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Bill of Materials Assigned';
        $timeFrame->save();

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $scId;
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'For Transformer and Pole Assigning';
        $timeFrame->save();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return redirect(route('serviceConnections.transformer-assigning', [$scId]));
        } else {
            return abort(403, "You're not authorized to update power load inspections.");
        } 
    }

    public function transformerIndex() { 
        $serviceConnections = DB::table('CRM_ServiceConnections')
                    ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                    ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')                    
                    ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')  
                    ->where('CRM_ServiceConnections.Status', 'For Transformer and Pole Assigning')
                    ->where(function ($query) {
                        $query->where('Trash', 'No')
                            ->orWhereNull('Trash');
                    })
                    ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.Status as Status', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_Towns.Town as Town',
                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                        'CRM_Barangays.Barangay as Barangay')
                    ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                    ->get();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/transformer_index', ['serviceConnections' => $serviceConnections]);
        } else {
            return abort(403, "You're not authorized to update view load inspections.");
        }         
    }

    public function transformerAssigning($scId) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.ContactNumber',
                    'CRM_ServiceConnections.BuildingType',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->where('CRM_ServiceConnections.id', $scId)
            ->first();

        $transformerIndex = DB::table('CRM_TransformerIndex')
            ->leftJoin('CRM_MaterialAssets', 'CRM_TransformerIndex.NEACode', '=', 'CRM_MaterialAssets.id')
            ->select('CRM_MaterialAssets.*',
                    'CRM_TransformerIndex.id as IndexId')
            ->get();

        $transformerMatrix = DB::table('CRM_TransformersAssignedMatrix')
            ->leftJoin('CRM_MaterialAssets', 'CRM_TransformersAssignedMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
            ->select('CRM_TransformersAssignedMatrix.id',
                    'CRM_MaterialAssets.Description',
                    'CRM_MaterialAssets.Amount',
                    'CRM_TransformersAssignedMatrix.Quantity')
            ->where('CRM_TransformersAssignedMatrix.ServiceConnectionId', $scId)
            ->get();

        $structureBrackets = Structures::where('Type', 'A_DT')->get();

        $bracketsAssigned = DB::table('CRM_BillOfMaterialsMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
                ->leftJoin('CRM_Structures', 'CRM_BillOfMaterialsMatrix.StructureId', '=', 'CRM_Structures.id')    
                ->select('CRM_MaterialAssets.id',
                        'CRM_MaterialAssets.Description',
                        'CRM_MaterialAssets.Amount',
                        DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),
                        DB::raw('(CAST(CRM_MaterialAssets.Amount As Money) * SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer))) AS ExtendedCost'))
                ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
                ->where('CRM_BillOfMaterialsMatrix.StructureType', 'A_DT')
                ->groupBy('CRM_MaterialAssets.Description', 'CRM_MaterialAssets.Amount', 'CRM_MaterialAssets.id')
                ->orderBy('CRM_MaterialAssets.Description')
                ->get(); 

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/transformer_assigning', ['serviceConnection' => $serviceConnection, 'transformerIndex' => $transformerIndex, 'transformerMatrix' => $transformerMatrix, 'structureBrackets' => $structureBrackets, 'bracketsAssigned' => $bracketsAssigned]);
        } else {
            return abort(403, "You're not authorized to update update load inspections.");
        }         
    }

    public function poleAssigning($scId) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.ContactNumber',
                    'CRM_ServiceConnections.BuildingType',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->where('CRM_ServiceConnections.id', $scId)
            ->first();

        $poleIndex = DB::table('CRM_PoleIndex')
            ->leftJoin('CRM_MaterialAssets', 'CRM_PoleIndex.NEACode', '=', 'CRM_MaterialAssets.id')
            ->select('CRM_MaterialAssets.*',
                    'CRM_PoleIndex.id as IndexId',
                    'CRM_PoleIndex.Type as Type')
            ->get();

        $poleAssigned = DB::table('CRM_BillOfMaterialsMatrix')
            ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')  
            ->select('CRM_BillOfMaterialsMatrix.id',
                    'CRM_MaterialAssets.Description',
                    'CRM_MaterialAssets.Amount',
                    'CRM_BillOfMaterialsMatrix.Quantity')
            ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
            ->where('CRM_BillOfMaterialsMatrix.StructureType', 'POLE')
            ->orderBy('CRM_MaterialAssets.Description')
            ->get(); 


        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/pole_assigning', ['serviceConnection' => $serviceConnection, 'poleIndex' => $poleIndex, 'poleAssigned' => $poleAssigned]);
        } else {
            return abort(403, "You're not authorized to update update load inspections.");
        }         
    }

    public function quotationSummary($scId) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.AccountCount as AccountCount', 
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.AccountOrganization as AccountOrganization', 
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.ConnectionApplicationType as ConnectionApplicationType',
                        'CRM_ServiceConnections.MemberConsumerId as MemberConsumerId',
                        'CRM_ServiceConnections.Status as Status',  
                        'CRM_ServiceConnections.Notes as Notes', 
                        'CRM_ServiceConnections.ORNumber as ORNumber', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.LoadCategory as LoadCategory', 
                        'CRM_ServiceConnections.DateTimeOfEnergization as DateTimeOfEnergization', 
                        'CRM_ServiceConnections.DateTimeLinemenArrived as DateTimeLinemenArrived', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                        'CRM_ServiceConnectionCrew.StationName as StationName',
                        'CRM_ServiceConnectionCrew.CrewLeader as CrewLeader',
                        'CRM_ServiceConnections.TemporaryDurationInMonths as TemporaryDurationInMonths',
                        'CRM_ServiceConnectionCrew.Members as Members')
        ->where('CRM_ServiceConnections.id', $scId)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        $materials = DB::table('CRM_BillOfMaterialsMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')   
                ->select('CRM_MaterialAssets.id',
                        'CRM_MaterialAssets.Description',
                        'CRM_BillOfMaterialsMatrix.Amount',
                        DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),
                        DB::raw('(CAST(CRM_BillOfMaterialsMatrix.Amount As Money) * SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer))) AS Cost'))
                ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
                // ->where('CRM_BillOfMaterialsMatrix.StructureType', 'A_DT')
                ->groupBy('CRM_MaterialAssets.Description', 'CRM_BillOfMaterialsMatrix.Amount', 'CRM_MaterialAssets.id')
                ->orderBy('CRM_MaterialAssets.Description')
                ->get();
                
        $poles = DB::table('CRM_BillOfMaterialsMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')   
                ->select('CRM_MaterialAssets.id',
                        'CRM_MaterialAssets.Description',
                        DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),)
                ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
                ->where('CRM_BillOfMaterialsMatrix.StructureType', 'POLE')
                ->groupBy('CRM_MaterialAssets.Description', 'CRM_MaterialAssets.id')
                ->orderBy('CRM_MaterialAssets.Description')
                ->get();

        $transformers = DB::table('CRM_TransformersAssignedMatrix')
                ->leftJoin('CRM_MaterialAssets', 'CRM_TransformersAssignedMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')
                ->select('CRM_MaterialAssets.id',
                        'CRM_TransformersAssignedMatrix.id as TransformerId',
                        'CRM_MaterialAssets.Description',
                        'CRM_MaterialAssets.Amount',
                        'CRM_TransformersAssignedMatrix.Quantity',
                        'CRM_TransformersAssignedMatrix.Type')
                ->where('CRM_TransformersAssignedMatrix.ServiceConnectionId', $scId)
                ->get();

        $structures = DB::table('CRM_StructureAssignments')
            ->leftJoin('CRM_Structures', 'CRM_StructureAssignments.StructureId', '=', 'CRM_Structures.Data')
            ->select('CRM_Structures.id as id',
                    'CRM_StructureAssignments.StructureId',
                    DB::raw('SUM(CAST(CRM_StructureAssignments.Quantity AS Integer)) AS Quantity'))
            ->where('ServiceConnectionId', $scId)
            ->groupBy('CRM_Structures.id', 'CRM_StructureAssignments.StructureId')
            ->get();

        $conAss = DB::table('CRM_StructureAssignments')
            ->where('ServiceConnectionId', $scId)
            ->select('ConAssGrouping', 'StructureId', 'Quantity', 'Type')
            ->groupBy('StructureId', 'ConAssGrouping', 'Quantity', 'Type')
            ->orderBy('ConAssGrouping')
            ->get();

        $billOfMaterialsSummary = BillsOfMaterialsSummary::where('ServiceConnectionId', $scId)->first();
        if ($billOfMaterialsSummary == null) {
            $billOfMaterialsSummary = new BillsOfMaterialsSummary;
            $billOfMaterialsSummary->id = IDGenerator::generateID();
            $billOfMaterialsSummary->ServiceConnectionId = $scId;
            $billOfMaterialsSummary->TransformerLaborCostPercentage = '0.035';
            $billOfMaterialsSummary->MaterialLaborCostPercentage = '0.35';
            $billOfMaterialsSummary->HandlingCostPercentage = '0.30';
            $billOfMaterialsSummary->MonthDuration = $serviceConnection->TemporaryDurationInMonths;

            // CALCULATE SUB-TOTAL
            $subTtl = 0.0;
            foreach($materials as $items) { // materials total
                $subTtl += floatval($items->Cost);
            }
            foreach($transformers as $items) { 
                if ($items->Type != 'Transformer') {
                    $subTtl += floatval($items->Quantity) * floatval($items->Amount);
                }
            }

            // transformer sub total
            $transSoloTtl = 0.0;
            foreach($transformers as $items) { 
                if ($items->Type == 'Transformer') {
                    $transSoloTtl += floatval($items->Quantity) * floatval($items->Amount);
                }                
            }

            // sub total
            $billOfMaterialsSummary->SubTotal = $subTtl + $transSoloTtl;

            // transformer total
            $billOfMaterialsSummary->TransformerTotal = $transSoloTtl;

            // transformer labor cost
            $billOfMaterialsSummary->TransformerLaborCost = $transSoloTtl * floatval($billOfMaterialsSummary->TransformerLaborCostPercentage);

            // materials labor cost            
            $billOfMaterialsSummary->MaterialLaborCost = $subTtl * floatval($billOfMaterialsSummary->MaterialLaborCostPercentage);

            // total labor cost except handling (just transformer and material labor costs)
            $billOfMaterialsSummary->LaborCost = floatval($billOfMaterialsSummary->TransformerLaborCost) + floatval($billOfMaterialsSummary->MaterialLaborCost);

            // handling labor cost            
            $billOfMaterialsSummary->HandlingCost = floatval($billOfMaterialsSummary->LaborCost) * floatval($billOfMaterialsSummary->HandlingCostPercentage);

            // overall total
            $billOfMaterialsSummary->Total = floatval($billOfMaterialsSummary->SubTotal) +
                                            floatval($billOfMaterialsSummary->HandlingCost) +
                                            floatval($billOfMaterialsSummary->LaborCost);
            
            // total vat
            $vatAmount = floatval($billOfMaterialsSummary->Total) * BillsOfMaterialsSummary::getVat();

            $billOfMaterialsSummary->Total = $billOfMaterialsSummary->Total + $vatAmount;

            $billOfMaterialsSummary->TotalVAT = $vatAmount;
            
            $billOfMaterialsSummary->save();
        } else {
            $billOfMaterialsSummary->MonthDuration = $serviceConnection->TemporaryDurationInMonths;

            // CALCULATE SUB-TOTAL
            $subTtl = 0.0;
            foreach($materials as $items) { // materials total
                $subTtl += floatval($items->Cost);
            }
            foreach($transformers as $items) { 
                if ($items->Type != 'Transformer') {
                    $subTtl += floatval($items->Quantity) * floatval($items->Amount);
                }
            }

            // transformer sub total
            $transSoloTtl = 0.0;
            foreach($transformers as $items) { 
                if ($items->Type == 'Transformer') {
                    $transSoloTtl += floatval($items->Quantity) * floatval($items->Amount);
                }                
            }

            // sub total
            $billOfMaterialsSummary->SubTotal = $subTtl + $transSoloTtl;

            // materials labor cost            
            $billOfMaterialsSummary->MaterialLaborCost = $subTtl * floatval($billOfMaterialsSummary->MaterialLaborCostPercentage);

            if ($billOfMaterialsSummary->ExcludeTransformerLaborCost == null) {
                $billOfMaterialsSummary->TransformerLaborCost = $transSoloTtl * floatval($billOfMaterialsSummary->TransformerLaborCostPercentage);
                $billOfMaterialsSummary->LaborCost = floatval($billOfMaterialsSummary->TransformerLaborCost) + floatval($billOfMaterialsSummary->MaterialLaborCost);
            } else {
                if ($billOfMaterialsSummary->ExcludeTransformerLaborCost == 'Yes') {
                    $billOfMaterialsSummary->TransformerLaborCost = null;
                    $billOfMaterialsSummary->LaborCost = $billOfMaterialsSummary->MaterialLaborCost;
                } else {
                    $billOfMaterialsSummary->TransformerLaborCost = $transSoloTtl * floatval($billOfMaterialsSummary->TransformerLaborCostPercentage);
                    $billOfMaterialsSummary->LaborCost = floatval($billOfMaterialsSummary->TransformerLaborCost) + floatval($billOfMaterialsSummary->MaterialLaborCost);
                }
            }

            // handling labor cost            
            $billOfMaterialsSummary->HandlingCost = floatval($billOfMaterialsSummary->LaborCost) * floatval($billOfMaterialsSummary->HandlingCostPercentage);

            // overall total
            $billOfMaterialsSummary->Total = floatval($billOfMaterialsSummary->SubTotal) +
                                            floatval($billOfMaterialsSummary->HandlingCost) +
                                            floatval($billOfMaterialsSummary->LaborCost);

            // total vat
            $vatAmount = floatval($billOfMaterialsSummary->Total) * BillsOfMaterialsSummary::getVat();

            $billOfMaterialsSummary->Total = $billOfMaterialsSummary->Total + $vatAmount;

            $billOfMaterialsSummary->TotalVAT = $vatAmount;

            $billOfMaterialsSummary->save();
        }

        return view('/service_connections/quotation_summary', [
                'serviceConnection' => $serviceConnection, 
                'materials' => $materials, 
                'transformers' => $transformers, 
                'billOfMaterialsSummary' => $billOfMaterialsSummary,
                'structures' => $structures,
                'conAss' => $conAss,
                'poles' => $poles,
            ]
        );
    }

    public function spanningAssigning($scId) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.ContactNumber',
                    'CRM_ServiceConnections.BuildingType',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->where('CRM_ServiceConnections.id', $scId)
            ->first();

        $billOfMaterials = DB::table('CRM_BillOfMaterialsMatrix')
            ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')   
            ->select('CRM_MaterialAssets.id',
                    'CRM_MaterialAssets.Description',
                    'CRM_MaterialAssets.Amount',
                    DB::raw('SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer)) AS ProjectRequirements'),
                    DB::raw('(CAST(CRM_MaterialAssets.Amount As Money) * SUM(CAST(CRM_BillOfMaterialsMatrix.Quantity AS Integer))) AS ExtendedCost'))
            ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
            ->where('CRM_BillOfMaterialsMatrix.StructureType', 'SPANNING')
            ->groupBy('CRM_MaterialAssets.Description', 'CRM_MaterialAssets.Amount', 'CRM_MaterialAssets.id')
            ->orderBy('CRM_MaterialAssets.Description')
            ->get(); 
        
        $spanningData = SpanningData::where('ServiceConnectionId', $scId)->first();

        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/spanning_assigning', [
                'serviceConnection' => $serviceConnection,
                'billOfMaterials' => $billOfMaterials,
                'spanningData' => $spanningData
            ]);
        } else {
            return abort(403, "You're not authorized to update update load inspections.");
        }         
    }

    public function meteringEquipmentAssigning($scId) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->select('CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.ContactNumber',
                    'CRM_ServiceConnections.BuildingType',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->where('CRM_ServiceConnections.id', $scId)
            ->first();

        $specialEquipmentIndex = DB::table('CRM_SpecialEquipmentMaterials')
            ->leftJoin('CRM_MaterialAssets', 'CRM_SpecialEquipmentMaterials.NEACode', '=', 'CRM_MaterialAssets.id')
            ->select('CRM_MaterialAssets.*',
                    'CRM_SpecialEquipmentMaterials.id as IndexId')
            ->get();

        $equipmentAssigned = DB::table('CRM_BillOfMaterialsMatrix')
            ->leftJoin('CRM_MaterialAssets', 'CRM_BillOfMaterialsMatrix.MaterialsId', '=', 'CRM_MaterialAssets.id')  
            ->select('CRM_BillOfMaterialsMatrix.id',
                    'CRM_MaterialAssets.Description',
                    'CRM_MaterialAssets.Amount',
                    'CRM_BillOfMaterialsMatrix.Quantity')
            ->where('CRM_BillOfMaterialsMatrix.ServiceConnectionId', $scId)
            ->where('CRM_BillOfMaterialsMatrix.StructureType', 'SPEC_EQUIP')
            ->orderBy('CRM_MaterialAssets.Description')
            ->get(); 


        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/metering_equipment_assigning', [
                'serviceConnection' => $serviceConnection,
                'specialEquipmentIndex' => $specialEquipmentIndex,
                'equipmentAssigned' => $equipmentAssigned,
            ]);
        } else {
            return abort(403, "You're not authorized to update update load inspections.");
        }          
    }

    public function forwardToVerification($scId) {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            $serviceConnection = ServiceConnections::find($scId);
            $serviceConnection->Status = 'For Inspection';
            $serviceConnection->save();

            // CREATE Timeframes
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateID();
            $timeFrame->ServiceConnectionId = $scId;
            $timeFrame->UserId = Auth::id();
            $timeFrame->Status = 'Forwarded for Verfication';
            $timeFrame->Notes = 'Forwarded to ISD for Verfication';
            $timeFrame->save();

            return redirect(route('serviceConnections.show', [$scId]));
        } else {
            return abort(403, "You're not authorized to forward an application.");
        }        
    }

    public function largeLoadPredefinedMaterials($scId, $options) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')            
            ->leftJoin('CRM_LargeLoadInspections', 'CRM_ServiceConnections.id', '=', 'CRM_LargeLoadInspections.ServiceConnectionId')
            ->select('CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.ContactNumber',
                    'CRM_ServiceConnections.BuildingType',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'CRM_ServiceConnections.AccountApplicationType',
                    'CRM_ServiceConnections.TemporaryDurationInMonths',
                    'CRM_LargeLoadInspections.Options')
            ->where('CRM_ServiceConnections.id', $scId)
            ->first();

        $materials = MaterialAssets::orderBy('Description')->get();

        if ($serviceConnection->AccountApplicationType == 'Temporary' && $serviceConnection->Options == 'Transformer Only') {
            $preDefMaterials = DB::table('CRM_PreDefinedMaterials')
            ->leftJoin('CRM_MaterialAssets', 'CRM_PreDefinedMaterials.NEACode', '=', 'CRM_MaterialAssets.id')
            ->where('CRM_PreDefinedMaterials.Options', $options)
            ->where('CRM_PreDefinedMaterials.ApplicationType', $serviceConnection->AccountApplicationType)
            ->select('CRM_PreDefinedMaterials.id',
                    'CRM_PreDefinedMaterials.NEACode',
                    'CRM_MaterialAssets.Description',
                    'CRM_MaterialAssets.Amount',
                    'CRM_PreDefinedMaterials.Quantity',
                    'CRM_PreDefinedMaterials.LaborPercentage',
                    DB::raw('(CAST(CRM_MaterialAssets.Amount AS DECIMAL(9,2)) * CAST(CRM_PreDefinedMaterials.Quantity AS DECIMAL(9,2)) * 0.15 * ' . floatval($serviceConnection->TemporaryDurationInMonths) . ') AS Cost'),
                    DB::raw('(CAST(CRM_MaterialAssets.Amount AS DECIMAL(9,2)) * CAST(CRM_PreDefinedMaterials.Quantity AS DECIMAL(9,2)) * CAST(CRM_PreDefinedMaterials.LaborPercentage AS DECIMAL(9,4))) AS LaborCost'))
            ->get();
        } else {
            $preDefMaterials = DB::table('CRM_PreDefinedMaterials')
            ->leftJoin('CRM_MaterialAssets', 'CRM_PreDefinedMaterials.NEACode', '=', 'CRM_MaterialAssets.id')
            ->where('CRM_PreDefinedMaterials.Options', $options)
            ->where('CRM_PreDefinedMaterials.ApplicationType', $serviceConnection->AccountApplicationType)
            ->select('CRM_PreDefinedMaterials.id',
                    'CRM_PreDefinedMaterials.NEACode',
                    'CRM_MaterialAssets.Description',
                    'CRM_MaterialAssets.Amount',
                    'CRM_PreDefinedMaterials.Quantity',
                    'CRM_PreDefinedMaterials.LaborPercentage',
                    DB::raw('(CAST(CRM_MaterialAssets.Amount AS DECIMAL(9,2)) * CAST(CRM_PreDefinedMaterials.Quantity AS DECIMAL(9,2))) AS Cost'),
                    DB::raw('(CAST(CRM_MaterialAssets.Amount AS DECIMAL(9,2)) * CAST(CRM_PreDefinedMaterials.Quantity AS DECIMAL(9,2)) * CAST(CRM_PreDefinedMaterials.LaborPercentage AS DECIMAL(9,4))) AS LaborCost'))
            ->get();
        }

        if ($preDefMaterials != null) {
            $preDef = PreDefinedMaterialsMatrix::where('ServiceConnectionId', $scId)
                            ->get();
            
            if (count($preDef) < 1) {
                // SAVE PRE DEFINED MATERIALS
                foreach($preDefMaterials as $item) {
                    $preDef = PreDefinedMaterialsMatrix::where('ServiceConnectionId', $scId)
                                ->where('NEACode', $item->NEACode)
                                ->first();
                    if ($preDef == null) {
                        $preDef = new PreDefinedMaterialsMatrix;
                        $preDef->id = IDGenerator::generateID();
                        $preDef->ServiceConnectionId = $scId;
                        $preDef->NEACode = $item->NEACode;
                        $preDef->Description = $item->Description;
                        $preDef->Quantity = $item->Quantity;
                        $preDef->Options = $options;
                        $preDef->ApplicationType = $serviceConnection->AccountApplicationType;
                        $preDef->Cost = $item->Cost;
                        $preDef->LaborCost = $item->LaborCost;
                        $preDef->Amount = $item->Amount;
                        $preDef->LaborPercentage = $item->LaborPercentage;
                        $preDef->save();
                    } else {
                        $preDef->ServiceConnectionId = $scId;
                        $preDef->NEACode = $item->NEACode;
                        $preDef->Description = $item->Description;
                        $preDef->Quantity = $item->Quantity;
                        $preDef->Options = $options;
                        $preDef->ApplicationType = $serviceConnection->AccountApplicationType;
                        $preDef->Cost = $item->Cost;
                        $preDef->LaborCost = $item->LaborCost;
                        $preDef->Amount = $item->Amount;
                        $preDef->LaborPercentage = $item->LaborPercentage;
                        $preDef->save();
                    }                
                }
            }            
        }

        $preDef = PreDefinedMaterialsMatrix::where('ServiceConnectionId', $scId)
                            ->get();


        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc powerload update', 'sc powerload view', 'Super Admin'])) {
            return view('/service_connections/largeload_predefined_materials', 
            [
                'serviceConnection' => $serviceConnection,
                'materials' => $materials,
                'preDefMaterials' => $preDefMaterials,
                'preDef' => $preDef,
            ]);
        } else {
            return abort(403, "You're not authorized to update update load inspections.");
        }         
    }

    public function fleetMonitor() {
        /**
         * ASSESS PERMISSIONS
         */
        if(Auth::user()->hasAnyPermission(['sc view', 'sc powerload view', 'view membership', 'view metering data', 'Super Admin'])) {
            return view('/service_connections/fleet_monitor');
        } else {
            return abort(403, "You're not authorized to update update load inspections.");
        }         
    }

    public function dailyMonitor() {
        return view('/service_connections/daily_monitor');
    }

    public function fetchDailyMonitorApplicationsData(Request $request) {
        // if ($request->ajax()) {
            $serviceConnections = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
                ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->select('CRM_ServiceConnections.id as id',
                            'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                            'CRM_ServiceConnections.DateOfApplication as DateOfApplication',
                            'CRM_ServiceConnections.Sitio as Sitio', 
                            'CRM_Towns.Town as Town',
                            'CRM_Barangays.Barangay as Barangay')
                ->where('CRM_ServiceConnections.DateOfApplication', $request['DateOfApplication'])
                ->where(function ($query) {
                    $query->where('CRM_ServiceConnections.Trash', 'No')
                        ->orWhereNull('CRM_ServiceConnections.Trash');
                })
                ->get();
                
                if (count($serviceConnections) > 0) {
                    $output = '';
                    $count = 1;
                    foreach ($serviceConnections as $row) {    
                        $output .= '
                            <tr>
                                <td>' . $count . '</td>
                                <td><a href="' . route('serviceConnections.show', [$row->id]) . '">' . $row->id . '</a></td>
                                <td>' . $row->ServiceAccountName . '</td>
                                <td>' . ServiceConnections::getAddress($row) . '</td>
                            </tr>   
                        ';
                        $count++;
                    }                
                } else {
                    $output = '';
                }

            return response()->json($output, 200);
        // }
    }

    public function fetchDailyMonitorEnergizedData(Request $request) {
        // if ($request->ajax()) {
            $serviceConnections = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
                ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->select('CRM_ServiceConnections.id as id',
                            'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                            'CRM_ServiceConnections.Sitio as Sitio', 
                            'CRM_Towns.Town as Town',
                            'CRM_Barangays.Barangay as Barangay')
                ->whereBetween('CRM_ServiceConnections.DateTimeOfEnergization', [$request['DateOfEnergization'] . ' 00:01:00', $request['DateOfEnergization'] . ' 23:59:00'])
                ->where(function ($query) {
                    $query->where('CRM_ServiceConnections.Trash', 'No')
                        ->orWhereNull('CRM_ServiceConnections.Trash');
                })
                ->get();
                
                if (count($serviceConnections) > 0) {
                    $output = '';
                    $count = 1;
                    foreach ($serviceConnections as $row) {    
                        $output .= '
                            <tr>
                                <td>' . $count . '</td>
                                <td><a href="' . route('serviceConnections.show', [$row->id]) . '">' . $row->id . '</a></td>
                                <td>' . $row->ServiceAccountName . '</td>
                                <td>' . ServiceConnections::getAddress($row) . '</td>
                            </tr>   
                        ';
                        $count++;
                    }                
                } else {
                    $output = '';
                }

            return response()->json($output, 200);
        // }
    }

    public function applicationsReport() {
        $towns = Towns::all();

        return view('/service_connections/applications_report', [
            'towns' => $towns
        ]);
    }

    public function fetchApplicationsReport(Request $request) {
        if ($request['Office'] == 'All') {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication',
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.Office', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay')
            ->whereBetween('CRM_ServiceConnections.DateOfApplication', [$request['From'], $request['To']])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateOfApplication')
            ->get();
        } else {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication',
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.Office', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay')
            ->whereBetween('CRM_ServiceConnections.DateOfApplication', [$request['From'], $request['To']])
            ->where('CRM_ServiceConnections.Town', $request['Office'])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateOfApplication')
            ->get();
        }
        
            
        if (count($serviceConnections) > 0) {
            $output = '';
            $count = 1;
            foreach ($serviceConnections as $row) {    
                $output .= '
                    <tr>
                        <td>' . $count . '</td>
                        <td><a href="' . route('serviceConnections.show', [$row->id]) . '">' . $row->id . '</a></td>
                        <td>' . $row->ServiceAccountName . '</td>
                        <td>' . ServiceConnections::getAddress($row) . '</td>
                        <td>' . $row->Office . '</td>
                        <td>' . date('F d, Y', strtotime($row->DateOfApplication)) . '</td>
                    </tr>   
                ';
                $count++;
            }                
        } else {
            $output = '';
        }

        return response()->json($output, 200);
    }

    public function downloadApplicationsReport(Request $request) {
        if ($request['Office'] == 'All') {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select(DB::raw("CRM_ServiceConnections.id as id"),
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication',
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_Towns.Town as Town',                        
                        'CRM_ServiceConnections.ConnectionApplicationType',
                        'CRM_ServiceConnections.Status')
            ->whereBetween('CRM_ServiceConnections.DateOfApplication', [$request['From'], $request['To']])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateOfApplication')
            ->get();
        } else {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select("CRM_ServiceConnections.id as id",
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication',
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_Towns.Town as Town',
                        'CRM_ServiceConnections.ConnectionApplicationType',
                        'CRM_ServiceConnections.Status')
            ->whereBetween('CRM_ServiceConnections.DateOfApplication', [$request['From'], $request['To']])
            ->where('CRM_ServiceConnections.Town', $request['Office'])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateOfApplication')
            ->get();
        }

        $export = new ServiceConnectionApplicationsReportExport($serviceConnections->toArray());

        return Excel::download($export, 'Applications-Report.xlsx');
    }

    public function energizationReport() {
        $towns = Towns::all();

        return view('/service_connections/energization_report', [
            'towns' => $towns
        ]);
    }

    public function fetchEnergizationReport(Request $request) {
        if ($request['Office'] == 'All') {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateTimeOfEnergization',
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.Notes', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber')
            ->whereBetween('CRM_ServiceConnections.DateTimeOfEnergization', [$request['From'], $request['To']])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateTimeOfEnergization')
            ->get();
        } else {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateTimeOfEnergization',
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.Office',
                        'CRM_ServiceConnections.Notes', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber')
            ->whereBetween('CRM_ServiceConnections.DateTimeOfEnergization', [$request['From'], $request['To']])
            ->where('CRM_ServiceConnections.Town', $request['Office'])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateTimeOfEnergization')
            ->get();
        }
        
            
        if (count($serviceConnections) > 0) {
            $output = '';
            $count = 1;
            foreach ($serviceConnections as $row) {    
                $output .= '
                    <tr>
                        <td>' . $count . '</td>
                        <td><a href="' . route('serviceConnections.show', [$row->id]) . '">' . $row->id . '</a></td>
                        <td>' . $row->ServiceAccountName . '</td>
                        <td>' . ServiceConnections::getAddress($row) . '</td>
                        <td>' . $row->Office . '</td>
                        <td>' . date('F d, Y @ h:i:s A', strtotime($row->DateTimeOfEnergization)) . '</td>
                        <td>' . $row->MeterSerialNumber . '</td>
                        <td>' . $row->Notes . '</td>
                    </tr>   
                ';
                $count++;
            }                
        } else {
            $output = '';
        }

        return response()->json($output, 200);
    }

    public function downloadEnergizationReport(Request $request) {
        if ($request['Office'] == 'All') {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select(DB::raw("CONCAT(CRM_ServiceConnections.id, ' ') as id"),
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateTimeOfEnergization',
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_Towns.Town as Town',
                        'CRM_ServiceConnections.ConnectionApplicationType',
                        'CRM_ServiceConnections.Status')
            ->whereBetween('CRM_ServiceConnections.DateTimeOfEnergization', [$request['From'], $request['To']])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateTimeOfEnergization')
            ->get();
        } else {
            $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select(DB::raw("CONCAT(CRM_ServiceConnections.id, ' ') as id"),
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateTimeOfEnergization',
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.Sitio as Sitio',
                        'CRM_Barangays.Barangay as Barangay', 
                        'CRM_Towns.Town as Town',
                        'CRM_ServiceConnections.ConnectionApplicationType',
                        'CRM_ServiceConnections.Status')
            ->whereBetween('CRM_ServiceConnections.DateTimeOfEnergization', [$request['From'], $request['To']])
            ->where('CRM_ServiceConnections.Town', $request['Office'])
            ->where(function ($query) {
                $query->where('CRM_ServiceConnections.Trash', 'No')
                    ->orWhereNull('CRM_ServiceConnections.Trash');
            })
            ->orderByDesc('DateTimeOfEnergization')
            ->get();
        }

        $export = new ServiceConnectionEnergizationReportExport($serviceConnections->toArray());

        return Excel::download($export, 'Energization-Report.xlsx');
    }

    public function fetchApplicationCountViaStatus(Request $request) {
        $startDate = date('Y-m-d', strtotime('first day of this month'));
        $serviceConnections = DB::table('CRM_ServiceConnections')
            ->select(DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateOfApplication BETWEEN '" . $startDate . "' AND '" . date('Y-m-d', strtotime($startDate . ' +1 month')) . "') AS 'ApplicationOne'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateOfApplication BETWEEN '" . date('Y-m-d', strtotime($startDate . '-1 months')) . "' AND '" . $startDate . "') AS 'ApplicationTwo'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateOfApplication BETWEEN '" . date('Y-m-d', strtotime($startDate . '-2 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-1 months')) . "') AS 'ApplicationThree'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateOfApplication BETWEEN '" . date('Y-m-d', strtotime($startDate . '-3 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-2 months')) . "') AS 'ApplicationFour'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateOfApplication BETWEEN '" . date('Y-m-d', strtotime($startDate . '-4 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-3 months')) . "') AS 'ApplicationFive'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateOfApplication BETWEEN '" . date('Y-m-d', strtotime($startDate . '-5 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-4 months')) . "') AS 'ApplicationSix'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization BETWEEN '" . $startDate . "' AND '" . date('Y-m-d', strtotime($startDate . ' +1 month')) . "') AS 'EnergizationOne'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization BETWEEN '" . date('Y-m-d', strtotime($startDate . '-1 months')) . "' AND '" . $startDate . "') AS 'EnergizationTwo'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization BETWEEN '" . date('Y-m-d', strtotime($startDate . '-2 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-1 months')) . "') AS 'EnergizationThree'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization BETWEEN '" . date('Y-m-d', strtotime($startDate . '-3 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-2 months')) . "') AS 'EnergizationFour'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization BETWEEN '" . date('Y-m-d', strtotime($startDate . '-4 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-3 months')) . "') AS 'EnergizationFive'"),
                DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE DateTimeOfEnergization BETWEEN '" . date('Y-m-d', strtotime($startDate . '-5 months')) . "' AND '" . date('Y-m-d', strtotime($startDate . '-4 months')) . "') AS 'EnergizationSix'"),)
            ->limit(1)
            ->get();
    
        return response()->json($serviceConnections, 200);
    }

    public function printServiceConnectionApplication($id) {
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.AccountCount as AccountCount', 
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.AccountOrganization as AccountOrganization', 
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.ConnectionApplicationType as ConnectionApplicationType',
                        'CRM_ServiceConnections.MemberConsumerId as MemberConsumerId',
                        'CRM_ServiceConnections.Status as Status',  
                        'CRM_ServiceConnections.Notes as Notes', 
                        'CRM_ServiceConnections.TypeOfOccupancy',
                        'CRM_ServiceConnections.ResidenceNumber',
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.ORNumber as ORNumber', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.LoadCategory as LoadCategory', 
                        'CRM_ServiceConnections.DateTimeOfEnergization as DateTimeOfEnergization', 
                        'CRM_ServiceConnections.DateTimeLinemenArrived as DateTimeLinemenArrived', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                        'CRM_ServiceConnectionCrew.StationName as StationName',
                        'CRM_ServiceConnectionCrew.CrewLeader as CrewLeader',
                        'CRM_ServiceConnectionCrew.Members as Members')
        ->where('CRM_ServiceConnections.id', $id)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        if ($serviceConnection != null) {
            $memberConsumer = DB::table('CRM_MemberConsumers')
            ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
            ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
            ->select('CRM_MemberConsumers.Id as Id',
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
                    'CRM_MemberConsumers.OrganizationRepresentative', 
                    'CRM_MemberConsumers.EmailAddress as EmailAddress',  
                    'CRM_MemberConsumers.Notes as Notes', 
                    'CRM_MemberConsumers.Gender as Gender', 
                    'CRM_MemberConsumers.Sitio as Sitio', 
                    'CRM_MemberConsumerTypes.*',
                    'CRM_Towns.Town as Town',
                    'CRM_Barangays.Barangay as Barangay')
            ->where('CRM_MemberConsumers.Id', $serviceConnection->MemberConsumerId)
            ->first();

            $transactionIndex = TransactionIndex::where('ServiceConnectionId', $id)->first();

            if ($transactionIndex != null) {
                $transactionDetails = TransactionDetails::where('TransactionIndexId', $transactionIndex->id)->get();
            } else {
                $transactionDetails = null;
            }

            if ($memberConsumer != null) {
                $spouse = MemberConsumerSpouse::where('MemberConsumerId', $serviceConnection->MemberConsumerId)->first();
            } else {
                $spouse = null;
            }

            return view('/service_connections/print_service_connection_application', [
                'serviceConnection' => $serviceConnection,
                'memberConsumer' => $memberConsumer,
                'spouse' => $spouse,
                'transactionIndex' => $transactionIndex,
                'transactionDetails' => $transactionDetails,
            ]);
        } else {
            return abort(404, 'Service connection application not found!');
        }
    }  
    
    public function printServiceConnectionContract($id) { 
        $serviceConnection = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_ServiceConnectionAccountTypes', 'CRM_ServiceConnections.AccountType', '=', 'CRM_ServiceConnectionAccountTypes.id')
            ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.AccountCount as AccountCount', 
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.AccountOrganization as AccountOrganization', 
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.ConnectionApplicationType as ConnectionApplicationType',
                        'CRM_ServiceConnections.MemberConsumerId as MemberConsumerId',
                        'CRM_ServiceConnections.Status as Status',  
                        'CRM_ServiceConnections.Notes as Notes', 
                        'CRM_ServiceConnections.TypeOfOccupancy',
                        'CRM_ServiceConnections.ResidenceNumber',
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.ORNumber as ORNumber', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.LoadCategory as LoadCategory', 
                        'CRM_ServiceConnections.DateTimeOfEnergization as DateTimeOfEnergization', 
                        'CRM_ServiceConnections.DateTimeLinemenArrived as DateTimeLinemenArrived', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnectionAccountTypes.AccountType as AccountType',
                        'CRM_ServiceConnectionCrew.StationName as StationName',
                        'CRM_ServiceConnectionCrew.CrewLeader as CrewLeader',
                        'CRM_ServiceConnectionCrew.Members as Members')
        ->where('CRM_ServiceConnections.id', $id)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        if ($serviceConnection != null) {
            $memberConsumer = DB::table('CRM_MemberConsumers')
            ->leftJoin('CRM_MemberConsumerTypes', 'CRM_MemberConsumers.MembershipType', '=', 'CRM_MemberConsumerTypes.Id')
            ->leftJoin('CRM_Barangays', 'CRM_MemberConsumers.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_MemberConsumers.Town', '=', 'CRM_Towns.id')
            ->select('CRM_MemberConsumers.Id as Id',
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
                    'CRM_MemberConsumers.OrganizationRepresentative', 
                    'CRM_MemberConsumers.EmailAddress as EmailAddress',  
                    'CRM_MemberConsumers.Notes as Notes', 
                    'CRM_MemberConsumers.Gender as Gender', 
                    'CRM_MemberConsumers.Sitio as Sitio', 
                    'CRM_MemberConsumerTypes.*',
                    'CRM_Towns.Town as Town',
                    'CRM_Barangays.Barangay as Barangay')
            ->where('CRM_MemberConsumers.Id', $serviceConnection->MemberConsumerId)
            ->first();

            if ($memberConsumer != null) {
                $spouse = MemberConsumerSpouse::where('MemberConsumerId', $serviceConnection->MemberConsumerId)->first();
            } else {
                $spouse = null;
            }

            return view('/service_connections/print_service_connection_contract', [
                'serviceConnection' => $serviceConnection,
                'memberConsumer' => $memberConsumer,
                'spouse' => $spouse,
            ]);
        } else {
            return abort(404, 'Service connection application not found!');
        }
    }

    public function relocationSearch(Request $request) {
        if ($request['params'] == null) {
            $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->select('Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.id', 'Billing_ServiceAccounts.Purok', 'Billing_ServiceAccounts.OldAccountNo', 'CRM_Towns.Town', 'CRM_Barangays.Barangay', 'Billing_ServiceAccounts.AccountCount')
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->paginate(15);
        } else {
            $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->select('Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.id', 'Billing_ServiceAccounts.Purok', 'Billing_ServiceAccounts.OldAccountNo', 'CRM_Towns.Town', 'CRM_Barangays.Barangay', 'Billing_ServiceAccounts.AccountCount')
                        ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['params'] . '%')
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->paginate(15);
        }     

        return view('/service_connections/relocation_search', [
            'serviceAccounts' => $serviceAccounts
        ]);
    }

    public function createRelocation($id) {
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
                    'Billing_ServiceAccounts.ContactNumber',
                    'Billing_ServiceAccounts.Organization',
                    'Billing_ServiceAccounts.OrganizationParentAccount',
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
                    'CRM_Barangays.Barangay')
            ->where('Billing_ServiceAccounts.id', $id)
            ->first();

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        $accountTypes = ServiceConnectionAccountTypes::orderBy('id')->get();

        $crew = ServiceConnectionCrew::orderBy('StationName')->pluck('StationName', 'id');

        return view('/service_connections/create_relocation', [
            'account' => $account,
            'towns' => $towns,
            'accountTypes' => $accountTypes,
            'crew' => $crew,
        ]);
    }

    public function changeNameSearch(Request $request) {
        if ($request['params'] == null) {
            $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->select('Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.id', 'Billing_ServiceAccounts.Purok', 'Billing_ServiceAccounts.OldAccountNo', 'CRM_Towns.Town', 'CRM_Barangays.Barangay', 'Billing_ServiceAccounts.AccountCount')
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->paginate(15);
        } else {
            $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->select('Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.id', 'Billing_ServiceAccounts.Purok', 'Billing_ServiceAccounts.OldAccountNo', 'CRM_Towns.Town', 'CRM_Barangays.Barangay', 'Billing_ServiceAccounts.AccountCount')
                        ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['params'] . '%')
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->paginate(15);
        }     

        return view('/service_connections/change_name_search', [
            'serviceAccounts' => $serviceAccounts
        ]);
    }

    public function createChangeName($id) {
        $account = ServiceAccounts::find($id);

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        $accountTypes = ServiceConnectionAccountTypes::orderBy('id')->get();

        $types = MemberConsumerTypes::orderByDesc('Id')->pluck('Type', 'Id');

        $crew = ServiceConnectionCrew::orderBy('StationName')->pluck('StationName', 'id');

        return view('/service_connections/create_change_name', [
            'account' => $account,
            'towns' => $towns,
            'accountTypes' => $accountTypes,
            'crew' => $crew,
            'types' => $types,
        ]);
    }

    public function storeChangeName(CreateServiceConnectionsRequest $request) {
        $input = $request->all();

        $input['FirstName'] = strtoupper($input['FirstName']);
        $input['MiddleName'] = strtoupper($input['MiddleName']);
        $input['LastName'] = strtoupper($input['LastName']);
        $input['OrganizationName'] = strtoupper($input['OrganizationName']);
        
        if ($input['MembershipType'] == MemberConsumers::getJuridicalId()) {
            $input['ServiceAccountName'] = $input['OrganizationName'];
        } else {
            $input['ServiceAccountName'] = $input['LastName'] . ', ' . $input['FirstName'];
        }

        // SAVE MEMBER CONSUMER
        $mco = new MemberConsumers;
        $mco->Id = IDGenerator::generateID();
        $mco->MembershipType = $input['MembershipType'];
        if ($input['MembershipType'] == MemberConsumers::getJuridicalId()) {
            $mco->OrganizationName = $input['OrganizationName'];
            $mco->OrganizationRepresentative = $input['OrganizationRepresentative'];
        } else {
            $mco->FirstName = $input['FirstName'];
            $mco->MiddleName = $input['MiddleName'];
            $mco->LastName = $input['LastName'];
            $mco->Suffix = $input['Suffix'];
            $mco->Gender = $input['Gender'];
        }
        $mco->Town = $input['Town'];
        $mco->Barangay = $input['Barangay'];
        $mco->Sitio = $input['Sitio'];
        $mco->DateApplied = date('Y-m-d');
        $mco->Notes = "CHANGE NAME";
        $mco->save();

        $input['MemberConsumerId'] = $mco->Id;

        // SAVE SC
        $serviceConnections = $this->serviceConnectionsRepository->create($input);

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $input['id'];
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Received';
        $timeFrame->save();

        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateIDandRandString();
        $timeFrame->ServiceConnectionId = $input['id'];
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = 'Forwarded to Teller For Payment';
        $timeFrame->save();

        // CREATE PAYMENT TRANSACTIONS
        $paymentParticulars = ServiceConnectionPayParticulars::all();
        $subTotal = 0.0;
        $vatTotal = 0.0;
        $overAllTotal = 0.0;
        $totalTransactions = new ServiceConnectionTotalPayments;
        $totalTransactions->id = IDGenerator::generateIDandRandString();
        $totalTransactions->ServiceConnectionId = $input['id'];
        $totalTransactions->SubTotal = $subTotal;
        $totalTransactions->TotalVat = $vatTotal;
        $totalTransactions->Total = $overAllTotal;
        $totalTransactions->save();        

        Flash::success('Service Connections saved successfully.');

        // return redirect(route('serviceConnectionInspections.create-step-two', [$input['id']]));
        return redirect(route('serviceConnections.show', [$input['id']]));
    }

    public function approveForChangeName($id) {
        $serviceConnection = ServiceConnections::find($id);

        if ($serviceConnection != null) {
            $serviceConnection->Status = 'Approved For Change Name';
            $serviceConnection->DateTimeOfEnergization = date('Y-m-d H:i:s');
            $serviceConnection->save();

            // CREATE Timeframes
            $timeFrame = new ServiceConnectionTimeframes;
            $timeFrame->id = IDGenerator::generateID();
            $timeFrame->ServiceConnectionId = $serviceConnection->id;
            $timeFrame->UserId = Auth::id();
            $timeFrame->Status = 'Approved For Change Name';
            $timeFrame->save();
        }

        return redirect(route('serviceConnections.show', [$serviceConnection->id]));
    }

    public function bypassApproveInspection($inspectionId) {
        $inspection = ServiceConnectionInspections::find($inspectionId);

        if ($inspection != null) {
            $inspection->Status = 'Approved';
            $inspection->save();

            $sc = ServiceConnections::find($inspection->ServiceConnectionId);

            if ($sc != null) {
                $sc->Status = 'Approved';
                $sc->save();

                $timeFrame = new ServiceConnectionTimeframes;
                $timeFrame->id = IDGenerator::generateID();
                $timeFrame->ServiceConnectionId = $sc->id;
                $timeFrame->UserId = Auth::id();
                $timeFrame->Status = 'Approved';
                $timeFrame->Notes = 'Bypassed Approval';
                $timeFrame->save();
            }

            return redirect(route('serviceConnections.show', [$inspection->ServiceConnectionId]));
        } else {
            return abort('Inspection not found!', 404);
        }
    }

    public function reInstallationSearch(Request $request) {
        if ($request['params'] == null) {
            $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->select('Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.id', 'Billing_ServiceAccounts.Purok', 'Billing_ServiceAccounts.OldAccountNo', 'CRM_Towns.Town', 'CRM_Barangays.Barangay', 'Billing_ServiceAccounts.AccountCount')
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->paginate(15);
        } else {
            $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->select('Billing_ServiceAccounts.ServiceAccountName', 'Billing_ServiceAccounts.id', 'Billing_ServiceAccounts.Purok', 'Billing_ServiceAccounts.OldAccountNo', 'CRM_Towns.Town', 'CRM_Barangays.Barangay', 'Billing_ServiceAccounts.AccountCount')
                        ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['params'] . '%')
                        ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['params'] . '%')
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->paginate(15);
        }     

        return view('/service_connections/re_installation_search', [
            'serviceAccounts' => $serviceAccounts
        ]);
    }

    public function createReInstallation($id) {
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
                    'Billing_ServiceAccounts.ContactNumber',
                    'Billing_ServiceAccounts.Organization',
                    'Billing_ServiceAccounts.OrganizationParentAccount',
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
                    'CRM_Towns.id As TownId',
                    'CRM_Barangays.id As BarangayId',
                    'CRM_Barangays.Barangay')
            ->where('Billing_ServiceAccounts.id', $id)
            ->first();

        $towns = Towns::orderBy('Town')->pluck('Town', 'id');

        $cond = 'new';

        $accountTypes = ServiceConnectionAccountTypes::orderBy('id')->get();

        $crew = ServiceConnectionCrew::orderBy('StationName')->pluck('StationName', 'id');

        return view('/service_connections/create_re_installation', [
            'account' => $account,
            'towns' => $towns,
            'accountTypes' => $accountTypes,
            'crew' => $crew,
            'cond' => $cond,
        ]);
    }

    public function printContractWithoutMembership($id) {
        $serviceConnection = ServiceConnections::find($id);

        return view('/service_connections/print_contract_without_membership', [
            'serviceConnection' => $serviceConnection,
        ]);
    }

    public function printApplicationFormWithoutMembership($id) {
        $serviceConnection = ServiceConnections::find($id);

        $transactionIndex = TransactionIndex::where('ServiceConnectionId', $id)->first();

        if ($transactionIndex != null) {
            $transactionDetails = TransactionDetails::where('TransactionIndexId', $transactionIndex->id)->get();
        } else {
            $transactionDetails = null;
        }

        return view('/service_connections/print_application_form_without_membership', [
            'serviceConnection' => $serviceConnection,
            'transactionIndex' => $transactionIndex,
            'transactionDetails' => $transactionDetails,
        ]);
    }

    public function meteringInstallation(Request $request) {
        $town = $request['Town'];
        $from = $request['From'];
        $to = $request['To'];

        if ($town == 'All') {
            $data = DB::table('CRM_ServiceConnectionMeterAndTransformer')
                ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->whereRaw("(TRY_CAST(CRM_ServiceConnections.DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_ServiceConnections.Status='Energized'")
                ->select(
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_Barangays.Barangay',
                    'CRM_Towns.Town',
                    'CRM_ServiceConnections.Office',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_ServiceConnections.DateTimeOfEnergization',
                    'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber',
                    'CRM_ServiceConnectionMeterAndTransformer.created_at',
                    'CRM_ServiceConnectionCrew.StationName',
                    'CRM_ServiceConnections.Notes',
                )
                ->orderBy('CRM_Towns.Town')
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();
        } else {
            $data = DB::table('CRM_ServiceConnectionMeterAndTransformer')
                ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->whereRaw("CRM_ServiceConnections.Town='" . $town . "' AND (TRY_CAST(CRM_ServiceConnections.DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_ServiceConnections.Status='Energized'")
                ->select(
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_Barangays.Barangay',
                    'CRM_Towns.Town',
                    'CRM_ServiceConnections.Office',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_ServiceConnections.DateTimeOfEnergization',
                    'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber',
                    'CRM_ServiceConnectionMeterAndTransformer.created_at',
                    'CRM_ServiceConnectionCrew.StationName',
                    'CRM_ServiceConnections.Notes',
                )
                ->orderBy('CRM_Towns.Town')
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();
        }
        
        return view('/service_connections/metering_installation', [
            'data' => $data,
            'towns' => Towns::all()
        ]);
    }

    public function downloadMeteringInstallation($town, $from, $to) {
        if ($town == 'All') {
            $data = DB::table('CRM_ServiceConnectionMeterAndTransformer')
                ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->whereRaw("(TRY_CAST(CRM_ServiceConnections.DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_ServiceConnections.Status='Energized'")
                ->select(
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_Barangays.Barangay',
                    'CRM_Towns.Town',
                    'CRM_ServiceConnections.Office',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_ServiceConnections.DateTimeOfEnergization',
                    'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber',
                    'CRM_ServiceConnectionMeterAndTransformer.created_at',
                    'CRM_ServiceConnectionCrew.StationName',
                    'CRM_ServiceConnections.Notes',
                )
                ->orderBy('CRM_Towns.Town')
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();
        } else {
            $data = DB::table('CRM_ServiceConnectionMeterAndTransformer')
                ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->whereRaw("CRM_ServiceConnections.Town='" . $town . "' AND (TRY_CAST(CRM_ServiceConnections.DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_ServiceConnections.Status='Energized'")
                ->select(
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_Barangays.Barangay',
                    'CRM_Towns.Town',
                    'CRM_ServiceConnections.Office',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_ServiceConnections.DateTimeOfEnergization',
                    'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber',
                    'CRM_ServiceConnectionMeterAndTransformer.created_at',
                    'CRM_ServiceConnectionCrew.StationName',
                    'CRM_ServiceConnections.Notes',
                )
                ->orderBy('CRM_Towns.Town')
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();
        }

        $headers = [
            'Svc. No',
            'Applicant Name',
            'Address',
            'Office',
            'Date of Application',
            'Date of Energization',
            'Meter Serial Number',
            'Date Installed',
            'Station Name',
            'Notes/Remarks',
        ];

        $arr = [];
        foreach($data as $item) {
            array_push($arr, [
                'SvcNo' => "#" . $item->id,
                'Applicant' => $item->ServiceAccountName,
                'Address' => ServiceConnections::getAddress($item),
                'Office' => $item->Office,
                'DateofAppliction' => $item->DateOfApplication != null ? date('M d, Y', strtotime($item->DateOfApplication)) : '-',
                'DateOfEnergization' => $item->DateTimeOfEnergization != null ? date('M d, Y', strtotime($item->DateTimeOfEnergization)) : '-',
                'MeterNo' => $item->MeterSerialNumber,
                'DateInstalled' => $item->DateTimeOfEnergization != null ? date('M d, Y', strtotime($item->DateTimeOfEnergization)) : '-',
                'Crew' => $item->StationName,
                'Remarks' => $item->Notes,
            ]);
        }

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
        
        $export = new DynamicExportsNoBillingMonth($arr, 
                                    $town,
                                    $headers, 
                                    [],
                                    'A8',
                                    $styles,
                                    'METER INSTALLATION REPORT FROM ' . date('M d, Y', strtotime($from)) . ' TO ' . date('M d, Y', strtotime($to))
                                );

        return Excel::download($export, 'Meter-Installation-Report.xlsx');
    }

    public function detailedSummary(Request $request) {
        $town = $request['Town'];
        $from = $request['From'];
        $to = $request['To'];

        if ($town == 'All') {
            $data = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->whereRaw("(TRY_CAST(CRM_ServiceConnections.DateOfApplication AS DATE) BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select(
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_Barangays.Barangay',
                    'CRM_Towns.Town',
                    'CRM_ServiceConnections.Status',
                    'CRM_ServiceConnections.Office',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_ServiceConnections.DateTimeOfEnergization',
                    'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber',
                    'CRM_ServiceConnectionMeterAndTransformer.created_at',
                    'CRM_ServiceConnectionCrew.StationName',
                    'CRM_ServiceConnections.Notes',
                )
                ->orderBy('CRM_Towns.Town')
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();
        } else {
            $data = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->whereRaw("CRM_ServiceConnections.Town='" . $town . "' AND (TRY_CAST(CRM_ServiceConnections.DateOfApplication AS DATE) BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select(
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_Barangays.Barangay',
                    'CRM_Towns.Town',
                    'CRM_ServiceConnections.Status',
                    'CRM_ServiceConnections.Office',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_ServiceConnections.DateTimeOfEnergization',
                    'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber',
                    'CRM_ServiceConnectionMeterAndTransformer.created_at',
                    'CRM_ServiceConnectionCrew.StationName',
                    'CRM_ServiceConnections.Notes',
                )
                ->orderBy('CRM_Towns.Town')
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();
        }
        
        return view('/service_connections/detailed_summary', [
            'data' => $data,
            'towns' => Towns::all()
        ]);
    }

    public function downloadDetailedSummary($town, $from, $to) {
        if ($town == 'All') {
            $data = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->whereRaw("(TRY_CAST(CRM_ServiceConnections.DateOfApplication AS DATE) BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select(
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_Barangays.Barangay',
                    'CRM_Towns.Town',
                    'CRM_ServiceConnections.Status',
                    'CRM_ServiceConnections.Office',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_ServiceConnections.DateTimeOfEnergization',
                    'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber',
                    'CRM_ServiceConnectionMeterAndTransformer.created_at',
                    'CRM_ServiceConnectionCrew.StationName',
                    'CRM_ServiceConnections.Notes',
                )
                ->orderBy('CRM_Towns.Town')
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();
        } else {
            $data = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionCrew', 'CRM_ServiceConnections.StationCrewAssigned', '=', 'CRM_ServiceConnectionCrew.id')
                ->whereRaw("CRM_ServiceConnections.Town='" . $town . "' AND (TRY_CAST(CRM_ServiceConnections.DateOfApplication AS DATE) BETWEEN '" . $from . "' AND '" . $to . "')")
                ->select(
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_Barangays.Barangay',
                    'CRM_Towns.Town',
                    'CRM_ServiceConnections.Status',
                    'CRM_ServiceConnections.Office',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_ServiceConnections.DateTimeOfEnergization',
                    'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber',
                    'CRM_ServiceConnectionMeterAndTransformer.created_at',
                    'CRM_ServiceConnectionCrew.StationName',
                    'CRM_ServiceConnections.Notes',
                )
                ->orderBy('CRM_Towns.Town')
                ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                ->get();
        }

        $headers = [
            'Svc. No',
            'Applicant Name',
            'Address',
            'Office',
            'Status',
            'Date of Application',
            'Date of Energization',
            'Meter Serial Number',
            'Date Installed',
            'Station Name',
            'Notes/Remarks',
        ];

        $arr = [];
        foreach($data as $item) {
            array_push($arr, [
                'SvcNo' => "#" . $item->id,
                'Applicant' => $item->ServiceAccountName,
                'Address' => ServiceConnections::getAddress($item),
                'Office' => $item->Office,
                'Status' => $item->Status,
                'DateofAppliction' => $item->DateOfApplication != null ? date('M d, Y', strtotime($item->DateOfApplication)) : '-',
                'DateOfEnergization' => $item->DateTimeOfEnergization != null ? date('M d, Y', strtotime($item->DateTimeOfEnergization)) : '-',
                'MeterNo' => $item->MeterSerialNumber,
                'DateInstalled' => $item->DateTimeOfEnergization != null ? date('M d, Y', strtotime($item->DateTimeOfEnergization)) : '-',
                'Crew' => $item->StationName,
                'Remarks' => $item->Notes,
            ]);
        }

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
        
        $export = new DynamicExportsNoBillingMonth($arr, 
                                    $town,
                                    $headers, 
                                    [],
                                    'A8',
                                    $styles,
                                    'DETAILED APPLICATION SUMMARY REPORT FROM ' . date('M d, Y', strtotime($from)) . ' TO ' . date('M d, Y', strtotime($to))
                                );

        return Excel::download($export, 'Detailed-Application-Summary-Report.xlsx');
    }

    public function summaryReport(Request $request) {
        $town = $request['Town'];
        $from = $request['From'];
        $to = $request['To'];

        if ($town != null) {
            if ($town == 'All') {
                $data = DB::table('CRM_ServiceConnections')
                    ->select(
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE (Trash IS NULL OR Trash='No') AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') ) AS TotalApplications"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE (Trash IS NULL OR Trash='No') AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND Status IN ('For Inspection')) AS ForStaking"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE (Trash IS NULL OR Trash='No') AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND Status IN ('Approved') AND ORNumber IS NULL) AS ForPayment"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE (Trash IS NULL OR Trash='No') AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND Status IN ('Approved') AND ORNumber IS NOT NULL) AS ForMeterAssigning"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE (Trash IS NULL OR Trash='No') AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND id IN 
                            (SELECT ServiceConnectionId FROM CRM_ServiceConnectionMeterAndTransformer WHERE ServiceConnectionId IS NOT NULL)) AS ForEnergization"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE (Trash IS NULL OR Trash='No') AND (TRY_CAST(DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND Status IN ('Energized')) AS Energized"),
                    )
                    ->first();
            } else {
                $data = DB::table('CRM_ServiceConnections')
                    ->select(
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE Town='" . $town . "' AND (Trash IS NULL OR Trash='No') AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') ) AS TotalApplications"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE Town='" . $town . "' AND (Trash IS NULL OR Trash='No') AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND Status IN ('For Inspection')) AS ForStaking"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE Town='" . $town . "' AND (Trash IS NULL OR Trash='No') AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND Status IN ('Approved') AND ORNumber IS NULL) AS ForPayment"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE Town='" . $town . "' AND (Trash IS NULL OR Trash='No') AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND Status IN ('Approved') AND ORNumber IS NOT NULL) AS ForMeterAssigning"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE Town='" . $town . "' AND (Trash IS NULL OR Trash='No') AND (DateOfApplication BETWEEN '" . $from . "' AND '" . $to . "') AND id IN 
                            (SELECT ServiceConnectionId FROM CRM_ServiceConnectionMeterAndTransformer WHERE ServiceConnectionId IS NOT NULL)) AS ForEnergization"),
                        DB::raw("(SELECT COUNT(id) FROM CRM_ServiceConnections WHERE Town='" . $town . "' AND (Trash IS NULL OR Trash='No') AND (TRY_CAST(DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "') AND Status IN ('Energized')) AS Energized"),
                    )
                    ->first();
            }
        } else {
            $data = [];
        }        

        return view('/service_connections/summary_report', [
            'towns' => Towns::all(),
            'data' => $data,
        ]);
    }

    public function mriv(Request $request) {
        $town = $request['Town'];
        $from = $request['From'];
        $to = $request['To'];

        if ($town != null) {
            if ($town == 'All') {
                $data = DB::table('CRM_ServiceConnections')
                        ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                        ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionInspections.ServiceConnectionId')
                        ->select('CRM_ServiceConnections.id as ConsumerId',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnections.ORNumber',
                                'CRM_ServiceConnections.ORDate',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnections.Status',
                                'CRM_ServiceConnectionInspections.DateOfVerification',
                                'CRM_ServiceConnectionInspections.SDWLengthAsInstalled',
                            )
                        ->where(function ($query) {
                                            $query->where('CRM_ServiceConnections.Trash', 'No')
                                                ->orWhereNull('CRM_ServiceConnections.Trash');
                                        })
                        ->whereRaw("CRM_ServiceConnections.Status IN ('Approved', 'Downloaded by Crew') AND ConnectionApplicationType NOT IN ('Change Name') AND
                            (CRM_ServiceConnections.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_ServiceConnectionInspections.DateOfVerification IS NOT NULL")
                        ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                        ->get();
            } else {
                $data = DB::table('CRM_ServiceConnections')
                        ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                        ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionInspections.ServiceConnectionId')
                        ->select('CRM_ServiceConnections.id as ConsumerId',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnections.ORNumber',
                                'CRM_ServiceConnections.ORDate',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnections.Status',
                                'CRM_ServiceConnectionInspections.DateOfVerification',
                                'CRM_ServiceConnectionInspections.SDWLengthAsInstalled',
                            )
                        ->where(function ($query) {
                                            $query->where('CRM_ServiceConnections.Trash', 'No')
                                                ->orWhereNull('CRM_ServiceConnections.Trash');
                                        })
                        ->whereRaw("CRM_ServiceConnections.Status IN ('Approved', 'Downloaded by Crew') AND ConnectionApplicationType NOT IN ('Change Name') AND
                            CRM_ServiceConnections.Town='" . $town . "' AND  
                            (CRM_ServiceConnections.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_ServiceConnectionInspections.DateOfVerification IS NOT NULL")
                        ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                        ->get();
            }
        } else {
            $data = [];
        }        

        return view('/service_connections/mriv', [
            'towns' => Towns::all(),
            'data' => $data,
        ]);
    }

    public function printMriv($town, $from, $to) {
        if ($town != null) {
            if ($town == 'All') {
                $data = DB::table('CRM_ServiceConnections')
                        ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                        ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionInspections.ServiceConnectionId')
                        ->select('CRM_ServiceConnections.id as ConsumerId',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnections.ORNumber',
                                'CRM_ServiceConnections.ORDate',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnections.Status',
                                'CRM_ServiceConnectionInspections.SDWLengthAsInstalled',
                            )
                        ->where(function ($query) {
                                            $query->where('CRM_ServiceConnections.Trash', 'No')
                                                ->orWhereNull('CRM_ServiceConnections.Trash');
                                        })
                        ->whereRaw("CRM_ServiceConnections.Status IN ('Approved', 'Downloaded by Crew') AND ConnectionApplicationType NOT IN ('Change Name') AND
                            (CRM_ServiceConnections.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_ServiceConnectionInspections.DateOfVerification IS NOT NULL")
                        ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                        ->get();
            } else {
                $data = DB::table('CRM_ServiceConnections')
                        ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                        ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionInspections.ServiceConnectionId')
                        ->select('CRM_ServiceConnections.id as ConsumerId',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnections.ORNumber',
                                'CRM_ServiceConnections.ORDate',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnections.Status',
                                'CRM_ServiceConnectionInspections.SDWLengthAsInstalled',
                            )
                        ->where(function ($query) {
                                            $query->where('CRM_ServiceConnections.Trash', 'No')
                                                ->orWhereNull('CRM_ServiceConnections.Trash');
                                        })
                        ->whereRaw("CRM_ServiceConnections.Status IN ('Approved', 'Downloaded by Crew') AND ConnectionApplicationType NOT IN ('Change Name') AND
                            CRM_ServiceConnections.Town='" . $town . "' AND  
                            (CRM_ServiceConnections.ORDate BETWEEN '" . $from . "' AND '" . $to . "') AND CRM_ServiceConnectionInspections.DateOfVerification IS NOT NULL")
                        ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                        ->get();
            }
        } else {
            $data = [];
        }        

        return view('/service_connections/print_mriv', [
            'town' => $town=='All' ? 'All' : Towns::find($town)->Town,
            'data' => $data,
        ]);
    }

    public function updateStatus(Request $request) {
        $id = $request['id'];
        $status = $request['Status'];

        ServiceConnections::where('id', $id)
            ->update(['Status' => $status]);

        // CREATE Timeframes
        $timeFrame = new ServiceConnectionTimeframes;
        $timeFrame->id = IDGenerator::generateID();
        $timeFrame->ServiceConnectionId = $id;
        $timeFrame->UserId = Auth::id();
        $timeFrame->Status = $status;
        $timeFrame->Notes = 'Status updated manually';
        $timeFrame->save();

        $serviceConnections = ServiceConnections::find($id);

        if ($status == 'For Payment') {
            if ($serviceConnections != null) {

                $paymentOrder = PaymentOrder::where('ServiceConnectionId', $id)->first();

                // SEND SMS
                $smsSettings = SmsSettings::orderByDesc('created_at')->first();
                // PaymentApproved
                if ($serviceConnections->ContactNumber != null) {
                    if (strlen($serviceConnections->ContactNumber) > 10 && strlen($serviceConnections->ContactNumber) < 13) {
                        if ($smsSettings != null && $smsSettings->PaymentApproved=='Yes') {
                            $msg = "BLCI Notification\n\nHello " . $serviceConnections->ServiceAccountName . ", \nYour " . $serviceConnections->AccountApplicationType . " application with control no. " . $id . ", amounting P " . number_format($paymentOrder->OverAllTotal, 2) . ", is approved for payment. " .
                                "You can now pay the fees to BLCI offices. \nHave a great day!";
                            Notifications::createFreshSms($serviceConnections->ContactNumber, $msg, 'SERVICE CONNECTION INSPECTION', $id);
                        }
                    }                    
                } 
            }
        }

        return response()->json('ok', 200);
    }

    public function serviceConnectionsReport(Request $request) {
        $town = $request['Town'];
        $from = $request['From'];
        $to = $request['To'];

        if ($town != null) {
            if ($town == 'All') {
                $data = DB::table('CRM_ServiceConnections')
                        ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                        ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionInspections.ServiceConnectionId')
                        ->select('CRM_ServiceConnections.id as ConsumerId',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnections.ORNumber',
                                'CRM_ServiceConnections.ORDate',
                                'CRM_ServiceConnections.DateTimeOfEnergization',
                                'CRM_ServiceConnections.DateOfApplication',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnections.Status',
                                'CRM_ServiceConnectionInspections.DateOfVerification',
                                'CRM_ServiceConnectionInspections.SDWLengthAsInstalled',
                            )
                        ->whereRaw("(Trash IS NULL OR Trash='No')")
                        ->whereRaw("CRM_ServiceConnections.Status IN ('Energized') AND ConnectionApplicationType NOT IN ('Change Name') AND
                            (TRY_CAST(CRM_ServiceConnections.DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "')")
                        ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                        ->get();
            } else {
                $data = DB::table('CRM_ServiceConnections')
                        ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                        ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionInspections.ServiceConnectionId')
                        ->select('CRM_ServiceConnections.id as ConsumerId',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnections.ORNumber',
                                'CRM_ServiceConnections.ORDate',
                                'CRM_ServiceConnections.DateTimeOfEnergization',
                                'CRM_ServiceConnections.DateOfApplication',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnections.Status',
                                'CRM_ServiceConnectionInspections.DateOfVerification',
                                'CRM_ServiceConnectionInspections.SDWLengthAsInstalled',
                            )
                        ->whereRaw("(Trash IS NULL OR Trash='No')")
                        ->whereRaw("CRM_ServiceConnections.Status IN ('Energized') AND ConnectionApplicationType NOT IN ('Change Name') AND
                            CRM_ServiceConnections.Town='" . $town . "' AND  
                            (TRY_CAST(CRM_ServiceConnections.DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "')")
                        ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                        ->get();
            }
        } else {
            $data = [];
        }

        return view('/service_connections/service_connection_report', [
            'towns' => Towns::orderBy('Town')->get(),
            'data' => $data,
        ]);
    }

    public function printServiceConnectionsReport($town, $from, $to) {
        if ($town != null) {
            if ($town == 'All') {
                $data = DB::table('CRM_ServiceConnections')
                        ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                        ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionInspections.ServiceConnectionId')
                        ->select('CRM_ServiceConnections.id as ConsumerId',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnections.ORNumber',
                                'CRM_ServiceConnections.ORDate',
                                'CRM_ServiceConnections.DateTimeOfEnergization',
                                'CRM_ServiceConnections.DateOfApplication',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnections.Status',
                                'CRM_ServiceConnectionInspections.DateOfVerification',
                                'CRM_ServiceConnectionInspections.SDWLengthAsInstalled',
                            )
                        ->whereRaw("(Trash IS NULL OR Trash='No')")
                        ->whereRaw("CRM_ServiceConnections.Status IN ('Energized') AND ConnectionApplicationType NOT IN ('Change Name') AND
                            (TRY_CAST(CRM_ServiceConnections.DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "')")
                        ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                        ->get();
            } else {
                $data = DB::table('CRM_ServiceConnections')
                        ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                        ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionInspections.ServiceConnectionId')
                        ->select('CRM_ServiceConnections.id as ConsumerId',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnections.ORNumber',
                                'CRM_ServiceConnections.ORDate',
                                'CRM_ServiceConnections.DateTimeOfEnergization',
                                'CRM_ServiceConnections.DateOfApplication',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnections.Status',
                                'CRM_ServiceConnectionInspections.DateOfVerification',
                                'CRM_ServiceConnectionInspections.SDWLengthAsInstalled',
                            )
                        ->whereRaw("(Trash IS NULL OR Trash='No')")
                        ->whereRaw("CRM_ServiceConnections.Status IN ('Energized') AND ConnectionApplicationType NOT IN ('Change Name') AND
                            CRM_ServiceConnections.Town='" . $town . "' AND  
                            (TRY_CAST(CRM_ServiceConnections.DateTimeOfEnergization AS DATE) BETWEEN '" . $from . "' AND '" . $to . "')")
                        ->orderBy('CRM_ServiceConnections.ServiceAccountName')
                        ->get();
            }
        } else {
            $data = [];
        }       

        return view('/service_connections/print_service_connection_report', [
            'data' => $data,
        ]);
    }
    
    public function paymentOrder($scId) {
        $whHead = DB::connection('mysql')
            ->table('tblor_head')
            ->whereRaw("appl_no='" . $scId . "'")
            ->select('*')
            ->first();

        $entNoLast = DB::connection('mysql')
            ->table('tblor_head')
            ->select('ent_no')
            ->orderByDesc('ent_no')
            ->first();

        if ($whHead != null) {
            $whItems = DB::connection('mysql')
                ->table('tblor_line')
                ->whereRaw("reqno='" . $whHead->orderno . "'")
                ->select('*')
                ->get();
        } else {
            $whItems = [];
        }     
        
        $serviceConnection = DB::connection('sqlsrv')
            ->table('CRM_ServiceConnections')
            ->whereRaw("id='" . $scId . "'")
            ->select('*')
            ->first();

        $inspection = ServiceConnectionInspections::where('ServiceConnectionId', $scId)->first();
        
        return view('/service_connections/payment_order', [
            'whHead' => $whHead,
            'whItems' => $whItems,
            'serviceConnection' => $serviceConnection,
            'entNoLast' => $entNoLast,
            'costCenters' => CostCenters::orderBy('CostCode')->get(),
            'projectCodes' => ProjectCodes::orderBy('ProjectCode')->get(),
            'inspection' => $inspection,
        ]);
    }

    public function savePaymentOrder(Request $request) {
        $materialItems = $request['MaterialItems'];
        $meterItems = $request['MeterItems'];
        $ServiceConnectionId = $request['ServiceConnectionId'];
        $MaterialDeposit = $request['MaterialDeposit'];
        $TransformerRentalFees = $request['TransformerRentalFees'];
        $Apprehension = $request['Apprehension'];
        $OverheadExpenses = $request['OverheadExpenses'];
        $CIAC = $request['CIAC'];
        $ServiceFee = $request['ServiceFee'];
        $CustomerDeposit = $request['CustomerDeposit'];
        $Others = $request['Others'];
        $SaleOfMaterials = $request['SaleOfMaterials'];
        $LocalFTax = $request['LocalFTax'];
        $SubTotal = $request['SubTotal'];
        $VAT = $request['VAT'];
        $OthersTotal = $request['OthersTotal'];
        $OverAllTotal = $request['OverAllTotal'];
        $ORNumber = $request['ORNumber'];
        $MaterialTotal = $request['MaterialTotal'];
        $reqNo = $request['ReqNo'];
        $mirsNo = $request['MIRSNo'];
        $CostCenter = $request['CostCenter'];
        $chargeTo = $request['ChargeTo'];
        $projectCode = $request['ProjectCode'];
        $requestedBy = $request['RequestedBy'];
        $invoiceNo = $request['InvoiceNo'];
        $customerName = $request['CustomerName'];
        $typeOfServiceId = $request['TypeOfServiceId'];
        $entryNo = $request['EntryNo'];
        $meter_reqNo = $request['MeterReqNo'];
        $meter_mirsNo = $request['MeterMIRSNo'];
        $meter_CostCenter = $request['MeterCostCenter'];
        $meter_chargeTo = $request['MeterChargeTo'];
        $meter_projectCode = $request['MeterProjectCode'];
        $meter_requestedBy = $request['MeterRequestedBy'];
        $meter_invoiceNo = $request['MeterInvoiceNo'];
        $meter_customerName = $request['MeterCustomerName'];
        $meter_typeOfServiceId = $request['MeterTypeOfServiceId'];
        $meter_entryNo = $request['MeterEntryNo'];
        $acctNo = $request['AccountNumber'];

        $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        )
        ->where('CRM_ServiceConnections.id', $ServiceConnectionId)
        ->first();

        // UPDATE AccountNumber
        if ($ORNumber != null && $ORNumber !== '0') {
            ServiceConnections::where('id', $ServiceConnectionId)
                ->update(['AccountNumber' => $acctNo, 'ORNumber' => $ORNumber, 'Status' => 'For Energization']);
        } else {
            ServiceConnections::where('id', $ServiceConnectionId)
                ->update(['AccountNumber' => $acctNo,  'ORNumber' => null]);
        }
        

        $materialItems = json_decode(stripslashes($materialItems));
        $meterItems = json_decode(stripslashes($meterItems));

        // CHECK FIRST IF PAYMENT ORDER EXIST
        $paymentOrder = PaymentOrder::where('ServiceConnectionId', $ServiceConnectionId)->first();
        if ($paymentOrder != null) {
            $paymentOrder->delete();
        } 

        $paymentOrder = new PaymentOrder;
        $paymentOrder->id = IDGenerator::generateID();
        $paymentOrder->ServiceConnectionId = $ServiceConnectionId;
        $paymentOrder->MaterialDeposit = $MaterialDeposit;
        $paymentOrder->TransformerRentalFees = $TransformerRentalFees;
        $paymentOrder->Apprehension = $Apprehension;
        $paymentOrder->OverheadExpenses = $OverheadExpenses;
        $paymentOrder->CIAC = $CIAC;
        $paymentOrder->ServiceFee = $ServiceFee;
        $paymentOrder->CustomerDeposit = $CustomerDeposit;
        $paymentOrder->Others = $Others;
        $paymentOrder->LocalFTax = $LocalFTax;
        $paymentOrder->SubTotal = $SubTotal;
        $paymentOrder->VAT = $VAT;
        $paymentOrder->OthersTotal = $OthersTotal;
        $paymentOrder->OverAllTotal = $OverAllTotal;
        $paymentOrder->ORNumber = $ORNumber;
        $paymentOrder->ORDate = date('Y-m-d');
        $paymentOrder->MaterialTotal = $MaterialTotal;
        $paymentOrder->SaleOfMaterials = $SaleOfMaterials;
        $paymentOrder->save();

        // DELETE WAREHOUSE HEAD
        $whHead = WarehouseHead::where('appl_no', $ServiceConnectionId)->get();

        if ($whHead != null) {
            foreach($whHead as $item) {
                $item->delete();

                // DELETE MATERIAL ITEMS FIRST
                $whItems = WarehouseItems::where('reqno', $item->orderno)->delete();
            }            
        }
        // WarehouseHead::where('appl_no', $ServiceConnectionId)->delete();
        // WarehouseItems::where('reqno', $whHead->orderno)->delete();

        // SAVE WAREHOUSE HEAD ITEMS
        $whHead = new WarehouseHead;
        $whHead->orderno = $reqNo;
        $whHead->ent_no = $entryNo;
        $whHead->misno = $mirsNo;
        $whHead->address = ServiceConnections::getAddress($serviceConnections);
        $whHead->tdate = date('m/d/Y');
        $whHead->emp_id = Auth::id();
        $whHead->ccode = $CostCenter;
        $whHead->dept = $chargeTo;
        $whHead->pcode = $projectCode;
        $whHead->reqby = $requestedBy;
        $whHead->invoice = $invoiceNo;
        $whHead->orno = $ORNumber;
        $whHead->purpose = 'FOR ' . strtoupper($customerName);
        $whHead->serv_code = $typeOfServiceId;
        $whHead->account_no = $acctNo;
        $whHead->cust_name = $customerName;
        $whHead->tot_amt = $MaterialTotal;
        $whHead->chkby = $requestedBy;
        if (Auth::user()->hasAnyRole(ServiceConnections::whHeadStatus())) {
            $whHead->stat = 'Checked';
        } else {
            $whHead->stat = 'Pending';
        }
        
        $whHead->rdate = date('m/d/Y');
        $whHead->rtime = date('h:i A');
        $whHead->walk_in = 0;
        $whHead->appl_no = $ServiceConnectionId;
        $whHead->appby = ' ';
        $whHead->save();

        foreach($materialItems as $item) {
            $whItems = new WarehouseItems;
            $whItems->reqno = $item->ReqNo;
            $whItems->ent_no = $item->EntryNo;            
            $whItems->tdate = date('m/d/Y');
            $whItems->itemcd = $item->ItemCode;  
            $whItems->qty = $item->ItemQuantity;  
            $whItems->uom = $item->ItemUOM; 
            $whItems->cst = $item->ItemSalesPrice; 
            $whItems->amt = $item->ItemTotalCost; 
            $whItems->itemno = $item->ItemNo; 
            $whItems->rdate = date('m/d/Y');
            $whItems->rtime = date('h:i A');
            $whItems->salesprice = $item->ItemUnitPrice;
            $whItems->save();
        }

        // SAVE WAREHOUSE HEAD METERS
        if ($meter_reqNo != null && count($meterItems) > 0) {
            $whHead = new WarehouseHead;
            $whHead->orderno = $meter_reqNo;
            $whHead->ent_no = $meter_entryNo;
            $whHead->misno = $meter_mirsNo;
            $whHead->address = ServiceConnections::getAddress($serviceConnections);
            $whHead->tdate = date('m/d/Y');
            $whHead->emp_id = Auth::id();
            $whHead->ccode = $CostCenter;
            $whHead->dept = $meter_chargeTo;
            $whHead->pcode = $meter_projectCode;
            $whHead->reqby = $meter_requestedBy;
            $whHead->invoice = $meter_invoiceNo;
            $whHead->orno = $ORNumber;
            $whHead->purpose = 'FOR ' . strtoupper($meter_customerName);
            $whHead->serv_code = $meter_typeOfServiceId;
            $whHead->account_no = $acctNo;
            $whHead->cust_name = $meter_customerName;
            $whHead->tot_amt = $MaterialTotal;
            $whHead->chkby = $meter_requestedBy;
            if (Auth::user()->hasAnyRole(ServiceConnections::whHeadStatus())) {
                $whHead->stat = 'Checked';
            } else {
                $whHead->stat = 'Pending';
            }
            $whHead->rdate = date('m/d/Y');
            $whHead->rtime = date('h:i A');
            $whHead->walk_in = 0;
            $whHead->appl_no = $ServiceConnectionId;
            $whHead->appby = ' ';
            $whHead->save();

            foreach($meterItems as $item) {
                $whItems = new WarehouseItems;
                $whItems->reqno = $item->ReqNo;
                $whItems->ent_no = $item->EntryNo;            
                $whItems->tdate = date('m/d/Y');
                $whItems->itemcd = $item->ItemCode;  
                $whItems->qty = $item->ItemQuantity;  
                $whItems->uom = $item->ItemUOM; 
                $whItems->cst = $item->ItemSalesPrice; 
                $whItems->amt = $item->ItemTotalCost; 
                $whItems->itemno = $item->ItemNo; 
                $whItems->rdate = date('m/d/Y');
                $whItems->rtime = date('h:i A');
                $whItems->salesprice = $item->ItemUnitPrice;
                $whItems->save();
            }
        }
        return response()->json('ok', 200);
    }

    public function updatePaymentOrder($scId) {
        $whHead = DB::connection('mysql')
            ->table('tblor_head')
            ->whereRaw("appl_no='" . $scId . "' AND orderno NOT LIKE 'M%'")
            ->select('*')
            ->first();

        $entNoLast = DB::connection('mysql')
            ->table('tblor_head')
            ->select('ent_no')
            ->orderByDesc('ent_no')
            ->first();

        if ($whHead != null) {
            $whItems = DB::connection('mysql')
                ->table('tblor_line')
                ->leftJoin('tblitems', 'tblor_line.itemcd', '=', 'tblitems.itm_code')
                ->whereRaw("reqno='" . $whHead->orderno . "'")
                ->select(
                    'tblor_line.*', 
                    'tblitems.itm_desc'
                    )
                ->orderBy('itemno')
                ->get();
        } else {
            $whItems = [];
        }  
        
        $whHeadMeters = DB::connection('mysql')
            ->table('tblor_head')
            ->whereRaw("appl_no='" . $scId . "' AND orderno LIKE 'M%'")
            ->select('*')
            ->first();

        if ($whHeadMeters != null) {
            $whItemsMeters = DB::connection('mysql')
                ->table('tblor_line')
                ->leftJoin('tblitems', 'tblor_line.itemcd', '=', 'tblitems.itm_code')
                ->whereRaw("reqno='" . $whHeadMeters->orderno . "'")
                ->select(
                    'tblor_line.*', 
                    'tblitems.itm_desc'
                    )
                ->orderBy('itemno')
                ->get();
        } else {
            $whItemsMeters = [];
        }  
        
        $serviceConnection = DB::connection('sqlsrv')
            ->table('CRM_ServiceConnections')
            ->whereRaw("id='" . $scId . "'")
            ->select('*')
            ->first();

        $paymentOrder = PaymentOrder::where("ServiceConnectionId", $scId)->first();

        return view('/service_connections/update_payment_order', [
            'whHead' => $whHead,
            'whItems' => $whItems,
            'whHeadMeters' => $whHeadMeters,
            'whItemsMeters' => $whItemsMeters,
            'serviceConnection' => $serviceConnection,
            'paymentOrder' => $paymentOrder,
            'costCenters' => CostCenters::orderBy('CostCode')->get(),
            'projectCodes' => ProjectCodes::orderBy('ProjectCode')->get(),
            'entNoLast' => $entNoLast,
        ]);
    }

    public function inspectionMonitor(Request $request) {
        return view('/service_connections/inspection_monitor', [
            'inspectors' => User::role('Inspector')->get(),
        ]);
    }

    public function getInspectionSummaryData(Request $request) {
        $month = $request['ServicePeriod'];
        $from = date('Y-m-d', strtotime($month));
        $to = date('Y-m-d', strtotime($from . ' +1 month'));

        $summary = DB::table('CRM_ServiceConnectionInspections')
            ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
            ->whereNotNull('CRM_ServiceConnectionInspections.Inspector')
            ->select('users.name', 'users.id',
                DB::raw("(SELECT COUNT(a.id) FROM CRM_ServiceConnectionInspections a LEFT JOIN CRM_ServiceConnections b ON a.ServiceConnectionId=b.id WHERE b.Trash IS NULL AND a.Status IN ('FOR INSPECTION', 'Re-Inspection') AND a.Inspector=users.id AND (a.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS ForInspection"),
                DB::raw("(SELECT COUNT(a.id) FROM CRM_ServiceConnectionInspections a LEFT JOIN CRM_ServiceConnections b ON a.ServiceConnectionId=b.id WHERE b.Trash IS NULL AND a.Status='Approved' AND a.Inspector=users.id AND (a.DateOfVerification BETWEEN '" . $from . "' AND '" . $to . "')) AS ApprovedThisMonth"),
                DB::raw("(SELECT COUNT(a.id) FROM CRM_ServiceConnectionInspections a LEFT JOIN CRM_ServiceConnections b ON a.ServiceConnectionId=b.id WHERE b.Trash IS NULL AND a.Inspector=users.id AND (a.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS Total"),
                DB::raw("(SELECT COUNT(a.id) FROM CRM_ServiceConnectionInspections a LEFT JOIN CRM_ServiceConnections b ON a.ServiceConnectionId=b.id WHERE b.Trash IS NULL AND a.Inspector=users.id AND (a.created_at BETWEEN '" . date('Y-m-d') . "' AND '" . date('Y-m-d', strtotime('tomorrow')) . "')) AS Today"),
                DB::raw("(SELECT AVG(DATEDIFF(day, b.DateOfApplication, a.DateOfVerification)) FROM CRM_ServiceConnectionInspections a LEFT JOIN CRM_ServiceConnections b ON a.ServiceConnectionId=b.id WHERE b.Trash IS NULL AND a.Inspector=users.id AND (a.created_at BETWEEN '" . $from . "' AND '" . $to . "')) AS AverageHours"),
            )
            ->groupBy('users.name', 'users.id')
            ->orderBy('users.name')
            ->get();

        $days = round((strtotime($to) - strtotime($from)) / (60 * 60 * 24));

        $output = "";
        foreach($summary as $item) {
            if ($item->name != null) {
                $output .= "<tr>
                                <td>" . $item->name . "</td>
                                <td class='text-right'>" . $item->Today . "</td>
                                <td class='text-right'>" . $item->ForInspection . "</td>
                                <td class='text-right'>" . $item->ApprovedThisMonth . "</td>
                                <th class='text-right text-success'>" . $item->Total . "</th>
                                <td class='text-right'>" . $days . "</td>
                                <th class='text-right text-primary'>" . ($item->Total != null && intval($item->Total) > 0 ? round(intval($item->Total)/$days, 2) : 0) . "</th>
                            </tr>";
            }            
        }

        return response()->json($output, 200);
    }

    public function getInspectionSummaryDataCalendar(Request $request) {
        $month = $request['ServicePeriod'];
        $from = date('Y-m-d', strtotime($month));
        $to = date('Y-m-d', strtotime($from . ' +1 month'));
        $inspector = $request['Inspector'];

        $inspection = DB::table('CRM_ServiceConnectionInspections')
            ->whereNotNull('CRM_ServiceConnectionInspections.Inspector')
            ->whereRaw("Inspector='" . $inspector . "' AND ReInspectionSchedule IS NULL")
            ->select(
                DB::raw("'Inspection' AS Type"),
                DB::raw("TRY_CAST(InspectionSchedule AS DATE) AS InspectionSchedule"),
                DB::raw("COUNT(InspectionSchedule) AS Count"),
            )
            ->groupBy(DB::raw("TRY_CAST(InspectionSchedule AS DATE)"))
            ->orderBy(DB::raw("TRY_CAST(InspectionSchedule AS DATE)"))
            ->get();

        $reinspection = DB::table('CRM_ServiceConnectionInspections')
            ->whereNotNull('CRM_ServiceConnectionInspections.Inspector')
            ->whereRaw("Inspector='" . $inspector . "'")
            ->select(
                DB::raw("'ReInspection' AS Type"),
                DB::raw("TRY_CAST(ReInspectionSchedule AS DATE) AS InspectionSchedule"),
                DB::raw("COUNT(ReInspectionSchedule) AS Count"),
            )
            ->groupBy(DB::raw("TRY_CAST(ReInspectionSchedule AS DATE)"))
            ->orderBy(DB::raw("TRY_CAST(ReInspectionSchedule AS DATE)"))
            ->get();

        $accomplished = DB::table('CRM_ServiceConnectionInspections')
            ->whereNotNull('CRM_ServiceConnectionInspections.Inspector')
            ->whereRaw("Inspector='" . $inspector . "' AND DateOfVerification IS NOT NULL")
            ->select(
                DB::raw("'Accomplished' AS Type"),
                DB::raw("TRY_CAST(DateOfVerification AS DATE) AS InspectionSchedule"),
                DB::raw("COUNT(id) AS Count"),
            )
            ->groupBy(DB::raw("TRY_CAST(DateOfVerification AS DATE)"))
            ->orderBy(DB::raw("TRY_CAST(DateOfVerification AS DATE)"))
            ->get();

        $arr = array_merge($inspection->toArray(), $reinspection->toArray(), $accomplished->toArray());

        return response()->json($arr, 200);
    }

    public function getInspectionData(Request $request) {
        $type = $request['Type'];
        $inspector = $request['Inspector'];
        $schedule = $request['Schedule'];

        if ($type == 'Inspection') {
            $data = DB::table('CRM_ServiceConnectionInspections')
                ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->whereNotNull('CRM_ServiceConnectionInspections.Inspector')
                ->whereRaw("(Trash IS NULL OR Trash='No')")
                ->whereRaw("Inspector='" . $inspector . "' AND InspectionSchedule='" . $schedule . "' AND ReInspectionSchedule IS NULL")
                ->select(
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.Status',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay'
                )
                ->orderBy('ServiceAccountName')
                ->get();
        } elseif ($type == 'Accomplished') {
            $data = DB::table('CRM_ServiceConnectionInspections')
                ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->whereNotNull('CRM_ServiceConnectionInspections.Inspector')
                ->whereRaw("(Trash IS NULL OR Trash='No')")
                ->whereRaw("Inspector='" . $inspector . "' AND TRY_CAST(DateOfVerification AS DATE)='" . $schedule . "'")
                ->select(
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.Status',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay'
                )
                ->orderBy('ServiceAccountName')
                ->get();
        } else {
            $data = DB::table('CRM_ServiceConnectionInspections')
                ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->whereNotNull('CRM_ServiceConnectionInspections.Inspector')
                ->whereRaw("(Trash IS NULL OR Trash='No')")
                ->whereRaw("Inspector='" . $inspector . "' AND ReInspectionSchedule='" . $schedule . "'")
                ->select(
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.Status',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay'
                )
                ->orderBy('ServiceAccountName')
                ->get();
        }

        $output = "";
        foreach($data as $item) {
            $output .= "<tr>
                            <td><a href='" . route('serviceConnections.show', [$item->id]) . "'>" . $item->id . "</a></td>
                            <td>" . $item->ServiceAccountName . "</td>
                            <td>" . ServiceConnections::getAddress($item) . "</td>
                            <td>" . $item->Status . "</td>
                        </tr>";
        }

        return response()->json($output, 200);
    }

    public function getInspectionSummary(Request $request) {
        $type = $request['Type'];

        if ($type == 'Inspection') {
            $data = DB::table('CRM_ServiceConnectionInspections')
                ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
                ->whereNotNull('CRM_ServiceConnectionInspections.Inspector')
                ->whereRaw("(Trash IS NULL OR Trash='No')")
                ->whereRaw("ReInspectionSchedule IS NULL AND CRM_ServiceConnectionInspections.Status='FOR INSPECTION'")
                ->select(
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.Status',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_ServiceConnections.AccountApplicationType',
                    'CRM_ServiceConnectionInspections.ReInspectionSchedule',
                    'CRM_ServiceConnectionInspections.InspectionSchedule',
                    'users.name',
                )
                ->orderBy('ServiceAccountName')
                ->get();
        } else {
            $data = DB::table('CRM_ServiceConnectionInspections')
                ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
                ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
                ->whereNotNull('CRM_ServiceConnectionInspections.Inspector')
                ->whereRaw("(Trash IS NULL OR Trash='No')")
                ->whereRaw("CRM_ServiceConnectionInspections.Status='Re-Inspection'")
                ->select(
                    'CRM_ServiceConnections.id',
                    'CRM_ServiceConnections.ServiceAccountName',
                    'CRM_ServiceConnections.Sitio',
                    'CRM_ServiceConnections.Status',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay',
                    'CRM_ServiceConnections.DateOfApplication',
                    'CRM_ServiceConnections.AccountApplicationType',
                    'CRM_ServiceConnectionInspections.ReInspectionSchedule',
                    'CRM_ServiceConnectionInspections.InspectionSchedule',
                    'users.name',
                )
                ->orderBy('ServiceAccountName')
                ->get();
        }

        $output = "";
        $i = 1;
        foreach($data as $item) {
            $output .= "<tr>
                            <td>" . $i . "</td>
                            <td><a href='" . route('serviceConnections.show', [$item->id]) . "'>" . $item->id . "</a></td>
                            <td>" . $item->ServiceAccountName . "</td>
                            <td>" . ServiceConnections::getAddress($item) . "</td>
                            <td>" . $item->AccountApplicationType . "</td>
                            <td>" . date('M d, Y', strtotime($item->DateOfApplication)) . "</td>
                            <td>" . $item->name . "</td>
                            <td>" . ($type == 'Inspection' ? date('M d, Y', strtotime($item->InspectionSchedule)) : ($item->ReInspectionSchedule != null ? date('M d, Y', strtotime($item->ReInspectionSchedule)) : 'n/a')) . "</td>
                            <td>" . $item->Status . "</td>
                        </tr>";

            $i++;
        }

        return response()->json($output, 200);
    }

    public function getForReInspection(Request $request) {
        $data = DB::table('CRM_ServiceConnectionInspections')
            ->leftJoin('CRM_ServiceConnections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
            ->whereNotNull('CRM_ServiceConnectionInspections.Inspector')
            ->whereRaw("(Trash IS NULL OR Trash='No')")
            ->whereRaw("ReInspectionSchedule IS NULL AND CRM_ServiceConnectionInspections.Status='Re-Inspection'")
            ->select(
                'CRM_ServiceConnections.id',
                'CRM_ServiceConnectionInspections.id AS InspectionId',
                'CRM_ServiceConnections.ServiceAccountName',
                'CRM_ServiceConnections.Sitio',
                'CRM_ServiceConnections.Status',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
                'CRM_ServiceConnectionInspections.DateOfVerification',
                'CRM_ServiceConnections.AccountApplicationType',
                'CRM_ServiceConnectionInspections.ReInspectionSchedule',
                'CRM_ServiceConnectionInspections.InspectionSchedule',
                'users.name',
            )
            ->orderBy('ServiceAccountName')
            ->get();

        $output = "";
        $i = 1;
        foreach($data as $item) {
            $output .= "<tr id='" . $item->InspectionId . "'>
                            <td>" . $i . "</td>
                            <td><a href='" . route('serviceConnections.show', [$item->id]) . "'>" . $item->id . "</a></td>
                            <td>" . $item->ServiceAccountName . "</td>
                            <td>" . ServiceConnections::getAddress($item) . "</td>
                            <td>" . $item->AccountApplicationType . "</td>
                            <td>" . date('M d, Y', strtotime($item->DateOfVerification)) . "</td>
                            <td>" . $item->Status . "</td>
                            <td>" . $item->name . "</td>
                            <td>
                                <input type='date' class='form-control form-control-sm' id='date-" . $item->InspectionId . "' placeholder='Set Re-Inspection Date'>
                            </td>
                            <td class='text-right'>
                                <button class='btn btn-xs btn-success' onclick='saveSchedule(`" . $item->InspectionId . "`)'>Save</button>
                            </td>
                        </tr>";

            $i++;
        }

        return response()->json($output, 200);
    }

    public function updateReInspectionSchedule(Request $request) {
        $id = $request['id'];
        $sched = $request['Schedule'];

        $inspection = ServiceConnectionInspections::find($id);

        if ($inspection != null) {
            $inspection->ReInspectionSchedule = $sched;
            $inspection->save();
        }

        return response()->json($inspection, 200);
    }

    public function forPayment(Request $request) {
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

        return view('/service_connections/for_payment', [
            'data' => $data,
        ]);
    }

    public function forEnergization(Request $request) {
        $data = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
            ->whereRaw("(Trash IS NULL OR Trash='No')")
            ->whereRaw("CRM_ServiceConnections.Status='Approved for Energization' AND CRM_ServiceConnections.ORNumber IS NOT NULL")
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

        return view('/service_connections/for_energization', [
            'data' => $data,
            'crew' => ServiceConnectionCrew::orderBy('StationName')->get(),
        ]);
    }

    public function setConnectionSchedule(Request $request) {
        $id = $request['id'];
        $schedule = $request['Schedule'];
        $crew = $request['Crew'];

        $serviceConnection = ServiceConnections::find($id);
        if ($serviceConnection != null) {
            $serviceConnection->ConnectionSchedule = $schedule;
            $serviceConnection->StationCrewAssigned = $crew;
            $serviceConnection->save();
        }

        return response()->json($serviceConnection, 200);
    }

    public function getExistingAccounts(Request $request) {
        $search = $request['Params'];

        $serviceAccounts = DB::table('Billing_ServiceAccounts')
                        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                        ->select('Billing_ServiceAccounts.id', 
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.OldAccountNo',   
                            'Billing_ServiceAccounts.AccountCount',  
                            'Billing_ServiceAccounts.AccountStatus',
                            'Billing_ServiceAccounts.ContactNumber',
                            'Billing_ServiceAccounts.AccountType',
                            'Billing_ServiceAccounts.Barangay AS BarangayId',
                            'MeterNumber',
                            'CRM_Towns.Town', 
                            'CRM_Barangays.Barangay')
                        ->whereRaw("Billing_ServiceAccounts.OldAccountNo LIKE '%" . $search . "%' OR Billing_ServiceAccounts.ServiceAccountName LIKE '%" . $search . "%' OR Billing_ServiceAccounts.MeterNumber LIKE '%" . $search . "%'")
                        ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
                        ->get(); 

        $output = "";
        foreach($serviceAccounts as $item) {
            $output .= "<tr id='" . $item->id . "' onclick='selectCustomer(`" . $item->id . "`)' 
                data_id='" . $item->id . "' 
                data_name='" . $item->ServiceAccountName . "'
                data_purok='" . $item->Purok . "'
                data_contact='" . $item->ContactNumber . "'
                data_barangay='" . $item->BarangayId . "'>
                            <td>" . $item->OldAccountNo . "</td>
                            <td>" . $item->ServiceAccountName . "</td>
                            <td>" . ServiceAccounts::getAddress($item) . "</td>
                            <td>" . $item->MeterNumber . "</td>
                            <td>" . $item->AccountType . "</td>
                        </tr>";
        }

        return response()->json($output, 200);
    }

    public function manualEnergization(Request $request) {
        $data = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_ServiceConnectionInspections', 'CRM_ServiceConnectionInspections.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId', '=', 'CRM_ServiceConnections.id')
            ->leftJoin('users', 'CRM_ServiceConnectionInspections.Inspector', '=', 'users.id')
            ->whereRaw("(Trash IS NULL OR Trash='No') AND ConnectionSchedule IS NOT NULL")
            ->whereRaw("CRM_ServiceConnections.Status='Approved for Energization' AND CRM_ServiceConnections.ORNumber IS NOT NULL")
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
                'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber',
                'CRM_ServiceConnectionMeterAndTransformer.id AS MeterId',
            )
            ->orderBy('ConnectionSchedule')
            ->orderBy('ServiceAccountName')
            ->get();

        return view('/service_connections/manual_energization', [
            'data' => $data,
            'crew' => ServiceConnectionCrew::orderBy('StationName')->get(),
        ]);
    }

    public function printPaymentOrder($id) {
        $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('users', 'CRM_ServiceConnections.UserId', '=', 'users.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.AccountCount as AccountCount', 
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication', 
                        'CRM_ServiceConnections.TimeOfApplication', 
                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.AccountOrganization as AccountOrganization', 
                        'CRM_ServiceConnections.ConnectionApplicationType as ConnectionApplicationType',
                        'CRM_ServiceConnections.MemberConsumerId as MemberConsumerId',
                        'CRM_ServiceConnections.Status as Status',  
                        'CRM_ServiceConnections.Notes as Notes', 
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.LongSpan', 
                        'CRM_ServiceConnections.ORNumber as ORNumber',
                        'CRM_ServiceConnections.ORDate', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.LoadCategory as LoadCategory', 
                        'CRM_ServiceConnections.DateTimeOfEnergization as DateTimeOfEnergization', 
                        'CRM_ServiceConnections.DateTimeLinemenArrived as DateTimeLinemenArrived', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnections.AccountType',
                        'CRM_ServiceConnections.ServiceNumber',
                        'CRM_ServiceConnections.ConnectionSchedule',
                        'CRM_ServiceConnections.LoadType',
                        'CRM_ServiceConnections.Zone',
                        'CRM_ServiceConnections.Block',
                        'CRM_ServiceConnections.TransformerID',
                        'CRM_ServiceConnections.LoadInKva',
                        'CRM_ServiceConnections.PoleNumber',
                        'CRM_ServiceConnections.Feeder',
                        'CRM_ServiceConnections.ChargeTo',
                        'CRM_ServiceConnections.AccountNumber',
                        'CRM_ServiceConnections.CertificateOfConnectionIssuedOn',
                        'users.name'
                        )
        ->where('CRM_ServiceConnections.id', $id)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        $paymentOrder = PaymentOrder::where('ServiceConnectionId', $id)->first();

        return view('/service_connections/print_payment_order', [
            'serviceConnection' => $serviceConnections,
            'paymentOrder' => $paymentOrder,
        ]);
    }

    public function uploadFiles($id) {
        $serviceConnections = DB::table('CRM_ServiceConnections')
            ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')
            ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
            ->leftJoin('users', 'CRM_ServiceConnections.UserId', '=', 'users.id')
            ->select('CRM_ServiceConnections.id as id',
                        'CRM_ServiceConnections.AccountCount as AccountCount', 
                        'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                        'CRM_ServiceConnections.DateOfApplication', 
                        'CRM_ServiceConnections.TimeOfApplication', 
                        'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                        'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                        'CRM_ServiceConnections.AccountApplicationType as AccountApplicationType', 
                        'CRM_ServiceConnections.AccountOrganization as AccountOrganization', 
                        'CRM_ServiceConnections.ConnectionApplicationType as ConnectionApplicationType',
                        'CRM_ServiceConnections.MemberConsumerId as MemberConsumerId',
                        'CRM_ServiceConnections.Status as Status',  
                        'CRM_ServiceConnections.Notes as Notes', 
                        'CRM_ServiceConnections.Office', 
                        'CRM_ServiceConnections.LongSpan', 
                        'CRM_ServiceConnections.ORNumber as ORNumber',
                        'CRM_ServiceConnections.ORDate', 
                        'CRM_ServiceConnections.Sitio as Sitio', 
                        'CRM_ServiceConnections.LoadCategory as LoadCategory', 
                        'CRM_ServiceConnections.DateTimeOfEnergization as DateTimeOfEnergization', 
                        'CRM_ServiceConnections.DateTimeLinemenArrived as DateTimeLinemenArrived', 
                        'CRM_Towns.Town as Town',
                        'CRM_Barangays.Barangay as Barangay',
                        'CRM_ServiceConnections.AccountType',
                        'CRM_ServiceConnections.ServiceNumber',
                        'CRM_ServiceConnections.ConnectionSchedule',
                        'CRM_ServiceConnections.LoadType',
                        'CRM_ServiceConnections.Zone',
                        'CRM_ServiceConnections.Block',
                        'CRM_ServiceConnections.TransformerID',
                        'CRM_ServiceConnections.LoadInKva',
                        'CRM_ServiceConnections.PoleNumber',
                        'CRM_ServiceConnections.Feeder',
                        'CRM_ServiceConnections.ChargeTo',
                        'CRM_ServiceConnections.AccountNumber',
                        'CRM_ServiceConnections.CertificateOfConnectionIssuedOn',
                        'users.name'
                        )
        ->where('CRM_ServiceConnections.id', $id)
        ->where(function ($query) {
            $query->where('CRM_ServiceConnections.Trash', 'No')
                ->orWhereNull('CRM_ServiceConnections.Trash');
        })
        ->first(); 

        return view('/service_connections/upload_files', [
            'serviceConnection' => $serviceConnections,
        ]);
    }

    public function saveUploadedFiles(Request $request) {
        $id = $request['id'];
        $files = $_FILES['files'];

        $path = ServiceConnections::filePath() . "$id/";
        File::makeDirectory($path, $mode = 0777, true, true);

        foreach ($_FILES["files"]["name"] as $key => $fileName) {
            $tempFileName = $_FILES["files"]["tmp_name"][$key];
            $targetFileName = $path . basename($fileName);
    
            // Move the uploaded file to the target directory
            if (move_uploaded_file($tempFileName, $targetFileName)) {
                echo "The file " . basename($fileName) . " has been uploaded.<br>";
            } else {
                echo "Sorry, there was an error uploading your file.<br>";
            }
        }

        return redirect(route('serviceConnections.show', [$id]));
    }

    public function turnOnApprovals(Request $request) {
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

        return view('/service_connections/turn_on_approvals', [
            'data' => $data,
        ]);
    }

    public function paymentApprovals(Request $request) {
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

        return view('/service_connections/payment_approvals', [
            'data' => $data,
        ]);
    }

    public function allApplications(Request $request) {
        return view('/service_connections/all_applications', [

        ]);
    }

    public function search(Request $request) {
        $params = $request['params'];

        if ($params == null) {
            $data = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId')
                ->select('CRM_ServiceConnections.id as ConsumerId',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.Status as Status',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.ConnectionApplicationType',
                                'CRM_ServiceConnections.Office',
                                'CRM_ServiceConnections.AccountApplicationType',
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnections.ORNumber',
                                'CRM_ServiceConnections.AccountNumber',
                                'CRM_ServiceConnections.LoadCategory',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber')
                ->whereRaw("(CRM_ServiceConnections.Trash IS NULL OR CRM_ServiceConnections.Trash='No')")
                ->orderByDesc('CRM_ServiceConnections.created_at')
                ->paginate(15);
        } else {
            $data = DB::table('CRM_ServiceConnections')
                ->leftJoin('CRM_Barangays', 'CRM_ServiceConnections.Barangay', '=', 'CRM_Barangays.id')                    
                ->leftJoin('CRM_Towns', 'CRM_ServiceConnections.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_ServiceConnectionMeterAndTransformer', 'CRM_ServiceConnections.id', '=', 'CRM_ServiceConnectionMeterAndTransformer.ServiceConnectionId')
                ->select('CRM_ServiceConnections.id as ConsumerId',
                                'CRM_ServiceConnections.ServiceAccountName as ServiceAccountName',
                                'CRM_ServiceConnections.Status as Status',
                                'CRM_ServiceConnections.DateOfApplication as DateOfApplication', 
                                'CRM_ServiceConnections.ContactNumber as ContactNumber', 
                                'CRM_ServiceConnections.EmailAddress as EmailAddress',  
                                'CRM_ServiceConnections.AccountCount as AccountCount',  
                                'CRM_ServiceConnections.ConnectionApplicationType',
                                'CRM_ServiceConnections.Office',
                                'CRM_ServiceConnections.AccountApplicationType',
                                'CRM_ServiceConnections.Sitio as Sitio', 
                                'CRM_Towns.Town as Town',
                                'CRM_ServiceConnections.ORNumber',
                                'CRM_ServiceConnections.AccountNumber',
                                'CRM_ServiceConnections.LoadCategory',
                                'CRM_Barangays.Barangay as Barangay',
                                'CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber')
                ->whereRaw("(CRM_ServiceConnections.Trash IS NULL OR CRM_ServiceConnections.Trash='No') 
                    AND (ServiceAccountName LIKE '%" . $params . "%' OR CRM_ServiceConnections.id LIKE '%" . $params . "%' OR CRM_ServiceConnectionMeterAndTransformer.MeterSerialNumber LIKE '%" . $params . "%')")
                ->orderBy('CRM_ServiceConnections.created_at')
                ->paginate(15);
        }

        return response()->json($data, 200);
    }
}
