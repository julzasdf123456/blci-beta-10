<div class="table-responsive">
    <table class="table" id="serviceConnectionChecklists-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Checklistid</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnectionChecklists as $serviceConnectionChecklists)
            <tr>
                <td>{{ $serviceConnectionChecklists->ServiceConnectionId }}</td>
            <td>{{ $serviceConnectionChecklists->ChecklistId }}</td>
            <td>{{ $serviceConnectionChecklists->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnectionChecklists.destroy', $serviceConnectionChecklists->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnectionChecklists.show', [$serviceConnectionChecklists->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnectionChecklists.edit', [$serviceConnectionChecklists->id]) }}"
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
