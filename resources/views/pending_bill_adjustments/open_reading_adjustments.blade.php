@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Zero Reading Adjustments for {{ date('F Y', strtotime($servicePeriod)) }}</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-8 offset-lg-2 col-md-12">
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Accounts that have been adjusted</span>

                <div class="card-tools">
                    <a href="{{ route('pendingBillAdjustments.confirm-all-adjustments', [$servicePeriod]) }}" class="btn btn-sm btn-success"><i class="fas fa-check-circle ico-tab-mini"></i>Confirm All</a>
                </div>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>Account No</th>
                        <th>Consumer Name</th>
                        <th>Adjusted Kwh Consumption</th>
                        <th>Reading Date</th>
                        <th>Adjusted By</th>
                        <th>Area Adjusted</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($pendingBillAdjustments as $item)
                            <tr>
                                <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->AccountNumber }}</a></td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ $item->KwhUsed }}</td>
                                <td>{{ date('F d, Y', strtotime($item->ReadDate)) }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->Office }}</td>
                                <td>
                                    <a href="{{ route('bills.zero-readings-view', [$item->ReadingId]) }}"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('pendingBillAdjustments.confirm-adjustment', [$item->id]) }}" class="btn btn-sm btn-link text-success" title="Confirm"><i class="fas fa-check-circle"></i></a>
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