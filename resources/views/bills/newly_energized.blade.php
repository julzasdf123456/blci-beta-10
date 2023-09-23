@php
    use App\Models\ServiceAccounts;
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
                    <h4>Newly Energized Consumers</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body">
                    {!! Form::open(['route' => 'bills.newly-energized', 'method' => 'GET']) !!}
                    <div class="row">
                        <div class="form-group col-lg-1">
                            <label for="Town">Town</label>
                            <select name="Town" id="Town" class="form-control form-control-sm">
                                <option value="All">All</option>
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}" {{ isset($_GET['Town']) && $_GET['Town']==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                                @endforeach
                            </select>
                            {{-- <input type="text" value="{{ isset($_GET['Office']) ? $_GET['Office'] : env('APP_AREA_CODE') }}" class="form-control form-control-sm" id="Office" name="Office"> --}}
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="ServicePeriod">Billing Month</label>
                            <select name="ServicePeriod" id="ServicePeriod" class="form-control form-control-sm">
                                @for ($i = 0; $i < count($months); $i++)
                                    <option value="{{ $months[$i] }}" {{ isset($_GET['ServicePeriod']) && $_GET['ServicePeriod']==$months[$i] ? 'selected' : '' }}>{{ date('F Y', strtotime($months[$i])) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Action</label><br>
                            {!! Form::submit('View', ['class' => 'btn btn-primary btn-sm']) !!}
                            <button class="btn btn-sm btn-success" id="download">Download</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        {{-- RESULTS --}}
        <div class="col-lg-12">
            <div class="card shadow-none" style="height: 70vh">
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-bordered table-hover table-head-fixed text-nowrap">
                        <thead>
                            <th style="width: 20px;">#</th>
                            <th>Consumer Name</th>
                            <th>Account No</th>
                            <th>Account Type</th>
                            <th class="text-right">kW Demand</th>
                            <th class="text-center">kWh Energy</th>
                            <th class="text-right">Multiplier</th>
                            <th class="text-right">Net Amount</th>
                            <th class="text-right">Generation System</th>
                            <th class="text-right">Transmission Delivery KW</th>
                            <th class="text-right">Transmission Delivery KWH</th>
                            <th class="text-right">System Loss</th>
                            <th class="text-right">Distribution Demand</th>
                            <th class="text-right">Distribution System</th>
                            <th class="text-right">Supply Retail Customer</th>
                            <th class="text-right">Supply System</th>
                            <th class="text-right">Metering Retail Customer</th>
                            <th class="text-right">Metering System</th>
                            <th class="text-right">RFSC</th>
                            <th class="text-right">Lifeline</th>
                            <th class="text-right">ICCS</th>
                            <th class="text-right">PPA Refund</th>
                            <th class="text-right">Senior Citizen</th>
                            <th class="text-right">Missionary</th>
                            <th class="text-right">Environmental</th>
                            <th class="text-right">SCC</th>
                            <th class="text-right">NPC</th>
                            <th class="text-right">FIT All.</th>
                            <th class="text-right">REDCI</th>
                            <th class="text-right">Generation VAT</th>
                            <th class="text-right">Transmission VAT</th>
                            <th class="text-right">SystemLoss VAT</th>
                            <th class="text-right">Distribution VAT</th>
                            <th class="text-right">RPT</th>
                            <th class="text-right">Franchise Tax</th>
                            <th class="text-right">Business Tax</th>
                            <th class="text-right">Other Generation Rate Adjustment</th>
                            <th class="text-right">Other Transmission Adjustment KW</th>
                            <th class="text-right">Other Transmission Adjustment KWH</th>
                            <th class="text-right">Other System Loss Adjustment</th>
                            <th class="text-right">Other Lifeline Rate Adjustment</th>
                            <th class="text-right">SC Discount & Subsidy Adjustment</th>
                        </thead>
                        <tbody>
                            @php
                                $i=1;
                            @endphp
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $item->ServiceAccountName }}</td>
                                    <td><a href="{{ $item->OldAccountNo != null ? route('serviceAccounts.show', [$item->AccountId]) : '' }}">{{ $item->OldAccountNo }}</a></td>
                                    <td>{{ $item->ConsumerType }}</td>
                                    <td class="text-right">{{ $item->DemandPresentKwh }}</td>
                                    <td class="text-right">{{ is_numeric($item->KwhUsed) ? round($item->KwhUsed, 2) : 0 }}</td>
                                    <td class="text-right">{{ $item->Multiplier }}</td>
                                    <th class="text-right text-danger">{{ is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : "0" }}</th>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->GenerationSystemCharge, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->TransmissionDeliveryChargeKW, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->TransmissionDeliveryChargeKWH, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->SystemLossCharge, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->DistributionDemandCharge, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->DistributionSystemCharge, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->SupplyRetailCustomerCharge, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->SupplySystemCharge, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->MeteringRetailCustomerCharge, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->MeteringSystemCharge, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->RFSC, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->LifelineRate, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->InterClassCrossSubsidyCharge, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->PPARefund, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->SeniorCitizenSubsidy, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->MissionaryElectrificationCharge, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->EnvironmentalCharge, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->StrandedContractCosts, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->NPCStrandedDebt, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->FeedInTariffAllowance, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->MissionaryElectrificationREDCI, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->GenerationVAT, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->TransmissionVAT, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->SystemLossVAT, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->DistributionVAT, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->RealPropertyTax, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->FranchiseTax, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->BusinessTax, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->OtherGenerationRateAdjustment, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->OtherTransmissionCostAdjustmentKW, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->OtherTransmissionCostAdjustmentKWH, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->OtherSystemLossCostAdjustment, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->OtherLifelineRateCostAdjustment, 2) : "0" }}</td>
                                    <td class="text-right">{{ is_numeric($item->NetAmount) ? number_format($item->SeniorCitizenDiscountAndSubsidyAdjustment, 2) : "0" }}</td>
                                </tr>
                                @php
                                    $i++;
                                @endphp
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
            $('#download').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/bills/download-newly-energized') }}" + "/" + $('#Town').val()  + "/" + $('#ServicePeriod').val()
            })
        })
    </script>
@endpush