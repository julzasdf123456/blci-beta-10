@php
    use App\Models\Readings;
    ini_set('max_execution_time', '300');
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4><i class="text-muted">Reading Report </i> | <i class="text-muted">Billing Month: </i> <strong>{{ date('F Y', strtotime($period)) }}</strong>
                        | <i class="text-muted">Meter Reader: </i> <strong>{{ $meterReader != null ? $meterReader->name : ($bapaName != null ? $bapaName : '') }} </strong>
                        | <i class="text-muted">Day: </i><strong>{{ $day }}</strong></h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        {{-- SUMMARY --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                {{-- <div class="card-header">
                    <span class="card-title"><i class="fas fa-check-circle ico-tab"></i>Summary</span>
                </div> --}}
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm">
                        <thead>
                            <td class="text-muted text-center">Disconnected<br>Readings</td>
                            <td class="text-muted text-center">Captured <br> Readings</td>
                            <td class="text-muted text-center">Stuck Up</td>
                            <td class="text-muted text-center">Change <br> Meters</td>
                            <td class="text-muted text-center">No Display</td>
                            <td class="text-muted text-center">Not in <br> Use</td>
                            <td class="text-muted text-center">Disco<br>/Active</td>
                            <td class="text-muted text-center">Other <br> Unbilled</td>
                            <td class="text-muted text-center">Total <br> Billed</td>
                            <td class="text-muted text-center">Total <br> Readings</td>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="text-center">{{ $summary != null ? $summary->Disconnected : '0' }}</th>
                                <th class="text-center">{{ $summary != null ? $summary->Captured : '0' }}</th>
                                <th class="text-center">{{ $summary != null ? $summary->StuckUp : '0' }}</th>
                                <th class="text-center">{{ $summary != null ? $summary->ChangeMeter : '0' }}</th>
                                <th class="text-center">{{ $summary != null ? $summary->NoDisplay : '0' }}</th>
                                <th class="text-center">{{ $summary != null ? $summary->NotInUse : '0' }}</th>
                                <th class="text-center">{{ $summary != null ? $summary->DiscoActive : '0' }}</th>
                                <th class="text-center">{{ $summary != null ? $summary->OtherUnbilled : '0' }}</th>
                                <th class="text-center text-primary">{{ $summary != null ? $summary->TotalBilled : '0' }}</th>
                                <th class="text-center text-success">{{ $summary != null ? number_format(intval($summary->Total) + intval($summary->Captured)) : '0' }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- INDIVIDUALIZED --}}
        <div class="col-lg-12">
            <div class="card shadow-none" style="height: 70vh;">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-list ico-tab"></i>Reading Full Report</span>
                    <div class="card-tools">
                        @if ($meterReader != null)
                            <a href="{{ route('readings.print-old-format-adjusted', [$period, $day, $town, $meterReader->id]) }}" title="Print All Adjusted via Old Format Bill" style="margin-right: 10px;"><i class="fas fa-print text-warning"></i></a>
                            <a href="{{ route('readings.print-new-format-adjusted', [$period, $day, $town, $meterReader->id]) }}" title="Print All Adjusted via New Format Bill" style="margin-right: 10px;"><i class="fas fa-print text-primary"></i></a>
                            <button class="btn btn-xs btn-info" data-toggle="modal" data-target="#modal-format-select-mreader" style="margin-right: 2px;"><i class="fas fa-print"></i> Print All Bills</button>
                            <a class="btn btn-xs btn-default" href="{{ route('readings.print-unbilled-by-status', [$period, $day, $town, $meterReader->id, 'STUCK-UP']) }}" title="Print All Stuck Ups" style="margin-right: 2px;"><i class="fas fa-print"></i> Stuck Up</a>
                            <a class="btn btn-xs btn-default" href="{{ route('readings.print-unbilled-by-status', [$period, $day, $town, $meterReader->id, 'NO DISPLAY']) }}" title="Print All No Display" style="margin-right: 2px;"><i class="fas fa-print"></i> No Display</a>
                            <a class="btn btn-xs btn-default" href="{{ route('readings.print-unbilled-by-status', [$period, $day, $town, $meterReader->id, 'NOT IN USE']) }}" title="Print All Not in Use" style="margin-right: 2px;"><i class="fas fa-print"></i> Not in Use</a>
                            <a class="btn btn-xs btn-default" href="{{ route('readings.print-disco-active', [$meterReader->id, $day, $period, $town]) }}" title="Print All Disco Active" style="margin-right: 2px;"><i class="fas fa-print"></i> Disco Active</a>
                            <a class="btn btn-xs btn-default" href="{{ route('readings.print-unbilled-by-status', [$period, $day, $town, $meterReader->id, 'CHANGE METER']) }}" title="Print All Change Meters" style="margin-right: 2px;"><i class="fas fa-print"></i> Change Meters</a>
                            <a class="btn btn-xs btn-default" href="{{ route('readings.print-other-unbilled-list', [$period, $day, $town, $meterReader->id]) }}" title="Print Others Unbilled" style="margin-right: 2px;"><i class="fas fa-print"></i> Others</a>
                        @else
                            <a href="{{ route('readings.print-old-format-adjusted-bapa', [$period, $bapaName]) }}" title="Print All Adjusted via Old Format Bill" style="margin-right: 10px;"><i class="fas fa-print text-warning"></i></a>
                            <a href="{{ route('readings.print-new-format-adjusted-bapa', [$period, $bapaName]) }}" title="Print All Adjusted via New Format Bill" style="margin-right: 10px;"><i class="fas fa-print text-primary"></i></a>
                        @endif
                        
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover table-head-fixed text-nowrap table-bordered">
                        <thead>
                            <th class="text-center">#</th>
                            <th class="text-center text-primary">Account #</th>
                            <th class="text-center">Sequence #</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Acct. Status</th>
                            <th class="text-center">Timestamp</th>
                            <th class="text-center">Pres</th>
                            <th class="text-center">Prev</th>
                            <th class="text-center text-success">Current <br>Kwh Used</th>
                            {{-- <th class="text-center text-info">Previous <br>Kwh Used</th> --}}
                            {{-- <th class="text-center">% <span class="text-danger">Inc</span>/<span class="text-success">Dec</span></th> --}}
                            {{-- <th class="text-center">Daily <br>Average</th> --}}
                            {{-- <th class="text-center"># of Days</th> --}}
                            <th class="text-center">Meter #</th>
                            <th class="text-center">Field <br>Findings</th>
                            <th class="text-center">Remarks</th>
                            <th class="text-center"></th>
                        </thead>
                        <tbody>
                            @php
                                $i=1;
                            @endphp
                            @foreach ($readingReport as $item)
                                @php
                                    // NUMBER OF DAYS
                                    // $noOfDays = Readings::getDaysBetweenDates($item->PrevReadingTimestamp, $item->ReadingTimestamp);

                                    // COMPUTE PERCENTAGE
                                    $currentKwh = ($item->CurrentKwh != null ? $item->CurrentKwh : (round(floatval($item->KwhUsed) - floatval($item->PrevReading), 2)));
                                    $currentKwh = floatval(trim($currentKwh));
                                    // $prevKwh = $item->PrevKwh != null ? $item->PrevKwh : 0;
                                    // $diffKwh = $currentKwh - $prevKwh;
                                    // if ($currentKwh != 0) {
                                    //     $percentage = $diffKwh/$currentKwh;
                                    // } else {
                                    //     $percentage = 0;
                                    // }
                                    // $percentage = $item->CurrentKwh != null ? round($percentage, 4) : 0;                                    

                                @endphp
                                <tr title="{{ $item->CurrentKwh != null ? '' : 'No Bill' }}">
                                    <td>{{ $i }}</td>
                                    @if ($item->AccountStatus == 'ACTIVE')
                                        <td><i class="fas {{ $item->CurrentKwh != null ? 'fa-check-circle ico-tab text-success' : 'fa-exclamation-circle ico-tab text-danger' }}"></i><a href="{{ route('serviceAccounts.show', [$item->AccountId]) }}">{{ $item->OldAccountNo }}</a></td>
                                    @else
                                        <td><i class="fas fa-info-circle ico-tab text-muted"></i><a href="{{ $item->AccountId != null ? route('serviceAccounts.show', [$item->AccountId]) : '' }}">{{ $item->OldAccountNo }}</a></td>
                                    @endif
                                    
                                    <td>{{ $item->SequenceCode }}</td>
                                    <td>{{ $item->ServiceAccountName }}</td>
                                    <td>{{ $item->AccountStatus }}</td>
                                    <td>{{ date('Y-m-d h:i:s A', strtotime($item->ReadingTimestamp )) }}</td>
                                    <td class="text-right">{{ $item->KwhUsed }}</td>
                                    <td class="text-right">{{ $item->PrevReading }}</td>
                                    @if ($item->CurrentKwh != null)
                                        <td class="{{ $item->CurrentKwh != null ? 'text-success' : 'text-danger' }} text-right">{{ $item->CurrentKwh != null ? $item->CurrentKwh : (round(floatval($item->KwhUsed) - floatval($item->PrevReading), 2)) }}</td>
                                    @else
                                        <th class="{{ $item->CurrentKwh != null ? 'text-success' : 'text-danger' }} text-right">{{ $item->CurrentKwh != null ? $item->CurrentKwh : (round(floatval($item->KwhUsed) - floatval($item->PrevReading), 2)) }}</th>
                                    @endif
                                    {{-- <td class="text-right text-info">{{ $item->PrevKwh != null ? $item->PrevKwh : '0' }}</td> --}}
                                    {{-- @if ($item->CurrentKwh != null)
                                        <td class="text-right {{ floatval($percentage) < 0 ? 'text-success' : 'text-danger' }}"><i class="float-left fas {{ floatval($percentage) < 0 ? 'fa-caret-down' : 'fa-caret-up' }}"></i>{{ $item->CurrentKwh != null ? ($percentage * 100) . '%' : '-' }}</td>
                                    @else
                                        <td class="text-right">-</td>
                                    @endif    --}}
                                    {{-- <td class="text-right">{{ $item->CurrentKwh != null ? round(floatval($item->CurrentKwh) / floatval($noOfDays), 2) : '-' }}</td>                                  --}}
                                    {{-- <td class="text-right">{{ $noOfDays }}</td> --}}
                                    <td class="text-right">{{ $item->MeterNumber }}</td>
                                    <td>{{ $item->FieldStatus }}</td>
                                    <td>{{ $item->Notes }}</td>
                                    <td class="text-right">
                                        @if ($item->CurrentKwh == null && $item->AccountStatus == 'ACTIVE')
                                            {{-- <a href="{{ route('bills.zero-readings-view', [$item->id]) }}"><i class="fas fa-pen"></i></a> --}}
                                            <button class="btn btn-link text-primary btn-xs float-right" 
                                                acctno="{{ $item->OldAccountNo }}" 
                                                reading="{{ $item->KwhUsed }}" 
                                                prevreading="{{ $item->PrevReading }}" 
                                                fieldfindings="{{ $item->FieldStatus }}" 
                                                remarks="{{ $item->Notes }}" 
                                                meternumber="{{ $item->MeterNumber }}"
                                                consumername="{{ $item->ServiceAccountName }}" 
                                                multiplier="{{ $item->Multiplier }}" 
                                                onclick="updateReading(this, '{{ $item->id }}', '{{ $item->AccountId }}')">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                        @else
                                            @if ($item->AccountNumber == null)
                                            <button class="btn btn-link text-danger btn-xs float-right" onclick="mergeCapture('{{ $item->id }}', '{{ $item->KwhUsed }}', '{{ $item->FieldStatus }}', '{{ $item->Notes }}')">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            @else
                                                @if ($item->FieldStatus != null && ($item->FieldStatus=='CHANGE METER' | $item->FieldStatus=='RESET'))
                                                <a href="{{ $item->BillId != null ? route('bills.adjust-bill', [$item->BillId]) : '' }}" class="btn btn-xs btn-link" title="Adjust Reading"><i class="fas fa-pen"></i></a>
                                                @else
                                                    
                                                @endif
                                            @endif
                                        @endif                                        
                                    </td>
                                </tr>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

{{-- MODAL SEARCH AND MERGE FOR CAPTURED --}}
<div class="modal fade" id="modal-captured-reading" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Charge This Reading To a Consumer Account</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- SEARCH --}}
                <div class="row">                    
                    <div class="form-group col-lg-4">
                        <input class="form-control" id="old-account-captured" autocomplete="off" data-inputmask="'alias': 'phonebe'" maxlength="12" value="{{ env('APP_AREA_CODE') }}" style="font-size: 1.5em; color: #b91400; font-weight: bold;">
                    </div>                   
                    <div class="form-group col-lg-7">
                        <input type="text" id="search-global-captured" placeholder="Account Number, Account Name, or Meter Number" class="form-control" autofocus="true">
                    </div>
                    <div class="form-group col-lg-1">
                        <button id="search-consumer-global-captured" class="btn btn-primary"><i class="fas fa-search-dollar"></i></button>
                    </div>
                </div>

                {{-- RESULTS --}}
                <p class="text-muted"><i id="count">Results</i></p>
                <table class="table table-sm table-hover" id="res-table-captured">
                    <thead>
                        <th>Account Number</th>
                        <th>Account Name</th>
                        <th>Address</th>
                        <th>Meter No</th>
                        <th>Status</th>
                        <th></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="bill-consumer"><i class="fas fa-check-circle ico-tab-mini"></i>Bill</button>
            </div> --}}
        </div>
    </div>
</div>

{{-- MODAL UPDATE READING FOR ZERO READINGS --}}
<div class="modal fade" id="modal-update-reading" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4><strong id="consumer-name"></strong></h4>
                    <span class="text-muted">Account No: <strong id="acct-no"></strong></span>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        {{-- DETAILS --}}
                        <div class="card">
                            <div class="card-body table-responsive px-0">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td>Meter Number</td>
                                        <th id="meter-number"></th>
                                    </tr>
                                    <tr>
                                        <td>Field Findings</td>
                                        <th id="field-findings"></th>
                                    </tr>
                                    <tr>
                                        <td>Remarks</td>
                                        <th id="remarks"></th>
                                    </tr>
                                    <tr>
                                        <td>Multiplier</td>
                                        <th id="multiplier"></th>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        {{-- PREV READINGS --}}
                        <div class="card" style="height: 35vh;">
                            <div class="card-header">
                                <span class="card-title">Previous Months Reading</span>
                            </div>
                            <div class="card-body table-responsive px-0">
                                <table class="table table-sm table-hover" id="prev-reading-tbl">
                                    <thead>
                                        <th>Billing Month</th>
                                        <th>Reading</th>
                                        <th>Meter Reader</th>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <span>Perform Billing Here</span>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="" class="col-sm-5">Previous Reading</label>
                                    <input type="number" step="any" id="PreviousReading" class="form-control col-sm-7" placeholder="PreviousReading" readonly>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-5">Preset Reading</label>
                                    <input type="number" step="any" id="PresentReading" class="form-control col-sm-7" placeholder="PresentReading">
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-5">Input Kwh Used</label>
                                    <input type="number" step="any" id="KwhAdjusted" class="form-control col-sm-7" placeholder="Input Kwh Used">
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-5">Input Demand Used</label>
                                    <input type="number" step="any" id="DemandAdjusted" class="form-control col-sm-7" placeholder="Input Demand">
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-5">Total Kwh Used (w/ Multiplier)</label>
                                    <input id="TotalKwhused" class="form-control col-sm-7" placeholder="Total Kwh Used" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="bill-consumer"><i class="fas fa-check-circle ico-tab-mini"></i>Bill</button>
            </div>
        </div>
    </div>
</div>

{{-- SELECT FORMAT FOR PRINT BILL MODAL --}}
<div class="modal fade" id="modal-format-select-mreader" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Select Bill Format</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <button id="print-new-format-mreader" class="btn btn-primary float-right"><i class="fas fa-print ico-tab"></i>New Bill Format</button>
                    </div>
                    <div class="col-lg-6">
                        <button id="print-old-format-mreader" class="btn btn-warning"><i class="fas fa-print ico-tab"></i>Old Bill Format</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        var prev = 0
        var pres = 0
        var kwhused = 0
        var multiplier = 0
        var acctid = ""

        // CAPTURED
        var readid = ""
        var remarks = ""
        var fieldFindings = ""
        $(document).ready(function() {

            $('#KwhAdjusted').keyup(function(e) {
                computeKwh()
            })
            
            $('#PresentReading').keyup(function(e) {
                var dif = parseFloat(this.value) - prev
                $('#KwhAdjusted').val(parseFloat(dif).toFixed(2)).change()
                computeKwh()
            })

            $('#modal-update-reading').on('shown.bs.modal', function () {
                $('#KwhAdjusted').focus();
            })

            $('#bill-consumer').on('click', function(e) {
                if (jQuery.isEmptyObject($('#KwhAdjusted').val())) {
                    Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Kwh invalid!',
                            showConfirmButton: false,
                        })
                } else {
                    transact(acctid, $('#PresentReading').val(), prev, $('#DemandAdjusted').val())
                }                
            })

            /** 
             *  CAPTURED
             */
            $('#modal-captured-reading').on('shown.bs.modal', function () {
                $('#old-account-captured').focus();
            })

            $("#old-account-captured").inputmask({
                mask: '99-99999-999',
                placeholder: '',
                showMaskOnHover: false,
                showMaskOnFocus: false,
                onBeforePaste: function (pastedValue, opts) {
                    var processedValue = pastedValue;

                    return processedValue;
                }
            });

            $("#old-account-captured").on('keyup', function(event) {
                if (this.value.length > 7) {
                    performSearchCaptured(this.value)
                }
            })

            $('#search-global-captured').on('keyup', function() {
                if (this.value.length > 5) {
                    performSearchCaptured(this.value)
                }
            })

            $('#print-new-format-mreader').on('click', function() {
                window.location.href = "{{ url('/readings/print-bulk-new-format-mreader') }}" + "/{{ $period }}/{{ $day }}/{{ $town }}/{{ $meterReader != null ? $meterReader->id : '' }}"
            })

            $('#print-old-format-mreader').on('click', function() {
                window.location.href = "{{ url('/readings/print-bulk-old-format-mreader') }}" + "/{{ $period }}/{{ $day }}/{{ $town }}/{{ $meterReader != null ? $meterReader->id : '' }}"
            })
        })

        function computeKwh() {
            kwhused = parseFloat($('#KwhAdjusted').val()).toFixed(2)
            var totalKwh = kwhused * multiplier
            $('#TotalKwhused').val(parseFloat(totalKwh).toFixed(2)).change()
        }

        function updateReading(el, readid, accountid, consumername) {
            prev = 0
            pres = 0
            kwhused = 0
            multiplier = 0
            acctid = ""

            // show modal 
            $('#modal-update-reading').modal('show')
            $('#KwhAdjusted').focus()

            prev = parseFloat($(el).attr('prevreading'))
            pres = parseFloat($(el).attr('reading'))
            multiplier = parseFloat($(el).attr('multiplier'))
            acctid = accountid

            // set params
            $('#consumer-name').text($(el).attr('consumername'))
            $('#acct-no').text($(el).attr('acctno'))
            $('#PresentReading').val($(el).attr('reading'))
            $('#PreviousReading').val($(el).attr('prevreading'))
            $('#meter-number').text($(el).attr('meternumber'))
            $('#field-findings').text($(el).attr('fieldfindings'))
            $('#remarks').text($(el).attr('remarks'))
            $('#multiplier').text($(el).attr('multiplier'))
            getPrevReadings(accountid)
        }

        function getPrevReadings(accountid) {
            $('#prev-reading-tbl tbody tr').remove()
            $.ajax({
                url : "{{ route('readings.get-previous-readings') }}",
                type : 'GET',
                data : {
                    AccountNumber : accountid
                },
                success : function(res) {
                    $.each(res, function(index, element) {
                        if (index == 0) {

                        } else {
                            $('#prev-reading-tbl tbody').append(addRow(res[index]['ServicePeriod'], res[index]['KwhUsed'], res[index]['name']))
                        }
                        
                    })
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error fetching previous readings',
                        icon : 'error'
                    })
                }
            })
        }   

        function addRow(period, reading, meterreader) {
            return "<tr>" + 
                    "<td>" + period + "</td>" +
                    "<th>" + reading + "</th>" +
                    "<td>" + meterreader + "</td>" +
                + "</tr>"
        }

        function transact(accountid, pres, prev, demand) {
            $.ajax({
                url : "{{ route('readings.create-manual-billing-ajax') }}",
                type : 'GET',
                data : {
                    AccountNumber : accountid,
                    KwhUsed : $('#TotalKwhused').val(),
                    ServicePeriod : "{{ $period }}",
                    PresentKwh : pres,
                    PreviousKwh : prev,
                    Demand : demand
                },
                success : function(res) {
                    if (res=='ok') {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Bill for this reading successfully created!',
                            showConfirmButton: false,
                        })
                        location.reload()
                    } else if (res=='amount negative') {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Amount invalid!',
                            showConfirmButton: false,
                        })
                    } 
                },
                error : function(err) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Error generating bill!',
                        showConfirmButton: false,
                        timer: 1800
                    })
                }
            })
        }

        /**
         * CAPTURED READINGS
         */
        function mergeCapture(id, presreading, fieldstatus, remarksx) {
            prev = 0
            pres = 0
            kwhused = 0
            multiplier = 0
            acctid = ""

            readid = id
            pres = presreading
            fieldFindings = fieldstatus
            remarks = remarksx
            $('#modal-captured-reading').modal('show')
        }

        function performSearchCaptured(regex) {
            $.ajax({
                url : '{{ route("serviceAccounts.search-for-captured") }}',
                type : 'GET',
                data : {
                    query : regex,
                },
                success : function(res) {
                    try {
                        if (jQuery.isEmptyObject(res)) {
                            $('#res-table-captured tbody tr').remove()
                        } else {
                            $('#res-table-captured tbody tr').remove()
                            $('#res-table-captured tbody').append(res)
                        }   
                    } catch (err) {
                        $('#res-table-captured tbody tr').remove()
                    }                                     
                },
                error : function(error) {
                    $('#res-table-captured tbody tr').remove()
                    // alert('Error fetching data')
                    console.log(error)
                }
            })
        }

        function proceedBilling(id) {
            // PERFORM CHECK
            $.ajax({
                url : "{{ route('readings.check-if-account-has-bill') }}",
                type : 'GET',
                data : {
                    AccountNumber : id,
                    ServicePeriod : '{{ $period }}',
                },
                success : function(res) {
                    if (res == 'ok') {
                        window.location = "{{ url('/readings/captured-readings-console') }}" + "/" + id + "/" + readid + "/{{ $day }}/{{ $bapaName != null ? $bapaName : 'mreader' }}"
                    } else {
                        Swal.fire({
                            title: 'Bill Already Exists',
                            text : 'This account already has a bill for this Billing month. Do you wish to proceed?',
                            showDenyButton: true,
                            confirmButtonText: 'Proceed',
                            denyButtonText: `Cancel`,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire('Saved!', '', 'success')
                            } else if (result.isDenied) {
                                Swal.fire('Changes are not saved', '', 'info')
                            }
                        })
                    }
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error validating account',
                        icon : 'error'
                    })
                }
            })
        }
    </script>
@endpush