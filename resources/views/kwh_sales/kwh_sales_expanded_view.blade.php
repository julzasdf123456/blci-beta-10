@php
    use App\Models\ServiceAccounts;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Kwh Sales Expanded | Billing Mo: {{ date('F Y', strtotime($period)) }}, Town: {{ $town }}, Route: {{ $route }}</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-bordered table-hover">
                        <thead>
                            <th>#</th>
                            <th>Consumer Name</th>
                            <th>Account Number</th>
                            <th>Address</th>
                            <th>Kwh Used</th>
                            <th>Multiplier</th>
                            <th>Total Kwh Used</th>
                            <th>Amount Due</th>
                        </thead>
                        <tbody>
                            @php
                                $totalKwh = 0;
                                $totalAmount = 0;
                                $i=0;
                            @endphp
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $item->ServiceAccountName }}</td>
                                    <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></td>
                                    <td>{{ ServiceAccounts::getAddress($item) }}</td>
                                    <td class="text-right text-info">{{ is_numeric($item->Multiplier) ? round(floatval($item->PresentKwh) - floatval($item->PreviousKwh),2) : 'MULT_ERR' }}</td>
                                    <td class="text-right">{{ number_format($item->Multiplier) }}</td>
                                    <th class="text-right text-primary">{{ number_format($item->KwhUsed, 2) }}</th>
                                    <th class="text-right text-success">{{ number_format($item->NetAmount, 2) }}</th>
                                </tr>
                                @php
                                    $totalKwh += floatval($item->KwhUsed);
                                    $totalAmount += floatval($item->NetAmount);
                                    $i++;
                                @endphp
                            @endforeach
                            <tr>
                                <th colspan="6">Total</th>
                                <th class="text-right text-primary">{{ number_format($totalKwh, 2) }}</th>
                                <th class="text-right text-success">{{ number_format($totalAmount, 2) }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection