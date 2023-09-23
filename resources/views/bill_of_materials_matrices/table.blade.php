<div class="table-responsive">
    <table class="table" id="billOfMaterialsMatrices-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Structureassigningid</th>
        <th>Structureid</th>
        <th>Materialsid</th>
        <th>Quantity</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($billOfMaterialsMatrices as $billOfMaterialsMatrix)
            <tr>
                <td>{{ $billOfMaterialsMatrix->ServiceConnectionId }}</td>
            <td>{{ $billOfMaterialsMatrix->StructureAssigningId }}</td>
            <td>{{ $billOfMaterialsMatrix->StructureId }}</td>
            <td>{{ $billOfMaterialsMatrix->MaterialsId }}</td>
            <td>{{ $billOfMaterialsMatrix->Quantity }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['billOfMaterialsMatrices.destroy', $billOfMaterialsMatrix->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('billOfMaterialsMatrices.show', [$billOfMaterialsMatrix->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('billOfMaterialsMatrices.edit', [$billOfMaterialsMatrix->id]) }}"
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
