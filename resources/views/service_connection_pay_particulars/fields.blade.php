<!-- Particular Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Particular', 'Payment Particular Name:') !!}
    {!! Form::text('Particular', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- DefaultAmount Field -->
<div class="form-group col-sm-3">
    {!! Form::label('DefaultAmount', 'Default Amount (in Peso):') !!}
    {!! Form::number('DefaultAmount', null, ['class' => 'form-control', 'step' => 'any']) !!}
</div>

<!-- Vatpercentage Field -->
{{-- <div class="form-group col-sm-3">
    {!! Form::label('VatPercentage', 'VAT Percentage (in Decimal, e.g., 20% is .20):') !!}
    {!! Form::number('VatPercentage', null, ['class' => 'form-control', 'step' => 'any']) !!}
</div> --}}

<!-- AccountNumber Field -->
<div class="form-group col-sm-3">
    {!! Form::label('AccountNumber', 'AccountNumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 800,'maxlength' => 800]) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-3">
    {!! Form::label('Description', 'Description:') !!}
    {!! Form::text('Description', null, ['class' => 'form-control','maxlength' => 800,'maxlength' => 800]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>