<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCollectiblesRequest;
use App\Http\Requests\UpdateCollectiblesRequest;
use App\Repositories\CollectiblesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Collectibles;
use App\Models\ArrearsLedgerDistribution;
use App\Models\IDGenerator;
use Flash;
use Response;

class CollectiblesController extends AppBaseController
{
    /** @var  CollectiblesRepository */
    private $collectiblesRepository;

    public function __construct(CollectiblesRepository $collectiblesRepo)
    {
        $this->middleware('auth');
        $this->collectiblesRepository = $collectiblesRepo;
    }

    /**
     * Display a listing of the Collectibles.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $collectibles = $this->collectiblesRepository->all();

        return view('collectibles.index')
            ->with('collectibles', $collectibles);
    }

    /**
     * Show the form for creating a new Collectibles.
     *
     * @return Response
     */
    public function create()
    {
        return view('collectibles.create');
    }

    /**
     * Store a newly created Collectibles in storage.
     *
     * @param CreateCollectiblesRequest $request
     *
     * @return Response
     */
    public function store(CreateCollectiblesRequest $request)
    {
        $input = $request->all();

        $collectibles = $this->collectiblesRepository->create($input);

        Flash::success('Collectibles saved successfully.');

        return redirect(route('serviceAccounts.show', [$input['AccountNumber']]));
    }

    /**
     * Display the specified Collectibles.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $collectibles = $this->collectiblesRepository->find($id);

        if (empty($collectibles)) {
            Flash::error('Collectibles not found');

            return redirect(route('collectibles.index'));
        }

        return view('collectibles.show')->with('collectibles', $collectibles);
    }

    /**
     * Show the form for editing the specified Collectibles.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $collectibles = $this->collectiblesRepository->find($id);

        if (empty($collectibles)) {
            Flash::error('Collectibles not found');

            return redirect(route('collectibles.index'));
        }

        return view('collectibles.edit')->with('collectibles', $collectibles);
    }

    /**
     * Update the specified Collectibles in storage.
     *
     * @param int $id
     * @param UpdateCollectiblesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCollectiblesRequest $request)
    {
        $collectibles = $this->collectiblesRepository->find($id);

        if (empty($collectibles)) {
            Flash::error('Collectibles not found');

            return redirect(route('serviceAccounts.show', [$input['AccountNumber']]));
        }

        $collectibles = $this->collectiblesRepository->update($request->all(), $id);

        Flash::success('Collectibles updated successfully.');

        return redirect(route('serviceAccounts.show', [$collectibles->AccountNumber]));
    }

    /**
     * Remove the specified Collectibles from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $collectibles = $this->collectiblesRepository->find($id);

        if (empty($collectibles)) {
            Flash::error('Collectibles not found');

            return redirect(route('collectibles.index'));
        }

        $this->collectiblesRepository->delete($id);

        Flash::success('Collectibles deleted successfully.');

        return redirect(route('collectibles.index'));
    }

    public function ledgerize(Request $request) {
        $collectibles = Collectibles::find($request['CollectibleId']);

        if ($collectibles != null) {
            $term = intval($request['Term']);

            $balance = floatval($collectibles->Balance);

            if ($balance > 0) {
                $termAmount = $balance / $term;

                for($i=0; $i<$term; $i++) {
                    $arrearLedger = new ArrearsLedgerDistribution;
                    $arrearLedger->id = IDGenerator::generateIDandRandString();
                    $arrearLedger->AccountNumber = $collectibles->AccountNumber;
                    $arrearLedger->ServicePeriod = date('Y-m-01', strtotime(date('Y-m-01') . ' +' . ($i+1) . ' months'));
                    $arrearLedger->Amount = $termAmount;
                    $arrearLedger->save();
                }
            }
            return response()->json(['res' => 'ok'], 200);
        } else {
            return response()->json(['res' => 'Arrears not found'], 404);
        }
    }

    public function clearLedger($id) {
        $arrearLedger = ArrearsLedgerDistribution::where('AccountNumber', $id)->whereNull('IsPaid')->delete();

        return redirect(route('serviceAccounts.show', [$id]));
    }

    public function addToMonth(Request $request) {
        $collectibles = Collectibles::where('AccountNumber', $request['AccountNumber'])
            ->first();

        if ($collectibles != null) {
            $collectibles->Balance = floatval($collectibles->Balance) + floatval($request['Amount']);
            $collectibles->save();

            $arrearLedger = new ArrearsLedgerDistribution;
            $arrearLedger->id = IDGenerator::generateIDandRandString();
            $arrearLedger->AccountNumber = $collectibles->AccountNumber;
            $arrearLedger->ServicePeriod = $request['Month'];
            $arrearLedger->Amount = $request['Amount'];
            $arrearLedger->save();
        }

        return response()->json(['res' => 'ok'], 200);
    }
}
