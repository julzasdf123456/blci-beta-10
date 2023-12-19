<!-- Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id', 'Id:') !!}
    {!! Form::text('id', null, ['class' => 'form-control']) !!}
</div>

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
    {!! Form::text('ConsumerName', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Ornumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORNumber', 'Ornumber:') !!}
    {!! Form::text('ORNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Ordate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORDate', 'Ordate:') !!}
    {!! Form::text('ORDate', null, ['class' => 'form-control','id'=>'ORDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ORDate').datepicker()
    </script>
@endpush

<!-- Amountpaid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AmountPaid', 'Amountpaid:') !!}
    {!! Form::number('AmountPaid', null, ['class' => 'form-control']) !!}
</div>

<!-- Cash Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Cash', 'Cash:') !!}
    {!! Form::number('Cash', null, ['class' => 'form-control']) !!}
</div>

<!-- Particulars Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Particulars', 'Particulars:') !!}
    {!! Form::text('Particulars', null, ['class' => 'form-control', 'maxlength' => 500, 'maxlength' => 500]) !!}
</div>

<!-- Checkno Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CheckNo', 'Checkno:') !!}
    {!! Form::text('CheckNo', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Checkdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CheckDate', 'Checkdate:') !!}
    {!! Form::text('CheckDate', null, ['class' => 'form-control','id'=>'CheckDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#CheckDate').datepicker()
    </script>
@endpush

<!-- Bankcode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BankCode', 'Bankcode:') !!}
    {!! Form::text('BankCode', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Checkamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CheckAmount', 'Checkamount:') !!}
    {!! Form::number('CheckAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Collector Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Collector', 'Collector:') !!}
    {!! Form::text('Collector', null, ['class' => 'form-control', 'maxlength' => 300, 'maxlength' => 300]) !!}
</div>