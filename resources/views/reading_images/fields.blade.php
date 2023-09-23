<!-- Photo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Photo', 'Photo:') !!}
    {!! Form::text('Photo', null, ['class' => 'form-control','maxlength' => 2500,'maxlength' => 2500]) !!}
</div>

<!-- Readingid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ReadingId', 'Readingid:') !!}
    {!! Form::text('ReadingId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>