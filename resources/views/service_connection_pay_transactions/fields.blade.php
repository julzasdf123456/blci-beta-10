<!-- Serviceconnectionid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceConnectionId', 'Serviceconnectionid:') !!}
    {!! Form::text('ServiceConnectionId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Particular Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Particular', 'Particular:') !!}
    {!! Form::text('Particular', null, ['class' => 'form-control','maxlength' => 40,'maxlength' => 40]) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Amount', 'Amount:') !!}
    {!! Form::text('Amount', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Vat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Vat', 'Vat:') !!}
    {!! Form::text('Vat', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Total Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Total', 'Total:') !!}
    {!! Form::text('Total', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>