<!-- Bapaname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BAPAName', 'Bapaname:') !!}
    {!! Form::text('BAPAName', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Serviceperiod Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServicePeriod', 'Serviceperiod:') !!}
    {!! Form::text('ServicePeriod', null, ['class' => 'form-control','id'=>'ServicePeriod']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServicePeriod').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

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

<!-- Subtotal Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SubTotal', 'Subtotal:') !!}
    {!! Form::text('SubTotal', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Twopercentdiscount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TwoPercentDiscount', 'Twopercentdiscount:') !!}
    {!! Form::text('TwoPercentDiscount', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Fivepercentdiscount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FivePercentDiscount', 'Fivepercentdiscount:') !!}
    {!! Form::text('FivePercentDiscount', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
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

<!-- Vat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('VAT', 'Vat:') !!}
    {!! Form::text('VAT', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Total Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Total', 'Total:') !!}
    {!! Form::text('Total', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Teller Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Teller', 'Teller:') !!}
    {!! Form::text('Teller', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Noofconsumerspaid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NoOfConsumersPaid', 'Noofconsumerspaid:') !!}
    {!! Form::text('NoOfConsumersPaid', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>