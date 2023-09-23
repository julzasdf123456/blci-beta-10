<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReadAndBillNoticesRequest;
use App\Http\Requests\UpdateReadAndBillNoticesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ReadAndBillNoticesRepository;
use Illuminate\Http\Request;
use Flash;

class ReadAndBillNoticesController extends AppBaseController
{
    /** @var ReadAndBillNoticesRepository $readAndBillNoticesRepository*/
    private $readAndBillNoticesRepository;

    public function __construct(ReadAndBillNoticesRepository $readAndBillNoticesRepo)
    {
        $this->middleware('auth');
        $this->readAndBillNoticesRepository = $readAndBillNoticesRepo;
    }

    /**
     * Display a listing of the read-and-bill-notices.
     */
    public function index(Request $request)
    {
        $readAndBillNotices = $this->readAndBillNoticesRepository->paginate(10);

        return view('read_and_bill_notices.index')
            ->with('readAndBillNotices', $readAndBillNotices);
    }

    /**
     * Show the form for creating a new read-and-bill-notices.
     */
    public function create()
    {
        return view('read_and_bill_notices.create');
    }

    /**
     * Store a newly created ReadAndBillNotices in storage.
     */
    public function store(CreateReadAndBillNoticesRequest $request)
    {
        $input = $request->all();

        $readAndBillNotices = $this->readAndBillNoticesRepository->create($input);

        Flash::success('Read And Bill Notices saved successfully.');

        return redirect(route('read-and-bill-notices.index'));
    }

    /**
     * Display the specified read-and-bill-notices.
     */
    public function show($id)
    {
        $readAndBillNotices = $this->readAndBillNoticesRepository->find($id);

        if (empty($readAndBillNotices)) {
            Flash::error('Read And Bill Notices not found');

            return redirect(route('read-and-bill-notices.index'));
        }

        return view('read_and_bill_notices.show')->with('readAndBillNotices', $readAndBillNotices);
    }

    /**
     * Show the form for editing the specified read-and-bill-notices.
     */
    public function edit($id)
    {
        $readAndBillNotices = $this->readAndBillNoticesRepository->find($id);

        if (empty($readAndBillNotices)) {
            Flash::error('Read And Bill Notices not found');

            return redirect(route('read-and-bill-notices.index'));
        }

        return view('read_and_bill_notices.edit')->with('readAndBillNotices', $readAndBillNotices);
    }

    /**
     * Update the specified ReadAndBillNotices in storage.
     */
    public function update($id, UpdateReadAndBillNoticesRequest $request)
    {
        $readAndBillNotices = $this->readAndBillNoticesRepository->find($id);

        if (empty($readAndBillNotices)) {
            Flash::error('Read And Bill Notices not found');

            return redirect(route('read-and-bill-notices.index'));
        }

        $readAndBillNotices = $this->readAndBillNoticesRepository->update($request->all(), $id);

        Flash::success('Read And Bill Notices updated successfully.');

        return redirect(route('read-and-bill-notices.index'));
    }

    /**
     * Remove the specified ReadAndBillNotices from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $readAndBillNotices = $this->readAndBillNoticesRepository->find($id);

        if (empty($readAndBillNotices)) {
            Flash::error('Read And Bill Notices not found');

            return redirect(route('read-and-bill-notices.index'));
        }

        $this->readAndBillNoticesRepository->delete($id);

        Flash::success('Read And Bill Notices deleted successfully.');

        return redirect(route('read-and-bill-notices.index'));
    }
}
