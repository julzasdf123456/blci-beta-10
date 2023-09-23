<div class="table-responsive">
    <table class="table" id="transacionPaymentDetails-table">
        <thead>
        <tr>
            <th>Transactionindexid</th>
        <th>Amount</th>
        <th>Paymentused</th>
        <th>Bank</th>
        <th>Checkno</th>
        <th>Checkexpiration</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transacionPaymentDetails as $transacionPaymentDetails)
            <tr>
                <td>{{ $transacionPaymentDetails->TransactionIndexId }}</td>
            <td>{{ $transacionPaymentDetails->Amount }}</td>
            <td>{{ $transacionPaymentDetails->PaymentUsed }}</td>
            <td>{{ $transacionPaymentDetails->Bank }}</td>
            <td>{{ $transacionPaymentDetails->CheckNo }}</td>
            <td>{{ $transacionPaymentDetails->CheckExpiration }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['transacionPaymentDetails.destroy', $transacionPaymentDetails->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('transacionPaymentDetails.show', [$transacionPaymentDetails->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('transacionPaymentDetails.edit', [$transacionPaymentDetails->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
