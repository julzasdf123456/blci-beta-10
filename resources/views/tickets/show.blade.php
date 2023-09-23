@php
    use App\Models\Tickets;
    use App\Models\TicketsRepository;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <span class="badge-lg {{ $tickets->Status=="Executed" ? 'bg-success' : 'bg-warning' }}"><strong>{{ $tickets->Status }}</strong></span>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('tickets.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>
    <div class="content px-3">
        <div class="card">
            <div class="card-header">
                <span class="card-title text-muted">Ticket No: {{ $tickets->id }}</span>
                <div class="card-tools">
                    @if ($tickets->Status=="Executed")
                        <a class="btn btn-tool text-info" title="This ticket is closed because it's been already tagged as executed."><i class="fas fa-lock"></i></a>
                    @else
                        <a href="{{ route('tickets.edit', [$tickets->id]) }}" class="btn btn-tool" title="Edit this ticket"><i class="fas fa-pen"></i></a>
                        <a href="{{ route('tickets.print-ticket', [$tickets->id]) }}" class="btn btn-tool" title="Re-print Ticket Order"><i class="fas fa-print"></i></a>
                    @endif
                    

                    {!! Form::open(['route' => ['tickets.destroy', $tickets->id], 'method' => 'delete', 'style' => 'width: 30px; display: inline;']) !!}                
                    {!! Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-tool text-danger', 'onclick' => "return confirm('Are you sure you want to delete this ticket?')"]) !!}                
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-9 col-md-8">
                        <div class="row">
                            {{-- STATUS BOXES --}}
                            <div class="col-lg-3">
                                <div class="info-box {{ $tickets->created_at==null ? 'bg-light' : 'bg-success' }}">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center {{ $tickets->created_at==null ? 'text-muted' : 'text-white' }}">Filed</span>
                                        <span class="info-box-number text-center {{ $tickets->created_at==null ? 'text-muted' : 'text-white' }} mb-0">
                                            @if ($tickets->created_at==null)
                                                @if ($tickets->Status!="Executed")
                                                    <button class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-date-filed" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>
                                                @else
                                                    -
                                                @endif                                                
                                            @else
                                                {{ date('F d, Y h:i:s A', strtotime($tickets->created_at)) }}
                                                @if ($tickets->Status!="Executed")
                                                    <button class="btn btn-link btn-sm text-white" data-toggle="modal" data-target="#modal-date-filed" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>
                                                @endif
                                            @endif 
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3">
                                <div class="info-box {{ $tickets->DateTimeDownloaded==null ? 'bg-light' : 'bg-success' }}">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center {{ $tickets->DateTimeDownloaded==null ? 'text-muted' : 'text-white' }}">Sent to Lineman</span>
                                        <span class="info-box-number text-center {{ $tickets->DateTimeDownloaded==null ? 'text-muted' : 'text-white' }} mb-0">
                                            @if ($tickets->DateTimeDownloaded==null)
                                                <button class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-lineman-sent" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>
                                            @else
                                                {{ date('F d, Y h:i:s A', strtotime($tickets->DateTimeDownloaded)) }}
                                                <button class="btn btn-link btn-sm text-white" data-toggle="modal" data-target="#modal-lineman-sent" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>                                              
                                            @endif                                            
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="info-box {{ $tickets->DateTimeLinemanArrived==null ? 'bg-light' : 'bg-success' }}">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center {{ $tickets->DateTimeLinemanArrived==null ? 'text-muted' : 'text-white' }}">Lineman Site Arrival</span>
                                        <span class="info-box-number text-center {{ $tickets->DateTimeLinemanArrived==null ? 'text-muted' : 'text-white' }} mb-0">
                                            @if ($tickets->DateTimeLinemanArrived==null)
                                                <button class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-lineman-arrived" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>                                              
                                            @else                                            
                                                {{ date('F d, Y h:i:s A', strtotime($tickets->DateTimeLinemanArrived)) }}
                                                <button class="btn btn-link btn-sm text-white" data-toggle="modal" data-target="#modal-lineman-arrived" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>
                                            @endif 
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="info-box {{ $tickets->DateTimeLinemanExecuted==null ? 'bg-light' : ($tickets->Status=="Executed" ? 'bg-success' : 'bg-danger') }}">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center {{ $tickets->DateTimeLinemanExecuted==null ? 'text-muted' : 'text-white' }}">Execution</span>
                                        <span class="info-box-number text-center {{ $tickets->DateTimeLinemanExecuted==null ? 'text-muted' : 'text-white' }} mb-0">
                                            @if ($tickets->DateTimeLinemanExecuted==null)
                                                <button class="btn btn-link btn-sm" data-toggle="modal" data-target="#modal-execution" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>                                               
                                            @else
                                                {{ date('F d, Y h:i:s A', strtotime($tickets->DateTimeLinemanExecuted)) }}
                                                <button class="btn btn-link btn-sm text-white" data-toggle="modal" data-target="#modal-execution" data-id="{{ $tickets->id }}"><i class="fas fa-pen"></i></button>                                             
                                            @endif 
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <h3>{{ $tickets->ConsumerName }}</h3>
                                <div class="row">
                                    <span class="col-lg-4 text-muted"><i class="fas fa-location-arrow ico-tab"></i>{{ Tickets::getAddress($tickets) }}</span><br>
                                    <span class="col-lg-4 text-muted text-center" title="Account Number"><i class="fas fa-user-circle ico-tab"></i><a href="{{ $tickets->AccountNumber != null ? route('serviceAccounts.show', [$tickets->AccountNumber]) : '' }}">{{ $tickets->AccountNumber }}</a></span><br>
                                    <span class="col-lg-4 text-muted text-right" title="Contact Number"><i class="fas fa-phone-alt ico-tab"></i>{{ $tickets->ContactNumber }}</span><br>
                                </div>
                                    
                                <div class="divider"></div>

                                <span class="text-muted"><i class="fas fa-info-circle ico-tab"></i>{{ $tickets->TicketType }}</span><br>
                                @php
                                    $parent = TicketsRepository::where('id', $tickets->ParentTicket)->first();
                                @endphp
                                <h4><span class="text-muted">{{ $parent != null ? $parent->Name . ' - ' : '' }}</span>{{ $tickets->Ticket }}</h4>
                                <p>{{ $tickets->Reason }}</p>

                                {{-- TABS --}}
                                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#details" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#history" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">History</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#photos" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Photos/Images</a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="custom-content-below-tabContent">
                                    {{-- Details Tab --}}
                                    <div class="tab-pane fade active show" id="details" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <table class="table table-hover table-borderless">
                                                    <tr>
                                                        <th>Reported By :</th>
                                                        <td>{{ $tickets->ReportedBy }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Crew Assigned :</th>
                                                        <td>{{ $tickets->StationName }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>OR Number :</th>
                                                        <td>{{ $tickets->ORNumber }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>OR Date :</th>
                                                        <td>{{ $tickets->ORDate }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Neighbor 1 :</th>
                                                        <td>{{ $tickets->Neighbor1 }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Neighbor 2 :</th>
                                                        <td>{{ $tickets->Neighbor2 }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Notes :</th>
                                                        <td>{{ $tickets->Notes }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Office :</th>
                                                        <td>{{ $tickets->Office }}</td>
                                                    </tr>
                                                </table>             
                                            </div>                            
                                        </div>
                                    </div>

                                    {{-- History Tab --}}
                                    <div class="tab-pane fade active" id="history" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <th>Ticket</th>
                                                        <th>Date Filed</th>
                                                        <th>Status</th>
                                                        <th width="8%"></th>
                                                    </thead>
                                                    <tbody>
                                                        @if ($history != null)
                                                            @foreach ($history as $item)
                                                                @php
                                                                    $parentHist = TicketsRepository::where('id', $item->ParentTicket)->first();
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $parentHist != null ? $parentHist->Name . ' - ' : '' }}{{ $item->Ticket }}</td>
                                                                    <td>{{ date('F d, Y, h:i A', strtotime($item->created_at)) }}</td>
                                                                    <td>{{ $item->Status }}</td>
                                                                    <td class="text-right">
                                                                        <a href="{{ route('tickets.show', [$item->id]) }}" title="Expand ticket"><i class="fas fa-share"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Photos Tab --}}
                                    <div class="tab-pane fade active" id="photos" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                        <div class="row">
                                            @foreach ($images as $item)
                                                <div class="col-lg-3">
                                                    <img src="data:image/png;base64,{{ $item->Signature }}" alt="Photo" style="width: 100%;">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <span class="card-title">Ticket Logs</span>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>           
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="timeline timeline-inverse">
                                    @if ($ticketLogs == null)
                                        <p><i>No ticketLogs recorded</i></p>
                                    @else
                                        @php
                                            $i = 0;
                                        @endphp
                                        @foreach ($ticketLogs as $item)
                                            <div class="time-label" style="font-size: .9em !important;">
                                                <span class="{{ $i==0 ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $item->Log }}
                                                </span>
                                            </div>
                                            <div>
                                            <i class="fas fa-info-circle bg-primary"></i>
                
                                            <div class="timeline-item">
                                                    <span class="time"><i class="far fa-clock"></i> {{ date('h:i A', strtotime($item->created_at)) }}</span>
                
                                                    <p class="timeline-header"  style="font-size: .9em !important;"><a href="">{{ date('F d, Y', strtotime($item->created_at)) }}</a> by {{ $item->name }}</p>
                
                                                    @if ($item->LogDetails != null)
                                                        <div class="timeline-body" style="font-size: .9em !important;">
                                                            <?= $item->LogDetails ?>
                                                        </div>
                                                    @endif
                                                    
                                                </div>
                                            </div>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- MODAL FOR UPDATING CREATED AT --}}
<div class="modal fade" id="modal-date-filed" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Date Filed</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="created-data">
                    <div class="form-group">
                        <label for="created_at">Filed At</label>
                        <input type="text" name="created_at" id="created_at" value="{{ $tickets->created_at }}" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update-created-at">Save changes</button>
                {{-- <input type="submit" value="Save changes" id="submit" class="btn btn-primary"> --}}
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#created_at').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

{{-- MODAL FOR UPDATING SENT TO LINEMAN --}}
<div class="modal fade" id="modal-lineman-sent" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Lineman Receiving Date</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="created-data">
                    <div class="form-group">
                        <label for="DateTimeDownloaded">Sent to Lineman at</label>
                        <input type="text" name="DateTimeDownloaded" id="DateTimeDownloaded" value="{{ $tickets->DateTimeDownloaded }}" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update-lenman-sent">Save changes</button>
                {{-- <input type="submit" value="Save changes" id="submit" class="btn btn-primary"> --}}
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#DateTimeDownloaded').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

{{-- MODAL FOR UPDATING LINEMAN ARRIVED AT SITE --}}
<div class="modal fade" id="modal-lineman-arrived" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Lineman Arrived on Site</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="created-data">
                    <div class="form-group">
                        <label for="DateTimeLinemanArrived">Lineman Arrived at</label>
                        <input type="text" name="DateTimeLinemanArrived" id="DateTimeLinemanArrived" value="{{ $tickets->DateTimeLinemanArrived }}" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update-lenman-arrival">Save changes</button>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#DateTimeLinemanArrived').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

{{-- MODAL FOR UPDATING OF EXECUTION --}}
<div class="modal fade" id="modal-execution" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Field Assessment</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="created-data">
                    <div class="form-group">
                        <label>Assessment</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Status" id="executed" value="Executed">
                            <label class="form-check-label" for="executed">Executed</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Status" id="executed" value="Not Executed">
                            <label class="form-check-label" for="not-executed">Not Executed</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="DateTimeLinemanExecuted">Date of Execution</label>
                        <input type="text" name="DateTimeLinemanExecuted" id="DateTimeLinemanExecuted" value="{{ $tickets->DateTimeLinemanExecuted }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="Notes">Notes/Field Remarks</label>                        
                        <textarea type="text" class="form-control" name="Notes" id="Notes"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update-execution">Save changes</button>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#DateTimeLinemanExecuted').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // UPDATE CREATED AT
            $('#update-created-at').on('click', function() {
                $.ajax({
                    url : '/tickets/update-date-filed',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        created_at : $('#created_at').val(),
                        id : "{{ $tickets->id }}",
                    },
                    success : function(response) {
                        location.reload();
                    },
                    error : function(error) {
                        alert(error);
                    }
                })
            });

            // UPDATE SENT TO LINEMAN
            $('#update-lenman-sent').on('click', function() {
                $.ajax({
                    url : '/tickets/update-date-downloaded',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        DateTimeDownloaded : $('#DateTimeDownloaded').val(),
                        id : "{{ $tickets->id }}",
                    },
                    success : function(response) {
                        location.reload();
                    },
                    error : function(error) {
                        alert(error);
                    }
                })
            });

            // UPDATE LINEMAN ARRIVED ON SITE
            $('#update-lenman-arrival').on('click', function() {
                $.ajax({
                    url : '/tickets/update-date-arrival',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        DateTimeLinemanArrived : $('#DateTimeLinemanArrived').val(),
                        id : "{{ $tickets->id }}",
                    },
                    success : function(response) {
                        location.reload();
                    },
                    error : function(error) {
                        alert(error);
                    }
                })
            });

            // UPDATE EXECUTION STATUS
            $('#update-execution').on('click', function() {
                $.ajax({
                    url : '/tickets/update-execution',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        DateTimeLinemanExecuted : $('#DateTimeLinemanExecuted').val(),
                        id : "{{ $tickets->id }}",
                        Status : $('input[name="Status"]:checked').val(),
                        Notes : $('#Notes').val(),
                    },
                    success : function(response) {
                        location.reload();
                    },
                    error : function(error) {
                        alert(error);
                    }
                })
            });
        })
    </script>
@endpush
