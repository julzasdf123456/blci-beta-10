<!-- Serviceconnectionid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceConnectionId', 'Serviceconnectionid:') !!}
    {!! Form::text('ServiceConnectionId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Type', 'Type:') !!}
    {!! Form::text('Type', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Newmeternumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewMeterNumber', 'Newmeternumber:') !!}
    {!! Form::text('NewMeterNumber', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmeterbrand Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewMeterBrand', 'Newmeterbrand:') !!}
    {!! Form::text('NewMeterBrand', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmetersize Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewMeterSize', 'Newmetersize:') !!}
    {!! Form::text('NewMeterSize', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmetertype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewMeterType', 'Newmetertype:') !!}
    {!! Form::text('NewMeterType', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmeteramperes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewMeterAmperes', 'Newmeteramperes:') !!}
    {!! Form::text('NewMeterAmperes', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmeterinitialreading Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewMeterInitialReading', 'Newmeterinitialreading:') !!}
    {!! Form::number('NewMeterInitialReading', null, ['class' => 'form-control']) !!}
</div>

<!-- Newmeterlinetoneutral Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewMeterLineToNeutral', 'Newmeterlinetoneutral:') !!}
    {!! Form::text('NewMeterLineToNeutral', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmeterlinetoground Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewMeterLineToGround', 'Newmeterlinetoground:') !!}
    {!! Form::text('NewMeterLineToGround', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmeterneutraltoground Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewMeterNeutralToGround', 'Newmeterneutraltoground:') !!}
    {!! Form::text('NewMeterNeutralToGround', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Dateinstalled Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateInstalled', 'Dateinstalled:') !!}
    {!! Form::text('DateInstalled', null, ['class' => 'form-control','id'=>'DateInstalled']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateInstalled').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Newmetermultiplier Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewMeterMultiplier', 'Newmetermultiplier:') !!}
    {!! Form::text('NewMeterMultiplier', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Transfomercapacity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransfomerCapacity', 'Transfomercapacity:') !!}
    {!! Form::text('TransfomerCapacity', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Transformerid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransformerID', 'Transformerid:') !!}
    {!! Form::text('TransformerID', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Poleid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PoleID', 'Poleid:') !!}
    {!! Form::text('PoleID', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Ctserialnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CTSerialNumber', 'Ctserialnumber:') !!}
    {!! Form::text('CTSerialNumber', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmeterremarks Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewMeterRemarks', 'Newmeterremarks:') !!}
    {!! Form::text('NewMeterRemarks', null, ['class' => 'form-control','maxlength' => 2500,'maxlength' => 2500]) !!}
</div>

<!-- Oldmeternumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OldMeterNumber', 'Oldmeternumber:') !!}
    {!! Form::text('OldMeterNumber', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Oldmeterbrand Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OldMeterBrand', 'Oldmeterbrand:') !!}
    {!! Form::text('OldMeterBrand', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Oldmetersize Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OldMeterSize', 'Oldmetersize:') !!}
    {!! Form::text('OldMeterSize', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Oldmetertype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OldMeterType', 'Oldmetertype:') !!}
    {!! Form::text('OldMeterType', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Dateremoved Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateRemoved', 'Dateremoved:') !!}
    {!! Form::text('DateRemoved', null, ['class' => 'form-control','id'=>'DateRemoved']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateRemoved').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Reasonforchanging Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ReasonForChanging', 'Reasonforchanging:') !!}
    {!! Form::text('ReasonForChanging', null, ['class' => 'form-control','maxlength' => 1500,'maxlength' => 1500]) !!}
</div>

<!-- Oldmetermultiplier Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OldMeterMultiplier', 'Oldmetermultiplier:') !!}
    {!! Form::text('OldMeterMultiplier', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Oldmeterremarks Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OldMeterRemarks', 'Oldmeterremarks:') !!}
    {!! Form::text('OldMeterRemarks', null, ['class' => 'form-control','maxlength' => 2500,'maxlength' => 2500]) !!}
</div>

<!-- Installedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('InstalledBy', 'Installedby:') !!}
    {!! Form::text('InstalledBy', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Checkedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CheckedBy', 'Checkedby:') !!}
    {!! Form::text('CheckedBy', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Witness Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Witness', 'Witness:') !!}
    {!! Form::text('Witness', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Blcirepresentative Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BLCIRepresentative', 'Blcirepresentative:') !!}
    {!! Form::text('BLCIRepresentative', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Approvedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ApprovedBy', 'Approvedby:') !!}
    {!! Form::text('ApprovedBy', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Removedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RemovedBy', 'Removedby:') !!}
    {!! Form::text('RemovedBy', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Customersignature Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CustomerSignature', 'Customersignature:') !!}
    {!! Form::text('CustomerSignature', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Witnesssignature Field -->
<div class="form-group col-sm-6">
    {!! Form::label('WitnessSignature', 'Witnesssignature:') !!}
    {!! Form::text('WitnessSignature', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Installedbysignature Field -->
<div class="form-group col-sm-6">
    {!! Form::label('InstalledBySignature', 'Installedbysignature:') !!}
    {!! Form::text('InstalledBySignature', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Approvedbysignature Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ApprovedBySignature', 'Approvedbysignature:') !!}
    {!! Form::text('ApprovedBySignature', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Checkedbysignature Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CheckedBySignature', 'Checkedbysignature:') !!}
    {!! Form::text('CheckedBySignature', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Blcirepresentativesignature Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BLCIRepresentativeSignature', 'Blcirepresentativesignature:') !!}
    {!! Form::text('BLCIRepresentativeSignature', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>