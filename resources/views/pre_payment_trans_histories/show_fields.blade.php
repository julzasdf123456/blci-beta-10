<!-- Accountnumber Field -->
<div class="col-sm-12">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    <p>{{ $prePaymentTransHistory->AccountNumber }}</p>
</div>

<!-- Method Field -->
<div class="col-sm-12">
    {!! Form::label('Method', 'Method:') !!}
    <p>{{ $prePaymentTransHistory->Method }}</p>
</div>

<!-- Amount Field -->
<div class="col-sm-12">
    {!! Form::label('Amount', 'Amount:') !!}
    <p>{{ $prePaymentTransHistory->Amount }}</p>
</div>

<!-- Userid Field -->
<div class="col-sm-12">
    {!! Form::label('UserId', 'Userid:') !!}
    <p>{{ $prePaymentTransHistory->UserId }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    <p>{{ $prePaymentTransHistory->Notes }}</p>
</div>

