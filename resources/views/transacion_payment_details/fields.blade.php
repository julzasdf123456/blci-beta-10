<!-- Transactionindexid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransactionIndexId', 'Transactionindexid:') !!}
    {!! Form::text('TransactionIndexId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Amount', 'Amount:') !!}
    {!! Form::text('Amount', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Paymentused Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PaymentUsed', 'Paymentused:') !!}
    {!! Form::text('PaymentUsed', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Bank Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Bank', 'Bank:') !!}
    {!! Form::text('Bank', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Checkno Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CheckNo', 'Checkno:') !!}
    {!! Form::text('CheckNo', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Checkexpiration Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CheckExpiration', 'Checkexpiration:') !!}
    {!! Form::text('CheckExpiration', null, ['class' => 'form-control','id'=>'CheckExpiration']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#CheckExpiration').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush