@php
    use App\Models\ServiceAccounts;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Erroneous Reading Analyzer</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body">
                    {!! Form::open(['route' => 'readings.erroneous-readings', 'method' => 'GET']) !!}
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="">Town</label>
                            <select id="Town" name="Town" class="form-control form-control-sm">
                                <option value="All">All</option>
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}" {{ !isset($_GET['Town']) ? ($item->id==env('APP_AREA_CODE') ? 'selected' : '') : ($_GET['Town']==$item->id ? 'selected' : '') }}>{{ $item->Town }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Billing Month</label>
                            <select id="ServicePeriod" name="ServicePeriod" class="form-control form-control-sm">
                                @foreach ($billingMonths as $item)
                                    <option value="{{ $item->ServicePeriod }}">{{ date('F Y', strtotime($item->ServicePeriod)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">No. of Results</label>
                            <input type="number" class="form-control form-control-sm" value="{{ isset($_GET['Count']) ? $_GET['Count'] : '30' }}" name="Count" id="Count">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Action</label><br>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye ico-tab-mini"></i>View</button>
                            {{-- <button id="print-btn" class="btn btn-sm btn-warning"><i class="fas fa-print ico-tab-mini"></i>Print</button> --}}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        {{-- RESULTS --}}
        <div class="col-lg-12">
            <div class="card shadow-none" style="height: 65vh;">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-bordered table-head-fixed text-nowrap">
                        <thead>
                            <th>Account Number</th>
                            <th>Account Name</th>
                            <th>Address</th>
                            <th>Consumer Type</th>
                            <th class="text-right">Pres Reading</th>
                            <th class="text-right">Prev Reading</th>
                            <th class="text-right">Reading Kwh</th>
                            <th class="text-right">Mult.</th>
                            <th class="text-right">Total Kwh Used</th>
                            <th class="text-right">Amount Due</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></td>
                                    <td>{{ $item->ServiceAccountName }}</td>
                                    <td>{{ ServiceAccounts::getAddress($item) }}</td>
                                    <td>{{ $item->AccountType }}</td>
                                    <td class="text-right text-info">{{ number_format($item->PresentKwh, 2) }}</td>
                                    <td class="text-right text-success">{{ number_format($item->PreviousKwh, 2) }}</td>
                                    <th class="text-right">{{ number_format(floatval($item->PresentKwh) - floatval($item->PreviousKwh), 2) }}</th>
                                    <td class="text-right">{{ number_format($item->Multiplier, 2) }}</td>
                                    <th class="text-right text-danger">{{ number_format($item->KwhUsed, 2) }}</th>
                                    <td class="text-right"><a href="{{ route('bills.show', [$item->id]) }}">{{ number_format($item->NetAmount, 2) }}</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection