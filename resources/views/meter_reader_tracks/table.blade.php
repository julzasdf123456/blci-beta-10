<div class="table-responsive">
    <table class="table" id="meterReaderTracks-table">
        <thead>
        <tr>
            <th>Tracknameid</th>
        <th>Latitude</th>
        <th>Longitude</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($meterReaderTracks as $meterReaderTracks)
            <tr>
                <td>{{ $meterReaderTracks->TrackNameId }}</td>
            <td>{{ $meterReaderTracks->Latitude }}</td>
            <td>{{ $meterReaderTracks->Longitude }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['meterReaderTracks.destroy', $meterReaderTracks->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('meterReaderTracks.show', [$meterReaderTracks->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('meterReaderTracks.edit', [$meterReaderTracks->id]) }}"
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
