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
            <div class="card-header">
                <span class="card-title">Details</span>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover">
                    <thead>
                        <th>OR Number</th>
                        <th>OR Date</th>
                        <th>Consumer Name</th>
                        <th>Account Number</th>
                        <th>Billing Month</th>
                        <th>Amount Paid</th>
                    </thead>
                    <tbody>
                        @foreach ($paidBill as $item)
                            <tr>
                                <td>{{ $item->ORNumber }}</td>
                                <td>{{ $item->ORDate }}</td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ $item->AccountNumber }}</td>
                                <td>{{ date('M d, Y', strtotime($item->ServicePeriod)) }}</td>
                                <td>{{ number_format($item->NetAmount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('oRCancellations.approve-bills-or-cancellation', [$orNo]) }}" class="btn btn-warning">Approve Cancellation</a>
                <a href="" class="btn btn-default float-right">Decline</a>
            </div>
        </div>
    </div>
</div>
@endsection
