<!-- Serviceconnectionid Field -->
<div class="col-sm-12">
    {!! Form::label('ServiceConnectionId', 'Serviceconnectionid:') !!}
    <p>{{ $serviceConnectionPayTransaction->ServiceConnectionId }}</p>
</div>

<!-- Particular Field -->
<div class="col-sm-12">
    {!! Form::label('Particular', 'Particular:') !!}
    <p>{{ $serviceConnectionPayTransaction->Particular }}</p>
</div>

<!-- Amount Field -->
<div class="col-sm-12">
    {!! Form::label('Amount', 'Amount:') !!}
    <p>{{ $serviceConnectionPayTransaction->Amount }}</p>
</div>

<!-- Vat Field -->
<div class="col-sm-12">
    {!! Form::label('Vat', 'Vat:') !!}
    <p>{{ $serviceConnectionPayTransaction->Vat }}</p>
</div>

<!-- Total Field -->
<div class="col-sm-12">
    {!! Form::label('Total', 'Total:') !!}
    <p>{{ $serviceConnectionPayTransaction->Total }}</p>
</div>

