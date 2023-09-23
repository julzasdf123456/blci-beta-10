<div class="table-responsive">
    <table class="table" id="tickets-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Consumername</th>
        <th>Town</th>
        <th>Barangay</th>
        <th>Sitio</th>
        <th>Ticket</th>
        <th>Reason</th>
        <th>Contactnumber</th>
        <th>Reportedby</th>
        <th>Ornumber</th>
        <th>Ordate</th>
        <th>Geolocation</th>
        <th>Neighbor1</th>
        <th>Neighbor2</th>
        <th>Notes</th>
        <th>Status</th>
        <th>Datetimedownloaded</th>
        <th>Datetimelinemanarrived</th>
        <th>Datetimelinemanexecuted</th>
        <th>Userid</th>
        <th>Crewassigned</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tickets as $tickets)
            <tr>
                <td>{{ $tickets->AccountNumber }}</td>
            <td>{{ $tickets->ConsumerName }}</td>
            <td>{{ $tickets->Town }}</td>
            <td>{{ $tickets->Barangay }}</td>
            <td>{{ $tickets->Sitio }}</td>
            <td>{{ $tickets->Ticket }}</td>
            <td>{{ $tickets->Reason }}</td>
            <td>{{ $tickets->ContactNumber }}</td>
            <td>{{ $tickets->ReportedBy }}</td>
            <td>{{ $tickets->ORNumber }}</td>
            <td>{{ $tickets->ORDate }}</td>
            <td>{{ $tickets->GeoLocation }}</td>
            <td>{{ $tickets->Neighbor1 }}</td>
            <td>{{ $tickets->Neighbor2 }}</td>
            <td>{{ $tickets->Notes }}</td>
            <td>{{ $tickets->Status }}</td>
            <td>{{ $tickets->DateTimeDownloaded }}</td>
            <td>{{ $tickets->DateTimeLinemanArrived }}</td>
            <td>{{ $tickets->DateTimeLinemanExecuted }}</td>
            <td>{{ $tickets->UserId }}</td>
            <td>{{ $tickets->CrewAssigned }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['tickets.destroy', $tickets->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('tickets.show', [$tickets->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('tickets.edit', [$tickets->id]) }}"
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
