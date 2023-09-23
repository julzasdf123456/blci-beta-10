<div class="table-responsive">
    <table class="table" id="billOfMaterialsIndices-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Date</th>
        <th>Subtotal</th>
        <th>Laborcost</th>
        <th>Others</th>
        <th>Total</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($billOfMaterialsIndices as $billOfMaterialsIndex)
            <tr>
                <td>{{ $billOfMaterialsIndex->ServiceConnectionId }}</td>
            <td>{{ $billOfMaterialsIndex->Date }}</td>
            <td>{{ $billOfMaterialsIndex->SubTotal }}</td>
            <td>{{ $billOfMaterialsIndex->LaborCost }}</td>
            <td>{{ $billOfMaterialsIndex->Others }}</td>
            <td>{{ $billOfMaterialsIndex->Total }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['billOfMaterialsIndices.destroy', $billOfMaterialsIndex->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('billOfMaterialsIndices.show', [$billOfMaterialsIndex->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('billOfMaterialsIndices.edit', [$billOfMaterialsIndex->id]) }}"
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
