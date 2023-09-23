<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionInspectionsRequest;
use App\Http\Requests\UpdateServiceConnectionInspectionsRequest;
use App\Repositories\ServiceConnectionInspectionsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\ServiceConnections;
use App\Http\Controllers\ServiceConnectionsController;
use App\Models\User;
use App\Models\ServiceConnectionTimeframes;
use App\Models\ServiceConnectionPayParticulars;
use App\Models\ServiceConnectionTotalPayments;
use App\Models\ServiceConnectionPayTransaction;
use App\Models\ServiceConnectionInspections;
use App\Models\IDGenerator;
use App\Models\Signatories;
use Illuminate\Support\Facades\Auth;
use Flash;
use Response;

class ServiceConnectionInspectionsController extends AppBaseController
{
    /** @var  ServiceConnectionInspectionsRepository */
    private $serviceConnectionInspectionsRepository;

    public function __construct(ServiceConnectionInspectionsRepository $serviceConnectionInspectionsRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionInspectionsRepository = $serviceConnectionInspectionsRepo;
    }

    /**
     * Display a listing of the ServiceConnectionInspections.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->all();

        return view('service_connection_inspections.index')
            ->with('serviceConnectionInspections', $serviceConnectionInspections);
    }

    /**
     * Show the form for creating a new ServiceConnectionInspections.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_connection_inspections.create');
    }

    /**
     * Store a newly created ServiceConnectionInspections in storage.
     *
     * @param CreateServiceConnectionInspectionsRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionInspectionsRequest $request)
    {
        $input = $request->all();

        if ($input['id'] != null) {
            $sc = ServiceConnectionInspections::find($input['id']);

            if ($sc != null) {
                $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->find($sc->id);

                if (empty($serviceConnectionInspections)) {
                    Flash::error('Service Connection Inspections not found');

                    return redirect(route('serviceConnectionInspections.index'));
                }

                $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->update($request->all(), $sc->id);

                $serviceConnection = ServiceConnections::find($input['ServiceConnectionId']);

                if ($serviceConnection->LoadCategory == 'below 5kVa') {
                    if ($serviceConnection->LongSpan == 'Yes') {
                        $serviceConnection->Status = 'Forwarded To Planning';

                        // CREATE Timeframes
                        $timeFrame = new ServiceConnectionTimeframes;
                        $timeFrame->id = IDGenerator::generateID();
                        $timeFrame->ServiceConnectionId = $input['ServiceConnectionId'];
                        $timeFrame->UserId = Auth::id();
                        $timeFrame->Status = 'Forwarded To Planning';
                        $timeFrame->Notes = 'For assigning of BoM and Staking.';
                        $timeFrame->save();
                    } else {
                        $serviceConnection->Status = 'For Inspection';

                        // CREATE Timeframes
                        $timeFrame = new ServiceConnectionTimeframes;
                        $timeFrame->id = IDGenerator::generateID();
                        $timeFrame->ServiceConnectionId = $input['ServiceConnectionId'];
                        $timeFrame->UserId = Auth::id();
                        $timeFrame->Status = 'For Inspection';
                        $timeFrame->Notes = 'Tickets for staking and inspection created!';
                        $timeFrame->save();
                    }            
                } else {
                    $serviceConnection->Status = 'Forwarded To Planning';

                    // CREATE Timeframes
                    $timeFrame = new ServiceConnectionTimeframes;
                    $timeFrame->id = IDGenerator::generateID();
                    $timeFrame->ServiceConnectionId = $input['ServiceConnectionId'];
                    $timeFrame->UserId = Auth::id();
                    $timeFrame->Status = 'Forwarded To Planning';
                    $timeFrame->Notes = 'For assigning of BoM and Staking.';
                    $timeFrame->save();
                }
                $serviceConnection->save();

                // CREATE PAYMENT TRANSACTIONS
                $paymentParticulars = ServiceConnectionPayParticulars::all();
                $subTotal = 0.0;
                $vatTotal = 0.0;
                $overAllTotal = 0.0;
                // foreach($paymentParticulars as $item) {
                //     $transactions = new ServiceConnectionPayTransaction;
                //     $transactions->id = IDGenerator::generateIDandRandString();
                //     $transactions->ServiceConnectionId = $input['ServiceConnectionId'];
                //     $transactions->Particular = $item->id;
                //     $transactions->Amount = $item->DefaultAmount;
                //     $transactions->Vat = floatval($item->DefaultAmount) * floatval($item->VatPercentage);
                //     $transactions->Total = floatval($transactions->Vat) + floatval($transactions->Amount);
                //     $transactions->save();

                //     $subTotal = $subTotal + floatval($transactions->Amount);
                //     $vatTotal = $vatTotal + floatval($transactions->Vat);
                //     $overAllTotal = $overAllTotal + floatval($transactions->Total);
                // }
                $totalTransactions = new ServiceConnectionTotalPayments;
                $totalTransactions->id = IDGenerator::generateIDandRandString();
                $totalTransactions->ServiceConnectionId = $input['ServiceConnectionId'];
                $totalTransactions->SubTotal = $subTotal;
                $totalTransactions->TotalVat = $vatTotal;
                $totalTransactions->Total = $overAllTotal;
                $totalTransactions->save();

                // return redirect()->action([ServiceConnectionsController::class, 'show'], [$input['ServiceConnectionId']]);
                // return redirect()->action([App\Http\Controllers\ServiceConnectionMtrTrnsfrmrController::class, 'createStepThree'], [$input['ServiceConnectionId']]);
                // return redirect(route('serviceConnectionMtrTrnsfrmrs.create-step-three', [$input['ServiceConnectionId']]));
                return redirect()->action([ServiceConnectionsController::class, 'show'], [$input['ServiceConnectionId']]); 
            } else {
                $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->create($input);

                Flash::success('Service Connection Inspections saved successfully.');

                $serviceConnection = ServiceConnections::find($input['ServiceConnectionId']);

                if ($serviceConnection->LoadCategory == 'below 5kVa') {
                    if ($serviceConnection->LongSpan == 'Yes') {
                        $serviceConnection->Status = 'Forwarded To Planning';

                        // CREATE Timeframes
                        $timeFrame = new ServiceConnectionTimeframes;
                        $timeFrame->id = IDGenerator::generateID();
                        $timeFrame->ServiceConnectionId = $input['ServiceConnectionId'];
                        $timeFrame->UserId = Auth::id();
                        $timeFrame->Status = 'Forwarded To Planning';
                        $timeFrame->Notes = 'For assigning of BoM and Staking.';
                        $timeFrame->save();
                    } else {
                        $serviceConnection->Status = 'For Inspection';

                        // CREATE Timeframes
                        $timeFrame = new ServiceConnectionTimeframes;
                        $timeFrame->id = IDGenerator::generateID();
                        $timeFrame->ServiceConnectionId = $input['ServiceConnectionId'];
                        $timeFrame->UserId = Auth::id();
                        $timeFrame->Status = 'For Inspection';
                        $timeFrame->Notes = 'Tickets for staking and inspection created!';
                        $timeFrame->save();
                    }            
                } else {
                    $serviceConnection->Status = 'Forwarded To Planning';

                    // CREATE Timeframes
                    $timeFrame = new ServiceConnectionTimeframes;
                    $timeFrame->id = IDGenerator::generateID();
                    $timeFrame->ServiceConnectionId = $input['ServiceConnectionId'];
                    $timeFrame->UserId = Auth::id();
                    $timeFrame->Status = 'Forwarded To Planning';
                    $timeFrame->Notes = 'For assigning of BoM and Staking.';
                    $timeFrame->save();
                }
                $serviceConnection->save();

                // CREATE PAYMENT TRANSACTIONS
                $paymentParticulars = ServiceConnectionPayParticulars::all();
                $subTotal = 0.0;
                $vatTotal = 0.0;
                $overAllTotal = 0.0;
                // foreach($paymentParticulars as $item) {
                //     $transactions = new ServiceConnectionPayTransaction;
                //     $transactions->id = IDGenerator::generateIDandRandString();
                //     $transactions->ServiceConnectionId = $input['ServiceConnectionId'];
                //     $transactions->Particular = $item->id;
                //     $transactions->Amount = $item->DefaultAmount;
                //     $transactions->Vat = floatval($item->DefaultAmount) * floatval($item->VatPercentage);
                //     $transactions->Total = floatval($transactions->Vat) + floatval($transactions->Amount);
                //     $transactions->save();

                //     $subTotal = $subTotal + floatval($transactions->Amount);
                //     $vatTotal = $vatTotal + floatval($transactions->Vat);
                //     $overAllTotal = $overAllTotal + floatval($transactions->Total);
                // }
                $totalTransactions = new ServiceConnectionTotalPayments;
                $totalTransactions->id = IDGenerator::generateIDandRandString();
                $totalTransactions->ServiceConnectionId = $input['ServiceConnectionId'];
                $totalTransactions->SubTotal = $subTotal;
                $totalTransactions->TotalVat = $vatTotal;
                $totalTransactions->Total = $overAllTotal;
                $totalTransactions->save();

                // return redirect()->action([ServiceConnectionsController::class, 'show'], [$input['ServiceConnectionId']]);
                // return redirect()->action([App\Http\Controllers\ServiceConnectionMtrTrnsfrmrController::class, 'createStepThree'], [$input['ServiceConnectionId']]);
                // return redirect(route('serviceConnectionMtrTrnsfrmrs.create-step-three', [$input['ServiceConnectionId']]));
                return redirect()->action([ServiceConnectionsController::class, 'show'], [$input['ServiceConnectionId']]); 
            }
        } else {
            return abort('ID Not found!', 404);
        }

        
    }

    /**
     * Display the specified ServiceConnectionInspections.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->find($id);

        if (empty($serviceConnectionInspections)) {
            Flash::error('Service Connection Inspections not found');

            return redirect(route('serviceConnectionInspections.index'));
        }

        return view('service_connection_inspections.show')->with('serviceConnectionInspections', $serviceConnectionInspections);
    }

    /**
     * Show the form for editing the specified ServiceConnectionInspections.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->find($id);

        if (env('APP_AREA_CODE') == 15) {
            $inspectors = Signatories::where('Notes', 'INSPECTOR')
                ->get();
        } else {
            $inspectors = Signatories::where('Notes', 'INSPECTOR')
                ->where('Office', env('APP_AREA_CODE'))
                ->get();
        }
        

        if (empty($serviceConnectionInspections)) {
            Flash::error('Service Connection Inspections not found');

            return redirect(route('serviceConnectionInspections.index'));
        }

        return view('service_connection_inspections.edit', [
            'serviceConnectionInspections' => $serviceConnectionInspections, 
            'inspectors' => $inspectors
        ]);
    }

    /**
     * Update the specified ServiceConnectionInspections in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionInspectionsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionInspectionsRequest $request)
    {
        $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->find($id);

        if (empty($serviceConnectionInspections)) {
            Flash::error('Service Connection Inspections not found');

            return redirect(route('serviceConnectionInspections.index'));
        }

        $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->update($request->all(), $id);

        Flash::success('Service Connection Inspections updated successfully.');

        // return redirect(route('serviceConnectionInspections.index'));
        return redirect()->action([ServiceConnectionsController::class, 'show'], [$request['ServiceConnectionId']]);
    }

    /**
     * Remove the specified ServiceConnectionInspections from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionInspections = $this->serviceConnectionInspectionsRepository->find($id);

        if (empty($serviceConnectionInspections)) {
            Flash::error('Service Connection Inspections not found');

            return redirect(route('serviceConnectionInspections.index'));
        }

        $this->serviceConnectionInspectionsRepository->delete($id);

        Flash::success('Service Connection Inspections deleted successfully.');

        return redirect(route('serviceConnectionInspections.index'));
    }

    public function createStepTwo($scId) {
        $serviceConnection = ServiceConnections::find($scId);

        if (env('APP_AREA_CODE') == '15') {
            $inspectors = Signatories::where('Notes', 'INSPECTOR')
                ->get();
        } else {    
            $inspectors = Signatories::where('Notes', 'INSPECTOR')
                ->where('Office', env('APP_AREA_CODE'))
                ->get();
        }
        // $inspectors = User::where('id', env('AREA_INSPECTOR_ID'))->pluck('name', 'id'); // CHANGE PERMISSION TO WHATEVER VERIFIER NAME IS

        $serviceConnectionInspections = null;

        return view('/service_connection_inspections/create_step_two', [
            'serviceConnection' => $serviceConnection, 
            'inspectors' => $inspectors, 
            'serviceConnectionInspections' => $serviceConnectionInspections
        ]);
    }
}
