<div class="table-responsive">
    <table class="table" id="billOfMaterialsDetails-table">
        <thead>
        <tr>
            <th>Billofmaterialsid</th>
        <th>Neacode</th>
        <th>Description</th>
        <th>Rate</th>
        <th>Quantity</th>
        <th>Amount</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($billOfMaterialsDetails as $billOfMaterialsDetails)
            <tr>
                <td>{{ $billOfMaterialsDetails->BillOfMaterialsId }}</td>
            <td>{{ $billOfMaterialsDetails->NeaCode }}</td>
            <td>{{ $billOfMaterialsDetails->Description }}</td>
            <td>{{ $billOfMaterialsDetails->Rate }}</td>
            <td>{{ $billOfMaterialsDetails->Quantity }}</td>
            <td>{{ $billOfMaterialsDetails->Amount }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['billOfMaterialsDetails.destroy', $billOfMaterialsDetails->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('billOfMaterialsDetails.show', [$billOfMaterialsDetails->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('billOfMaterialsDetails.edit', [$billOfMaterialsDetails->id]) }}"
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
