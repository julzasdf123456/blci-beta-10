@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>OR Cancellation Confirmation</h4>
            </div>
            <div class="col-sm-6">
                <a class="btn btn-default float-right"
                    href="{{ route('oRCancellations.index') }}">
                    Back
                </a>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-10 offset-lg-1 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="invoice p-3 mb-3">
                    <div class="row">
                        <div class="col-12">
                            <small class="float-right">Transaction Date: {{ date('F d, Y', strtotime($transaction->created_at)) }}</small>
                        </div>
                        <!-- /.col -->
                    </div>
                    <br>
                    <div class="row invoice-info">
                        <div class="col-sm-6 invoice-col">
                            <address>
                                <strong>OR No: {{ $transaction->ORNumber }}</strong><br>
                                OR Date: {{ date('F d, Y', strtotime($transaction->ORDate)) }}<br>
                                Time Paid: {{ date('h:i:s A', strtotime($transaction->created_at)) }}<br>
                                <strong>Cancelled By: {{ $user != null ? $user->name : '-' }}</strong><br>
                            </address>
                        </div>

                        <!-- /.col -->
                        <div class="col-sm-6 invoice-col">
                            <address>
                                Sub-Total: <strong>P {{ number_format($transaction->SubTotal, 2) }}</strong><br>
                                VAT: <strong>P {{ number_format($transaction->VAT, 2) }}</strong><br>
                                Overall Total: <h1 class="text-success">P {{ number_format($transaction->Total, 2) }}</h1>
                            </address>
                        </div>

                        <div class="col-sm-12">
                            <div class="divider"></div>
                            Reason of Cancellation: <strong><i>{{ $transaction->CancellationNotes }}</i></strong>
                        </div>
                    </div>
                </div>
                <p><strong>Particulars</strong></p>
                <table class="table table-sm table-hover">
                    <thead>
                        <th>Item</th>
                        <th>Amount</th>
                        <th>VAT</th>
                        <th>Total</th>
                    </thead>
                    <tbody>
                        @foreach ($transactionDetails as $item)
                            <tr>
                                <td>{{ $item->Particular }}</td>
                                <td>{{ number_format($item->Amount, 2) }}</td>
                                <td>{{ number_format($item->VAT, 2) }}</td>
                                <td>{{ number_format($item->Total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('oRCancellations.approve-transaction-cancellation', [$orCancellations->id]) }}" class="btn btn-warning">Approve Cancellation</a>
                <a href="" class="btn btn-default float-right">Decline</a>
            </div>
        </div>
    </div>
</div>
@endsection
