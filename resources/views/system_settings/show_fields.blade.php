<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $systemSettings->id }}</p>
</div>

<!-- Passworddaysexpire Field -->
<div class="col-sm-12">
    {!! Form::label('PasswordDaysExpire', 'Passworddaysexpire:') !!}
    <p>{{ $systemSettings->PasswordDaysExpire }}</p>
</div>

