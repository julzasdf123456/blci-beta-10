<div class="table-responsive">
    <table class="table" id="accountPayables-table">
        <thead>
        <tr>
            <th>Accountcode</th>
        <th>Accounttitle</th>
        <th>Accountdescription</th>
        <th>Defaultamount</th>
        <th>Vatpercentage</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($accountPayables as $accountPayables)
            <tr>
                <td>{{ $accountPayables->AccountCode }}</td>
            <td>{{ $accountPayables->AccountTitle }}</td>
            <td>{{ $accountPayables->AccountDescription }}</td>
            <td>{{ $accountPayables->DefaultAmount }}</td>
            <td>{{ $accountPayables->VATPercentage }}</td>
            <td>{{ $accountPayables->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['accountPayables.destroy', $accountPayables->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('accountPayables.show', [$accountPayables->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('accountPayables.edit', [$accountPayables->id]) }}"
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
