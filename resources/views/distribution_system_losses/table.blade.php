<div class="table-responsive">
    <table class="table" id="distributionSystemLosses-table">
        <thead>
        <tr>
            <th>Serviceperiod</th>
        <th>Victoriassubstation</th>
        <th>Sagaysubstation</th>
        <th>Sancarlossubstation</th>
        <th>Escalantesubstation</th>
        <th>Lopezsubstation</th>
        <th>Cadizsubstation</th>
        <th>Ipisubstation</th>
        <th>Tobosocalatravasubstation</th>
        <th>Victoriasmillingcompany</th>
        <th>Sancarlosbionergy</th>
        <th>Totalenergyinput</th>
        <th>Energysales</th>
        <th>Energyadjustmentrecoveries</th>
        <th>Totalenergyoutput</th>
        <th>Totalsystemloss</th>
        <th>Totalsystemlosspercentage</th>
        <th>Userid</th>
        <th>From</th>
        <th>To</th>
        <th>Status</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($distributionSystemLosses as $distributionSystemLoss)
            <tr>
                <td>{{ $distributionSystemLoss->ServicePeriod }}</td>
            <td>{{ $distributionSystemLoss->VictoriasSubstation }}</td>
            <td>{{ $distributionSystemLoss->SagaySubstation }}</td>
            <td>{{ $distributionSystemLoss->SanCarlosSubstation }}</td>
            <td>{{ $distributionSystemLoss->EscalanteSubstation }}</td>
            <td>{{ $distributionSystemLoss->LopezSubstation }}</td>
            <td>{{ $distributionSystemLoss->CadizSubstation }}</td>
            <td>{{ $distributionSystemLoss->IpiSubstation }}</td>
            <td>{{ $distributionSystemLoss->TobosoCalatravaSubstation }}</td>
            <td>{{ $distributionSystemLoss->VictoriasMillingCompany }}</td>
            <td>{{ $distributionSystemLoss->SanCarlosBionergy }}</td>
            <td>{{ $distributionSystemLoss->TotalEnergyInput }}</td>
            <td>{{ $distributionSystemLoss->EnergySales }}</td>
            <td>{{ $distributionSystemLoss->EnergyAdjustmentRecoveries }}</td>
            <td>{{ $distributionSystemLoss->TotalEnergyOutput }}</td>
            <td>{{ $distributionSystemLoss->TotalSystemLoss }}</td>
            <td>{{ $distributionSystemLoss->TotalSystemLossPercentage }}</td>
            <td>{{ $distributionSystemLoss->UserId }}</td>
            <td>{{ $distributionSystemLoss->From }}</td>
            <td>{{ $distributionSystemLoss->To }}</td>
            <td>{{ $distributionSystemLoss->Status }}</td>
            <td>{{ $distributionSystemLoss->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['distributionSystemLosses.destroy', $distributionSystemLoss->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('distributionSystemLosses.show', [$distributionSystemLoss->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('distributionSystemLosses.edit', [$distributionSystemLoss->id]) }}"
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
