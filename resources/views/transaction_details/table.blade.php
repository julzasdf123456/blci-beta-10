<div class="table-responsive">
    <table class="table" id="transactionDetails-table">
        <thead>
        <tr>
            <th>Transactionindexid</th>
        <th>Particular</th>
        <th>Amount</th>
        <th>Vat</th>
        <th>Total</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transactionDetails as $transactionDetails)
            <tr>
                <td>{{ $transactionDetails->TransactionIndexId }}</td>
            <td>{{ $transactionDetails->Particular }}</td>
            <td>{{ $transactionDetails->Amount }}</td>
            <td>{{ $transactionDetails->VAT }}</td>
            <td>{{ $transactionDetails->Total }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['transactionDetails.destroy', $transactionDetails->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('transactionDetails.show', [$transactionDetails->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('transactionDetails.edit', [$transactionDetails->id]) }}"
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
