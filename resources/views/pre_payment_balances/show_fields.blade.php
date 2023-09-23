<!-- Accountnumber Field -->
<div class="col-sm-12">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    <p>{{ $prePaymentBalance->AccountNumber }}</p>
</div>

<!-- Balance Field -->
<div class="col-sm-12">
    {!! Form::label('Balance', 'Balance:') !!}
    <p>{{ $prePaymentBalance->Balance }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12">
    {!! Form::label('Status', 'Status:') !!}
    <p>{{ $prePaymentBalance->Status }}</p>
</div>

