@extends('layouts.app')

@section('content')
<p style="padding-top: 8px;"><i class="fas fa-chart-line ico-tab"></i>Third Party DCR - <strong class="text-primary">{{ $source }}</strong></p>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none p-0">
            <div class="card-body px-4 py-1">
                <div class="row">
                    <div class="col-lg-2">
                        <span>Collector</span><br>
                        <p><strong>{{ $source }}</strong></p>
                    </div>

                    <div class="col-lg-2">
                        <span>Payment Date</span><br>
                        <p><strong>{{ date('F d, Y', strtotime($date)) }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RESULTS --}}
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#dcr-summary" data-toggle="tab">
                        <i class="fas fa-list"></i>
                        DCR Summary</a></li>

                    <li class="nav-item"><a class="nav-link" href="#power-bills" data-toggle="tab">
                        <i class="fas fa-user"></i>
                        Power Bills Payments</a></li>

                    <li class="nav-item"><a class="nav-link" href="#check-payments" data-toggle="tab">
                        <i class="fas fa-circle"></i>
                        Check Payments</a></li>
                </ul>
            </div>
            <div class="card-body p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="dcr-summary">
                        @include('d_c_r_summary_transactions.dcr_summary')
                    </div>

                    <div class="tab-pane" id="power-bills">
                        @include('d_c_r_summary_transactions.power_bills')
                    </div>

                    <div class="tab-pane" id="check-payments">
                        @include('d_c_r_summary_transactions.tab_check_admin_dcr')
                    </div>  
                </div>                    
            </div>
        </div>
    </div>

    <div class="col-lg-2">
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title">Summary</span>
            </div>
            <div class="card-body">
                @php
                    $powerBillsTotal = 0;
                    // $nonPowerBillsTotal = 0;
                    $checkTotal = 0;
                    $dcrTotal = 0;
                @endphp
                {{-- POWER BILLS --}}
                @foreach ($powerBills as $item) 
                    @php
                        $powerBillsTotal = $powerBillsTotal + floatval($item->CashPaid);
                    @endphp   
                @endforeach  
                
                {{-- NON POWER BILLS --}}
                {{-- @foreach ($nonPowerBills as $item)  
                    @php  
                        $nonPowerBillsTotal = $nonPowerBillsTotal + floatval($item->Total);          
                    @endphp   
                @endforeach   --}}

                {{-- CHECK PAYMENTS --}}
                @if ($checkPayments != null)
                    @foreach ($checkPayments as $item)   
                        @php
                            $checkTotal += floatval($item->Amount);
                        @endphp
                    @endforeach     
                @endif  

                {{-- DCR TOTAL --}}
                @foreach ($data as $item)
                    @php
                        $dcrTotal += floatval($item->Amount);
                    @endphp
                @endforeach
                @php
                    $overAllTotal = $powerBillsTotal /*+ $nonPowerBillsTotal*/ + $checkTotal;

                    // DIFFERENCE
                    $dif = round($overAllTotal - $dcrTotal, 2);
                @endphp
                <p class="text-right text-muted" style="margin: 0; padding: 0;">Cash Payments Total</p>
                <h3 class='text-right text-info'>{{ number_format($powerBillsTotal /* + $nonPowerBillsTotal*/, 2) }}</h3>
                <p class="text-right text-muted" style="margin: 0; padding: 0;">Check Payments Total</p>
                <h3 class='text-right text-info'>{{ number_format($checkTotal, 2) }}</h3>
                <div class="divider"></div>
                <p class="text-right text-muted" style="margin: 0; padding: 0;">Collection Total</p>
                <h1 class='text-right text-success'>{{ number_format($overAllTotal, 2) }}</h1>

                @if ($dif == 0)
                
                @else
                    <div class="divider"></div>
                    <p class="text-right text-muted" style="margin: 0; padding: 0;">DCR Difference Detected</p>
                    <h3 class='text-right text-danger'>{{ number_format($dif, 2) }}</h3>
                    <button class="btn btn-sm btn-danger float-right" id="fixbtn">Fix</button>                  
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#fixbtn').on('click', function() {
                $.ajax({
                    url : '{{ route("paidBills.fix-third-party-dcr") }}',
                    type : 'GET',
                    data : {
                        Figure : "{{ $dif }}",
                        PostingDate : "{{ $postingDate }}",
                        Date : "{{ $date }}",
                        Source : "{{ $source }}",
                        SeriesNo : "{{ $seriesNo }}",
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        Swal.fire({
                            icon : 'error',
                            text : 'Error fixing DCR Difference'
                        })
                    }
                })
            })
        })
    </script>
@endpush