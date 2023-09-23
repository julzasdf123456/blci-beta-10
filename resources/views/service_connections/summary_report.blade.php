@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 style="display: inline; margin-right: 15px;">Service Connection Status Summary Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- FORM --}}
    <div class="col-sm-12">
        <div class="card shadow-none">
            {!! Form::open(['route' => 'serviceConnections.summary-report', 'method' => 'GET']) !!}
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-2">
                        <label for="Town">Town</label>
                        <select name="Town" id="Town" class="form-control form-control-sm">
                            <option value="All">All</option>
                            @foreach ($towns as $item)
                                <option value="{{ $item->id }}" {{ isset($_GET['Town']) && $_GET['Town']==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
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
                                sideBySide: true
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
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    <div class="form-group col-lg-2">
                        <label for="">Action</label><br>
                        <button type="submit" class="btn btn-sm btn-primary" title="Show Results"><i class="fas fa-check-circle"></i></button>
                        {{-- <button id="download" class="btn btn-sm btn-success" title="Download in Excel File"><i class="fas fa-file-download"></i></button> --}}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    {{-- RESULTS --}}
    <div class="col-sm-12">
        <div class="card shadow-none">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-bordered">
                    <thead>
                        <th>Items</th>
                        <th>No. of Consumers</th>
                        <th>Remarks</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Total Applications</td>
                            <td>{{ $data != null ? $data->TotalApplications : 0 }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Staking and Inspection</td>
                            <td>{{ $data != null ? $data->ForStaking : 0 }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>For Payment</td>
                            <td>{{ $data != null ? $data->ForPayment : 0 }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>For Meter Assigning</td>
                            <td>{{ $data != null ? $data->ForMeterAssigning : 0 }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>For Energization</td>
                            <td>{{ $data != null ? $data->ForEnergization : 0 }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Energized</td>
                            <td>{{ $data != null ? $data->Energized : 0 }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection