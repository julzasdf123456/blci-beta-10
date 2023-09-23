<div class="table-responsive">
    <table class="table" id="serviceConnectionMatPayments-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Material</th>
        <th>Quantity</th>
        <th>Vat</th>
        <th>Total</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnectionMatPayments as $serviceConnectionMatPayments)
            <tr>
                <td>{{ $serviceConnectionMatPayments->ServiceConnectionId }}</td>
            <td>{{ $serviceConnectionMatPayments->Material }}</td>
            <td>{{ $serviceConnectionMatPayments->Quantity }}</td>
            <td>{{ $serviceConnectionMatPayments->Vat }}</td>
            <td>{{ $serviceConnectionMatPayments->Total }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnectionMatPayments.destroy', $serviceConnectionMatPayments->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnectionMatPayments.show', [$serviceConnectionMatPayments->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnectionMatPayments.edit', [$serviceConnectionMatPayments->id]) }}"
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
