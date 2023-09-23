@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 style="display: inline; margin-right: 15px;">Material Request Issuance Voucher</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- FORM --}}
    <div class="col-sm-12">
        <div class="card shadow-none">
            {!! Form::open(['route' => 'serviceConnections.mriv', 'method' => 'GET']) !!}
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-2">
                        <label for="Town">Town</label>
                        <select name="Town" id="Town" class="form-control form-control-sm">
                            <option value="All">All</option>
                            @foreach ($towns as $item)
                                <option value="{{ $item->id }}" {{ isset($_GET['Town']) && $_GET['Town']==$item->id ? 'selected' : (env('APP_AREA_CODE')==$item->id ? 'selected' : '') }}>{{ $item->Town }}</option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="form-group col-lg-2">
                        {!! Form::label('From', 'Payment Date From') !!}
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
                        {!! Form::label('To', 'Payment Date To') !!}
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
                        <button type="submit" class="btn btn-sm btn-primary" title="Show Results"><i class="fas fa-check-circle"></i> View</button>
                        <button id="print" class="btn btn-sm btn-warning" title="Print"><i class="fas fa-print"></i> Print</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    {{-- RESULTS --}}
    <div class="col-sm-12">
        <div class="card shadow-none" style="height: 75vh;">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm table-bordered">
                    <thead>
                        <th style="width: 30px;">#</th>
                        <th>Consumer Name</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th class="text-right">Turn On No</th>
                        <th>Inspection Date</th>
                        <th>Payment Date</th>
                        <th class="text-right">Length (in mtrs)</th>
                    </thead>
                    <tbody>
                        @php
                            $i=1;
                        @endphp
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $i }}</td>
                                <td><a href="{{ route('serviceConnections.show', [$item->ConsumerId]) }}">{{ strtoupper($item->ServiceAccountName) }}</a></td>
                                <td>{{ strtoupper(ServiceConnections::getAddress($item)) }}</td>
                                <td>{{ $item->Status }}</td>
                                <td class="text-right">{{ $item->ConsumerId }}</td>
                                <td>{{ date('M d, Y', strtotime($item->DateOfVerification)) }}</td>
                                <td>{{ date('M d, Y', strtotime($item->ORDate)) }}</td>
                                <td class="text-right">{{ $item->SDWLengthAsInstalled }} meters</td>
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
            $('#print').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/service_connections/print-mriv') }}" + "/" + $("#Town").val() + "/" + $("#From").val() + "/" + $("#To").val()
            })
        })
    </script>
@endpush