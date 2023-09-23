<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRatesRequest;
use App\Http\Requests\UpdateRatesRequest;
use App\Repositories\RatesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ResidentialRateRS;
use App\Imports\ResidentialRateRP;
use App\Imports\CommercialRateC2;
use App\Imports\CommercialRateC3;
use App\Imports\RetailSuppliersRateRE;
use App\Imports\CommercialRateCM;
use App\Imports\CommercialRateCP;
use App\Imports\PublicBuildingRateG2;
use App\Imports\PublicBuildingRateG3;
use App\Imports\PublicBuildingRateGV;
use App\Imports\PublicBuildingRateGP;
use App\Imports\HospitalRateH2;
use App\Imports\HospitalRateH3;
use App\Imports\HospitalRateHR;
use App\Imports\HospitalRateHP;
use App\Models\Rates;
use App\Models\RateUploadHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;
use Response;

class RatesController extends AppBaseController
{
    /** @var  RatesRepository */
    private $ratesRepository;

    public function __construct(RatesRepository $ratesRepo)
    {
        $this->middleware('auth');
        $this->ratesRepository = $ratesRepo;
    }

    /**
     * Display a listing of the Rates.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $rates = DB::table('Billing_Rates')
            ->select('ServicePeriod')
            ->groupBy('ServicePeriod')
            ->orderByDesc('ServicePeriod')
            ->get();

        return view('rates.index', [
            'rates' => $rates,
        ]);
    }

    /**
     * Show the form for creating a new Rates.
     *
     * @return Response
     */
    public function create()
    {
        return view('rates.create');
    }

    /**
     * Store a newly created Rates in storage.
     *
     * @param CreateRatesRequest $request
     *
     * @return Response
     */
    public function store(CreateRatesRequest $request)
    {
        $input = $request->all();

        $rates = $this->ratesRepository->create($input);

        Flash::success('Rates saved successfully.');

        return redirect(route('rates.index'));
    }

    /**
     * Display the specified Rates.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $rates = $this->ratesRepository->find($id);

        if (empty($rates)) {
            Flash::error('Rates not found');

            return redirect(route('rates.index'));
        }

        return view('rates.show')->with('rates', $rates);
    }

    /**
     * Show the form for editing the specified Rates.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $rates = $this->ratesRepository->find($id);

        if (empty($rates)) {
            Flash::error('Rates not found');

            return redirect(route('rates.index'));
        }

        return view('rates.edit')->with('rates', $rates);
    }

    /**
     * Update the specified Rates in storage.
     *
     * @param int $id
     * @param UpdateRatesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRatesRequest $request)
    {
        $rates = $this->ratesRepository->find($id);

        if (empty($rates)) {
            Flash::error('Rates not found');

            return redirect(route('rates.index'));
        }

        $rates = $this->ratesRepository->update($request->all(), $id);

        Flash::success('Rates updated successfully.');

        return redirect(route('rates.index'));
    }

    /**
     * Remove the specified Rates from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $rates = $this->ratesRepository->find($id);

        if (empty($rates)) {
            Flash::error('Rates not found');

            return redirect(route('rates.index'));
        }

        $this->ratesRepository->delete($id);

        Flash::success('Rates deleted successfully.');

        return redirect(route('rates.index'));
    }

    public function uploadRate() {
        return view('/rates/upload_rate');
    }

    public function validateRateUpload(Request $request) {
        if ($request->file('file') != null) {

            $period = $request['ServicePeriod'];
            $file = $request->file('file');
            $userId = Auth::id();

            $residentialRatesRS = new ResidentialRateRS($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($residentialRatesRS, $file);

            $residentialRatesRP = new ResidentialRateRP($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($residentialRatesRP, $file);

            $commercialRatesC2 = new CommercialRateC2($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($commercialRatesC2, $file);

            $commercialRatesC3 = new CommercialRateC3($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($commercialRatesC3, $file);

            $retailSuppliersRE = new RetailSuppliersRateRE($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($retailSuppliersRE, $file);

            $commercialRatesCM = new CommercialRateCM($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($commercialRatesCM, $file);

            $commercialRatesCP = new CommercialRateCP($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($commercialRatesCP, $file);

            $publicBldgG2 = new PublicBuildingRateG2($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($publicBldgG2, $file);

            $publicBldgG3 = new PublicBuildingRateG3($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($publicBldgG3, $file);

            $publicBldgGV = new PublicBuildingRateGV($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($publicBldgGV, $file);

            $publicBldgGP = new PublicBuildingRateGP($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($publicBldgGP, $file);

            $hospitalH2 = new HospitalRateH2($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($hospitalH2, $file);

            $hospitalH3 = new HospitalRateH3($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($hospitalH3, $file);

            $hospitalHR = new HospitalRateHR($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($hospitalHR, $file);

            $hospitalHP = new HospitalRateHP($period, $userId, env('APP_LOCATION'), env('APP_AREA_CODE'), 0);
            Excel::import($hospitalHP, $file);

            Flash::success('Rates for ' . date('F Y', strtotime($period)) . ' uploaded successfully.');

            return redirect(route('rates.index'));
        } else {
            return abort(404, "No file specified!");
        }
    }

    public function viewRates($servicePeriod) {
        $categories = DB::table('Billing_Rates')
            ->select('RateFor')
            ->where('ServicePeriod', $servicePeriod)
            ->groupBy('RateFor')
            ->get();
        
        $rates = DB::table('Billing_Rates')
            ->where('ServicePeriod', $servicePeriod)
            ->orderBy('created_at')
            ->get();

        return view('rates.view_rates', [
            'categories' => $categories,
            'servicePeriod' => $servicePeriod,
            'rates' => $rates,
        ]);
    }

    public function deleteRates($servicePeriod) {
        Rates::where('ServicePeriod', $servicePeriod)->delete();

        Flash::success('Rates for ' . date('F Y', strtotime($servicePeriod)) . ' deleted.');

        return redirect(route('rates.index'));
    }

    public function getRate(Request $request) {
        $rates = Rates::where('ServicePeriod', $request['ServicePeriod'])
            ->where('ConsumerType', $request['ConsumerType'])
            ->where('AreaCode', $request['AreaCode'])
            ->first();

        return response()->json($rates, 200);
    }
}

