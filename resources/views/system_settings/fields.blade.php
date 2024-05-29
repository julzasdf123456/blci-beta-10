<!-- Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id', 'Id:') !!}
    {!! Form::text('id', null, ['class' => 'form-control']) !!}
</div>

<!-- Passworddaysexpire Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PasswordDaysExpire', 'Passworddaysexpire:') !!}
    {!! Form::number('PasswordDaysExpire', null, ['class' => 'form-control']) !!}
</div>