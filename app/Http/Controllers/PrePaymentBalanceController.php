<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePrePaymentBalanceRequest;
use App\Http\Requests\UpdatePrePaymentBalanceRequest;
use App\Repositories\PrePaymentBalanceRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\ServiceAccounts;
use App\Models\PrePaymentBalance;
use App\Models\PrePaymentTransHistory;
use App\Models\IDGenerator;
use App\Models\ORAssigning;
use App\Models\TransactionDetails;
use App\Models\TransactionIndex;
use Illuminate\Support\Facades\DB;   
use Illuminate\Support\Facades\Auth; 
use Flash;
use Response;

class PrePaymentBalanceController extends AppBaseController
{
    /** @var  PrePaymentBalanceRepository */
    private $prePaymentBalanceRepository;

    public function __construct(PrePaymentBalanceRepository $prePaymentBalanceRepo)
    {
        $this->middleware('auth');
        $this->prePaymentBalanceRepository = $prePaymentBalanceRepo;
    }

    /**
     * Display a listing of the PrePaymentBalance.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $prePaymentBalances = $this->prePaymentBalanceRepository->all();

        return view('pre_payment_balances.index')
            ->with('prePaymentBalances', $prePaymentBalances);
    }

    /**
     * Show the form for creating a new PrePaymentBalance.
     *
     * @return Response
     */
    public function create()
    {
        return view('pre_payment_balances.create');
    }

    /**
     * Store a newly created PrePaymentBalance in storage.
     *
     * @param CreatePrePaymentBalanceRequest $request
     *
     * @return Response
     */
    public function store(CreatePrePaymentBalanceRequest $request)
    {
        $input = $request->all();
        $input['id'] = IDGenerator::generateIDandRandString();

        $prePaymentBalance = PrePaymentBalance::where('AccountNumber', $input['AccountNumber'])->first();

        if ($prePaymentBalance != null) {
            $oldAmount = floatval($prePaymentBalance->Balance);
            $newAmount = floatval($input['Balance']) + $oldAmount;

            $prePaymentBalance->Balance = $newAmount;
            $prePaymentBalance->save();
        } else {
            $prePaymentBalance = $this->prePaymentBalanceRepository->create($input);
        }

        // ADD TRANSACTION HISTORY
        $transHistory = new PrePaymentTransHistory;
        $transHistory->id = IDGenerator::generateIDandRandString();
        $transHistory->AccountNumber = $input['AccountNumber'];
        $transHistory->Method = 'DEPOSIT';
        $transHistory->Amount = $input['Balance'];
        $transHistory->UserId = Auth::id(); 
        $transHistory->Notes = $input['Remarks'];
        $transHistory->ORNumber = $input['ORNumber'];
        $transHistory->save();

        // SAVE OR
        $saveOR = ORAssigning::where('ORNumber',  $input['ORNumber'])
            ->where('UserId', Auth::id())
            ->first();        
        if ($saveOR == null) {
            $saveOR = new ORAssigning;
            $saveOR->id = IDGenerator::generateIDandRandString();
            $saveOR->ORNumber = $input['ORNumber'];
            $saveOR->UserId = Auth::id();
            $saveOR->DateAssigned = date('Y-m-d');
            $saveOR->TimeAssigned = date('H:i:s');
            $saveOR->Office = env('APP_LOCATION');
            $saveOR->save();
        }     

        // SAVE TRANSACTION
        $id = IDGenerator::generateID();

        $transactionIndex = new TransactionIndex;
        $transactionIndex->id = $id;
        $transactionIndex->TransactionNumber = env('APP_LOCATION') . '-' . $id;
        $transactionIndex->PaymentTitle = "Pre-payment Deposit for Account No. " . $input['AccountNumber'];
        $transactionIndex->ORNumber = $input['ORNumber'];
        $transactionIndex->ORDate = date('Y-m-d');
        $transactionIndex->Total = $input['Balance'];
        $transactionIndex->Source = 'Pre-Payment Deposits';
        $transactionIndex->ObjectId = $transHistory->id;
        $transactionIndex->PaymentUsed = 'Cash';
        $transactionIndex->UserId = Auth::id();
        $transactionIndex->save();

        // SAVE TRANSACTION DETAILS
        $transactionDetails = new TransactionDetails;
        $transactionDetails->id = IDGenerator::generateIDandRandString();
        $transactionDetails->TransactionIndexId = $id;
        $transactionDetails->Particular = 'Pre-Payment Deposit';
        $transactionDetails->Amount = $input['Balance'];
        $transactionDetails->Total = $input['Balance'];
        $transactionDetails->save();

        return response()->json($transactionIndex, 200);
    }

    /**
     * Display the specified PrePaymentBalance.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $prePaymentBalance = $this->prePaymentBalanceRepository->find($id);

        if (empty($prePaymentBalance)) {
            Flash::error('Pre Payment Balance not found');

            return redirect(route('prePaymentBalances.index'));
        }

        return view('pre_payment_balances.show')->with('prePaymentBalance', $prePaymentBalance);
    }

    /**
     * Show the form for editing the specified PrePaymentBalance.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $prePaymentBalance = $this->prePaymentBalanceRepository->find($id);

        if (empty($prePaymentBalance)) {
            Flash::error('Pre Payment Balance not found');

            return redirect(route('prePaymentBalances.index'));
        }

        return view('pre_payment_balances.edit')->with('prePaymentBalance', $prePaymentBalance);
    }

    /**
     * Update the specified PrePaymentBalance in storage.
     *
     * @param int $id
     * @param UpdatePrePaymentBalanceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePrePaymentBalanceRequest $request)
    {
        $prePaymentBalance = $this->prePaymentBalanceRepository->find($id);

        if (empty($prePaymentBalance)) {
            Flash::error('Pre Payment Balance not found');

            return redirect(route('prePaymentBalances.index'));
        }

        $prePaymentBalance = $this->prePaymentBalanceRepository->update($request->all(), $id);

        Flash::success('Pre Payment Balance updated successfully.');

        return redirect(route('prePaymentBalances.index'));
    }

    /**
     * Remove the specified PrePaymentBalance from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $prePaymentBalance = $this->prePaymentBalanceRepository->find($id);

        if (empty($prePaymentBalance)) {
            Flash::error('Pre Payment Balance not found');

            return redirect(route('prePaymentBalances.index'));
        }

        $this->prePaymentBalanceRepository->delete($id);

        Flash::success('Pre Payment Balance deleted successfully.');

        return redirect(route('prePaymentBalances.index'));
    }

    public function search(Request $request) {
        $results = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->where('Billing_ServiceAccounts.ServiceAccountName', 'LIKE', '%' . $request['query'] . '%')
            ->orWhere('Billing_ServiceAccounts.id', 'LIKE', '%' . $request['query'] . '%')
            ->orWhere('Billing_ServiceAccounts.OldAccountNo', 'LIKE', '%' . $request['query'] . '%')
            ->select('Billing_ServiceAccounts.id',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.AccountCount',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.AccountType',
                    'Billing_ServiceAccounts.AccountStatus',
                    'Billing_ServiceAccounts.AreaCode',
                    'Billing_ServiceAccounts.SequenceCode',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
            ->orderBy('Billing_ServiceAccounts.ServiceAccountName')
            ->get();

        $output = "";

        if (count($results) > 0) {
            foreach($results as $item) {
                $output .= '
                        <tr onclick=fetchDetails("' . $item->id . '")>
                            <td>' . $item->id . '</td>
                            <td>' . $item->ServiceAccountName . '</td>
                            <td>' . ServiceAccounts::getAddress($item) . '</td>
                            <td>' . $item->AccountStatus . '</td>
                            <td>
                                <button class="btn btn-link text-primary" onclick=fetchDetails("' . $item->id . '")><i class="fas fa-forward"></i></button>
                            </td>
                        </tr>
                    '; 
            }

            return response()->json($output, 200);
        } else {
            return response()->json([], 200);
        }        
    }

    public function getBalanceDetails(Request $request) {
        $accountNo = $request['AccountNumber'];

        $details = DB::table('Billing_ServiceAccounts')
            ->leftJoin('Billing_PrePaymentBalance', 'Billing_ServiceAccounts.id', '=', 'Billing_PrePaymentBalance.AccountNumber')
            ->select('Billing_PrePaymentBalance.id',
                'Billing_ServiceAccounts.id as AccountNumber',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_PrePaymentBalance.Balance')
            ->where('Billing_ServiceAccounts.id', $accountNo)
            ->first();

        return response()->json($details, 200);
    }
}
