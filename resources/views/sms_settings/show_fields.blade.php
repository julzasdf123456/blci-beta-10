<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $smsSettings->id }}</p>
</div>

<!-- Bills Field -->
<div class="col-sm-12">
    {!! Form::label('Bills', 'Bills:') !!}
    <p>{{ $smsSettings->Bills }}</p>
</div>

<!-- Noticeofdisconnection Field -->
<div class="col-sm-12">
    {!! Form::label('NoticeOfDisconnection', 'Noticeofdisconnection:') !!}
    <p>{{ $smsSettings->NoticeOfDisconnection }}</p>
</div>

<!-- Serviceconnectionreception Field -->
<div class="col-sm-12">
    {!! Form::label('ServiceConnectionReception', 'Serviceconnectionreception:') !!}
    <p>{{ $smsSettings->ServiceConnectionReception }}</p>
</div>

<!-- Inspectioncreation Field -->
<div class="col-sm-12">
    {!! Form::label('InspectionCreation', 'Inspectioncreation:') !!}
    <p>{{ $smsSettings->InspectionCreation }}</p>
</div>

<!-- Paymentapproved Field -->
<div class="col-sm-12">
    {!! Form::label('PaymentApproved', 'Paymentapproved:') !!}
    <p>{{ $smsSettings->PaymentApproved }}</p>
</div>

<!-- Inspectiontoday Field -->
<div class="col-sm-12">
    {!! Form::label('InspectionToday', 'Inspectiontoday:') !!}
    <p>{{ $smsSettings->InspectionToday }}</p>
</div>

