<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillsOriginalRequest;
use App\Http\Requests\UpdateBillsOriginalRequest;
use App\Repositories\BillsOriginalRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class BillsOriginalController extends AppBaseController
{
    /** @var  BillsOriginalRepository */
    private $billsOriginalRepository;

    public function __construct(BillsOriginalRepository $billsOriginalRepo)
    {
        $this->middleware('auth');
        $this->billsOriginalRepository = $billsOriginalRepo;
    }

    /**
     * Display a listing of the BillsOriginal.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $billsOriginals = $this->billsOriginalRepository->all();

        return view('bills_originals.index')
            ->with('billsOriginals', $billsOriginals);
    }

    /**
     * Show the form for creating a new BillsOriginal.
     *
     * @return Response
     */
    public function create()
    {
        return view('bills_originals.create');
    }

    /**
     * Store a newly created BillsOriginal in storage.
     *
     * @param CreateBillsOriginalRequest $request
     *
     * @return Response
     */
    public function store(CreateBillsOriginalRequest $request)
    {
        $input = $request->all();

        $billsOriginal = $this->billsOriginalRepository->create($input);

        Flash::success('Bills Original saved successfully.');

        return redirect(route('billsOriginals.index'));
    }

    /**
     * Display the specified BillsOriginal.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $billsOriginal = $this->billsOriginalRepository->find($id);

        if (empty($billsOriginal)) {
            Flash::error('Bills Original not found');

            return redirect(route('billsOriginals.index'));
        }

        return view('bills_originals.show')->with('billsOriginal', $billsOriginal);
    }

    /**
     * Show the form for editing the specified BillsOriginal.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $billsOriginal = $this->billsOriginalRepository->find($id);

        if (empty($billsOriginal)) {
            Flash::error('Bills Original not found');

            return redirect(route('billsOriginals.index'));
        }

        return view('bills_originals.edit')->with('billsOriginal', $billsOriginal);
    }

    /**
     * Update the specified BillsOriginal in storage.
     *
     * @param int $id
     * @param UpdateBillsOriginalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBillsOriginalRequest $request)
    {
        $billsOriginal = $this->billsOriginalRepository->find($id);

        if (empty($billsOriginal)) {
            Flash::error('Bills Original not found');

            return redirect(route('billsOriginals.index'));
        }

        $billsOriginal = $this->billsOriginalRepository->update($request->all(), $id);

        Flash::success('Bills Original updated successfully.');

        return redirect(route('billsOriginals.index'));
    }

    /**
     * Remove the specified BillsOriginal from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $billsOriginal = $this->billsOriginalRepository->find($id);

        if (empty($billsOriginal)) {
            Flash::error('Bills Original not found');

            return redirect(route('billsOriginals.index'));
        }

        $this->billsOriginalRepository->delete($id);

        Flash::success('Bills Original deleted successfully.');

        return redirect(route('billsOriginals.index'));
    }
}
