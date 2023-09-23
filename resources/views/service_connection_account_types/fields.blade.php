<!-- Accounttype Field -->
<div class="form-group col-sm-4">
    {!! Form::label('AccountType', 'Accounttype:') !!}
    {!! Form::text('AccountType', null, ['class' => 'form-control','maxlength' => 200,'maxlength' => 200]) !!}
</div>

<!-- Alias Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Alias', 'Abbreviation:') !!}
    {!! Form::text('Alias', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Description', 'Description:') !!}
    {!! Form::text('Description', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>