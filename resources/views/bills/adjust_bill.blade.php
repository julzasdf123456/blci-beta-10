@php
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>
                    Adjust {{ $account->ServiceAccountName }}'s Bill (Account No: <strong><a href="{{ route('serviceAccounts.show', [$account->id]) }}">{{ $account->OldAccountNo }}</a></strong>)
                    @if ($bill->IsUnlockedForPayment == 'CLOSED')
                        <span class="badge bg-danger">This bill is closed</span>
                    @endif
                </h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-8 offset-lg-2 col-md-12">
        <div class="card">
            {!! Form::model($bill, ['route' => ['bills.update', $bill->id], 'method' => 'patch']) !!}
            <div class="card-header">
                <span class="card-title">Bill Number : <strong>{{ $bill->BillNumber }}</strong> | Rate: <strong>{{ number_format($bill->EffectiveRate, 4) }}</strong> | Billing Month: <strong>{{ date('F Y', strtotime($bill->ServicePeriod)) }}</strong></span>
                
                <div class="card-tools">
                    <button type="submit" class="btn btn-primary">Save and Proceed</button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="AdjustmentNumber" id="AdjustmentNumber" value="{{ IDGenerator::generateID() }}" class="form-control">

                    <div class="form-group col-lg-3">
                        <label for="Multiplier">Multiplier</label>
                        <input type="text" name="Multiplier" id="Multiplier" value="{{ $account->Multiplier }}" class="form-control" readonly>
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="AdjustmentType">Adjustment Type</label>
                        <select name="AdjustmentType" id="AdjustmentType" class="form-control">
                            @if ($bill->IsUnlockedForPayment != 'CLOSED')
                                <option value="Direct Adjustment">Direct Adjustment</option>
                            @else
                                <option value="DM/CM">DM/CM</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="DueDate">Due Date</label>
                        <input type="text" name="DueDate" id="DueDate" value="{{ $bill->DueDate }}" class="form-control text-right">
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#DueDate').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    <div class="form-group col-lg-3">
                        <label for="Notes">Remarks/Comments</label>
                        <input type="text" name="Notes" id="Notes" value="{{ $bill->Notes }}" class="form-control text-right">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="PreviousKwh">Previous Reading</label>
                        <input type="number" step="any" name="PreviousKwh" id="PreviousKwh" value="{{ $bill->PreviousKwh }}" class="form-control text-right">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="PresentKwh">Present Reading</label>
                        <input type="number" step="any" name="PresentKwh" id="PresentKwh" value="{{ $bill->PresentKwh }}" class="form-control text-right">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="KwhUsed">Kwh Used</label>
                        <input type="number" step="any" name="KwhUsed" id="KwhUsed" value="{{ $bill->KwhUsed }}" class="form-control text-right">
                        {{-- <input type="number" step="any" name="KwhUsedProxy" id="KwhUsedProxy" value="{{ floatval($bill->KwhUsed) * floatval($bill->Multiplier) }}" class="form-control text-right"> --}}
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="DemandPresentKwh">Demand Kwh</label>
                        <input type="number" step="any" name="DemandPresentKwh" id="DemandPresentKwh" value="{{ $bill->DemandPresentKwh }}" class="form-control text-right">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="AdditionalCharges">Termed Payment Attached</label>
                        <input type="number" step="any" name="AdditionalCharges" id="AdditionalCharges" value="{{ $ocl != null ? $ocl->Amount : '0' }}" class="form-control text-right" readonly>
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="Deductions">Deductions</label>
                        <input type="number" step="any" name="Deductions" id="Deductions" value="{{ $bill->Deductions }}" class="form-control text-right">
                    </div>

                    {{-- <div class="col-lg-3">
                        {!! Form::label('Form 2307:') !!}
                        <div class="input-group">
                            <input type="hidden" value="" name="Form2307">
                            <input type="checkbox" value="{{ $bill->Form2307Amount }}" name="Form2307" id="Form2307" class="custom-checkbox" {{ $bill->Form2307Amount != null ? 'checked' : '' }}>
                        </div>
                    </div> --}}

                    {{-- <div class="form-group col-lg-3">
                        <label for="Form2307Amount">Form 2307 Amount</label>
                        <input type="number" step="any" name="Form2307Amount" id="Form2307Amount" value="{{ $bill->Form2307Amount }}" class="form-control text-right" readonly>
                    </div> --}}

                    <div class="form-group col-lg-3">
                        <label for="ServiceDateFrom">Date From</label>
                        <input type="text" name="ServiceDateFrom" id="ServiceDateFrom" value="{{ $bill->ServiceDateFrom }}" class="form-control text-right">
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#ServiceDateFrom').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    <div class="form-group col-lg-3">
                        <label for="ServiceDateTo">Date To</label>
                        <input type="text" name="ServiceDateTo" id="ServiceDateTo" value="{{ $bill->ServiceDateTo }}" class="form-control text-right">
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#ServiceDateTo').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush


                </div>

                <div class="divider"></div>

                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Net Amount</th>
                        <td></td>
                        <td></td>
                        <td>
                            <input type="text" name="NetAmount" value="{{ $bill->NetAmount }}" id="NetAmount" class="form-control text-right" readonly="true" step="any" style="font-size: 1.6em; font-weight: bold; color: blue;">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="GenerationSystemCharge">Generation System Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->GenerationSystemCharge }}"  name="GenerationSystemCharge" id="GenerationSystemCharge" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="MissionaryElectrificationCharge">Missionary Electrification Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->MissionaryElectrificationCharge }}"  name="MissionaryElectrificationCharge" id="MissionaryElectrificationCharge" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="TransmissionDeliveryChargeKW">Transmission Delivery Charge (KW)</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->TransmissionDeliveryChargeKW }}"  name="TransmissionDeliveryChargeKW" id="TransmissionDeliveryChargeKW" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="EnvironmentalCharge">Environmental Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->EnvironmentalCharge }}"  name="EnvironmentalCharge" id="EnvironmentalCharge" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="TransmissionDeliveryChargeKWH">Transmission Delivery Charge (KWH)</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->TransmissionDeliveryChargeKWH }}"  name="TransmissionDeliveryChargeKWH" id="TransmissionDeliveryChargeKWH" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="StrandedContractCosts">Stranded Contract Costs</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->StrandedContractCosts }}"  name="StrandedContractCosts" id="StrandedContractCosts" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="SystemLossCharge">System Loss Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->SystemLossCharge }}"  name="SystemLossCharge" id="SystemLossCharge" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="NPCStrandedDebt">NPC Stranded Debt</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->NPCStrandedDebt }}"  name="NPCStrandedDebt" id="NPCStrandedDebt" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="OtherGenerationRateAdjustment">Other Generation Rate Adj.</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->OtherGenerationRateAdjustment }}"  name="OtherGenerationRateAdjustment" id="OtherGenerationRateAdjustment" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="FeedInTariffAllowance">Feed-In Tariff All.</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->FeedInTariffAllowance }}"  name="FeedInTariffAllowance" id="FeedInTariffAllowance" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="OtherTransmissionCostAdjustmentKW">Other Transmission Cost Adj. (KW)</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->OtherTransmissionCostAdjustmentKW }}"  name="OtherTransmissionCostAdjustmentKW" id="OtherTransmissionCostAdjustmentKW" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="MissionaryElectrificationREDCI">Missionary Electrification - REDCI</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->MissionaryElectrificationREDCI }}"  name="MissionaryElectrificationREDCI" id="MissionaryElectrificationREDCI" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="OtherTransmissionCostAdjustmentKWH">Other Transmission Cost Adj. (KWH)</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->OtherTransmissionCostAdjustmentKWH }}"  name="OtherTransmissionCostAdjustmentKWH" id="OtherTransmissionCostAdjustmentKWH" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="GenerationVAT">VAT: Generation</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->GenerationVAT }}"  name="GenerationVAT" id="GenerationVAT" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="OtherSystemLossCostAdjustment">Other System Loss Cost Adj.</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->OtherSystemLossCostAdjustment }}"  name="OtherSystemLossCostAdjustment" id="OtherSystemLossCostAdjustment" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="TransmissionVAT">VAT: Transmission</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->TransmissionVAT }}"  name="TransmissionVAT" id="TransmissionVAT" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="DistributionDemandCharge">Distribution Demand Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->DistributionDemandCharge }}"  name="DistributionDemandCharge" id="DistributionDemandCharge" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="SystemLossVAT">VAT: System Loss</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->SystemLossVAT }}"  name="SystemLossVAT" id="SystemLossVAT" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="DistributionSystemCharge">Distribution System Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->DistributionSystemCharge }}"  name="DistributionSystemCharge" id="DistributionSystemCharge" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="DistributionVAT">VAT: Distribution & Others</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->DistributionVAT }}"  name="DistributionVAT" id="DistributionVAT" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="SupplyRetailCustomerCharge">Supply Retail Customer Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->SupplyRetailCustomerCharge }}"  name="SupplyRetailCustomerCharge" id="SupplyRetailCustomerCharge" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="FranchiseTax">Franchise Tax</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->FranchiseTax }}"  name="FranchiseTax" id="FranchiseTax" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="SupplySystemCharge">Supply System Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->SupplySystemCharge }}"  name="SupplySystemCharge" id="SupplySystemCharge" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="BusinessTax">Business Tax</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->BusinessTax }}"  name="BusinessTax" id="BusinessTax" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="MeteringRetailCustomerCharge">Metering Retail Customer Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->MeteringRetailCustomerCharge }}"  name="MeteringRetailCustomerCharge" id="MeteringRetailCustomerCharge" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="RealPropertyTax">Real Property Tax (RPT)</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->RealPropertyTax }}"  name="RealPropertyTax" id="RealPropertyTax" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="MeteringSystemCharge">Metering System Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->MeteringSystemCharge }}"  name="MeteringSystemCharge" id="MeteringSystemCharge" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="RFSC">RFSC</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->RFSC }}"  name="RFSC" id="RFSC" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="LifelineRate">Lifeline Rate (Discount/Subsidy)</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->LifelineRate }}"  name="LifelineRate" id="LifelineRate" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="InterClassCrossSubsidyCharge">Inter-Class Cross Subsidy Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->InterClassCrossSubsidyCharge }}"  name="InterClassCrossSubsidyCharge" id="InterClassCrossSubsidyCharge" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="PPARefund">PPA (Refund)</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->PPARefund }}"  name="PPARefund" id="PPARefund" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="SeniorCitizenSubsidy">Senior Citizen Subsidy</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->SeniorCitizenSubsidy }}"  name="SeniorCitizenSubsidy" id="SeniorCitizenSubsidy" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="OtherLifelineRateCostAdjustment">Other Lifeline Rate Cost Adj.</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->OtherLifelineRateCostAdjustment }}"  name="OtherLifelineRateCostAdjustment" id="OtherLifelineRateCostAdjustment" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="SeniorCitizenDiscountAndSubsidyAdjustment">Sen. Citizen Discount & Subsidy Adj.</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->SeniorCitizenDiscountAndSubsidyAdjustment }}"  name="SeniorCitizenDiscountAndSubsidyAdjustment" id="SeniorCitizenDiscountAndSubsidyAdjustment" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="Evat2Percent">EWT 2%</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->Evat2Percent }}"  name="Evat2Percent" id="Evat2Percent" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="Evat5Percent">EVAT 5%</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->Evat5Percent }}"  name="Evat5Percent" id="Evat5Percent" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="DeductedDeposit">Pre-payment Deduction</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->DeductedDeposit }}"  name="DeductedDeposit" id="DeductedDeposit" class="form-control text-right" readonly="true">
                        </td>
                        <td>
                            <label for="ExcessDeposit">Excess Pre-payment</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->ExcessDeposit }}"  name="ExcessDeposit" id="ExcessDeposit" class="form-control text-right" readonly="true">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right">Save and Proceed</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            var is2307Checked = false

            if($('#Form2307').prop('checked')) {
                is2307Checked = true                    
            } else {
                is2307Checked = false
            }

            // $('#KwhUsedProxy').keyup(function() {
            //     $('#KwhUsed').val(this.value).change()
            // })

            $('#KwhUsed').keyup(function() {
                adjustBill(this.value, $('#AdditionalCharges').val(), $('#Deductions').val(), is2307Checked)
            })

            $('#KwhUsed').on('change', function() {
                adjustBill(this.value, $('#AdditionalCharges').val(), $('#Deductions').val(), is2307Checked)
            })

            $('#DemandPresentKwh').keyup(function() {
                adjustBill($('#KwhUsed').val(), $('#AdditionalCharges').val(), $('#Deductions').val(), is2307Checked)
            })

            $('#AdditionalCharges').keyup(function() {
                adjustBill($('#KwhUsed').val(), this.value, $('#Deductions').val(), is2307Checked)
            })

            $('#AdditionalCharges').on('change', function() {
                adjustBill($('#KwhUsed').val(), this.value, $('#Deductions').val(), is2307Checked)
            })

            $('#Deductions').keyup(function() {
                adjustBill($('#KwhUsed').val(), $('#AdditionalCharges').val(), this.value, is2307Checked)
            })

            $('#Deductions').on('change', function() {
                adjustBill($('#KwhUsed').val(), $('#AdditionalCharges').val(), this.value, is2307Checked)
            })

            $('#Form2307').change(function() {
                if($('#Form2307').prop('checked')) {
                    is2307Checked = true                    
                } else {
                    is2307Checked = false
                }
                adjustBill($('#KwhUsed').val(), $('#AdditionalCharges').val(), $('#Deductions').val(), is2307Checked)
            })

            $('#PresentKwh').keyup(function() {
                computeKwhUsed()
            })

            $('#PreviousKwh').keyup(function() {
                computeKwhUsed()
            })
        })

        function computeKwhUsed() {
            var pres = parseFloat($('#PresentKwh').val())
            var prev = parseFloat($('#PreviousKwh').val())
            var dif = pres - prev

            var kwhFinal = parseFloat($('#Multiplier').val()) * dif

            // $('#KwhUsedProxy').val(parseFloat(kwhFinal).toFixed(2)).change()   
            $('#KwhUsed').val(parseFloat(kwhFinal).toFixed(2)).change()          
        }

        function adjustBill(kwh, additionalCharges, deductions, is2307) {
            $.ajax({
                    url : '{{ route("bills.fetch-bill-adjustment-data") }}',
                    type : 'GET',
                    data : {
                        BillId : "{{ $bill->id }}",
                        AccountNumber : "{{ $bill->AccountNumber }}",
                        AdditionalCharges : additionalCharges,
                        Deductions : deductions,
                        Is2307 : is2307,
                        KwhUsed : kwh,
                        Demand : $('#DemandPresentKwh').val()
                    },
                    success : function(res) {
                        $('#NetAmount').val(res['NetAmount'])
                        $('#GenerationSystemCharge').val(res['GenerationSystemCharge'])
                        $('#MissionaryElectrificationCharge').val(res['MissionaryElectrificationCharge'])
                        $('#TransmissionDeliveryChargeKW').val(res['TransmissionDeliveryChargeKW'])
                        $('#EnvironmentalCharge').val(res['EnvironmentalCharge'])
                        $('#TransmissionDeliveryChargeKWH').val(res['TransmissionDeliveryChargeKWH'])
                        $('#StrandedContractCosts').val(res['StrandedContractCosts'])
                        $('#SystemLossCharge').val(res['SystemLossCharge'])
                        $('#NPCStrandedDebt').val(res['NPCStrandedDebt'])
                        $('#OtherGenerationRateAdjustment').val(res['OtherGenerationRateAdjustment'])
                        $('#FeedInTariffAllowance').val(res['FeedInTariffAllowance'])
                        $('#OtherTransmissionCostAdjustmentKW').val(res['OtherTransmissionCostAdjustmentKW'])
                        $('#MissionaryElectrificationREDCI').val(res['MissionaryElectrificationREDCI'])
                        $('#OtherTransmissionCostAdjustmentKWH').val(res['OtherTransmissionCostAdjustmentKWH'])
                        $('#GenerationVAT').val(res['GenerationVAT'])
                        $('#OtherSystemLossCostAdjustment').val(res['OtherSystemLossCostAdjustment'])
                        $('#TransmissionVAT').val(res['TransmissionVAT'])
                        $('#DistributionDemandCharge').val(res['DistributionDemandCharge'])
                        $('#SystemLossVAT').val(res['SystemLossVAT'])
                        $('#DistributionSystemCharge').val(res['DistributionSystemCharge'])
                        $('#DistributionVAT').val(res['DistributionVAT'])
                        $('#SupplyRetailCustomerCharge').val(res['SupplyRetailCustomerCharge'])
                        $('#FranchiseTax').val(res['FranchiseTax'])
                        $('#SupplySystemCharge').val(res['SupplySystemCharge'])
                        $('#BusinessTax').val(res['BusinessTax'])
                        $('#MeteringRetailCustomerCharge').val(res['MeteringRetailCustomerCharge'])
                        $('#RealPropertyTax').val(res['RealPropertyTax'])
                        $('#MeteringSystemCharge').val(res['MeteringSystemCharge'])
                        $('#RFSC').val(res['RFSC'])
                        $('#LifelineRate').val(res['LifelineRate'])
                        $('#InterClassCrossSubsidyCharge').val(res['InterClassCrossSubsidyCharge'])
                        $('#PPARefund').val(res['PPARefund'])
                        $('#SeniorCitizenSubsidy').val(res['SeniorCitizenSubsidy'])
                        $('#OtherLifelineRateCostAdjustment').val(res['OtherLifelineRateCostAdjustment'])
                        $('#SeniorCitizenDiscountAndSubsidyAdjustment').val(res['SeniorCitizenDiscountAndSubsidyAdjustment'])
                        $('#Form2307Amount').val(res['Form2307Amount'])                        
                        $('#Evat2Percent').val(res['Evat2Percent'])
                        $('#Evat5Percent').val(res['Evat5Percent'])
                        $('#ServiceDateFrom').val(res['ServiceDateFrom'])
                        $('#ServiceDateTo').val(res['ServiceDateTo'])                  
                        $('#DeductedDeposit').val(res['DeductedDeposit'])
                        $('#ExcessDeposit').val(res['ExcessDeposit'])
                    },
                    error : function(error) {
                        Swal.fire({
                            title : 'Oops...',
                            text : 'An error occurred while adjusting the bill. Contact suppport immediately!',
                            icon : 'error'
                        })
                    }
                })
        }
    </script>
@endpush