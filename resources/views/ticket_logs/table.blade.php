<div class="table-responsive">
    <table class="table" id="ticketLogs-table">
        <thead>
        <tr>
            <th>Ticketid</th>
        <th>Log</th>
        <th>Logdetails</th>
        <th>Logtype</th>
        <th>Userid</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($ticketLogs as $ticketLogs)
            <tr>
                <td>{{ $ticketLogs->TicketId }}</td>
            <td>{{ $ticketLogs->Log }}</td>
            <td>{{ $ticketLogs->LogDetails }}</td>
            <td>{{ $ticketLogs->LogType }}</td>
            <td>{{ $ticketLogs->UserId }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['ticketLogs.destroy', $ticketLogs->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('ticketLogs.show', [$ticketLogs->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('ticketLogs.edit', [$ticketLogs->id]) }}"
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
