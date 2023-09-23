<table class="table table-sm table-hover table-bordered">
    <thead>
        <th>Account Number</th>
        <th>Account Name</th>
        <th class="text-center">Kwh Used</th>
        <th class="text-right">Total Amnt</th>
        <th class="text-right">Missionary</th>
        <th class="text-center">Environmental<br>NPC Debt</th>
        <th class="text-center">Stranded CC<br>REDCI</th>
        <th class="text-center">RFSC<br>FIT ALL</th>
        <th class="text-center">VAT</th>
        <th class="text-center">Others?</th>
    </thead>
    <tbody>
        @php
            // COMPUTE TOTALS
            $totalKwhSold = 0;
            $totalAmnt = 0;
            $totalMisionary = 0;
            $totalEnv = 0;
            $totalNpc = 0;
            $totalStranded = 0;
            $totalRedci = 0;
            $totalFitAll = 0;
            $totalRfsc = 0;
            $totalVat = 0;
        @endphp
        @foreach ($coopConsumptions as $item)
            <tr>
                <th><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></th>
                <th>{{ $item->ServiceAccountName }}</th>
                <th class="text-right text-primary">{{ number_format($item->KwhUsed, 2) }}</th>
                <td class="text-right text-danger">{{ number_format($item->NetAmount, 2) }}</td>
                <td class="text-right">{{ number_format($item->MissionaryElectrificationCharge, 2) }}</td>
                <td class="text-right">{{ number_format($item->EnvironmentalCharge, 2) }}<br>{{ number_format($item->NPCStrandedDebt, 2) }}</td>
                <td class="text-right">{{ number_format($item->StrandedContractCosts, 2) }}<br>{{ number_format($item->MissionaryElectrificationREDCI, 2) }}</td>
                <td class="text-right">{{ number_format($item->RFSC, 2) }}<br>{{ number_format($item->FeedInTariffAllowance, 2) }}</td>
                <td class="text-right">{{ number_format(floatval($item->GenerationVAT) + floatval($item->TransmissionVAT) + floatval($item->SystemLossVAT) + floatval($item->DistributionVAT), 2) }}</td>
                <td class="text-right"></td>
            </tr>
            @php
                // COMPUTE TOTALS
                $totalKwhSold += floatval($item->KwhUsed);
                $totalAmnt += floatval($item->NetAmount);
                $totalMisionary += floatval($item->MissionaryElectrificationCharge);
                $totalEnv += floatval($item->EnvironmentalCharge);
                $totalNpc += floatval($item->NPCStrandedDebt);
                $totalStranded += floatval($item->StrandedContractCosts);
                $totalRedci += floatval($item->MissionaryElectrificationREDCI);
                $totalFitAll += floatval($item->FeedInTariffAllowance);
                $totalRfsc += floatval($item->RFSC);
                $totalVat += (floatval($item->GenerationVAT) + floatval($item->TransmissionVAT) + floatval($item->SystemLossVAT) + floatval($item->DistributionVAT));
            @endphp
        @endforeach
        <tr>
            <th colspan="2">TOTAL</th>
            <th class="text-right text-primary">{{ number_format($totalKwhSold, 2) }}</th>
            <th class="text-right text-danger">{{ number_format($totalAmnt, 2) }}</th>
            <th class="text-right">{{ number_format($totalMisionary, 2) }}</th>
            <th class="text-right">{{ number_format($totalEnv, 2) }}<br>{{ number_format($totalNpc, 2) }}</th>
            <th class="text-right">{{ number_format($totalStranded, 2) }}<br>{{ number_format($totalRedci, 2) }}</th>
            <th class="text-right">{{ number_format($totalRfsc, 2) }}<br>{{ number_format($totalFitAll, 2) }}</th>
            <th class="text-right">{{ number_format($totalVat, 2) }}</th>
            <th class="text-right"></th>
        </tr>
    </tbody>
</table>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#download-merged-sales').on('click', function() {
                window.location.href = "{{ url('/kwh_sales/download-merged-sales') }}" + "/" + "{{ $period }}"
            })
        })
    </script>
@endpush