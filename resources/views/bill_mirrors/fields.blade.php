<!-- Billnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillNumber', 'Billnumber:') !!}
    {!! Form::text('BillNumber', null, ['class' => 'form-control', 'maxlength' => 255, 'maxlength' => 255]) !!}
</div>

<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control', 'maxlength' => 255, 'maxlength' => 255]) !!}
</div>

<!-- Serviceperiod Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServicePeriod', 'Serviceperiod:') !!}
    {!! Form::text('ServicePeriod', null, ['class' => 'form-control','id'=>'ServicePeriod']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServicePeriod').datepicker()
    </script>
@endpush

<!-- Additionalcharges Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdditionalCharges', 'Additionalcharges:') !!}
    {!! Form::number('AdditionalCharges', null, ['class' => 'form-control']) !!}
</div>

<!-- Deductions Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Deductions', 'Deductions:') !!}
    {!! Form::number('Deductions', null, ['class' => 'form-control']) !!}
</div>

<!-- Netamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetAmount', 'Netamount:') !!}
    {!! Form::number('NetAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Billingdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillingDate', 'Billingdate:') !!}
    {!! Form::text('BillingDate', null, ['class' => 'form-control','id'=>'BillingDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#BillingDate').datepicker()
    </script>
@endpush

<!-- Servicedatefrom Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceDateFrom', 'Servicedatefrom:') !!}
    {!! Form::text('ServiceDateFrom', null, ['class' => 'form-control','id'=>'ServiceDateFrom']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServiceDateFrom').datepicker()
    </script>
@endpush

<!-- Servicedateto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceDateTo', 'Servicedateto:') !!}
    {!! Form::text('ServiceDateTo', null, ['class' => 'form-control','id'=>'ServiceDateTo']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServiceDateTo').datepicker()
    </script>
@endpush

<!-- Duedate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DueDate', 'Duedate:') !!}
    {!! Form::text('DueDate', null, ['class' => 'form-control','id'=>'DueDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DueDate').datepicker()
    </script>
@endpush

<!-- Generationsystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GenerationSystemCharge', 'Generationsystemcharge:') !!}
    {!! Form::number('GenerationSystemCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Transmissiondeliverychargekw Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransmissionDeliveryChargeKW', 'Transmissiondeliverychargekw:') !!}
    {!! Form::number('TransmissionDeliveryChargeKW', null, ['class' => 'form-control']) !!}
</div>

<!-- Transmissiondeliverychargekwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransmissionDeliveryChargeKWH', 'Transmissiondeliverychargekwh:') !!}
    {!! Form::number('TransmissionDeliveryChargeKWH', null, ['class' => 'form-control']) !!}
</div>

<!-- Systemlosscharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SystemLossCharge', 'Systemlosscharge:') !!}
    {!! Form::number('SystemLossCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Distributiondemandcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DistributionDemandCharge', 'Distributiondemandcharge:') !!}
    {!! Form::number('DistributionDemandCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Distributionsystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DistributionSystemCharge', 'Distributionsystemcharge:') !!}
    {!! Form::number('DistributionSystemCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Supplyretailcustomercharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SupplyRetailCustomerCharge', 'Supplyretailcustomercharge:') !!}
    {!! Form::number('SupplyRetailCustomerCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Supplysystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SupplySystemCharge', 'Supplysystemcharge:') !!}
    {!! Form::number('SupplySystemCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Meteringretailcustomercharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeteringRetailCustomerCharge', 'Meteringretailcustomercharge:') !!}
    {!! Form::number('MeteringRetailCustomerCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Meteringsystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeteringSystemCharge', 'Meteringsystemcharge:') !!}
    {!! Form::number('MeteringSystemCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Rfsc Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RFSC', 'Rfsc:') !!}
    {!! Form::number('RFSC', null, ['class' => 'form-control']) !!}
</div>

<!-- Lifelinerate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LifelineRate', 'Lifelinerate:') !!}
    {!! Form::number('LifelineRate', null, ['class' => 'form-control']) !!}
</div>

<!-- Interclasscrosssubsidycharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('InterClassCrossSubsidyCharge', 'Interclasscrosssubsidycharge:') !!}
    {!! Form::number('InterClassCrossSubsidyCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Pparefund Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PPARefund', 'Pparefund:') !!}
    {!! Form::number('PPARefund', null, ['class' => 'form-control']) !!}
</div>

<!-- Seniorcitizensubsidy Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SeniorCitizenSubsidy', 'Seniorcitizensubsidy:') !!}
    {!! Form::number('SeniorCitizenSubsidy', null, ['class' => 'form-control']) !!}
</div>

<!-- Missionaryelectrificationcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MissionaryElectrificationCharge', 'Missionaryelectrificationcharge:') !!}
    {!! Form::number('MissionaryElectrificationCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Environmentalcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EnvironmentalCharge', 'Environmentalcharge:') !!}
    {!! Form::number('EnvironmentalCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Strandedcontractcosts Field -->
<div class="form-group col-sm-6">
    {!! Form::label('StrandedContractCosts', 'Strandedcontractcosts:') !!}
    {!! Form::number('StrandedContractCosts', null, ['class' => 'form-control']) !!}
</div>

<!-- Npcstrandeddebt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NPCStrandedDebt', 'Npcstrandeddebt:') !!}
    {!! Form::number('NPCStrandedDebt', null, ['class' => 'form-control']) !!}
</div>

<!-- Feedintariffallowance Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FeedInTariffAllowance', 'Feedintariffallowance:') !!}
    {!! Form::number('FeedInTariffAllowance', null, ['class' => 'form-control']) !!}
</div>

<!-- Missionaryelectrificationredci Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MissionaryElectrificationREDCI', 'Missionaryelectrificationredci:') !!}
    {!! Form::number('MissionaryElectrificationREDCI', null, ['class' => 'form-control']) !!}
</div>

<!-- Generationvat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GenerationVAT', 'Generationvat:') !!}
    {!! Form::number('GenerationVAT', null, ['class' => 'form-control']) !!}
</div>

<!-- Transmissionvat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransmissionVAT', 'Transmissionvat:') !!}
    {!! Form::number('TransmissionVAT', null, ['class' => 'form-control']) !!}
</div>

<!-- Systemlossvat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SystemLossVAT', 'Systemlossvat:') !!}
    {!! Form::number('SystemLossVAT', null, ['class' => 'form-control']) !!}
</div>

<!-- Distributionvat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DistributionVAT', 'Distributionvat:') !!}
    {!! Form::number('DistributionVAT', null, ['class' => 'form-control']) !!}
</div>

<!-- Realpropertytax Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RealPropertyTax', 'Realpropertytax:') !!}
    {!! Form::number('RealPropertyTax', null, ['class' => 'form-control']) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control', 'maxlength' => 2500, 'maxlength' => 2500]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control', 'maxlength' => 255, 'maxlength' => 255]) !!}
</div>

<!-- Othergenerationrateadjustment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherGenerationRateAdjustment', 'Othergenerationrateadjustment:') !!}
    {!! Form::number('OtherGenerationRateAdjustment', null, ['class' => 'form-control']) !!}
</div>

<!-- Othertransmissioncostadjustmentkw Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherTransmissionCostAdjustmentKW', 'Othertransmissioncostadjustmentkw:') !!}
    {!! Form::number('OtherTransmissionCostAdjustmentKW', null, ['class' => 'form-control']) !!}
</div>

<!-- Othertransmissioncostadjustmentkwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherTransmissionCostAdjustmentKWH', 'Othertransmissioncostadjustmentkwh:') !!}
    {!! Form::number('OtherTransmissionCostAdjustmentKWH', null, ['class' => 'form-control']) !!}
</div>

<!-- Othersystemlosscostadjustment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherSystemLossCostAdjustment', 'Othersystemlosscostadjustment:') !!}
    {!! Form::number('OtherSystemLossCostAdjustment', null, ['class' => 'form-control']) !!}
</div>

<!-- Otherlifelineratecostadjustment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherLifelineRateCostAdjustment', 'Otherlifelineratecostadjustment:') !!}
    {!! Form::number('OtherLifelineRateCostAdjustment', null, ['class' => 'form-control']) !!}
</div>

<!-- Seniorcitizendiscountandsubsidyadjustment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SeniorCitizenDiscountAndSubsidyAdjustment', 'Seniorcitizendiscountandsubsidyadjustment:') !!}
    {!! Form::number('SeniorCitizenDiscountAndSubsidyAdjustment', null, ['class' => 'form-control']) !!}
</div>

<!-- Franchisetax Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FranchiseTax', 'Franchisetax:') !!}
    {!! Form::number('FranchiseTax', null, ['class' => 'form-control']) !!}
</div>

<!-- Businesstax Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BusinessTax', 'Businesstax:') !!}
    {!! Form::number('BusinessTax', null, ['class' => 'form-control']) !!}
</div>

<!-- Adjustmenttype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdjustmentType', 'Adjustmenttype:') !!}
    {!! Form::text('AdjustmentType', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Form2307Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Form2307Amount', 'Form2307Amount:') !!}
    {!! Form::text('Form2307Amount', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Deducteddeposit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DeductedDeposit', 'Deducteddeposit:') !!}
    {!! Form::number('DeductedDeposit', null, ['class' => 'form-control']) !!}
</div>

<!-- Excessdeposit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ExcessDeposit', 'Excessdeposit:') !!}
    {!! Form::number('ExcessDeposit', null, ['class' => 'form-control']) !!}
</div>

<!-- Isunlockedforpayment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('IsUnlockedForPayment', 'Isunlockedforpayment:') !!}
    {!! Form::text('IsUnlockedForPayment', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Unlockedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UnlockedBy', 'Unlockedby:') !!}
    {!! Form::text('UnlockedBy', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Evat2Percent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Evat2Percent', 'Evat2Percent:') !!}
    {!! Form::text('Evat2Percent', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Evat5Percent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Evat5Percent', 'Evat5Percent:') !!}
    {!! Form::text('Evat5Percent', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Adjustmentnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdjustmentNumber', 'Adjustmentnumber:') !!}
    {!! Form::text('AdjustmentNumber', null, ['class' => 'form-control', 'maxlength' => 80, 'maxlength' => 80]) !!}
</div>

<!-- Adjustedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdjustedBy', 'Adjustedby:') !!}
    {!! Form::text('AdjustedBy', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Dateadjusted Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateAdjusted', 'Dateadjusted:') !!}
    {!! Form::text('DateAdjusted', null, ['class' => 'form-control','id'=>'DateAdjusted']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateAdjusted').datepicker()
    </script>
@endpush

<!-- Forcancellation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ForCancellation', 'Forcancellation:') !!}
    {!! Form::text('ForCancellation', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Cancelrequestedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CancelRequestedBy', 'Cancelrequestedby:') !!}
    {!! Form::text('CancelRequestedBy', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Cancelapprovedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CancelApprovedBy', 'Cancelapprovedby:') !!}
    {!! Form::text('CancelApprovedBy', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Katasngvat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('KatasNgVat', 'Katasngvat:') !!}
    {!! Form::number('KatasNgVat', null, ['class' => 'form-control']) !!}
</div>

<!-- Solarimportpresent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SolarImportPresent', 'Solarimportpresent:') !!}
    {!! Form::number('SolarImportPresent', null, ['class' => 'form-control']) !!}
</div>

<!-- Solarimportprevious Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SolarImportPrevious', 'Solarimportprevious:') !!}
    {!! Form::number('SolarImportPrevious', null, ['class' => 'form-control']) !!}
</div>

<!-- Solarexportpresent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SolarExportPresent', 'Solarexportpresent:') !!}
    {!! Form::number('SolarExportPresent', null, ['class' => 'form-control']) !!}
</div>

<!-- Solarexportprevious Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SolarExportPrevious', 'Solarexportprevious:') !!}
    {!! Form::number('SolarExportPrevious', null, ['class' => 'form-control']) !!}
</div>

<!-- Solarimportkwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SolarImportKwh', 'Solarimportkwh:') !!}
    {!! Form::number('SolarImportKwh', null, ['class' => 'form-control']) !!}
</div>

<!-- Solarexportkwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SolarExportKwh', 'Solarexportkwh:') !!}
    {!! Form::number('SolarExportKwh', null, ['class' => 'form-control']) !!}
</div>

<!-- Generationchargesolarexport Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GenerationChargeSolarExport', 'Generationchargesolarexport:') !!}
    {!! Form::number('GenerationChargeSolarExport', null, ['class' => 'form-control']) !!}
</div>

<!-- Solarresidualcredit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SolarResidualCredit', 'Solarresidualcredit:') !!}
    {!! Form::number('SolarResidualCredit', null, ['class' => 'form-control']) !!}
</div>

<!-- Solardemandchargekw Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SolarDemandChargeKW', 'Solardemandchargekw:') !!}
    {!! Form::number('SolarDemandChargeKW', null, ['class' => 'form-control']) !!}
</div>

<!-- Solardemandchargekwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SolarDemandChargeKWH', 'Solardemandchargekwh:') !!}
    {!! Form::number('SolarDemandChargeKWH', null, ['class' => 'form-control']) !!}
</div>

<!-- Solarretailcustomercharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SolarRetailCustomerCharge', 'Solarretailcustomercharge:') !!}
    {!! Form::number('SolarRetailCustomerCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Solarsupplysystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SolarSupplySystemCharge', 'Solarsupplysystemcharge:') !!}
    {!! Form::number('SolarSupplySystemCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Solarmeteringretailcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SolarMeteringRetailCharge', 'Solarmeteringretailcharge:') !!}
    {!! Form::number('SolarMeteringRetailCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Solarmeteringsystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SolarMeteringSystemCharge', 'Solarmeteringsystemcharge:') !!}
    {!! Form::number('SolarMeteringSystemCharge', null, ['class' => 'form-control']) !!}
</div>

<!-- Item1 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item1', 'Item1:') !!}
    {!! Form::text('Item1', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Item2 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item2', 'Item2:') !!}
    {!! Form::text('Item2', null, ['class' => 'form-control', 'maxlength' => 255, 'maxlength' => 255]) !!}
</div>

<!-- Item3 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item3', 'Item3:') !!}
    {!! Form::text('Item3', null, ['class' => 'form-control', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>

<!-- Item4 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item4', 'Item4:') !!}
    {!! Form::text('Item4', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Item5 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Item5', 'Item5:') !!}
    {!! Form::text('Item5', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Paidamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PaidAmount', 'Paidamount:') !!}
    {!! Form::number('PaidAmount', null, ['class' => 'form-control']) !!}
</div>

<!-- Balance Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Balance', 'Balance:') !!}
    {!! Form::number('Balance', null, ['class' => 'form-control']) !!}
</div>

<!-- Acrm Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ACRM', 'Acrm:') !!}
    {!! Form::number('ACRM', null, ['class' => 'form-control']) !!}
</div>

<!-- Poweractreduction Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PowerActReduction', 'Poweractreduction:') !!}
    {!! Form::number('PowerActReduction', null, ['class' => 'form-control']) !!}
</div>

<!-- Acrmvat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ACRMVAT', 'Acrmvat:') !!}
    {!! Form::number('ACRMVAT', null, ['class' => 'form-control']) !!}
</div>

<!-- Missionaryelectrificationspug Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MissionaryElectrificationSPUG', 'Missionaryelectrificationspug:') !!}
    {!! Form::number('MissionaryElectrificationSPUG', null, ['class' => 'form-control']) !!}
</div>

<!-- Missionaryelectrificationspugtrueup Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MissionaryElectrificationSPUGTRUEUP', 'Missionaryelectrificationspugtrueup:') !!}
    {!! Form::number('MissionaryElectrificationSPUGTRUEUP', null, ['class' => 'form-control']) !!}
</div>

<!-- Franchisetaxothers Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FranchiseTaxOthers', 'Franchisetaxothers:') !!}
    {!! Form::number('FranchiseTaxOthers', null, ['class' => 'form-control']) !!}
</div>

<!-- Othersvat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OthersVAT', 'Othersvat:') !!}
    {!! Form::number('OthersVAT', null, ['class' => 'form-control']) !!}
</div>

<!-- Advancedmaterialdeposit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdvancedMaterialDeposit', 'Advancedmaterialdeposit:') !!}
    {!! Form::number('AdvancedMaterialDeposit', null, ['class' => 'form-control']) !!}
</div>

<!-- Customerdeposit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CustomerDeposit', 'Customerdeposit:') !!}
    {!! Form::number('CustomerDeposit', null, ['class' => 'form-control']) !!}
</div>

<!-- Transformerrental Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransformerRental', 'Transformerrental:') !!}
    {!! Form::number('TransformerRental', null, ['class' => 'form-control']) !!}
</div>

<!-- Adjustmentrequestedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdjustmentRequestedBy', 'Adjustmentrequestedby:') !!}
    {!! Form::text('AdjustmentRequestedBy', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Adjustmentapprovedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdjustmentApprovedBy', 'Adjustmentapprovedby:') !!}
    {!! Form::text('AdjustmentApprovedBy', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Adjustmentstatus Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdjustmentStatus', 'Adjustmentstatus:') !!}
    {!! Form::text('AdjustmentStatus', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Dateadjustmentrequested Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateAdjustmentRequested', 'Dateadjustmentrequested:') !!}
    {!! Form::text('DateAdjustmentRequested', null, ['class' => 'form-control','id'=>'DateAdjustmentRequested']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateAdjustmentRequested').datepicker()
    </script>
@endpush

<!-- Termedpayments Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TermedPayments', 'Termedpayments:') !!}
    {!! Form::number('TermedPayments', null, ['class' => 'form-control']) !!}
</div>

<!-- Ornumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORNumber', 'Ornumber:') !!}
    {!! Form::text('ORNumber', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Ordate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORDate', 'Ordate:') !!}
    {!! Form::text('ORDate', null, ['class' => 'form-control','id'=>'ORDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ORDate').datepicker()
    </script>
@endpush

<!-- Batchnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BatchNumber', 'Batchnumber:') !!}
    {!! Form::text('BatchNumber', null, ['class' => 'form-control', 'maxlength' => 90, 'maxlength' => 90]) !!}
</div>

<!-- Teller Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Teller', 'Teller:') !!}
    {!! Form::text('Teller', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Paidbillid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PaidBillId', 'Paidbillid:') !!}
    {!! Form::text('PaidBillId', null, ['class' => 'form-control', 'maxlength' => 100, 'maxlength' => 100]) !!}
</div>