
<!-- Type Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Type', 'Type:') !!}
    {!! Form::text('Type', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Newmeternumber Field -->
<div class="form-group col-sm-12">
    {!! Form::label('NewMeterNumber', 'New Meter Number:') !!}
    {!! Form::text('NewMeterNumber', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmeterbrand Field -->
<div class="form-group col-sm-12">
    {!! Form::label('NewMeterBrand', 'New Meter Brand:') !!}
    {!! Form::text('NewMeterBrand', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmetersize Field -->
<div class="form-group col-sm-12">
    {!! Form::label('NewMeterSize', 'New Meter Size:') !!}
    {!! Form::text('NewMeterSize', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmetertype Field -->
<div class="form-group col-sm-12">
    {!! Form::label('NewMeterType', 'New Meter Type:') !!}
    {!! Form::text('NewMeterType', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmeteramperes Field -->
<div class="form-group col-sm-12">
    {!! Form::label('NewMeterAmperes', 'New Meter Ampere Rating:') !!}
    {!! Form::text('NewMeterAmperes', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmeterinitialreading Field -->
<div class="form-group col-sm-12">
    {!! Form::label('NewMeterInitialReading', 'Initial Reading:') !!}
    {!! Form::number('NewMeterInitialReading', null, ['class' => 'form-control']) !!}
</div>

<!-- Newmeterlinetoneutral Field -->
<div class="form-group col-sm-12">
    {!! Form::label('NewMeterLineToNeutral', 'Line to Neutral Voltage:') !!}
    {!! Form::text('NewMeterLineToNeutral', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmeterlinetoground Field -->
<div class="form-group col-sm-12">
    {!! Form::label('NewMeterLineToGround', 'Line to Ground Voltage:') !!}
    {!! Form::text('NewMeterLineToGround', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmeterneutraltoground Field -->
<div class="form-group col-sm-12">
    {!! Form::label('NewMeterNeutralToGround', 'Neutral to Ground Voltage:') !!}
    {!! Form::text('NewMeterNeutralToGround', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Dateinstalled Field -->
<div class="form-group col-sm-12">
    {!! Form::label('DateInstalled', 'Date Installed:') !!}
    {!! Form::text('DateInstalled', null, ['class' => 'form-control','id'=>'DateInstalled']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateInstalled').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Newmetermultiplier Field -->
<div class="form-group col-sm-12">
    {!! Form::label('NewMeterMultiplier', 'Multiplier:') !!}
    {!! Form::text('NewMeterMultiplier', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Transfomercapacity Field -->
<div class="form-group col-sm-12">
    {!! Form::label('TransfomerCapacity', 'Transformer Load Capacity:') !!}
    {!! Form::text('TransfomerCapacity', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Transformerid Field -->
<div class="form-group col-sm-12">
    {!! Form::label('TransformerID', 'Transformer ID:') !!}
    {!! Form::text('TransformerID', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Poleid Field -->
<div class="form-group col-sm-12">
    {!! Form::label('PoleID', 'Pole ID:') !!}
    {!! Form::text('PoleID', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Ctserialnumber Field -->
<div class="form-group col-sm-12">
    {!! Form::label('CTSerialNumber', 'CT Serial Number:') !!}
    {!! Form::text('CTSerialNumber', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Newmeterremarks Field -->
<div class="form-group col-sm-12">
    {!! Form::label('NewMeterRemarks', 'Meter Remarks:') !!}
    {!! Form::text('NewMeterRemarks', null, ['class' => 'form-control','maxlength' => 2500,'maxlength' => 2500]) !!}
</div>

<!-- Oldmeternumber Field -->
<div class="form-group col-sm-12">
    {!! Form::label('OldMeterNumber', 'Old Meter Number:') !!}
    {!! Form::text('OldMeterNumber', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Oldmeterbrand Field -->
<div class="form-group col-sm-12">
    {!! Form::label('OldMeterBrand', 'Old Meter Brand:') !!}
    {!! Form::text('OldMeterBrand', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Oldmetersize Field -->
<div class="form-group col-sm-12">
    {!! Form::label('OldMeterSize', 'Old Meter Size:') !!}
    {!! Form::text('OldMeterSize', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Oldmetertype Field -->
<div class="form-group col-sm-12">
    {!! Form::label('OldMeterType', 'Old Meter Type:') !!}
    {!! Form::text('OldMeterType', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Dateremoved Field -->
<div class="form-group col-sm-12">
    {!! Form::label('DateRemoved', 'Date Removed:') !!}
    {!! Form::text('DateRemoved', null, ['class' => 'form-control','id'=>'DateRemoved']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateRemoved').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Reasonforchanging Field -->
<div class="form-group col-sm-12">
    {!! Form::label('ReasonForChanging', 'Reason for Changing:') !!}
    {!! Form::text('ReasonForChanging', null, ['class' => 'form-control','maxlength' => 1500,'maxlength' => 1500]) !!}
</div>

<!-- Oldmetermultiplier Field -->
<div class="form-group col-sm-12">
    {!! Form::label('OldMeterMultiplier', 'Old Meter Multiplier:') !!}
    {!! Form::text('OldMeterMultiplier', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Oldmeterremarks Field -->
<div class="form-group col-sm-12">
    {!! Form::label('OldMeterRemarks', 'Old Meter Remarks:') !!}
    {!! Form::text('OldMeterRemarks', null, ['class' => 'form-control','maxlength' => 2500,'maxlength' => 2500]) !!}
</div>
