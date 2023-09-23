<!-- Ratefor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RateFor', 'Ratefor:') !!}
    {!! Form::text('RateFor', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Consumertype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ConsumerType', 'Consumertype:') !!}
    {!! Form::text('ConsumerType', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
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

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
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

<!-- Totalratevatexcluded Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TotalRateVATExcluded', 'Totalratevatexcluded:') !!}
    {!! Form::text('TotalRateVATExcluded', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Totalratevatincluded Field -->
<div class="form-group col-sm-6">
    {!! Form::label('TotalRateVATIncluded', 'Totalratevatincluded:') !!}
    {!! Form::text('TotalRateVATIncluded', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
</div>

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>