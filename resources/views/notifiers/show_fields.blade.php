<!-- Notification Field -->
<div class="col-sm-12">
    {!! Form::label('Notification', 'Notification:') !!}
    <p>{{ $notifiers->Notification }}</p>
</div>

<!-- From Field -->
<div class="col-sm-12">
    {!! Form::label('From', 'From:') !!}
    <p>{{ $notifiers->From }}</p>
</div>

<!-- To Field -->
<div class="col-sm-12">
    {!! Form::label('To', 'To:') !!}
    <p>{{ $notifiers->To }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12">
    {!! Form::label('Status', 'Status:') !!}
    <p>{{ $notifiers->Status }}</p>
</div>

<!-- Intent Field -->
<div class="col-sm-12">
    {!! Form::label('Intent', 'Intent:') !!}
    <p>{{ $notifiers->Intent }}</p>
</div>

<!-- Intentlink Field -->
<div class="col-sm-12">
    {!! Form::label('IntentLink', 'Intentlink:') !!}
    <p>{{ $notifiers->IntentLink }}</p>
</div>

<!-- Objectid Field -->
<div class="col-sm-12">
    {!! Form::label('ObjectId', 'Objectid:') !!}
    <p>{{ $notifiers->ObjectId }}</p>
</div>

