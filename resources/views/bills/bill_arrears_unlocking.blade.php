@php
    use App\Models\Bills;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Bill Arrears Unlocking Console</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <table class="table table-hover table-sm">
            <thead>
                <th>Bill Number</th>
                <th>Account No.</th>
                <th>Consumer Name</th>
                <th>Service Period</th>
                <th>Kwh Used</th>
                <th>Net Amount</th>
                <th>Due Date</th>
                <th>Penalty/Surcharge</th>
                <th>Requested By</th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($bills as $item)
                    <tr>
                        <td><a href="{{ route('bills.show', [$item->id]) }}">{{ $item->BillNumber }}</a></td>
                        <td>{{ $item->AccountNumber }}</td>
                        <td>{{ $item->ServiceAccountName }}</td>
                        <td>{{ date('M d, Y', strtotime($item->ServicePeriod)) }}</td>
                        <td>{{ $item->KwhUsed }}</td>
                        <td>{{ number_format($item->NetAmount, 2) }}</td>
                        <td>{{ date('M d, Y', strtotime($item->DueDate)) }}</td>
                        <td>{{ Bills::getFinalPenalty($item) }}</td>
                        <td>{{ $item->name }}</td>
                        <td class="text-right">
                            <a href="{{ route('bills.unlock-bill-arrear', [$item->id]) }}" class="btn btn-xs btn-primary">Approve</a>
                            <a href="{{ route('bills.reject-unlock-bill-arrear', [$item->id]) }}" class="btn btn-xs btn-danger">Reject</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection