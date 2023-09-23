@php
    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
    ini_set('max_execution_time', '600');
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Generate Turn Off List</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            {{-- Config --}}
            <div class="col-lg-4">
                {{-- METER READERS --}}
                <div class="card" id="meter-reader-search">
                    <div class="card-header">
                        <span class="card-title">Filter By Meter Reader</span>
                        <div class="card-tools">
                            <button class="btn btn-sm btn-primary" id="switch-to-routes">Switch To Routes</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="service-period">Billing Month</label>
                            <select id="service-period" class="form-control">
                                @for ($i = 0; $i < count($months); $i++)
                                    <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="towns">Area</label>
                            <select id="towns" class="form-control">
                                <option value="All">All</option>
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}" {{ env('APP_AREA_CODE')==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="MeterReader">Select Meter Reader</label>
                            <select name="MeterReader" id="MeterReader" class="form-control">
                                @if (count($meterReaders) > 0)
                                    @foreach ($meterReaders as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @else
                                    <option value="">No Meter Reader Found</option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="Day">Select Day</label>
                            <select name="Day" id="Day" class="form-control">
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" id="get-list-btn">Generate List</button>
                        <div id="loader-mreader" class="spinner-border text-info float-right gone" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

                {{-- ROUTE --}}
                <div class="card d-none" id="route-search">
                    <div class="card-header">
                        <span class="card-title">Filter By Route</span>
                        <div class="card-tools">
                            <button class="btn btn-sm btn-danger" id="switch-to-meter-reader">Switch To Meter Reader</button>
                        </div>
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
                    <div class="card-footer">
                        <button class="btn btn-danger" id="get-list-btn-route">Generate List</button>
                        <div id="loader-route" class="spinner-border text-info float-right gone" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="col-lg-8">
                <div class="card" style="height: 75vh;">
                    <div class="card-header border-0">
                        <span class="card-title">List (<i>press <strong>F3</strong> to search</i>)</span>
                    </div>
                    <div class="card-body table-responsive px-0">
                        <table class="table table-sm table-hover table-bordered" id="results-table">
                            <thead>
                                <th style="width: 25px;">#</th>
                                <th>Bill Number</th>
                                <th>Account Number</th>
                                <th>Account Name</th>
                                <th>Address</th>
                                <th>Meter No</th>
                                <th class="text-right">Amount Due</th>
                                <th>Due Date</th>
                                <th class="text-right">Arrears</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button id="print-list" class="btn btn-danger"><i class="fas fa-print ico-tab"></i>Print Turn Off List</button>
                    </div>
                </div>                
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        var period = ""
        var town = ""
        var option = "meter-reader"
        $(document).ready(function() {
            $('#get-list-btn').on('click', function() {
                period = $('#service-period').val()
                town = $('#towns').val()

                getList()
            })

            $('#get-list-btn-route').on('click', function() {
                period = $('#service-period').val()
                town = $('#towns').val()

                getListRoute()
            })

            $('#print-list').on('click', function() {
                if (option == "meter-reader") {
                    window.location.href = "{{ url('/disconnection_histories/print-turn-off-list') }}" + "/" + $('#service-period').val() + "/" + $('#towns').val() + "/" + $('#MeterReader').val() + "/" + $('#Day').val() 
                } else {
                    window.location.href = "{{ url('/disconnection_histories/print-turn-off-list-route') }}" + "/" + $('#service-period-route').val() + "/" + $('#towns-route').val() + "/" + $('#Route').val()
                }                         
            })

            $('#switch-to-routes').on('click', function() {
                $('#route-search').removeClass('d-none')
                $('#meter-reader-search').addClass('d-none')
                option = "route"
            })

            $('#switch-to-meter-reader').on('click', function() {
                $('#route-search').addClass('d-none')
                $('#meter-reader-search').removeClass('d-none')
                option = "meter-reader"
            })
        })

        function getList() {
            $('#results-table tbody tr').remove()
            $('#loader-mreader').removeClass('gone')
            $.ajax({
                url : '{{ route("disconnectionHistories.get-turn-off-list-preview") }}',
                type : 'GET',
                data : {
                    ServicePeriod : period,
                    Town : town,
                    MeterReader : $('#MeterReader').val(),
                    Day : $('#Day').val()
                },
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {
                        Swal.fire({
                            title : 'No Accounts to be Disconnected.',
                            icon : 'info'
                        })
                    } else {
                        $('#results-table tbody').append(res)
                    }    
                    $('#loader-mreader').addClass('gone')                
                },
                error : function(err) {
                    alert('An error occurred while fetching the disconnection list')
                    $('#loader-mreader').addClass('gone')
                }
            })
        }

        function getListRoute() {
            $('#results-table tbody tr').remove()
            $('#loader-route').removeClass('gone')
            $.ajax({
                url : '{{ route("disconnectionHistories.get-turn-off-list-preview-route") }}',
                type : 'GET',
                data : {
                    ServicePeriod : $('#service-period-route').val(),
                    Town : $('#towns-route').val(),
                    Route : $('#Route').val()
                },
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {
                        Swal.fire({
                            title : 'No Accounts to be Disconnected.',
                            icon : 'info'
                        })
                    } else {
                        console.log(res)
                        $('#results-table tbody').append(res)
                    }  
                    $('#loader-route').addClass('gone')
                },
                error : function(err) {
                    alert('An error occurred while fetching the disconnection list')
                    $('#loader-route').addClass('gone')
                }
            })
        }

        function deleteNotice(id) {
            if (confirm('Are you sure you want to remove this entry from the disconnection list?')) {
                $.ajax({
                    url : '/discoNoticeHistories/' + id,
                    type : 'DELETE',
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : id,
                    },
                    success : function(res) {
                        $('#' + id).remove()
                    },
                    error : function(err) {
                        alert('An error occurred while deleting the item from the disconnection list')
                    }
                })
            } 
        }
    </script>
@endpush