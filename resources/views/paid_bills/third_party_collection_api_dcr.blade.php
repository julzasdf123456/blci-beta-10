@extends('layouts.app')

@section('content')
<p style="padding-top: 8px;"><i class="fas fa-chart-line ico-tab"></i>Third Party API DCR - <strong class="text-primary">{{ $source }}</strong></p>

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
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#dcr-summary" data-toggle="tab">
                        <i class="fas fa-list"></i>
                        DCR Summary</a></li>

                    <li class="nav-item"><a class="nav-link" href="#power-bills" data-toggle="tab">
                        <i class="fas fa-user"></i>
                        Power Bills Payments</a></li>

                    <li class="nav-item"><a class="nav-link" href="#non-power-bills" data-toggle="tab">
                        <i class="fas fa-circle"></i>
                        Non-Power Bills Payments</a></li>

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

                    <div class="tab-pane" id="non-power-bills">
                        @include('d_c_r_summary_transactions.non_power_bills')
                    </div>

                    <div class="tab-pane" id="check-payments">
                        @include('d_c_r_summary_transactions.tab_check_admin_dcr')
                    </div>  
                </div>                    
            </div>
        </div>
    </div>
</div>
@endsection