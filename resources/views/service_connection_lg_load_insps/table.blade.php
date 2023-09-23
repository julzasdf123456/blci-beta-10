<div class="table-responsive">
    <table class="table" id="serviceConnectionLgLoadInsps-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Assessment</th>
        <th>Dateofinspection</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnectionLgLoadInsps as $serviceConnectionLgLoadInsp)
            <tr>
                <td>{{ $serviceConnectionLgLoadInsp->ServiceConnectionId }}</td>
            <td>{{ $serviceConnectionLgLoadInsp->Assessment }}</td>
            <td>{{ $serviceConnectionLgLoadInsp->DateOfInspection }}</td>
            <td>{{ $serviceConnectionLgLoadInsp->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnectionLgLoadInsps.destroy', $serviceConnectionLgLoadInsp->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnectionLgLoadInsps.show', [$serviceConnectionLgLoadInsp->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnectionLgLoadInsps.edit', [$serviceConnectionLgLoadInsp->id]) }}"
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
