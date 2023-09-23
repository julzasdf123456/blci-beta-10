@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>OR Cancellation Approvals Console</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        @include('flash::message')

        <div class="clearfix"></div>
    </div>
    <div class="col-lg-6">
        {{-- BILLS PAYMENT --}}
        <div class="card" style="height: 80vh;">
            <div class="card-header border-0">
                <span class="card-title">Bills Payment</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover">
                    <thead>
                        <th>OR Number</th>
                        <th>OR Date</th>
                        <th>Consumer Name</th>
                        <th>Cancelled By</th>
                        <th width="40px;"></th>
                    </thead>
                    <tbody>
                        @foreach ($billsCancellations as $item)
                            <tr>
                                <td>{{ $item->ORNumber }}</td>
                                <td>{{ date('F d, Y', strtotime($item->ORDate)) }}</td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ $item->name }}</td>
                                <td class="text-right">
                                    <a href="{{ route('oRCancellations.show', [$item->ORNumber]) }}"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        {{-- OTHER PAYMENTS --}}
        <div class="card" style="height: 80vh;">
            <div class="card-header border-0">
                <span class="card-title">Other Payments</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover">
                    <thead>
                        <th>OR Number</th>
                        <th>OR Date</th>
                        <th>Payment Details</th>
                        <th>Cancelled By</th>
                        <th width="40px;"></th>
                    </thead>
                    <tbody>
                        @foreach ($transactionCancellations as $item)
                            <tr>
                                <td>{{ $item->ORNumber }}</td>
                                <td>{{ date('F d, Y', strtotime($item->ORDate)) }}</td>
                                <td>{{ $item->PaymentTitle }}</td>
                                <td>{{ $item->name }}</td>
                                <td class="text-right">
                                    <a href="{{ route('oRCancellations.show-other-payments', [$item->id]) }}"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

