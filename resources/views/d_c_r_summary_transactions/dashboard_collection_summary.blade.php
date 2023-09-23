<div class="col-lg-12">
    <div class="card shadow-none">
        <div class="card-body">
            <div class="row">
                {{-- QUICK SUMMARY --}}
                <div class="col-lg-4">
                    <p class="text-muted" style="margin: 0px !important; padding: 0px !important;">Today's Collection</p>
                    <p class="text-success" id="todays-collection" style="font-size: 3em; font-weight: bold;">P 0.0</p>
                    <p class="text-muted">Yesterday: <span id="yesterday-collection">P 0.0</span></p>
                </div>

                <div class="col-lg-8 table-responsive">
                    <table class="table">
                        <tr>
                            <td class="text-center">Cadiz City</td>
                            <td class="text-center">EB Magalona</td>
                            <td class="text-center">Manapla</td>
                            <td class="text-center">Victorias City</td>
                            <td class="text-center">San Carlos City</td>
                            <td class="text-center">Sagay City</td>
                            <td class="text-center">Escalante City</td>
                            <td class="text-center">Calatrava</td>
                            <td class="text-center">Toboso</td>
                        </tr>
                        <tr>
                            <th class="text-center" id="cadiz-today"></th>
                            <th class="text-center" id="magalona-today"></th>
                            <th class="text-center" id="manapla-today"></th>
                            <th class="text-center" id="victorias-today"></th>
                            <th class="text-center" id="sancarlos-today"></th>
                            <th class="text-center" id="sagay-today"></th>
                            <th class="text-center" id="escalante-today"></th>
                            <th class="text-center" id="calatrava-today"></th>
                            <th class="text-center" id="toboso-today"></th>
                        </tr>
                    </table>
                </div>

                <div class="col-lg-12">
                    <div class="divider"></div>

                    <div class="card shadow-none" style="height: 35vh">
                        <div class="card-body">
                            <canvas id="collection-summary-chart" height="300" style="height: 300px;"></canvas>
                        </div>
                    </div>                       
                </div>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        
        $(document).ready(function() {
            getCollectionSummary()
            graphCollectionSummary()
        })

        function getCollectionSummary() {
            $.ajax({
                url : "{{ route('home.dash-get-collection-summary') }}",
                type : 'GET',
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {

                    } else {
                        var today = parseFloat(res['TodaysPowerBill']) + parseFloat(res['TodaysNonPowerBill'])
                        var yesterday = parseFloat(res['YesterdaysPowerBill']) + parseFloat(res['YesterdaysNonPowerBill'])

                        var cadiz = parseFloat(res['Cadiz'])
                        var magalona = parseFloat(res['Magalona'])
                        var manapla = parseFloat(res['Manapla'])
                        var victorias = parseFloat(res['Victorias'])
                        var sancarlos = parseFloat(res['SanCarlos'])
                        var sagay = parseFloat(res['Sagay'])
                        var escalante = parseFloat(res['Escalante'])
                        var calatrava = parseFloat(res['Calatrava'])
                        var toboso = parseFloat(res['Toboso'])

                        $('#todays-collection').text('₱' + (isNaN(today) ? '0.0' : Number(today.toFixed(2)).toLocaleString()))
                        $('#yesterday-collection').text('₱' + (isNaN(yesterday) ? '0.0' :  Number(yesterday.toFixed(2)).toLocaleString()))
                        
                        $('#cadiz-today').text('₱' + (isNaN(cadiz) ? '0.0' :  Number(cadiz.toFixed(2)).toLocaleString()))
                        $('#magalona-today').text('₱' + (isNaN(magalona) ? '0.0' :  Number(magalona.toFixed(2)).toLocaleString()))
                        $('#manapla-today').text('₱' + (isNaN(manapla) ? '0.0' :  Number(manapla.toFixed(2)).toLocaleString()))
                        $('#victorias-today').text('₱' + (isNaN(victorias) ? '0.0' :  Number(victorias.toFixed(2)).toLocaleString()))
                        $('#sancarlos-today').text('₱' + (isNaN(sancarlos) ? '0.0' :  Number(sancarlos.toFixed(2)).toLocaleString()))
                        $('#sagay-today').text('₱' + (isNaN(sagay) ? '0.0' :  Number(sagay.toFixed(2)).toLocaleString()))
                        $('#escalante-today').text('₱' + (isNaN(escalante) ? '0.0' :  Number(escalante.toFixed(2)).toLocaleString()))
                        $('#calatrava-today').text('₱' + (isNaN(calatrava) ? '0.0' :  Number(calatrava.toFixed(2)).toLocaleString()))
                        $('#toboso-today').text('₱' + (isNaN(toboso) ? '0.0' :  Number(toboso.toFixed(2)).toLocaleString()))
                    }
                },
                error : function(err) {
                    console.log(err)
                }
            })
        }

        function graphCollectionSummary() {
            var collectionSummaryChartCanvas = document.getElementById('collection-summary-chart').getContext('2d')
            // $('#application-chart-canvas').get(0).getContext('2d');
            var areas = ['Cadiz', 'EB Magalona', 'Manapla', 'Victorias', 'San Carlos', 'Sagay', 'Escalante', 'Calatrava', 'Toboso']

            $.ajax({
                url : "{{ route('home.dash-get-collection-summary-graph') }}",
                type : 'GET',
                success : function(res) {
                    if (!jQuery.isEmptyObject(res)) {
                        var today = []
                        var yesterday = []

                        today.push(parseFloat(res['TodayCadiz']))
                        today.push(parseFloat(res['TodayMagalona']))
                        today.push(parseFloat(res['TodayManapla']))
                        today.push(parseFloat(res['TodayVictorias']))
                        today.push(parseFloat(res['TodaySanCarlos']))
                        today.push(parseFloat(res['TodaySagay']))
                        today.push(parseFloat(res['TodayEscalante']))
                        today.push(parseFloat(res['TodayCalatrava']))
                        today.push(parseFloat(res['TodayToboso']))

                        yesterday.push(parseFloat(res['YesterdayCadiz']))
                        yesterday.push(parseFloat(res['YesterdayMagalona']))
                        yesterday.push(parseFloat(res['YesterdayManapla']))
                        yesterday.push(parseFloat(res['YesterdayVictorias']))
                        yesterday.push(parseFloat(res['YesterdaySanCarlos']))
                        yesterday.push(parseFloat(res['YesterdaySagay']))
                        yesterday.push(parseFloat(res['YesterdayEscalante']))
                        yesterday.push(parseFloat(res['YesterdayCalatrava']))
                        yesterday.push(parseFloat(res['YesterdayToboso']))

                        var collectionSummaryChartData = {
                            labels: areas,
                            datasets: [
                                {
                                    label: 'Today',
                                    backgroundColor: '#388e3c',
                                    borderColor: '#1b5e20',
                                    pointRadius: true,
                                    pointColor: '#e64a19',
                                    pointStrokeColor: 'rgba(60,141,188,1)',
                                    pointHighlightFill: '#fff',
                                    pointHighlightStroke: 'rgba(60,141,188,1)',
                                    data: today
                                },
                                {
                                    label: 'Yesterday',
                                    backgroundColor: 'rgba(210, 214, 222, .4)',
                                    borderColor: 'rgba(210, 214, 222, 1)',
                                    pointRadius: true,
                                    pointColor: '#e64a19',
                                    pointStrokeColor: '#c1c7d1',
                                    pointHighlightFill: '#fff',
                                    pointHighlightStroke: 'rgba(220,220,220,1)',
                                    data: yesterday
                                },
                            ]
                        }

                        var collectionSummaryChartOptions = {
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

                        var collectionSummaryChart = new Chart(collectionSummaryChartCanvas, { 
                            type: 'line',
                            data: collectionSummaryChartData,
                            options: collectionSummaryChartOptions
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