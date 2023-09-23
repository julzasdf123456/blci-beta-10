<div class="table-responsive">
    <table class="table" id="dCRIndices-table">
        <thead>
        <tr>
            <th>Glcode</th>
        <th>Neacode</th>
        <th>Tablename</th>
        <th>Columns</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($dCRIndices as $dCRIndex)
            <tr>
                <td>{{ $dCRIndex->GLCode }}</td>
            <td>{{ $dCRIndex->NEACode }}</td>
            <td>{{ $dCRIndex->TableName }}</td>
            <td>{{ $dCRIndex->Columns }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['dCRIndices.destroy', $dCRIndex->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('dCRIndices.show', [$dCRIndex->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('dCRIndices.edit', [$dCRIndex->id]) }}"
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
