<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Previousaccountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PreviousAccountNumber', 'Previousaccountnumber:') !!}
    {!! Form::text('PreviousAccountNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Consumername Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerName', 'Consumername:') !!}
    {!! Form::text('ConsumerName', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- Connectiontype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConnectionType', 'Connectiontype:') !!}
    {!! Form::text('ConnectionType', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Meternumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeterNumber', 'Meternumber:') !!}
    {!! Form::text('MeterNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Totalbill Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TotalBill', 'Totalbill:') !!}
    {!! Form::number('TotalBill', null, ['class' => 'form-control']) !!}
</div>

<!-- Waterbill Field -->
<div class="form-group col-sm-6">
    {!! Form::label('WaterBill', 'Waterbill:') !!}
    {!! Form::number('WaterBill', null, ['class' => 'form-control']) !!}
</div>

<!-- Billspenalty Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillsPenalty', 'Billspenalty:') !!}
    {!! Form::number('BillsPenalty', null, ['class' => 'form-control']) !!}
</div>

<!-- Salescharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SalesCharge', 'Salescharge:') !!}
    {!! Form::number('SalesCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Salespenalty Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SalesPenalty', 'Salespenalty:') !!}
    {!! Form::number('SalesPenalty', null, ['class' => 'form-control']) !!}
</div>

<!-- Othercharges Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherCharges', 'Othercharges:') !!}
    {!! Form::number('OtherCharges', null, ['class' => 'form-control']) !!}
</div>