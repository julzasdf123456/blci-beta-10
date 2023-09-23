<!-- Transactionindexid Field -->
<div class="col-sm-12">
    {!! Form::label('TransactionIndexId', 'Transactionindexid:') !!}
    <p>{{ $transactionDetails->TransactionIndexId }}</p>
</div>

<!-- Particular Field -->
<div class="col-sm-12">
    {!! Form::label('Particular', 'Particular:') !!}
    <p>{{ $transactionDetails->Particular }}</p>
</div>

<!-- Amount Field -->
<div class="col-sm-12">
    {!! Form::label('Amount', 'Amount:') !!}
    <p>{{ $transactionDetails->Amount }}</p>
</div>

<!-- Vat Field -->
<div class="col-sm-12">
    {!! Form::label('VAT', 'Vat:') !!}
    <p>{{ $transactionDetails->VAT }}</p>
</div>

<!-- Total Field -->
<div class="col-sm-12">
    {!! Form::label('Total', 'Total:') !!}
    <p>{{ $transactionDetails->Total }}</p>
</div>

