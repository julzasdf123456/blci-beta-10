<!-- Ornumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORNumber', 'Ornumber:') !!}
    {!! Form::text('ORNumber', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Dateassigned Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateAssigned', 'Dateassigned:') !!}
    {!! Form::text('DateAssigned', null, ['class' => 'form-control','id'=>'DateAssigned']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateAssigned').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Issetmanually Field -->
<div class="form-group col-sm-6">
    {!! Form::label('IsSetManually', 'Issetmanually:') !!}
    {!! Form::text('IsSetManually', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Timeassigned Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TimeAssigned', 'Timeassigned:') !!}
    {!! Form::text('TimeAssigned', null, ['class' => 'form-control']) !!}
</div>

<!-- Office Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Office', 'Office:') !!}
    {!! Form::text('Office', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>