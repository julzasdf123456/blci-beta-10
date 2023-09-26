@php
    use App\Models\User;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Bill Adjustments Approvals</h4>
            </div>
        </div>
    </div>
</section>

<div class="content">
    @include('flash::message')

    <div class="clearfix"></div>
    
    <div class="card shadow-none">
        <div class="card-header">
            <span class="card-title">Pending Approvals</span>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-sm table-hover">
                <thead>
                    <th>Account No.</th>
                    <th>Account Name</th>
                    <th>Bill No.</th>
                    <th>Billing Month</th>
                    <th>Kwh Used</th>
                    <th>Net Amount</th>
                    <th>Remarks</th>
                    <th>Requested By</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($bills as $item)
                        <tr title="Account ID : {{ $item->AccountNumber }}">
                            <td>{{ $item->OldAccountNo }}</td>
                            <td>{{ $item->ServiceAccountName }}</td>
                            <td><a href="{{ route('bills.show', [$item->id]) }}">{{ $item->BillNumber }}</a></td>
                            <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                            <td>{{ $item->KwhUsed }}</td>
                            <td>{{ is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : $item->NetAmount }}</td>
                            <td>{{ $item->Notes }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="text-right">
                                <a href="{{ route('bills.bill-adjustments-approval-view', [$item->id]) }}" class="btn btn-xs btn-default"><i class="fas fa-eye ico-tab-mini"></i>View</a>
                                <a href="{{ route('bills.bill-adjustments-approve', [$item->id]) }}" class="btn btn-xs btn-success"><i class="fas fa-check-circle ico-tab-mini"></i>Approve</a>
                                <a href="{{ route('bills.bill-adjustments-reject', [$item->id]) }}" class="btn btn-xs btn-danger"><i class="fas fa-times-circle ico-tab-mini"></i>Reject</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
</div>
@endsection