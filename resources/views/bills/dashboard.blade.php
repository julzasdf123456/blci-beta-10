@php
    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
    
ini_set('max_execution_time', 0);
@endphp

@extends('layouts.app')

@section('content')
<div class="row">
    {{-- CONFIG --}}
    <div class="col-lg-5">
        <p style="padding-top: 8px;"><i class="fas fa-chart-line ico-tab"></i>Billing Dashboard</p>
    </div>
    <div class="col-lg-7" style="padding-top: 5px;">
        
    </div>

    {{-- TABLES --}}
    <div class="col-lg-8">
        <div class="card" style="height: 70vh;">
            <div class="card-header">
                <span class="card-title">Meter Reading Collection Efficiency</span>

                <div class="card-tools">
                    <div class="form-group float-right">
                        <select id="service-period" class="form-control form-control-sm">
                            @for ($i = 0; $i < count($months); $i++)
                                <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                            @endfor
                        </select>
                    </div>
            
                    <div class="float-right">
                        <p style="margin-top: 2px; margin-right: 5px;"><strong>Billing Month</strong></p>
                    </div>
            
                    <div class="form-group float-right" style="margin-right: 10px;">
                        <select id="day-reading-monitor" class="form-control form-control-sm">
                            <option value="All">All</option>
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
                        </select>
                    </div> 
                    <div class="float-right">
                        <p style="margin-top: 2px; margin-right: 5px;"><strong>Day</strong></p>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-bordered table-hover" id="reading-monitor-table">
                    <thead>
                        <th class='text-center'>Meter Reader</th>
                        <th class='text-center'></th>
                        <th class='text-center'>Sales</th>
                        <th class='text-center'>Collected</th>
                        <th class='text-center'>Uncollected</th>
                        <th class='text-center'>% Collected</th>
                        <th class='text-center'>% Uncollected</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-success" style="height: 70vh;">
            <div class="card-header">
                <span class="card-title">Today's Reading (<i>F5 to Refresh</i>)</span>
                <div class="card-tools">
                    <a href="{{ route('readings.reading-monitor-view', [date('Y-m-d', strtotime($latestRate->ServicePeriod))]) }}" title="Go to Reading Monitor"><i class="fas fa-share"></i></a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-bordered table-hover">
                    <thead>
                        <th class="text-center">Meter Reader</th>
                        <th class="text-center">Total Readings</th>
                        <th class="text-center">Total Bills</th>
                        <th class="text-center">Total Unbilled</th>
                    </thead>
                    <tbody>
                        @php
                            $totalReadingsToday = 0;
                            $totalBillsToday = 0;
                            $totalUnbilledToday = 0;
                        @endphp
                        @foreach ($todaysReading as $item)
                            <tr>
                                <th>{{ $item->name }}</th>
                                <th class="text-right">{{ number_format($item->TotalReading) }}</th>
                                <th class="text-right">{{ number_format($item->TotalBills) }}</th>
                                <th class="text-right">{{ number_format(floatval($item->TotalReading) - floatval($item->TotalBills)) }}</th>
                            </tr>
                            @php
                                $totalReadingsToday += floatval($item->TotalReading);
                                $totalBillsToday += floatval($item->TotalBills);
                                $totalUnbilledToday += (floatval($item->TotalReading) - floatval($item->TotalBills));
                            @endphp
                        @endforeach
                        <tr>
                            <th>Total</th>
                            <th class="text-right">{{ number_format($totalReadingsToday) }}</th>
                            <th class="text-right">{{ number_format($totalBillsToday) }}</th>
                            <th class="text-right">{{ number_format($totalUnbilledToday) }}</th>
                        </tr>
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
            fetchReadingMonitor()

            $('#service-period').on('change', function() {
                fetchReadingMonitor(this.value, $('#day-reading-monitor').val())
            })

            $('#day-reading-monitor').on('change', function() {
                fetchReadingMonitor($('#service-period').val(), this.value)
            })
        })

        function fetchReadingMonitor(period, day) {
            $('#reading-monitor-table tbody tr').remove()
            $.ajax({
                url : "{{ route('bills.get-minified-collection-efficiency') }}",
                type : 'GET',
                data : {
                    Period : period,
                    Day : day,
                },
                success : function(res) {
                    $('#reading-monitor-table tbody').append(res)
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error fetching reading monitor',
                        icon : 'error'
                    })
                }
            })
        }
    </script>
@endpush