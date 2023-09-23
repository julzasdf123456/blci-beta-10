<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Town Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Town', 'Town:') !!}
    {!! Form::text('Town', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Barangay Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Barangay', 'Barangay:') !!}
    {!! Form::text('Barangay', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Purok Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Purok', 'Purok:') !!}
    {!! Form::text('Purok', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Areacode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AreaCode', 'Areacode:') !!}
    {!! Form::text('AreaCode', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Sequencecode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SequenceCode', 'Sequencecode:') !!}
    {!! Form::text('SequenceCode', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Meterreader Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeterReader', 'Meterreader:') !!}
    {!! Form::text('MeterReader', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Serviceconnectionid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceConnectionId', 'Serviceconnectionid:') !!}
    {!! Form::text('ServiceConnectionId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Relocationdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RelocationDate', 'Relocationdate:') !!}
    {!! Form::text('RelocationDate', null, ['class' => 'form-control','id'=>'RelocationDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#RelocationDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush