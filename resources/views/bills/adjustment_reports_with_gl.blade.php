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
                    <h4>Adjustment Reports with GL Codes</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            {{-- HEADER --}}
            <div class="card shadow-none">
                <div class="card-body ">
                    {!! Form::open(['route' => 'bills.adjustment-reports-with-gl', 'method' => 'GET']) !!}
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="">Area</label>
                            <select id="Area" name="Area" class="form-control form-control-sm">
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}" {{ isset($_GET['Area']) && $_GET['Area']==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="">Billing Month</label>
                            <select id="ServicePeriod" name="ServicePeriod" class="form-control form-control-sm">
                                @for ($i = 0; $i < count($months); $i++)
                                    <option value="{{ $months[$i] }}" {{ isset($_GET['ServicePeriod']) && $months[$i]==$_GET['ServicePeriod'] ? 'selected' : '' }}>{{ date('F Y', strtotime($months[$i])) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Action</label><br>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye ico-tab-mini"></i>View</button>
                            <button id="print-btn" class="btn btn-sm btn-warning"><i class="fas fa-print ico-tab-mini"></i>Print</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

            {{-- DATA DISPLAY --}}
            <div class="card shadow-none">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#gl-summary" data-toggle="tab">
                            <i class="fas fa-list"></i>
                            Adjustment Comparison Summary</a></li>
    
                        <li class="nav-item"><a class="nav-link" href="#detailed" data-toggle="tab">
                            <i class="fas fa-user"></i>
                            Detailed Data</a></li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content">
                        <div class="tab-pane active" id="gl-summary">
                            @include('bills.tab_adjustments_gl_summary')
                        </div>
    
                        <div class="tab-pane" id="detailed">
                            @include('bills.tab_adjustments_details')
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
            $('#print-btn').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('bills/print-adjustment-report') }}" + "/" + encodeURIComponent($('#Type').val()) + "/" + $('#ServicePeriod').val()
            })
        })
    </script>
@endpush