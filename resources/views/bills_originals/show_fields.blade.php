<!-- Billnumber Field -->
<div class="col-sm-12">
    {!! Form::label('BillNumber', 'Billnumber:') !!}
    <p>{{ $billsOriginal->BillNumber }}</p>
</div>

<!-- Accountnumber Field -->
<div class="col-sm-12">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    <p>{{ $billsOriginal->AccountNumber }}</p>
</div>

<!-- Serviceperiod Field -->
<div class="col-sm-12">
    {!! Form::label('ServicePeriod', 'Serviceperiod:') !!}
    <p>{{ $billsOriginal->ServicePeriod }}</p>
</div>

<!-- Multiplier Field -->
<div class="col-sm-12">
    {!! Form::label('Multiplier', 'Multiplier:') !!}
    <p>{{ $billsOriginal->Multiplier }}</p>
</div>

<!-- Coreloss Field -->
<div class="col-sm-12">
    {!! Form::label('Coreloss', 'Coreloss:') !!}
    <p>{{ $billsOriginal->Coreloss }}</p>
</div>

<!-- Kwhused Field -->
<div class="col-sm-12">
    {!! Form::label('KwhUsed', 'Kwhused:') !!}
    <p>{{ $billsOriginal->KwhUsed }}</p>
</div>

<!-- Previouskwh Field -->
<div class="col-sm-12">
    {!! Form::label('PreviousKwh', 'Previouskwh:') !!}
    <p>{{ $billsOriginal->PreviousKwh }}</p>
</div>

<!-- Presentkwh Field -->
<div class="col-sm-12">
    {!! Form::label('PresentKwh', 'Presentkwh:') !!}
    <p>{{ $billsOriginal->PresentKwh }}</p>
</div>

<!-- Demandpreviouskwh Field -->
<div class="col-sm-12">
    {!! Form::label('DemandPreviousKwh', 'Demandpreviouskwh:') !!}
    <p>{{ $billsOriginal->DemandPreviousKwh }}</p>
</div>

<!-- Demandpresentkwh Field -->
<div class="col-sm-12">
    {!! Form::label('DemandPresentKwh', 'Demandpresentkwh:') !!}
    <p>{{ $billsOriginal->DemandPresentKwh }}</p>
</div>

<!-- Additionalkwh Field -->
<div class="col-sm-12">
    {!! Form::label('AdditionalKwh', 'Additionalkwh:') !!}
    <p>{{ $billsOriginal->AdditionalKwh }}</p>
</div>

<!-- Additionaldemandkwh Field -->
<div class="col-sm-12">
    {!! Form::label('AdditionalDemandKwh', 'Additionaldemandkwh:') !!}
    <p>{{ $billsOriginal->AdditionalDemandKwh }}</p>
</div>

<!-- Kwhamount Field -->
<div class="col-sm-12">
    {!! Form::label('KwhAmount', 'Kwhamount:') !!}
    <p>{{ $billsOriginal->KwhAmount }}</p>
</div>

<!-- Effectiverate Field -->
<div class="col-sm-12">
    {!! Form::label('EffectiveRate', 'Effectiverate:') !!}
    <p>{{ $billsOriginal->EffectiveRate }}</p>
</div>

<!-- Additionalcharges Field -->
<div class="col-sm-12">
    {!! Form::label('AdditionalCharges', 'Additionalcharges:') !!}
    <p>{{ $billsOriginal->AdditionalCharges }}</p>
</div>

<!-- Deductions Field -->
<div class="col-sm-12">
    {!! Form::label('Deductions', 'Deductions:') !!}
    <p>{{ $billsOriginal->Deductions }}</p>
</div>

<!-- Netamount Field -->
<div class="col-sm-12">
    {!! Form::label('NetAmount', 'Netamount:') !!}
    <p>{{ $billsOriginal->NetAmount }}</p>
</div>

<!-- Billingdate Field -->
<div class="col-sm-12">
    {!! Form::label('BillingDate', 'Billingdate:') !!}
    <p>{{ $billsOriginal->BillingDate }}</p>
</div>

<!-- Servicedatefrom Field -->
<div class="col-sm-12">
    {!! Form::label('ServiceDateFrom', 'Servicedatefrom:') !!}
    <p>{{ $billsOriginal->ServiceDateFrom }}</p>
</div>

<!-- Servicedateto Field -->
<div class="col-sm-12">
    {!! Form::label('ServiceDateTo', 'Servicedateto:') !!}
    <p>{{ $billsOriginal->ServiceDateTo }}</p>
</div>

<!-- Duedate Field -->
<div class="col-sm-12">
    {!! Form::label('DueDate', 'Duedate:') !!}
    <p>{{ $billsOriginal->DueDate }}</p>
</div>

<!-- Meternumber Field -->
<div class="col-sm-12">
    {!! Form::label('MeterNumber', 'Meternumber:') !!}
    <p>{{ $billsOriginal->MeterNumber }}</p>
</div>

<!-- Consumertype Field -->
<div class="col-sm-12">
    {!! Form::label('ConsumerType', 'Consumertype:') !!}
    <p>{{ $billsOriginal->ConsumerType }}</p>
</div>

<!-- Billtype Field -->
<div class="col-sm-12">
    {!! Form::label('BillType', 'Billtype:') !!}
    <p>{{ $billsOriginal->BillType }}</p>
</div>

<!-- Generationsystemcharge Field -->
<div class="col-sm-12">
    {!! Form::label('GenerationSystemCharge', 'Generationsystemcharge:') !!}
    <p>{{ $billsOriginal->GenerationSystemCharge }}</p>
</div>

<!-- Transmissiondeliverychargekw Field -->
<div class="col-sm-12">
    {!! Form::label('TransmissionDeliveryChargeKW', 'Transmissiondeliverychargekw:') !!}
    <p>{{ $billsOriginal->TransmissionDeliveryChargeKW }}</p>
</div>

<!-- Transmissiondeliverychargekwh Field -->
<div class="col-sm-12">
    {!! Form::label('TransmissionDeliveryChargeKWH', 'Transmissiondeliverychargekwh:') !!}
    <p>{{ $billsOriginal->TransmissionDeliveryChargeKWH }}</p>
</div>

<!-- Systemlosscharge Field -->
<div class="col-sm-12">
    {!! Form::label('SystemLossCharge', 'Systemlosscharge:') !!}
    <p>{{ $billsOriginal->SystemLossCharge }}</p>
</div>

<!-- Distributiondemandcharge Field -->
<div class="col-sm-12">
    {!! Form::label('DistributionDemandCharge', 'Distributiondemandcharge:') !!}
    <p>{{ $billsOriginal->DistributionDemandCharge }}</p>
</div>

<!-- Distributionsystemcharge Field -->
<div class="col-sm-12">
    {!! Form::label('DistributionSystemCharge', 'Distributionsystemcharge:') !!}
    <p>{{ $billsOriginal->DistributionSystemCharge }}</p>
</div>

<!-- Supplyretailcustomercharge Field -->
<div class="col-sm-12">
    {!! Form::label('SupplyRetailCustomerCharge', 'Supplyretailcustomercharge:') !!}
    <p>{{ $billsOriginal->SupplyRetailCustomerCharge }}</p>
</div>

<!-- Supplysystemcharge Field -->
<div class="col-sm-12">
    {!! Form::label('SupplySystemCharge', 'Supplysystemcharge:') !!}
    <p>{{ $billsOriginal->SupplySystemCharge }}</p>
</div>

<!-- Meteringretailcustomercharge Field -->
<div class="col-sm-12">
    {!! Form::label('MeteringRetailCustomerCharge', 'Meteringretailcustomercharge:') !!}
    <p>{{ $billsOriginal->MeteringRetailCustomerCharge }}</p>
</div>

<!-- Meteringsystemcharge Field -->
<div class="col-sm-12">
    {!! Form::label('MeteringSystemCharge', 'Meteringsystemcharge:') !!}
    <p>{{ $billsOriginal->MeteringSystemCharge }}</p>
</div>

<!-- Rfsc Field -->
<div class="col-sm-12">
    {!! Form::label('RFSC', 'Rfsc:') !!}
    <p>{{ $billsOriginal->RFSC }}</p>
</div>

<!-- Lifelinerate Field -->
<div class="col-sm-12">
    {!! Form::label('LifelineRate', 'Lifelinerate:') !!}
    <p>{{ $billsOriginal->LifelineRate }}</p>
</div>

<!-- Interclasscrosssubsidycharge Field -->
<div class="col-sm-12">
    {!! Form::label('InterClassCrossSubsidyCharge', 'Interclasscrosssubsidycharge:') !!}
    <p>{{ $billsOriginal->InterClassCrossSubsidyCharge }}</p>
</div>

<!-- Pparefund Field -->
<div class="col-sm-12">
    {!! Form::label('PPARefund', 'Pparefund:') !!}
    <p>{{ $billsOriginal->PPARefund }}</p>
</div>

<!-- Seniorcitizensubsidy Field -->
<div class="col-sm-12">
    {!! Form::label('SeniorCitizenSubsidy', 'Seniorcitizensubsidy:') !!}
    <p>{{ $billsOriginal->SeniorCitizenSubsidy }}</p>
</div>

<!-- Missionaryelectrificationcharge Field -->
<div class="col-sm-12">
    {!! Form::label('MissionaryElectrificationCharge', 'Missionaryelectrificationcharge:') !!}
    <p>{{ $billsOriginal->MissionaryElectrificationCharge }}</p>
</div>

<!-- Environmentalcharge Field -->
<div class="col-sm-12">
    {!! Form::label('EnvironmentalCharge', 'Environmentalcharge:') !!}
    <p>{{ $billsOriginal->EnvironmentalCharge }}</p>
</div>

<!-- Strandedcontractcosts Field -->
<div class="col-sm-12">
    {!! Form::label('StrandedContractCosts', 'Strandedcontractcosts:') !!}
    <p>{{ $billsOriginal->StrandedContractCosts }}</p>
</div>

<!-- Npcstrandeddebt Field -->
<div class="col-sm-12">
    {!! Form::label('NPCStrandedDebt', 'Npcstrandeddebt:') !!}
    <p>{{ $billsOriginal->NPCStrandedDebt }}</p>
</div>

<!-- Feedintariffallowance Field -->
<div class="col-sm-12">
    {!! Form::label('FeedInTariffAllowance', 'Feedintariffallowance:') !!}
    <p>{{ $billsOriginal->FeedInTariffAllowance }}</p>
</div>

<!-- Missionaryelectrificationredci Field -->
<div class="col-sm-12">
    {!! Form::label('MissionaryElectrificationREDCI', 'Missionaryelectrificationredci:') !!}
    <p>{{ $billsOriginal->MissionaryElectrificationREDCI }}</p>
</div>

<!-- Generationvat Field -->
<div class="col-sm-12">
    {!! Form::label('GenerationVAT', 'Generationvat:') !!}
    <p>{{ $billsOriginal->GenerationVAT }}</p>
</div>

<!-- Transmissionvat Field -->
<div class="col-sm-12">
    {!! Form::label('TransmissionVAT', 'Transmissionvat:') !!}
    <p>{{ $billsOriginal->TransmissionVAT }}</p>
</div>

<!-- Systemlossvat Field -->
<div class="col-sm-12">
    {!! Form::label('SystemLossVAT', 'Systemlossvat:') !!}
    <p>{{ $billsOriginal->SystemLossVAT }}</p>
</div>

<!-- Distributionvat Field -->
<div class="col-sm-12">
    {!! Form::label('DistributionVAT', 'Distributionvat:') !!}
    <p>{{ $billsOriginal->DistributionVAT }}</p>
</div>

<!-- Realpropertytax Field -->
<div class="col-sm-12">
    {!! Form::label('RealPropertyTax', 'Realpropertytax:') !!}
    <p>{{ $billsOriginal->RealPropertyTax }}</p>
</div>

<!-- Othergenerationrateadjustment Field -->
<div class="col-sm-12">
    {!! Form::label('OtherGenerationRateAdjustment', 'Othergenerationrateadjustment:') !!}
    <p>{{ $billsOriginal->OtherGenerationRateAdjustment }}</p>
</div>

<!-- Othertransmissioncostadjustmentkw Field -->
<div class="col-sm-12">
    {!! Form::label('OtherTransmissionCostAdjustmentKW', 'Othertransmissioncostadjustmentkw:') !!}
    <p>{{ $billsOriginal->OtherTransmissionCostAdjustmentKW }}</p>
</div>

<!-- Othertransmissioncostadjustmentkwh Field -->
<div class="col-sm-12">
    {!! Form::label('OtherTransmissionCostAdjustmentKWH', 'Othertransmissioncostadjustmentkwh:') !!}
    <p>{{ $billsOriginal->OtherTransmissionCostAdjustmentKWH }}</p>
</div>

<!-- Othersystemlosscostadjustment Field -->
<div class="col-sm-12">
    {!! Form::label('OtherSystemLossCostAdjustment', 'Othersystemlosscostadjustment:') !!}
    <p>{{ $billsOriginal->OtherSystemLossCostAdjustment }}</p>
</div>

<!-- Otherlifelineratecostadjustment Field -->
<div class="col-sm-12">
    {!! Form::label('OtherLifelineRateCostAdjustment', 'Otherlifelineratecostadjustment:') !!}
    <p>{{ $billsOriginal->OtherLifelineRateCostAdjustment }}</p>
</div>

<!-- Seniorcitizendiscountandsubsidyadjustment Field -->
<div class="col-sm-12">
    {!! Form::label('SeniorCitizenDiscountAndSubsidyAdjustment', 'Seniorcitizendiscountandsubsidyadjustment:') !!}
    <p>{{ $billsOriginal->SeniorCitizenDiscountAndSubsidyAdjustment }}</p>
</div>

<!-- Franchisetax Field -->
<div class="col-sm-12">
    {!! Form::label('FranchiseTax', 'Franchisetax:') !!}
    <p>{{ $billsOriginal->FranchiseTax }}</p>
</div>

<!-- Businesstax Field -->
<div class="col-sm-12">
    {!! Form::label('BusinessTax', 'Businesstax:') !!}
    <p>{{ $billsOriginal->BusinessTax }}</p>
</div>

<!-- Adjustmenttype Field -->
<div class="col-sm-12">
    {!! Form::label('AdjustmentType', 'Adjustmenttype:') !!}
    <p>{{ $billsOriginal->AdjustmentType }}</p>
</div>

<!-- Adjustmentnumber Field -->
<div class="col-sm-12">
    {!! Form::label('AdjustmentNumber', 'Adjustmentnumber:') !!}
    <p>{{ $billsOriginal->AdjustmentNumber }}</p>
</div>

<!-- Adjustedby Field -->
<div class="col-sm-12">
    {!! Form::label('AdjustedBy', 'Adjustedby:') !!}
    <p>{{ $billsOriginal->AdjustedBy }}</p>
</div>

<!-- Dateadjusted Field -->
<div class="col-sm-12">
    {!! Form::label('DateAdjusted', 'Dateadjusted:') !!}
    <p>{{ $billsOriginal->DateAdjusted }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    <p>{{ $billsOriginal->Notes }}</p>
</div>

<!-- Userid Field -->
<div class="col-sm-12">
    {!! Form::label('UserId', 'Userid:') !!}
    <p>{{ $billsOriginal->UserId }}</p>
</div>

<!-- Billedfrom Field -->
<div class="col-sm-12">
    {!! Form::label('BilledFrom', 'Billedfrom:') !!}
    <p>{{ $billsOriginal->BilledFrom }}</p>
</div>

<!-- Form2307Amount Field -->
<div class="col-sm-12">
    {!! Form::label('Form2307Amount', 'Form2307Amount:') !!}
    <p>{{ $billsOriginal->Form2307Amount }}</p>
</div>

<!-- Evat2Percent Field -->
<div class="col-sm-12">
    {!! Form::label('Evat2Percent', 'Evat2Percent:') !!}
    <p>{{ $billsOriginal->Evat2Percent }}</p>
</div>

<!-- Evat5Percent Field -->
<div class="col-sm-12">
    {!! Form::label('Evat5Percent', 'Evat5Percent:') !!}
    <p>{{ $billsOriginal->Evat5Percent }}</p>
</div>

<!-- Mergedtocollectible Field -->
<div class="col-sm-12">
    {!! Form::label('MergedToCollectible', 'Mergedtocollectible:') !!}
    <p>{{ $billsOriginal->MergedToCollectible }}</p>
</div>

<!-- Deducteddeposit Field -->
<div class="col-sm-12">
    {!! Form::label('DeductedDeposit', 'Deducteddeposit:') !!}
    <p>{{ $billsOriginal->DeductedDeposit }}</p>
</div>

<!-- Excessdeposit Field -->
<div class="col-sm-12">
    {!! Form::label('ExcessDeposit', 'Excessdeposit:') !!}
    <p>{{ $billsOriginal->ExcessDeposit }}</p>
</div>

<!-- Averagedcount Field -->
<div class="col-sm-12">
    {!! Form::label('AveragedCount', 'Averagedcount:') !!}
    <p>{{ $billsOriginal->AveragedCount }}</p>
</div>

<!-- Isunlockedforpayment Field -->
<div class="col-sm-12">
    {!! Form::label('IsUnlockedForPayment', 'Isunlockedforpayment:') !!}
    <p>{{ $billsOriginal->IsUnlockedForPayment }}</p>
</div>

<!-- Unlockedby Field -->
<div class="col-sm-12">
    {!! Form::label('UnlockedBy', 'Unlockedby:') !!}
    <p>{{ $billsOriginal->UnlockedBy }}</p>
</div>

