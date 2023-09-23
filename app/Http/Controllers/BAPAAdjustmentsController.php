<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBAPAAdjustmentsRequest;
use App\Http\Requests\UpdateBAPAAdjustmentsRequest;
use App\Repositories\BAPAAdjustmentsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Towns;
use App\Models\BAPAAdjustments;
use App\Models\BAPAAdjustmentDetails;
use App\Models\IDGenerator;
use App\Models\Bills;
use App\Models\Rates;
use App\Models\ServiceAccounts;
use Illuminate\Support\Facades\DB;   
use Illuminate\Support\Facades\Auth; 
use Flash;
use Response;

class BAPAAdjustmentsController extends AppBaseController
{
    /** @var  BAPAAdjustmentsRepository */
    private $bAPAAdjustmentsRepository;

    public function __construct(BAPAAdjustmentsRepository $bAPAAdjustmentsRepo)
    {
        $this->middleware('auth');
        $this->bAPAAdjustmentsRepository = $bAPAAdjustmentsRepo;
    }

    /**
     * Display a listing of the BAPAAdjustments.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $towns = Towns::orderBy('id')->get();

        return view('b_a_p_a_adjustments.index',[
            'towns' => $towns,
        ]);
    }

    /**
     * Show the form for creating a new BAPAAdjustments.
     *
     * @return Response
     */
    public function create()
    {
        return view('b_a_p_a_adjustments.create');
    }

    /**
     * Store a newly created BAPAAdjustments in storage.
     *
     * @param CreateBAPAAdjustmentsRequest $request
     *
     * @return Response
     */
    public function store(CreateBAPAAdjustmentsRequest $request)
    {
        $input = $request->all();

        $bAPAAdjustments = $this->bAPAAdjustmentsRepository->create($input);

        Flash::success('B A P A Adjustments saved successfully.');

        return redirect(route('bAPAAdjustments.index'));
    }

    /**
     * Display the specified BAPAAdjustments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $bAPAAdjustments = $this->bAPAAdjustmentsRepository->find($id);

        if (empty($bAPAAdjustments)) {
            Flash::error('B A P A Adjustments not found');

            return redirect(route('bAPAAdjustments.index'));
        }

        return view('b_a_p_a_adjustments.show')->with('bAPAAdjustments', $bAPAAdjustments);
    }

    /**
     * Show the form for editing the specified BAPAAdjustments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $bAPAAdjustments = $this->bAPAAdjustmentsRepository->find($id);

        if (empty($bAPAAdjustments)) {
            Flash::error('B A P A Adjustments not found');

            return redirect(route('bAPAAdjustments.index'));
        }

        return view('b_a_p_a_adjustments.edit')->with('bAPAAdjustments', $bAPAAdjustments);
    }

    /**
     * Update the specified BAPAAdjustments in storage.
     *
     * @param int $id
     * @param UpdateBAPAAdjustmentsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBAPAAdjustmentsRequest $request)
    {
        $bAPAAdjustments = $this->bAPAAdjustmentsRepository->find($id);

        if (empty($bAPAAdjustments)) {
            Flash::error('B A P A Adjustments not found');

            return redirect(route('bAPAAdjustments.index'));
        }

        $bAPAAdjustments = $this->bAPAAdjustmentsRepository->update($request->all(), $id);

        Flash::success('B A P A Adjustments updated successfully.');

        return redirect(route('bAPAAdjustments.index'));
    }

    /**
     * Remove the specified BAPAAdjustments from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $bAPAAdjustments = $this->bAPAAdjustmentsRepository->find($id);

        if (empty($bAPAAdjustments)) {
            Flash::error('B A P A Adjustments not found');

            return redirect(route('bAPAAdjustments.index'));
        }

        $this->bAPAAdjustmentsRepository->delete($id);

        Flash::success('B A P A Adjustments deleted successfully.');

        return redirect(route('bAPAAdjustments.index'));
    }

    public function searchBapa(Request $request) {
        $param = $request['BAPA'];
        $town = $request['Town'];

        if ($town == 'All') {
            $bapas = DB::table('Billing_ServiceAccounts AS sa')
            ->where('sa.OrganizationParentAccount', 'LIKE', '%' . $param . '%')
            ->select('sa.OrganizationParentAccount', 
                'sa.Town',
                DB::raw("COUNT(sa.id) AS NoOfAccounts"),
                DB::raw("(SELECT SUBSTRING((SELECT ',' + AreaCode AS 'data()' FROM Billing_ServiceAccounts WHERE OrganizationParentAccount=sa.OrganizationParentAccount GROUP BY AreaCode FOR XML PATH('')), 2 , 9999)) As Result"))
            ->groupBy('sa.OrganizationParentAccount', 
                'sa.Town')
            ->orderBy('sa.OrganizationParentAccount')
            ->get();
        } else {
            $bapas = DB::table('Billing_ServiceAccounts AS sa')
            ->where('sa.OrganizationParentAccount', 'LIKE', '%' . $param . '%')
            ->where('sa.Town', $town)
            ->select('sa.OrganizationParentAccount', 
                'sa.Town',
                DB::raw("COUNT(sa.id) AS NoOfAccounts"),
                DB::raw("(SELECT SUBSTRING((SELECT ',' + AreaCode AS 'data()' FROM Billing_ServiceAccounts WHERE OrganizationParentAccount=sa.OrganizationParentAccount GROUP BY AreaCode FOR XML PATH('')), 2 , 9999)) As Result"))
            ->groupBy('sa.OrganizationParentAccount', 
                'sa.Town')
            ->orderBy('sa.OrganizationParentAccount')
            ->get();
        }

        $output = "";
        foreach($bapas as $item) {
            if (strlen($item->OrganizationParentAccount) > 1) {
                $output .= '<tr>
                                <td><a href="' . route('bAPAAdjustments.adjust-bapa', [urlencode($item->OrganizationParentAccount)]) . '">' . $item->OrganizationParentAccount . '</a></td>
                                <td>' . $item->Town . '</td>
                                <td>' . number_format($item->NoOfAccounts) . '</td>
                                <td>' . $item->Result . '</td>
                            </tr>';
            }            
        }

        return response()->json($output, 200);
    }

    public function adjustBapaPayments($bapaName) {
        $bapaName = urldecode($bapaName);
        $rate = Rates::orderByDesc("ServicePeriod")
            ->first();

        return view('/b_a_p_a_adjustments/adjust_payment', [
            'bapaName' => $bapaName,
            'rate' => $rate,
        ]);
    }

    public function saveBapaAdjustments(Request $request) {
        $bapaName = urldecode($request['BAPAName']);
        $percentage = floatval($request['DiscountPercentage']);
        $period = $request['Period'];
        // FETCH FIRST IF EXISTS IN BAPA ADJUSTMENTS
        $bAPAAdjustments = BAPAAdjustments::where('ServicePeriod', $request['Period'])
            ->where('BAPAName', $bapaName)
            ->first();
        
        if ($bAPAAdjustments != null) {
            // UPDATE
            $bAPAAdjustments->BAPAName = $bapaName;
            $bAPAAdjustments->ServicePeriod = $request['Period'];
            $bAPAAdjustments->DiscountPercentage = $request['DiscountPercentage'];
            $bAPAAdjustments->DiscountAmount = round($request['DiscountAmount'], 2);
            $bAPAAdjustments->NumberOfConsumers = $request['NoOfConsumers'];
            $bAPAAdjustments->SubTotal = round($request['SubTotal'], 2);
            $bAPAAdjustments->NetAmount = round($request['NetAmount'], 2);
            $bAPAAdjustments->UserId = Auth::id();
            $bAPAAdjustments->save();
        } else {
            // SAVE NEW
            $bAPAAdjustments = new BAPAAdjustments;
            $bAPAAdjustments->id = IDGenerator::generateIDandRandString();
            $bAPAAdjustments->BAPAName = $bapaName;
            $bAPAAdjustments->ServicePeriod = $request['Period'];
            $bAPAAdjustments->DiscountPercentage = $request['DiscountPercentage'];
            $bAPAAdjustments->DiscountAmount = round($request['DiscountAmount'], 2);
            $bAPAAdjustments->NumberOfConsumers = $request['NoOfConsumers'];
            $bAPAAdjustments->SubTotal = round($request['SubTotal'], 2);
            $bAPAAdjustments->NetAmount = round($request['NetAmount'], 2);
            $bAPAAdjustments->UserId = Auth::id();
            $bAPAAdjustments->save();
        }

        // SAVE EVERY BILL
        $billNumbers = $request['BillNumbers'];
        $len = count($billNumbers);

        for($i=0; $i<$len; $i++) {
            $bill = Bills::find($billNumbers[$i]);

            if ($bill != null) {
                // CHECK IF EXISTS IN ADJUSTMENT DETAILS
                $adjusted = BAPAAdjustmentDetails::where('BillId', $bill->id)->first();
                $discountAmnt = round(floatval($bill->NetAmount) * $percentage, 2);

                if ($adjusted != null) {
                    // UPDATE
                    $adjusted->AccountNumber = $bill->AccountNumber;
                    $adjusted->BillId = $bill->id;
                    $adjusted->DiscountPercentage = $percentage;
                    $adjusted->DiscountAmount = $discountAmnt;
                    $adjusted->BAPAName = $bapaName;
                    $adjusted->ServicePeriod = $period;
                    $adjusted->save();
                } else {
                    // SAVE NEW
                    $adjusted = new BAPAAdjustmentDetails;
                    $adjusted->id = IDGenerator::generateIDandRandString();
                    $adjusted->AccountNumber = $bill->AccountNumber;
                    $adjusted->BillId = $bill->id;
                    $adjusted->DiscountPercentage = $percentage;
                    $adjusted->DiscountAmount = $discountAmnt;
                    $adjusted->BAPAName = $bapaName;
                    $adjusted->ServicePeriod = $period;
                    $adjusted->save();
                }
            } 
        }

        return response()->json('ok', 200);
    }

    public function searchBapaMonitor() {
        $towns = Towns::all();

        return view('/b_a_p_a_adjustments/search_bapa_monitor', [
            'towns' => $towns,
        ]);
    }

    public function getBapaMonitorSearchResults(Request $request) {
        $param = $request['BAPA'];
        $town = $request['Town'];

        if ($town == 'All') {
            $bapas = DB::table('Billing_ServiceAccounts AS sa')
            ->where('sa.OrganizationParentAccount', 'LIKE', '%' . $param . '%')
            ->select('sa.OrganizationParentAccount', 
                'sa.Town',
                DB::raw("COUNT(sa.id) AS NoOfAccounts"),
                DB::raw("(SELECT SUBSTRING((SELECT ',' + AreaCode AS 'data()' FROM Billing_ServiceAccounts WHERE OrganizationParentAccount=sa.OrganizationParentAccount GROUP BY AreaCode FOR XML PATH('')), 2 , 9999)) As Result"))
            ->groupBy('sa.OrganizationParentAccount', 
                'sa.Town')
            ->orderBy('sa.OrganizationParentAccount')
            ->get();
        } else {
            $bapas = DB::table('Billing_ServiceAccounts AS sa')
            ->where('sa.OrganizationParentAccount', 'LIKE', '%' . $param . '%')
            ->where('sa.Town', $town)
            ->select('sa.OrganizationParentAccount', 
                'sa.Town',
                DB::raw("COUNT(sa.id) AS NoOfAccounts"),
                DB::raw("(SELECT SUBSTRING((SELECT ',' + AreaCode AS 'data()' FROM Billing_ServiceAccounts WHERE OrganizationParentAccount=sa.OrganizationParentAccount GROUP BY AreaCode FOR XML PATH('')), 2 , 9999)) As Result"))
            ->groupBy('sa.OrganizationParentAccount', 
                'sa.Town')
            ->orderBy('sa.OrganizationParentAccount')
            ->get();
        }

        $output = "";
        foreach($bapas as $item) {
            if (strlen($item->OrganizationParentAccount) > 1) {
                $output .= '<tr>
                                <td><a href="' . route('bAPAAdjustments.bapa-collection-monitor-console', [urlencode($item->OrganizationParentAccount)]) . '">' . $item->OrganizationParentAccount . '</a></td>
                                <td>' . $item->Town . '</td>
                                <td>' . number_format($item->NoOfAccounts) . '</td>
                                <td>' . $item->Result . '</td>
                            </tr>';
            }            
        }

        return response()->json($output, 200);
    }

    public function bapaCollectionMonitorConsole($bapaName, Request $request) {
        $bapaName = urldecode($bapaName);
        $rate = Rates::orderByDesc('ServicePeriod')
            ->first();

        $routes = ServiceAccounts::where('OrganizationParentAccount', $bapaName)
            ->select('AreaCode', 
                DB::raw("COUNT(id) AS NoOfConsumers"))
            ->groupBy('AreaCode')
            ->get();

        $period = $request['Period'];

        if ($period != null) {
            $bapaData = DB::table('Billing_ServiceAccounts')
                ->where('OrganizationParentAccount', $bapaName)
                ->select('*',
                    DB::raw("(SELECT TOP 1 KwhUsed FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS KwhUsed"),
                    DB::raw("(SELECT TOP 1 NetAmount FROM Billing_Bills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS NetAmount"),
                    DB::raw("(SELECT TOP 1 id FROM Cashier_BAPAAdjustmentDetails WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS DiscId"),
                    DB::raw("(SELECT TOP 1 DiscountAmount FROM Cashier_BAPAAdjustmentDetails WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS DiscountAmount"),
                    DB::raw("(SELECT TOP 1 DiscountPercentage FROM Cashier_BAPAAdjustmentDetails WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS DiscountPercentage"),
                    DB::raw("(SELECT TOP 1 ORNumber FROM Cashier_PaidBills WHERE AccountNumber=Billing_ServiceAccounts.id AND ServicePeriod='" . $period . "') AS ORNumber"),
                )
                ->orderBy('OldAccountNo')
                ->get();

            $bapaAdjustmentData = DB::table('Cashier_BAPAAdjustmentDetails')
                ->leftJoin('Billing_Bills', 'Cashier_BAPAAdjustmentDetails.BillId', '=', 'Billing_Bills.id')
                ->where('BAPAName', $bapaName)
                ->where("Cashier_BAPAAdjustmentDetails.ServicePeriod", $period)
                ->select('Cashier_BAPAAdjustmentDetails.DiscountPercentage',
                    'Cashier_BAPAAdjustmentDetails.ServicePeriod',
                    DB::raw("CAST(Cashier_BAPAAdjustmentDetails.created_at AS DATE) AS DateAdjusted"),
                    DB::raw("COUNT(Cashier_BAPAAdjustmentDetails.id) AS NoOfConsumers"),
                    DB::raw("SUM(CAST(Billing_Bills.NetAmount AS DECIMAL(20,2))) AS TotalAmount"),
                    DB::raw("SUM(CAST(Cashier_BAPAAdjustmentDetails.DiscountAmount AS DECIMAL(10,2))) AS DiscountTotal"))
                ->groupBy('Cashier_BAPAAdjustmentDetails.DiscountPercentage', 
                    'Cashier_BAPAAdjustmentDetails.ServicePeriod',
                    DB::raw("CAST(Cashier_BAPAAdjustmentDetails.created_at AS DATE)"))
                ->get();
        } else {
            $bapaData = [];
            $bapaAdjustmentData = [];
        }

        return view('/b_a_p_a_adjustments/bapa_collection_monitor_console', [
            'bapaName' => $bapaName,
            'rate' => $rate,
            'routes' => $routes,
            'bapaData' => $bapaData,
            'bapaAdjustmentData' => $bapaAdjustmentData,
        ]);
    }

    public function printVoucher($representative, $bapaName, $period, $discount, $dateAdjusted) {
        $representative = urldecode($representative);
        $bapaName = urldecode($bapaName);

        $town = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->where('Billing_ServiceAccounts.OrganizationParentAccount', $bapaName)
            ->groupBy('Billing_ServiceAccounts.OrganizationParentAccount', 'CRM_Towns.Town')
            ->select('Billing_ServiceAccounts.OrganizationParentAccount', 'CRM_Towns.Town')
            ->first();

        $bapaAdjustmentData = BAPAAdjustmentDetails::where('BAPAName', $bapaName)
            ->where("ServicePeriod", $period)
            ->whereRaw("CAST(created_at AS DATE)='" . $dateAdjusted . "'")
            ->where("DiscountPercentage", $discount)
            ->select('DiscountPercentage',
                DB::raw("CAST(created_at AS DATE) AS DateAdjusted"),
                DB::raw("COUNT(id) AS NoOfConsumers"),
                DB::raw("SUM(CAST(DiscountAmount AS DECIMAL(10,2))) AS DiscountTotal"))
            ->groupBy('DiscountPercentage', DB::raw("CAST(created_at AS DATE)"))
            ->first();

        return view('/b_a_p_a_adjustments/print_voucher', [
            'town' => $town,
            'bapaName' => $bapaName,
            'representative' => $representative,
            'discount' => $discount,
            'dateAdjusted' => $dateAdjusted,
            'bapaAdjustmentData' => $bapaAdjustmentData,
            'period' => $period,
        ]);
    }

    public function updateBapaPercentage(Request $request) {
        $bapaName = $request['BAPAName'];
        $period = $request['Period'];
        $percentage = $request['Percentage'];
        $dateAdjusted = $request['DateAdjusted'];

        $bapaAdjustments = DB::table('Cashier_BAPAAdjustmentDetails')
                ->leftJoin('Billing_Bills', 'Cashier_BAPAAdjustmentDetails.BillId', '=', 'Billing_Bills.id')
                ->where('BAPAName', $bapaName)
                ->where("Cashier_BAPAAdjustmentDetails.ServicePeriod", $period)
                ->whereRaw("CAST(Cashier_BAPAAdjustmentDetails.created_at AS DATE) = '" . $dateAdjusted . "'")
                ->select('Cashier_BAPAAdjustmentDetails.DiscountPercentage',
                    'Cashier_BAPAAdjustmentDetails.ServicePeriod',
                    'Cashier_BAPAAdjustmentDetails.id',
                    'Billing_Bills.NetAmount',)
                ->get();

        foreach ($bapaAdjustments as $item) {
            $adj = BAPAAdjustmentDetails::find($item->id);

            if ($adj != null) {
                $adj->DiscountPercentage = $percentage;
                $adj->DiscountAmount = floatval($item->NetAmount) * floatval($percentage);
                $adj->save();
            }
        }

        return response()->json('ok', 200);
    }

    public function deleteBapaPercentage(Request $request) {
        $bapaName = $request['BAPAName'];
        $period = $request['Period'];
        $percentage = $request['Percentage'];
        $dateAdjusted = $request['DateAdjusted'];

        $bapaAdjustments = DB::table('Cashier_BAPAAdjustmentDetails')
                ->where('BAPAName', $bapaName)
                ->where("Cashier_BAPAAdjustmentDetails.ServicePeriod", $period)
                ->whereRaw("CAST(Cashier_BAPAAdjustmentDetails.created_at AS DATE) = '" . $dateAdjusted . "'")
                ->delete();

        return response()->json('ok', 200);
    }

    public function removeAccountFromVoucher(Request $request) {
        $id = $request['id'];

        $bapaAdjustments = DB::table('Cashier_BAPAAdjustmentDetails')
                ->where('id', $id)
                ->delete();

        return response()->json('ok', 200);
    }
}
