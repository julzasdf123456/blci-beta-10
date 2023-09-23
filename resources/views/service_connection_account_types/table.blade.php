<div class="table-responsive">
    <table class="table" id="serviceConnectionAccountTypes-table">
        <thead>
        <tr>
            <th>Accounttype</th>
            <th>Abbrev</th>
            <th>Description</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnectionAccountTypes as $serviceConnectionAccountTypes)
            <tr>
                <td>{{ $serviceConnectionAccountTypes->AccountType }}</td>
                <td>{{ $serviceConnectionAccountTypes->Alias }}</td>
                <td>{{ $serviceConnectionAccountTypes->Description }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnectionAccountTypes.destroy', $serviceConnectionAccountTypes->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnectionAccountTypes.show', [$serviceConnectionAccountTypes->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnectionAccountTypes.edit', [$serviceConnectionAccountTypes->id]) }}"
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
