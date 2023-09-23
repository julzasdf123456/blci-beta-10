<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateExcemptionsRequest;
use App\Http\Requests\UpdateExcemptionsRequest;
use App\Repositories\ExcemptionsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Excemptions;
use App\Models\Rates;
use App\Models\IDGenerator;
use App\Models\MeterReaders;
use App\Models\Towns;
use App\Models\ServiceAccounts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class ExcemptionsController extends AppBaseController
{
    /** @var  ExcemptionsRepository */
    private $excemptionsRepository;

    public function __construct(ExcemptionsRepository $excemptionsRepo)
    {
        $this->middleware('auth');
        $this->excemptionsRepository = $excemptionsRepo;
    }

    /**
     * Display a listing of the Excemptions.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // $excemptions = Excemptions::select('ServicePeriod')
        //     ->groupBy('ServicePeriod')
        //     ->orderByDesc('ServicePeriod')
        //     ->get();

        // return view('excemptions.index', [
        //     'excemptions' => $excemptions
        // ]);
        $latestRate = Rates::orderByDesc('ServicePeriod')
            ->first();

        return view('/excemptions/new_excemptions', [
            'latestRate' => $latestRate,
            'towns' => Towns::all()
        ]);
    }

    /**
     * Show the form for creating a new Excemptions.
     *
     * @return Response
     */
    public function create()
    {
        return view('excemptions.create');
    }

    /**
     * Store a newly created Excemptions in storage.
     *
     * @param CreateExcemptionsRequest $request
     *
     * @return Response
     */
    public function store(CreateExcemptionsRequest $request)
    {
        $input = $request->all();

        $excemptions = $this->excemptionsRepository->create($input);

        Flash::success('Excemptions saved successfully.');

        return redirect(route('excemptions.index'));
    }

    /**
     * Display the specified Excemptions.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $excemptions = $this->excemptionsRepository->find($id);

        if (empty($excemptions)) {
            Flash::error('Excemptions not found');

            return redirect(route('excemptions.index'));
        }

        return view('excemptions.show')->with('excemptions', $excemptions);
    }

    /**
     * Show the form for editing the specified Excemptions.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $excemptions = $this->excemptionsRepository->find($id);

        if (empty($excemptions)) {
            Flash::error('Excemptions not found');

            return redirect(route('excemptions.index'));
        }

        return view('excemptions.edit')->with('excemptions', $excemptions);
    }

    /**
     * Update the specified Excemptions in storage.
     *
     * @param int $id
     * @param UpdateExcemptionsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateExcemptionsRequest $request)
    {
        $excemptions = $this->excemptionsRepository->find($id);

        if (empty($excemptions)) {
            Flash::error('Excemptions not found');

            return redirect(route('excemptions.index'));
        }

        $excemptions = $this->excemptionsRepository->update($request->all(), $id);

        Flash::success('Excemptions updated successfully.');

        return redirect(route('excemptions.index'));
    }

    /**
     * Remove the specified Excemptions from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $excemptions = $this->excemptionsRepository->find($id);

        if (empty($excemptions)) {
            Flash::error('Excemptions not found');

            return redirect(route('excemptions.index'));
        }

        $this->excemptionsRepository->delete($id);

        Flash::success('Excemptions deleted successfully.');

        return redirect(route('excemptions.index'));
    }

    public function newExcemption(Request $request) {
        $latestRate = Rates::orderByDesc('ServicePeriod')
            ->first();

        return view('/excemptions/new_excemptions', [
            'latestRate' => $latestRate,
            'towns' => Towns::all()
        ]);
    }

    public function searchAccountExcemption(Request $request) {
        $accountNo = $request['AccountNumber'];

        $data = DB::table('Billing_ServiceAccounts')
            ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
            ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
            ->whereRaw("Billing_ServiceAccounts.OldAccountNo LIKE '%" . $accountNo . "%'")
            ->whereRaw("Billing_ServiceAccounts.id NOT IN (SELECT AccountNumber FROM Billing_Excemptions)")
            ->select('Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.Purok',
                'Billing_ServiceAccounts.id',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay',
            )
            ->get();

        $output = "";
        foreach($data as $item) {
            $output .= "<tr id='" . $item->id . "'>
                            <td>" . $item->OldAccountNo . "</td>
                            <td>" . $item->ServiceAccountName . "</td>
                            <td class='text-right'>" . ServiceAccounts::getAddress($item) . "</td>
                            <td class='text-right'>
                                <button onclick=addExcemption('" . $item->id . "') class='btn btn-primary btn-xs'><i class='fas fa-plus ico-tab-mini'></i> Add</button>
                            </td>
                        </tr>";
        }

        return response()->json($output, 200);
    }

    public function addExcemption(Request $request) {
        $accountNo = $request['AccountNumber'];
        $reason = $request['Reason'];

        $excemption = new Excemptions;
        $excemption->id = IDGenerator::generateIDandRandString();
        $excemption->AccountNumber = $accountNo;
        $excemption->Notes = $reason;
        $excemption->save();

        return response()->json($excemption, 200);
    }

    public function getExcemptionsAjax(Request $request) {
        $town = $request['Town'];

        if ($town == 'All') {
            $data = DB::table('Billing_Excemptions')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Excemptions.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->select('Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_Excemptions.id',
                    'Billing_Excemptions.Notes')
                ->get();
        } else {
            $data = DB::table('Billing_Excemptions')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Excemptions.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->where('Billing_ServiceAccounts.Town', $town)
                ->select('Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_Excemptions.id',
                    'Billing_Excemptions.Notes')
                ->get();
        }

        $output = "";
        foreach($data as $item) {
            $output .= "<tr>
                        <td>" . $item->OldAccountNo . "</td>
                        <td>" . $item->ServiceAccountName . "</td>
                        <td>" . $item->Notes . "</td>
                        <td class='text-right'>
                            <button onclick=removeExcemption('" . $item->id . "') class='btn btn-danger btn-xs'><i class='fas fa-trash ico-tab-mini'></i> Remove</button>
                        </td>
                    </tr>";
        }

        return response()->json($output, 200);
    }

    public function removeExcemption(Request $request) {
        Excemptions::find($request['id'])->delete();

        return response()->json('ok', 200);
    }

    public function printExcemptions($town) {
        if ($town == 'All') {
            $data = DB::table('Billing_Excemptions')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Excemptions.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->select('Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_Excemptions.id',
                    'Billing_Excemptions.Notes',
                    'Billing_ServiceAccounts.Purok',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
                ->get();
        } else {
            $data = DB::table('Billing_Excemptions')
                ->leftJoin('Billing_ServiceAccounts', 'Billing_Excemptions.AccountNumber', '=', 'Billing_ServiceAccounts.id')
                ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                ->where('Billing_ServiceAccounts.Town', $town)
                ->select('Billing_ServiceAccounts.OldAccountNo',
                    'Billing_ServiceAccounts.ServiceAccountName',
                    'Billing_Excemptions.id',
                    'Billing_Excemptions.Notes',
                    'Billing_ServiceAccounts.Purok',
                    'Billing_ServiceAccounts.id',
                    'CRM_Towns.Town',
                    'CRM_Barangays.Barangay')
                ->get();
        }
        return view('/excemptions/print_excemptions', [
            'data' => $data,
            'town' => $town=='All' ? $town : Towns::find($town)->Town,
        ]);
    }
}
