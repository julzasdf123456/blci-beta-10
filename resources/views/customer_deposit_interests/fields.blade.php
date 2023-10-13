<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Interestearned Field -->
<div class="form-group col-sm-6">
    {!! Form::label('InterestEarned', 'Interestearned:') !!}
    {!! Form::number('InterestEarned', null, ['class' => 'form-control']) !!}
</div>

<!-- Currentamountremaining Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CurrentAmountRemaining', 'Currentamountremaining:') !!}
    {!! Form::number('CurrentAmountRemaining', null, ['class' => 'form-control']) !!}
</div>

<!-- Originalamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OriginalAmount', 'Originalamount:') !!}
    {!! Form::number('OriginalAmount', null, ['class' => 'form-control']) !!}
</div>