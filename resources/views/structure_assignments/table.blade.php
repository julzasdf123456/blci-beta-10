<div class="table-responsive">
    <table class="table" id="structureAssignments-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Structureid</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($structureAssignments as $structureAssignments)
            <tr>
                <td>{{ $structureAssignments->ServiceConnectionId }}</td>
            <td>{{ $structureAssignments->StructureId }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['structureAssignments.destroy', $structureAssignments->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('structureAssignments.show', [$structureAssignments->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('structureAssignments.edit', [$structureAssignments->id]) }}"
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
