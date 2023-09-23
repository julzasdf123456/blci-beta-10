<div class="table-responsive">
    <table class="table" id="materialsMatrices-table">
        <thead>
        <tr>
            <th>Structureid</th>
        <th>Materialsid</th>
        <th>Quantity</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($materialsMatrices as $materialsMatrix)
            <tr>
                <td>{{ $materialsMatrix->StructureId }}</td>
            <td>{{ $materialsMatrix->MaterialsId }}</td>
            <td>{{ $materialsMatrix->Quantity }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['materialsMatrices.destroy', $materialsMatrix->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('materialsMatrices.show', [$materialsMatrix->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('materialsMatrices.edit', [$materialsMatrix->id]) }}"
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
