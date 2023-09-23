<!-- Accountcode Field -->
<div class="form-group col-sm-12">
    {!! Form::label('AccountCode', 'Account Code:') !!}
    {!! Form::text('AccountCode', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Accounttitle Field -->
<div class="form-group col-sm-12">
    {!! Form::label('AccountTitle', 'Account Title:') !!}
    {!! Form::text('AccountTitle', null, ['class' => 'form-control','maxlength' => 600,'maxlength' => 600]) !!}
</div>

<!-- Accountdescription Field -->
<div class="form-group col-sm-12">
    {!! Form::label('AccountDescription', 'Account Description:') !!}
    {!! Form::text('AccountDescription', null, ['class' => 'form-control','maxlength' => 700,'maxlength' => 700]) !!}
</div>

<!-- Defaultamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DefaultAmount', 'Default Amount:') !!}
    {!! Form::number('DefaultAmount', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}
</div>

<!-- Vatpercentage Field -->
<div class="form-group col-sm-6">
    {!! Form::label('VATPercentage', 'VAT Percentage:') !!}
    {!! Form::number('VATPercentage', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Notes', 'Notes/Remarks:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>