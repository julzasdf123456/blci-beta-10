@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0">For Energization</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Energization</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Applications</h3>
                  {{-- <div class="card-tools">
                    <a href="#" class="btn btn-tool btn-sm">
                      <i class="fas fa-download"></i>
                    </a>
                    <a href="#" class="btn btn-tool btn-sm">
                      <i class="fas fa-bars"></i>
                    </a>
                  </div> --}}
                </div>
                <div class="card-body table-responsive p-0">
                    @if ($serviceConnections == null)
                        <p class="text-center"><i>No Service Connection Applications with Unassigned Meters.</i></p>
                    @else
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Service Account Name</th>
                                    <th>Address</th>
                                    <th>Account Type</th>
                                    <th>Station Crew</th>
                                    <th width="10%">Pre-Energization</th>
                                    <th width="10%">Post-Energization</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($serviceConnections as $item)
                                    <tr>
                                        <td><a href="{{ route('serviceConnections.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                        <td>{{ $item->ServiceAccountName }}</td>
                                        <td>{{ ServiceConnections::getAddress($item) }}</td>
                                        <td>{{ $item->AccountType }}</td>
                                        <td>{{ $item->StationName }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('serviceConnections.print-order', [$item->id]) }}" class="{{ $item->EnergizationOrderIssued==null ? 'text-primary' : 'text-success' }}" title="Issue and Print Energization Order/Ticket"> <i class="fas fa-print"></i> Print Turn On</a>
                                            <button id="reAssign" class="btn text-muted" data-toggle="modal" data-target="#modal-default" data-id="{{ $item->id }}" fromData="{{ $item->StationName }}" style="margin-left: 10px;" title="Re-assign station and crew"> <i class="fas fa-hard-hat"></i> Re-assign Crew</button>
                                        </td>
                                        <td class="text-center">
                                            <button id="update-energization" class="btn {{ $item->Status=='Not Energized' ? 'text-danger' : 'text-primary' }}" data-toggle="modal" data-target="#modal-energization" data-id="{{ $item->id }}" title="Update Energization Order"> <i class="fas fa-clipboard-check"></i> </button>
                                        </td>
                                    </tr>
                                @endforeach                                
                            </tbody>
                        </table>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- MODAL FOR UPDATING OF CREW --}}
<div class="modal fade" id="modal-default" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Crew</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="crew-data">
                    <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">

                    <input type="hidden" name="Station" id="Station" value="" class="form-control">
                    
                    <input type="hidden" name="From" id="From" value="" class="form-control">

                    <select name="StationCrewAssigned" id="StationCrewAssigned" class="form-control">
                    @foreach ($crew as $item)
                        <option value="{{ $item->id }}">{{ $item->StationName }}</option>
                    @endforeach
                    </select>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary" id="submit">Save changes</button> --}}
                <input type="submit" value="Save changes" id="submit" class="btn btn-primary">
            </div>
        </div>
    </div>
</div>

{{-- MODAL FOR UPDATING OF ENERGIZED CONNECTIONS --}}
<div class="modal fade" id="modal-energization" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Energization Status</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="energization-update">
                    <input type="hidden" name="_token" id="csrfEnergization" value="{{ Session::token() }}">

                    <input type="hidden" name="ServiceConnectionId" id="ServiceConnectionId" value="">

                    <label for="Status">Status</label>
                    <select name="Status" id="Status" class="form-control">
                        <option value="Energized">Energization Successful</option>
                        <option value="Not Energized">Energization Unsuccessful</option>
                    </select>

                    <label for="ArrivalDate">Crew Arrival Date and Time</label>
                    <input type="text" name="ArrivalDate" id="ArrivalDate" value="" class="form-control">

                    <label for="EnergizationDate">Energization Date and Time</label>
                    <input type="text" name="EnergizationDate" id="EnergizationDate" value="" class="form-control">

                    <textarea type="text" name="Reason" id="Reason" value="" placeholder="Reason" class="form-control" style="margin-top: 8px;" rows="3"></textarea>
                
                    <p class="text-danger" id="error-message"><i>Please supply all the fields to proceed!</i></p>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submitEnergizationUpdate" value="Save changes" id="submitEnergizationUpdate" class="btn btn-primary">
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#EnergizationDate').datetimepicker({
            format: 'YYYY-MM-DD hh:mm:ss',
            useCurrent: false,
            sideBySide: true
        })

        $('#ArrivalDate').datetimepicker({
            format: 'YYYY-MM-DD hh:mm:ss',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

@push('page_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#Reason').hide();
            $('#error-message').hide();

            // FOR STATION UPDATING
            $('body').on('click', '#reAssign', function (event) {
                event.preventDefault();
                var id = $(this).data('id');
                var fromStation = $(this).attr('fromData');
                $('#Station').val(id);
                $('#From').val(fromStation);
            });

            $('body').on('click', '#submit', function (event) {
                event.preventDefault();
                var id = $('#Station').val();
                var crew = $('#StationCrewAssigned').val();
                var toStationName = $("#StationCrewAssigned option:selected").text();
                var fromStationName = $('#From').val();

                // console.log(crew + ' - ' + id);
                $.ajax({
                    url : '/service_connections/change-station-crew',
                    type : "POST",
                    data : {
                        _token : $("#csrf").val(),
                        id : id,
                        StationCrewAssigned : crew,
                        FromStationCrewName : fromStationName,
                        ToStationCrewName : toStationName,
                    },
                    // dataType : 'json',
                    success : function(data) {
                        $('#crew-data').trigger('reset');
                        $('#modal-default').modal('hide');
                        console.log(data);
                        window.location.reload();
                    }
                });
            });

            // FOR ENERGIZATION UPDATING
            $('#Status').on('change', function() {
                if ($('#Status').val()=='Energized') {
                    $('#Reason').hide();
                } else {
                    $('#Reason').show();
                }
            });

            $('body').on('click', '#update-energization', function (event) {
                event.preventDefault();
                var id = $(this).data('id');
                $('#ServiceConnectionId').val(id);
            });

            $('body').on('click', '#submitEnergizationUpdate', function (event) {
                event.preventDefault();
                var id = $('#ServiceConnectionId').val();
                var status = $('#Status').val();
                var energizationDate = $("#EnergizationDate").val();
                var arrivalDate = $("#ArrivalDate").val();
                var reason = $('#Reason').val();

                if (jQuery.isEmptyObject(arrivalDate) || jQuery.isEmptyObject(energizationDate)) {                    
                    $('#error-message').show();
                } else {
                    $('#error-message').hide();
                    // console.log(crew + ' - ' + id);
                    $.ajax({
                        url : '/service_connections/update-energization-status',
                        type : "POST",
                        data : {
                            _token : $("#csrfEnergization").val(),
                            id : id,
                            Status : status,
                            EnergizationDate : energizationDate,
                            ArrivalDate : arrivalDate,
                            Reason : reason,
                        },
                        // dataType : 'json',
                        success : function(data) {
                            $('#energization-update').trigger('reset');
                            $('#modal-energization').modal('hide');
                            console.log(data);
                            window.location.reload();
                        }
                    });
                }
            });
        });
    </script>
@endpush