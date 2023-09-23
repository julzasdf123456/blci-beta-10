<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateKatasNgVatTotalRequest;
use App\Http\Requests\UpdateKatasNgVatTotalRequest;
use App\Repositories\KatasNgVatTotalRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class KatasNgVatTotalController extends AppBaseController
{
    /** @var  KatasNgVatTotalRepository */
    private $katasNgVatTotalRepository;

    public function __construct(KatasNgVatTotalRepository $katasNgVatTotalRepo)
    {
        $this->middleware('auth');
        $this->katasNgVatTotalRepository = $katasNgVatTotalRepo;
    }

    /**
     * Display a listing of the KatasNgVatTotal.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $katasNgVatTotals = $this->katasNgVatTotalRepository->all();

        return view('katas_ng_vat_totals.index')
            ->with('katasNgVatTotals', $katasNgVatTotals);
    }

    /**
     * Show the form for creating a new KatasNgVatTotal.
     *
     * @return Response
     */
    public function create()
    {
        return view('katas_ng_vat_totals.create');
    }

    /**
     * Store a newly created KatasNgVatTotal in storage.
     *
     * @param CreateKatasNgVatTotalRequest $request
     *
     * @return Response
     */
    public function store(CreateKatasNgVatTotalRequest $request)
    {
        $input = $request->all();

        $katasNgVatTotal = $this->katasNgVatTotalRepository->create($input);

        Flash::success('Katas Ng Vat Total saved successfully.');

        return redirect(route('katasNgVats.index'));
    }

    /**
     * Display the specified KatasNgVatTotal.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $katasNgVatTotal = $this->katasNgVatTotalRepository->find($id);

        if (empty($katasNgVatTotal)) {
            Flash::error('Katas Ng Vat Total not found');

            return redirect(route('katasNgVatTotals.index'));
        }

        return view('katas_ng_vat_totals.show')->with('katasNgVatTotal', $katasNgVatTotal);
    }

    /**
     * Show the form for editing the specified KatasNgVatTotal.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $katasNgVatTotal = $this->katasNgVatTotalRepository->find($id);

        if (empty($katasNgVatTotal)) {
            Flash::error('Katas Ng Vat Total not found');

            return redirect(route('katasNgVatTotals.index'));
        }

        return view('katas_ng_vat_totals.edit')->with('katasNgVatTotal', $katasNgVatTotal);
    }

    /**
     * Update the specified KatasNgVatTotal in storage.
     *
     * @param int $id
     * @param UpdateKatasNgVatTotalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateKatasNgVatTotalRequest $request)
    {
        $katasNgVatTotal = $this->katasNgVatTotalRepository->find($id);

        if (empty($katasNgVatTotal)) {
            Flash::error('Katas Ng Vat Total not found');

            return redirect(route('katasNgVatTotals.index'));
        }

        $katasNgVatTotal = $this->katasNgVatTotalRepository->update($request->all(), $id);

        Flash::success('Katas Ng Vat Total updated successfully.');

        return redirect(route('katasNgVatTotals.index'));
    }

    /**
     * Remove the specified KatasNgVatTotal from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $katasNgVatTotal = $this->katasNgVatTotalRepository->find($id);

        if (empty($katasNgVatTotal)) {
            Flash::error('Katas Ng Vat Total not found');

            return redirect(route('katasNgVatTotals.index'));
        }

        $this->katasNgVatTotalRepository->delete($id);

        Flash::success('Katas Ng Vat Total deleted successfully.');

        return redirect(route('katasNgVatTotals.index'));
    }
}
