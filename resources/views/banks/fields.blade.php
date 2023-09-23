<!-- Bankfullname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BankFullName', 'Bank Fullname (e.g. Banko De Oro, Philippine National Bank):') !!}
    {!! Form::text('BankFullName', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Bankabbrev Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BankAbbrev', 'Bank Abbrevation (e.g. BDO, PNB):') !!}
    {!! Form::text('BankAbbrev', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Address', 'Address:') !!}
    {!! Form::text('Address', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>

<!-- Tin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TIN', 'TIN No:') !!}
    {!! Form::text('TIN', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>