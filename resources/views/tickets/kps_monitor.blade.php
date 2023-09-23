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
                    <h4>KPS Monitoring</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            {{-- TICKETS --}}
            <div class="col-lg-12">
                {{-- TREND GRAPH --}}
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Ticket Crew Monitoring</h3>

                            <div class="card-tools">
                                <div class="form-group">
                                    <select id="ticket-month" class="form-control">
                                        @for ($i = 0; $i < count($months); $i++)
                                            <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="position-relative mb-4" id="ticket-graph-container">
                                    <canvas id="ticket-trend-graph" height="280"></canvas>
                                </div>
                                <div class="d-flex flex-row justify-content-end">
                                    <span class="mr-2">
                                        <i class="fas fa-square text-primary"></i> Assigned
                                    </span>
                                    <span>
                                        <i class="fas fa-square text-gray"></i> Executed
                                    </span>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="info-box mb-3 bg-warning">
                                    <span class="info-box-icon"><i class="fas fa-calendar"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">Tickets Filed This Month</span>
                                    <span class="info-box-number" id="tickets-this-month">...</span>
                                    </div>
                                    
                                </div>
                                    
                                <div class="info-box mb-3 bg-success">
                                    <span class="info-box-icon"><i class="fas fa-calculator"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">Filed Daily Average</span>
                                    <span class="info-box-number" id="average-this-month">...</span>
                                    </div>                                    
                                </div>
                                    
                                <div class="info-box mb-3 bg-danger">
                                    <span class="info-box-icon"><i class="fas fa-calendar-check"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">Tickets Executed This Month</span>
                                    <span class="info-box-number" id="executed-this-month">...</span>
                                    </div>
                                    
                                </div>
                                    
                                <div class="info-box mb-3 bg-info">
                                    <span class="info-box-icon"><i class="fas fa-clipboard-check"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">Execution Daily Average</span>
                                    <span class="info-box-number" id="execution-daily-average">...</span>
                                    </div>
                                </div>
                                    
                            </div>
                        </div>                        
                        
                    </div>
                    <div class="card-footer table-responsive">
                        <p class="card-title">Crew Average Performance</p>
                        <table class="table table-hover table-sm table-bordered table-striped" id="ticket-average-hours-table">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center">Crew</th>
                                    <th colspan="4" class="text-center">Average Execution Hours</th>
                                    <th colspan="4" class="text-center">Average Statistical Count</th>
                                </tr>
                                <tr>
                                    <td class="text-center">Date Filed to Lineman Receiving</td>
                                    <td class="text-center">Lineman Receiving to Field Arrival</td>
                                    <td class="text-center">Arrival to Execution</td>
                                    <td class="text-center">Over All</td>
                                    <td class="text-center">Assigned This Month</td>
                                    <td class="text-center">Assigned Daily Avg.</td>
                                    <td class="text-center">Executed This Month</td>
                                    <td class="text-center">Execution Daily Avg.</td>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- SERVICE CONNECTIONS --}}
            <div class="col-lg-12">

            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        // TICKET TREND
        var ticketsCrew = []
        var ticketAssigned = []
        var ticketExecuted = []    
        
        $(document).ready(function() {
            plotTicketCrewGraph($('#ticket-month').val())
            plotTicketAverageCrewHours($('#ticket-month').val())
            plotTicketOverAllKps($('#ticket-month').val())

            // TICKET ticket-month CHANGE EVENT
            $('#ticket-month').change(function() {
                plotTicketCrewGraph(this.value)
                plotTicketAverageCrewHours(this.value)
                plotTicketOverAllKps(this.value)
            })
        })

        function plotTicketCrewGraph(month) {
            ticketsCrew = []
            ticketAssigned = []
            ticketExecuted = []
            $.ajax({
                url : '{{ route("tickets.get-kps-ticket-crew-graph") }}',
                type : 'GET',
                data : {
                    Month : month,
                },
                success : function(res) {
                    // RESET CANVAS
                    $('#ticket-trend-graph').remove()
                    $('#ticket-graph-container').append('<canvas id="ticket-trend-graph" height="280"></canvas>')
                    var ticketTrendGraph = $('#ticket-trend-graph')

                    // LOOP RESULTS
                    $.each(res, function(index, element) {
                        ticketsCrew.push(res[index]['StationName'])
                        ticketAssigned.push(res[index]['Assigned'])
                        ticketExecuted.push(res[index]['Executed'])
                    })
                    
                    'use strict'
                    var ticksStyle = { 
                        fontColor: '#495057', 
                        fontStyle: 'bold' 
                    }
                    var mode = 'index'
                    var intersect = true
                    var ticketTrendGraphInit = new Chart(ticketTrendGraph, {
                        type: 'bar', 
                            data: { 
                                labels: ticketsCrew, 
                                datasets: [
                                    { 
                                        backgroundColor: '#007bff', 
                                        borderColor: '#007bff', 
                                        data: ticketAssigned 
                                    }, 
                                    { 
                                        backgroundColor: '#ced4da', 
                                        borderColor: '#ced4da', 
                                        data: ticketExecuted
                                    }
                                ] 
                            }, 
                            options: {
                            maintainAspectRatio: false, 
                                tooltips: { 
                                    mode: mode, 
                                    intersect: intersect 
                                }, 
                                hover: { 
                                    mode: mode, 
                                    intersect: intersect 
                                }, 
                                legend: { 
                                    display: false 
                                }, 
                                scales: {
                                yAxes: [{
                                    gridLines: { 
                                        display: true, 
                                        lineWidth: '4px', 
                                        color: 'rgba(0, 0, 0, .2)', 
                                        zeroLineColor: 'transparent' 
                                    }, 
                                    ticks: $.extend({
                                        beginAtZero: true, callback: function (value) {
                                            if (value >= 1000) {
                                                value /= 1000
                                                value += 'k'
                                            }
                                            return value
                                        }
                                    }, ticksStyle)
                                }], 
                                xAxes: [
                                    { 
                                        display: true, 
                                        gridLines: { 
                                            display: false 
                                        }, 
                                        ticks: ticksStyle 
                                    }
                                ]
                            }
                        }
                    })
                },
                error : function(err) {
                    console.log(err)
                }
            })
        }

        function plotTicketAverageCrewHours(month) {
            $.ajax({
                url : '{{ route("tickets.get-ticket-avg-hours") }}',
                type : 'GET',
                data : {
                    Month : month,
                },
                success : function(res) {
                    $('#ticket-average-hours-table tbody tr').remove()
                    $('#ticket-average-hours-table tbody').append(res)
                }, 
                error : function(err) {
                    console.log(err)
                }
            })
        }

        function plotTicketOverAllKps(month) {
            $.ajax({
                url : '{{ route("tickets.get-overall-avg-kps") }}',
                type : 'GET',
                data : {
                    Month : month,
                },
                success : function(res) {
                    if (!jQuery.isEmptyObject(res)) {
                        $('#tickets-this-month').text(res[0]['TotalFiled'])
                        $('#executed-this-month').text(res[0]['TotalExecuted'])
                        $('#average-this-month').text(res[0]['AverageFiled'])
                        $('#execution-daily-average').text(res[0]['AverageExecuted'])
                    }
                }, 
                error : function(err) {
                    console.log(err)
                }
            })
        }
    </script>
@endpush