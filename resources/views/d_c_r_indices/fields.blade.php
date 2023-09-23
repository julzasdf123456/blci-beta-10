<!-- Glcode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GLCode', 'Glcode:') !!}
    {!! Form::text('GLCode', null, ['class' => 'form-control','maxlength' => 10,'maxlength' => 10]) !!}
</div>

<!-- Neacode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NEACode', 'Neacode:') !!}
    {!! Form::text('NEACode', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Tablename Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TableName', 'Tablename:') !!}
    {!! Form::text('TableName', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Columns Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Columns', 'Columns:') !!}
    {!! Form::text('Columns', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>

<!-- TownCode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TownCode', 'TownCode:') !!}
    <select name="TownCode" id="TownCode">
        <option value="">None</option>
        <option value="01">01</option>
        <option value="02">02</option>
        <option value="03">03</option>
        <option value="04">04</option>
        <option value="05">05</option>
        <option value="06">06</option>
        <option value="07">07</option>
        <option value="08">08</option>
        <option value="09">09</option>
    </select>
</div>