<!-- Accountnumber Field -->
<div class="col-sm-12">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    <p>{{ $customerDepositInterests->AccountNumber }}</p>
</div>

<!-- Interestearned Field -->
<div class="col-sm-12">
    {!! Form::label('InterestEarned', 'Interestearned:') !!}
    <p>{{ $customerDepositInterests->InterestEarned }}</p>
</div>

<!-- Currentamountremaining Field -->
<div class="col-sm-12">
    {!! Form::label('CurrentAmountRemaining', 'Currentamountremaining:') !!}
    <p>{{ $customerDepositInterests->CurrentAmountRemaining }}</p>
</div>

<!-- Originalamount Field -->
<div class="col-sm-12">
    {!! Form::label('OriginalAmount', 'Originalamount:') !!}
    <p>{{ $customerDepositInterests->OriginalAmount }}</p>
</div>

