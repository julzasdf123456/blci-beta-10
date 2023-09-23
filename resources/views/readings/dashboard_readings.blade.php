@php
    use App\Models\Towns;
    $towns = Towns::all();
    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
    
ini_set('max_execution_time', 0);
@endphp
<div class="col-lg-12">
    <div class="card shadow-none" style="height: 40vh;">
        <div class="card-header border-0">
            <span class="card-title">Meter Reading Monitor (Meter Readers)</span>

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

                <div class="form-group float-right" style="margin-right: 10px;">
                    <select id="Town" class="form-control form-control-sm">
                        <option value="All">All</option>
                        @foreach ($towns as $item)
                            <option value="{{ $item->id }}">{{ $item->Town }}</option>
                        @endforeach
                    </select>
                </div> 
                <div class="float-right">
                    <p style="margin-top: 2px; margin-right: 5px;"><strong>Town</strong></p>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-sm table-bordered table-hover table-head-fixed text-nowrap" id="reading-monitor-table">
                <thead>
                    <th class='text-center'>Meter Reader</th>
                    <th class='text-center'>Unbilled Based <br> From Readings</th>
                    <th class='text-center'>All Unbilled</th>
                    <th class='text-center'>Captured</th>
                    <th class='text-center'>Total Reading</th>
                    <th class='text-center'>Total Kwh</th>
                    <th class='text-center'>Total Billed</th>
                    <th class='text-center'>Total Amount</th>
                    <th class='text-center'>Collected Bills</th>
                    <th class='text-center'>Collected %</th>
                    <th class='text-center'>Uncollected Bills</th>
                    <th class='text-center'>Uncollected %</th>
                    <th class='text-center'>Disconnected</th>
                    <th class='text-center'>Disconnected %</th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- UNCOLLECTED --}}
<div class="modal fade" id="modal-uncollected" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="uncollected-title">Uncollected Accounts</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loader-uncollected" class="spinner-border text-info gone" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <table class="table table-hover table-sm" id="table-uncollected">
                    <thead>
                        <th style="width: 30px;">#</th>
                        <th>Account No.</th>
                        <th>Service Account Name</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Bill Amount</th>
                        <th>Due Date</th>
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

{{-- UNBILLED --}}
<div class="modal fade" id="modal-unbilled" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="unbilled-title">Unbilled Accounts</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loader-unbilled" class="spinner-border text-info gone" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <table class="table table-hover table-sm" id="table-unbilled">
                    <thead>
                        <th style="width: 30px;">#</th>
                        <th>Account No.</th>
                        <th>Service Account Name</th>
                        <th>Address</th>
                        <th>Status</th>
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
            fetchReadingMonitor()

            $('#service-period').on('change', function() {
                fetchReadingMonitor(this.value, $('#day-reading-monitor').val(), $('#Town').val())
            })

            $('#day-reading-monitor').on('change', function() {
                fetchReadingMonitor($('#service-period').val(), this.value, $('#Town').val())
            })

            $('#Town').on('change', function() {
                fetchReadingMonitor($('#service-period').val(), $('#day-reading-monitor').val(), this.value)
            })
        })

        function fetchReadingMonitor(period, day, town) {
            $('#reading-monitor-table tbody tr').remove()
            $.ajax({
                url : "{{ route('bills.dashboard-reading-monitor') }}",
                type : 'GET',
                data : {
                    Period : period,
                    Day : day,
                    Town : town,
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

        function showUncollected(mreader) {
            $('#modal-uncollected').modal('show')   
            $('#loader-uncollected').removeClass('gone')
            $('#table-uncollected tbody tr').remove()

            $.ajax({
                url : "{{ route('bills.show-uncollected-dashboard') }}",
                type : 'GET',
                data : {
                    MeterReader : mreader,
                    Day : $('#day-reading-monitor').val(),
                    Period : $('#service-period').val(),
                    Town : $('#Town').val()
                },
                success : function(res) {
                    $('#table-uncollected tbody').append(res)
                    $('#loader-uncollected').addClass('gone')
                },
                error : function(err) {
                    $('#loader-uncollected').addClass('gone')
                    Swal.fire({
                        text : 'Error getting uncollected data',
                        icon : 'error'
                    })
                }
            })
        }

        function showUnbilled(mreader) {
            $('#modal-unbilled').modal('show')   
            $('#loader-unbilled').removeClass('gone')
            $('#table-unbilled tbody tr').remove()

            $.ajax({
                url : "{{ route('bills.show-unbilled-dashboard') }}",
                type : 'GET',
                data : {
                    MeterReader : mreader,
                    Day : $('#day-reading-monitor').val(),
                    Period : $('#service-period').val(),
                    Town : $('#Town').val()
                },
                success : function(res) {
                    $('#table-unbilled tbody').append(res)
                    $('#loader-unbilled').addClass('gone')
                },
                error : function(err) {
                    $('#loader-unbilled').addClass('gone')
                    Swal.fire({
                        text : 'Error getting unbilled data',
                        icon : 'error'
                    })
                }
            })
        }
    </script>
@endpush