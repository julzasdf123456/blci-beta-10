<div class="table-responsive">
    <table class="table" id="transformersAssignedMatrices-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Materialsid</th>
        <th>Quantity</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transformersAssignedMatrices as $transformersAssignedMatrix)
            <tr>
                <td>{{ $transformersAssignedMatrix->ServiceConnectionId }}</td>
            <td>{{ $transformersAssignedMatrix->MaterialsId }}</td>
            <td>{{ $transformersAssignedMatrix->Quantity }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['transformersAssignedMatrices.destroy', $transformersAssignedMatrix->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('transformersAssignedMatrices.show', [$transformersAssignedMatrix->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('transformersAssignedMatrices.edit', [$transformersAssignedMatrix->id]) }}"
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
