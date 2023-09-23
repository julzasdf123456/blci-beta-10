@php
    use App\Models\User;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Bills Cancellation Approvals</h4>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <table class="table table-sm table-hover">
        <thead>
            <th>Account No.</th>
            <th>Account Name</th>
            <th>Bill No.</th>
            <th>Billing Month</th>
            <th>Previous Kwh</th>
            <th>Present Kwh</th>
            <th>Kwh Used</th>
            <th>Net Amount</th>
            <th>Remarks</th>
            <th>Requested By</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($bills as $item)
                @php
                    $user = User::find($item->CancelRequestedBy);
                @endphp
                <tr title="Account ID : {{ $item->AccountNumber }}">
                    <td>{{ $item->OldAccountNo }}</td>
                    <td>{{ $item->ServiceAccountName }}</td>
                    <td><a href="{{ route('bills.show', [$item->id]) }}">{{ $item->BillNumber }}</a></td>
                    <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                    <td>{{ $item->PreviousKwh }}</td>
                    <td>{{ $item->PresentKwh }}</td>
                    <td>{{ $item->KwhUsed }}</td>
                    <td>{{ is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : $item->NetAmount }}</td>
                    <td>{{ $item->Notes }}</td>
                    <td>{{ $user != null ? ($user->name) : '-' }}</td>
                    <td>
                        <a href="{{ route('bills.approve-bill-cancellation-request', [$item->id]) }}" class="btn btn-xs btn-primary">Approve</a>
                        <a href="{{ route('bills.reject-bill-cancellation-request', [$item->id]) }}" class="btn btn-xs btn-danger">Reject</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection