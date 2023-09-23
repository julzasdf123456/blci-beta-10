@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Tickets Dashboard</h4>
                </div>
            </div>
        </div>
    </section>

    <div>
        <div class="row">
            {{-- STATUS COUNT --}}
            <div class="col-lg-3">                
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="new-tickets">...</h3>
                        <p>New Received Tickets</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file"></i>
                    </div>
                    <a href="#" id="new-tickets-btn" class="small-box-footer" title="New Received Tickets"  data-toggle="modal" data-target="#modal-stats">View <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3">                
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="sent-to-lineman">...</h3>
                        <p>Tickets Sent To Crew</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-forward"></i>
                    </div>
                    <a href="#" id="sent-to-lineman-btn" class="small-box-footer" title="Tickets Sent To Crew"  data-toggle="modal" data-target="#modal-stats">View <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3">                
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="executed-this-month">...</h3>
                        <p>Tickets Executed This Month</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <a href="#" id="executed-this-month-btn" class="small-box-footer" title="Tickets Executed This Month"  data-toggle="modal" data-target="#modal-stats">View <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3">                
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="average-execution-time">...</h3>
                        <p>Avg. Exec. Time This Month</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <a href="{{ route('tickets.kps-monitor') }}" class="small-box-footer">More Info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">          
            {{-- Execution and Filling Graph --}}
            <div class="col-lg-6">            
                <div class="card" style="height: 35vh;">
                    <div class="card-header border-0">
                        <span class="card-title"><i class="fas fa-chart-area ico-tab"></i>Trend of Ticket Execution</span>
                    </div>
                    <div class="card-body">
                        <canvas id="ticket-chart-canvas" height="300" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- MODAL FOR SHOWING STAT DETAILS --}}
<div class="modal fade" id="modal-stats" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="stat-show-title" class="modal-title">...</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive" style="height: 75vh;">
                <table class="table table-sm table-hover" id="results-table">
                    <thead>
                        <th>Account No.</th>
                        <th>Account Name</th>
                        <th>Address</th>
                        <th>Ticket/Complain</th>
                        <th id="date-performed">Date Filed</th>
                        <td width="30px;"></td>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {            
            fetchTicketTrends()
            fetchStatistics()

            // STATS BUTTONS
            $('#new-tickets-btn').on('click', function(e) {
                e.preventDefault()
                fetchStatDetails('Received')
                $('#stat-show-title').text($('#new-tickets-btn').attr('title'))
                $('#date-performed').text('Date Filed')
            })

            $('#sent-to-lineman-btn').on('click', function(e) {
                e.preventDefault()
                fetchStatDetails('Forwarded To Lineman')
                $('#stat-show-title').text($('#sent-to-lineman-btn').attr('title'))
                $('#date-performed').text('Date Forwarded')
            })

            $('#executed-this-month-btn').on('click', function(e) {
                e.preventDefault()
                fetchStatDetails('Executed')
                $('#stat-show-title').text($('#executed-this-month-btn').attr('title'))
                $('#date-performed').text('Date Executed')
            })
        })

        /**
         * Ticket TREND CHART
         */  
        function fetchTicketTrends() {
            // $('#application-chart-canvas').get(0).getContext('2d');
            //get previous 6 months
            var prevMonths = [];
            for (var i=0; i<6; i++) {
                prevMonths.push(moment().subtract(i, 'months').format('MMM Y'))
            }

            var ticketsChartCanvas = document.getElementById('ticket-chart-canvas').getContext('2d')

            $.ajax({
                url : '{{ route("tickets.fetch-dashboard-tickets-trend") }}',
                type : 'GET',
                success : function(res) {
                    var fileData = []
                    var executionData = []

                    fileData.push(res[0]['FileOne'])
                    fileData.push(res[0]['FileTwo'])
                    fileData.push(res[0]['FileThree'])
                    fileData.push(res[0]['FileFour'])
                    fileData.push(res[0]['FileFive'])
                    fileData.push(res[0]['FileSix'])

                    executionData.push(res[0]['ExecutionOne'])
                    executionData.push(res[0]['ExecutionTwo'])
                    executionData.push(res[0]['ExecutionThree'])
                    executionData.push(res[0]['ExecutionFour'])
                    executionData.push(res[0]['ExecutionFive'])
                    executionData.push(res[0]['ExecutionSix'])
                    
                    var ticketChartData = {
                        labels: prevMonths,
                        datasets: [
                        {
                            label: 'Received Tickets',
                            backgroundColor: 'rgba(60,141,188,0.9)',
                            borderColor: 'rgba(60,141,188,0.8)',
                            pointRadius: true,
                            pointColor: '#3b8bba',
                            pointStrokeColor: 'rgba(60,141,188,1)',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(60,141,188,1)',
                            data: fileData
                        },
                        {
                            label: 'Executed Tickets',
                            backgroundColor: 'rgba(210, 214, 222, 1)',
                            borderColor: 'rgba(210, 214, 222, 1)',
                            pointRadius: true,
                            pointColor: 'rgba(210, 214, 222, 1)',
                            pointStrokeColor: '#c1c7d1',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(220,220,220,1)',
                            data: executionData
                        }
                        ]
                    }

                    var ticketChartOptions = {
                        maintainAspectRatio: false,
                        responsive: true,
                        legend: {
                            display: true
                        },
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    display: false
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    display: false
                                }
                            }]
                        }
                    }

                    // This will get the first returned node in the jQuery collection.
                    // eslint-disable-next-line no-unused-vars
                    var ticketsChart = new Chart(ticketsChartCanvas, { // lgtm[js/unused-local-variable]
                        type: 'line',
                        data: ticketChartData,
                        options: ticketChartOptions
                    })
                },
                error : function(err) {
                    console.log(err)
                }
            })
        }

        /**
         * Get ticket statistics
         */
        function fetchStatistics() {
            $.ajax({
                url : '{{ route("tickets.get-ticket-statistics") }}',
                type : 'GET',
                success : function(res) {
                    if (!jQuery.isEmptyObject(res[0])) {
                        $('#new-tickets').text(res[0]['Received'])
                        $('#sent-to-lineman').text(res[0]['SentToLineman'])
                        $('#executed-this-month').text(res[0]['ExecutedThisMonth'])
                        $('#average-execution-time').text(res[0]['AverageExecutionTime'] + " hrs")
                    }
                },
                error : function(err) {
                    console.log(err)
                }
            })
        }

        /**
         * FETCH Statistics details
         */
        function fetchStatDetails(query) {
            $('#results-table tbody tr').remove()
            $.ajax({
                url : '{{ route("tickets.get-ticket-statistics-details") }}',
                type : 'GET',
                data : {
                    Query : query
                },
                success : function(res) {
                    $('#results-table tbody').append(res)
                },
                error : function(err) {
                    console.log(err)
                }
            })
        }
    </script>
@endpush