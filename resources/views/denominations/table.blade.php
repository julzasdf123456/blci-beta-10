<div class="table-responsive">
    <table class="table" id="denominations-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Serviceperiod</th>
        <th>Onethousand</th>
        <th>Fivehundred</th>
        <th>Onehundred</th>
        <th>Fifty</th>
        <th>Twenty</th>
        <th>Ten</th>
        <th>Five</th>
        <th>Peso</th>
        <th>Cents</th>
        <th>Paidbillid</th>
        <th>Notes</th>
        <th>Total</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($denominations as $denominations)
            <tr>
                <td>{{ $denominations->AccountNumber }}</td>
            <td>{{ $denominations->ServicePeriod }}</td>
            <td>{{ $denominations->OneThousand }}</td>
            <td>{{ $denominations->FiveHundred }}</td>
            <td>{{ $denominations->OneHundred }}</td>
            <td>{{ $denominations->Fifty }}</td>
            <td>{{ $denominations->Twenty }}</td>
            <td>{{ $denominations->Ten }}</td>
            <td>{{ $denominations->Five }}</td>
            <td>{{ $denominations->Peso }}</td>
            <td>{{ $denominations->Cents }}</td>
            <td>{{ $denominations->PaidBillId }}</td>
            <td>{{ $denominations->Notes }}</td>
            <td>{{ $denominations->Total }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['denominations.destroy', $denominations->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('denominations.show', [$denominations->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('denominations.edit', [$denominations->id]) }}"
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
