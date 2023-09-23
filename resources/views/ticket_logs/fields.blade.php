<!-- Ticketid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TicketId', 'Ticketid:') !!}
    {!! Form::text('TicketId', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Log Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Log', 'Log:') !!}
    {!! Form::text('Log', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Logdetails Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LogDetails', 'Logdetails:') !!}
    {!! Form::text('LogDetails', null, ['class' => 'form-control','maxlength' => 1500,'maxlength' => 1500]) !!}
</div>

<!-- Logtype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LogType', 'Logtype:') !!}
    {!! Form::text('LogType', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>