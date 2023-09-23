@php
    use App\Models\ServiceAccounts;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h4>Abrupt Increase/Decrease Analyzer</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        {{-- FORM --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="">Town</label>
                            <select id="Town" name="Town" class="form-control form-control-sm">
                                <option value="All">All</option>
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}" {{ !isset($_GET['Town']) ? ($item->id==env('APP_AREA_CODE') ? 'selected' : '') : ($_GET['Town']==$item->id ? 'selected' : '') }}>{{ $item->Town }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Billing Month</label>
                            <select id="ServicePeriod" name="ServicePeriod" class="form-control form-control-sm">
                                @foreach ($billingMonths as $item)
                                    <option value="{{ $item->ServicePeriod }}">{{ date('F Y', strtotime($item->ServicePeriod)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Direction</label>
                            <select name="Direction" id="Direction" class="form-control form-control-sm">
                                <option value="Increase">Increase</option>
                                <option value="Decrease">Decrease</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">% Inc/Dec Threshold</label>
                            <input type="number" class="form-control form-control-sm" value="50" name="Percent" id="Percent">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Action</label><br>
                            <button id="analyze" class="btn btn-sm btn-primary"><i class="fas fa-eye ico-tab-mini"></i>Analyze</button>
                            {{-- <button id="print-btn" class="btn btn-sm btn-warning"><i class="fas fa-print ico-tab-mini"></i>Print</button> --}}
                        </div>

                        <div class="col-lg-2">
                            <div id="loader" class="spinner-border text-info float-right gone" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RESULTS --}}
        <div class="col-lg-12">
            <div class="card shadow-none" style="height: 60vh;">
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover table-bordered table-head-fixed text-nowrap" id="res-table">
                        <thead>
                            <th>#</th>
                            <th>Account No.</th>
                            <th>Consumer Name</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th class="text-right">Prev. Mo. Kwh</th>
                            <th class="text-right">Pres. Mo. Kwh</th>
                            <th class="text-right">Difference</th>
                            <th class="text-right">Percentage</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#analyze').on('click', function() {
                analyze()
            })
        })

        function analyze() {
            $('#res-table tbody tr').remove()
            $('#loader').removeClass('gone')
            $('#analyze').attr('disabled', 'disabled')
            $.ajax({
                url : "{{ route('readings.analyze-abrupt-increase-decrease') }}",
                type : 'GET',
                data : {
                    Town : $('#Town').val(),
                    ServicePeriod : $('#ServicePeriod').val(),
                    Direction : $('#Direction').val(),
                    Percent : $('#Percent').val(),
                },
                success : function(res) {
                    $('#res-table tbody').append(res)
                    $('#loader').addClass('gone')
                    $('#analyze').removeAttr('disabled')
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error analyzing data',
                        icon : 'error'
                    })
                    $('#loader').addClass('gone')
                    $('#analyze').removeAttr('disabled')
                }
            })
        }
    </script>
@endpush