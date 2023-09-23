<button id="download-merged-sales" class="btn btn-sm btn-success m-2"><i class="fas fa-download"></i> Download</button>
<table class="table table-sm table-hover table-bordered">
    <thead>
        <th>Town</th>
        <th class="text-right">No. Of <br> Consumers</th>
        <th class="text-right">Residential</th>
        <th class="text-right">Low Voltage</th>
        <th class="text-center">High Voltage</th>
        <th class="text-center">Total Kwh Sold</th>
        <th class="text-right">Total Amnt</th>
        <th class="text-right">Missionary</th>
        <th class="text-center">Environmental<br>NPC Debt</th>
        <th class="text-center">Stranded CC<br>REDCI</th>
        <th class="text-center">RFSC<br>FIT ALL</th>
        <th class="text-center">RPT<br>NPC Contract</th>
        <th class="text-center">VAT</th>
        <th class="text-center">SC Subsidy<br>Discount</th>
        <th class="text-center">Others?</th>
    </thead>
    <tbody>
        @php
            // COMPUTE TOTALS
            $totalConsumers = 0;
            $totalResidentials = 0;
            $totalLowVoltage = 0;
            $totalHighVoltage = 0;
            $totalKwhSold = 0;
            $totalAmnt = 0;
            $totalMisionary = 0;
            $totalEnv = 0;
            $totalNpc = 0;
            $totalStranded = 0;
            $totalRedci = 0;
            $totalFitAll = 0;
            $totalRpt = 0;
            $totalRfsc = 0;
            $totalVat = 0;
            $totalScSub = 0;
            $totalScDisc = 0;
        @endphp
        @foreach ($allData as $item)
            <tr>
                <th>{{ $item->Town }}</th>
                <td class="text-right">{{ number_format($item->ConsumerCount) }}</td>
                <td class="text-right">{{ number_format($item->Residentials, 2) }}</td>
                <td class="text-right">{{ number_format($item->LowVoltKwh, 2) }}</td>
                <td class="text-right">{{ number_format($item->DemandKwh, 2) }}</td>
                <th class="text-right text-primary">{{ number_format($item->KwhSold, 2) }}</th>
                <td class="text-right text-danger">{{ number_format($item->TotalAmount, 2) }}</td>
                <td class="text-right">{{ number_format($item->Missionary, 2) }}</td>
                <td class="text-right">{{ number_format($item->Environmental, 2) }}<br>{{ number_format($item->NPC, 2) }}</td>
                <td class="text-right">{{ number_format($item->StrandedCC, 2) }}<br>{{ number_format($item->Redci, 2) }}</td>
                <td class="text-right">{{ number_format($item->RFSC, 2) }}<br>{{ number_format($item->FITAll, 2) }}</td>
                <td class="text-right">{{ number_format($item->RPT, 2) }}<br>-</td>
                <td class="text-right">{{ number_format(floatval($item->GenVat) + floatval($item->TransVat) + floatval($item->SysLossVat) + floatval($item->DistVat), 2) }}</td>
                <td class="text-right">{{ number_format($item->SCSubsidy, 2) }}<br>{{ number_format($item->SCDsc, 2) }}</td>
                <td class="text-right"></td>
            </tr>
            @php
                // COMPUTE TOTALS
                $totalConsumers += floatval($item->ConsumerCount);
                $totalResidentials += floatval($item->Residentials);
                $totalLowVoltage += floatval($item->LowVoltKwh);
                $totalHighVoltage += floatval($item->DemandKwh);
                $totalKwhSold += floatval($item->KwhSold);
                $totalAmnt += floatval($item->TotalAmount);
                $totalMisionary += floatval($item->Missionary);
                $totalEnv += floatval($item->Environmental);
                $totalNpc += floatval($item->NPC);
                $totalStranded += floatval($item->StrandedCC);
                $totalRedci += floatval($item->Redci);
                $totalFitAll += floatval($item->FITAll);
                $totalRpt += floatval($item->RPT);
                $totalRfsc += floatval($item->RFSC);
                $totalVat += (floatval($item->GenVat) + floatval($item->TransVat) + floatval($item->SysLossVat) + floatval($item->DistVat));
                $totalScSub += floatval($item->SCSubsidy);
                $totalScDisc += floatval($item->SCDsc);
            @endphp
        @endforeach
        <tr>
            <th>GRAND TOTAL</th>
            <th class="text-right">{{ number_format($totalConsumers) }}</th>
            <th class="text-right">{{ number_format($totalResidentials, 2) }}</th>
            <th class="text-right">{{ number_format($totalLowVoltage, 2) }}</th>
            <th class="text-right">{{ number_format($totalHighVoltage, 2) }}</th>
            <th class="text-right text-primary">{{ number_format($totalKwhSold, 2) }}</th>
            <th class="text-right text-danger">{{ number_format($totalAmnt, 2) }}</th>
            <th class="text-right">{{ number_format($totalMisionary, 2) }}</th>
            <th class="text-right">{{ number_format($totalEnv, 2) }}<br>{{ number_format($totalNpc, 2) }}</th>
            <th class="text-right">{{ number_format($totalStranded, 2) }}<br>{{ number_format($totalRedci, 2) }}</th>
            <th class="text-right">{{ number_format($totalRfsc, 2) }}<br>{{ number_format($totalFitAll, 2) }}</th>
            <th class="text-right">{{ number_format($totalRpt, 2) }}<br>-</th>
            <th class="text-right">{{ number_format($totalVat, 2) }}</th>
            <th class="text-right">{{ number_format($totalScSub, 2) }}<br>{{ number_format($totalScDisc, 2) }}</th>
            <th class="text-right"></th>
        </tr>
    </tbody>
</table>
<div class="divider"></div>
<br>
<div class="row">
    @if ($sales != null)
        {{-- SALES SUMMARY --}}
        <div class="col-lg-4 col-md-5">
            <table class="table table-borderless table-sm">
                <tr>
                    <td>Total KWH Purchased</td>
                    <th class="text-right">{{ number_format($sales->TotalEnergyInput, 2) }}</th>
                </tr>
                <tr>
                    <td>Total KWH Sold</td>
                    <th class="text-right">{{ number_format($sales->TotalEnergyOutput, 2) }}</th>
                </tr>
                <tr>
                    <td>Total Demand KWH Sold</td>
                    <th class="text-right">{{ number_format($demandTotal->Demand, 2) }}</th>
                </tr>
                <tr>
                    <td>System Loss</td>
                    <th class="text-right">{{ number_format($sales->TotalSystemLoss, 2) }} ({{ $sales->TotalSystemLossPercentage }}%)</th>
                </tr>
            </table>
        </div>

        {{-- SALES BREAKDOWN --}}
        <div class="col-lg-4 offset-lg-2 col-md-5">
            <table class="table table-borderless table-sm">
                {{-- <tr>            
                    <td>Calatrava Substation</td>
                    <th class="text-right">{{ $sales != null ? number_format($sales->CalatravaSubstation, 2) : 0 }}</th>
                </tr> --}}
                <tr>            
                    <td>Victorias Substation</td>
                    <th class="text-right">{{ $sales != null ? number_format($sales->VictoriasSubstation, 2) : 0 }}</th>
                </tr>
                <tr>            
                    <td>Sagay Substation</td>
                    <th class="text-right">{{ $sales != null ? number_format($sales->SagaySubstation, 2) : 0 }}</th>
                </tr>
                <tr>            
                    <td>San Sarlos Substation</td>
                    <th class="text-right">{{ $sales != null ? number_format($sales->SanCarlosSubstation, 2) : 0 }}</th>
                </tr>
                <tr>            
                    <td>Escalante Substation</td>
                    <th class="text-right">{{ $sales != null ? number_format($sales->EscalanteSubstation, 2) : 0 }}</th>
                </tr>
                <tr>            
                    <td>Lopez Substation</td>
                    <th class="text-right">{{ $sales != null ? number_format($sales->LopezSubstation, 2) : 0 }}</th>
                </tr>
                <tr>            
                    <td>Cadiz Substation</td>
                    <th class="text-right">{{ $sales != null ? number_format($sales->CadizSubstation, 2) : 0 }}</th>
                </tr>
                <tr>            
                    <td>IPI Substation</td>
                    <th class="text-right">{{ $sales != null ? number_format($sales->IpiSubstation, 2) : 0 }}</th>
                </tr>
                <tr>            
                    <td>Toboso-Calatrava Substation</td>
                    <th class="text-right">{{ $sales != null ? number_format($sales->TobosoCalatravaSubstation, 2) : 0 }}</th>
                </tr>
                <tr>            
                    <td>Victorias Milling Company</td>
                    <th class="text-right">{{ $sales != null ? number_format($sales->VictoriasMillingCompany, 2) : 0 }}</th>
                </tr>        
                <tr>            
                    <td>San Carlos Bionergy</td>
                    <th class="text-right">{{ $sales != null ? number_format($sales->SanCarlosBionergy, 2) : 0 }}</th>
                </tr>
                <tr style="border-top: 1px solid #9a9a9a;">
                    <td>Total KWH</td>
                    <th class="text-right">{{ number_format($sales->TotalEnergyInput, 2) }}</th>
                </tr>
            </table>
        </div>
    @else
        <p style="margin-left: 60px;"><i>Sales Distribution Loss Report Not Yet Generated.</i></p>
    @endif                    
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#download-merged-sales').on('click', function() {
                window.location.href = "{{ url('/kwh_sales/download-merged-sales') }}" + "/" + "{{ $period }}"
            })
        })
    </script>
@endpush