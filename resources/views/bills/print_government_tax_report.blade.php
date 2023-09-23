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

</style>

<div>
    {{-- DETAILS --}}
    <table style="page-break-before: always; width: 100%;">
        <thead>
            <tr>
                <th colspan="10" class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-center">BILLING SUMMARY REPORT FOR THE MONTH : {{ date('F Y', strtotime($period)) }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-left">TOWN/CITY: {{ $towns->id }} - {{ $towns->Town }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-left">ROUTE: {{ $route }}</th>
            </tr>
            <tr>
                <!-- <th style="width: 25px;"></th> -->
                <th style="border-bottom: 1px solid #454455">Acct. #</th>
                <th style="border-bottom: 1px solid #454455">Consumer Name</th>
                <th style="border-bottom: 1px solid #454455" class="text-left">Kwh Used</th>
                <th style="border-bottom: 1px solid #454455; padding-left: 5px !important;" class="text-center">Gen. VAT</th>
                <th style="border-bottom: 1px solid #454455; padding-left: 5px !important;" class="text-center">Trans. VAT</th>
                <th style="border-bottom: 1px solid #454455; padding-left: 5px !important;" class="text-center">Sys. Loss VAT</th>
                <th style="border-bottom: 1px solid #454455; padding-left: 5px !important;" class="text-center">Dist. VAT</th>
                <th style="border-bottom: 1px solid #454455; padding-left: 5px !important;" class="text-center">2% EWT</th>
                <th style="border-bottom: 1px solid #454455; padding-left: 5px !important;" class="text-center">5% EWT</th>
                <th style="border-bottom: 1px solid #454455; padding-left: 5px !important;" class="text-center">Gross Total</th>
                <th style="border-bottom: 1px solid #454455; padding-left: 5px !important;" class="text-center">Net Total</th>
            </tr>            
        </thead>
        <tbody>
            @php
                $totalGen = 0;
                $totalTrans = 0;
                $totalSys = 0;
                $totalDist = 0;
                $total2 = 0;
                $total5 = 0;
                $total = 0;
                $gross = 0;
                $kwh = 0;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->OldAccountNo }}</td>
                    <td>{{ $item->ServiceAccountName }}</td>
                    <td>{{ $item->KwhUsed }}</td>
                    {{-- <td>*</td> --}}
                    <td class="text-right">{{ is_numeric($item->GenerationVAT) ? number_format($item->GenerationVAT, 2) : $item->GenerationVAT }}</td>
                    <td class="text-right">{{ is_numeric($item->TransmissionVAT) ? number_format($item->TransmissionVAT, 2) : $item->TransmissionVAT }}</td>
                    <td class="text-right">{{ is_numeric($item->SystemLossVAT) ? number_format($item->SystemLossVAT, 2) : $item->SystemLossVAT }}</td>
                    <td class="text-right">{{ is_numeric($item->DistributionVAT) ? number_format($item->DistributionVAT, 2) : $item->DistributionVAT }}</td>
                    <td class="text-right">{{ is_numeric($item->Evat2Percent) ? number_format($item->Evat2Percent, 2) : $item->Evat2Percent }}</td>
                    <td class="text-right">{{ is_numeric($item->Evat5Percent) ? number_format($item->Evat5Percent, 2) : $item->Evat5Percent }}</td>
                    <td class="text-right">{{ $item->NetAmount != null && is_numeric($item->NetAmount) ? number_format(floatval($item->NetAmount) + floatval($item->Evat2Percent) + floatval($item->Evat5Percent), 2) : '' }}</td>
                    <td class="text-right">{{ $item->NetAmount != null && is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : $item->NetAmount }}</td>
                </tr>
                @php
                    $kwh += floatval($item->KwhUsed);
                    $totalGen += floatval($item->GenerationVAT);
                    $totalTrans += floatval($item->TransmissionVAT);
                    $totalSys += floatval($item->SystemLossVAT);
                    $totalDist += floatval($item->DistributionVAT);
                    $total2 += floatval($item->Evat2Percent);
                    $total5 += floatval($item->Evat5Percent);
                    $total += floatval($item->NetAmount);
                    $gross += floatval($item->NetAmount) + floatval($item->Evat2Percent) + floatval($item->Evat5Percent);
                @endphp
            @endforeach
            <tr>
                <td style="border-top: 1px solid #454455" ></td>
                <th style="border-top: 1px solid #454455">TOTAL</th>
                <th style="border-top: 1px solid #454455">{{ number_format($kwh, 2) }}</th>
                {{-- <th style="border-top: 1px solid #454455">*</th> --}}
                <th style="border-top: 1px solid #454455" class="text-right">{{ number_format($totalGen, 2) }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ number_format($totalTrans, 2) }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ number_format($totalSys, 2) }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ number_format($totalDist, 2) }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ number_format($total2, 2) }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ number_format($total5, 2) }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ number_format($gross, 2) }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ number_format($total, 2) }}</th>
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