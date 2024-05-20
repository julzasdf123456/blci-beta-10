<!-- Serviceconnectionid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceConnectionId', 'Serviceconnectionid:') !!}
    {!! Form::text('ServiceConnectionId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Typeofservice Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TypeOfService', 'Typeofservice:') !!}
    {!! Form::text('TypeOfService', null, ['class' => 'form-control', 'maxlength' => 300, 'maxlength' => 300]) !!}
</div>

<!-- Metersealnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeterSealNumber', 'Metersealnumber:') !!}
    {!! Form::text('MeterSealNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Isleadseal Field -->
<div class="form-group col-sm-6">
    {!! Form::label('IsLeadSeal', 'Isleadseal:') !!}
    {!! Form::text('IsLeadSeal', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Meterstatus Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeterStatus', 'Meterstatus:') !!}
    {!! Form::text('MeterStatus', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Meternumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeterNumber', 'Meternumber:') !!}
    {!! Form::text('MeterNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Multiplier Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Multiplier', 'Multiplier:') !!}
    {!! Form::number('Multiplier', null, ['class' => 'form-control']) !!}
</div>

<!-- Metertype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeterType', 'Metertype:') !!}
    {!! Form::text('MeterType', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Meterbrand Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeterBrand', 'Meterbrand:') !!}
    {!! Form::text('MeterBrand', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>

<!-- Servicedate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceDate', 'Servicedate:') !!}
    {!! Form::text('ServiceDate', null, ['class' => 'form-control','id'=>'ServiceDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServiceDate').datepicker()
    </script>
@endpush

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Privateelectrician Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PrivateElectrician', 'Privateelectrician:') !!}
    {!! Form::text('PrivateElectrician', null, ['class' => 'form-control', 'maxlength' => 300, 'maxlength' => 300]) !!}
</div>

<!-- Linelength Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LineLength', 'Linelength:') !!}
    {!! Form::text('LineLength', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Conductortype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConductorType', 'Conductortype:') !!}
    {!! Form::text('ConductorType', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Conductorsize Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConductorSize', 'Conductorsize:') !!}
    {!! Form::text('ConductorSize', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Conductorunit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConductorUnit', 'Conductorunit:') !!}
    {!! Form::text('ConductorUnit', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>