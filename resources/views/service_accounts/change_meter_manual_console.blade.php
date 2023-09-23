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
                    <h4 style="display: inline; margin-right: 15px;">Chang Meter Wizzard</h4>
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

        {{-- METER INFO --}}
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Current Meter Info</span>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body table-responsive">
                @if ($activeMeter != null)
                    <table class="table table-hover table-borderless table-sm">
                        <tr>
                            <td>Brand</td>
                            <td>{{ $activeMeter->Brand }}</td>
                        </tr>
                        <tr>
                            <td>Serial No</td>
                            <td>{{ $activeMeter->SerialNumber }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{{ $activeMeter->Status }}</td>
                        </tr>
                        <tr>
                            <td>Multiplier</td>
                            <td>{{ $activeMeter->Multiplier }}</td>
                        </tr>
                        <tr>
                            <td>Connection Date</td>
                            <td>{{ $activeMeter->ConnectionDate != null ? date('F d, Y', strtotime($activeMeter->ConnectionDate)) : '' }}</td>
                        </tr>
                    </table>                    
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-9 col-md-8">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Change Meter Information</span>
            </div>
            {!! Form::open(['route' => 'serviceAccounts.store-change-meter-manual']) !!}
            <div class="card-body">
                {{-- HIDDEN FIELDS --}}
                <input type="hidden" value="{{ $serviceAccount->id }}" name="ServiceAccountId">

                <div class="row">
                    <!-- Pullout Reading Field -->
                    <div class="col-lg-2 col-md-2">
                        {!! Form::label('PullOutReading', 'Pull Out Reading:') !!}
                    </div>

                    <div class="col-lg-4 col-md-4">
                        <div class="input-group">
                            {!! Form::number('PullOutReading', null, ['class' => 'form-control', 'maxlength' => 100, 'step' => 'any', 'maxlength' => 100, 'autofocus' => 'true']) !!}
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

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
                                    {!! Form::text('SerialNumber', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
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

                    {{-- READINGS --}}
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

                            <!-- InitialReading Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('InitialReading', 'Initial Reading:') !!}
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="input-group">
                                    {!! Form::number('InitialReading', '0.0', ['class' => 'form-control', 'step' => 'any']) !!}
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
                </div>

            </div>
            <div class="card-footer">
                {!! Form::submit('Finish', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection