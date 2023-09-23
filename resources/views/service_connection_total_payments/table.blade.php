<div class="table-responsive">
    <table class="table" id="serviceConnectionTotalPayments-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Subtotal</th>
        <th>Form2307Twopercent</th>
        <th>Form2307Fivepercent</th>
        <th>Totalvat</th>
        <th>Total</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnectionTotalPayments as $serviceConnectionTotalPayments)
            <tr>
                <td>{{ $serviceConnectionTotalPayments->ServiceConnectionId }}</td>
            <td>{{ $serviceConnectionTotalPayments->SubTotal }}</td>
            <td>{{ $serviceConnectionTotalPayments->Form2307TwoPercent }}</td>
            <td>{{ $serviceConnectionTotalPayments->Form2307FivePercent }}</td>
            <td>{{ $serviceConnectionTotalPayments->TotalVat }}</td>
            <td>{{ $serviceConnectionTotalPayments->Total }}</td>
            <td>{{ $serviceConnectionTotalPayments->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnectionTotalPayments.destroy', $serviceConnectionTotalPayments->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnectionTotalPayments.show', [$serviceConnectionTotalPayments->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnectionTotalPayments.edit', [$serviceConnectionTotalPayments->id]) }}"
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
