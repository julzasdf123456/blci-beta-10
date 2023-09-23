
@php
use App\Models\ServiceAccounts;
use App\Models\MemberConsumers;
use App\Models\Bills;
@endphp

<style>
html, body {
    font-family: sans-serif;
    font-stretch: condensed;
    font-size: .85em;
}

th, td {
    font-family: sans-serif;
    font-stretch: condensed;
    font-size: .68em;
}

@media print {
    @page {
        /* size: landscape !important; */
    }

    header {
        display: none;
    }

    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    }

    .left-indent {
        margin-left: 30px;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .print-area {        
        page-break-after: always;
    }

    .print-area:last-child {        
        page-break-after: auto;
    }

    .u-bottom {
        border-bottom: 1px solid #444555;
        padding-bottom: 2px;
        padding-left: 10px;
        padding-right: 10px;
    }

    .half {
        display: inline-table; 
        width: 49%;
    }

    table, th, tr {
        border-collapse: collapse;
        border: 1px solid #444555;
    }

    p {
        margin: 0;
        padding: 0;
    }
}  

.left-indent {
    padding-left: 15px;
}

.left-indent-more {
    padding-left: 40px;
}

.text-right {
    text-align: right;
}

.text-left {
    text-align: left;
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

.u-bottom {
    border-bottom: 1px solid #444555;
    padding-bottom: 2px;
    padding-left: 10px;
    padding-right: 10px;
}

.half {
    display: inline-table; 
    width: 49%;
}

table, th, tr, td {
    border-collapse: collapse;
    border: 1px solid #444555;
}

.no-border-top-bottom {
    border-bottom: 0px !important;
    border-top: 0px !important;
}

.border-bottom-only {
    border-bottom: 1px solid #444555 !important;
    border-top: 0px !important;
}

p {
    margin: 0;
    padding: 0;
}
</style>

<script src="{{ URL::asset('js/jquery.min.css'); }}"></script>
<script src="{{ URL::asset('js/barcodejs.js'); }}"></script>

<div id="print-area">
    <div style="text-align: center; display: inline;">
        <img src="{{ URL::asset('imgs/noneco-official-logo.png'); }}" width="60px;" style="position: absolute; left: 0; top: 0;"> 

        <p class="text-center"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>
        <p class="text-center">{{ env('APP_ADDRESS') }}  |  {{ env('APP_COMPANY_TIN') }}</p>
        <p class="text-center">{{ env('APP_COMPANY_CONTACT') }}</p>
        <p class="text-center">{{ env('APP_COMPANY_EMAIL') }}</p>

        <h4 class="text-center">STATEMENT OF ACCOUNT</h4>
    </div>

    <span>
        BILLING MONTH: <span class="u-bottom" style="margin-right: 30px;">{{ strtoupper(date("F Y", strtotime($bills->ServicePeriod))) }}</span>
        DATE BILLED: <span class="u-bottom" style="margin-right: 30px;">{{ strtoupper(date("F d, Y", strtotime($bills->BillingDate))) }}</span>
        BILL NUMBER: <span class="u-bottom">{{ $bills->BillNumber }}</span>
    </span>
    <div style="width: 100%; height: 5px;"></div>
    <div class="half" style="float: left;">
        <table class="bordered" style="width: 100%;">
            <tr>
                <td class="text-center">ACCOUNT NO.</td>
                <td class="text-center">METER NO.</td>
                <td class="text-center">TYPE</td>
                <td class="text-center">KWH MULT.</td>
                <td class="text-center">DUE DATE</td>
            </tr>
            <tr>
                <th class="text-center" id="acctno">{{ $account->OldAccountNo }}</th>
                <th class="text-center">{{ $meters != null ? $meters->SerialNumber : '' }}</th>
                <th class="text-center">{{ substr($bills->ConsumerType, 0, 1) }}</th>
                <th class="text-center">{{ $bills->Multiplier }}</th>
                <th class="text-center">{{ date('M d, Y', strtotime($bills->DueDate)) }}</th>
            </tr>
            <tr  class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left" colspan="5">NAME: <strong>{{ $account->ServiceAccountName }}</strong></td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left" colspan="5">ADDRESS: <strong>{{ ServiceAccounts::getAddress($account) }}</strong></td>
            </tr>
        </table>

        <table class="bordered" style="width: 100%; margin-top: 2px;">
            <tr>
                <th class="text-center">ELECTRIC BILL CHARGES</th>
                <th class="text-center">RATE</th>
                <th class="text-center">AMOUNT</th>
            </tr>
            {{-- GENERATION CHARGES --}}
            <tr class="no-border-top-bottom">
                <th class="no-border-top-bottom text-left">I. GENERATION, TRANS., AND SYSTEM LOSS CHARGES</th>
                <th class="no-border-top-bottom"></th>
                <th class="no-border-top-bottom"></th>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Generation System Charge (Php/kWh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->GenerationSystemCharge }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->GenerationSystemCharge, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">GRAM/ICERA/ACRM</td>
                <td class="no-border-top-bottom text-right">{{ $rate->ACRM }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->ACRM, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Transmission Delivery Charge (Php/kWh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->TransmissionDeliveryChargeKWH }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->TransmissionDeliveryChargeKWH, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">System Loss Charge (Php/kWh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->SystemLossCharge }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->SystemLossCharge, 2) }}</td>
            </tr>
            
            {{-- DISTRIBUTION CHARGES --}}
            <tr class="no-border-top-bottom">
                <th class="no-border-top-bottom text-left">II. DISTRIBUTION, SUPPLY, & METERING REVENUES</th>
                <th class="no-border-top-bottom"></th>
                <th class="no-border-top-bottom"></th>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Distribution Demand  Charge (Php/kw)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->DistributionDemandCharge }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->DistributionDemandCharge, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Distribution System Charge (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->DistributionSystemCharge }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->DistributionSystemCharge, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Supply Retail Cust. Charge (Php/cust/mo)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->SupplyRetailCustomerCharge }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->SupplyRetailCustomerCharge, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Metering Retail Cust. Charge (Php/cust/mo)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->MeteringRetailCustomerCharge }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->MeteringRetailCustomerCharge, 2) }}</td>
            </tr>
            <tr class="border-bottom-only">
                <td class="border-bottom-only text-left left-indent">Metering System Charge (Php/kwh)</td>
                <td class="border-bottom-only text-right">{{ $rate->MeteringSystemCharge }}</td>
                <td class="border-bottom-only text-right">{{ number_format($bills->MeteringSystemCharge, 2) }}</td>
            </tr>

            {{-- GOVERNMENT REVENUES --}}
            <tr class="no-border-top-bottom">
                <th class="no-border-top-bottom text-left">III. GOVERNMENT REVENUES (VAT)</th>
                <th class="no-border-top-bottom"></th>
                <th class="no-border-top-bottom"></th>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">VAT: Generation (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->GenerationVAT }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->GenerationVAT, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">VAT: GRAM/ICERA/ACRM  (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->ACRMVAT }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->ACRMVAT, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">VAT: Transmission (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->TransmissionVAT }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->TransmissionVAT, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">VAT: System Loss (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->SystemLossVAT }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->SystemLossVAT, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">VAT: Distribution (%)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->DistributionVAT }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->DistributionVAT, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">VAT: Others (%)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->OthersVAT }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->OthersVAT, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Franchise Tax (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->FranchiseTax }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->FranchiseTax, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Franchise Tax (Others)</td>
                <td class="no-border-top-bottom text-right">???</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->FranchiseTaxOthers, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Business Tax (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->BusinessTax }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->BusinessTax, 2) }}</td>
            </tr>
            <tr class="border-bottom-only">
                <td class="border-bottom-only text-left left-indent" style="padding-bottom: 3px;">Real Property Tax (RPT) (Php/kwh)</td>
                <td class="border-bottom-only text-right">{{ $rate->RealPropertyTax }}</td>
                <td class="border-bottom-only text-right">{{ number_format($bills->RealPropertyTax, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="half" style="float-right; margin-left: 5px;">
        <table class="bordered" style="width: 100%;">
            <tr>
                <td class="text-center" colspan="2">PERIOD COVERED</td>
                <td class="text-center" colspan="2">READING</td>
                <td class="text-center" rowspan="2">KWH USED</td>
                <td class="text-center" rowspan="2">TOTAL KWH</td>
            </tr>
            <tr>
                <td class="text-center">FROM</td>
                <td class="text-center">TO</td>
                <td class="text-center">PRES</td>
                <td class="text-center">PREV</td>
            </tr>
            <tr>
                <th class="text-center">{{ date('m/d/Y', strtotime($bills->ServiceDateFrom)) }}</th>
                <th class="text-center">{{ date('m/d/Y', strtotime($bills->ServiceDateTo)) }}</th>
                <th class="text-center">{{ $bills->PresentKwh }}</th>
                <th class="text-center">{{ $bills->PreviousKwh }}</th>
                <th class="text-center">{{ is_numeric($bills->Multiplier) ? round(floatval($bills->PresentKwh) - floatval($bills->PreviousKwh),2) : 'MULT_ERR' }}</th>
                <th class="text-center">{{ $bills->KwhUsed }}</th>
            </tr>
            <tr>
                <td colspan="6">DEMAND: <strong>{{ $bills->DemandPresentKwh }}</strong></td>
            </tr>
        </table>

        <table class="bordered" style="width: 100%; margin-top: 2px;">
            <tr>
                <th class="text-center">ELECTRIC BILL CHARGES</th>
                <th class="text-center">RATE</th>
                <th class="text-center">AMOUNT</th>
            </tr>

            {{-- OTHER CHARGES --}}
            <tr class="no-border-top-bottom">
                <th class="no-border-top-bottom text-left">IV. OTHER CHARGES</th>
                <th class="no-border-top-bottom"></th>
                <th class="no-border-top-bottom"></th>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Lifeline Rate (Discount)/Subsidy (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->LifelineRate }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->LifelineRate, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">ICC Subsidy Charge (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->InterClassCrossSubsidyCharge }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->InterClassCrossSubsidyCharge, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Power Act Reduction (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->RFSC }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->RFSC, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Senior Citizen Subsidy (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->SeniorCitizenSubsidy }}</td>
                <td class="no-border-top-bottom text-right">{{ intval($bills->SeniorCitizenSubsidy) < 0 ? '-' : number_format($bills->SeniorCitizenSubsidy, 2) }}</td>
            </tr>

            {{-- UNVERISAL CHARGES --}}
            <tr class="no-border-top-bottom">
                <th class="no-border-top-bottom text-left">V. UNIVERSAL CHARGES</th>
                <th class="no-border-top-bottom"></th>
                <th class="no-border-top-bottom"></th>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Environmental Charge (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->EnvironmentalCharge }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->EnvironmentalCharge, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Stranded Contract Costs (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->StrandedContractCosts }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->StrandedContractCosts, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">NPC Stranded Debt (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->NPCStrandedDebt }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->NPCStrandedDebt, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Feed-in Tariff Allowance_FIT-All (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->FeedInTariffAllowance }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->FeedInTariffAllowance, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Missionary Elect. - RED (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->MissionaryElectrificationREDCI }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->MissionaryElectrificationREDCI, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Missionary Elect. - SPUG (Php/kwh)</td>
                <td class="no-border-top-bottom text-right">{{ $rate->MissionaryElectrificationSPUG }}</td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->MissionaryElectrificationSPUG, 2) }}</td>
            </tr>
            <tr class="border-bottom-only">
                <td class="border-bottom-only text-left left-indent">Missionary Elect. - SPUG TRUE UP (Php/kwh)</td>
                <td class="border-bottom-only text-right">{{ $rate->MissionaryElectrificationSPUGTRUEUP }}</td>
                <td class="border-bottom-only text-right">{{ number_format($bills->MissionaryElectrificationSPUGTRUEUP, 2) }}</td>
            </tr>

            {{-- ADJUSTMENTS & OTHER CHARGES --}}
            <tr class="no-border-top-bottom">
                <th class="no-border-top-bottom text-left">VI. ADJUSTMENTS & OTHER CHARGES</th>
                <th class="no-border-top-bottom"></th>
                <th class="no-border-top-bottom"></th>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">2% EWT</td>
                <td class="no-border-top-bottom text-right"></td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->Evat2Percent, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">5% CWVAT</td>
                <td class="no-border-top-bottom text-right"></td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->Evat5Percent, 2) }}</td>
            </tr>
            <tr class="border-bottom-only">
                <td class="border-bottom-only text-left left-indent">Senior Citizen Discount</td>
                <td class="border-bottom-only text-right"></td>
                <td class="border-bottom-only text-right">{{ intval($bills->SeniorCitizenSubsidy) < 0 ? number_format($bills->SeniorCitizenSubsidy, 2) : '' }}</td>
            </tr>

            {{-- TOTAL --}}
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Adv. Mat. Deposit</td>
                <td class="no-border-top-bottom text-right"></td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->AdvancedMaterialDeposit, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Customer Deposit</td>
                <td class="no-border-top-bottom text-right"></td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->CustomerDeposit, 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Deductions/Prepayments</td>
                <td class="no-border-top-bottom text-right"></td>
                <td class="no-border-top-bottom text-right">-{{ number_format(floatval($bills->Deductions) + floatval($bills->DeductedDeposit), 2) }}</td>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Termed Payments</td>
                <td class="no-border-top-bottom text-right"></td>
                <td class="no-border-top-bottom text-right">{{ number_format($bills->AdditionalCharges, 2) }}</td>
            </tr>
            {{-- <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent">Katas Ng VAT</td>
                <td class="no-border-top-bottom text-right"></td>
                <td class="no-border-top-bottom text-right">-{{ number_format($bills->KatasNgVat, 2) }}</td>
            </tr> --}}
            <tr class="no-border-top-bottom">
                <th class="no-border-top-bottom text-left left-indent">CURRENT AMOUNT DUE on/before</th>
                <td class="no-border-top-bottom text-right"></td>
                <th class="no-border-top-bottom text-right">{{ number_format($bills->NetAmount, 2) }}</th>
            </tr>
            {{-- <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom text-left left-indent-more">Due Date: <strong>{{ date('M d, Y', strtotime($bills->DueDate)) }}</strong></td>
                <td class="no-border-top-bottom text-right"></td>
                <td class="no-border-top-bottom text-right"></td>
            </tr> --}}
            <tr class="no-border-top-bottom">
                <th class="no-border-top-bottom text-left left-indent">Penalty Charges After</th>
                <td class="no-border-top-bottom text-right"></td>
                <th class="no-border-top-bottom text-right">{{ number_format(Bills::assessDueBillAndGetSurcharge($bills), 2) }}</th>
            </tr>
            <tr class="no-border-top-bottom">
                <th class="no-border-top-bottom text-left left-indent">TOTAL GROSS AMOUNT</th>
                <td class="no-border-top-bottom text-right"></td>
                <th class="no-border-top-bottom text-right">{{ number_format(floatval($bills->NetAmount) + floatval($bills->Evat5Percent) + floatval($bills->Evat2Percent), 2) }}</th>
            </tr>
            <tr class="no-border-top-bottom">
                <th class="no-border-top-bottom text-left left-indent">TOTAL AMOUNT DUE After</th>
                <td class="no-border-top-bottom text-right"></td>
                <th class="no-border-top-bottom text-right">{{ number_format(floatval(Bills::assessDueBillAndGetSurcharge($bills)) + floatval($bills->NetAmount), 2) }}</th>
            </tr>
        </table>
    </div>
    
    <div style="width: 99%; margin-top: 5px; border-top: 1px dotted #444555">
        <p><i>Reserved For Authorized Collection Agents</i></p>
    </div>

    <div class="half" style="float: left;">
        <table class="bordered" style="width: 100%;">
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom">ACCOUNT NAME</td>
                <th class="no-border-top-bottom text-left">{{ $account->ServiceAccountName }}</th>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom">ACCOUNT ADDRESS</td>
                <th class="no-border-top-bottom text-left">{{ ServiceAccounts::getAddress($account) }}</th>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom">METER NO</td>
                <th class="no-border-top-bottom text-left">{{ $meters != null ? $meters->SerialNumber : '' }}</th>
            </tr>
        </table>
    </div>

    <div class="half" style="float-right; margin-left: 5px;">
        <table class="bordered" style="width: 100%;">
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom">ACCOUNT NO</td>
                <th class="no-border-top-bottom text-left">{{ $account->OldAccountNo }}</th>
                <td class="no-border-top-bottom">AMOUNT DUE</td>
                <th class="no-border-top-bottom text-right">{{ number_format(floatval(Bills::assessDueBillAndGetSurcharge($bills)) + floatval($bills->NetAmount), 2) }}</th>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom">BILLING MO</td>
                <th class="no-border-top-bottom text-left">{{ date('F Y', strtotime($bills->ServicePeriod)) }}</th>
                <td class="no-border-top-bottom">ARREARS</td>
                <th class="no-border-top-bottom text-right">{{ $arrears != null ? ($arrears->Countx . ' (' . number_format($arrears->Total, 2) . ')') : "0" }}</th>
            </tr>
            <tr class="no-border-top-bottom">
                <td class="no-border-top-bottom">DATE BILLED</td>
                <th class="no-border-top-bottom text-left">{{ date('M d, Y', strtotime($bills->BillingDate)) }}</th>
                <td class="no-border-top-bottom">DUE DATE</td>
                <th class="no-border-top-bottom text-right">{{ date('M d, Y', strtotime($bills->DueDate)) }}</th>
            </tr>
        </table>
    </div>
    
    <div style="width: 100%; text-align: center;">
        <svg id="barcode"></svg>
    </div>
    
</div>
<script type="text/javascript">
JsBarcode("#barcode", $('#acctno').text(), {
    format : "code39",
    width: 1,
    height: 25,
    displayValue: false
})

window.print();

window.setTimeout(function(){
    window.history.go(-1)
}, 800);
</script>