<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillMiscellaneousRequest;
use App\Http\Requests\UpdateBillMiscellaneousRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BillMiscellaneousRepository;
use Illuminate\Http\Request;
use Flash;

class BillMiscellaneousController extends AppBaseController
{
    /** @var BillMiscellaneousRepository $billMiscellaneousRepository*/
    private $billMiscellaneousRepository;

    public function __construct(BillMiscellaneousRepository $billMiscellaneousRepo)
    {
        $this->middleware('auth');
        $this->billMiscellaneousRepository = $billMiscellaneousRepo;
    }

    /**
     * Display a listing of the BillMiscellaneous.
     */
    public function index(Request $request)
    {
        $billMiscellaneouses = $this->billMiscellaneousRepository->paginate(10);

        return view('bill_miscellaneouses.index')
            ->with('billMiscellaneouses', $billMiscellaneouses);
    }

    /**
     * Show the form for creating a new BillMiscellaneous.
     */
    public function create()
    {
        return view('bill_miscellaneouses.create');
    }

    /**
     * Store a newly created BillMiscellaneous in storage.
     */
    public function store(CreateBillMiscellaneousRequest $request)
    {
        $input = $request->all();

        $billMiscellaneous = $this->billMiscellaneousRepository->create($input);

        Flash::success('Bill Miscellaneous saved successfully.');

        return redirect(route('billMiscellaneouses.index'));
    }

    /**
     * Display the specified BillMiscellaneous.
     */
    public function show($id)
    {
        $billMiscellaneous = $this->billMiscellaneousRepository->find($id);

        if (empty($billMiscellaneous)) {
            Flash::error('Bill Miscellaneous not found');

            return redirect(route('billMiscellaneouses.index'));
        }

        return view('bill_miscellaneouses.show')->with('billMiscellaneous', $billMiscellaneous);
    }

    /**
     * Show the form for editing the specified BillMiscellaneous.
     */
    public function edit($id)
    {
        $billMiscellaneous = $this->billMiscellaneousRepository->find($id);

        if (empty($billMiscellaneous)) {
            Flash::error('Bill Miscellaneous not found');

            return redirect(route('billMiscellaneouses.index'));
        }

        return view('bill_miscellaneouses.edit')->with('billMiscellaneous', $billMiscellaneous);
    }

    /**
     * Update the specified BillMiscellaneous in storage.
     */
    public function update($id, UpdateBillMiscellaneousRequest $request)
    {
        $billMiscellaneous = $this->billMiscellaneousRepository->find($id);

        if (empty($billMiscellaneous)) {
            Flash::error('Bill Miscellaneous not found');

            return redirect(route('billMiscellaneouses.index'));
        }

        $billMiscellaneous = $this->billMiscellaneousRepository->update($request->all(), $id);

        Flash::success('Bill Miscellaneous updated successfully.');

        return redirect(route('billMiscellaneouses.index'));
    }

    /**
     * Remove the specified BillMiscellaneous from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $billMiscellaneous = $this->billMiscellaneousRepository->find($id);

        if (empty($billMiscellaneous)) {
            Flash::error('Bill Miscellaneous not found');

            return redirect(route('billMiscellaneouses.index'));
        }

        $this->billMiscellaneousRepository->delete($id);

        Flash::success('Bill Miscellaneous deleted successfully.');

        return redirect(route('billMiscellaneouses.index'));
    }
}
