<div class="table-responsive">
    <table class="table" id="cacheOtherPayments-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Transactionindexid</th>
        <th>Particular</th>
        <th>Amount</th>
        <th>Vat</th>
        <th>Total</th>
        <th>Accountcode</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cacheOtherPayments as $cacheOtherPayments)
            <tr>
                <td>{{ $cacheOtherPayments->AccountNumber }}</td>
            <td>{{ $cacheOtherPayments->TransactionIndexId }}</td>
            <td>{{ $cacheOtherPayments->Particular }}</td>
            <td>{{ $cacheOtherPayments->Amount }}</td>
            <td>{{ $cacheOtherPayments->VAT }}</td>
            <td>{{ $cacheOtherPayments->Total }}</td>
            <td>{{ $cacheOtherPayments->AccountCode }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['cacheOtherPayments.destroy', $cacheOtherPayments->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('cacheOtherPayments.show', [$cacheOtherPayments->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('cacheOtherPayments.edit', [$cacheOtherPayments->id]) }}"
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
