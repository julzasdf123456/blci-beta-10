@php
    use App\Models\User;
@endphp
@extends('layouts.app')

@section('content')
<br>
<div class="row px-3">
    <div class="col-lg-6" style="margin-bottom: 10px;">
        <p class="text-muted" style="margin: 0px; padding: 0px;">BILL ADJUSTMENT PENDING APPROVAL</p>
        <a href="{{ route('serviceAccounts.show', [$account->id]) }}" style="font-size: 1.35em;">{{ $account->ServiceAccountName }}</a><br>
        <a href="{{ route('serviceAccounts.show', [$account->id]) }}">{{ $account->OldAccountNo }}</a>
    </div>

    <div class="col-lg-6">
        @php
            $user = User::find($billsOriginal->AdjustmentRequestedBy);
        @endphp
        <p class="text-muted" style="margin: 0px; padding: 0px;">Requested By: <strong>{{ $user != null ? $user->name : '' }}</strong></p>
        <p class="text-muted" style="margin: 0px; padding: 0px;">Date Requested: <strong>{{ date('M d, Y h:i A', strtotime($billsOriginal->DateAdjustmentRequested)) }}</strong></p>

        <a href="{{ route('bills.bill-adjustments-reject', [$billsOriginal->id]) }}" style="margin-left: 5px;" class="btn btn-xs btn-danger float-right"><i class="fas fa-times-circle ico-tab-mini"></i>Reject</a>
        <a href="{{ route('bills.bill-adjustments-approve', [$billsOriginal->id]) }}" class="btn btn-xs btn-success float-right"><i class="fas fa-check-circle ico-tab-mini"></i>Approve</a>
    </div>

    {{-- ORIGINAL --}}
    <div class="col-lg-6">
        <div class="card shadow-none">
            <div class="card-header bg-primary">
                <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Original</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-bordered table-sm">
                    <tbody>
                        <tr>
                            <td>Billing Month</td>
                            <td>{{ date('F Y', strtotime($bill->ServicePeriod)) }}</td>
                        </tr>
                        <tr>
                            <td>Due Date</td>
                            <td>{{ date('F d, Y', strtotime($bill->DueDate)) }}</td>
                        </tr>
                        <tr>
                            <td>Previous Reading</td>
                            <td>{{ $bill->PreviousKwh }}</td>
                        </tr>
                        <tr>
                            <td>Present Reading</td>
                            <td>{{ $bill->PresentKwh }}</td>
                        </tr>
                        <tr>
                            <td>kWH Used</td>
                            <td><strong class="text-danger">{{ $bill->KwhUsed }}</strong></td>
                        </tr>
                        <tr>
                            <td>Amount Due</td>
                            <td><strong class="text-primary">{{ number_format($bill->NetAmount, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ADJUSTED --}}
    <div class="col-lg-6">
        <div class="card shadow-none">
            <div class="card-header bg-warning">
                <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Adjusted</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-bordered table-sm">
                    <tbody>
                        <tr>
                            <td>Billing Month</td>
                            <td>{{ date('F Y', strtotime($billsOriginal->ServicePeriod)) }}</td>
                        </tr>
                        <tr>
                            <td>Due Date</td>
                            <td>{{ date('F d, Y', strtotime($billsOriginal->DueDate)) }}</td>
                        </tr>
                        <tr>
                            <td>Previous Reading</td>
                            <td>{{ $billsOriginal->PreviousKwh }}</td>
                        </tr>
                        <tr>
                            <td>Present Reading</td>
                            <td>{{ $billsOriginal->PresentKwh }}</td>
                        </tr>
                        <tr>
                            <td>kWH Used</td>
                            <td><strong class="text-danger">{{ $billsOriginal->KwhUsed }}</strong></td>
                        </tr>
                        <tr>
                            <td>Amount Due</td>
                            <td><strong class="text-primary">{{ number_format($billsOriginal->NetAmount, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection