<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillMirrorRequest;
use App\Http\Requests\UpdateBillMirrorRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BillMirrorRepository;
use Illuminate\Http\Request;
use Flash;

class BillMirrorController extends AppBaseController
{
    /** @var BillMirrorRepository $billMirrorRepository*/
    private $billMirrorRepository;

    public function __construct(BillMirrorRepository $billMirrorRepo)
    {
        $this->middleware('auth');
        $this->billMirrorRepository = $billMirrorRepo;
    }

    /**
     * Display a listing of the BillMirror.
     */
    public function index(Request $request)
    {
        $billMirrors = $this->billMirrorRepository->paginate(10);

        return view('bill_mirrors.index')
            ->with('billMirrors', $billMirrors);
    }

    /**
     * Show the form for creating a new BillMirror.
     */
    public function create()
    {
        return view('bill_mirrors.create');
    }

    /**
     * Store a newly created BillMirror in storage.
     */
    public function store(CreateBillMirrorRequest $request)
    {
        $input = $request->all();

        $billMirror = $this->billMirrorRepository->create($input);

        Flash::success('Bill Mirror saved successfully.');

        return redirect(route('billMirrors.index'));
    }

    /**
     * Display the specified BillMirror.
     */
    public function show($id)
    {
        $billMirror = $this->billMirrorRepository->find($id);

        if (empty($billMirror)) {
            Flash::error('Bill Mirror not found');

            return redirect(route('billMirrors.index'));
        }

        return view('bill_mirrors.show')->with('billMirror', $billMirror);
    }

    /**
     * Show the form for editing the specified BillMirror.
     */
    public function edit($id)
    {
        $billMirror = $this->billMirrorRepository->find($id);

        if (empty($billMirror)) {
            Flash::error('Bill Mirror not found');

            return redirect(route('billMirrors.index'));
        }

        return view('bill_mirrors.edit')->with('billMirror', $billMirror);
    }

    /**
     * Update the specified BillMirror in storage.
     */
    public function update($id, UpdateBillMirrorRequest $request)
    {
        $billMirror = $this->billMirrorRepository->find($id);

        if (empty($billMirror)) {
            Flash::error('Bill Mirror not found');

            return redirect(route('billMirrors.index'));
        }

        $billMirror = $this->billMirrorRepository->update($request->all(), $id);

        Flash::success('Bill Mirror updated successfully.');

        return redirect(route('billMirrors.index'));
    }

    /**
     * Remove the specified BillMirror from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $billMirror = $this->billMirrorRepository->find($id);

        if (empty($billMirror)) {
            Flash::error('Bill Mirror not found');

            return redirect(route('billMirrors.index'));
        }

        $this->billMirrorRepository->delete($id);

        Flash::success('Bill Mirror deleted successfully.');

        return redirect(route('billMirrors.index'));
    }
}
