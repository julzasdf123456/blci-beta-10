<!-- Serviceconnectionid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceConnectionId', 'Serviceconnectionid:') !!}
    {!! Form::text('ServiceConnectionId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Assessment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Assessment', 'Assessment:') !!}
    {!! Form::text('Assessment', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Dateofinspection Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateOfInspection', 'Dateofinspection:') !!}
    {!! Form::text('DateOfInspection', null, ['class' => 'form-control','id'=>'DateOfInspection']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateOfInspection').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>