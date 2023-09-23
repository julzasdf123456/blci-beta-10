<div class="table-responsive">
    <table class="table" id="bills-table">
        <thead>
        <tr>
            <th>Billnumber</th>
        <th>Accountnumber</th>
        <th>Serviceperiod</th>
        <th>Multiplier</th>
        <th>Coreloss</th>
        <th>Kwhused</th>
        <th>Previouskwh</th>
        <th>Presentkwh</th>
        <th>Demandpreviouskwh</th>
        <th>Demandpresentkwh</th>
        <th>Additionalkwh</th>
        <th>Additionaldemandkwh</th>
        <th>Kwhamount</th>
        <th>Effectiverate</th>
        <th>Additionalcharges</th>
        <th>Deductions</th>
        <th>Netamount</th>
        <th>Billingdate</th>
        <th>Servicedatefrom</th>
        <th>Servicedateto</th>
        <th>Duedate</th>
        <th>Meternumber</th>
        <th>Consumertype</th>
        <th>Billtype</th>
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
        <th>Realpropertytax</th>
        <th>Notes</th>
        <th>Userid</th>
        <th>Billedfrom</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($bills as $bills)
            <tr>
                <td>{{ $bills->BillNumber }}</td>
            <td>{{ $bills->AccountNumber }}</td>
            <td>{{ $bills->ServicePeriod }}</td>
            <td>{{ $bills->Multiplier }}</td>
            <td>{{ $bills->Coreloss }}</td>
            <td>{{ $bills->KwhUsed }}</td>
            <td>{{ $bills->PreviousKwh }}</td>
            <td>{{ $bills->PresentKwh }}</td>
            <td>{{ $bills->DemandPreviousKwh }}</td>
            <td>{{ $bills->DemandPresentKwh }}</td>
            <td>{{ $bills->AdditionalKwh }}</td>
            <td>{{ $bills->AdditionalDemandKwh }}</td>
            <td>{{ $bills->KwhAmount }}</td>
            <td>{{ $bills->EffectiveRate }}</td>
            <td>{{ $bills->AdditionalCharges }}</td>
            <td>{{ $bills->Deductions }}</td>
            <td>{{ $bills->NetAmount }}</td>
            <td>{{ $bills->BillingDate }}</td>
            <td>{{ $bills->ServiceDateFrom }}</td>
            <td>{{ $bills->ServiceDateTo }}</td>
            <td>{{ $bills->DueDate }}</td>
            <td>{{ $bills->MeterNumber }}</td>
            <td>{{ $bills->ConsumerType }}</td>
            <td>{{ $bills->BillType }}</td>
            <td>{{ $bills->GenerationSystemCharge }}</td>
            <td>{{ $bills->TransmissionDeliveryChargeKW }}</td>
            <td>{{ $bills->TransmissionDeliveryChargeKWH }}</td>
            <td>{{ $bills->SystemLossCharge }}</td>
            <td>{{ $bills->DistributionDemandCharge }}</td>
            <td>{{ $bills->DistributionSystemCharge }}</td>
            <td>{{ $bills->SupplyRetailCustomerCharge }}</td>
            <td>{{ $bills->SupplySystemCharge }}</td>
            <td>{{ $bills->MeteringRetailCustomerCharge }}</td>
            <td>{{ $bills->MeteringSystemCharge }}</td>
            <td>{{ $bills->RFSC }}</td>
            <td>{{ $bills->LifelineRate }}</td>
            <td>{{ $bills->InterClassCrossSubsidyCharge }}</td>
            <td>{{ $bills->PPARefund }}</td>
            <td>{{ $bills->SeniorCitizenSubsidy }}</td>
            <td>{{ $bills->MissionaryElectrificationCharge }}</td>
            <td>{{ $bills->EnvironmentalCharge }}</td>
            <td>{{ $bills->StrandedContractCosts }}</td>
            <td>{{ $bills->NPCStrandedDebt }}</td>
            <td>{{ $bills->FeedInTariffAllowance }}</td>
            <td>{{ $bills->MissionaryElectrificationREDCI }}</td>
            <td>{{ $bills->GenerationVAT }}</td>
            <td>{{ $bills->TransmissionVAT }}</td>
            <td>{{ $bills->SystemLossVAT }}</td>
            <td>{{ $bills->DistributionVAT }}</td>
            <td>{{ $bills->RealPropertyTax }}</td>
            <td>{{ $bills->Notes }}</td>
            <td>{{ $bills->UserId }}</td>
            <td>{{ $bills->BilledFrom }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['bills.destroy', $bills->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('bills.show', [$bills->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('bills.edit', [$bills->id]) }}"
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
