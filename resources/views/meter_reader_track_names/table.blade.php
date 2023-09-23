<div class="table-responsive">
    <table class="table" id="meterReaderTrackNames-table">
        <thead>
        <tr>
            <th>Trackname</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($meterReaderTrackNames as $meterReaderTrackNames)
            <tr>
                <td>{{ $meterReaderTrackNames->TrackName }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['meterReaderTrackNames.destroy', $meterReaderTrackNames->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('meterReaderTrackNames.show', [$meterReaderTrackNames->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('meterReaderTrackNames.edit', [$meterReaderTrackNames->id]) }}"
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
