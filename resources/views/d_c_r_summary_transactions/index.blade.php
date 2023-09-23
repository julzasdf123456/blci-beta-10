@php    
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>DCR Summary Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">

    @include('flash::message')

    <div class="clearfix"></div>

    <div class="row">
        {{-- FORM --}}
        <div class="col-lg-3">
            <div class="card">
                {!! Form::open(['route' => 'dCRSummaryTransactions.index', 'method' => 'GET']) !!}
                <div class="card-body">
                    <input type="hidden" value="{{ Auth::id() }}" name="Teller">
                    <!-- Day Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('Day', 'Choose Day:') !!}
                        {!! Form::text('Day', $day, ['class' => 'form-control','id'=>'Day']) !!}
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#Day').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush
                </div>
                <div class="card-footer">
                    {!! Form::submit('Go', ['class' => 'btn btn-primary']) !!}
                    <button id="printdcr" class="btn btn-warning float-right"><i class="fas fa-print ico-tab"></i>Print DCR</button>
                </div>
                {!! Form::close() !!}
            </div>

            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title">Collection Summary</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover">
                        <tr>
                            <td>Cash</td>
                            <th class="text-right">{{ $summary != null ? number_format(floatval($summary->CashTotal) + floatval($summary->CashNpbTotal), 2) : "0" }}</th>
                        </tr>
                        <tr>
                            <td>Check</td>
                            <th class="text-right">{{ $summary != null ? number_format($summary->CheckTotal, 2) : "0" }}</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- RESULTS --}}
        <div class="col-lg-9">
            <div class="card" style="80vh">
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

                        <li class="nav-item"><a class="nav-link" href="#cancelled-ors" data-toggle="tab">
                            <i class="fas fa-circle"></i>
                            Cancelled ORs</a></li>
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
                            @include('d_c_r_summary_transactions.check_payments')
                        </div>

                        <div class="tab-pane" id="cancelled-ors">
                            @include('d_c_r_summary_transactions.cancelled_ors')
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#printdcr').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/d_c_r_summary_transactions/print-dcr') }}" + "/{{ Auth::id() }}/" + $('#Day').val()
            })
        })
    </script>
@endpush

