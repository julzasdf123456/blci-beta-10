<!-- Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id', 'Id:') !!}
    {!! Form::text('id', null, ['class' => 'form-control']) !!}
</div>

<!-- Serviceconnectionid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceConnectionId', 'Serviceconnectionid:') !!}
    {!! Form::text('ServiceConnectionId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Comments Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Comments', 'Comments:') !!}
    {!! Form::text('Comments', null, ['class' => 'form-control', 'maxlength' => 3000, 'maxlength' => 3000]) !!}
</div>