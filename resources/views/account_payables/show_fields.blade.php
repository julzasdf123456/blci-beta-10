<!-- Accountcode Field -->
<div class="col-sm-12">
    {!! Form::label('AccountCode', 'Accountcode:') !!}
    <p>{{ $accountPayables->AccountCode }}</p>
</div>

<!-- Accounttitle Field -->
<div class="col-sm-12">
    {!! Form::label('AccountTitle', 'Accounttitle:') !!}
    <p>{{ $accountPayables->AccountTitle }}</p>
</div>

<!-- Accountdescription Field -->
<div class="col-sm-12">
    {!! Form::label('AccountDescription', 'Accountdescription:') !!}
    <p>{{ $accountPayables->AccountDescription }}</p>
</div>

<!-- Defaultamount Field -->
<div class="col-sm-12">
    {!! Form::label('DefaultAmount', 'Defaultamount:') !!}
    <p>{{ $accountPayables->DefaultAmount }}</p>
</div>

<!-- Vatpercentage Field -->
<div class="col-sm-12">
    {!! Form::label('VATPercentage', 'Vatpercentage:') !!}
    <p>{{ $accountPayables->VATPercentage }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    <p>{{ $accountPayables->Notes }}</p>
</div>

