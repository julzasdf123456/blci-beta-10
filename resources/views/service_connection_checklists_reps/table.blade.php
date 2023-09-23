<div class="table-responsive">
    <table class="table" id="serviceConnectionChecklistsReps-table">
        <thead>
        <tr>
            <th>Checklist</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnectionChecklistsReps as $serviceConnectionChecklistsRep)
            <tr>
                <td>{{ $serviceConnectionChecklistsRep->Checklist }}</td>
            <td>{{ $serviceConnectionChecklistsRep->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnectionChecklistsReps.destroy', $serviceConnectionChecklistsRep->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnectionChecklistsReps.show', [$serviceConnectionChecklistsRep->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnectionChecklistsReps.edit', [$serviceConnectionChecklistsRep->id]) }}"
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
