@php
    use App\Models\IDGenerator;
    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
    set_time_limit(500);
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Meter Reader Efficiency Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-body">
                {!! Form::open(['route' => 'readings.efficiency-report', 'method' => 'GET']) !!}
                <div class="row">
                    <div class="form-group col-lg-2">
                        <label for="ServicePeriod">Select Month</label>
                        <select name="ServicePeriod" id="ServicePeriod" class="form-control form-control-sm">
                            @for ($i = 0; $i < count($months); $i++)
                                <option value="{{ $months[$i] }}" {{ $month!=null && $month==$months[$i] ? 'selected' : ($latestRate != null && $latestRate->ServicePeriod==$months[$i] ? 'selected' : '') }}>{{ date('F Y', strtotime($months[$i])) }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group col-lg-1">
                        <label for="">Office</label>
                        <select name="Office" id="Office" class="form-control form-control-sm">
                            <option value="All">All</option>
                            @foreach ($towns as $item)
                                <option value="{{ $item->id }}" {{ isset($_GET['Office']) && $_GET['Office']==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <label for="MeterReader">Meter Reader</label>
                        <select name="MeterReader" id="MeterReader" class="form-control form-control-sm">
                            
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="">Action</label><br>
                        {!! Form::submit('View', ['class' => 'btn btn-primary btn-sm']) !!}
                        <button class="btn btn-sm btn-warning" id="printBtnReport">Print</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card shadow-none" style="height: 70vh;">
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-bordered table-hover small">
                    <thead>
                        <tr>
                            <th class="align-middle" rowspan="4">Route<br>Code</th>
                            <th class="text-center" colspan="2">{{ strtoupper(date('F Y', strtotime($period))) }} SALES</th>
                            <th class="text-center" colspan="4">{{ strtoupper(date('F Y', strtotime($period))) }} COLLECTION</th>
                            <th colspan="2"></th>
                            <th class="text-center" colspan="2">{{ strtoupper(date('F Y', strtotime($month))) }}</th>
                            <th class="text-center" colspan="4">{{ strtoupper(date('F Y', strtotime($period))) }} SALES</th>
                            <th class="text-center" colspan="4">COLLECTION EFFICIENCY</th>
                            <th class="text-center" colspan="3">OUTSTANDING ACCOUNTS</th>
                        </tr>   
                        <tr>
                            <th></th>
                            <th></th>
                            <th colspan="2" class="text-center">PREV MONTH</th>
                            <th colspan="2" class="text-center">THIS MONTH</th>
                            <th colspan="2" class="text-center">COLLECTED ARREARS</th>
                            <th colspan="2" class="text-center">ADVANCE COLLECTION</th>
                            <th colspan="2" class="text-center">DISCONNECTED</th>
                            <th colspan="2" class="text-center">OTHER (EXCMPTD)</th>
                            <th colspan="2" rowspan="2" class="text-center">All</th> 
                            <th colspan="2" rowspan="2" class="text-center">exclude disco<br>& other ajd</th>
                            <th colspan="3" class="text-center">Uncollected</th>
                        </tr> 
                        <tr>
                            <th></th>
                            <th class="text-right">Bill Amount</th>
                            <th></th>
                            <th class="text-right">Bill Amount</th> 
                            <th></th>
                            <th class="text-right">Bill Amount</th> 
                            <th></th>
                            <th class="text-right">Bill Amount</th> 
                            <th></th>
                            <th class="text-right">Bill Amount</th> 
                            <th></th>
                            <th class="text-right">Bill Amount</th> 
                            <th></th>
                            <th class="text-right">Bill Amount</th> 
                            <th class="text-right">% Bills</th>              
                            <th class="text-right">% Amnt</th>              
                            <th class="text-right">Bill Amnt</th> 
                        </tr> 
                        <tr>    
                            <th class="text-right"># Bills</th>                    
                            <th class="text-right">Others</th>
                            <th class="text-right"># Bills</th>                  
                            <th class="text-right">Others</th> 
                            <th class="text-right"># Bills</th>                  
                            <th class="text-right">Others</th> 
                            <th class="text-right"># Bills</th>                  
                            <th class="text-right">Others</th> 
                            <th class="text-right"># Bills</th>                  
                            <th class="text-right">Others</th> 
                            <th class="text-right"># Bills</th>                  
                            <th class="text-right">Others</th> 
                            <th class="text-right"># Bills</th>                  
                            <th class="text-right">Others</th> 
                            <th class="text-right">% Bills</th>                  
                            <th class="text-right">% Amnt</th> 
                            <th class="text-right">% Bills</th>                  
                            <th class="text-right">% Amnt</th>  
                            <th class="text-right"># Bills</th>  
                            <th></th> 
                            <th></th>
                        </tr>                     
                    </thead>
                    <tbody>
                        @php
                            $totalBillSalesCount = 0;
                            $totalBillSalesAmount = 0;
                            $totalBillSalesPrevMonthCount = 0;
                            $totalBillSalesPrevMonthAmount = 0;
                            $totalBillSalesThisMonthCount = 0;
                            $totalBillSalesThisMonthAmount = 0;
                            $totalPeriodOtherSales = 0;
                            $totalArrearsCollectedCount = 0;
                            $totalArrearsCollectedAmount = 0;
                            $totalCurrentCollectedCount = 0;
                            $totalCurrentCollectedAmount = 0;
                            $totalCurrentOtherSales = 0;
                            $totalDiscoCount = 0;
                            $totalDiscoAmount = 0;
                            $totalAdjustmentCount = 0;
                            $totalAdjustmentAmount = 0;
                            $totalCollectionEffAllCount = 0;
                            $totalCollectionEffAllAmount = 0;
                            $totalCollectionEffExcludedCount = 0;
                            $totalCollectionEffExcludedAmount = 0;
                            $totalUncollectedCountPercentage = 0;
                            $totalUncollectedAmountPercentage = 0;
                            $i=0;
                        @endphp
                        @foreach ($data as $item)
                            @if ($item->AreaCode != null)
                                @php
                                    $allCollectionCount = IDGenerator::getPercentage(floatval($item->PeriodNoOfBillsCurrentMonthCollection) + floatval($item->PeriodNoOfBillsPrevMonthCollection), $item->PeriodNoOfBillsSales);
                                    $allCollectionCount = intval($item->PeriodNoOfBillsSales) ==0 ? 100 : ($allCollectionCount > 100 ? 100 : $allCollectionCount);
                                    $allCollectionAmount = IDGenerator::getPercentage(floatval($item->PeriodAmountCurrentMonthCollection) + floatval($item->PeriodAmountPrevMonthCollection), $item->PeriodBillAmountSales);
                                    // IF WAY BILL, TOMATIC 100% ANG COLLECTION AMOUNT
                                    $allCollectionAmount = intval($item->PeriodNoOfBillsSales) ==0 ? 100 : ($allCollectionAmount > 100 ? 100 : $allCollectionAmount);
                                    $excludedCollectionCount = IDGenerator::getPercentage(floatval($item->PeriodNoOfBillsCurrentMonthCollection) + floatval($item->PeriodNoOfBillsPrevMonthCollection) + floatval($item->DiscoCount) + floatval($item->AdjustmentCount), $item->PeriodNoOfBillsSales);
                                    $excludedCollectionCount = intval($item->PeriodNoOfBillsSales) ==0 ? 100 : ($excludedCollectionCount > 100 ? 100 : $excludedCollectionCount);
                                    $excludedCollectionAmount = IDGenerator::getPercentage(floatval($item->PeriodAmountCurrentMonthCollection) + floatval($item->PeriodAmountPrevMonthCollection) + floatval($item->DiscoAmount) + floatval($item->AdjustmentAmount), $item->PeriodBillAmountSales);
                                    // IF WAY BILL, TOMATIC 100% ANG COLLECTION AMOUNT
                                    $excludedCollectionAmount = intval($item->PeriodNoOfBillsSales) ==0 ? 100 : ($excludedCollectionAmount > 100 ? 100 : $excludedCollectionAmount);
                                    $uncollectedCountPercent = 100 - $excludedCollectionCount;
                                    $uncollectedCountPercent = $uncollectedCountPercent < 0 ? 0 : $uncollectedCountPercent;
                                    $uncollectedAmountPercent = 100 - $excludedCollectionAmount;
                                    // $uncollectedAmountPercent = $uncollectedAmountPercent < 0 ? 0 : $uncollectedAmountPercent;
                                @endphp
                                <tr>
                                    <th rowspan="2">{{ $item->AreaCode }}</th>
                                    <td class="text-right">{{ $item->PeriodNoOfBillsSales }}</td>
                                    <td class="text-right">{{ number_format($item->PeriodBillAmountSales, 2) }}</td>
                                    <td class="text-right">{{ $item->PeriodNoOfBillsPrevMonthCollection }}</td>
                                    <td class="text-right">{{ number_format($item->PeriodAmountPrevMonthCollection, 2) }}</td>
                                    <td class="text-right">{{ $item->PeriodNoOfBillsCurrentMonthCollection }}</td>
                                    <td class="text-right">{{ number_format($item->PeriodAmountCurrentMonthCollection, 2) }}</td>
                                    <td class="text-right">{{ $item->PeriodNoOfBillsArrearsCollected }}</td>
                                    <td class="text-right">{{ number_format($item->PeriodAmountArrearsCollected, 2) }}</td>
                                    <td class="text-right">{{ $item->CurrentNoOfBillsSales }}</td>
                                    <td class="text-right">{{ number_format($item->CurrentAmountSales, 2) }}</td>
                                    <td class="text-right text-primary" onclick="showDisconnections('{{ $item->AreaCode }}')">{{ $item->DiscoCount }}</td>
                                    <td class="text-right">{{ number_format($item->DiscoAmount, 2) }}</td>
                                    <td class="text-right text-primary" onclick="showExcemptions('{{ $item->AreaCode }}')">{{ $item->AdjustmentCount }}</td>
                                    <td class="text-right">{{ number_format($item->AdjustmentAmount, 2) }}</td>
                                    <td class="text-right">{{ $allCollectionCount > 100 ? 100 : $allCollectionCount }}%</td>
                                    <td class="text-right">{{ $allCollectionAmount }}%</td>
                                    <td class="text-right">{{ $excludedCollectionCount > 100 ? 100 : $excludedCollectionCount }}%</td>
                                    <td class="text-right">{{ $excludedCollectionAmount }}%</td>
                                    <td class="text-right">{{ round($uncollectedCountPercent, 2) }}%</td>
                                    @if ($uncollectedCountPercent <= 0)
                                        <td class="text-right">0%</td>
                                        <td class="text-right text-primary">0</td>
                                    @else
                                        <td class="text-right">{{ round($uncollectedAmountPercent, 2) }}%</td>
                                        <td class="text-right">{{ number_format(floatval($item->PeriodBillAmountSales) - (floatval($item->PeriodAmountCurrentMonthCollection) + floatval($item->PeriodAmountPrevMonthCollection) + floatval($item->AdjustmentAmount) + floatval($item->DiscoAmount)), 2) }}</td>
                                    @endif                                      
                                </tr>
                                <tr>     
                                    <td></td>   
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">{{ number_format($item->PeriodOthersSales, 2) }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">{{ number_format($item->CurrentOthersSales, 2) }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @if ($uncollectedCountPercent <= 0)
                                        <td class="text-right text-primary">0</td>
                                    @else
                                        <td onclick="showOutstanding('{{ $item->AreaCode }}')" class="text-right text-primary">{{ round((floatval($item->PeriodNoOfBillsSales)) - (floatval($item->PeriodNoOfBillsCurrentMonthCollection) + floatval($item->PeriodNoOfBillsPrevMonthCollection) + floatval($item->DiscoCount) + floatval($item->AdjustmentCount))) }}</td>
                                    @endif                                    
                                    <td></td>
                                    <td></td>
                                </tr>
                                @php
                                    $totalBillSalesCount += intval($item->PeriodNoOfBillsSales);
                                    $totalBillSalesAmount += floatval($item->PeriodBillAmountSales);
                                    $totalBillSalesPrevMonthCount += intval($item->PeriodNoOfBillsPrevMonthCollection);
                                    $totalBillSalesPrevMonthAmount += floatval($item->PeriodAmountPrevMonthCollection);
                                    $totalBillSalesThisMonthCount += intval($item->PeriodNoOfBillsCurrentMonthCollection);
                                    $totalBillSalesThisMonthAmount += floatval($item->PeriodAmountCurrentMonthCollection);
                                    $totalPeriodOtherSales += floatval($item->PeriodOthersSales);  
                                    $totalArrearsCollectedCount += intval($item->PeriodNoOfBillsArrearsCollected);
                                    $totalArrearsCollectedAmount += floatval($item->PeriodAmountArrearsCollected);  
                                    $totalCurrentCollectedCount += intval($item->CurrentNoOfBillsSales);
                                    $totalCurrentCollectedAmount += floatval($item->CurrentAmountSales);  
                                    $totalCurrentOtherSales += floatval($item->CurrentOthersSales); 
                                    $totalDiscoCount += intval($item->DiscoCount);
                                    $totalDiscoAmount += floatval($item->DiscoAmount); 
                                    $totalAdjustmentCount += intval($item->AdjustmentCount);
                                    $totalAdjustmentAmount += floatval($item->AdjustmentAmount); 
                                    $totalCollectionEffAllCount += floatval($allCollectionCount);
                                    $totalCollectionEffAllAmount += floatval($allCollectionAmount);
                                    $totalCollectionEffExcludedCount += floatval($excludedCollectionCount);
                                    $totalCollectionEffExcludedAmount += floatval($excludedCollectionAmount);
                                    $totalUncollectedCountPercentage += intval($uncollectedCountPercent);
                                    $totalUncollectedAmountPercentage += floatval($uncollectedAmountPercent); 
                                    $i++;                                 
                                @endphp
                            @endif
                            
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th rowspan="3" class="text-center">TOTAL</th>
                            <th class="text-right">{{ $totalBillSalesCount }}</th>
                            <th class="text-right">{{ number_format($totalBillSalesAmount, 2) }}</th>
                            <th class="text-right">{{ $totalBillSalesPrevMonthCount }}</th>
                            <th class="text-right">{{ number_format($totalBillSalesPrevMonthAmount, 2) }}</th>
                            <th class="text-right">{{ $totalBillSalesThisMonthCount }}</th>
                            <th class="text-right">{{ number_format($totalBillSalesThisMonthAmount, 2) }}</th>
                            <th class="text-right">{{ $totalArrearsCollectedCount }}</th>
                            <th class="text-right">{{ number_format($totalArrearsCollectedAmount, 2) }}</th>
                            <th class="text-right">{{ $totalCurrentCollectedCount }}</th>
                            <th class="text-right">{{ number_format($totalCurrentCollectedAmount, 2) }}</th>
                            <th class="text-right">{{ $totalDiscoCount }}</th>
                            <th class="text-right">{{ number_format($totalDiscoAmount, 2) }}</th>
                            <th class="text-right">{{ $totalAdjustmentCount }}</th>
                            <th class="text-right">{{ number_format($totalAdjustmentAmount, 2) }}</th>
                            <th class="text-right">{{ round(IDGenerator::getAverage($totalCollectionEffAllCount, $i), 2) }}%</th>
                            <th class="text-right">{{ round(IDGenerator::getAverage($totalCollectionEffAllAmount, $i), 2) }}%</th>
                            <th class="text-right">{{ round(IDGenerator::getAverage($totalCollectionEffExcludedCount, $i), 2) }}%</th>
                            <th class="text-right">{{ round(IDGenerator::getAverage($totalCollectionEffExcludedAmount, $i), 2) }}%</th>
                            <th class="text-right">{{ round(IDGenerator::getAverage($totalUncollectedCountPercentage, $i), 2) }}%</th>
                            <th class="text-right">{{ round(IDGenerator::getAverage($totalUncollectedAmountPercentage, $i), 2) }}%</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-right">{{ number_format($totalPeriodOtherSales, 2) }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-right">{{ number_format($totalCurrentOtherSales, 2) }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>                            
                        </tr>
                        <tr>
                            <th class="text-right"></th>
                            <th class="text-right">{{ number_format($totalBillSalesAmount, 2) }}</th>
                            <th class="text-right">{{ $totalBillSalesPrevMonthCount }}</th>
                            <th class="text-right">{{ number_format($totalBillSalesPrevMonthAmount, 2) }}</th>
                            <th class="text-right">{{ $totalBillSalesThisMonthCount }}</th>
                            <th class="text-right">{{ number_format(floatval($totalBillSalesThisMonthAmount) + floatval($totalPeriodOtherSales), 2) }}</th>
                            <th class="text-right">{{ $totalArrearsCollectedCount }}</th>
                            <th class="text-right">{{ number_format($totalArrearsCollectedAmount, 2) }}</th>
                            <th class="text-right">{{ $totalCurrentCollectedCount }}</th>
                            <th class="text-right">{{ number_format(floatval($totalCurrentCollectedAmount) + floatval($totalCurrentOtherSales), 2) }}</th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- EXCEMPTIONS --}}
<div class="modal fade" id="modal-excemptions" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="excemptions-title">Excempted Accounts</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loader-excemptions" class="spinner-border text-info gone" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <table class="table table-hover table-sm" id="table-excemptions">
                    <thead>
                        <th style="width: 30px;">#</th>
                        <th>Account No.</th>
                        <th>Service Account Name</th>
                        <th>Address</th>
                        <th>Amount Due</th>
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

{{-- DISCONNNECTED --}}
<div class="modal fade" id="modal-disconnected" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="disconnected-title">Disconnected Accounts</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loader-disconnected" class="spinner-border text-info gone" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <table class="table table-hover table-sm" id="table-disconnected">
                    <thead>
                        <th style="width: 30px;">#</th>
                        <th>Account No.</th>
                        <th>Service Account Name</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Bill Amount</th>
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

{{-- OUTSTANDING --}}
<div class="modal fade" id="modal-outstanding" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="disconnected-title">Outstanding Accounts</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loader-outstanding" class="spinner-border text-info gone" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <table class="table table-hover table-sm" id="table-outstanding">
                    <thead>
                        <th style="width: 30px;">#</th>
                        <th>Account No.</th>
                        <th>Service Account Name</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Bill Amount</th>
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

<p id="MReaderHidden" style="display: none;">{{ isset($_GET['MeterReader']) ? $_GET['MeterReader'] : '' }}</p>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            fetchMeterReaders()

            $('#Office').on('change', function() {
                fetchMeterReaders()
            })

            $('#printBtnReport').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/readings/print-efficiency-report') }}" + '/' + $('#MeterReader').val() + '/' + $('#ServicePeriod').val() + '/' + $('#Office').val() 
            })

            
        })

        function fetchMeterReaders() {
            $.ajax({
                url : '{{ route("readings.get-meter-readers") }}',
                type : 'GET',
                data : {
                    Town : $('#Office').val(),
                },
                success : function(res) {
                    $('#MeterReader option').remove()
                    if (!jQuery.isEmptyObject(res)) {
                        $.each(res, function(index, element) {
                            if ($('#MReaderHidden').text() == res[index]["MeterReader"]) {
                                $('#MeterReader').append('<option value="' + res[index]["MeterReader"] + '" selected>' + res[index]["name"] + '</option>')
                            } else {
                                $('#MeterReader').append('<option value="' + res[index]["MeterReader"] + '">' + res[index]["name"] + '</option>')
                            }                            
                        })
                    }
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error fetching meter readers',
                        icon : 'error'
                    })
                }
            })
        }

        function showExcemptions(route) {
            $('#table-excemptions tbody tr').remove()
            $('#excemptions-title').text('Account Excemptions for Route ' + route)
            $('#modal-excemptions').modal('show')   
            $('#loader-excemptions').removeClass('gone') 
            
            $.ajax({
                url : "{{ route('readings.show-excemptions-per-route') }}",
                type : 'GET',
                data : {
                    MeterReader : $('#MeterReader').val(),
                    Route : route,
                    Month : $('#ServicePeriod').val()
                },
                success : function(res) {
                    $('#table-excemptions tbody tr').remove()
                    $('#table-excemptions tbody').append(res)
                    $('#loader-excemptions').addClass('gone')
                },
                error : function(err) {
                    $('#loader-excemptions').addClass('gone')
                    Swal.fire({
                        title : 'Error getting excemptions',
                        icon : 'error'
                    })
                }
            })
        }

        function showDisconnections(route) {
            $('#table-disconnected tbody tr').remove()
            $('#disconnected-title').text('Disconnected for Route ' + route)
            $('#modal-disconnected').modal('show')    
            $('#loader-disconnected').removeClass('gone') 
            
            $.ajax({
                url : "{{ route('readings.show-disconnected-per-route') }}",
                type : 'GET',
                data : {
                    MeterReader : $('#MeterReader').val(),
                    Route : route,
                    Period : $('#ServicePeriod').val(),
                },
                success : function(res) {
                    $('#table-disconnected tbody tr').remove()
                    $('#table-disconnected tbody').append(res)
                    $('#loader-disconnected').addClass('gone') 
                },
                error : function(err) {                    
                    $('#loader-disconnected').addClass('gone') 
                    Swal.fire({
                        title : 'Error getting disconnected accounts',
                        icon : 'error'
                    })
                }
            })
        }

        function showOutstanding(route) {
            $('#table-outstanding tbody tr').remove()
            $('#outstanding-title').text('Disconnected for Route ' + route)
            $('#modal-outstanding').modal('show')  
            $('#loader-outstanding').removeClass('gone')   
            
            $.ajax({
                url : "{{ route('readings.show-outstanding-per-route') }}",
                type : 'GET',
                data : {
                    MeterReader : $('#MeterReader').val(),
                    Route : route,
                    Period : $('#ServicePeriod').val(),
                },
                success : function(res) {
                    $('#table-outstanding tbody tr').remove()
                    $('#table-outstanding tbody').append(res)
                    $('#loader-outstanding').addClass('gone')  
                },
                error : function(err) {
                    $('#loader-outstanding').addClass('gone') 
                    Swal.fire({
                        title : 'Error getting outstanding accounts',
                        icon : 'error'
                    })
                }
            })
        }
    </script>
@endpush