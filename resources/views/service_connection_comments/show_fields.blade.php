<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $serviceConnectionComments->id }}</p>
</div>

<!-- Serviceconnectionid Field -->
<div class="col-sm-12">
    {!! Form::label('ServiceConnectionId', 'Serviceconnectionid:') !!}
    <p>{{ $serviceConnectionComments->ServiceConnectionId }}</p>
</div>

<!-- Userid Field -->
<div class="col-sm-12">
    {!! Form::label('UserId', 'Userid:') !!}
    <p>{{ $serviceConnectionComments->UserId }}</p>
</div>

<!-- Comments Field -->
<div class="col-sm-12">
    {!! Form::label('Comments', 'Comments:') !!}
    <p>{{ $serviceConnectionComments->Comments }}</p>
</div>

