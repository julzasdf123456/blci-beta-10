<div class="table-responsive">
    <table class="table" id="events-table">
        <thead>
        <tr>
            <th>Eventtitle</th>
        <th>Eventdescription</th>
        <th>Eventstart</th>
        <th>Eventend</th>
        <th>Registrationstart</th>
        <th>Registrationend</th>
        <th>Userid</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($events as $events)
            <tr>
                <td>{{ $events->EventTitle }}</td>
            <td>{{ $events->EventDescription }}</td>
            <td>{{ $events->EventStart }}</td>
            <td>{{ $events->EventEnd }}</td>
            <td>{{ $events->RegistrationStart }}</td>
            <td>{{ $events->RegistrationEnd }}</td>
            <td>{{ $events->UserId }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['events.destroy', $events->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('events.show', [$events->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('events.edit', [$events->id]) }}"
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
