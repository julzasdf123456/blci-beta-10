<!-- Notification Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notification', 'Notification:') !!}
    {!! Form::text('Notification', null, ['class' => 'form-control','maxlength' => 3000,'maxlength' => 3000]) !!}
</div>

<!-- From Field -->
<div class="form-group col-sm-6">
    {!! Form::label('From', 'From:') !!}
    {!! Form::text('From', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- To Field -->
<div class="form-group col-sm-6">
    {!! Form::label('To', 'To:') !!}
    {!! Form::text('To', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Intent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Intent', 'Intent:') !!}
    {!! Form::text('Intent', null, ['class' => 'form-control','maxlength' => 600,'maxlength' => 600]) !!}
</div>

<!-- Intentlink Field -->
<div class="form-group col-sm-6">
    {!! Form::label('IntentLink', 'Intentlink:') !!}
    {!! Form::text('IntentLink', null, ['class' => 'form-control','maxlength' => 800,'maxlength' => 800]) !!}
</div>

<!-- Objectid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ObjectId', 'Objectid:') !!}
    {!! Form::text('ObjectId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>