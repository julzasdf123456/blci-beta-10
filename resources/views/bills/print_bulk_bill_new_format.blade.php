
@php
    ob_start("ob_gzhandler");
    use App\Models\ServiceAccounts;
    use App\Models\Bills;
    use App\Models\Rates;
@endphp

<style>
    html, body {
        font-family: sans-serif;
        font-stretch: condensed;
        font-size: .85em;
        overflow: visible;
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

@foreach ($bills as $item)
    @php
        $rate = Rates::where('ServicePeriod', $item->ServicePeriod)
            ->where('ConsumerType', Rates::filterConsumerType($item->ConsumerType))
            ->first();
    @endphp

    <div class="print-area">
        <div style="width: 100%;">
            <img src="{{ URL::asset('imgs/noneco-official-logo.png'); }}" width="60px;" style="float: left;"> 

            <p class="text-center"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>
            <p class="text-center">{{ env('APP_ADDRESS') }}  |  {{ env('APP_COMPANY_TIN') }}</p>
            <p class="text-center">{{ env('APP_COMPANY_CONTACT') }}</p>
            <p class="text-center">{{ env('APP_COMPANY_EMAIL') }}</p>

            <h4 class="text-center">STATEMENT OF ACCOUNT</h4>

            <span>
                BILLING MONTH: <span class="u-bottom" style="margin-right: 30px;">{{ strtoupper(date("F Y", strtotime($item->ServicePeriod))) }}</span>
                DATE BILLED: <span class="u-bottom" style="margin-right: 30px;">{{ strtoupper(date("F d, Y", strtotime($item->BillingDate))) }}</span>
                BILL NUMBER: <span class="u-bottom">{{ $item->BillNumber }}</span>
            </span>
        </div>
        <div style="width: 100%; height: 5px;"></div>
        <div style="width: 100%; position: relative;">
            <div class="half" style="float: left;">
                <table class="bordered" style="width: 100%;">
                    <tr>
                        <td class="text-center">ACCOUNT NUMBER</td>
                        <td class="text-center">METER NUMBER</td>
                        <td class="text-center">TYPE</td>
                        <td class="text-center">KWH MULT.</td>
                        <td class="text-center">DUE DATE</td>
                    </tr>
                    <tr>
                        <th class="text-center" id="acctno">{{ $item->OldAccountNo }}</th>
                        <th class="text-center">{{ $item->MeterNumber }}</th>
                        <th class="text-center">{{ substr($item->ConsumerType, 0, 1) }}</th>
                        <th class="text-center">{{ $item->Multiplier }}</th>
                        <th class="text-center">{{ date('M d, Y', strtotime($item->DueDate)) }}</th>
                    </tr>
                    <tr  class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left" colspan="5">NAME: <strong>{{ $item->ServiceAccountName }}</strong></td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left" colspan="5">ADDRESS: <strong>{{ ServiceAccounts::getAddress($item) }}</strong></td>
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
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->GenerationSystemCharge) ? number_format($item->GenerationSystemCharge, 2) : $item->GenerationSystemCharge }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Transmission Delivery Charge (Php/kW)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->TransmissionDeliveryChargeKW }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->TransmissionDeliveryChargeKW) ? number_format($item->TransmissionDeliveryChargeKW, 2) : $item->TransmissionDeliveryChargeKW }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Transmission Delivery Charge (Php/kWh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->TransmissionDeliveryChargeKWH }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->TransmissionDeliveryChargeKWH) ? number_format($item->TransmissionDeliveryChargeKWH, 2) : $item->TransmissionDeliveryChargeKWH }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">System Loss Charge (Php/kWh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->SystemLossCharge }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->SystemLossCharge) ? number_format($item->SystemLossCharge, 2) : $item->SystemLossCharge }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Other Gen. Rate Adjustment_OGA (Php/kWh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->OtherGenerationRateAdjustment }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->OtherGenerationRateAdjustment) ? number_format($item->OtherGenerationRateAdjustment, 2) : $item->OtherGenerationRateAdjustment }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Other Trans. Cost Adjustment_OTCA (Php/kW)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->OtherTransmissionCostAdjustmentKW }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->OtherTransmissionCostAdjustmentKW) ? number_format($item->OtherTransmissionCostAdjustmentKW, 2) : $item->OtherTransmissionCostAdjustmentKW }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Other Trans. Cost Adjustment_OTCA (Php/kWh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->OtherTransmissionCostAdjustmentKWH }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->OtherTransmissionCostAdjustmentKWH) ? number_format($item->OtherTransmissionCostAdjustmentKWH, 2) : $item->OtherTransmissionCostAdjustmentKWH }}</td>
                    </tr>
                    <tr class="border-bottom-only">
                        <td class="border-bottom-only text-left left-indent">Other Sys/ Loss Cost Adjustment_OSLA (Php/kWh)</td>
                        <td class="border-bottom-only text-right">{{ $rate->OtherSystemLossCostAdjustment }}</td>
                        <td class="border-bottom-only text-right">{{ is_numeric($item->OtherSystemLossCostAdjustment) ? number_format($item->OtherSystemLossCostAdjustment, 2) : $item->OtherSystemLossCostAdjustment }}</td>
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
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->DistributionDemandCharge) ? number_format($item->DistributionDemandCharge, 2) : $item->DistributionDemandCharge }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Distribution System Charge (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->DistributionSystemCharge }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->DistributionSystemCharge) ? number_format($item->DistributionSystemCharge, 2) : $item->DistributionSystemCharge }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Supply Retail Cust. Charge (Php/cust/mo)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->SupplyRetailCustomerCharge }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->SupplyRetailCustomerCharge) ? number_format($item->SupplyRetailCustomerCharge, 2) : $item->SupplyRetailCustomerCharge }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Supply System Charge (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->SupplySystemCharge }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->SupplySystemCharge) ? number_format($item->SupplySystemCharge, 2) : $item->SupplySystemCharge }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Metering Retail Cust. Charge (Php/cust/mo)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->MeteringRetailCustomerCharge }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->MeteringRetailCustomerCharge) ? number_format($item->MeteringRetailCustomerCharge, 2) : $item->MeteringRetailCustomerCharge }}</td>
                    </tr>
                    <tr class="border-bottom-only">
                        <td class="border-bottom-only text-left left-indent">Metering System Charge (Php/kwh)</td>
                        <td class="border-bottom-only text-right">{{ $rate->MeteringSystemCharge }}</td>
                        <td class="border-bottom-only text-right">{{ is_numeric($item->MeteringSystemCharge) ? number_format($item->MeteringSystemCharge, 2) : $item->MeteringSystemCharge }}</td>
                    </tr>

                    {{-- GOVERNMENT REVENUES --}}
                    <tr class="no-border-top-bottom">
                        <th class="no-border-top-bottom text-left">III. GOVERNMENT REVENUES (VAT)</th>
                        <th class="no-border-top-bottom"></th>
                        <th class="no-border-top-bottom"></th>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">VAT Rate: Generation (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->GenerationVAT }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->GenerationVAT) ? number_format($item->GenerationVAT, 2) : $item->GenerationVAT }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">VAT Rate: Transmission (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->TransmissionVAT }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->TransmissionVAT) ? number_format($item->TransmissionVAT, 2) : $item->TransmissionVAT }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">VAT Rate: System Loss (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->SystemLossVAT }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->SystemLossVAT) ? number_format($item->SystemLossVAT, 2) : $item->SystemLossVAT }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">VAT Rate: Distribution & Others (%)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->DistributionVAT }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->DistributionVAT) ? number_format($item->DistributionVAT, 2) : $item->DistributionVAT }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Franchise Tax (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->FranchiseTax }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->FranchiseTax) ? number_format($item->FranchiseTax, 2) : $item->FranchiseTax }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Business Tax (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->BusinessTax }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->BusinessTax) ? number_format($item->BusinessTax, 2) : $item->BusinessTax }}</td>
                    </tr>
                    <tr class="border-bottom-only">
                        <td class="border-bottom-only text-left left-indent" style="padding-bottom: 3px;">Real Property Tax (RPT) (Php/kwh)</td>
                        <td class="border-bottom-only text-right">{{ $rate->RealPropertyTax }}</td>
                        <td class="border-bottom-only text-right">{{ is_numeric($item->RealPropertyTax) ? number_format($item->RealPropertyTax, 2) : $item->RealPropertyTax }}</td>
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
                        <th class="text-center">{{ date('m/d/Y', strtotime($item->ServiceDateFrom)) }}</th>
                        <th class="text-center">{{ date('m/d/Y', strtotime($item->ServiceDateTo)) }}</th>
                        <th class="text-center">{{ $item->PresentKwh }}</th>
                        <th class="text-center">{{ $item->PreviousKwh }}</th>
                        <th class="text-center">{{ is_numeric($item->Multiplier) ? round(floatval($item->KwhUsed) / floatval($item->Multiplier)) : 'MULT_ERR' }}</th>
                        <th class="text-center">{{ $item->KwhUsed }}</th>
                    </tr>
                    <tr>
                        <td colspan="6">DEMAND: <strong>{{ $item->DemandPresentKwh }}</strong></td>
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
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->LifelineRate) ? number_format($item->LifelineRate, 2) : $item->LifelineRate }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Senior Citizen Subsidy (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->SeniorCitizenSubsidy }}</td>
                        <td class="no-border-top-bottom text-right">{{ intval($item->SeniorCitizenSubsidy) < 0 ? '-' : is_numeric($item->SeniorCitizenSubsidy) ? number_format($item->SeniorCitizenSubsidy, 2) : $item->SeniorCitizenSubsidy }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Other Lifeline Rate Cost Adj._OLRA (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->OtherLifelineRateCostAdjustment }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->OtherLifelineRateCostAdjustment) ? number_format($item->OtherLifelineRateCostAdjustment, 2) : $item->OtherLifelineRateCostAdjustment }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Senior Cit. Discount & Subsidy Adj. (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->SeniorCitizenDiscountAndSubsidyAdjustment }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->SeniorCitizenDiscountAndSubsidyAdjustment) ? number_format($item->SeniorCitizenDiscountAndSubsidyAdjustment, 2) : $item->SeniorCitizenDiscountAndSubsidyAdjustment }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Reinvestment Fund for Sust. CAPEX_RFSC (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->RFSC }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->RFSC) ? number_format($item->RFSC, 2) : $item->RFSC }}</td>
                    </tr>
                    <tr class="border-bottom-only">
                        <td class="border-bottom-only text-left left-indent">Feed-in Tariff Allowance_FIT-All (Php/kwh)</td>
                        <td class="border-bottom-only text-right">{{ $rate->FeedInTariffAllowance }}</td>
                        <td class="border-bottom-only text-right">{{ is_numeric($item->FeedInTariffAllowance) ? number_format($item->FeedInTariffAllowance, 2) : $item->FeedInTariffAllowance }}</td>
                    </tr>

                    {{-- UNVERISAL CHARGES --}}
                    <tr class="no-border-top-bottom">
                        <th class="no-border-top-bottom text-left">V. UNIVERSAL CHARGES</th>
                        <th class="no-border-top-bottom"></th>
                        <th class="no-border-top-bottom"></th>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Missionary Electrification Charge (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->MissionaryElectrificationCharge }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->MissionaryElectrificationCharge) ? number_format($item->MissionaryElectrificationCharge, 2) : $item->MissionaryElectrificationCharge }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Environmental Charge (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->EnvironmentalCharge }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->EnvironmentalCharge) ? number_format($item->EnvironmentalCharge, 2) : $item->EnvironmentalCharge }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Stranded Contract Costs (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->StrandedContractCosts }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->StrandedContractCosts) ? number_format($item->StrandedContractCosts, 2) : $item->StrandedContractCosts }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">NPC Stranded Debt (Php/kwh)</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->NPCStrandedDebt }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->NPCStrandedDebt) ? number_format($item->NPCStrandedDebt, 2) : $item->NPCStrandedDebt }}</td>
                    </tr>
                    <tr class="border-bottom-only">
                        <td class="border-bottom-only text-left left-indent">Missionary Electrification - REDCI (Php/kwh)</td>
                        <td class="border-bottom-only text-right">{{ $rate->MissionaryElectrificationREDCI }}</td>
                        <td class="border-bottom-only text-right">{{ is_numeric($item->MissionaryElectrificationREDCI) ? number_format($item->MissionaryElectrificationREDCI, 2) : $item->MissionaryElectrificationREDCI }}</td>
                    </tr>

                    {{-- ADJUSTMENTS & OTHER CHARGES --}}
                    <tr class="no-border-top-bottom">
                        <th class="no-border-top-bottom text-left">VI. ADJUSTMENTS & OTHER CHARGES</th>
                        <th class="no-border-top-bottom"></th>
                        <th class="no-border-top-bottom"></th>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">PPA Refund</td>
                        <td class="no-border-top-bottom text-right">{{ $rate->PPARefund }}</td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->PPARefund) ? number_format($item->PPARefund, 2) : $item->PPARefund }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">2% EWT</td>
                        <td class="no-border-top-bottom text-right"></td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->Evat2Percent) ? number_format($item->Evat2Percent, 2) : $item->Evat2Percent }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">5% CWVAT</td>
                        <td class="no-border-top-bottom text-right"></td>
                        <td class="no-border-top-bottom text-right">{{ $item->Evat5Percent }}</td>
                    </tr>
                    <tr class="border-bottom-only">
                        <td class="border-bottom-only text-left left-indent">Senior Citizen Discount</td>
                        <td class="border-bottom-only text-right"></td>
                        <td class="border-bottom-only text-right">{{ intval($item->SeniorCitizenSubsidy) < 0 ? (is_numeric($item->SeniorCitizenSubsidy) ? number_format($item->SeniorCitizenSubsidy, 2) : $item->SeniorCitizenSubsidy) : '' }}</td>
                    </tr>

                    {{-- TOTAL --}}
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Deductions/Prepayments</td>
                        <td class="no-border-top-bottom text-right"></td>
                        <td class="no-border-top-bottom text-right">-{{ number_format(floatval($item->Deductions) + floatval($item->DeductedDeposit), 2) }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">OCL/Termed Payments</td>
                        <td class="no-border-top-bottom text-right"></td>
                        <td class="no-border-top-bottom text-right">{{ is_numeric($item->AdditionalCharges) ? number_format($item->AdditionalCharges, 2) : $item->AdditionalCharges }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent">Katas Ng VAT</td>
                        <td class="no-border-top-bottom text-right"></td>
                        <td class="no-border-top-bottom text-right">-{{ is_numeric($item->KatasNgVat) ? number_format($item->KatasNgVat, 2) : $item->KatasNgVat }}</td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <th class="no-border-top-bottom text-left left-indent">CURRENT AMOUNT DUE on/before</th>
                        <td class="no-border-top-bottom text-right"></td>
                        <th class="no-border-top-bottom text-right">{{ is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : $item->NetAmount }}</th>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom text-left left-indent-more">Due Date: <strong>{{ date('M d, Y', strtotime($item->DueDate)) }}</strong></td>
                        <td class="no-border-top-bottom text-right"></td>
                        <td class="no-border-top-bottom text-right"></td>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <th class="no-border-top-bottom text-left left-indent">Penalty Charges After</th>
                        <td class="no-border-top-bottom text-right"></td>
                        <th class="no-border-top-bottom text-right">{{  number_format(Bills::assessDueBillAndGetSurcharge($item), 2) }}</th>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <th class="no-border-top-bottom text-left left-indent">TOTAL GROSS AMOUNT</th>
                        <td class="no-border-top-bottom text-right"></td>
                        <th class="no-border-top-bottom text-right">{{ number_format(floatval($item->NetAmount) + floatval($item->Evat5Percent) + floatval($item->Evat2Percent), 2) }}</th>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <th class="no-border-top-bottom text-left left-indent">TOTAL AMOUNT DUE After</th>
                        <td class="no-border-top-bottom text-right"></td>
                        <th class="no-border-top-bottom text-right">{{ number_format(floatval(Bills::assessDueBillAndGetSurcharge($item)) + floatval($item->NetAmount), 2)  }}</th>
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
                        <th class="no-border-top-bottom text-left">{{ $item->ServiceAccountName }}</th>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom">ACCOUNT ADDRESS</td>
                        <th class="no-border-top-bottom text-left">{{ ServiceAccounts::getAddress($item) }}</th>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom">METER NO</td>
                        <th class="no-border-top-bottom text-left">{{ $item->MeterNumber }}</th>
                    </tr>
                </table>
            </div>

            <div class="half" style="float-right; margin-left: 5px;">
                <table class="bordered" style="width: 100%;">
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom">ACCOUNT NO</td>
                        <th class="no-border-top-bottom text-left">{{ $item->OldAccountNo }}</th>
                        <td class="no-border-top-bottom">AMOUNT DUE</td>
                        <th class="no-border-top-bottom text-right">{{ number_format(floatval(Bills::assessDueBillAndGetSurcharge($item)) + floatval($item->NetAmount), 2) }}</th>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom">BILLING MO</td>
                        <th class="no-border-top-bottom text-left">{{ date('F Y', strtotime($item->ServicePeriod)) }}</th>
                        <td class="no-border-top-bottom">ARREARS</td>
                        <th class="no-border-top-bottom text-right">{{ $item->ArrearsCount }}</th>
                    </tr>
                    <tr class="no-border-top-bottom">
                        <td class="no-border-top-bottom">DATE BILLED</td>
                        <th class="no-border-top-bottom text-left">{{ date('M d, Y', strtotime($item->BillingDate)) }}</th>
                        <td class="no-border-top-bottom">DUE DATE</td>
                        <th class="no-border-top-bottom text-right">{{ date('M d, Y', strtotime($item->DueDate)) }}</th>
                    </tr>
                </table>
            </div>
            
            <div style="width: 100%; text-align: center;">
                <svg id="barcode" jsbarcode-format="code39" jsbarcode-value="{{ $item->OldAccountNo }}" jsbarcode-height="25" jsbarcode-width="1" jsbarcode-displayValue=false></svg>
                <script type="text/javascript">
                    JsBarcode("#barcode").init()
                </script>
            </div>    
        </div>
        
    </div>
@endforeach

<script type="text/javascript">
window.print();

window.setTimeout(function(){
    window.history.go(-1)
}, 1000);
</script>