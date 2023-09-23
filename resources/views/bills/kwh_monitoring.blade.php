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
                <h4>Kwh Sales Monitor</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-3 col-md-4">
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title">Parameters</span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="service-period-route">Billing Month</label>
                    <select id="service-period-route" class="form-control">
                        @for ($i = 0; $i < count($months); $i++)
                            <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label for="towns-route">Area</label>
                    <select id="towns-route" class="form-control">
                        <option value="All">All</option>
                        @foreach ($towns as $item)
                            <option value="{{ $item->id }}" {{ env('APP_AREA_CODE')==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="Route">Route</label>
                    <input type="text" maxlength="5" class="form-control" id="Route" placeholder="Input Route">
                </div>
            </div>

            <div class="card-body">
                <button id="go-btn" class="btn btn-sm btn-primary"><i class="fas fa-check-circle ico-tab"></i>Go</button>
            </div>
        </div>
    </div>

    {{-- DETAILS --}}
    <div class="col-lg-9 col-md-8">
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title">Details</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover" id="results-table">
                    <thead>
                        <th>Consumer Type</th>
                        <th class="text-right"># of Consumers</th>
                        <th class="text-right">Total kWh Consumed</th>
                        <th class="text-right">Total Amount</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#go-btn').on('click', function() {
                fetchKwhData($('#Route').val())
            })
        })

        function fetchKwhData(route) {
            $.ajax({
                url : "{{ route('bills.fetch-kwh-data') }}",
                type : 'GET',
                data : {
                    ServicePeriod : $('#service-period-route').val(),
                    Town : $('#towns-route').val(),
                    Route : route
                },
                success : function(res) {
                    $('#results-table tbody tr').remove()
                    $('#results-table tbody').append(res)
                },
                error : function(err) {
                    Swal.fire({
                        title : 'An error occurred while getting data',
                        icon : 'error'
                    })
                }
            })
        }
    </script>
@endpush