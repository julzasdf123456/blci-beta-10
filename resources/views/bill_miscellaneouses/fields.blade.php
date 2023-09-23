<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('Name', null, ['class' => 'form-control', 'maxlength' => 200, 'maxlength' => 200]) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Description', 'Description:') !!}
    {!! Form::text('Description', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>

<!-- Balance Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Balance', 'Balance:') !!}
    {!! Form::number('Balance', null, ['class' => 'form-control']) !!}
</div>

<!-- Operation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Operation', 'Operation:') !!}
    {!! Form::text('Operation', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Terms Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Terms', 'Terms:') !!}
    {!! Form::text('Terms', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 1000, 'maxlength' => 1000]) !!}
</div>

<!-- Enddate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EndDate', 'Enddate:') !!}
    {!! Form::text('EndDate', null, ['class' => 'form-control','id'=>'EndDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#EndDate').datepicker()
    </script>
@endpush