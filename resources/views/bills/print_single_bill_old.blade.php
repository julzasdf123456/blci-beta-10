
@php
use App\Models\ServiceAccounts;
use App\Models\MemberConsumers;
use App\Models\Bills;
use App\Models\ServiceConnectionAccountTypes;
@endphp

<style>
@media print {
    @page {
        /* size: 8.5in 6.5in; */
        font-size: .8em !important;
    }

    html, body {
        font-family: sans-serif;
        font-size: .8em !important;
    }

    header {
        display: none;
    }

    .print-area {        
        page-break-after: always;
    }

    .print-area:last-child {        
        page-break-after: auto;
    }

    p {
        margin: 0px;
        padding: 0px;
    }
}  

html, body {
    font-family: sans-serif;
    font-size: .8em !important;
}

.left-indent {
    margin-left: 50px;
}

.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}

.divider {
    width: 100%;
    margin: 10px auto;
    height: 1px;
    background-color: #dedede;
} 

p {
    margin: 0px;
    padding: 0px;
}
</style>

{{-- <link rel="stylesheet" href="{{ URL::asset('adminlte.min.css') }}"> --}}
@php
    $acctType = ServiceConnectionAccountTypes::where('AccountType', $bills->ConsumerType)->first();
@endphp
<div id="print-area" class="content">
    <br>
    <br>
    <br>
    <span style="margin-left: 55px;">{{ date('F Y', strtotime($bills->ServicePeriod)) }}</span>
    <span style="margin-left: 135px;">{{ date('F d, Y', strtotime($bills->billsingDate)) }}</span><br>
    <br>
    <br>
    <span>{{ $account->OldAccountNo }}</span>
    <span style="margin-left: 15px;">{{ $meters != null ? $meters->SerialNumber : '-' }}</span>
    <span style="margin-left: 55px;">{{ $acctType != null ? $acctType->Alias : '-' }}</span>
    <span style="margin-left: 15px;">{{ $bills->Multiplier }}</span>
    <span style="margin-left: 35px;">{{ date('m/d/Y', strtotime($bills->DueDate)) }}</span>
    <br>
    <br>
    <span style="margin-left: 40px;">{{ $account->ServiceAccountName }}</span>
    <span style="margin-left: 280px;">{{ date('M d, Y', strtotime($bills->ServiceDateFrom)) }}</span>
    <span style="margin-left: 10px;">{{ date('M d, Y', strtotime($bills->ServiceDateTo)) }}</span>
    <span style="margin-left: 15px;">{{ $bills->PresentKwh }}</span>
    <span style="margin-left: 15px;">{{ $bills->PreviousKwh }}</span>
    <span style="margin-left: 25px;">{{ $bills->KwhUsed }}</span>
    <br>

    <span style="margin-left: 40px">{{ ServiceAccounts::getAddress($account) }}</span>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div style="display: inline-table; width: 34%;">
        <p style="text-align: right; margin-top: 5px;">{{ number_format($rate->GenerationSystemCharge, 4) ? number_format($rate->GenerationSystemCharge, 4) : $rate->GenerationSystemCharge }}</p>
        <p style="text-align: right; margin-top: 5px; opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 5px; opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 5px;opacity: 0;">0</p>
        <br>
        <p style="text-align: right; margin-top: 5px;">{{ number_format($rate->TransmissionDeliveryChargeKW, 4) ? number_format($rate->TransmissionDeliveryChargeKW, 4) : $rate->TransmissionDeliveryChargeKW }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->TransmissionDeliveryChargeKWH, 4) ? number_format($rate->TransmissionDeliveryChargeKWH, 4) : $rate->TransmissionDeliveryChargeKWH }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->SystemLossCharge, 4) ? number_format($rate->SystemLossCharge, 4) : $rate->SystemLossCharge }}</p>
        <br>
        <p style="text-align: right; margin-top: 8px; ">{{ number_format($rate->DistributionDemandCharge, 4) ? number_format($rate->DistributionDemandCharge, 4) : $rate->DistributionDemandCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->DistributionSystemCharge, 4) ? number_format($rate->DistributionSystemCharge, 4) : $rate->DistributionSystemCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->SupplySystemCharge, 4) ? number_format($rate->SupplySystemCharge, 4) : $rate->SupplySystemCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->SupplyRetailCustomerCharge, 4) ? number_format($rate->SupplyRetailCustomerCharge, 4) : $rate->SupplyRetailCustomerCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->MeteringSystemCharge, 4) ? number_format($rate->MeteringSystemCharge, 4) : $rate->MeteringSystemCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->MeteringRetailCustomerCharge, 4) ? number_format($rate->MeteringRetailCustomerCharge, 4) : $rate->MeteringRetailCustomerCharge }}</p>
        <p style="text-align: right; margin-top: 5px; opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 5px; opacity: 0;">0</p>
        <p style="text-align: right; opacity: 0;">0</p>
        <p style="text-align: right; opacity: 0;">0</p>
        <p style="text-align: right; opacity: 0;">0</p>
        <p style="text-align: right; opacity: 0;">0</p>
        <p style="text-align: right; ">{{ number_format($rate->GenerationVAT, 4) ? number_format($rate->GenerationVAT, 4) : $rate->GenerationVAT }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->TransmissionVAT, 4) ? number_format($rate->TransmissionVAT, 4) : $rate->TransmissionVAT }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->SystemLossVAT, 4) ? number_format($rate->SystemLossVAT, 4) : $rate->SystemLossVAT }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->DistributionVAT, 4) ? number_format($rate->DistributionVAT, 4) : $rate->DistributionVAT }}</p>
        <p style="margin-left: 70px; margin-top: 29px; ">{{ $account->ServiceAccountName }}</p>
        <p style="margin-left: 55px; margin-top: 2px; ">{{ ServiceAccounts::getAddress($account) }}</p>
        <p style="margin-left: 65px; margin-top: 2px; ">{{ $meters != null ? $meters->SerialNumber : $bills->MeterNumber }}</p>
    </div>    
    <div style="display: inline-table; width: 10%;">
        <p style="text-align: right; margin-top: 5px;">{{ number_format($bills->GenerationSystemCharge, 2) ? number_format($bills->GenerationSystemCharge, 2) : $bills->GenerationSystemCharge }}</p>
        <p style="text-align: right; margin-top: 5px;  opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 5px; opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 5px; opacity: 0;">0</p>
        <br>
        <p style="text-align: right; margin-top: 5px;">{{ number_format($bills->TransmissionDeliveryChargeKW, 2) ? number_format($bills->TransmissionDeliveryChargeKW, 2) : $bills->TransmissionDeliveryChargeKW }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->TransmissionDeliveryChargeKWH, 2) ? number_format($bills->TransmissionDeliveryChargeKWH, 2) : $bills->TransmissionDeliveryChargeKWH }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->SystemLossCharge, 2) ? number_format($bills->SystemLossCharge, 2) : $bills->SystemLossCharge }}</p>
        <br>
        <p style="text-align: right; margin-top: 8px; ">{{ number_format($bills->DistributionDemandCharge, 2) ? number_format($bills->DistributionDemandCharge, 2) : $bills->DistributionDemandCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->DistributionSystemCharge, 2) ? number_format($bills->DistributionSystemCharge, 2) : $bills->DistributionSystemCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->SupplySystemCharge, 2) ? number_format($bills->SupplySystemCharge, 2) : $bills->SupplySystemCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->SupplyRetailCustomerCharge, 2) ? number_format($bills->SupplyRetailCustomerCharge, 2) : $bills->SupplyRetailCustomerCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->MeteringSystemCharge, 2) ? number_format($bills->MeteringSystemCharge, 2) : $bills->MeteringSystemCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->MeteringRetailCustomerCharge, 2) ? number_format($bills->MeteringRetailCustomerCharge, 2) : $bills->MeteringRetailCustomerCharge }}</p>
        <p style="text-align: right; margin-top: 5px;  opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 5px;  opacity: 0;">0</p>
        <p style="text-align: right; opacity: 0;">0</p>
        <p style="text-align: right; opacity: 0;">0</p>
        <p style="text-align: right;  opacity: 0;">0</p>
        <p style="text-align: right;  opacity: 0;">0</p>
        <p style="text-align: right; ">{{ number_format($bills->GenerationVAT, 2) ? number_format($bills->GenerationVAT, 2) : $bills->GenerationVAT }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->TransmissionVAT, 2) ? number_format($bills->TransmissionVAT, 2) : $bills->TransmissionVAT }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->SystemLossVAT, 2) ? number_format($bills->SystemLossVAT, 2) : $bills->SystemLossVAT }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->DistributionVAT, 2) ? number_format($bills->DistributionVAT, 2) : $bills->DistributionVAT }}</p>
    </div>

    <div style="display: inline-table; width: 33%;">
        <p style="margin-left: 15px; margin-top: 5px; opacity: 0;">0</p>
        <p style="margin-left: 15px;  margin-top: 5px; opacity: 0;">0</p>
        <p style="margin-left: 15px; margin-top: 5px; opacity: 0;">0</p>
        <p style="margin-left: 15px; margin-top: 5px; opacity: 0;">0</p>
        <br>
        <p style="margin-left: 15px; margin-top: 5px; opacity: 0;">0</p>
        <p style="margin-left: 15px;  margin-top: 5px; opacity: 0;">0</p>        
        <p style="margin-left: 15px;  margin-top: 5px; opacity: 0;">0</p>
        <br>
        <p style="margin-left: 15px;  margin-top: 5px; opacity: 0;">0</p>
        <p style="margin-left: 15px;  margin-top: 5px; opacity: 0;">0</p>
        <p style="margin-left: 15px;  margin-top: 5px; opacity: 0;">0</p>
        <p style="margin-left: 15px;  margin-top: 5px; opacity: 0;">0</p>
        <p style="margin-left: 15px;  margin-top: 5px; opacity: 0;">0</p>  
        <p style="margin-left: 15px;  margin-top: 5px; opacity: 0;">0</p>
        <p style="margin-left: 15px;  margin-top: 5px; opacity: 0;">0</p>   
        <p style="margin-left: 20px; margin-top: 5px;">Other Generation Rate Adj. (Php/kWh)</p>
        <p style="margin-left: 20px;">Other Transmission Cost Adj. (Php/kW)</p>
        <p style="margin-left: 20px;">Other Transmission Cost Adj. (Php/kWh)</p>
        <p style="margin-left: 20px; ">Other System Loss Cost Adj. (Php/kWh)</p>
        <p style="margin-left: 20px;">Other Lifeline Rate Cost Adj. (Php/kWh)</p>
        <p style="margin-left: 20px;">Sen. Cit. Discnt. Subsidy Adj. (Php/kWh)</p>
        <p style="margin-left: 20px;">2% EVAT</p>
        <p style="text-align: right; margin-top: 28px;">{{ date('M d, Y', strtotime($bills->DueDate)) }}</p>
        <p style="margin-top: 30px; margin-left: 80px">{{ $account->OldAccountNo  }}</p>
        <p style="margin-left: 80px; margin-top: 2px; ">{{ date('F Y', strtotime($bills->ServicePeriod)) }}</p>
        <p style="margin-left: 80px; margin-top: 2px; ">{{ date('M d, Y', strtotime($bills->billsingDate)) }}</p>
    </div>

    <div style="display: inline-table; width: 10%;">
        <p style="text-align: right; margin-top: 5px;">{{ number_format($rate->LifelineRate, 4) ? number_format($rate->LifelineRate, 4) : $rate->LifelineRate }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->InterClassCrossSubsidyCharge, 4) ? number_format($rate->InterClassCrossSubsidyCharge, 4) : $rate->InterClassCrossSubsidyCharge }}</p>
        <p style="text-align: right; margin-top: 5px;">{{ number_format($rate->RealPropertyTax, 4) ? number_format($rate->RealPropertyTax, 4) : $rate->RealPropertyTax }}</p>
        <p style="text-align: right; margin-top: 5px;">{{ number_format($rate->SeniorCitizenSubsidy, 4) ? number_format($rate->SeniorCitizenSubsidy, 4) : $rate->SeniorCitizenSubsidy }}</p>
        <br>
        <p style="text-align: right; margin-top: 5px;">{{ number_format($rate->NPCStrandedDebt, 4) ? number_format($rate->NPCStrandedDebt, 4) : $rate->NPCStrandedDebt }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->StrandedContractCosts, 4) ? number_format($rate->StrandedContractCosts, 4) : $rate->StrandedContractCosts }}</p>
        <p style="text-align: right; margin-top: 5px;  opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 8px; ">{{ number_format($rate->MissionaryElectrificationCharge, 4) ? number_format($rate->MissionaryElectrificationCharge, 4) : $rate->MissionaryElectrificationCharge }}</p>
        <br>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->EnvironmentalCharge, 4) ? number_format($rate->EnvironmentalCharge, 4) : $rate->EnvironmentalCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->MissionaryElectrificationREDCI, 4) ? number_format($rate->MissionaryElectrificationREDCI, 4) : $rate->MissionaryElectrificationREDCI }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->FeedInTariffAllowance, 4) ? number_format($rate->FeedInTariffAllowance, 4) : $rate->FeedInTariffAllowance }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->RFSC, 4) ? number_format($rate->RFSC, 4) : $rate->RFSC }}</p>
        <p style="text-align: right; margin-top: 5px;  opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->PPARefund, 4) ? number_format($rate->PPARefund, 4) : $rate->PPARefund }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->OtherGenerationRateAdjustment, 4) ? number_format($rate->OtherGenerationRateAdjustment, 4) : $rate->OtherGenerationRateAdjustment }}</p>
        <p style="text-align: right;">{{ number_format($rate->OtherTransmissionCostAdjustmentKW, 4) ? number_format($rate->OtherTransmissionCostAdjustmentKW, 4) : $rate->OtherTransmissionCostAdjustmentKW }}</p>
        <p style="text-align: right; ">{{ number_format($rate->OtherTransmissionCostAdjustmentKWH, 4) ? number_format($rate->OtherTransmissionCostAdjustmentKWH, 4) : $rate->OtherTransmissionCostAdjustmentKWH }}</p>
        <p style="text-align: right; ">{{ number_format($rate->OtherSystemLossCostAdjustment, 4) ? number_format($rate->OtherSystemLossCostAdjustment, 4) : $rate->OtherSystemLossCostAdjustment }}</p>
        <p style="text-align: right; ">{{ number_format($rate->OtherLifelineRateCostAdjustment, 4) ? number_format($rate->OtherLifelineRateCostAdjustment, 4) : $rate->OtherLifelineRateCostAdjustment }}</p>
        <p style="text-align: right; ">{{ number_format($rate->SeniorCitizenDiscountAndSubsidyAdjustment, 4) ? number_format($rate->SeniorCitizenDiscountAndSubsidyAdjustment, 4) : $rate->SeniorCitizenDiscountAndSubsidyAdjustment }}</p>
        <p style="text-align: right; opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 8px;  opacity: 0;">0</p>
    </div>

    <div style="display: inline-table; width: 11%;">  
        <p style="text-align: right; margin-top: 5px;">{{ number_format($bills->LifelineRate, 2) ? number_format($bills->LifelineRate, 2) : $bills->LifelineRate }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->InterClassCrossSubsidyCharge, 2) ? number_format($bills->InterClassCrossSubsidyCharge, 2) : $bills->InterClassCrossSubsidyCharge }}</p>
        <p style="text-align: right; margin-top: 5px;">{{ number_format($bills->RealPropertyTax, 2) ? number_format($bills->RealPropertyTax, 2) : $bills->RealPropertyTax }}</p>
        <p style="text-align: right; margin-top: 5px;">{{ number_format($bills->SeniorCitizenSubsidy, 2) ? number_format($bills->SeniorCitizenSubsidy, 2) : $bills->SeniorCitizenSubsidy }}</p>
        <br>
        <p style="text-align: right; margin-top: 5px;">{{ number_format($bills->NPCStrandedDebt, 2) ? number_format($bills->NPCStrandedDebt, 2) : $bills->NPCStrandedDebt }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->StrandedContractCosts, 2) ? number_format($bills->StrandedContractCosts, 2) : $bills->StrandedContractCosts }}</p>
        <p style="text-align: right; margin-top: 5px;  opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 8px; ">{{ number_format($bills->MissionaryElectrificationCharge, 2) ? number_format($bills->MissionaryElectrificationCharge, 2) : $bills->MissionaryElectrificationCharge }}</p>
        <br>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->EnvironmentalCharge, 2) ? number_format($bills->EnvironmentalCharge, 2) : $bills->EnvironmentalCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->MissionaryElectrificationREDCI, 2) ? number_format($bills->MissionaryElectrificationREDCI, 2) : $bills->MissionaryElectrificationREDCI }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->FeedInTariffAllowance, 2) ? number_format($bills->FeedInTariffAllowance, 2) : $bills->FeedInTariffAllowance }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->RFSC, 2) ? number_format($bills->RFSC, 2) : $bills->RFSC }}</p>
        <p style="text-align: right; margin-top: 5px;  opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->PPARefund, 2) ? number_format($bills->PPARefund, 2) : $bills->PPARefund }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($bills->OtherGenerationRateAdjustment, 2) ? number_format($bills->OtherGenerationRateAdjustment, 2) : $bills->OtherGenerationRateAdjustment }}</p>
        <p style="text-align: right;">{{ number_format($bills->OtherTransmissionCostAdjustmentKW, 2) ? number_format($bills->OtherTransmissionCostAdjustmentKW, 2) : $bills->OtherTransmissionCostAdjustmentKW }}</p>
        <p style="text-align: right;">{{ number_format($bills->OtherTransmissionCostAdjustmentKWH, 2) ? number_format($bills->OtherTransmissionCostAdjustmentKWH, 2) : $bills->OtherTransmissionCostAdjustmentKWH }}</p>
        <p style="text-align: right; ">{{ number_format($bills->OtherSystemLossCostAdjustment, 2) ? number_format($bills->OtherSystemLossCostAdjustment, 2) : $bills->OtherSystemLossCostAdjustment }}</p>
        <p style="text-align: right; ">{{ number_format($bills->OtherLifelineRateCostAdjustment, 2) ? number_format($bills->OtherLifelineRateCostAdjustment, 2) : $bills->OtherLifelineRateCostAdjustment }}</p>
        <p style="text-align: right; ">{{ number_format($bills->SeniorCitizenDiscountAndSubsidyAdjustment, 2) ? number_format($bills->SeniorCitizenDiscountAndSubsidyAdjustment, 2) : $bills->SeniorCitizenDiscountAndSubsidyAdjustment }}</p>
        <p style="text-align: right; ">{{ number_format($bills->Evat2Percent, 2) ? number_format($bills->Evat2Percent, 2) : $bills->Evat2Percent }}</p>
        <p style="text-align: right; margin-top: 8px; ">{{ number_format($bills->NetAmount, 2) ? number_format($bills->NetAmount, 2) : $bills->NetAmount }}</p>
            
        <p style="text-align: right; margin-top: 5px; ">{{ number_format(Bills::assessDueBillAndGetSurcharge($bills), 2) }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format(floatval(Bills::assessDueBillAndGetSurcharge($bills)) + floatval($bills->NetAmount), 2)  }}</span>
        <p style="text-align: right; margin-top: 15px; ">{{ number_format(floatval(Bills::assessDueBillAndGetSurcharge($bills)) + floatval($bills->NetAmount), 2)  }}</span>
        @php
            $totalArr = 0.0;
            if ($arrears != null) {
                foreach($arrears as $bills) {
                    $totalArr = $totalArr += floatval($bills->NetAmount);
                }
            }
        @endphp
        <p style="text-align: right; margin-top: 2px; ">({{ $arrears != null ? count($arrears) : '0' }}) {{ number_format($totalArr, 2) }}</span>
        <p style="text-align: right; margin-top: 2px; ">{{ date('M d, Y', strtotime($bills->DueDate)) }}</span>
    </div>
</div>
<script type="text/javascript">
window.print();

window.setTimeout(function(){
    window.history.go(-1)
}, 800);
</script>