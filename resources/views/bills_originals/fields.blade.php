<!-- Billnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillNumber', 'Billnumber:') !!}
    {!! Form::text('BillNumber', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Accountnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AccountNumber', 'Accountnumber:') !!}
    {!! Form::text('AccountNumber', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Serviceperiod Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServicePeriod', 'Serviceperiod:') !!}
    {!! Form::text('ServicePeriod', null, ['class' => 'form-control','id'=>'ServicePeriod']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServicePeriod').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Multiplier Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Multiplier', 'Multiplier:') !!}
    {!! Form::text('Multiplier', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Coreloss Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Coreloss', 'Coreloss:') !!}
    {!! Form::text('Coreloss', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Kwhused Field -->
<div class="form-group col-sm-6">
    {!! Form::label('KwhUsed', 'Kwhused:') !!}
    {!! Form::text('KwhUsed', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Previouskwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PreviousKwh', 'Previouskwh:') !!}
    {!! Form::text('PreviousKwh', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Presentkwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PresentKwh', 'Presentkwh:') !!}
    {!! Form::text('PresentKwh', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Demandpreviouskwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DemandPreviousKwh', 'Demandpreviouskwh:') !!}
    {!! Form::text('DemandPreviousKwh', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Demandpresentkwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DemandPresentKwh', 'Demandpresentkwh:') !!}
    {!! Form::text('DemandPresentKwh', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Additionalkwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdditionalKwh', 'Additionalkwh:') !!}
    {!! Form::text('AdditionalKwh', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Additionaldemandkwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdditionalDemandKwh', 'Additionaldemandkwh:') !!}
    {!! Form::text('AdditionalDemandKwh', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Kwhamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('KwhAmount', 'Kwhamount:') !!}
    {!! Form::text('KwhAmount', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Effectiverate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EffectiveRate', 'Effectiverate:') !!}
    {!! Form::text('EffectiveRate', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Additionalcharges Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdditionalCharges', 'Additionalcharges:') !!}
    {!! Form::text('AdditionalCharges', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Deductions Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Deductions', 'Deductions:') !!}
    {!! Form::text('Deductions', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Netamount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NetAmount', 'Netamount:') !!}
    {!! Form::text('NetAmount', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Billingdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillingDate', 'Billingdate:') !!}
    {!! Form::text('BillingDate', null, ['class' => 'form-control','id'=>'BillingDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#BillingDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Servicedatefrom Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceDateFrom', 'Servicedatefrom:') !!}
    {!! Form::text('ServiceDateFrom', null, ['class' => 'form-control','id'=>'ServiceDateFrom']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServiceDateFrom').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Servicedateto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceDateTo', 'Servicedateto:') !!}
    {!! Form::text('ServiceDateTo', null, ['class' => 'form-control','id'=>'ServiceDateTo']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ServiceDateTo').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Duedate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DueDate', 'Duedate:') !!}
    {!! Form::text('DueDate', null, ['class' => 'form-control','id'=>'DueDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DueDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Meternumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeterNumber', 'Meternumber:') !!}
    {!! Form::text('MeterNumber', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Consumertype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerType', 'Consumertype:') !!}
    {!! Form::text('ConsumerType', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Billtype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BillType', 'Billtype:') !!}
    {!! Form::text('BillType', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Generationsystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GenerationSystemCharge', 'Generationsystemcharge:') !!}
    {!! Form::text('GenerationSystemCharge', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Transmissiondeliverychargekw Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransmissionDeliveryChargeKW', 'Transmissiondeliverychargekw:') !!}
    {!! Form::text('TransmissionDeliveryChargeKW', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Transmissiondeliverychargekwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransmissionDeliveryChargeKWH', 'Transmissiondeliverychargekwh:') !!}
    {!! Form::text('TransmissionDeliveryChargeKWH', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Systemlosscharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SystemLossCharge', 'Systemlosscharge:') !!}
    {!! Form::text('SystemLossCharge', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Distributiondemandcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DistributionDemandCharge', 'Distributiondemandcharge:') !!}
    {!! Form::text('DistributionDemandCharge', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Distributionsystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DistributionSystemCharge', 'Distributionsystemcharge:') !!}
    {!! Form::text('DistributionSystemCharge', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Supplyretailcustomercharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SupplyRetailCustomerCharge', 'Supplyretailcustomercharge:') !!}
    {!! Form::text('SupplyRetailCustomerCharge', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Supplysystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SupplySystemCharge', 'Supplysystemcharge:') !!}
    {!! Form::text('SupplySystemCharge', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Meteringretailcustomercharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeteringRetailCustomerCharge', 'Meteringretailcustomercharge:') !!}
    {!! Form::text('MeteringRetailCustomerCharge', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Meteringsystemcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MeteringSystemCharge', 'Meteringsystemcharge:') !!}
    {!! Form::text('MeteringSystemCharge', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Rfsc Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RFSC', 'Rfsc:') !!}
    {!! Form::text('RFSC', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Lifelinerate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('LifelineRate', 'Lifelinerate:') !!}
    {!! Form::text('LifelineRate', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Interclasscrosssubsidycharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('InterClassCrossSubsidyCharge', 'Interclasscrosssubsidycharge:') !!}
    {!! Form::text('InterClassCrossSubsidyCharge', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Pparefund Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PPARefund', 'Pparefund:') !!}
    {!! Form::text('PPARefund', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Seniorcitizensubsidy Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SeniorCitizenSubsidy', 'Seniorcitizensubsidy:') !!}
    {!! Form::text('SeniorCitizenSubsidy', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Missionaryelectrificationcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MissionaryElectrificationCharge', 'Missionaryelectrificationcharge:') !!}
    {!! Form::text('MissionaryElectrificationCharge', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Environmentalcharge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EnvironmentalCharge', 'Environmentalcharge:') !!}
    {!! Form::text('EnvironmentalCharge', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Strandedcontractcosts Field -->
<div class="form-group col-sm-6">
    {!! Form::label('StrandedContractCosts', 'Strandedcontractcosts:') !!}
    {!! Form::text('StrandedContractCosts', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Npcstrandeddebt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NPCStrandedDebt', 'Npcstrandeddebt:') !!}
    {!! Form::text('NPCStrandedDebt', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Feedintariffallowance Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FeedInTariffAllowance', 'Feedintariffallowance:') !!}
    {!! Form::text('FeedInTariffAllowance', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Missionaryelectrificationredci Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MissionaryElectrificationREDCI', 'Missionaryelectrificationredci:') !!}
    {!! Form::text('MissionaryElectrificationREDCI', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Generationvat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GenerationVAT', 'Generationvat:') !!}
    {!! Form::text('GenerationVAT', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Transmissionvat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TransmissionVAT', 'Transmissionvat:') !!}
    {!! Form::text('TransmissionVAT', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Systemlossvat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SystemLossVAT', 'Systemlossvat:') !!}
    {!! Form::text('SystemLossVAT', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Distributionvat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DistributionVAT', 'Distributionvat:') !!}
    {!! Form::text('DistributionVAT', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Realpropertytax Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RealPropertyTax', 'Realpropertytax:') !!}
    {!! Form::text('RealPropertyTax', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Othergenerationrateadjustment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherGenerationRateAdjustment', 'Othergenerationrateadjustment:') !!}
    {!! Form::text('OtherGenerationRateAdjustment', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Othertransmissioncostadjustmentkw Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherTransmissionCostAdjustmentKW', 'Othertransmissioncostadjustmentkw:') !!}
    {!! Form::text('OtherTransmissionCostAdjustmentKW', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Othertransmissioncostadjustmentkwh Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherTransmissionCostAdjustmentKWH', 'Othertransmissioncostadjustmentkwh:') !!}
    {!! Form::text('OtherTransmissionCostAdjustmentKWH', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Othersystemlosscostadjustment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherSystemLossCostAdjustment', 'Othersystemlosscostadjustment:') !!}
    {!! Form::text('OtherSystemLossCostAdjustment', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Otherlifelineratecostadjustment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('OtherLifelineRateCostAdjustment', 'Otherlifelineratecostadjustment:') !!}
    {!! Form::text('OtherLifelineRateCostAdjustment', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Seniorcitizendiscountandsubsidyadjustment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('SeniorCitizenDiscountAndSubsidyAdjustment', 'Seniorcitizendiscountandsubsidyadjustment:') !!}
    {!! Form::text('SeniorCitizenDiscountAndSubsidyAdjustment', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Franchisetax Field -->
<div class="form-group col-sm-6">
    {!! Form::label('FranchiseTax', 'Franchisetax:') !!}
    {!! Form::text('FranchiseTax', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Businesstax Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BusinessTax', 'Businesstax:') !!}
    {!! Form::text('BusinessTax', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Adjustmenttype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdjustmentType', 'Adjustmenttype:') !!}
    {!! Form::text('AdjustmentType', null, ['class' => 'form-control','maxlength' => 30,'maxlength' => 30]) !!}
</div>

<!-- Adjustmentnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdjustmentNumber', 'Adjustmentnumber:') !!}
    {!! Form::text('AdjustmentNumber', null, ['class' => 'form-control','maxlength' => 80,'maxlength' => 80]) !!}
</div>

<!-- Adjustedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AdjustedBy', 'Adjustedby:') !!}
    {!! Form::text('AdjustedBy', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Dateadjusted Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateAdjusted', 'Dateadjusted:') !!}
    {!! Form::text('DateAdjusted', null, ['class' => 'form-control','id'=>'DateAdjusted']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateAdjusted').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 2500,'maxlength' => 2500]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Billedfrom Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BilledFrom', 'Billedfrom:') !!}
    {!! Form::text('BilledFrom', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Form2307Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Form2307Amount', 'Form2307Amount:') !!}
    {!! Form::text('Form2307Amount', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Evat2Percent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Evat2Percent', 'Evat2Percent:') !!}
    {!! Form::text('Evat2Percent', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Evat5Percent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Evat5Percent', 'Evat5Percent:') !!}
    {!! Form::text('Evat5Percent', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Mergedtocollectible Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MergedToCollectible', 'Mergedtocollectible:') !!}
    {!! Form::text('MergedToCollectible', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Deducteddeposit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DeductedDeposit', 'Deducteddeposit:') !!}
    {!! Form::text('DeductedDeposit', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Excessdeposit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ExcessDeposit', 'Excessdeposit:') !!}
    {!! Form::text('ExcessDeposit', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Averagedcount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AveragedCount', 'Averagedcount:') !!}
    {!! Form::text('AveragedCount', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Isunlockedforpayment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('IsUnlockedForPayment', 'Isunlockedforpayment:') !!}
    {!! Form::text('IsUnlockedForPayment', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Unlockedby Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UnlockedBy', 'Unlockedby:') !!}
    {!! Form::text('UnlockedBy', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>