@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 style="display: inline; margin-right: 15px;">Applications Report</h4>
                <i class="text-muted">Generates data containing all filed applications on a specified date range</i>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="row">
        {{-- PARAMS --}}
        <div class="col-lg-3 col-md-4">
            <div class="card card-primary card-outline">
                {!! Form::open(['route' => 'serviceConnections.download-applications-report']) !!}
                <div class="card-header">
                    <span class="card-title">Config</span>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        {!! Form::label('From', 'From') !!}
                        {!! Form::text('From', null, ['class' => 'form-control','id'=>'From']) !!}
                    </div>
                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#From').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    <div class="form-group">
                        {!! Form::label('To', 'To') !!}
                        {!! Form::text('To', null, ['class' => 'form-control','id'=>'To']) !!}
                    </div>
                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#To').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    <div class="form-group">
                        {!! Form::label('Office', 'Office') !!}
                        <select name="Office" id="Office" class="form-control">
                            <option value="All">All</option>
                            @foreach ($towns as $item)
                                <option value="{{ $item->id }}">{{ $item->Town }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button id="show-results" class="btn btn-link" title="Show Results"><i class="fas fa-check-circle"></i></button>
                    <button type="submit" class="btn btn-link text-success" title="Download in Excel File"><i class="fas fa-file-download"></i></button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        
        {{-- CONTENT --}}
        <div class="col-lg-9 col-md-8">
            <div class="card">
                <div class="card-body table-responsive px-0">
                    <table id="content-table" class="table table-hover">
                        <thead>
                            <th width="4%"></th>
                            <th>Svc. No.</th>
                            <th>Applicant Name</th>
                            <th>Address</th>
                            <th>Office</th>
                            <th>Application Date</th>
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

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#show-results').click(function(e) {
                e.preventDefault()
                
                $.ajax({
                    url : '{{ route("serviceConnections.fetch-applications-report") }}',
                    type : 'GET',
                    data : {
                        From : $('#From').val(),
                        To : $('#To').val(),
                        Office : $('#Office').val()
                    },
                    success : function(res) {
                        $('#content-table tbody tr').remove()
                        $('#content-table tbody').append(res)
                    },
                    error : function(err) {
                        alert('An error occured while fetching data. See console for details!')
                    }
                })
            })
        })
    </script>
@endpush