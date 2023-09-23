<div class="table-responsive">
    <table class="table" id="transformerIndices-table">
        <thead>
        <tr>
            <th>Neacode</th>
            <th>Transformer Description</th>
            <th>Link Fuse</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transformerIndices as $transformerIndex)
            <tr>
                <td>{{ $transformerIndex->NEACode }}</td>
                <td>{{ $transformerIndex->Description }}</td>
                <td>{{ $transformerIndex->LinkFuseCode }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['transformerIndices.destroy', $transformerIndex->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('transformerIndices.show', [$transformerIndex->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('transformerIndices.edit', [$transformerIndex->id]) }}"
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
