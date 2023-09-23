<!-- Serviceconnectionid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceConnectionId', 'Serviceconnectionid:') !!}
    {!! Form::text('ServiceConnectionId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Latitude Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Latitude', 'Latitude:') !!}
    {!! Form::text('Latitude', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Longitude Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Longitude', 'Longitude:') !!}
    {!! Form::text('Longitude', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Datetimetaken Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateTimeTaken', 'Datetimetaken:') !!}
    {!! Form::text('DateTimeTaken', null, ['class' => 'form-control','id'=>'DateTimeTaken']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateTimeTaken').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Poleremarks Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PoleRemarks', 'Poleremarks:') !!}
    {!! Form::text('PoleRemarks', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>