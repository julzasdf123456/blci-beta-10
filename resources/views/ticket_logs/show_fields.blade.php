<!-- Ticketid Field -->
<div class="col-sm-12">
    {!! Form::label('TicketId', 'Ticketid:') !!}
    <p>{{ $ticketLogs->TicketId }}</p>
</div>

<!-- Log Field -->
<div class="col-sm-12">
    {!! Form::label('Log', 'Log:') !!}
    <p>{{ $ticketLogs->Log }}</p>
</div>

<!-- Logdetails Field -->
<div class="col-sm-12">
    {!! Form::label('LogDetails', 'Logdetails:') !!}
    <p>{{ $ticketLogs->LogDetails }}</p>
</div>

<!-- Logtype Field -->
<div class="col-sm-12">
    {!! Form::label('LogType', 'Logtype:') !!}
    <p>{{ $ticketLogs->LogType }}</p>
</div>

<!-- Userid Field -->
<div class="col-sm-12">
    {!! Form::label('UserId', 'Userid:') !!}
    <p>{{ $ticketLogs->UserId }}</p>
</div>

