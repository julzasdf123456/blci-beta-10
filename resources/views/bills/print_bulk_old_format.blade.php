
@php
use App\Models\ServiceAccounts;
use App\Models\MemberConsumers;
use App\Models\Bills;
use App\Models\Rates;
use App\Models\BillingMeters;
use App\Models\ServiceConnectionAccountTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
ini_set('max_execution_time', 0);
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
        page-break-before: always;
    }

    .print-area:last-child {        
        page-break-after: auto;
    }

    p {
        margin: 1px !important;
        padding: 1px !important;
    }
}  

html, body {
    font-family: sans-serif;
    font-size: .8em !important;
}

p {
    margin: 1px !important;
    padding: 1px !important;
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
</style>

{{-- <link rel="stylesheet" href="{{ URL::asset('adminlte.min.css') }}"> --}}

@foreach ($bills as $item)
@php
    $acctType = ServiceConnectionAccountTypes::where('AccountType', $item->ConsumerType)->first();

    $account = DB::table('Billing_ServiceAccounts')
        ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
        ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
        ->select('Billing_ServiceAccounts.id',
                'Billing_ServiceAccounts.ServiceAccountName',
                'Billing_ServiceAccounts.OldAccountNo',
                'Billing_ServiceAccounts.AccountCount',
                'Billing_ServiceAccounts.Purok',
                'Billing_ServiceAccounts.AccountType',
                'Billing_ServiceAccounts.AccountStatus',
                'Billing_ServiceAccounts.AreaCode',
                'Billing_ServiceAccounts.SequenceCode',
                'Billing_ServiceAccounts.ForDistribution',
                'Billing_ServiceAccounts.Organization',
                'Billing_ServiceAccounts.Main',
                'Billing_ServiceAccounts.GroupCode',
                'Billing_ServiceAccounts.Multiplier',
                'Billing_ServiceAccounts.Coreloss',
                'Billing_ServiceAccounts.ConnectionDate',
                'Billing_ServiceAccounts.ServiceConnectionId',
                'Billing_ServiceAccounts.SeniorCitizen',
                'Billing_ServiceAccounts.Evat5Percent',
                'Billing_ServiceAccounts.Ewt2Percent',
                'Billing_ServiceAccounts.Contestable',
                'Billing_ServiceAccounts.NetMetered',
                'Billing_ServiceAccounts.AccountRetention',
                'Billing_ServiceAccounts.DurationInMonths',
                'Billing_ServiceAccounts.AccountExpiration',
                'CRM_Towns.Town',
                'CRM_Barangays.Barangay')
        ->where('Billing_ServiceAccounts.id', $item->AccountNumber)
        ->first();

    $meters = BillingMeters::where('ServiceAccountId', $item->AccountNumber)
        ->orderByDesc('created_at')
        ->first();

    if ($account != null) {
        $arrears = DB::table('Billing_Bills')
            ->whereRaw("Billing_Bills.id NOT IN (SELECT ObjectSourceId FROM Cashier_PaidBills WHERE AccountNumber='" . $account->id . "')")
            ->where('Billing_Bills.AccountNumber', $account->id)
            ->whereNotIN('Billing_Bills.id', [$item->id])
            ->select('Billing_Bills.*')
            ->get();

        $rate = Rates::where('ServicePeriod', $item->ServicePeriod)
            ->where('ConsumerType', Bills::getAccountType($account))
            ->first();
    } else {
        $arrears = null;
    }
@endphp
<div class="print-area" style="padding-top: 8px;">
    <br>
    <br>
    <br>
    <span style="margin-left: 80px;">{{ date('F Y', strtotime($item->ServicePeriod)) }}</span>
    <span style="margin-left: 135px;">{{ date('F d, Y', strtotime($item->BillingDate)) }}</span>
    <span style="margin-left: 200px;">{{ $item->BillNumber }}</span><br>
    <br>
    <div style="padding-top: 3px;">
        <span style="padding-top: 10px !important;">{{ $account->OldAccountNo }}</span>
        <span style="margin-left: 15px;">{{ $meters != null ? $meters->SerialNumber : '-' }}</span>
        <span style="margin-left: 55px;">{{ $acctType != null ? $acctType->Alias : '-' }}</span>
        <span style="margin-left: 15px;">{{ $item->Multiplier }}</span>
        <span style="margin-left: 35px;">{{ date('m/d/Y', strtotime($item->DueDate)) }}</span>
    </div>
    <br>
    <span style="padding-left: 35px; float: left;">{{ $account->ServiceAccountName }}</span>
    <span style="padding-left: 260px; float: left;">{{ date('M d, Y', strtotime($item->ServiceDateFrom)) }}</span>
    <span style="padding-left: 15px; float: left;">{{ date('M d, Y', strtotime($item->ServiceDateTo)) }}</span>
    <span style="padding-left: 24px; float: left;">{{ $item->PresentKwh }}</span>
    <span style="padding-left: 24px; float: left;">{{ $item->PreviousKwh }}</span>
    <span style="padding-left: 35px; float: left;">{{ round(floatval($item->KwhUsed) * floatval($item->Multiplier), 2) }}</span>
    <span style="padding-left: 35px; float: left;">{{ $item->KwhUsed }}</span>
    <br>
    <span style="padding-left: 30px; float: left;">{{ ServiceAccounts::getAddress($account) }}</span>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div style="display: inline-table; width: 37%;">
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
        <!-- <p style="text-align: right; opacity: 0;">0</p> -->
        <p style="text-align: right; ">{{ number_format($rate->GenerationVAT, 4) ? number_format($rate->GenerationVAT, 4) : $rate->GenerationVAT }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->TransmissionVAT, 4) ? number_format($rate->TransmissionVAT, 4) : $rate->TransmissionVAT }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->SystemLossVAT, 4) ? number_format($rate->SystemLossVAT, 4) : $rate->SystemLossVAT }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ number_format($rate->DistributionVAT, 4) ? number_format($rate->DistributionVAT, 4) : $rate->DistributionVAT }}</p>
        <br>
        <p style="padding-left: 70px !important; margin-top: 29px; ">{{ $account->ServiceAccountName }}</p>
        <p style="padding-left: 55px !important; margin-top: 2px; ">{{ ServiceAccounts::getAddress($account) }}</p>
        <p style="padding-left: 65px !important; margin-top: 2px; ">{{ $meters != null ? $meters->SerialNumber : $item->MeterNumber }}</p>
    </div>    
    <div style="display: inline-table; width: 10%; padding-right: 20px;">
        <p style="text-align: right; margin-top: 5px;">{{ is_numeric($item->GenerationSystemCharge) ? number_format($item->GenerationSystemCharge, 2) : $item->GenerationSystemCharge }}</p>
        <p style="text-align: right; margin-top: 5px;  opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 5px; opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 5px; opacity: 0;">0</p>
        <br>
        <p style="text-align: right; margin-top: 5px;">{{ is_numeric($item->TransmissionDeliveryChargeKW) ? number_format($item->TransmissionDeliveryChargeKW, 2) : $item->TransmissionDeliveryChargeKW }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->TransmissionDeliveryChargeKWH) ? number_format($item->TransmissionDeliveryChargeKWH, 2) : $item->TransmissionDeliveryChargeKWH }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->SystemLossCharge) ? number_format($item->SystemLossCharge, 2) : $item->SystemLossCharge }}</p>
        <br>
        <p style="text-align: right; margin-top: 8px; ">{{ is_numeric($item->DistributionDemandCharge) ? number_format($item->DistributionDemandCharge, 2) : $item->DistributionDemandCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->DistributionSystemCharge) ? number_format($item->DistributionSystemCharge, 2) : $item->DistributionSystemCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->SupplySystemCharge) ? number_format($item->SupplySystemCharge, 2) : $item->SupplySystemCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->SupplyRetailCustomerCharge) ? number_format($item->SupplyRetailCustomerCharge, 2) : $item->SupplyRetailCustomerCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->MeteringSystemCharge) ? number_format($item->MeteringSystemCharge, 2) : $item->MeteringSystemCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->MeteringRetailCustomerCharge) ? number_format($item->MeteringRetailCustomerCharge, 2) : $item->MeteringRetailCustomerCharge }}</p>
        <p style="text-align: right; margin-top: 5px;  opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 5px;  opacity: 0;">0</p>
        <p style="text-align: right; opacity: 0;">0</p>
        <p style="text-align: right; opacity: 0;">0</p>
        <p style="text-align: right;  opacity: 0;">0</p>
        <!-- <p style="text-align: right;  opacity: 0;">0</p> -->
        <p style="text-align: right;">{{ is_numeric($item->GenerationVAT) ? number_format($item->GenerationVAT, 2) : $item->GenerationVAT }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->TransmissionVAT) ? number_format($item->TransmissionVAT, 2) : $item->TransmissionVAT }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->SystemLossVAT) ? number_format($item->SystemLossVAT, 2) : $item->SystemLossVAT }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->DistributionVAT) ? number_format($item->DistributionVAT, 2) : $item->DistributionVAT }}</p>
    </div>

    <div style="display: inline-table; width: 30%;">
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
        <!-- <p style="margin-left: 15px;  margin-top: 5px; opacity: 0;">0</p> -->
        <p style="margin-left: 15px;  margin-top: 5px; opacity: 0;">0</p>   
        <p style="margin-left: 20px; padding: 0px !important;">Other Generation Rate Adj. (Php/kWh)</p>
        <p style="margin-left: 20px; padding: 0px !important;">Other Transmission Cost Adj. (Php/kW)</p>
        <p style="margin-left: 20px; padding: 0px !important;">Other Transmission Cost Adj. (Php/kWh)</p>
        <p style="margin-left: 20px; padding: 0px !important; ">Other System Loss Cost Adj. (Php/kWh)</p>
        <p style="margin-left: 20px; padding: 0px !important;">Other Lifeline Rate Cost Adj. (Php/kWh)</p>
        <p style="margin-left: 20px; padding: 0px !important;">Sen. Cit. Discnt. Subsidy Adj. (Php/kWh)</p>
        <p style="margin-left: 20px; padding: 0px !important;">2% EVAT</p>
        <p style="text-align: right; margin-top: 5px !important;">{{ date('M d, Y', strtotime($item->DueDate)) }}</p>
        <br>
        <p style="margin-top: 30px !important; padding-left: 80px !important;">{{ $account->OldAccountNo  }}</p>
        <p style="padding-left: 80px !important; margin-top: 2px; ">{{ date('F Y', strtotime($item->ServicePeriod)) }}</p>
        <p style="padding-left: 80px !important; margin-top: 2px; ">{{ date('M d, Y', strtotime($item->BillingDate)) }}</p>
    </div>

    <div style="display: inline-table; width: 8%;">
        <p style="text-align: right; margin-top: 5px;">{{ is_numeric($rate->LifelineRate) ? number_format($rate->LifelineRate, 4) : $rate->LifelineRate }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($rate->InterClassCrossSubsidyCharge) ? number_format($rate->InterClassCrossSubsidyCharge, 4) : $rate->InterClassCrossSubsidyCharge }}</p>
        <p style="text-align: right; margin-top: 5px;">{{ is_numeric($rate->RealPropertyTax) ? number_format($rate->RealPropertyTax, 4) : $rate->RealPropertyTax }}</p>
        <p style="text-align: right; margin-top: 5px;">{{ is_numeric($rate->SeniorCitizenSubsidy) ? number_format($rate->SeniorCitizenSubsidy, 4) : $rate->SeniorCitizenSubsidy }}</p>
        <br>
        <p style="text-align: right; margin-top: 5px;">{{ is_numeric($rate->NPCStrandedDebt) ? number_format($rate->NPCStrandedDebt, 4) : $rate->NPCStrandedDebt }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($rate->StrandedContractCosts) ? number_format($rate->StrandedContractCosts, 4) : $rate->StrandedContractCosts }}</p>
        <p style="text-align: right; margin-top: 5px;  opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 8px; ">{{ is_numeric($rate->MissionaryElectrificationCharge) ? number_format($rate->MissionaryElectrificationCharge, 4) : $rate->MissionaryElectrificationCharge }}</p>
        <br>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($rate->EnvironmentalCharge) ? number_format($rate->EnvironmentalCharge, 4) : $rate->EnvironmentalCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($rate->MissionaryElectrificationREDCI) ? number_format($rate->MissionaryElectrificationREDCI, 4) : $rate->MissionaryElectrificationREDCI }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($rate->FeedInTariffAllowance) ? number_format($rate->FeedInTariffAllowance, 4) : $rate->FeedInTariffAllowance }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($rate->RFSC) ? number_format($rate->RFSC, 4) : $rate->RFSC }}</p>
        <!-- <p style="text-align: right; margin-top: 5px;  opacity: 0;">0</p> -->
        <p style="text-align: right;">{{ is_numeric($rate->PPARefund) ? number_format($rate->PPARefund, 4) : $rate->PPARefund }}</p>
        <p style="text-align: right;  padding: 0px !important;">{{ is_numeric($rate->OtherGenerationRateAdjustment) ? number_format($rate->OtherGenerationRateAdjustment, 4) : $rate->OtherGenerationRateAdjustment }}</p>
        <p style="text-align: right; padding: 0px !important;">{{ is_numeric($rate->OtherTransmissionCostAdjustmentKW) ? number_format($rate->OtherTransmissionCostAdjustmentKW, 4) : $rate->OtherTransmissionCostAdjustmentKW }}</p>
        <p style="text-align: right;  padding: 0px !important;">{{ is_numeric($rate->OtherTransmissionCostAdjustmentKWH) ? number_format($rate->OtherTransmissionCostAdjustmentKWH, 4) : $rate->OtherTransmissionCostAdjustmentKWH }}</p>
        <p style="text-align: right;  padding: 0px !important;">{{ is_numeric($rate->OtherSystemLossCostAdjustment) ? number_format($rate->OtherSystemLossCostAdjustment, 4) : $rate->OtherSystemLossCostAdjustment }}</p>
        <p style="text-align: right;  padding: 0px !important;">{{ is_numeric($rate->OtherLifelineRateCostAdjustment) ? number_format($rate->OtherLifelineRateCostAdjustment, 4) : $rate->OtherLifelineRateCostAdjustment }}</p>
        <p style="text-align: right;  padding: 0px !important;">{{ is_numeric($rate->SeniorCitizenDiscountAndSubsidyAdjustment) ? number_format($rate->SeniorCitizenDiscountAndSubsidyAdjustment, 4) : $rate->SeniorCitizenDiscountAndSubsidyAdjustment }}</p>
        <p style="text-align: right; opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 8px;  opacity: 0;">0</p>
    </div>

    <div style="display: inline-table; width: 10%;">  
        <p style="text-align: right; margin-top: 5px;">{{ is_numeric($item->LifelineRate) ? number_format($item->LifelineRate, 2) : $item->LifelineRate }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->InterClassCrossSubsidyCharge) ? number_format($item->InterClassCrossSubsidyCharge, 2) : $item->InterClassCrossSubsidyCharge }}</p>
        <p style="text-align: right; margin-top: 5px;">{{ is_numeric($item->RealPropertyTax) ? number_format($item->RealPropertyTax, 2) : $item->RealPropertyTax }}</p>
        <p style="text-align: right; margin-top: 5px;">{{ is_numeric($item->SeniorCitizenSubsidy) ? number_format($item->SeniorCitizenSubsidy, 2) : $item->SeniorCitizenSubsidy }}</p>
        <br>
        <p style="text-align: right; margin-top: 5px;">{{ is_numeric($item->NPCStrandedDebt) ? number_format($item->NPCStrandedDebt, 2) : $item->NPCStrandedDebt }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->StrandedContractCosts) ? number_format($item->StrandedContractCosts, 2) : $item->StrandedContractCosts }}</p>
        <p style="text-align: right; margin-top: 5px;  opacity: 0;">0</p>
        <p style="text-align: right; margin-top: 8px; ">{{ is_numeric($item->MissionaryElectrificationCharge) ? number_format($item->MissionaryElectrificationCharge, 2) : $item->MissionaryElectrificationCharge }}</p>
        <br>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->EnvironmentalCharge) ? number_format($item->EnvironmentalCharge, 2) : $item->EnvironmentalCharge }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->MissionaryElectrificationREDCI) ? number_format($item->MissionaryElectrificationREDCI, 2) : $item->MissionaryElectrificationREDCI }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->FeedInTariffAllowance) ? number_format($item->FeedInTariffAllowance, 2) : $item->FeedInTariffAllowance }}</p>
        <p style="text-align: right; margin-top: 5px; ">{{ is_numeric($item->RFSC) ? number_format($item->RFSC, 2) : $item->RFSC }}</p>
        <!-- <p style="text-align: right; margin-top: 5px;  opacity: 0;">0</p> -->
        <p style="text-align: right;">{{ is_numeric($item->PPARefund) ? number_format($item->PPARefund, 2) : $item->PPARefund }}</p>
        <p style="text-align: right;   padding: 0px !important;">{{ is_numeric($item->OtherGenerationRateAdjustment) ? number_format($item->OtherGenerationRateAdjustment, 2) : $item->OtherGenerationRateAdjustment }}</p>
        <p style="text-align: right;  padding: 0px !important;">{{ is_numeric($item->OtherTransmissionCostAdjustmentKW) ? number_format($item->OtherTransmissionCostAdjustmentKW, 2) : $item->OtherTransmissionCostAdjustmentKW }}</p>
        <p style="text-align: right;  padding: 0px !important;">{{ is_numeric($item->OtherTransmissionCostAdjustmentKWH) ? number_format($item->OtherTransmissionCostAdjustmentKWH, 2) : $item->OtherTransmissionCostAdjustmentKWH }}</p>
        <p style="text-align: right;  padding: 0px !important; ">{{ is_numeric($item->OtherSystemLossCostAdjustment) ? number_format($item->OtherSystemLossCostAdjustment, 2) : $item->OtherSystemLossCostAdjustment }}</p>
        <p style="text-align: right;  padding: 0px !important; ">{{ is_numeric($item->OtherLifelineRateCostAdjustment) ? number_format($item->OtherLifelineRateCostAdjustment, 2) : $item->OtherLifelineRateCostAdjustment }}</p>
        <p style="text-align: right;   padding: 0px !important;">{{ is_numeric($item->SeniorCitizenDiscountAndSubsidyAdjustment) ? number_format($item->SeniorCitizenDiscountAndSubsidyAdjustment, 2) : $item->SeniorCitizenDiscountAndSubsidyAdjustment }}</p>
        <p style="text-align: right;   padding: 0px !important;">{{ is_numeric($item->Evat2Percent) ? number_format($item->Evat2Percent, 2) : $item->Evat2Percent }}</p>
        <p style="text-align: right; margin-top: 8px; ">{{ is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : $item->NetAmount }}</p>

        <p style="text-align: right; margin-top: 5px; ">{{ number_format(Bills::assessDueBillAndGetSurcharge($item), 2) }}</p>
        <p style="text-align: right; margin-top: 5px !important;">{{ number_format(floatval(Bills::assessDueBillAndGetSurcharge($item)) + floatval($item->NetAmount), 2)  }}</p>
        <br>
        <p style="text-align: right; margin-top: 15px; ">{{ number_format(floatval(Bills::assessDueBillAndGetSurcharge($item)) + floatval($item->NetAmount), 2)  }}</p>
        
        @php
            $totalArr = 0.0;
            if ($arrears != null) {
                foreach($arrears as $itemx) {
                    $totalArr = $totalArr += floatval($itemx->NetAmount);
                }
            }
        @endphp
        <p style="text-align: right; margin-top: 2px; ">({{ $arrears != null ? count($arrears) : '0' }}) {{ number_format($totalArr, 2) }}</span>
        <p style="text-align: right; margin-top: 2px; ">{{ date('M d, Y', strtotime($item->DueDate)) }}</span>
    </div>
    
    
</div>
@endforeach

<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>