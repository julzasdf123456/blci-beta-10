<div class="table-responsive">
    <table class="table" id="zones-table">
        <thead>
        <tr>
            <th>Zone</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($zones as $zones)
            <tr>
                <td>{{ $zones->Zone }}</td>
            <td>{{ $zones->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['zones.destroy', $zones->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('zones.show', [$zones->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('zones.edit', [$zones->id]) }}"
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
