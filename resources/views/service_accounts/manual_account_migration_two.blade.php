@php
    use App\Models\ServiceConnections;
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Account Migration Wizzard</h4>
                    <i class="text-muted">Step 2. Import meter details and assess computation</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-3 col-md-4">
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Consumer Info</span>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover table-borderless table-sm">
                    <tr>
                        <th><i class="fas fa-user-circle ico-tab"></i>{{ $serviceAccount->ServiceAccountName }}</th>
                    </tr>
                    <tr>
                        <td><i class="fas fa-map-marker-alt ico-tab"></i>{{ ServiceConnections::getAddress($serviceAccount) }}</td>
                    </tr>
                    <tr>
                        <td title="Account Number Format: New Account Number (Old/Legacy Account Number)"><i class="fas fa-user-alt ico-tab"></i>{{ $serviceAccount->id }} ({{ $serviceAccount->OldAccountNo }})</td>
                    </tr>
                    <tr>
                        <td title="Area Code"><i class="fas fa-hashtag ico-tab"></i>{{ $serviceAccount->AreaCode }}</td>
                    </tr>
                    <tr>
                        <td title="Sequence Number"><i class="fas fa-hashtag ico-tab"></i>{{ $serviceAccount->SequenceCode }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-9 col-md-8">
        <div class="card">
            <div class="card-header">
                <span class="card-title"><strong>Step 2. </strong>Meter Information</span>
            </div>
            {!! Form::open(['route' => 'serviceAccounts.store-meters-manual']) !!}
            <div class="card-body">
                {{-- HIDDEN FIELDS --}}
                <input type="hidden" value="{{ $serviceAccount->id }}" name="ServiceAccountId">

                <div class="row">
                    {{-- Serial Number --}}
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Serialnumber Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('SerialNumber', 'Serial No:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('SerialNumber', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100, 'autofocus' => 'true']) !!}
                                </div>
                            </div>

                            <!-- Sealnumber Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('SealNumber', 'Seal No:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('SealNumber', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    {{-- BRAND --}}
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Brand Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('Brand', 'Brand:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('Brand', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
                                </div>
                            </div>

                            <!-- Model Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('Model', 'Model:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('Model', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    {{-- STATUS --}}
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Status Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('Status', 'Meter Status:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::select('Status', ['FUNCTIONAL' => 'FUNCTIONAL', 'DEFECTIVE' => 'DEFECTIVE'], null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <!-- Multiplier Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('Multiplier', 'Multiplier:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::number('Multiplier', '1.0', ['class' => 'form-control', 'maxlength' => 10,'maxlength' => 10, 'step' => 'any']) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    {{-- CONNECTION DATE --}}
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Connectiondate Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('ConnectionDate', 'Connection Date:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('ConnectionDate', null, ['class' => 'form-control','id'=>'ConnectionDate']) !!}
                                </div>
                            </div>

                            <!-- Datedisconnected Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('DateDisconnected', 'Disconnection Date:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('DateDisconnected', null, ['class' => 'form-control','id'=>'DateDisconnected']) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    {{-- READINGS --}}
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- InitialReading Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('InitialReading', 'Initial Reading:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::number('InitialReading', '0.0', ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            </div>

                            <!-- LatestReading Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('LatestReading', 'Latest Reading:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::number('LatestReading', '0.0', ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    {{-- TRANSFER --}}
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Latestreadingdate Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('LatestReadingDate', 'Latest Reading Date:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('LatestReadingDate', null, ['class' => 'form-control','id'=>'LatestReadingDate']) !!}
                                </div>
                            </div>

                            <!-- Datetransfered Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('DateTransfered', 'Date Transfered:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('DateTransfered', null, ['class' => 'form-control','id'=>'DateTransfered']) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#ConnectionDate').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#LatestReadingDate').datetimepicker({
                                format: 'YYYY-MM-DD HH:mm:ss',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#DateDisconnected').datetimepicker({
                                format: 'YYYY-MM-DD HH:mm:ss',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#DateTransfered').datetimepicker({
                                format: 'YYYY-MM-DD HH:mm:ss',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush
                </div>

            </div>
            <div class="card-footer">
                {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection