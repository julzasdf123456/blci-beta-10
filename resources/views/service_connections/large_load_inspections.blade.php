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
                    <h4 class="m-0">Large Load Inspections</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Large Load Inspections</li>
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
                                    <th>Load Category</th>
                                    <th width="8%" class="text-center">Long Span</th>
                                    <th width="8%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($serviceConnections as $item)
                                    <tr>
                                        <td><a href="{{ route('serviceConnections.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                        <td>{{ $item->ServiceAccountName }}</td>
                                        <td>{{ ServiceConnections::getAddress($item) }}</td>
                                        <td>{{ $item->LoadCategory }}</td>
                                        <td class="text-center"><?= $item->LongSpan=="Yes" ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-muted"></i>' ?></td>
                                        <td>
                                            <button id="update" class="btn btn-sm text-success" data-toggle="modal" data-target="#modal-default" data-id="{{ $item->id }}" svcid="{{ $item->id }}" style="margin-left: 10px;" title="Update Inspection"> <i class="fas fa-clipboard-check"></i> </button>
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

{{-- MODAL FOR UPDATING INSPECTIONS --}}
<div class="modal fade" id="modal-default" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Inspection</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="inspection-data">
                    <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">

                    <input type="hidden" class="form-control" name="ServiceConnectionId" id="ServiceConnectionId" value="">

                    <div class="form-group">
                        <label>Options</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Options" value="Transformer Only">
                            <label class="form-check-label">Transformer Installation Only</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Options" value="Underbuilt Only">
                            <label class="form-check-label">Underbuilt Construction Only</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Assessment</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Assessment" value="Approved" checked>
                            <label class="form-check-label">Approved</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Assessment" value="For Re-Inspection">
                            <label class="form-check-label">For Re-Inspection</label>
                        </div>
                    </div>

                    <!-- Accounttype Field -->
                    <div class="form-group">
                        <label>Application Establishment Type</label>
                        <div class="form-check">
                            <div class="radio-group">
                                @if ($accountTypes != null)
                                    @foreach ($accountTypes as $item)
                                    <div class="form-check">
                                        <input id="{{ $item->id }}" class="form-check-input" type="radio" name="AccountType" value="{{ $item->id }}">
                                        <label  for="{{ $item->id }}" class="form-check-label">{{ $item->AccountType }}</label>
                                    </div>
                                    @endforeach
                                @endif
                            </div> 
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="EnergizationDate">Date and Time of Inspection</label>
                        <input type="text" name="DateOfInspection" id="DateOfInspection" value="" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Notes/Field Remarks</label>                        
                        <input type="text" class="form-control" name="Notes" id="Notes">
                    </div>
                    <p class="text-danger" id="error-message"><i>Please supply all the fields to proceed!</i></p>
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
@push('page_scripts')
    <script type="text/javascript">
        $('#DateOfInspection').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush
@endsection

@push('page_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#error-message').hide();
            $('body').on('click', '#update', function() {
                $('#ServiceConnectionId').val($(this).attr('svcid'));
            });

            $('body').on('click', '#submit', function (event) {
                event.preventDefault();
                var svcId = $('#ServiceConnectionId').val();
                var assessment = $('input[name="Assessment"]:checked').val();
                var inspectionDate = $("#DateOfInspection").val();
                var notes = $('#Notes').val();
                var options = $('input[name="Options"]:checked').val();
                var accountType = $('input[name="AccountType"]:checked').val();

                if (jQuery.isEmptyObject(inspectionDate) || jQuery.isEmptyObject(assessment)) {                    
                    $('#error-message').show();
                } else {
                    $('#error-message').hide();
                    // console.log(crew + ' - ' + id);
                    $.ajax({
                        url : '/service_connections/large-load-inspection-update',
                        type : "POST",
                        data : {
                            _token : $("#csrf").val(),
                            ServiceConnectionId : svcId,
                            Assessment : assessment,
                            DateOfInspection : inspectionDate,
                            AccountType : accountType,
                            Notes : notes,
                            Options : options,
                        },
                        // dataType : 'json',
                        success : function(data) {
                            $('#inspection-data').trigger('reset');
                            $('#modal-default').modal('hide');
                            // console.log(data);
                            window.location.reload();
                        }
                    });
                }
            });
        });
    </script>
@endpush