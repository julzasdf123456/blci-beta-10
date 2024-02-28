<!-- Housenumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('HouseNumber', 'Housenumber:') !!}
    {!! Form::text('HouseNumber', null, ['class' => 'form-control', 'maxlength' => 60, 'maxlength' => 60]) !!}
</div>

<!-- Consumername Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerName', 'Consumername:') !!}
    {!! Form::text('ConsumerName', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- Oldaccountno Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OldAccountNo', 'Oldaccountno:') !!}
    {!! Form::text('OldAccountNo', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Newmeternumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NewMeterNumber', 'Newmeternumber:') !!}
    {!! Form::text('NewMeterNumber', null, ['class' => 'form-control', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Readingmonth Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ReadingMonth', 'Readingmonth:') !!}
    {!! Form::text('ReadingMonth', null, ['class' => 'form-control','id'=>'ReadingMonth']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ReadingMonth').datepicker()
    </script>
@endpush

<!-- Serviceperiod Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServicePeriod', 'Serviceperiod:') !!}
    {!! Form::text('ServicePeriod', null, ['class' => 'form-control','id'=>'ServicePeriod']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServicePeriod').datepicker()
    </script>
@endpush

<!-- Lastreading Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LastReading', 'Lastreading:') !!}
    {!! Form::number('LastReading', null, ['class' => 'form-control']) !!}
</div>

<!-- Oldmeternumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OldMeterNumber', 'Oldmeternumber:') !!}
    {!! Form::text('OldMeterNumber', null, ['class' => 'form-control', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Meterreader Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeterReader', 'Meterreader:') !!}
    {!! Form::text('MeterReader', null, ['class' => 'form-control', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>