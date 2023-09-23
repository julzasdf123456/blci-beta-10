<div class="col-lg-12">
    <div class="card shadow-none">
        <div class="card-body">
            <div class="row">
                {{-- LINE --}}
                <div class="col-md-8" style="height: 35vh">
                    <p class="text-muted"><i class="fas fa-chart-line ico-tab"></i>Monthly Kwh Sales Trend</p>
                    <canvas id="kwhsales-line" height="200" style="height: 200px;"></canvas>
                </div>

                {{-- PIE --}}
                <div class="col-md-4" style="height: 40vh">
                    <p class="text-muted"><i class="fas fa-chart-pie ico-tab"></i>Current Kwh Input Mix Report</p>
                    <canvas id="kwhsales-pie" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>

                <div class="col-md-12">
                    <div class="divider"></div>
                </div>
            </div>            
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            graphSalesLineChart()
            graphSalesPieChart()
        })

        function graphSalesPieChart() {
            var pieChartUi = $('#kwhsales-pie').get(0).getContext('2d')

            $.ajax({
                url : "{{ route('kwhSales.dashboard-get-annual-sales-pie-graph') }}",
                type : 'GET',
                success : function(res) {
                    if (!jQuery.isEmptyObject(res)) {
                        var pieData = {
                            labels: [
                                'Victorias Sub',
                                'Sagay Sub',
                                'San Carlos Sub',
                                'Escalante Sub',
                                'Lopez Sub',
                                'Cadiz Sub',
                                'IPI Sub',
                                'Toboso Sub',
                                'Calatrava Sub',
                                'VMC',
                                'San Carlos Bio',
                            ],
                            datasets: [
                                {
                                    data: [
                                        res['VictoriasSubstation'],
                                        res['SagaySubstation'],
                                        res['SanCarlosSubstation'],
                                        res['EscalanteSubstation'],
                                        res['LopezSubstation'],
                                        res['CadizSubstation'],
                                        res['IpiSubstation'],
                                        res['TobosoCalatravaSubstation'],
                                        res['CalatravaSubstation'],
                                        res['VictoriasMillingCompany'],
                                        res['SanCarlosBionergy'],
                                    ],
                                    backgroundColor : [
                                        '#ef5350', 
                                        '#ec407a', 
                                        '#ab47bc', 
                                        '#7e57c2', 
                                        '#5c6bc0', 
                                        '#42a5f5', 
                                        '#29b6f6', 
                                        '#00bcd4', 
                                        '#009688', 
                                        '#4caf50', 
                                        '#8bc34a'
                                    ],
                                }
                            ]
                        }

                        var pieOptions = {
                            maintainAspectRatio : false,
                            responsive : true,
                        }
                        
                        new Chart(pieChartUi, {
                            type: 'pie',
                            data: pieData,
                            options: pieOptions
                        })
                    }
                },
                error : function(err) {
                    console.log(err)
                }
            })
        }

        function graphSalesLineChart() {
            var kwhSalesChartUi = document.getElementById('kwhsales-line').getContext('2d')
            var months = []

            $.ajax({
                url : "{{ route('kwhSales.dashboard-get-annual-sales-graph') }}",
                type : 'GET',
                data : {
                    Year : null,
                },
                success : function(res) {
                    if (!jQuery.isEmptyObject(res)) {
                        console.log(res)
                        var sales = []

                        $.each(res, function(index, element) {
                            months.push(moment(res[index]['ServicePeriod']).format('MMM YYYY'))
                            sales.push(res[index]['KwhSales'])
                        })

                        var kwhSalesData = {
                            labels: months,
                            datasets: [
                                {
                                    label: 'Kwh Sales',
                                    backgroundColor: '#00acc1',
                                    borderColor: '#0097a7',
                                    pointRadius: true,
                                    pointColor: '#00838f',
                                    pointStrokeColor: 'rgba(60,141,188,1)',
                                    pointHighlightFill: '#fff',
                                    pointHighlightStroke: 'rgba(60,141,188,1)',
                                    data: sales
                                },
                            ]
                        }

                        var kwhSalesOption = {
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

                        var kwhSalesChrat = new Chart(kwhSalesChartUi, { 
                            type: 'line',
                            data: kwhSalesData,
                            options: kwhSalesOption
                        })
                    }
                },
                error : function(err) {
                    console.log(err)
                } 
            })
        }
    </script>
@endpush