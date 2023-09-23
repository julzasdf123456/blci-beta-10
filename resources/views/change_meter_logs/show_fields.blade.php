<!-- Accountnumber Field -->
<div class="col-sm-12">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    <p>{{ $changeMeterLogs->AccountNumber }}</p>
</div>

<!-- Oldmeterserial Field -->
<div class="col-sm-12">
    {!! Form::label('OldMeterSerial', 'Oldmeterserial:') !!}
    <p>{{ $changeMeterLogs->OldMeterSerial }}</p>
</div>

<!-- Newmeterserial Field -->
<div class="col-sm-12">
    {!! Form::label('NewMeterSerial', 'Newmeterserial:') !!}
    <p>{{ $changeMeterLogs->NewMeterSerial }}</p>
</div>

<!-- Pulloutreading Field -->
<div class="col-sm-12">
    {!! Form::label('PullOutReading', 'Pulloutreading:') !!}
    <p>{{ $changeMeterLogs->PullOutReading }}</p>
</div>

<!-- Additionalkwhfornextbilling Field -->
<div class="col-sm-12">
    {!! Form::label('AdditionalKwhForNextBilling', 'Additionalkwhfornextbilling:') !!}
    <p>{{ $changeMeterLogs->AdditionalKwhForNextBilling }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12">
    {!! Form::label('Status', 'Status:') !!}
    <p>{{ $changeMeterLogs->Status }}</p>
</div>

