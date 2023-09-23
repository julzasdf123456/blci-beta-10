<div class="table-responsive">
    <table class="table" id="billsOriginals-table">
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
        <th>Othergenerationrateadjustment</th>
        <th>Othertransmissioncostadjustmentkw</th>
        <th>Othertransmissioncostadjustmentkwh</th>
        <th>Othersystemlosscostadjustment</th>
        <th>Otherlifelineratecostadjustment</th>
        <th>Seniorcitizendiscountandsubsidyadjustment</th>
        <th>Franchisetax</th>
        <th>Businesstax</th>
        <th>Adjustmenttype</th>
        <th>Adjustmentnumber</th>
        <th>Adjustedby</th>
        <th>Dateadjusted</th>
        <th>Notes</th>
        <th>Userid</th>
        <th>Billedfrom</th>
        <th>Form2307Amount</th>
        <th>Evat2Percent</th>
        <th>Evat5Percent</th>
        <th>Mergedtocollectible</th>
        <th>Deducteddeposit</th>
        <th>Excessdeposit</th>
        <th>Averagedcount</th>
        <th>Isunlockedforpayment</th>
        <th>Unlockedby</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($billsOriginals as $billsOriginal)
            <tr>
                <td>{{ $billsOriginal->BillNumber }}</td>
            <td>{{ $billsOriginal->AccountNumber }}</td>
            <td>{{ $billsOriginal->ServicePeriod }}</td>
            <td>{{ $billsOriginal->Multiplier }}</td>
            <td>{{ $billsOriginal->Coreloss }}</td>
            <td>{{ $billsOriginal->KwhUsed }}</td>
            <td>{{ $billsOriginal->PreviousKwh }}</td>
            <td>{{ $billsOriginal->PresentKwh }}</td>
            <td>{{ $billsOriginal->DemandPreviousKwh }}</td>
            <td>{{ $billsOriginal->DemandPresentKwh }}</td>
            <td>{{ $billsOriginal->AdditionalKwh }}</td>
            <td>{{ $billsOriginal->AdditionalDemandKwh }}</td>
            <td>{{ $billsOriginal->KwhAmount }}</td>
            <td>{{ $billsOriginal->EffectiveRate }}</td>
            <td>{{ $billsOriginal->AdditionalCharges }}</td>
            <td>{{ $billsOriginal->Deductions }}</td>
            <td>{{ $billsOriginal->NetAmount }}</td>
            <td>{{ $billsOriginal->BillingDate }}</td>
            <td>{{ $billsOriginal->ServiceDateFrom }}</td>
            <td>{{ $billsOriginal->ServiceDateTo }}</td>
            <td>{{ $billsOriginal->DueDate }}</td>
            <td>{{ $billsOriginal->MeterNumber }}</td>
            <td>{{ $billsOriginal->ConsumerType }}</td>
            <td>{{ $billsOriginal->BillType }}</td>
            <td>{{ $billsOriginal->GenerationSystemCharge }}</td>
            <td>{{ $billsOriginal->TransmissionDeliveryChargeKW }}</td>
            <td>{{ $billsOriginal->TransmissionDeliveryChargeKWH }}</td>
            <td>{{ $billsOriginal->SystemLossCharge }}</td>
            <td>{{ $billsOriginal->DistributionDemandCharge }}</td>
            <td>{{ $billsOriginal->DistributionSystemCharge }}</td>
            <td>{{ $billsOriginal->SupplyRetailCustomerCharge }}</td>
            <td>{{ $billsOriginal->SupplySystemCharge }}</td>
            <td>{{ $billsOriginal->MeteringRetailCustomerCharge }}</td>
            <td>{{ $billsOriginal->MeteringSystemCharge }}</td>
            <td>{{ $billsOriginal->RFSC }}</td>
            <td>{{ $billsOriginal->LifelineRate }}</td>
            <td>{{ $billsOriginal->InterClassCrossSubsidyCharge }}</td>
            <td>{{ $billsOriginal->PPARefund }}</td>
            <td>{{ $billsOriginal->SeniorCitizenSubsidy }}</td>
            <td>{{ $billsOriginal->MissionaryElectrificationCharge }}</td>
            <td>{{ $billsOriginal->EnvironmentalCharge }}</td>
            <td>{{ $billsOriginal->StrandedContractCosts }}</td>
            <td>{{ $billsOriginal->NPCStrandedDebt }}</td>
            <td>{{ $billsOriginal->FeedInTariffAllowance }}</td>
            <td>{{ $billsOriginal->MissionaryElectrificationREDCI }}</td>
            <td>{{ $billsOriginal->GenerationVAT }}</td>
            <td>{{ $billsOriginal->TransmissionVAT }}</td>
            <td>{{ $billsOriginal->SystemLossVAT }}</td>
            <td>{{ $billsOriginal->DistributionVAT }}</td>
            <td>{{ $billsOriginal->RealPropertyTax }}</td>
            <td>{{ $billsOriginal->OtherGenerationRateAdjustment }}</td>
            <td>{{ $billsOriginal->OtherTransmissionCostAdjustmentKW }}</td>
            <td>{{ $billsOriginal->OtherTransmissionCostAdjustmentKWH }}</td>
            <td>{{ $billsOriginal->OtherSystemLossCostAdjustment }}</td>
            <td>{{ $billsOriginal->OtherLifelineRateCostAdjustment }}</td>
            <td>{{ $billsOriginal->SeniorCitizenDiscountAndSubsidyAdjustment }}</td>
            <td>{{ $billsOriginal->FranchiseTax }}</td>
            <td>{{ $billsOriginal->BusinessTax }}</td>
            <td>{{ $billsOriginal->AdjustmentType }}</td>
            <td>{{ $billsOriginal->AdjustmentNumber }}</td>
            <td>{{ $billsOriginal->AdjustedBy }}</td>
            <td>{{ $billsOriginal->DateAdjusted }}</td>
            <td>{{ $billsOriginal->Notes }}</td>
            <td>{{ $billsOriginal->UserId }}</td>
            <td>{{ $billsOriginal->BilledFrom }}</td>
            <td>{{ $billsOriginal->Form2307Amount }}</td>
            <td>{{ $billsOriginal->Evat2Percent }}</td>
            <td>{{ $billsOriginal->Evat5Percent }}</td>
            <td>{{ $billsOriginal->MergedToCollectible }}</td>
            <td>{{ $billsOriginal->DeductedDeposit }}</td>
            <td>{{ $billsOriginal->ExcessDeposit }}</td>
            <td>{{ $billsOriginal->AveragedCount }}</td>
            <td>{{ $billsOriginal->IsUnlockedForPayment }}</td>
            <td>{{ $billsOriginal->UnlockedBy }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['billsOriginals.destroy', $billsOriginal->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('billsOriginals.show', [$billsOriginal->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('billsOriginals.edit', [$billsOriginal->id]) }}"
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
