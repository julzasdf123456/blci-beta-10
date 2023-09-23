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
                <h4>Sales Distribution Report - {{ date('F Y', strtotime($period)) }}</h4>
            </div>

            <div class="col-sm-6">
                @if ($sales != null)
                    @if ($sales->Status == 'CLOSED')
                        <span class="badge bg-success float-right">CLOSED</span>
                    @else
                        <button id="close-billing" class="btn btn-primary float-right"><i class="fas fa-lock ico-tab-mini"></i> Close Billing</button>
                    @endif
                    
                @else
                    <button class="btn btn-primary btn-xs float-right" style="margin-left: 10px;" data-toggle="modal" data-target="#modal-period">Generate KWH Sales</button>
                    <span class="badge bg-danger float-right">Closing is only available if KWH Sales has already been generated</span>
                @endif
                
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#overall" data-toggle="tab">
                        <i class="fas fa-circle"></i>
                        Overall Sales Draft</a></li>
                    <li class="nav-item"><a class="nav-link" href="#merged" data-toggle="tab">
                        <i class="fas fa-circle"></i>
                        Merged Sales Draft</a></li>
                    <li class="nav-item"><a class="nav-link" href="#consolidated-all" data-toggle="tab">
                        <i class="fas fa-circle"></i>
                        Consolidated ERC</a></li>
                    <li class="nav-item"><a class="nav-link" href="#over-under" data-toggle="tab">
                        <i class="fas fa-circle"></i>
                        Over/Under</a></li>
                    <li class="nav-item"><a class="nav-link" href="#coop-consumption" data-toggle="tab">
                        <i class="fas fa-circle"></i>
                        Coop Consumption</a></li>
                </ul>
            </div>
            <div class="card-body table-responsive p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="overall">
                        @include('kwh_sales.attach_over_all_sales_draft')
                    </div>
                    <div class="tab-pane" id="merged">
                        @include('kwh_sales.attach_merged_sales_draft')
                    </div>
                    <div class="tab-pane" id="consolidated-all">
                        @if ($sales != null && $sales->Status=='CLOSED')
                            @include('kwh_sales.closed_consolidated_erc')
                        @else
                            @include('kwh_sales.attach_consolidate_erc')
                        @endif                        
                    </div>  
                    <div class="tab-pane" id="over-under">
                        @if ($sales != null && $sales->Status=='CLOSED')
                            @include('kwh_sales.closed_over_under')
                        @else
                            @include('kwh_sales.attach_over_under')
                        @endif                          
                    </div>  
                    <div class="tab-pane" id="coop-consumption">
                        @include('kwh_sales.attach_coop_consumption')                      
                    </div>  
                </div>
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

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#close-billing').on('click', function() {
                Swal.fire({
                    title: 'Do you want to close this billing period?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    showLoaderOnConfirm: true,
                    preConfirm: (login) => {
                        return fetch(`{{ route('bills.close-billing', [$period]) }}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                            return response.json()
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                            `Closing failed: ${error}`
                            )
                        })
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title : 'Closing success!',
                            icon : 'success'
                        })
                        location.reload()
                    }
                })
            })
        })
    </script>
@endpush