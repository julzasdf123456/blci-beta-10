<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateKatasNgVatRequest;
use App\Http\Requests\UpdateKatasNgVatRequest;
use App\Repositories\KatasNgVatRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\KatasNgVat;
use App\Models\KatasNgVatTotal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class KatasNgVatController extends AppBaseController
{
    /** @var  KatasNgVatRepository */
    private $katasNgVatRepository;

    public function __construct(KatasNgVatRepository $katasNgVatRepo)
    {
        $this->middleware('auth');
        $this->katasNgVatRepository = $katasNgVatRepo;
    }

    /**
     * Display a listing of the KatasNgVat.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $katas = DB::table('Billing_KatasNgVatTotal')
            ->select(
                'id',
                'Balance',
                'SeriesNo',
                DB::raw("(SELECT COUNT(id) FROM Billing_KatasNgVat WHERE SeriesNo=Billing_KatasNgVatTotal.SeriesNo) AS ConsumerCount")
            )
            ->orderByDesc('created_at')
            ->get();
        return view('katas_ng_vats.index', [
            'katas' => $katas,
        ]);
    }

    /**
     * Show the form for creating a new KatasNgVat.
     *
     * @return Response
     */
    public function create()
    {
        return view('katas_ng_vats.create');
    }

    /**
     * Store a newly created KatasNgVat in storage.
     *
     * @param CreateKatasNgVatRequest $request
     *
     * @return Response
     */
    public function store(CreateKatasNgVatRequest $request)
    {
        $input = $request->all();

        $katasNgVat = $this->katasNgVatRepository->create($input);

        Flash::success('Katas Ng Vat saved successfully.');

        return redirect(route('katasNgVats.index'));
    }

    /**
     * Display the specified KatasNgVat.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $katasNgVat = $this->katasNgVatRepository->find($id);

        if (empty($katasNgVat)) {
            Flash::error('Katas Ng Vat not found');

            return redirect(route('katasNgVats.index'));
        }

        return view('katas_ng_vats.show')->with('katasNgVat', $katasNgVat);
    }

    /**
     * Show the form for editing the specified KatasNgVat.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $katasNgVat = $this->katasNgVatRepository->find($id);

        if (empty($katasNgVat)) {
            Flash::error('Katas Ng Vat not found');

            return redirect(route('katasNgVats.index'));
        }

        return view('katas_ng_vats.edit')->with('katasNgVat', $katasNgVat);
    }

    /**
     * Update the specified KatasNgVat in storage.
     *
     * @param int $id
     * @param UpdateKatasNgVatRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateKatasNgVatRequest $request)
    {
        $katasNgVat = $this->katasNgVatRepository->find($id);

        if (empty($katasNgVat)) {
            Flash::error('Katas Ng Vat not found');

            return redirect(route('katasNgVats.index'));
        }

        $katasNgVat = $this->katasNgVatRepository->update($request->all(), $id);

        Flash::success('Katas Ng Vat updated successfully.');

        return redirect(route('katasNgVats.index'));
    }

    /**
     * Remove the specified KatasNgVat from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $katasNgVat = $this->katasNgVatRepository->find($id);

        if (empty($katasNgVat)) {
            Flash::error('Katas Ng Vat not found');

            return redirect(route('katasNgVats.index'));
        }

        $this->katasNgVatRepository->delete($id);

        Flash::success('Katas Ng Vat deleted successfully.');

        return redirect(route('katasNgVats.index'));
    }

    public function addKatas($seriesNo) {
        $katas = KatasNgVatTotal::where('SeriesNo', $seriesNo)->first();
        return view('/katas_ng_vats/add_katas', [
            'katas' => $katas,
            'seriesNo' => $seriesNo
        ]);
    }

    public function searchAccount(Request $request) {
        $accountNo = $request['AccountNumber'];

        $accounts = DB::table('Billing_ServiceAccounts')
            ->whereRaw("OldAccountNo='" . $accountNo . "'")
            ->get();

        $output = "";
        foreach($accounts as $item) {
            $output .= "<tr onclick=addToKatas('" . $item->id . "')>
                            <td>" . $item->OldAccountNo . "</td>
                            <td>" . $item->ServiceAccountName . "</td>
                        </tr>";
        }

        return response()->json($output, 200);
    }

    public function addAccountToKatas(Request $request)  {
        $accountNo = $request['AccountNumber'];
        $amount = $request['Amount'];
        
        $katas = KatasNgVat::where('AccountNumber', $accountNo)
            ->whereRaw("TRY_CAST(Balance AS DECIMAL(15,2)) > 0")
            ->first();
        
        if ($katas != null) {
            return response()->json('exists', 200);
        } else {
            $katas = new KatasNgVat;
            $katas->id = IDGenerator::generateIDandRandString();
            $katas->AccountNumber = $accountNo;
            $katas->Balance = $amount;
            $katas->SeriesNo = $request['SeriesNo'];
            $katas->Notes = Auth::id();
            $katas->save();

            $newBal = 0;

            // DEDUCT KATAS TO TOTAL AMOUNT
            $katasTotal = KatasNgVatTotal::where('SeriesNo', $request['SeriesNo'])->first();
            if ($katasTotal != null) {
                $amount = floatval($amount);
                $balance = floatval($katasTotal->Balance);
                $dif = $balance - $amount;

                $katasTotal->Balance = round($dif, 2);
                $katasTotal->save();

                $newBal = $dif;
            } else {
                $newBal = 0;
            }

            return response()->json($newBal, 200);
        }
    }

    public function fetchKatas(Request $request) {
        $katas = DB::table('Billing_KatasNgVat')
            ->leftJoin('Billing_ServiceAccounts', 'Billing_KatasNgVat.AccountNumber', '=', 'Billing_ServiceAccounts.id')
            ->whereRaw("TRY_CAST(Balance AS DECIMAL(15,2)) > 0 AND SeriesNo='" . $request['SeriesNo'] . "'")
            ->select('Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.AccountStatus',
                'Billing_KatasNgVat.*')
            ->orderByDesc('Billing_KatasNgVat.created_at')
            ->get();

        $output = "";
        foreach($katas as $item) {
            $output .= "<tr>
                            <td><a href='" . route('serviceAccounts.show', [$item->AccountNumber]) . "'>" . $item->OldAccountNo . "</a></td>
                            <td>" . $item->ServiceAccountName . "</td>
                            <td>" . $item->AccountStatus . "</td>
                            <td class='text-right'>" . number_format($item->Balance, 2) . "</td>
                            <td class='text-right'>
                                <button class='btn btn-xs btn-danger' onclick=deleteKatas('" . $item->id . "')><i class='fas fa-trash'></i></button>
                            </td>
                        </tr>";
        }

        return response()->json($output, 200);
    }

    public function deleteKatas(Request $request) {
        $katas = KatasNgVat::find($request['id']);

        // add back to total
        $amnt = floatval($katas->Balance);

        $newBal = 0;

        $katasTotal = KatasNgVatTotal::where('SeriesNo', $katas->SeriesNo)->first();
        if ($katasTotal != null) {
            $balance = floatval($katasTotal->Balance);
            $add = $balance + $amnt;

            $katasTotal->Balance = round($add, 2);
            $katasTotal->save();

            $newBal = $add;
        } else {
            $newBal = 0;
        }

        $katas->delete();

        return response()->json($newBal, 200);
    }
}
