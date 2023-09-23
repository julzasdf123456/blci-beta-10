<!-- Billnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillNumber', 'Billnumber:') !!}
    {!! Form::text('BillNumber', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Serviceperiod Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServicePeriod', 'Serviceperiod:') !!}
    {!! Form::text('ServicePeriod', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Ornumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORNumber', 'Ornumber:') !!}
    {!! Form::text('ORNumber', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Ordate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORDate', 'Ordate:') !!}
    {!! Form::text('ORDate', null, ['class' => 'form-control','id'=>'ORDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ORDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Dcrnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DCRNumber', 'Dcrnumber:') !!}
    {!! Form::text('DCRNumber', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Kwhused Field -->
<div class="form-group col-sm-6">
    {!! Form::label('KwhUsed', 'Kwhused:') !!}
    {!! Form::text('KwhUsed', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Teller Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Teller', 'Teller:') !!}
    {!! Form::text('Teller', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Officetransacted Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OfficeTransacted', 'Officetransacted:') !!}
    {!! Form::text('OfficeTransacted', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Postingdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PostingDate', 'Postingdate:') !!}
    {!! Form::text('PostingDate', null, ['class' => 'form-control','id'=>'PostingDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#PostingDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Postingtime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PostingTime', 'Postingtime:') !!}
    {!! Form::text('PostingTime', null, ['class' => 'form-control']) !!}
</div>

<!-- Surcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Surcharge', 'Surcharge:') !!}
    {!! Form::text('Surcharge', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Form2307Twopercent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Form2307TwoPercent', 'Form2307Twopercent:') !!}
    {!! Form::text('Form2307TwoPercent', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Form2307Fivepercent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Form2307FivePercent', 'Form2307Fivepercent:') !!}
    {!! Form::text('Form2307FivePercent', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Additionalcharges Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdditionalCharges', 'Additionalcharges:') !!}
    {!! Form::text('AdditionalCharges', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Deductions Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Deductions', 'Deductions:') !!}
    {!! Form::text('Deductions', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Netamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetAmount', 'Netamount:') !!}
    {!! Form::text('NetAmount', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Source Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Source', 'Source:') !!}
    {!! Form::text('Source', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Objectsourceid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ObjectSourceId', 'Objectsourceid:') !!}
    {!! Form::text('ObjectSourceId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>