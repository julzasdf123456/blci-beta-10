<!-- Bankfullname Field -->
<div class="col-sm-12">
    {!! Form::label('BankFullName', 'Bankfullname:') !!}
    <p>{{ $banks->BankFullName }}</p>
</div>

<!-- Bankabbrev Field -->
<div class="col-sm-12">
    {!! Form::label('BankAbbrev', 'Bankabbrev:') !!}
    <p>{{ $banks->BankAbbrev }}</p>
</div>

<!-- Address Field -->
<div class="col-sm-12">
    {!! Form::label('Address', 'Address:') !!}
    <p>{{ $banks->Address }}</p>
</div>

<!-- Tin Field -->
<div class="col-sm-12">
    {!! Form::label('TIN', 'Tin:') !!}
    <p>{{ $banks->TIN }}</p>
</div>

