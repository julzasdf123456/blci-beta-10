@php
    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Kwh Sales</h4>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-period">Generate New</button>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3 row">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="col-lg-8 offset-lg-2 col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <span class="card-title">Generated Reports</span>
                </div>
                <div class="card-body table-responsive px-0">
                    <table class="table table-hover">
                        <thead>
                            <th>Billing Month</th>
                            <th>Total Demand</th>
                            <th>Total Kwh Sales</th>
                            <th>System Loss</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($kwhSales as $item)
                                <tr>
                                    <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                    <td>{{ number_format($item->TotalEnergyInput, 2) }}</td>
                                    <td>{{ number_format($item->TotalEnergyOutput, 2) }}</td>
                                    <td>{{ number_format($item->TotalSystemLoss, 2) }} ({{ number_format($item->TotalSystemLossPercentage, 2) }}%)</td>
                                    <td>
                                        <a href="{{ route('kwhSales.view-sales', [$item->id]) }}"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>

    <div class="modal fade" id="modal-period" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['route' => 'kwhSales.generate-new']) !!}
                <div class="modal-header">
                    <h4 class="modal-title">Choose Billing Month</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <select name="ServicePeriod" id="ServicePeriod" class="form-control">
                        @for ($i = 0; $i < count($months); $i++)
                            <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                        @endfor
                    </select>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    {!! Form::submit('Proceed', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

