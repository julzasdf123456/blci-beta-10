@php
    use App\Models\IDGenerator;
    use App\Models\ServiceAccounts;
    use App\Models\Bills;

    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <p style="font-size: 1.3em;"> Captured Reading: <strong><a href="{{ route('serviceAccounts.show', [$account->id]) }}">{{ $account->ServiceAccountName }}</a></strong> (Account No: <strong>{{ $account->OldAccountNo }}</strong>)</p>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- FORM --}}
    <div class="col-lg-8 col-md-12">
        <div class="card">
            {!! Form::open(['route' => 'readings.create-bill-for-captured-reading']) !!}
            <div class="card-header bg-warning">
                <span class="card-title">Perform Reading</span>
                <div class="card-tools">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="AccountNumber" value="{{ $account->id }}">
                    <input type="hidden" name="ReadingId" value="{{ $reading->id }}">
                    <input type="hidden" name="Day" value="{{ $day }}">
                    <input type="hidden" name="BapaName" value="{{ $bapaName }}">
                    {{-- FORM --}}
                    <div class="form-group col-lg-3">
                        <label for="ServicePeriod">Billing Month</label>
                        <select name="ServicePeriod" id="ServicePeriod" class="form-control form-control-sm">
                            @for ($i = 0; $i < count($months); $i++)
                                <option value="{{ $months[$i] }}" {{ $rate != null ? ($rate->ServicePeriod == $months[$i] ? 'selected' : '') : '' }}>{{ date('F Y', strtotime($months[$i])) }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="ReadingDate">Reading/Billing Date</label>
                        <input type="text" name="ReadingDate" id="ReadingDate" value="{{ date('Y-m-d', strtotime($reading->ReadingTimestamp)) }}"  class="form-control form-control-sm">
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#ReadingDate').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    <div class="form-group col-lg-3">
                        <label for="DateFrom">Service Date From</label>
                        <input type="text" name="DateFrom" id="DateFrom" value="{{ $prevReading != null ? date('Y-m-d', strtotime($prevReading->ReadingTimestamp)) : date('Y-m-d', strtotime($reading->ReadingTimestamp . ' -1 month')) }}"  class="form-control form-control-sm">
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#DateFrom').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    <div class="form-group col-lg-3">
                        <label for="DateTo">Service Date To</label>
                        <input type="text" name="DateTo" id="DateTo" value="{{ date('Y-m-d', strtotime($reading->ReadingTimestamp . ' -1 month')) }}"  class="form-control form-control-sm">
                    </div>

                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#DateTo').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush

                    <div class="form-group col-lg-3">
                        <label for="PreviousKwh">Previous Reading</label>
                        <input type="text" name="PreviousKwh" id="PreviousKwh" value="{{ $prevReading != null ? ($prevReading->KwhUsed != null ? $prevReading->KwhUsed : '0') : '0' }}"  class="form-control" {{ $prevReading != null ? 'readonly' : '' }} style="font-size: 1.6em;">
                    </div>
                    
                    <div class="form-group col-lg-3">
                        <label for="PresentKwh">Present Reading</label>
                        <input type="number" step="any" name="PresentKwh" id="PresentKwh" value="{{ $reading->KwhUsed }}"  class="form-control" autofocus style="font-size: 1.6em;">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="Demand">Demand Reading</label>
                        <input type="number" step="any" name="Demand" id="Demand"  class="form-control" style="font-size: 1.6em;">
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="DueDate">Due Date</label>
                        <input type="text" name="DueDate" id="DueDate"  class="form-control form-control-sm" value="{{ Bills::createDueDate(date('Y-m-d')) }}">

                        @push('page_scripts')
                            <script type="text/javascript">
                                $('#DueDate').datetimepicker({
                                    format: 'YYYY-MM-DD',
                                    useCurrent: true,
                                    sideBySide: true
                                })
                            </script>
                        @endpush
                    </div>

                    <div class="divider"></div>

                    {{-- GENERATED OUTPU --}}
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>Kwh Used</th>
                            <td>
                                <input type="text" step="any" name="KwhUsed" id="KwhUsed"  class="form-control form-control-sm" style="font-size: 1.6em;">
                                {{-- <input type="number" step="any" name="KwhUsedProxy" id="KwhUsedProxy"  class="form-control form-control-sm" style="font-size: 1.6em;"> --}}
                            </td>
                            <th>Net Amount</th>
                            <td>
                                <input type="text" name="NetAmount" id="NetAmount" class="form-control form-control-sm text-right" readonly="true" step="any" style="font-size: 1.6em; font-weight: bold; color: blue;">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="GenerationSystemCharge">Generation System Charge</label>
                            </td>
                            <td>
                                <input type="text" step="any"  name="GenerationSystemCharge" id="GenerationSystemCharge" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="MissionaryElectrificationCharge">Missionary Electrification Charge</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="MissionaryElectrificationCharge" id="MissionaryElectrificationCharge" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="TransmissionDeliveryChargeKW">Transmission Delivery Charge (KW)</label>
                            </td>
                            <td>
                                <input type="text" step="any"  name="TransmissionDeliveryChargeKW" id="TransmissionDeliveryChargeKW" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="EnvironmentalCharge">Environmental Charge</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="EnvironmentalCharge" id="EnvironmentalCharge" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="TransmissionDeliveryChargeKWH">Transmission Delivery Charge (KWH)</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="TransmissionDeliveryChargeKWH" id="TransmissionDeliveryChargeKWH" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="StrandedContractCosts">Stranded Contract Costs</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="StrandedContractCosts" id="StrandedContractCosts" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="SystemLossCharge">System Loss Charge</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="SystemLossCharge" id="SystemLossCharge" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="NPCStrandedDebt">NPC Stranded Debt</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="NPCStrandedDebt" id="NPCStrandedDebt" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="OtherGenerationRateAdjustment">Other Generation Rate Adj.</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="OtherGenerationRateAdjustment" id="OtherGenerationRateAdjustment" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="FeedInTariffAllowance">Feed-In Tariff All.</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="FeedInTariffAllowance" id="FeedInTariffAllowance" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="OtherTransmissionCostAdjustmentKW">Other Transmission Cost Adj. (KW)</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="OtherTransmissionCostAdjustmentKW" id="OtherTransmissionCostAdjustmentKW" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="MissionaryElectrificationREDCI">Missionary Electrification - REDCI</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="MissionaryElectrificationREDCI" id="MissionaryElectrificationREDCI" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="OtherTransmissionCostAdjustmentKWH">Other Transmission Cost Adj. (KWH)</label>
                            </td>
                            <td>
                                <input type="text" step="any"  name="OtherTransmissionCostAdjustmentKWH" id="OtherTransmissionCostAdjustmentKWH" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="GenerationVAT">VAT: Generation</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="GenerationVAT" id="GenerationVAT" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="OtherSystemLossCostAdjustment">Other System Loss Cost Adj.</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="OtherSystemLossCostAdjustment" id="OtherSystemLossCostAdjustment" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="TransmissionVAT">VAT: Transmission</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="TransmissionVAT" id="TransmissionVAT" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="DistributionDemandCharge">Distribution Demand Charge</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="DistributionDemandCharge" id="DistributionDemandCharge" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="SystemLossVAT">VAT: System Loss</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="SystemLossVAT" id="SystemLossVAT" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="DistributionSystemCharge">Distribution System Charge</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="DistributionSystemCharge" id="DistributionSystemCharge" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="DistributionVAT">VAT: Distribution & Others</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="DistributionVAT" id="DistributionVAT" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="SupplyRetailCustomerCharge">Supply Retail Customer Charge</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="SupplyRetailCustomerCharge" id="SupplyRetailCustomerCharge" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="FranchiseTax">Franchise Tax</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="FranchiseTax" id="FranchiseTax" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="SupplySystemCharge">Supply System Charge</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="SupplySystemCharge" id="SupplySystemCharge" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="BusinessTax">Business Tax</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="BusinessTax" id="BusinessTax" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="MeteringRetailCustomerCharge">Metering Retail Customer Charge</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="MeteringRetailCustomerCharge" id="MeteringRetailCustomerCharge" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="RealPropertyTax">Real Property Tax (RPT)</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="RealPropertyTax" id="RealPropertyTax" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="MeteringSystemCharge">Metering System Charge</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="MeteringSystemCharge" id="MeteringSystemCharge" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="RFSC">RFSC</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="RFSC" id="RFSC" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="LifelineRate">Lifeline Rate (Discount/Subsidy)</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="LifelineRate" id="LifelineRate" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="InterClassCrossSubsidyCharge">Inter-Class Cross Subsidy Charge</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="InterClassCrossSubsidyCharge" id="InterClassCrossSubsidyCharge" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="PPARefund">PPA (Refund)</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="PPARefund" id="PPARefund" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="SeniorCitizenSubsidy">Senior Citizen Subsidy</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="SeniorCitizenSubsidy" id="SeniorCitizenSubsidy" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="OtherLifelineRateCostAdjustment">Other Lifeline Rate Cost Adj.</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="OtherLifelineRateCostAdjustment" id="OtherLifelineRateCostAdjustment" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="SeniorCitizenDiscountAndSubsidyAdjustment">Sen. Citizen Discount & Subsidy Adj.</label>
                            </td>
                            <td>
                                <input type="text" step="any" name="SeniorCitizenDiscountAndSubsidyAdjustment" id="SeniorCitizenDiscountAndSubsidyAdjustment" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="Evat2Percent">EWT 2%</label>
                            </td>
                            <td>
                                <input type="text" step="any"  name="Evat2Percent" id="Evat2Percent" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                            <td>
                                <label for="Evat5Percent">EVAT 5%</label>
                            </td>
                            <td>
                                <input type="text" step="any"  name="Evat5Percent" id="Evat5Percent" class="form-control form-control-sm text-right" readonly="true">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary float-right']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    {{-- DETAILS --}}
    <div class="col-lg-4 col-md-12">
        {{-- DETALS --}}
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Account Details</span>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover">
                    <tr>
                        <td>Address</td>
                        <th>{{ ServiceAccounts::getAddress($account) }}</th>
                    </tr>
                    <tr>
                        <td>Account Number</td>
                        <th>{{ $account->OldAccountNo }}</th>
                    </tr>
                    <tr>
                        <td>Consumer Type</td>
                        <th>{{ $account->AccountType }}</th>
                    </tr>
                    <tr>
                        <td>Meter Number</td>
                        <th>{{ $meter != null ? $meter->SerialNumber : '-' }}</th>
                    </tr>
                    <tr>
                        <td>Multiplier</td>
                        <th id="multiplier">{{ $account->Multiplier }}</th>
                    </tr>
                </table>
            </div>
        </div>

        {{-- READING --}}
        <div class="card" style="height: 50vh;">
            <div class="card-header border-0">
                <span class="card-title">Previous Readings</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-sm table-hover">
                    <thead>
                        <th>Month</th>
                        <th class="text-right">Reading</th>
                        <th class="text-right">Kwh Used</th>
                        <th class="text-right">Amount</th>
                    </thead>
                    <tbody>
                        @foreach ($bills as $item)
                            <tr>
                                <td>{{ date('F Y', strtotime($item->ServicePeriod ))}}</td>
                                <td class="text-right">{{ $item->PresentKwh }}</td>
                                <td class="text-right">{{ $item->KwhUsed }}</td>
                                <td class="text-right">{{ $item->NetAmount }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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

            // init value
            computeKwhUsed()
            adjustBill($('#KwhUsed').val(), $('#AdditionalCharges').val(), $('#Deductions').val(), is2307Checked)

            $('#ReadingDate').keyup(function() {
                validateDueDate(this.value)
            })

            $('#ReadingDate').on('dp.change', function() {
                validateDueDate(this.value)
            })

            $('#KwhUsed').keyup(function() {
                adjustBill(this.value, $('#AdditionalCharges').val(), $('#Deductions').val(), is2307Checked)
            })

            $('#KwhUsed').on('change', function() {
                adjustBill(this.value, $('#AdditionalCharges').val(), $('#Deductions').val(), is2307Checked)
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

            $('#Demand').keyup(function() {
                computeKwhUsed()
            })
        })

        function validateDueDate(date) {
            var date = moment(date)
            if (date.isValid()) {
                var dueDate = moment(date, "YYYY-MM-DD").add(9, 'days')
                $('#DueDate').val(dueDate.format("YYYY-MM-DD"))
            } else {
                Swal.fire({
                    title : 'Invalid date!',
                    icon : 'error'
                })
            }
        }

        function computeKwhUsed() {
            var pres = parseFloat($('#PresentKwh').val())
            var prev = parseFloat($('#PreviousKwh').val())
            var dif = pres - prev

            // GET PREVISOU KWH
            var prevUsage = "{{ $latestBill != null ? $latestBill->KwhUsed : '0' }}"
            prevUsage = parseFloat(prevUsage)

            // GET PERCENTAGE ALERT
            var percentageHigh = "{{ Bills::getHighConsumptionPercentageAlert() }}"
            percentageHigh = parseFloat(percentageHigh)

            var kwhFinal = parseFloat($('#multiplier').text()) * dif
         
            $('#KwhUsed').val(parseFloat(kwhFinal).toFixed(2)).change()              
            // $('#KwhUsedProxy').val(parseFloat(kwhFinal).toFixed(2)).change()   

            // FILTER HIGH USAGE
            var prevDifference = kwhFinal - prevUsage
            var percentageOfDifference = prevDifference/kwhFinal
            console.log(percentageOfDifference + ' - ' + percentageHigh)
            if (kwhFinal > -1 && percentageOfDifference > percentageHigh) {
                Swal.fire({
                    title : 'High Consumption Alert',
                    text : 'The new Kwh Consumption is ' + parseFloat(percentageOfDifference*100).toFixed(2) + '% higher from the previous consumption (from ' + prevUsage + ' to ' + kwhFinal + ' kwh). You might wanna check the reading for verification',
                    icon : 'warning'
                })
            }
        }

        function adjustBill(kwh, additionalCharges, deductions, is2307) {
            $.ajax({
                    url : '{{ route("readings.get-computed-bill") }}',
                    type : 'GET',
                    data : {
                        AccountNumber : "{{ $account->id }}",
                        Is2307 : is2307,
                        KwhUsed : kwh,
                        PreviousKwh : $('#PreviousKwh').val(),
                        PresentKwh : $('#PresentKwh').val(),
                        Demand : $('#Demand').val(),
                        ServicePeriod : $('#ServicePeriod').val(),
                        BillingDate : $('#ReadingDate').val(),
                    },
                    success : function(res) {
                        $('#NetAmount').val(Number(parseFloat(res['NetAmount']).toFixed(2)).toLocaleString())
                        $('#GenerationSystemCharge').val(Number(parseFloat(res['GenerationSystemCharge']).toFixed(2)).toLocaleString())
                        $('#MissionaryElectrificationCharge').val(Number(parseFloat(res['MissionaryElectrificationCharge']).toFixed(2)).toLocaleString())
                        $('#TransmissionDeliveryChargeKW').val(Number(parseFloat(res['TransmissionDeliveryChargeKW']).toFixed(2)).toLocaleString())
                        $('#EnvironmentalCharge').val(Number(parseFloat(res['EnvironmentalCharge']).toFixed(2)).toLocaleString())
                        $('#TransmissionDeliveryChargeKWH').val(Number(parseFloat(res['TransmissionDeliveryChargeKWH']).toFixed(2)).toLocaleString())
                        $('#StrandedContractCosts').val(Number(parseFloat(res['StrandedContractCosts']).toFixed(2)).toLocaleString())
                        $('#SystemLossCharge').val(Number(parseFloat(res['SystemLossCharge']).toFixed(2)).toLocaleString())
                        $('#NPCStrandedDebt').val(Number(parseFloat(res['NPCStrandedDebt']).toFixed(2)).toLocaleString())
                        $('#OtherGenerationRateAdjustment').val(Number(parseFloat(res['OtherGenerationRateAdjustment']).toFixed(2)).toLocaleString())
                        $('#FeedInTariffAllowance').val(Number(parseFloat(res['FeedInTariffAllowance']).toFixed(2)).toLocaleString())
                        $('#OtherTransmissionCostAdjustmentKW').val(Number(parseFloat(res['OtherTransmissionCostAdjustmentKW']).toFixed(2)).toLocaleString())
                        $('#MissionaryElectrificationREDCI').val(Number(parseFloat(res['MissionaryElectrificationREDCI']).toFixed(2)).toLocaleString())
                        $('#OtherTransmissionCostAdjustmentKWH').val(Number(parseFloat(res['OtherTransmissionCostAdjustmentKWH']).toFixed(2)).toLocaleString())
                        $('#GenerationVAT').val(Number(parseFloat(res['GenerationVAT']).toFixed(2)).toLocaleString())
                        $('#OtherSystemLossCostAdjustment').val(Number(parseFloat(res['OtherSystemLossCostAdjustment']).toFixed(2)).toLocaleString())
                        $('#TransmissionVAT').val(Number(parseFloat(res['TransmissionVAT']).toFixed(2)).toLocaleString())
                        $('#DistributionDemandCharge').val(Number(parseFloat(res['DistributionDemandCharge']).toFixed(2)).toLocaleString())
                        $('#SystemLossVAT').val(Number(parseFloat(res['SystemLossVAT']).toFixed(2)).toLocaleString())
                        $('#DistributionSystemCharge').val(Number(parseFloat(res['DistributionSystemCharge']).toFixed(2)).toLocaleString())
                        $('#DistributionVAT').val(Number(parseFloat(res['DistributionVAT']).toFixed(2)).toLocaleString())
                        $('#SupplyRetailCustomerCharge').val(Number(parseFloat(res['SupplyRetailCustomerCharge']).toFixed(2)).toLocaleString())
                        $('#FranchiseTax').val(Number(parseFloat(res['FranchiseTax']).toFixed(2)).toLocaleString())
                        $('#SupplySystemCharge').val(Number(parseFloat(res['SupplySystemCharge']).toFixed(2)).toLocaleString())
                        $('#BusinessTax').val(Number(parseFloat(res['BusinessTax']).toFixed(2)).toLocaleString())
                        $('#MeteringRetailCustomerCharge').val(Number(parseFloat(res['MeteringRetailCustomerCharge']).toFixed(2)).toLocaleString())
                        $('#RealPropertyTax').val(Number(parseFloat(res['RealPropertyTax']).toFixed(2)).toLocaleString())
                        $('#MeteringSystemCharge').val(Number(parseFloat(res['MeteringSystemCharge']).toFixed(2)).toLocaleString())
                        $('#RFSC').val(Number(parseFloat(res['RFSC']).toFixed(2)).toLocaleString())
                        $('#LifelineRate').val(Number(parseFloat(res['LifelineRate']).toFixed(2)).toLocaleString())
                        $('#InterClassCrossSubsidyCharge').val(Number(parseFloat(res['InterClassCrossSubsidyCharge']).toFixed(2)).toLocaleString())
                        $('#PPARefund').val(Number(parseFloat(res['PPARefund']).toFixed(2)).toLocaleString())
                        $('#SeniorCitizenSubsidy').val(Number(parseFloat(res['SeniorCitizenSubsidy']).toFixed(2)).toLocaleString())
                        $('#OtherLifelineRateCostAdjustment').val(Number(parseFloat(res['OtherLifelineRateCostAdjustment']).toFixed(2)).toLocaleString())
                        $('#SeniorCitizenDiscountAndSubsidyAdjustment').val(Number(parseFloat(res['SeniorCitizenDiscountAndSubsidyAdjustment']).toFixed(2)).toLocaleString())
                        $('#Form2307Amount').val(Number(parseFloat(res['Form2307Amount']).toFixed(2)).toLocaleString())                     
                        $('#Evat2Percent').val(Number(parseFloat(res['Evat2Percent']).toFixed(2)).toLocaleString())
                        $('#Evat5Percent').val(Number(parseFloat(res['Evat5Percent']).toFixed(2)).toLocaleString())
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