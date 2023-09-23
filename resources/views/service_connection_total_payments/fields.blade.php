<!-- Subtotal Field -->
<div class="form-group col-sm-4">
    {!! Form::label('SubTotal', 'Sub Total') !!}
    {!! Form::text('SubTotal', null, ['class' => 'form-control','maxlength' => 60,'maxlength' => 60, 'id' => 'SubTotalField', 'readonly' => 'true']) !!}
</div>

<!-- Totalvat Field -->
<div class="form-group col-sm-4">
    {!! Form::label('TotalVat', 'Totalvat:') !!}
    {!! Form::text('TotalVat', null, ['class' => 'form-control','maxlength' => 60,'maxlength' => 60, 'id' => 'TotalVatField', 'readonly' => 'true']) !!}
</div>

<!-- Total Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Total', 'Total:') !!}
    {!! Form::text('Total', null, ['class' => 'form-control','maxlength' => 60,'maxlength' => 60, 'id' => 'TotalField', 'readonly' => 'true']) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>