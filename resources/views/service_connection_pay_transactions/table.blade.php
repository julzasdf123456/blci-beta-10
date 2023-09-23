<div class="table-responsive">
    <table class="table" id="serviceConnectionPayTransactions-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Particular</th>
        <th>Amount</th>
        <th>Vat</th>
        <th>Total</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnectionPayTransactions as $serviceConnectionPayTransaction)
            <tr>
                <td>{{ $serviceConnectionPayTransaction->ServiceConnectionId }}</td>
            <td>{{ $serviceConnectionPayTransaction->Particular }}</td>
            <td>{{ $serviceConnectionPayTransaction->Amount }}</td>
            <td>{{ $serviceConnectionPayTransaction->Vat }}</td>
            <td>{{ $serviceConnectionPayTransaction->Total }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnectionPayTransactions.destroy', $serviceConnectionPayTransaction->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnectionPayTransactions.show', [$serviceConnectionPayTransaction->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnectionPayTransactions.edit', [$serviceConnectionPayTransaction->id]) }}"
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
