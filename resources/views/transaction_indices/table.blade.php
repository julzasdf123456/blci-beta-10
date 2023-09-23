<div class="table-responsive">
    <table class="table" id="transactionIndices-table">
        <thead>
        <tr>
            <th>Transactionnumber</th>
        <th>Paymenttitle</th>
        <th>Paymentdetails</th>
        <th>Ornumber</th>
        <th>Ordate</th>
        <th>Subtotal</th>
        <th>Vat</th>
        <th>Total</th>
        <th>Notes</th>
        <th>Userid</th>
        <th>Serviceconnectionid</th>
        <th>Ticketid</th>
        <th>Objectid</th>
        <th>Source</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transactionIndices as $transactionIndex)
            <tr>
                <td>{{ $transactionIndex->TransactionNumber }}</td>
            <td>{{ $transactionIndex->PaymentTitle }}</td>
            <td>{{ $transactionIndex->PaymentDetails }}</td>
            <td>{{ $transactionIndex->ORNumber }}</td>
            <td>{{ $transactionIndex->ORDate }}</td>
            <td>{{ $transactionIndex->SubTotal }}</td>
            <td>{{ $transactionIndex->VAT }}</td>
            <td>{{ $transactionIndex->Total }}</td>
            <td>{{ $transactionIndex->Notes }}</td>
            <td>{{ $transactionIndex->UserId }}</td>
            <td>{{ $transactionIndex->ServiceConnectionId }}</td>
            <td>{{ $transactionIndex->TicketId }}</td>
            <td>{{ $transactionIndex->ObjectId }}</td>
            <td>{{ $transactionIndex->Source }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['transactionIndices.destroy', $transactionIndex->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('transactionIndices.show', [$transactionIndex->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('transactionIndices.edit', [$transactionIndex->id]) }}"
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
