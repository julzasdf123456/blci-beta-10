<table class="table table-sm table-hover table-borderless">
    <tr>
        <td>{!! Form::label('TransformerNumber', 'Transformer Number:') !!}</td>
        <td>
            {!! Form::text('TransformerNumber', $meterAndTransformer!=null ? $meterAndTransformer->TransformerNumber : null, ['class' => 'form-control form-control-sm','maxlength' => 120,'maxlength' => 120]) !!}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('Rating', 'Rating (in KVA):') !!}</td>
        <td>
            {!! Form::text('Rating', $meterAndTransformer!=null ? $meterAndTransformer->TransformerRating : null, ['class' => 'form-control form-control-sm','maxlength' => 20,'maxlength' => 20]) !!}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('Load', 'Load:') !!}</td>
        <td>
            {!! Form::text('Load', null, ['class' => 'form-control form-control-sm','maxlength' => 50,'maxlength' => 50]) !!}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('TransformerRental', 'Transformer Rental:') !!}</td>
        <td>
            {!! Form::number('TransformerRental', $paymentOrder != null && $paymentOrder->TransformerRentalFees != null ? $paymentOrder->TransformerRentalFees : null, ['class' => 'form-control form-control-sm text-right', 'step' => 50,'any' => 50]) !!}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('AdvanceMaterialDeposit', 'Adv. Material Deposit:') !!}</td>
        <td>
            {!! Form::number('AdvanceMaterialDeposit', $paymentOrder != null && $paymentOrder->MaterialDeposit != null ? $paymentOrder->MaterialDeposit : null, ['class' => 'form-control form-control-sm text-right', 'step' => 50,'any' => 50]) !!}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('CustomerDeposit', 'Customer Deposit:') !!}</td>
        <td>
            {!! Form::number('CustomerDeposit', $paymentOrder != null && $paymentOrder->CustomerDeposit != null ? $paymentOrder->CustomerDeposit : null, ['class' => 'form-control form-control-sm text-right', 'step' => 50,'any' => 50]) !!}
        </td>
    </tr>
</table>



@push('page_scripts')
    <script>
       
    </script>
@endpush

