<!-- Consumerid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerId', 'Consumerid:') !!}
    {!! Form::text('ConsumerId', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Picturepath Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PicturePath', 'Picturepath:') !!}
    {!! Form::text('PicturePath', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>

<!-- Heximage Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('HexImage', 'Heximage:') !!}
    {!! Form::textarea('HexImage', null, ['class' => 'form-control']) !!}
</div>