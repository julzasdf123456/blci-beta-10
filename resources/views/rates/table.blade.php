<div class="table-responsive">
    <table class="table" id="rates-table">
        <thead>
        <tr>
            <th>Ratefor</th>
        <th>Consumertype</th>
        <th>Serviceperiod</th>
        <th>Notes</th>
        <th>Generationsystemcharge</th>
        <th>Transmissiondeliverychargekw</th>
        <th>Transmissiondeliverychargekwh</th>
        <th>Systemlosscharge</th>
        <th>Distributiondemandcharge</th>
        <th>Distributionsystemcharge</th>
        <th>Supplyretailcustomercharge</th>
        <th>Supplysystemcharge</th>
        <th>Meteringretailcustomercharge</th>
        <th>Meteringsystemcharge</th>
        <th>Rfsc</th>
        <th>Lifelinerate</th>
        <th>Interclasscrosssubsidycharge</th>
        <th>Pparefund</th>
        <th>Seniorcitizensubsidy</th>
        <th>Missionaryelectrificationcharge</th>
        <th>Environmentalcharge</th>
        <th>Strandedcontractcosts</th>
        <th>Npcstrandeddebt</th>
        <th>Feedintariffallowance</th>
        <th>Missionaryelectrificationredci</th>
        <th>Generationvat</th>
        <th>Transmissionvat</th>
        <th>Systemlossvat</th>
        <th>Distributionvat</th>
        <th>Totalratevatexcluded</th>
        <th>Totalratevatincluded</th>
        <th>Userid</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rates as $rates)
            <tr>
                <td>{{ $rates->RateFor }}</td>
            <td>{{ $rates->ConsumerType }}</td>
            <td>{{ $rates->ServicePeriod }}</td>
            <td>{{ $rates->Notes }}</td>
            <td>{{ $rates->GenerationSystemCharge }}</td>
            <td>{{ $rates->TransmissionDeliveryChargeKW }}</td>
            <td>{{ $rates->TransmissionDeliveryChargeKWH }}</td>
            <td>{{ $rates->SystemLossCharge }}</td>
            <td>{{ $rates->DistributionDemandCharge }}</td>
            <td>{{ $rates->DistributionSystemCharge }}</td>
            <td>{{ $rates->SupplyRetailCustomerCharge }}</td>
            <td>{{ $rates->SupplySystemCharge }}</td>
            <td>{{ $rates->MeteringRetailCustomerCharge }}</td>
            <td>{{ $rates->MeteringSystemCharge }}</td>
            <td>{{ $rates->RFSC }}</td>
            <td>{{ $rates->LifelineRate }}</td>
            <td>{{ $rates->InterClassCrossSubsidyCharge }}</td>
            <td>{{ $rates->PPARefund }}</td>
            <td>{{ $rates->SeniorCitizenSubsidy }}</td>
            <td>{{ $rates->MissionaryElectrificationCharge }}</td>
            <td>{{ $rates->EnvironmentalCharge }}</td>
            <td>{{ $rates->StrandedContractCosts }}</td>
            <td>{{ $rates->NPCStrandedDebt }}</td>
            <td>{{ $rates->FeedInTariffAllowance }}</td>
            <td>{{ $rates->MissionaryElectrificationREDCI }}</td>
            <td>{{ $rates->GenerationVAT }}</td>
            <td>{{ $rates->TransmissionVAT }}</td>
            <td>{{ $rates->SystemLossVAT }}</td>
            <td>{{ $rates->DistributionVAT }}</td>
            <td>{{ $rates->TotalRateVATExcluded }}</td>
            <td>{{ $rates->TotalRateVATIncluded }}</td>
            <td>{{ $rates->UserId }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['rates.destroy', $rates->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('rates.show', [$rates->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('rates.edit', [$rates->id]) }}"
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
