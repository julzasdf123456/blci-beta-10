@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Daily Monitoring</h4>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="row">
        <div class="col-lg-2 col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <span class="card-title">Pick Date</span>
                </div>
                <div class="card-body">
                    <div id="target" style="position:relative" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" id="daypicker" data-toggle="datetimepicker" data-target="#target" autocomplete="off"/>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="col-lg-5 col-md-4">
            <div class="card">
                <div class="card-header border-0">
                    <span class="card-title" id="applications-title">Applications</span>
                </div>

                <div class="card-body table-responsive px-0">
                    <table id="applications-table" class="table table-hover">
                        <thead>
                            <th width="5%"></th>
                            <th>Svc. No.</th>
                            <th>Applicant Name</th>
                            <th>Address</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-md-4">
            <div class="card">
                <div class="card-header border-0">
                    <span class="card-title" id="energized-title">Energized</span>
                </div>

                <div class="card-body table-responsive px-0">
                    <table id="energized-table" class="table table-hover">
                        <thead>
                            <th width="5%"></th>
                            <th>Svc. No.</th>
                            <th>Applicant Name</th>
                            <th>Address</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" />
@endpush

@push('page_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script>
<script type="text/javascript">
    // INITIALIZE DATE PICKER
    $(document).ready(function() {
        $("#target").datetimepicker({
            format: 'YYYY-MM-DD',
            defaultDate: new Date(),
            inline : true,
            sideBySide : true,
        });
        $("#target").on('change.datetimepicker', function() {
            // applications
            $.ajax({
                url : '{{ route("serviceConnections.fetch-daily-monitor-applications-data") }}',
                type : 'GET',
                data : {
                    DateOfApplication : $('#daypicker').val(),
                },
                success : function(res) {
                    $('#applications-table tbody tr').remove()
                    $('#applications-table tbody').append(res)
                },
                error : function(err) {
                    alert('An error occurred while fetching data. See console for details!')
                }
            })

            // energized
            $.ajax({
                url : '{{ route("serviceConnections.fetch-daily-monitor-energized-data") }}',
                type : 'GET',
                data : {
                    DateOfEnergization : $('#daypicker').val(),
                },
                success : function(res) {
                    $('#energized-table tbody tr').remove()
                    $('#energized-table tbody').append(res)
                },
                error : function(err) {
                    alert('An error occurred while fetching data. See console for details!')
                }
            })
        })
    })

    // function addTableRow(svcNo, name)
    
</script>
@endpush  