<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBAPAReadingSchedulesRequest;
use App\Http\Requests\UpdateBAPAReadingSchedulesRequest;
use App\Repositories\BAPAReadingSchedulesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\BAPAReadingSchedules;
use App\Models\Towns;
use App\Models\IDGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class BAPAReadingSchedulesController extends AppBaseController
{
    /** @var  BAPAReadingSchedulesRepository */
    private $bAPAReadingSchedulesRepository;

    public function __construct(BAPAReadingSchedulesRepository $bAPAReadingSchedulesRepo)
    {
        $this->middleware('auth');
        $this->bAPAReadingSchedulesRepository = $bAPAReadingSchedulesRepo;
    }

    /**
     * Display a listing of the BAPAReadingSchedules.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $bAPAReadingSchedules = DB::table('Billing_BAPAReadingSchedule')
            ->select('ServicePeriod')
            ->groupBy('ServicePeriod')
            ->orderByDesc('ServicePeriod')
            ->get();

        return view('b_a_p_a_reading_schedules.index', [
            'periods' => $bAPAReadingSchedules,
        ]);
    }

    /**
     * Show the form for creating a new BAPAReadingSchedules.
     *
     * @return Response
     */
    public function create()
    {
        $towns = Towns::orderBy('id')->get();

        return view('b_a_p_a_reading_schedules.create', [            
            'towns' => $towns
        ]);
    }

    /**
     * Store a newly created BAPAReadingSchedules in storage.
     *
     * @param CreateBAPAReadingSchedulesRequest $request
     *
     * @return Response
     */
    public function store(CreateBAPAReadingSchedulesRequest $request)
    {
        $input = $request->all();

        $bAPAReadingSchedules = $this->bAPAReadingSchedulesRepository->create($input);

        Flash::success('B A P A Reading Schedules saved successfully.');

        return redirect(route('bAPAReadingSchedules.index'));
    }

    /**
     * Display the specified BAPAReadingSchedules.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $bAPAReadingSchedules = $this->bAPAReadingSchedulesRepository->find($id);

        if (empty($bAPAReadingSchedules)) {
            Flash::error('B A P A Reading Schedules not found');

            return redirect(route('bAPAReadingSchedules.index'));
        }

        return view('b_a_p_a_reading_schedules.show')->with('bAPAReadingSchedules', $bAPAReadingSchedules);
    }

    /**
     * Show the form for editing the specified BAPAReadingSchedules.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $bAPAReadingSchedules = $this->bAPAReadingSchedulesRepository->find($id);

        if (empty($bAPAReadingSchedules)) {
            Flash::error('B A P A Reading Schedules not found');

            return redirect(route('bAPAReadingSchedules.index'));
        }

        return view('b_a_p_a_reading_schedules.edit')->with('bAPAReadingSchedules', $bAPAReadingSchedules);
    }

    /**
     * Update the specified BAPAReadingSchedules in storage.
     *
     * @param int $id
     * @param UpdateBAPAReadingSchedulesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBAPAReadingSchedulesRequest $request)
    {
        $bAPAReadingSchedules = $this->bAPAReadingSchedulesRepository->find($id);

        if (empty($bAPAReadingSchedules)) {
            Flash::error('B A P A Reading Schedules not found');

            return redirect(route('bAPAReadingSchedules.index'));
        }

        $bAPAReadingSchedules = $this->bAPAReadingSchedulesRepository->update($request->all(), $id);

        Flash::success('B A P A Reading Schedules updated successfully.');

        return redirect(route('bAPAReadingSchedules.index'));
    }

    /**
     * Remove the specified BAPAReadingSchedules from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $bAPAReadingSchedules = $this->bAPAReadingSchedulesRepository->find($id);

        if (empty($bAPAReadingSchedules)) {
            Flash::error('B A P A Reading Schedules not found');

            return redirect(route('bAPAReadingSchedules.index'));
        }

        $this->bAPAReadingSchedulesRepository->delete($id);

        Flash::success('B A P A Reading Schedules deleted successfully.');

        return redirect(route('bAPAReadingSchedules.index'));
    }

    public function addSchedule(Request $request) {
        $period = $request['Period'];
        $town = $request['Town'];

        if ($town == 'All') {
            $bapa = DB::table('Billing_ServiceAccounts')
                ->whereNotNull('OrganizationParentAccount')
                ->whereRaw('LEN(OrganizationParentAccount) > 3')
                ->select('OrganizationParentAccount', 'Town')
                ->groupBy('OrganizationParentAccount', 'Town')
                ->get();
        } else {
            $bapa = DB::table('Billing_ServiceAccounts')
                ->where('Town', $town)
                ->whereRaw('LEN(OrganizationParentAccount) > 3')
                ->whereNotNull('OrganizationParentAccount')
                ->select('OrganizationParentAccount', 'Town')
                ->groupBy('OrganizationParentAccount', 'Town')
                ->get();
        }

        // SAVE TO SCHEDS
        foreach($bapa as $item) {
            //FILTER EXISTING
            $sched = BAPAReadingSchedules::where('Town', $item->Town)
                ->where('ServicePeriod', $period)
                ->where('BAPAName', $item->OrganizationParentAccount)
                ->first();

            if ($sched != null) {
                
            } else {
                $sched = new BAPAReadingSchedules;
                $sched->id = IDGenerator::generateIDandRandString();
                $sched->ServicePeriod = $period;
                $sched->Town = $item->Town;
                $sched->BAPAName = $item->OrganizationParentAccount;
                $sched->save();
            }         
        }

        // GET SCHEDS ON THIS PERIOD AND TOWN
        $scheds = BAPAReadingSchedules::where('ServicePeriod', $period)->get();

        $output = "";
        foreach($scheds as $item) {
            $output .= '<tr id="' . $item->id . '">
                            <td>' . $item->BAPAName . '</td>
                            <td class="text-right">
                                <button class="btn btn-sm btn-link text-danger" onclick=removeBapaFromSched("' . $item->id . '")><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>';
        }

        return response()->json($output, 200);
    }

    public function removeBapaFromSched(Request $request) {
        $id = $request['id'];

        BAPAReadingSchedules::find($id)->delete();

        return response()->json('ok', 200);
    }

    public function showSchedules($period) {
        $towns = Towns::orderBy('id')->get();

        return view('/b_a_p_a_reading_schedules/show_schedules', [
            'towns' => $towns,
            'period' => $period,
        ]);
    }

    public function getBapas(Request $request) {
        $town = $request['Town'];
        $period = $request['Period'];

        if ($town != null) {
            $scheds = BAPAReadingSchedules::where('ServicePeriod', $period)
            ->where('Town', $town)
            ->get();
        } else {
            $scheds = BAPAReadingSchedules::where('ServicePeriod', $period)->get();            
        }

        $output = "";
        foreach($scheds as $item) {
            $output .= '<tr id="' . $item->id . '">
                            <td>' . $item->BAPAName . '</td>
                            <td>' . $item->Status . '
                                ' . ($item->Status!=null ? '<button onclick=removeStatus("' . $item->id . '") class="btn btn-xs btn-warning" style="margin-left: 20px;">Re-allow Download</button>' : '') . '
                            </td>
                            <td class="text-right">
                                <button class="btn btn-sm btn-link text-danger" onclick=removeBapaFromSched("' . $item->id . '")><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>';
        }

        return response()->json($output, 200);
    }

    public function removeDownloadedStatusFromBapa(Request $request) {
        $schedId = $request['id'];
        $sched = BAPAReadingSchedules::find($schedId);

        if ($sched != null) {
            $sched->Status = null;
            $sched->save();
        }

        return response()->json($sched, 200);
    }
}
