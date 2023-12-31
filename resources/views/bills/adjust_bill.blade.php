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
                    <input type="hidden" name="AdjustmentNumber" id="AdjustmentNumber" value="{{ IDGenerator::generateID() }}" class="form-control form-control-sm">

                    <div class="form-group col-lg-3">
                        <label for="Multiplier">Multiplier</label>
                        <input type="text" name="Multiplier" id="Multiplier" value="{{ $account->Multiplier }}" class="form-control form-control-sm" readonly>
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="AdjustmentType">Adjustment Type</label>
                        <select name="AdjustmentType" id="AdjustmentType" class="form-control form-control-sm">
                            @if ($bill->IsUnlockedForPayment != 'CLOSED')
                                <option value="Direct Adjustment">Direct Adjustment</option>
                            @else
                                <option value="DM/CM">DM/CM</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="DueDate">Due Date</label>
                        <input type="text" name="DueDate" id="DueDate" value="{{ $bill->DueDate }}" class="form-control form-control-sm text-right">
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
                        <label for="Notes">Reason</label>
                        <input type="text" name="Notes" id="Notes" value="{{ $bill->Notes }}" class="form-control form-control-sm">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="PreviousKwh">Previous Reading</label>
                        <input type="number" step="any" name="PreviousKwh" id="PreviousKwh" value="{{ $bill->PreviousKwh }}" class="form-control form-control-sm text-right">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="PresentKwh">Present Reading</label>
                        <input type="number" step="any" name="PresentKwh" id="PresentKwh" value="{{ $bill->PresentKwh }}" class="form-control form-control-sm text-right">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="KwhUsed">Kwh Used</label>
                        <input type="number" step="any" name="KwhUsed" id="KwhUsed" value="{{ $bill->KwhUsed }}" class="form-control form-control-sm text-right">
                        {{-- <input type="number" step="any" name="KwhUsedProxy" id="KwhUsedProxy" value="{{ floatval($bill->KwhUsed) * floatval($bill->Multiplier) }}" class="form-control form-control-sm text-right"> --}}
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="DemandPresentKwh">Demand Kwh</label>
                        <input type="number" step="any" name="DemandPresentKwh" id="DemandPresentKwh" value="{{ $bill->DemandPresentKwh }}" class="form-control form-control-sm text-right">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="TermedPayments">Termed Payment Attached</label>
                        <input type="number" step="any" name="TermedPayments" id="TermedPayments" value="{{ $bill->TermedPayments }}" class="form-control form-control-sm text-right" readonly>
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="Deductions">Deductions</label>
                        <input type="number" step="any" name="Deductions" id="Deductions" value="{{ $bill->Deductions }}" class="form-control form-control-sm text-right">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="ServiceDateFrom">Date From</label>
                        <input type="text" name="ServiceDateFrom" id="ServiceDateFrom" value="{{ $bill->ServiceDateFrom }}" class="form-control form-control-sm text-right">
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
                        <input type="text" name="ServiceDateTo" id="ServiceDateTo" value="{{ $bill->ServiceDateTo }}" class="form-control form-control-sm text-right">
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
                            <input type="text" name="NetAmount" value="{{ $bill->NetAmount }}" id="NetAmount" class="form-control form-control-sm text-right" readonly="true" step="any" style="font-size: 1.6em; font-weight: bold; color: blue;">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="GenerationSystemCharge">Generation System Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->GenerationSystemCharge }}"  name="GenerationSystemCharge" id="GenerationSystemCharge" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="InterClassCrossSubsidyCharge">Inter-Class Cross Sub. Chg.</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->InterClassCrossSubsidyCharge }}"  name="InterClassCrossSubsidyCharge" id="InterClassCrossSubsidyCharge" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="ACRM">GRAM/ICERA/ACRM</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->ACRM }}"  name="ACRM" id="ACRM" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="MissionaryElectrificationREDCI">Missionary Elec.-RED</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->MissionaryElectrificationREDCI }}"  name="MissionaryElectrificationREDCI" id="MissionaryElectrificationREDCI" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="TransmissionDeliveryChargeKWH">Transmission Delivery Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->TransmissionDeliveryChargeKWH }}"  name="TransmissionDeliveryChargeKWH" id="TransmissionDeliveryChargeKWH" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="MissionaryElectrificationSPUG">Missionary Elec.-SPUG</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->MissionaryElectrificationSPUG }}"  name="MissionaryElectrificationSPUG" id="MissionaryElectrificationSPUG" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="SystemLossCharge">System Loss Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->SystemLossCharge }}"  name="SystemLossCharge" id="SystemLossCharge" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="MissionaryElectrificationSPUGTRUEUP">Missionary Elec.-SPUGTRUEUP</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->MissionaryElectrificationSPUGTRUEUP }}"  name="MissionaryElectrificationSPUGTRUEUP" id="MissionaryElectrificationSPUGTRUEUP" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="DistributionSystemCharge">Distribution System Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->DistributionSystemCharge }}"  name="DistributionSystemCharge" id="DistributionSystemCharge" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="NPCStrandedDebt">NPC Stranded Debt</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->NPCStrandedDebt }}"  name="NPCStrandedDebt" id="NPCStrandedDebt" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="DistributionDemandCharge">Distribution Demand Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->DistributionDemandCharge }}"  name="DistributionDemandCharge" id="DistributionDemandCharge" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="GenerationVAT">VAT: Generation</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->GenerationVAT }}"  name="GenerationVAT" id="GenerationVAT" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="SupplyRetailCustomerCharge">Supply Retail Customer Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->SupplyRetailCustomerCharge }}"  name="SupplyRetailCustomerCharge" id="SupplyRetailCustomerCharge" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="TransmissionVAT">VAT: Transmission</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->TransmissionVAT }}"  name="TransmissionVAT" id="TransmissionVAT" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="MeteringRetailCustomerCharge">Metering Retail Customer Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->MeteringRetailCustomerCharge }}"  name="MeteringRetailCustomerCharge" id="MeteringRetailCustomerCharge" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="SystemLossVAT">VAT: System Loss</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->SystemLossVAT }}"  name="SystemLossVAT" id="SystemLossVAT" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="MeteringSystemCharge">Metering System Charge</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->MeteringSystemCharge }}"  name="MeteringSystemCharge" id="MeteringSystemCharge" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="ACRMVAT">VAT: GRAM/ICERA/ACRM</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->ACRMVAT }}"  name="ACRMVAT" id="ACRMVAT" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="PowerActReduction">Power Act Reduction</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->PowerActReduction }}"  name="PowerActReduction" id="PowerActReduction" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="DistributionVAT">VAT: Distribution</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->DistributionVAT }}"  name="DistributionVAT" id="DistributionVAT" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="LifelineRate">Lifeline Rate (Discount/Subsidy)</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->LifelineRate }}"  name="LifelineRate" id="LifelineRate" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="OthersVAT">VAT: Others</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->OthersVAT }}"  name="OthersVAT" id="OthersVAT" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="SeniorCitizenSubsidy">Senior Citizen Subsidy</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->SeniorCitizenSubsidy }}"  name="SeniorCitizenSubsidy" id="SeniorCitizenSubsidy" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="FranchiseTax">Franchise Tax-Bill Amnt.</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->FranchiseTax }}"  name="FranchiseTax" id="FranchiseTax" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="AdditionalCharges">Additional Charges</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->AdditionalCharges }}"  name="AdditionalCharges" id="AdditionalCharges" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="FranchiseTaxOthers">Franchise Tax-Others</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->FranchiseTaxOthers }}"  name="FranchiseTaxOthers" id="FranchiseTaxOthers" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="Evat2Percent">EWT 2%</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->Evat2Percent }}"  name="Evat2Percent" id="Evat2Percent" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="Evat5Percent">EVAT 5%</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->Evat5Percent }}"  name="Evat5Percent" id="Evat5Percent" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="DeductedDeposit">Pre-payment Deduction</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->DeductedDeposit }}"  name="DeductedDeposit" id="DeductedDeposit" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="ExcessDeposit">Excess Pre-payment</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->ExcessDeposit }}"  name="ExcessDeposit" id="ExcessDeposit" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="AdvancedMaterialDeposit">Adv. Mat. Deposit</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->AdvancedMaterialDeposit }}"  name="AdvancedMaterialDeposit" id="AdvancedMaterialDeposit" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="CustomerDeposit">Customer Deposit</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->CustomerDeposit }}"  name="CustomerDeposit" id="CustomerDeposit" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="Balance">Balance</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->Balance }}"  name="Balance" id="Balance" class="form-control form-control-sm text-right" readonly="true">
                        </td>
                        <td>
                            <label for="PaidAmount">Paid Partial Amount</label>
                        </td>
                        <td>
                            <input type="number" step="any" value="{{ $bill->PaidAmount }}"  name="PaidAmount" id="PaidAmount" class="form-control form-control-sm text-right" readonly="true">
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
                        $('#NetAmount').val(parseFloat(res['NetAmount']).toFixed(2))
                        $('#GenerationSystemCharge').val(parseFloat(res['GenerationSystemCharge']).toFixed(2))
                        $('#ACRM').val(parseFloat(res['ACRM']).toFixed(2))
                        $('#TransmissionDeliveryChargeKWH').val(parseFloat(res['TransmissionDeliveryChargeKWH']).toFixed(2))
                        $('#StrandedContractCosts').val(parseFloat(res['StrandedContractCosts']).toFixed(2))
                        $('#SystemLossCharge').val(parseFloat(res['SystemLossCharge']).toFixed(2))
                        $('#NPCStrandedDebt').val(parseFloat(res['NPCStrandedDebt']).toFixed(2))
                        $('#MissionaryElectrificationREDCI').val(parseFloat(res['MissionaryElectrificationREDCI']).toFixed(2))
                        $('#MissionaryElectrificationSPUG').val(parseFloat(res['MissionaryElectrificationSPUG']).toFixed(2))
                        $('#MissionaryElectrificationSPUGTRUEUP').val(parseFloat(res['MissionaryElectrificationSPUGTRUEUP']).toFixed(2))
                        $('#GenerationVAT').val(parseFloat(res['GenerationVAT']).toFixed(2))
                        $('#TransmissionVAT').val(parseFloat(res['TransmissionVAT']).toFixed(2))
                        $('#DistributionDemandCharge').val(parseFloat(res['DistributionDemandCharge']).toFixed(2))
                        $('#SystemLossVAT').val(parseFloat(res['SystemLossVAT']).toFixed(2))
                        $('#OthersVAT').val(parseFloat(res['OthersVAT']).toFixed(2))
                        $('#DistributionSystemCharge').val(parseFloat(res['DistributionSystemCharge']).toFixed(2))
                        $('#DistributionVAT').val(parseFloat(res['DistributionVAT']).toFixed(2))
                        $('#ACRMVAT').val(parseFloat(res['ACRMVAT']).toFixed(2))
                        $('#SupplyRetailCustomerCharge').val(parseFloat(res['SupplyRetailCustomerCharge']).toFixed(2))
                        $('#FranchiseTax').val(parseFloat(res['FranchiseTax']).toFixed(2))
                        $('#FranchiseTaxOthers').val(parseFloat(res['FranchiseTaxOthers']).toFixed(2))
                        $('#PowerActReduction').val(parseFloat(res['PowerActReduction']).toFixed(2))
                        $('#MeteringRetailCustomerCharge').val(parseFloat(res['MeteringRetailCustomerCharge']).toFixed(2))
                        $('#MeteringSystemCharge').val(parseFloat(res['MeteringSystemCharge']).toFixed(2))
                        $('#LifelineRate').val(parseFloat(res['LifelineRate']).toFixed(2))
                        $('#InterClassCrossSubsidyCharge').val(parseFloat(res['InterClassCrossSubsidyCharge']).toFixed(2))
                        $('#SeniorCitizenSubsidy').val(parseFloat(res['SeniorCitizenSubsidy']).toFixed(2))
                        $('#Form2307Amount').val(parseFloat(res['Form2307Amount']).toFixed(2))                     
                        $('#Evat2Percent').val(parseFloat(res['Evat2Percent']).toFixed(2))
                        $('#Evat5Percent').val(parseFloat(res['Evat5Percent']).toFixed(2))
                        $('#DeductedDeposit').val(parseFloat(res['DeductedDeposit']).toFixed(2))
                        $('#ExcessDeposit').val(parseFloat(res['ExcessDeposit']).toFixed(2))
                        $('#AdditionalCharges').val(parseFloat(res['AdditionalCharges']).toFixed(2))
                        $('#AdvancedMaterialDeposit').val(parseFloat(res['AdvancedMaterialDeposit']).toFixed(2))
                        $('#CustomerDeposit').val(parseFloat(res['CustomerDeposit']).toFixed(2))
                        $('#TermedPayments').val(parseFloat(res['TermedPayments']).toFixed(2))
                        $('#Balance').val(parseFloat(res['Balance']).toFixed(2))
                        $('#PaidAmount').val(parseFloat(res['PaidAmount']).toFixed(2))
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