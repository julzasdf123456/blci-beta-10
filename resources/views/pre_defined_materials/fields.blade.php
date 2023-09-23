<!-- Neacode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NEACode', 'Neacode:') !!}
    {!! Form::text('NEACode', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Quantity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Quantity', 'Quantity:') !!}
    {!! Form::text('Quantity', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Options Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Options', 'Options:') !!}
    {!! Form::text('Options', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Applicationtype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ApplicationType', 'Applicationtype:') !!}
    {!! Form::text('ApplicationType', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>