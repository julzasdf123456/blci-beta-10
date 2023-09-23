<div class="table-responsive">
    <table class="table" id="mastPoles-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Datetimetaken</th>
        <th>Poleremarks</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($mastPoles as $mastPoles)
            <tr>
                <td>{{ $mastPoles->ServiceConnectionId }}</td>
            <td>{{ $mastPoles->Latitude }}</td>
            <td>{{ $mastPoles->Longitude }}</td>
            <td>{{ $mastPoles->DateTimeTaken }}</td>
            <td>{{ $mastPoles->PoleRemarks }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['mastPoles.destroy', $mastPoles->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('mastPoles.show', [$mastPoles->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('mastPoles.edit', [$mastPoles->id]) }}"
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
