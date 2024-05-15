@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 style="display: inline; margin-right: 15px;">Detailed Summary Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-sm-12">
        <div class="card shadow-none">
            {!! Form::open(['route' => 'serviceConnections.detailed-summary', 'method' => 'GET']) !!}
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-2">
                        <label for="Status">Status</label>
                        <select name="Status" id="Status" class="form-control form-control-sm">
                            <option value="All">All</option>
                            @foreach ($statuses as $item)
                                <option value="{{ $item->Status }}" {{ isset($_GET['Status']) && $_GET['Status']==$item->Status ? 'selected' : '' }}>{{ $item->Status }}</option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="form-group col-lg-2">
                        {!! Form::label('From', 'From') !!}
                        {!! Form::text('From', isset($_GET['From']) ? $_GET['From'] : '', ['class' => 'form-control form-control-sm','id'=>'From', 'required' => true]) !!}
                    </div>
                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#From').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true,
                                icons :{
                                    next : 'fas fa-caret-right',
                                    previous : 'fas fa-caret-left'
                                }
                            })
                        </script>
                    @endpush
    
                    <div class="form-group col-lg-2">
                        {!! Form::label('To', 'To') !!}
                        {!! Form::text('To', isset($_GET['To']) ? $_GET['To'] : '', ['class' => 'form-control form-control-sm','id'=>'To', 'required' => true]) !!}
                    </div>
                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#To').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true,
                                icons :{
                                    next : 'fas fa-caret-right',
                                    previous : 'fas fa-caret-left'
                                }
                            })
                        </script>
                    @endpush

                    <div class="form-group col-lg-2">
                        <label for="">Action</label><br>
                        <button type="submit" class="btn btn-sm btn-default" title="Show Results"><i class="fas fa-check-circle ico-tab-mini"></i>View</button>
                        <button id="download" class="btn btn-sm btn-primary" title="Download in Excel File"><i class="fas fa-file-download ico-tab-mini"></i>Download .xlsx</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    {{-- RESULTS --}}
    <div class="col-sm-12">
        <div class="card shadow-none" style="height: 70vh;">
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover table-bordered">
                    <thead>
                        <th style="width: 35px;">#</th>
                        <th>Svc. No</th>
                        <th>Applicant Name</th>
                        <th>Address</th>
                        <th>Application Type</th>
                        <th>Status</th>
                        <th>Application Date</th>
                        <th>Inspector</th>
                        <th>Inspection Date</th>
                        <th>Date of Energization</th>
                        <th>Meter No.</th>
                        <th>Date Installed</th>
                        <th>Crew</th>
                        <th>Remarks/Notes</th>
                    </thead>
                    <tbody>
                        @php
                            $i=1;
                        @endphp
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $i }}</td>
                                <td><a href="{{ route('serviceConnections.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ ServiceConnections::getAddress($item) }}</td>
                                <td>{{ $item->AccountApplicationType }}</td>
                                <td>{{ $item->Status }}</td>
                                <td>{{ $item->DateOfApplication != null ? date('M d, Y', strtotime($item->DateOfApplication)) : '-' }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->DateOfVerification != null ? date('M d, Y', strtotime($item->DateOfVerification)) : '-' }}</td>
                                <td>{{ $item->DateTimeOfEnergization != null ? date('M d, Y', strtotime($item->DateTimeOfEnergization)) : '-' }}</td>
                                <td>{{ $item->MeterSerialNumber }}</td>
                                <td>{{ $item->DateTimeOfEnergization != null ? date('M d, Y', strtotime($item->DateTimeOfEnergization)) : '-' }}</td>
                                <td>{{ $item->StationName }}</td>
                                <td>{{ $item->Notes }}</td>
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
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
            $('#download').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/service_connections/download-detailed-summary') }}" + "/" + $('#Status').val() + "/" + $('#From').val() + "/" + $('#To').val()
            })
        })
    </script>
@endpush