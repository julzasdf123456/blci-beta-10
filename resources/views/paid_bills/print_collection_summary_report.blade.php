@php
    use App\Models\ServiceAccounts;
    use App\Models\Bills;
    use App\Models\Readings;
    use App\Models\MemberConsumers;
@endphp
<style>
    @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    }
    html, body {
        font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
        /* font-family: sans-serif; */
        /* font-stretch: condensed; */
        font-size: .85em;
    }

    table tbody th,td,
    table thead th {
        /* font-family: sans-serif; */
        font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: .72em;
    }
    @media print {
        @page {
            /* margin: 10px; */
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

        p {
            padding: 0px !important;
            margin: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }
    }  
    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    } 

    p {
        padding: 0px !important;
        margin: 0px;
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    td, th {
        margin-left: 10px;
        padding-left: 10px;
    }

</style>

<div>
    {{-- DETAILS --}}
    <table style="page-break-before: always; width: 100%;">
        <thead>
            <tr>
                <th colspan="21" class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</th>
            </tr>
            <tr>
                <th colspan="21" class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</th>
            </tr>
            <tr>
                <th colspan="21" class="text-center">COLLECTION SUMMARY FROM {{ date('M d', strtotime($from)) }} to {{ date('M d, Y', strtotime($to)) }}</th>
            </tr>
            <tr>
                <th colspan="21" class="text-left">TOWN/CITY: {{ $towns->id }} - {{ $towns->Town }}</th>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">DATE</th>
                <th class="text-center" colspan="2">ARREARS</th>
                <th class="text-center" colspan="2">PREVIOUS</th>
                <th class="text-center" colspan="2">CURRENT</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">SURCHARGE/ <br>INTEREST</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">OTHERS</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">MISC.</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">TOTAL <br> COLLECTION</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">GENERATION<br>VAT</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">TRANSMISSION<br>VAT</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">SYSTEM<br>LOSS VAT</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">DISTRIBUTION<br>VAT</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">OTHERS<br>VAT</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">OTHER<br>INCOME VAT</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">5% EWT</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">2% EWT</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">RFSC</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">EC</th>
                <th style="border-bottom: 1px solid #454455" class="text-center" rowspan="2">ME</th>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid #454455" class="text-center"># Bills</th>
                <th style="border-bottom: 1px solid #454455" class="text-center">Amount</th>
                <th style="border-bottom: 1px solid #454455" class="text-center"># Bills</th>
                <th style="border-bottom: 1px solid #454455" class="text-center">Amount</th>
                <th style="border-bottom: 1px solid #454455" class="text-center"># Bills</th>
                <th style="border-bottom: 1px solid #454455" class="text-center">Amount</th>
            </tr>         
        </thead>
        <tbody>
            @php
                $arrearsCountTotal = 0;
                $arrearsAmountTotal = 0;
                $previousCountTotal = 0;
                $previousAmountTotal = 0;
                $currentCountTotal = 0;
                $currentAmountTotal = 0;
                $surchargeTotal = 0;
                $miscTotal = 0;
                $totalCol = 0;
                $genVatTotal = 0;
                $transVatTotal = 0;
                $sysVatTotal = 0;
                $distVatTotal = 0;
                $miscVatTotal = 0;
                $fiveTotal = 0;
                $twoTotal = 0;
                $rfscTotal = 0;
                $ecTotal = 0;
                $meTotal = 0;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ date('m/d/Y', strtotime($item->ORDate)) }}</td>
                    <td class="text-right">{{ $item->ArrearsCount != null ? number_format($item->ArrearsCount) : '' }}</td>
                    <td class="text-right">{{ $item->ArrearsTotal != null ? number_format($item->ArrearsTotal, 2) : '' }}</td>
                    <td class="text-right">{{ $item->PreviousCount != null ? number_format($item->PreviousCount) : '' }}</td>
                    <td class="text-right">{{ $item->PreviousTotal != null ? number_format($item->PreviousTotal, 2) : '' }}</td>
                    <td class="text-right">{{ $item->CurrentCount != null ? number_format($item->CurrentCount) : '' }}</td>
                    <td class="text-right">{{ $item->CurrentTotal != null ? number_format($item->CurrentTotal, 2) : '' }}</td>
                    <td class="text-right">{{ $item->Surcharge != null ? number_format($item->Surcharge, 2) : '' }}</td>
                    <td></td>
                    <td class="text-right">{{ $item->Misc != null ? number_format($item->Misc, 2) : '' }}</td>
                    <td class="text-right">{{ number_format(floatval($item->Misc) + floatval($item->CurrentTotal) + floatval($item->PreviousTotal), 2) }}</td>
                    <td class="text-right">{{ $item->GenerationVat != null ? number_format($item->GenerationVat, 2) : '' }}</td>
                    <td class="text-right">{{ $item->TransmissionVat != null ? number_format($item->TransmissionVat, 2) : '' }}</td>
                    <td class="text-right">{{ $item->SystemLossVat != null ? number_format($item->SystemLossVat, 2) : '' }}</td>
                    <td class="text-right">{{ $item->DistributionVat != null ? number_format($item->DistributionVat, 2) : '' }}</td>
                    <td class="text-right">{{ $item->MiscVat != null ? number_format($item->MiscVat, 2) : '' }}</td>
                    <td></td>
                    <td class="text-right">{{ $item->FivePercent != null ? number_format($item->FivePercent, 2) : '' }}</td>
                    <td class="text-right">{{ $item->TwoPercent != null ? number_format($item->TwoPercent, 2) : '' }}</td>
                    <td class="text-right">{{ $item->RFSC != null ? number_format($item->RFSC, 2) : '' }}</td>
                    <td class="text-right">{{ $item->EnvironmentalCharge != null ? number_format($item->EnvironmentalCharge, 2) : '' }}</td>
                    <td class="text-right">{{ $item->MissionaryElectrification != null ? number_format($item->MissionaryElectrification, 2) : '' }}</td>
                </tr>
                @php
                    $arrearsCountTotal += floatval($item->ArrearsCount);
                    $arrearsAmountTotal += floatval($item->ArrearsTotal);
                    $previousCountTotal += floatval($item->PreviousCount);
                    $previousAmountTotal += floatval($item->PreviousTotal);
                    $currentCountTotal += floatval($item->CurrentCount);
                    $currentAmountTotal += floatval($item->CurrentTotal);
                    $surchargeTotal += floatval($item->Surcharge);
                    $miscTotal += floatval($item->Misc);
                    $totalCol += floatval($item->Misc) + floatval($item->CurrentTotal) + floatval($item->PreviousTotal);
                    $genVatTotal += floatval($item->GenerationVat);
                    $transVatTotal += floatval($item->TransmissionVat);
                    $sysVatTotal += floatval($item->SystemLossVat);
                    $distVatTotal += floatval($item->DistributionVat);
                    $miscVatTotal += floatval($item->MiscVat);
                    $fiveTotal += floatval($item->FivePercent);
                    $twoTotal += floatval($item->TwoPercent);
                    $rfscTotal += floatval($item->RFSC);
                    $ecTotal += floatval($item->EnvironmentalCharge);
                    $meTotal += floatval($item->MissionaryElectrification);
                @endphp
            @endforeach
            <tr>
                <th style="border-top: 1px solid #454455">Total</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $arrearsCountTotal != null ? number_format($arrearsCountTotal) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $arrearsAmountTotal != null ? number_format($arrearsAmountTotal, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $previousCountTotal != null ? number_format($previousCountTotal) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $previousAmountTotal != null ? number_format($previousAmountTotal, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $currentCountTotal != null ? number_format($currentCountTotal) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $currentAmountTotal != null ? number_format($currentAmountTotal, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $surchargeTotal != null ? number_format($surchargeTotal, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455"></th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $miscTotal != null ? number_format($miscTotal, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $totalCol != null ? number_format($totalCol, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $genVatTotal != null ? number_format($genVatTotal, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $transVatTotal != null ? number_format($transVatTotal, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $sysVatTotal != null ? number_format($sysVatTotal, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $distVatTotal != null ? number_format($distVatTotal, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $miscVatTotal != null ? number_format($miscVatTotal, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455"></th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $fiveTotal != null ? number_format($fiveTotal, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $twoTotal != null ? number_format($twoTotal, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $rfscTotal != null ? number_format($rfscTotal, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $ecTotal != null ? number_format($ecTotal, 2) : '' }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $meTotal != null ? number_format($meTotal, 2) : '' }}</th>
            </tr>
        </tbody>
    </table>
    
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 1600);
</script>