<!-- Readingid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ReadingId', 'Readingid:') !!}
    {!! Form::text('ReadingId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Kwhused Field -->
<div class="form-group col-sm-6">
    {!! Form::label('KwhUsed', 'Kwhused:') !!}
    {!! Form::text('KwhUsed', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Serviceperiod Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServicePeriod', 'Serviceperiod:') !!}
    {!! Form::text('ServicePeriod', null, ['class' => 'form-control','id'=>'ServicePeriod']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServicePeriod').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Confirmed Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Confirmed', 'Confirmed:') !!}
    {!! Form::text('Confirmed', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Readdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ReadDate', 'Readdate:') !!}
    {!! Form::text('ReadDate', null, ['class' => 'form-control','id'=>'ReadDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ReadDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush