<div class="table-responsive">
    <table class="table" id="structures-table">
        <thead>
        <tr>
            <th>Type</th>
        <th>Data</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($structures as $structures)
            <tr>
                <td>{{ $structures->Type }}</td>
            <td>{{ $structures->Data }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['structures.destroy', $structures->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('structures.show', [$structures->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('structures.edit', [$structures->id]) }}"
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
