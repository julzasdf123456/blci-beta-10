<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBAPAPaymentsRequest;
use App\Http\Requests\UpdateBAPAPaymentsRequest;
use App\Repositories\BAPAPaymentsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class BAPAPaymentsController extends AppBaseController
{
    /** @var  BAPAPaymentsRepository */
    private $bAPAPaymentsRepository;

    public function __construct(BAPAPaymentsRepository $bAPAPaymentsRepo)
    {
        $this->middleware('auth');
        $this->bAPAPaymentsRepository = $bAPAPaymentsRepo;
    }

    /**
     * Display a listing of the BAPAPayments.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $bAPAPayments = $this->bAPAPaymentsRepository->all();

        return view('b_a_p_a_payments.index')
            ->with('bAPAPayments', $bAPAPayments);
    }

    /**
     * Show the form for creating a new BAPAPayments.
     *
     * @return Response
     */
    public function create()
    {
        return view('b_a_p_a_payments.create');
    }

    /**
     * Store a newly created BAPAPayments in storage.
     *
     * @param CreateBAPAPaymentsRequest $request
     *
     * @return Response
     */
    public function store(CreateBAPAPaymentsRequest $request)
    {
        $input = $request->all();

        $bAPAPayments = $this->bAPAPaymentsRepository->create($input);

        Flash::success('B A P A Payments saved successfully.');

        return redirect(route('bAPAPayments.index'));
    }

    /**
     * Display the specified BAPAPayments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $bAPAPayments = $this->bAPAPaymentsRepository->find($id);

        if (empty($bAPAPayments)) {
            Flash::error('B A P A Payments not found');

            return redirect(route('bAPAPayments.index'));
        }

        return view('b_a_p_a_payments.show')->with('bAPAPayments', $bAPAPayments);
    }

    /**
     * Show the form for editing the specified BAPAPayments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $bAPAPayments = $this->bAPAPaymentsRepository->find($id);

        if (empty($bAPAPayments)) {
            Flash::error('B A P A Payments not found');

            return redirect(route('bAPAPayments.index'));
        }

        return view('b_a_p_a_payments.edit')->with('bAPAPayments', $bAPAPayments);
    }

    /**
     * Update the specified BAPAPayments in storage.
     *
     * @param int $id
     * @param UpdateBAPAPaymentsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBAPAPaymentsRequest $request)
    {
        $bAPAPayments = $this->bAPAPaymentsRepository->find($id);

        if (empty($bAPAPayments)) {
            Flash::error('B A P A Payments not found');

            return redirect(route('bAPAPayments.index'));
        }

        $bAPAPayments = $this->bAPAPaymentsRepository->update($request->all(), $id);

        Flash::success('B A P A Payments updated successfully.');

        return redirect(route('bAPAPayments.index'));
    }

    /**
     * Remove the specified BAPAPayments from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $bAPAPayments = $this->bAPAPaymentsRepository->find($id);

        if (empty($bAPAPayments)) {
            Flash::error('B A P A Payments not found');

            return redirect(route('bAPAPayments.index'));
        }

        $this->bAPAPaymentsRepository->delete($id);

        Flash::success('B A P A Payments deleted successfully.');

        return redirect(route('bAPAPayments.index'));
    }
}
