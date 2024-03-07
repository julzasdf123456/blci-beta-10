@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 style="display: inline; margin-right: 15px;">Energization Report</h4>
                <i class="text-muted">Generates data containing all successfully energized applications on a specified date range</i>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="row">
        {{-- PARAMS --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                {!! Form::open(['route' => 'serviceConnections.download-energization-report']) !!}
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-lg-2">
                            {!! Form::label('From', 'From') !!}
                            {!! Form::text('From', isset($_GET['From']) ? $_GET['From'] : '', ['class' => 'form-control','id'=>'From']) !!}
                        </div>
                        @push('page_scripts')
                            <script type="text/javascript">
                                $('#From').datetimepicker({
                                    format: 'YYYY-MM-DD',
                                    useCurrent: true,
                                    sideBySide: true,
                                    icons : {
                                        previous : 'fas fa-caret-left',
                                        next : 'fas fa-caret-right',
                                    }
                                })
                            </script>
                        @endpush

                        <div class="form-group col-lg-2">
                            {!! Form::label('To', 'To') !!}
                            {!! Form::text('To', isset($_GET['To']) ? $_GET['To'] : '', ['class' => 'form-control','id'=>'To']) !!}
                        </div>
                        @push('page_scripts')
                            <script type="text/javascript">
                                $('#To').datetimepicker({
                                    format: 'YYYY-MM-DD',
                                    useCurrent: true,
                                    sideBySide: true,
                                    icons : {
                                        previous : 'fas fa-caret-left',
                                        next : 'fas fa-caret-right',
                                    }
                                })
                            </script>
                        @endpush

                        <div class="form-group col-lg-2">
                            {!! Form::label('Office', 'Office') !!}
                            <select name="Office" id="Office" class="form-control">
                                <option value="All">All</option>
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}">{{ $item->Town }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Actions</label>
                            <br>
                            <button id="show-results" class="btn btn-default" title="Show Results"><i class="fas fa-check-circle ico-tab-mini"></i>Show</button>
                            <button type="submit" class="btn btn-primary" title="Download in Excel File"><i class="fas fa-file-download ico-tab-mini"></i>Download .xlsx</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        
        {{-- CONTENT --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body table-responsive px-0">
                    <table id="content-table" class="table table-hover table-sm">
                        <thead>
                            <th width="4%"></th>
                            <th>Svc. No.</th>
                            <th>Applicant Name</th>
                            <th>Address</th>
                            <th>Office</th>
                            <th>Energization Date</th>
                            <th>Meter No</th>                            
                            <th>Remarks</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="card-footer"></div>
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
                    url : '{{ route("serviceConnections.fetch-energization-report") }}',
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