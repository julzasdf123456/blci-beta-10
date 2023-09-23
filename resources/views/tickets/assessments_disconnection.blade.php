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
            <div class="col-sm-12">
                <h4>Create Disconnection Tickets</h4>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="row">
        {{-- FORM --}}
        <div class="col-lg-12">
            <div class="row">
                {{-- PERIOD --}}
                <div class="form-group col-lg-2 col-md-4">
                    <label for="service-period">Service Period</label>
                    <select id="service-period" class="form-control">
                        @for ($i = 0; $i < count($months); $i++)
                            <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                        @endfor
                    </select>
                </div>

                {{-- AREA --}}
                <div class="form-group col-lg-2">
                    <label for="route">Route</label>
                    {{-- <input type="text" id="route" class="form-control"> --}}
                    <select id="route" class="form-control">
                        @foreach ($towns as $item)
                            <option value="{{ $item->id }}">{{ $item->Town }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- BUTTONS --}}
                <div class="form-group col-lg-3">
                    <label style="opacity: 0; width: 100%;">Action</label>
                    <button class="btn btn-primary" id="filterBtn" title="Filter"><i class="fas fa-check ico-tab-mini"></i> View</button>
                    <button class="btn btn-success" id="downloadBtn" title="Download Excel"><i class="fas fa-print ico-tab-mini"></i> Create and Print</button>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="col-lg-12">
            <table class="table table-hover table-sm" id="results-table">
                <thead>
                    <th>Account No.</th>
                    <th>Consumer Name</th>
                    <th>Address</th>
                    <th>Due Date</th>
                    <th>Kwh Used</th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#filterBtn').on('click', function() {
                $('#results-table tbody tr').remove()
                $.ajax({
                    url : '/tickets/get-disconnection-results',
                    type : 'GET',
                    data : {
                        Period : $('#service-period').val(),
                        Route : $('#route').val(),
                    },
                    success : function(res) {
                        $('#results-table tbody').append(res)
                    },
                    error : function(error) {
                        alert('An error occurred while filtering data.')
                    }
                })
            })

            $('#downloadBtn').on('click', function() {
                $.ajax({
                    url : '/tickets/disconnection-results-route',
                    type : 'GET',
                    data : {
                        Period : $('#service-period').val(),
                        Route : $('#route').val(),
                    },
                    success : function(res) {
                        window.location.href = "{{ url('/tickets/create-and-print-disconnection-tickets') }}" + "/" + res['Period'] + "/" + res['Route']
                    },
                    error : function(error) {
                        alert('An error occurred while attempting to create tickets.')
                    }
                })
            })
        })
    </script>
@endpush