@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Rates for {{ date('F Y', strtotime($servicePeriod)) }}</h4>
                </div>
                <div class="col-sm-6">
                    {!! Form::open(['route' => ['rates.delete-rates', $servicePeriod], 'method' => 'post']) !!}
                    {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger float-right', 'onclick' => "return confirm('Are you sure?')", 'title' => 'Delete this rate']) !!}
                    {!! Form::close() !!}

                    <a class="btn btn-primary float-right" style="margin-right: 10px;"
                       href="{{ route('rates.upload-rate') }}" title="Upload New Rate">
                        <i class="fas fa-file-upload"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            @foreach ($categories as $key => $category)
                                <li class="nav-item"><a class="nav-link {{ $key==0 ? 'active' : '' }}" href="#tab{{ $key }}" data-toggle="tab">
                                    {{ $category->RateFor }}</a></li>
                            @endforeach                            
                        </ul>
                    </div>
                    {{-- {{ dd($rates) }} --}}
                    <div class="card-body">
                        <div class="tab-content table-responsive p-0">
                            @foreach ($categories as $key => $categoryValue)
                                <div class="tab-pane {{ $key==0 ? 'active' : '' }}" id="tab{{ $key }} p-0">                           
                                    <table class="table table-hover table-sm table-bordered">
                                        <thead>
                                            {{-- <tr>
                                                <th width="30%"></th>
                                                <th class="text-center">RESIDENTIAL</th>
                                                <th class="text-center" colspan="5">LOW VOLTAGE</th>
                                                <th class="text-center" colspan="3">HIGHER VOLTAGE</th>
                                            </tr> --}}
                                            
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <th class="text-center">{{ $item->ConsumerType }}</th>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <th class="text-center text-muted">{{ $item->ConsumerTypeDescription }}</th>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th colspan="17">GENERATION AND TRANSMISSION CHARGES:</th>
                                            </tr>
                                            <tr>
                                                <td>Generation System</td>
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->GenerationSystemCharge==null ? '' : number_format($item->GenerationSystemCharge, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>GRAM/ICERA/ACRM</td>
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->ACRM==null ? '' : number_format($item->ACRM, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Transmission Delivery Charge</td>                                                
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->TransmissionDeliveryChargeKWH==null ? '' : number_format($item->TransmissionDeliveryChargeKWH, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>System Loss Charge</td>                                                
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->SystemLossCharge==null ? '' : number_format($item->SystemLossCharge, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Distribution System</td>                                              
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->DistributionSystemCharge==null ? '' : number_format($item->DistributionSystemCharge, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Distribution System</td>                                              
                                                <td>P/kw</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->DistributionDemandCharge==null ? '' : number_format($item->DistributionDemandCharge, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Supply Retail Customer Charge</td>                                          
                                                <td>P/cust/mo.</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->SupplyRetailCustomerCharge==null ? '' : number_format($item->SupplyRetailCustomerCharge, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>                                            
                                            <tr>
                                                <th colspan="17">METERING CHARGES:</th>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;">Metering Retail Customer Charge</td>                                      
                                                <td>P/Meter/mo.</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->MeteringRetailCustomerCharge==null ? '' : number_format($item->MeteringRetailCustomerCharge, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;">Metering System Charge</td>                                      
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->MeteringSystemCharge==null ? '' : number_format($item->MeteringSystemCharge, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Power Act Reduction</td>                                      
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->PowerActReduction==null ? '' : number_format($item->PowerActReduction, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Lifeline Rate Subsidy</td>                                   
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->LifelineRate==null ? '' : number_format($item->LifelineRate, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Senior Citizen Subsidy</td>                               
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->SeniorCitizenSubsidy==null ? '' : number_format($item->SeniorCitizenSubsidy, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Inter-Class Cross Subsidy Charge</td>                             
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->InterClassCrossSubsidyCharge==null ? '' : number_format($item->InterClassCrossSubsidyCharge, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <th colspan="17">VALUE ADDED TAXES:</th>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;">Generation</td>                        
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->GenerationVAT==null ? '' : number_format($item->GenerationVAT, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;">GRAM/ICERA/ACRM</td>                        
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->ACRMVAT==null ? '' : number_format($item->ACRMVAT, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;">Transmission</td>                        
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->TransmissionVAT==null ? '' : number_format($item->TransmissionVAT, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;">System Loss</td>                        
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->SystemLossVAT==null ? '' : number_format($item->SystemLossVAT, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <th colspan="15">UNIVERSAL CHARGE:</th>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;">Missionary Elect.-SPUG</td>                    
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->MissionaryElectrificationSPUG==null ? '' : number_format($item->MissionaryElectrificationSPUG, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;">Missionary Elect.-SPUG TRUE UP</td>                    
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->MissionaryElectrificationSPUGTRUEUP==null ? '' : number_format($item->MissionaryElectrificationSPUGTRUEUP, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;">Missionary Elect.-RED</td>                
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->MissionaryElectrificationREDCI==null ? '' : number_format($item->MissionaryElectrificationREDCI, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;">Environmental</td>
                                                <td colspan="16" class="text-muted">Suspended starting June 2020 Billing Period.</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;">NPC Stranded Contract Cost</td>
                                                <td colspan="16" class="text-muted">Ceased starting February 2020 Billing Period.</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px;">NPC Stranded Debt</td>              
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->NPCStrandedDebt==null ? '' : number_format($item->NPCStrandedDebt, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Feed-inTariff Allowance</td>         
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->FeedInTariffAllowance==null ? '' : number_format($item->FeedInTariffAllowance, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>                                            
                                            <tr>
                                                <td>Local Franchise Tax</td>           
                                                <td>P/kwh</td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <td class="text-right">{{ $item->FranchiseTax==null ? '' : number_format($item->FranchiseTax, 4) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <th>TOTAL RATE PER KWH</th>
                                                <td></td>
                                                @foreach ($rates as $item)
                                                    @if ($item->RateFor == $categoryValue->RateFor)
                                                        <th class="text-right">{{ $item->TotalRateVATIncluded==null ? '' : number_format($item->TotalRateVATIncluded, 4) }}</th>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table> 
                                </div>
                            @endforeach                             
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection