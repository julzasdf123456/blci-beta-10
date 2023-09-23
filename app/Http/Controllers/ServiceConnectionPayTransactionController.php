<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceConnectionPayTransactionRequest;
use App\Http\Requests\UpdateServiceConnectionPayTransactionRequest;
use App\Repositories\ServiceConnectionPayTransactionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\ServiceConnectionMatPayables;
use App\Models\ServiceConnectionPayParticulars;
use App\Models\ServiceConnectionMatPayments;
use App\Models\ServiceConnections;
use App\Models\ServiceConnectionTotalPayments;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class ServiceConnectionPayTransactionController extends AppBaseController
{
    /** @var  ServiceConnectionPayTransactionRepository */
    private $serviceConnectionPayTransactionRepository;

    public function __construct(ServiceConnectionPayTransactionRepository $serviceConnectionPayTransactionRepo)
    {
        $this->middleware('auth');
        $this->serviceConnectionPayTransactionRepository = $serviceConnectionPayTransactionRepo;
    }

    /**
     * Display a listing of the ServiceConnectionPayTransaction.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $serviceConnectionPayTransactions = $this->serviceConnectionPayTransactionRepository->all();

        return view('service_connection_pay_transactions.index')
            ->with('serviceConnectionPayTransactions', $serviceConnectionPayTransactions);
    }

    /**
     * Show the form for creating a new ServiceConnectionPayTransaction.
     *
     * @return Response
     */
    public function create()
    {
        return view('service_connection_pay_transactions.create');
    }

    /**
     * Store a newly created ServiceConnectionPayTransaction in storage.
     *
     * @param CreateServiceConnectionPayTransactionRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceConnectionPayTransactionRequest $request)
    {
        $input = $request->all();

        $serviceConnectionPayTransaction = $this->serviceConnectionPayTransactionRepository->create($input);

        Flash::success('Service Connection Pay Transaction saved successfully.');

        // return redirect(route('serviceConnectionPayTransactions.index'));
        return redirect()->action([ServiceConnectionsController::class, 'show'], [$request['ServiceConnectionId']]);
    }

    /**
     * Display the specified ServiceConnectionPayTransaction.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $serviceConnectionPayTransaction = $this->serviceConnectionPayTransactionRepository->find($id);

        if (empty($serviceConnectionPayTransaction)) {
            Flash::error('Service Connection Pay Transaction not found');

            return redirect(route('serviceConnectionPayTransactions.index'));
        }

        return view('service_connection_pay_transactions.show')->with('serviceConnectionPayTransaction', $serviceConnectionPayTransaction);
    }

    /**
     * Show the form for editing the specified ServiceConnectionPayTransaction.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $serviceConnectionPayTransaction = $this->serviceConnectionPayTransactionRepository->find($id);

        if (empty($serviceConnectionPayTransaction)) {
            Flash::error('Service Connection Pay Transaction not found');

            return redirect(route('serviceConnectionPayTransactions.index'));
        }

        return view('service_connection_pay_transactions.edit')->with('serviceConnectionPayTransaction', $serviceConnectionPayTransaction);
    }

    /**
     * Update the specified ServiceConnectionPayTransaction in storage.
     *
     * @param int $id
     * @param UpdateServiceConnectionPayTransactionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceConnectionPayTransactionRequest $request)
    {
        $serviceConnectionPayTransaction = $this->serviceConnectionPayTransactionRepository->find($id);

        if (empty($serviceConnectionPayTransaction)) {
            Flash::error('Service Connection Pay Transaction not found');

            return redirect(route('serviceConnectionPayTransactions.index'));
        }

        $serviceConnectionPayTransaction = $this->serviceConnectionPayTransactionRepository->update($request->all(), $id);

        Flash::success('Service Connection Pay Transaction updated successfully.');

        return redirect(route('serviceConnectionPayTransactions.index'));
    }

    /**
     * Remove the specified ServiceConnectionPayTransaction from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $serviceConnectionPayTransaction = $this->serviceConnectionPayTransactionRepository->find($id);

        if (empty($serviceConnectionPayTransaction)) {
            // Flash::error('Service Connection Pay Transaction not found');

            // return redirect(route('serviceConnectionPayTransactions.index'));
        }

        $this->serviceConnectionPayTransactionRepository->delete($id);

        // Flash::success('Service Connection Pay Transaction deleted successfully.');

        // return redirect(route('serviceConnectionPayTransactions.index'));
        return json_encode([
            'result' => 'ok',
        ]);
    }

    public function createStepFour($scId) {
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

        $materials = ServiceConnectionMatPayables::where('BuildingType', $serviceConnection->BuildingType)->get();

        $particulars = ServiceConnectionPayParticulars::all();

        $materialPayments = DB::table('CRM_ServiceConnectionMaterialPayments')
                    ->leftJoin('CRM_ServiceConnectionMaterialPayables', 'CRM_ServiceConnectionMaterialPayments.Material', '=', 'CRM_ServiceConnectionMaterialPayables.id')
                    ->select('CRM_ServiceConnectionMaterialPayments.id',
                            'CRM_ServiceConnectionMaterialPayments.Quantity',
                            'CRM_ServiceConnectionMaterialPayments.Vat',
                            'CRM_ServiceConnectionMaterialPayments.Total',
                            'CRM_ServiceConnectionMaterialPayables.Material',
                            'CRM_ServiceConnectionMaterialPayables.Rate',)
                    ->where('CRM_ServiceConnectionMaterialPayments.ServiceConnectionId', $scId)
                    ->get();

        $particularPayments = DB::table('CRM_ServiceConnectionParticularPaymentsTransactions')
                    ->leftJoin('CRM_ServiceConnectionPaymentParticulars', 'CRM_ServiceConnectionParticularPaymentsTransactions.Particular', '=', 'CRM_ServiceConnectionPaymentParticulars.id')
                    ->select('CRM_ServiceConnectionParticularPaymentsTransactions.id',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Amount',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Vat',
                            'CRM_ServiceConnectionParticularPaymentsTransactions.Total',
                            'CRM_ServiceConnectionPaymentParticulars.Particular')
                    ->where('CRM_ServiceConnectionParticularPaymentsTransactions.ServiceConnectionId', $scId)
                    ->get();

        $totalPayments = ServiceConnectionTotalPayments::where('ServiceConnectionId', $scId)->first();

        return view('service_connection_pay_transactions\create_step_four', ['serviceConnection' => $serviceConnection, 
                                                                            'materials' => $materials, 
                                                                            'particulars' => $particulars,
                                                                            'materialPayments' => $materialPayments,
                                                                            'particularPayments' => $particularPayments,
                                                                            'totalPayments' => $totalPayments]);
    }
}
