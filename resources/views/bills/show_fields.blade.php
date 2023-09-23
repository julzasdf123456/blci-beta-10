<!-- Billnumber Field -->
<div class="col-sm-12">
    {!! Form::label('BillNumber', 'Billnumber:') !!}
    <p>{{ $bills->BillNumber }}</p>
</div>

<!-- Accountnumber Field -->
<div class="col-sm-12">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    <p>{{ $bills->AccountNumber }}</p>
</div>

<!-- Serviceperiod Field -->
<div class="col-sm-12">
    {!! Form::label('ServicePeriod', 'Serviceperiod:') !!}
    <p>{{ $bills->ServicePeriod }}</p>
</div>

<!-- Multiplier Field -->
<div class="col-sm-12">
    {!! Form::label('Multiplier', 'Multiplier:') !!}
    <p>{{ $bills->Multiplier }}</p>
</div>

<!-- Coreloss Field -->
<div class="col-sm-12">
    {!! Form::label('Coreloss', 'Coreloss:') !!}
    <p>{{ $bills->Coreloss }}</p>
</div>

<!-- Kwhused Field -->
<div class="col-sm-12">
    {!! Form::label('KwhUsed', 'Kwhused:') !!}
    <p>{{ $bills->KwhUsed }}</p>
</div>

<!-- Previouskwh Field -->
<div class="col-sm-12">
    {!! Form::label('PreviousKwh', 'Previouskwh:') !!}
    <p>{{ $bills->PreviousKwh }}</p>
</div>

<!-- Presentkwh Field -->
<div class="col-sm-12">
    {!! Form::label('PresentKwh', 'Presentkwh:') !!}
    <p>{{ $bills->PresentKwh }}</p>
</div>

<!-- Demandpreviouskwh Field -->
<div class="col-sm-12">
    {!! Form::label('DemandPreviousKwh', 'Demandpreviouskwh:') !!}
    <p>{{ $bills->DemandPreviousKwh }}</p>
</div>

<!-- Demandpresentkwh Field -->
<div class="col-sm-12">
    {!! Form::label('DemandPresentKwh', 'Demandpresentkwh:') !!}
    <p>{{ $bills->DemandPresentKwh }}</p>
</div>

<!-- Additionalkwh Field -->
<div class="col-sm-12">
    {!! Form::label('AdditionalKwh', 'Additionalkwh:') !!}
    <p>{{ $bills->AdditionalKwh }}</p>
</div>

<!-- Additionaldemandkwh Field -->
<div class="col-sm-12">
    {!! Form::label('AdditionalDemandKwh', 'Additionaldemandkwh:') !!}
    <p>{{ $bills->AdditionalDemandKwh }}</p>
</div>

<!-- Kwhamount Field -->
<div class="col-sm-12">
    {!! Form::label('KwhAmount', 'Kwhamount:') !!}
    <p>{{ $bills->KwhAmount }}</p>
</div>

<!-- Effectiverate Field -->
<div class="col-sm-12">
    {!! Form::label('EffectiveRate', 'Effectiverate:') !!}
    <p>{{ $bills->EffectiveRate }}</p>
</div>

<!-- Additionalcharges Field -->
<div class="col-sm-12">
    {!! Form::label('AdditionalCharges', 'Additionalcharges:') !!}
    <p>{{ $bills->AdditionalCharges }}</p>
</div>

<!-- Deductions Field -->
<div class="col-sm-12">
    {!! Form::label('Deductions', 'Deductions:') !!}
    <p>{{ $bills->Deductions }}</p>
</div>

<!-- Netamount Field -->
<div class="col-sm-12">
    {!! Form::label('NetAmount', 'Netamount:') !!}
    <p>{{ $bills->NetAmount }}</p>
</div>

<!-- Billingdate Field -->
<div class="col-sm-12">
    {!! Form::label('BillingDate', 'Billingdate:') !!}
    <p>{{ $bills->BillingDate }}</p>
</div>

<!-- Servicedatefrom Field -->
<div class="col-sm-12">
    {!! Form::label('ServiceDateFrom', 'Servicedatefrom:') !!}
    <p>{{ $bills->ServiceDateFrom }}</p>
</div>

<!-- Servicedateto Field -->
<div class="col-sm-12">
    {!! Form::label('ServiceDateTo', 'Servicedateto:') !!}
    <p>{{ $bills->ServiceDateTo }}</p>
</div>

<!-- Duedate Field -->
<div class="col-sm-12">
    {!! Form::label('DueDate', 'Duedate:') !!}
    <p>{{ $bills->DueDate }}</p>
</div>

<!-- Meternumber Field -->
<div class="col-sm-12">
    {!! Form::label('MeterNumber', 'Meternumber:') !!}
    <p>{{ $bills->MeterNumber }}</p>
</div>

<!-- Consumertype Field -->
<div class="col-sm-12">
    {!! Form::label('ConsumerType', 'Consumertype:') !!}
    <p>{{ $bills->ConsumerType }}</p>
</div>

<!-- Billtype Field -->
<div class="col-sm-12">
    {!! Form::label('BillType', 'Billtype:') !!}
    <p>{{ $bills->BillType }}</p>
</div>

<!-- Generationsystemcharge Field -->
<div class="col-sm-12">
    {!! Form::label('GenerationSystemCharge', 'Generationsystemcharge:') !!}
    <p>{{ $bills->GenerationSystemCharge }}</p>
</div>

<!-- Transmissiondeliverychargekw Field -->
<div class="col-sm-12">
    {!! Form::label('TransmissionDeliveryChargeKW', 'Transmissiondeliverychargekw:') !!}
    <p>{{ $bills->TransmissionDeliveryChargeKW }}</p>
</div>

<!-- Transmissiondeliverychargekwh Field -->
<div class="col-sm-12">
    {!! Form::label('TransmissionDeliveryChargeKWH', 'Transmissiondeliverychargekwh:') !!}
    <p>{{ $bills->TransmissionDeliveryChargeKWH }}</p>
</div>

<!-- Systemlosscharge Field -->
<div class="col-sm-12">
    {!! Form::label('SystemLossCharge', 'Systemlosscharge:') !!}
    <p>{{ $bills->SystemLossCharge }}</p>
</div>

<!-- Distributiondemandcharge Field -->
<div class="col-sm-12">
    {!! Form::label('DistributionDemandCharge', 'Distributiondemandcharge:') !!}
    <p>{{ $bills->DistributionDemandCharge }}</p>
</div>

<!-- Distributionsystemcharge Field -->
<div class="col-sm-12">
    {!! Form::label('DistributionSystemCharge', 'Distributionsystemcharge:') !!}
    <p>{{ $bills->DistributionSystemCharge }}</p>
</div>

<!-- Supplyretailcustomercharge Field -->
<div class="col-sm-12">
    {!! Form::label('SupplyRetailCustomerCharge', 'Supplyretailcustomercharge:') !!}
    <p>{{ $bills->SupplyRetailCustomerCharge }}</p>
</div>

<!-- Supplysystemcharge Field -->
<div class="col-sm-12">
    {!! Form::label('SupplySystemCharge', 'Supplysystemcharge:') !!}
    <p>{{ $bills->SupplySystemCharge }}</p>
</div>

<!-- Meteringretailcustomercharge Field -->
<div class="col-sm-12">
    {!! Form::label('MeteringRetailCustomerCharge', 'Meteringretailcustomercharge:') !!}
    <p>{{ $bills->MeteringRetailCustomerCharge }}</p>
</div>

<!-- Meteringsystemcharge Field -->
<div class="col-sm-12">
    {!! Form::label('MeteringSystemCharge', 'Meteringsystemcharge:') !!}
    <p>{{ $bills->MeteringSystemCharge }}</p>
</div>

<!-- Rfsc Field -->
<div class="col-sm-12">
    {!! Form::label('RFSC', 'Rfsc:') !!}
    <p>{{ $bills->RFSC }}</p>
</div>

<!-- Lifelinerate Field -->
<div class="col-sm-12">
    {!! Form::label('LifelineRate', 'Lifelinerate:') !!}
    <p>{{ $bills->LifelineRate }}</p>
</div>

<!-- Interclasscrosssubsidycharge Field -->
<div class="col-sm-12">
    {!! Form::label('InterClassCrossSubsidyCharge', 'Interclasscrosssubsidycharge:') !!}
    <p>{{ $bills->InterClassCrossSubsidyCharge }}</p>
</div>

<!-- Pparefund Field -->
<div class="col-sm-12">
    {!! Form::label('PPARefund', 'Pparefund:') !!}
    <p>{{ $bills->PPARefund }}</p>
</div>

<!-- Seniorcitizensubsidy Field -->
<div class="col-sm-12">
    {!! Form::label('SeniorCitizenSubsidy', 'Seniorcitizensubsidy:') !!}
    <p>{{ $bills->SeniorCitizenSubsidy }}</p>
</div>

<!-- Missionaryelectrificationcharge Field -->
<div class="col-sm-12">
    {!! Form::label('MissionaryElectrificationCharge', 'Missionaryelectrificationcharge:') !!}
    <p>{{ $bills->MissionaryElectrificationCharge }}</p>
</div>

<!-- Environmentalcharge Field -->
<div class="col-sm-12">
    {!! Form::label('EnvironmentalCharge', 'Environmentalcharge:') !!}
    <p>{{ $bills->EnvironmentalCharge }}</p>
</div>

<!-- Strandedcontractcosts Field -->
<div class="col-sm-12">
    {!! Form::label('StrandedContractCosts', 'Strandedcontractcosts:') !!}
    <p>{{ $bills->StrandedContractCosts }}</p>
</div>

<!-- Npcstrandeddebt Field -->
<div class="col-sm-12">
    {!! Form::label('NPCStrandedDebt', 'Npcstrandeddebt:') !!}
    <p>{{ $bills->NPCStrandedDebt }}</p>
</div>

<!-- Feedintariffallowance Field -->
<div class="col-sm-12">
    {!! Form::label('FeedInTariffAllowance', 'Feedintariffallowance:') !!}
    <p>{{ $bills->FeedInTariffAllowance }}</p>
</div>

<!-- Missionaryelectrificationredci Field -->
<div class="col-sm-12">
    {!! Form::label('MissionaryElectrificationREDCI', 'Missionaryelectrificationredci:') !!}
    <p>{{ $bills->MissionaryElectrificationREDCI }}</p>
</div>

<!-- Generationvat Field -->
<div class="col-sm-12">
    {!! Form::label('GenerationVAT', 'Generationvat:') !!}
    <p>{{ $bills->GenerationVAT }}</p>
</div>

<!-- Transmissionvat Field -->
<div class="col-sm-12">
    {!! Form::label('TransmissionVAT', 'Transmissionvat:') !!}
    <p>{{ $bills->TransmissionVAT }}</p>
</div>

<!-- Systemlossvat Field -->
<div class="col-sm-12">
    {!! Form::label('SystemLossVAT', 'Systemlossvat:') !!}
    <p>{{ $bills->SystemLossVAT }}</p>
</div>

<!-- Distributionvat Field -->
<div class="col-sm-12">
    {!! Form::label('DistributionVAT', 'Distributionvat:') !!}
    <p>{{ $bills->DistributionVAT }}</p>
</div>

<!-- Realpropertytax Field -->
<div class="col-sm-12">
    {!! Form::label('RealPropertyTax', 'Realpropertytax:') !!}
    <p>{{ $bills->RealPropertyTax }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    <p>{{ $bills->Notes }}</p>
</div>

<!-- Userid Field -->
<div class="col-sm-12">
    {!! Form::label('UserId', 'Userid:') !!}
    <p>{{ $bills->UserId }}</p>
</div>

<!-- Billedfrom Field -->
<div class="col-sm-12">
    {!! Form::label('BilledFrom', 'Billedfrom:') !!}
    <p>{{ $bills->BilledFrom }}</p>
</div>

