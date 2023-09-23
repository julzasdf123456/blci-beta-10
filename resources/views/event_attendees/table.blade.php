<div class="table-responsive">
    <table class="table" id="eventAttendees-table">
        <thead>
        <tr>
            <th>Eventid</th>
        <th>Haveattended</th>
        <th>Accountnumber</th>
        <th>Name</th>
        <th>Address</th>
        <th>Registeredat</th>
        <th>Registationmedium</th>
        <th>Userid</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($eventAttendees as $eventAttendees)
            <tr>
                <td>{{ $eventAttendees->EventId }}</td>
            <td>{{ $eventAttendees->HaveAttended }}</td>
            <td>{{ $eventAttendees->AccountNumber }}</td>
            <td>{{ $eventAttendees->Name }}</td>
            <td>{{ $eventAttendees->Address }}</td>
            <td>{{ $eventAttendees->RegisteredAt }}</td>
            <td>{{ $eventAttendees->RegistationMedium }}</td>
            <td>{{ $eventAttendees->UserId }}</td>
            <td>{{ $eventAttendees->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['eventAttendees.destroy', $eventAttendees->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('eventAttendees.show', [$eventAttendees->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('eventAttendees.edit', [$eventAttendees->id]) }}"
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
