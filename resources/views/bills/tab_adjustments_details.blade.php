@php    
    use App\Models\ServiceAccounts;
    use App\Models\DCRSummaryTransactions;
@endphp

<div class="card shadow-none" style="height: 70vh;">
    <div class="card-body p-0 table-responsive">
        <table class="table table-sm table-hover table-head-fixed text-nowrap">
            <thead>
                <th style="width: 25px;">#</th>
                <th>Account No</th>
                <th>Account Name</th>
                <th class="text-right">KWH Used</th>
                <th class="text-right">{{ isset($_GET['Area']) ? DCRSummaryTransactions::getARConsumers($_GET['Area']) : '' }} <br> (A/R)</th>
                <th class="text-right">140-142-93<br> (RFSC)</th>
                <th class="text-right">140-142-87<br> (NPC Str. Dbt.)</th>
                <th class="text-right">140-142-88<br> (Fit. All.)</th>
                <th class="text-right">140-142-89<br> (REDCI)</th>
                <th class="text-right">140-142-98<br> (UC ME)</th>
                <th class="text-right">140-142-94<br> (Gen. VAT)</th>
                <th class="text-right">140-142-95<br> (Trans. VAT)</th>
                <th class="text-right">140-142-96<br> (SL VAT)</th>
                <th class="text-right">140-142-97<br> (Othrs/Dist. VAT)</th>
                <th class="text-right">{{ isset($_GET['Area']) ? DCRSummaryTransactions::getARConsumersRPT($_GET['Area']) : '' }}<br> (RPT)</th>
                <th class="text-right">{{ isset($_GET['Area']) ? DCRSummaryTransactions::getARConsumersRPT($_GET['Area']) : '' }}<br> (Frchs. Tax)</th>
                <th class="text-right">{{ isset($_GET['Area']) ? DCRSummaryTransactions::getARConsumersRPT($_GET['Area']) : '' }}<br> (Bus. Tax)</th>
            </thead>
            <tbody>
                {{-- FORMULA: DATA = OLD - NEW --}}
                @php
                    $i = 1;
                    $kwhTotal = 0;
                    $arTotal = 0;
                    $rfscTotal = 0;
                    $npcTotal = 0;
                    $fitAllTotal = 0;
                    $redciTotal = 0;
                    $meTotal = 0;
                    $genTotal = 0;
                    $transTotal = 0;
                    $slTotal = 0;
                    $distTotal = 0;
                    $rptTotal = 0;
                    $ftTotal = 0;
                    $bizTotal = 0;
                @endphp
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $i }}</td>
                        <td><a href="{{ route('serviceAccounts.show', [$item->BillsAccountNumber]) }}">{{ $item->OldAccountNo }}</a></td>
                        <td>{{ $item->ServiceAccountName }}</td>
                        <td class="text-right">{{ is_numeric($item->OriginalKwhUsed) && is_numeric($item->BillsKwhUsed) ? round($item->OriginalKwhUsed - $item->BillsKwhUsed, 2) : '' }}</td>
                        <td class="text-right">{{ number_format(DCRSummaryTransactions::getARConsumersAmountAdjustment($item, 'Original') - DCRSummaryTransactions::getARConsumersAmountAdjustment($item, 'Bills'), 2) }}</td>
                        <td class="text-right">{{ is_numeric($item->OriginalRFSC) && is_numeric($item->BillsRFSC) ? number_format($item->OriginalRFSC - $item->BillsRFSC, 2) : '' }}</td>
                        <td class="text-right">{{ is_numeric($item->OriginalNPCStrandedDebt) && is_numeric($item->BillsNPCStrandedDebt) ?  number_format($item->OriginalNPCStrandedDebt - $item->BillsNPCStrandedDebt, 2) : '' }}</td>
                        <td class="text-right">{{ is_numeric($item->OriginalFeedInTariffAllowance) && is_numeric($item->BillsFeedInTariffAllowance) ?  number_format($item->OriginalFeedInTariffAllowance - $item->BillsFeedInTariffAllowance, 2) : '' }}</td>
                        <td class="text-right">{{ is_numeric($item->OriginalMissionaryElectrificationREDCI) && is_numeric($item->BillsMissionaryElectrificationREDCI) ?  number_format($item->OriginalMissionaryElectrificationREDCI - $item->BillsMissionaryElectrificationREDCI, 2) : '' }}</td>
                        <td class="text-right">{{ is_numeric($item->OriginalMissionaryElectrificationCharge) && is_numeric($item->BillsMissionaryElectrificationCharge) ?  number_format($item->OriginalMissionaryElectrificationCharge - $item->BillsMissionaryElectrificationCharge, 2) : '' }}</td>
                        <td class="text-right">{{ is_numeric($item->OriginalGenerationVAT) && is_numeric($item->BillsGenerationVAT) ?  number_format($item->OriginalGenerationVAT - $item->BillsGenerationVAT, 2) : '' }}</td>
                        <td class="text-right">{{ is_numeric($item->OriginalTransmissionVAT) && is_numeric($item->BillsTransmissionVAT) ?  number_format($item->OriginalTransmissionVAT - $item->BillsTransmissionVAT, 2) : '' }}</td>
                        <td class="text-right">{{ is_numeric($item->OriginalSystemLossVAT) && is_numeric($item->BillsSystemLossVAT) ?  number_format($item->OriginalSystemLossVAT - $item->BillsSystemLossVAT, 2) : '' }}</td>
                        <td class="text-right">{{ is_numeric($item->OriginalDistributionVAT) && is_numeric($item->BillsDistributionVAT) ?  number_format($item->OriginalDistributionVAT - $item->BillsDistributionVAT, 2) : '' }}</td>
                        <td class="text-right">{{ is_numeric($item->OriginalRealPropertyTax) && is_numeric($item->BillsRealPropertyTax) ?  number_format($item->OriginalRealPropertyTax - $item->BillsRealPropertyTax, 2) : '' }}</td>
                        <td class="text-right">{{ is_numeric($item->OriginalFranchiseTax) && is_numeric($item->BillsFranchiseTax) ?  number_format($item->OriginalFranchiseTax - $item->BillsFranchiseTax, 2) : '' }}</td>
                        <td class="text-right">{{ is_numeric($item->OriginalBusinessTax) && is_numeric($item->BillsBusinessTax) ?  number_format($item->OriginalBusinessTax - $item->BillsBusinessTax, 2) : '' }}</td>
                    </tr>   
                    @php
                        $i++;
                        $kwhTotal += (is_numeric($item->OriginalKwhUsed) && is_numeric($item->BillsKwhUsed) ? round($item->OriginalKwhUsed - $item->BillsKwhUsed, 2) : 0);
                        $arTotal += (DCRSummaryTransactions::getARConsumersAmountAdjustment($item, 'Original') - DCRSummaryTransactions::getARConsumersAmountAdjustment($item, 'Bills'));
                        $rfscTotal = (is_numeric($item->OriginalRFSC) && is_numeric($item->BillsRFSC) ? $item->OriginalRFSC - $item->BillsRFSC : 0);
                        $npcTotal = (is_numeric($item->OriginalNPCStrandedDebt) && is_numeric($item->BillsNPCStrandedDebt) ?  $item->OriginalNPCStrandedDebt - $item->BillsNPCStrandedDebt : 0);
                        $fitAllTotal = (is_numeric($item->OriginalFeedInTariffAllowance) && is_numeric($item->BillsFeedInTariffAllowance) ? $item->OriginalFeedInTariffAllowance - $item->BillsFeedInTariffAllowance : 0);
                        $redciTotal = (is_numeric($item->OriginalMissionaryElectrificationREDCI) && is_numeric($item->BillsMissionaryElectrificationREDCI) ? $item->OriginalMissionaryElectrificationREDCI - $item->BillsMissionaryElectrificationREDCI : 0);
                        $meTotal = (is_numeric($item->OriginalMissionaryElectrificationCharge) && is_numeric($item->BillsMissionaryElectrificationCharge) ? $item->OriginalMissionaryElectrificationCharge - $item->BillsMissionaryElectrificationCharge : 0);
                        $genTotal = (is_numeric($item->OriginalGenerationVAT) && is_numeric($item->BillsGenerationVAT) ? $item->OriginalGenerationVAT - $item->BillsGenerationVAT : 0);
                        $transTotal = (is_numeric($item->OriginalTransmissionVAT) && is_numeric($item->BillsTransmissionVAT) ? $item->OriginalTransmissionVAT - $item->BillsTransmissionVAT : 0);
                        $slTotal = (is_numeric($item->OriginalSystemLossVAT) && is_numeric($item->BillsSystemLossVAT) ? $item->OriginalSystemLossVAT - $item->BillsSystemLossVAT : 0);
                        $distTotal = (is_numeric($item->OriginalDistributionVAT) && is_numeric($item->BillsDistributionVAT) ? $item->OriginalDistributionVAT - $item->BillsDistributionVAT : 0);
                        $rptTotal = (is_numeric($item->OriginalRealPropertyTax) && is_numeric($item->BillsRealPropertyTax) ? $item->OriginalRealPropertyTax - $item->BillsRealPropertyTax : 0);
                        $ftTotal = (is_numeric($item->OriginalFranchiseTax) && is_numeric($item->BillsFranchiseTax) ? $item->OriginalFranchiseTax - $item->BillsFranchiseTax : 0);
                        $bizTotal = (is_numeric($item->OriginalBusinessTax) && is_numeric($item->BillsBusinessTax) ? $item->OriginalBusinessTax - $item->BillsBusinessTax : 0);
                    @endphp                         
                @endforeach
                <tr>
                    <th></th>
                    <th class="text-right">TOTAL</th>
                    <th class="text-center">====></th>
                    <th class="text-right">{{ number_format($kwhTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($arTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($rfscTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($npcTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($fitAllTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($redciTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($meTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($genTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($transTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($slTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($distTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($rptTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($ftTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($bizTotal, 2) }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>