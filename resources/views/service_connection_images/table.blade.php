<div class="table-responsive">
    <table class="table" id="serviceConnectionImages-table">
        <thead>
        <tr>
            <th>Photo</th>
        <th>Serviceconnectionid</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnectionImages as $serviceConnectionImages)
            <tr>
                <td>{{ $serviceConnectionImages->Photo }}</td>
            <td>{{ $serviceConnectionImages->ServiceConnectionId }}</td>
            <td>{{ $serviceConnectionImages->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnectionImages.destroy', $serviceConnectionImages->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnectionImages.show', [$serviceConnectionImages->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnectionImages.edit', [$serviceConnectionImages->id]) }}"
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
