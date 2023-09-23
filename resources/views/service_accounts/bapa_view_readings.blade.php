@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Readings of <?= $bapaName ?> for {{ date('F Y', strtotime($period)) }}</h4>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- DETAILS --}}
    <div class="col-lg-9 col-md-12">
        <div class="card" style="height: 80vh;">
            <div class="card-header border-0">
                <span class="card-title">Readings and Bills ({{ count($readings) }})</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover table-head-fixed text-nowrap table-bordered">
                    <thead>
                        <th>Account No</th>
                        <th>Account Name</th>
                        <th>Account Status</th>
                        <th class="text-right">Kwh Used</th>
                        <th class="text-right">Bill Number</th>
                        <th>Due Date</th>
                        <th class="text-right">Net Amount</th>
                    </thead>
                    <tbody>
                        @php
                            $billsCount = 0;
                            $disconnected = 0;
                            $totalKwh = 0;
                            $totalAmnt = 0;
                        @endphp
                        @foreach ($readings as $item)
                            <tr>
                                <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <th class="{{ $item->AccountStatus=='DISCONNECTED' ? 'text-danger' : 'text-success' }}">{{ $item->AccountStatus }}</th>
                                <td class="text-right">{{ number_format($item->KwhUsed) }}</td>
                                <td class="text-right"><a href="{{ $item->BillNumber==null ? '' : route('bills.show', [$item->BillId]) }}">{{ $item->BillNumber==null ? '-' : $item->BillNumber }}</a></td>
                                <td>{{ $item->DueDate != null ? date('F d, Y', strtotime($item->DueDate)) : '-' }}</td>
                                <td class="text-right">{{ $item->NetAmount==null ? '-' : number_format($item->NetAmount, 2) }}</td>
                            </tr>
                            @php
                                if ($item->BillNumber != null) {
                                    $billsCount += 1;
                                }

                                if ($item->AccountStatus=='DISCONNECTED') {
                                    $disconnected += 1;
                                }

                                $totalKwh += floatval($item->KwhUsed);
                                $totalAmnt += floatval($item->NetAmount);
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- STATS --}}
    <div class="col-lg-3 col-md-12">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Statistics</span>
            </div>
            <div class="card-body px-0">
                <table class="table table-borderless table-hover table-sm">
                    <tr>
                        <td>Total Readings</td>
                        <th class="text-right">{{ count($readings) }}</th>
                    </tr>
                    <tr>
                        <td>Total Bills</td>
                        <th class="text-right">{{ $billsCount }}</th>
                    </tr>
                    <tr>
                        <td>Total Disconnected</td>
                        <th class="text-right">{{ $disconnected }}</th>
                    </tr>
                    <tr>
                        <td>Total KWH Used</td>
                        <th class="text-right text-success">{{ number_format($totalKwh) }}</th>
                    </tr>
                    <tr>
                        <td>Total Amount</td>
                        <th class="text-right text-danger">{{ number_format($totalAmnt, 2) }}</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- MAP (to be added later) --}}
    <div class="col-lg-12 col-md-12">

    </div>
</div>
@endsection