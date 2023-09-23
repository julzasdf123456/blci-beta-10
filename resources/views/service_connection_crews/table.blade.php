<div class="table-responsive">
    <table class="table" id="serviceConnectionCrews-table">
        <thead>
        <tr>
            <th>Stationname</th>
        <th>Crewleader</th>
        <th>Members</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnectionCrews as $serviceConnectionCrew)
            <tr>
                <td>{{ $serviceConnectionCrew->StationName }}</td>
            <td>{{ $serviceConnectionCrew->CrewLeader }}</td>
            <td>{{ $serviceConnectionCrew->Members }}</td>
            <td>{{ $serviceConnectionCrew->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnectionCrews.destroy', $serviceConnectionCrew->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnectionCrews.show', [$serviceConnectionCrew->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnectionCrews.edit', [$serviceConnectionCrew->id]) }}"
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
