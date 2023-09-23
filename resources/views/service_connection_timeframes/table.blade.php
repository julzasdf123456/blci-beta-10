<div class="table-responsive">
    <table class="table" id="serviceConnectionTimeframes-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Userid</th>
        <th>Status</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnectionTimeframes as $serviceConnectionTimeframes)
            <tr>
                <td>{{ $serviceConnectionTimeframes->ServiceConnectionId }}</td>
            <td>{{ $serviceConnectionTimeframes->UserId }}</td>
            <td>{{ $serviceConnectionTimeframes->Status }}</td>
            <td>{{ $serviceConnectionTimeframes->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnectionTimeframes.destroy', $serviceConnectionTimeframes->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnectionTimeframes.show', [$serviceConnectionTimeframes->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnectionTimeframes.edit', [$serviceConnectionTimeframes->id]) }}"
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
