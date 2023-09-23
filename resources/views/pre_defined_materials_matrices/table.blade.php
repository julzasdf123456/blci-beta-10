<div class="table-responsive">
    <table class="table" id="preDefinedMaterialsMatrices-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Neacode</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Options</th>
        <th>Applicationtype</th>
        <th>Cost</th>
        <th>Laborcost</th>
        <th>Notes</th>
        <th>Laborpercentage</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($preDefinedMaterialsMatrices as $preDefinedMaterialsMatrix)
            <tr>
                <td>{{ $preDefinedMaterialsMatrix->ServiceConnectionId }}</td>
            <td>{{ $preDefinedMaterialsMatrix->NEACode }}</td>
            <td>{{ $preDefinedMaterialsMatrix->Description }}</td>
            <td>{{ $preDefinedMaterialsMatrix->Quantity }}</td>
            <td>{{ $preDefinedMaterialsMatrix->Options }}</td>
            <td>{{ $preDefinedMaterialsMatrix->ApplicationType }}</td>
            <td>{{ $preDefinedMaterialsMatrix->Cost }}</td>
            <td>{{ $preDefinedMaterialsMatrix->LaborCost }}</td>
            <td>{{ $preDefinedMaterialsMatrix->Notes }}</td>
            <td>{{ $preDefinedMaterialsMatrix->LaborPercentage }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['preDefinedMaterialsMatrices.destroy', $preDefinedMaterialsMatrix->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('preDefinedMaterialsMatrices.show', [$preDefinedMaterialsMatrix->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('preDefinedMaterialsMatrices.edit', [$preDefinedMaterialsMatrix->id]) }}"
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
