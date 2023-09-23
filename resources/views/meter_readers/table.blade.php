<div class="table-responsive">
    <table class="table" id="meterReaders-table">
        <thead>
        <tr>
            <th>Meterreadercode</th>
        <th>Userid</th>
        <th>Devicemacaddress</th>
        <th>Areacodeassignment</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($meterReaders as $meterReaders)
            <tr>
                <td>{{ $meterReaders->MeterReaderCode }}</td>
            <td>{{ $meterReaders->UserId }}</td>
            <td>{{ $meterReaders->DeviceMacAddress }}</td>
            <td>{{ $meterReaders->AreaCodeAssignment }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['meterReaders.destroy', $meterReaders->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('meterReaders.show', [$meterReaders->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('meterReaders.edit', [$meterReaders->id]) }}"
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
