<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Dateassigned Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateAssigned', 'Dateassigned:') !!}
    {!! Form::text('DateAssigned', null, ['class' => 'form-control','id'=>'DateAssigned']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateAssigned').datepicker()
    </script>
@endpush

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Assignedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AssignedBy', 'Assignedby:') !!}
    {!! Form::text('AssignedBy', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>