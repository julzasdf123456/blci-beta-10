<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Billid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillId', 'Billid:') !!}
    {!! Form::text('BillId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Discountpercentage Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DiscountPercentage', 'Discountpercentage:') !!}
    {!! Form::text('DiscountPercentage', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Discountamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DiscountAmount', 'Discountamount:') !!}
    {!! Form::text('DiscountAmount', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Bapaname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BAPAName', 'Bapaname:') !!}
    {!! Form::text('BAPAName', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
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