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

<!-- Readingtimestamp Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ReadingTimestamp', 'Readingtimestamp:') !!}
    {!! Form::text('ReadingTimestamp', null, ['class' => 'form-control','id'=>'ReadingTimestamp']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ReadingTimestamp').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Kwhused Field -->
<div class="form-group col-sm-6">
    {!! Form::label('KwhUsed', 'Kwhused:') !!}
    {!! Form::text('KwhUsed', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Demandkwhused Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DemandKwhUsed', 'Demandkwhused:') !!}
    {!! Form::text('DemandKwhUsed', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 3000,'maxlength' => 3000]) !!}
</div>

<!-- Latitude Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Latitude', 'Latitude:') !!}
    {!! Form::text('Latitude', null, ['class' => 'form-control','maxlength' => 60,'maxlength' => 60]) !!}
</div>

<!-- Longitude Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Longitude', 'Longitude:') !!}
    {!! Form::text('Longitude', null, ['class' => 'form-control','maxlength' => 60,'maxlength' => 60]) !!}
</div>