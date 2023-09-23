<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentOrderRequest;
use App\Http\Requests\UpdatePaymentOrderRequest;
use App\Repositories\PaymentOrderRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class PaymentOrderController extends AppBaseController
{
    /** @var  PaymentOrderRepository */
    private $paymentOrderRepository;

    public function __construct(PaymentOrderRepository $paymentOrderRepo)
    {
        $this->middleware('auth');
        $this->paymentOrderRepository = $paymentOrderRepo;
    }

    /**
     * Display a listing of the PaymentOrder.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $paymentOrders = $this->paymentOrderRepository->all();

        return view('payment_orders.index')
            ->with('paymentOrders', $paymentOrders);
    }

    /**
     * Show the form for creating a new PaymentOrder.
     *
     * @return Response
     */
    public function create()
    {
        return view('payment_orders.create');
    }

    /**
     * Store a newly created PaymentOrder in storage.
     *
     * @param CreatePaymentOrderRequest $request
     *
     * @return Response
     */
    public function store(CreatePaymentOrderRequest $request)
    {
        $input = $request->all();

        $paymentOrder = $this->paymentOrderRepository->create($input);

        Flash::success('Payment Order saved successfully.');

        return redirect(route('paymentOrders.index'));
    }

    /**
     * Display the specified PaymentOrder.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $paymentOrder = $this->paymentOrderRepository->find($id);

        if (empty($paymentOrder)) {
            Flash::error('Payment Order not found');

            return redirect(route('paymentOrders.index'));
        }

        return view('payment_orders.show')->with('paymentOrder', $paymentOrder);
    }

    /**
     * Show the form for editing the specified PaymentOrder.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $paymentOrder = $this->paymentOrderRepository->find($id);

        if (empty($paymentOrder)) {
            Flash::error('Payment Order not found');

            return redirect(route('paymentOrders.index'));
        }

        return view('payment_orders.edit')->with('paymentOrder', $paymentOrder);
    }

    /**
     * Update the specified PaymentOrder in storage.
     *
     * @param int $id
     * @param UpdatePaymentOrderRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePaymentOrderRequest $request)
    {
        $paymentOrder = $this->paymentOrderRepository->find($id);

        if (empty($paymentOrder)) {
            Flash::error('Payment Order not found');

            return redirect(route('paymentOrders.index'));
        }

        $paymentOrder = $this->paymentOrderRepository->update($request->all(), $id);

        Flash::success('Payment Order updated successfully.');

        return redirect(route('paymentOrders.index'));
    }

    /**
     * Remove the specified PaymentOrder from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $paymentOrder = $this->paymentOrderRepository->find($id);

        if (empty($paymentOrder)) {
            Flash::error('Payment Order not found');

            return redirect(route('paymentOrders.index'));
        }

        $this->paymentOrderRepository->delete($id);

        Flash::success('Payment Order deleted successfully.');

        return redirect(route('paymentOrders.index'));
    }
}
