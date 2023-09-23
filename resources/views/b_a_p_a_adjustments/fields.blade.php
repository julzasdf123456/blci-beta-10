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

<!-- Numberofconsumers Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NumberOfConsumers', 'Numberofconsumers:') !!}
    {!! Form::text('NumberOfConsumers', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Subtotal Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SubTotal', 'Subtotal:') !!}
    {!! Form::text('SubTotal', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Netamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetAmount', 'Netamount:') !!}
    {!! Form::text('NetAmount', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Route Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Route', 'Route:') !!}
    {!! Form::text('Route', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>