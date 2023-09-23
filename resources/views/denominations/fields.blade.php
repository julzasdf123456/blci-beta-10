<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
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

<!-- Onethousand Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OneThousand', 'Onethousand:') !!}
    {!! Form::text('OneThousand', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Fivehundred Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FiveHundred', 'Fivehundred:') !!}
    {!! Form::text('FiveHundred', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Onehundred Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OneHundred', 'Onehundred:') !!}
    {!! Form::text('OneHundred', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Fifty Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Fifty', 'Fifty:') !!}
    {!! Form::text('Fifty', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Twenty Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Twenty', 'Twenty:') !!}
    {!! Form::text('Twenty', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Ten Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Ten', 'Ten:') !!}
    {!! Form::text('Ten', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Five Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Five', 'Five:') !!}
    {!! Form::text('Five', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Peso Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Peso', 'Peso:') !!}
    {!! Form::text('Peso', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Cents Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Cents', 'Cents:') !!}
    {!! Form::text('Cents', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Paidbillid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PaidBillId', 'Paidbillid:') !!}
    {!! Form::text('PaidBillId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Total Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Total', 'Total:') !!}
    {!! Form::text('Total', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>