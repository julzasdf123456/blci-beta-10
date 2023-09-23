<div class="table-responsive">
    <table class="table" id="memberConsumerChecklistsReps-table">
        <thead>
        <tr>
            <th>Checklist</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($memberConsumerChecklistsReps as $memberConsumerChecklistsRep)
            <tr>
                <td>{{ $memberConsumerChecklistsRep->Checklist }}</td>
            <td>{{ $memberConsumerChecklistsRep->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['memberConsumerChecklistsReps.destroy', $memberConsumerChecklistsRep->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('memberConsumerChecklistsReps.show', [$memberConsumerChecklistsRep->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('memberConsumerChecklistsReps.edit', [$memberConsumerChecklistsRep->id]) }}"
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
