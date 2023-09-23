<div class="table-responsive">
    <table class="table" id="memberConsumerChecklists-table">
        <thead>
        <tr>
            <th>Memberconsumerid</th>
        <th>Checklistid</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($memberConsumerChecklists as $memberConsumerChecklists)
            <tr>
                <td>{{ $memberConsumerChecklists->MemberConsumerId }}</td>
            <td>{{ $memberConsumerChecklists->ChecklistId }}</td>
            <td>{{ $memberConsumerChecklists->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['memberConsumerChecklists.destroy', $memberConsumerChecklists->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('memberConsumerChecklists.show', [$memberConsumerChecklists->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('memberConsumerChecklists.edit', [$memberConsumerChecklists->id]) }}"
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
