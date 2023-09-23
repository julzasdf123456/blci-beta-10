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
                    <h4 style="display: inline; margin-right: 15px;">Update Account Wizzard</h4>
                    <i class="text-muted">Update meter details</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Meter Information</span>
            </div>
            {!! Form::model($meters, ['route' => ['billingMeters.update', $meters->id], 'method' => 'patch']) !!}
            <div class="card-body">

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
                                    {!! Form::text('SerialNumber', $meters != null ? $meters->SerialNumber : ($meterAndTransformer==null ? null : $meterAndTransformer->MeterSerialNumber), ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
                                </div>
                            </div>

                            <!-- Sealnumber Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('SealNumber', 'Seal No:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('SealNumber', $meters != null ? $meters->SealNumber : ($meterAndTransformer==null ? null : $meterAndTransformer->MeterSealNumber), ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
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
                                    {!! Form::text('Brand', $meters != null ? $meters->Brand : ($meterAndTransformer==null ? null : $meterAndTransformer->MeterBrand), ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
                                </div>
                            </div>

                            <!-- Model Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('Model', 'Model:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('Model', $meters != null ? $meters->Model : null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
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
                                    {!! Form::select('Status', ['FUNCTIONAL' => 'FUNCTIONAL', 'DEFECTIVE' => 'DEFECTIVE'], $meters != null ? $meters->Status : null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <!-- Multiplier Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('Multiplier', 'Multiplier:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::number('Multiplier', $meters != null ? $meters->Multiplier : '1.0', ['class' => 'form-control', 'maxlength' => 10,'maxlength' => 10, 'step' => 'any']) !!}
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
                                    {!! Form::text('ConnectionDate', $meters != null ? $meters->ConnectionDate : ($serviceConnection != null ? $serviceConnection->DateTimeOfEnergization : null), ['class' => 'form-control','id'=>'ConnectionDate']) !!}
                                </div>
                            </div>

                            <!-- Datedisconnected Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('DateDisconnected', 'Disconnection Date:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('DateDisconnected', $meters != null ? $meters->DateDisconnected : null, ['class' => 'form-control','id'=>'DateDisconnected']) !!}
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
                                    {!! Form::number('InitialReading', $meters != null ? $meters->InitialReading : '0.0', ['class' => 'form-control', 'step' => 'any']) !!}
                                </div>
                            </div>

                            <!-- LatestReading Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('LatestReading', 'Latest Reading:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::number('LatestReading', $meters != null ? $meters->LatestReading : '0.0', ['class' => 'form-control', 'step' => 'any']) !!}
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
                                    {!! Form::text('LatestReadingDate', $meters != null ? $meters->LatestReadingDate : null, ['class' => 'form-control','id'=>'LatestReadingDate']) !!}
                                </div>
                            </div>

                            <!-- Datetransfered Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('DateTransfered', 'Date Transfered:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('DateTransfered', $meters != null ? $meters->DateTransfered : null, ['class' => 'form-control','id'=>'DateTransfered']) !!}
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
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('serviceAccounts.show', [$meters->ServiceAccountId]) }}" class="btn btn-default">Cancel</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection