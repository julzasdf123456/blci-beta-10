<div class="table-responsive">
    <table class="table" id="billsOfMaterialsSummaries-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Excludetransformerlaborcost</th>
        <th>Transformerchangedprice</th>
        <th>Monthduration</th>
        <th>Transformerlaborcostpercentage</th>
        <th>Materiallaborcostpercentage</th>
        <th>Handlingcostpercentage</th>
        <th>Subtotal</th>
        <th>Laborcost</th>
        <th>Handlingcost</th>
        <th>Total</th>
        <th>Totalvat</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($billsOfMaterialsSummaries as $billsOfMaterialsSummary)
            <tr>
                <td>{{ $billsOfMaterialsSummary->ServiceConnectionId }}</td>
            <td>{{ $billsOfMaterialsSummary->ExcludeTransformerLaborCost }}</td>
            <td>{{ $billsOfMaterialsSummary->TransformerChangedPrice }}</td>
            <td>{{ $billsOfMaterialsSummary->MonthDuration }}</td>
            <td>{{ $billsOfMaterialsSummary->TransformerLaborCostPercentage }}</td>
            <td>{{ $billsOfMaterialsSummary->MaterialLaborCostPercentage }}</td>
            <td>{{ $billsOfMaterialsSummary->HandlingCostPercentage }}</td>
            <td>{{ $billsOfMaterialsSummary->SubTotal }}</td>
            <td>{{ $billsOfMaterialsSummary->LaborCost }}</td>
            <td>{{ $billsOfMaterialsSummary->HandlingCost }}</td>
            <td>{{ $billsOfMaterialsSummary->Total }}</td>
            <td>{{ $billsOfMaterialsSummary->TotalVAT }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['billsOfMaterialsSummaries.destroy', $billsOfMaterialsSummary->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('billsOfMaterialsSummaries.show', [$billsOfMaterialsSummary->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('billsOfMaterialsSummaries.edit', [$billsOfMaterialsSummary->id]) }}"
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
