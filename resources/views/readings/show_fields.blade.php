<!-- Accountnumber Field -->
<div class="col-sm-12">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    <p>{{ $readings->AccountNumber }}</p>
</div>

<!-- Serviceperiod Field -->
<div class="col-sm-12">
    {!! Form::label('ServicePeriod', 'Serviceperiod:') !!}
    <p>{{ $readings->ServicePeriod }}</p>
</div>

<!-- Readingtimestamp Field -->
<div class="col-sm-12">
    {!! Form::label('ReadingTimestamp', 'Readingtimestamp:') !!}
    <p>{{ $readings->ReadingTimestamp }}</p>
</div>

<!-- Kwhused Field -->
<div class="col-sm-12">
    {!! Form::label('KwhUsed', 'Kwhused:') !!}
    <p>{{ $readings->KwhUsed }}</p>
</div>

<!-- Demandkwhused Field -->
<div class="col-sm-12">
    {!! Form::label('DemandKwhUsed', 'Demandkwhused:') !!}
    <p>{{ $readings->DemandKwhUsed }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    <p>{{ $readings->Notes }}</p>
</div>

<!-- Latitude Field -->
<div class="col-sm-12">
    {!! Form::label('Latitude', 'Latitude:') !!}
    <p>{{ $readings->Latitude }}</p>
</div>

<!-- Longitude Field -->
<div class="col-sm-12">
    {!! Form::label('Longitude', 'Longitude:') !!}
    <p>{{ $readings->Longitude }}</p>
</div>

