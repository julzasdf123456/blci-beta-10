@php
    use App\Models\ServiceAccounts;
    use App\Models\Bills;
@endphp
<style>
    @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    }
    html, body {
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: sans-serif;
        font-stretch: condensed;
        font-size: .85em;
    }

    table tbody th,td,
    table thead th {
        font-family: sans-serif;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-stretch: condensed;
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

    .text-right {
        text-align: right;
    }

</style>

<div id="print-area" class="content">
    <p class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</p>
    <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>
    <p class="text-center">CONSUMER LEDGER INQURY (YEARS {{ $from }}-{{ $to }})</p>
    <br>    
    <div style="display: inline-table; width: 40%;">
        <p>ACCOUNT #: {{ $account->OldAccountNo }}</p>
        <p>ACCT NAME: {{ $account->ServiceAccountName }}</p>
        <p>ADDRESS  : {{ ServiceAccounts::getAddress($account) }}</p>
        <p>CONS TYPE: {{ $account->AccountType }}</p>
    </div>
    <div style="display: inline-table; width: 25%;">
        <p>METER #: {{ $meters != null ? $meters->SerialNumber : '-' }}</p>
        <p>MAKE  : {{ $meters != null ? $meters->Brand : '-' }}</p>
        <p>STATUS: {{ $account->AccountStatus }}</p>
    </div>
    <div style="display: inline-table; width: 25%;">
        <p>MULTIPLIER: {{ $account->Multiplier }}</p>
        {{-- <p>MAKE  : {{ $meters != null ? $meters->Brand : '-' }}</p>
        <p>STATUS: {{ $account->AccountStatus }}</p> --}}
    </div>
    <br>
    <br>
    <table style="width: 100%;">
        <thead>
            <th style="border-bottom: 1px solid #454544;">MONTH</th>
            <th style="border-bottom: 1px solid #454544;">BILL NO.</th>
            <th style="border-bottom: 1px solid #454544;">BILL AMOUNT</th>
            <th style="border-bottom: 1px solid #454544;">KWH</th>
            <th style="border-bottom: 1px solid #454544;">DEMAND</th>
            <th style="border-bottom: 1px solid #454544;">RDNG</th>
            <th style="border-bottom: 1px solid #454544;">SURCHARGE</th>
            <th style="border-bottom: 1px solid #454544;">INTEREST</th>
            <th style="border-bottom: 1px solid #454544;">TOTAL</th>
            <th style="border-bottom: 1px solid #454544;">OR NO.</th>
            <th style="border-bottom: 1px solid #454544;">OR DATE</th>
            <th style="border-bottom: 1px solid #454544;">OR PAYMENT</th>
            <th style="border-bottom: 1px solid #454544;">LOCATION</th>
            <th style="border-bottom: 1px solid #454544;">TELLER</th>
        </thead>
        <tbody>
            @php
                $totalKwh = 0;
                $totalDemand = 0;
                $paid = 0;
                $unpaid = 0;
                $totalAmountPaid = 0;
                $totalAmountUnpaid = 0;
                $surchargeTotal = 0;
                $surchargeTotalUnpaid = 0;
                $overAllTotal = 0;
            @endphp
            @foreach ($ledgers as $item)
                <tr>
                    <td style="width: 8%;">{{ date('M Y', strtotime($item->ServicePeriod)) }}</td>
                    <td class="text-right">{{ $item->BillNumber }}</td>
                    <td class="text-right">{{ $item->NetAmount != null && is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : $item->NetAmount }}</td>
                    <td class="text-right">{{ $item->KwhUsed }}</td>
                    <td class="text-right">{{ $item->DemandPresentKwh }}</td>
                    <td class="text-right">{{ $item->PresentKwh }}</td>
                    <td class="text-right">{{ number_format(Bills::getSurchargeOnly($item), 2) }}</td>
                    <td class="text-right">{{ number_format(Bills::getInterestOnly($item), 2) }}</td>
                    <td class="text-right">{{ $item->NetAmount != null && is_numeric($item->NetAmount) ? number_format(floatval($item->NetAmount) + floatval(Bills::getSurchargeFinal($item)), 2) : $item->NetAmount}}</td>
                    <td class="text-right">{{ $item->Status==null | $item->Status=='Application' ? $item->ORNumber : '' }}</td>
                    <td class="text-right">{{ $item->Status==null | $item->Status=='Application' ? date('m/d/Y', strtotime($item->ORDate)) : '' }}</td>
                    <td class="text-right">{{ $item->Status==null | $item->Status=='Application' ? ($item->ORAmount != null && is_numeric($item->ORAmount) ? number_format($item->ORAmount, 2) : $item->ORAmount) : '' }}</td>
                    <td class="text-right">{{ $item->Status==null | $item->Status=='Application' ? $item->OfficeTransacted : '' }}</td>
                    <td class="text-right">{{ $item->Status==null | $item->Status=='Application' ? $item->username : '' }}</td>
                </tr>
                @php
                    $totalKwh = floatval($item->KwhUsed) + $totalKwh;
                    $totalDemand = floatval($item->DemandPresentKwh) + $totalDemand;

                    if ($item->ORNumber != null) {
                        if ($item->Status==null | $item->Status=='Application') {
                            $paid++;
                            $totalAmountPaid = $totalAmountPaid + floatval($item->ORAmount);
                            $surchargeTotal = $surchargeTotal + floatval(Bills::getSurchargeFinal($item));
                            $overAllTotal = $overAllTotal + floatval($item->ORAmount);
                        } else {
                            $unpaid++;
                            $totalAmountUnpaid = $totalAmountUnpaid + floatval($item->NetAmount);
                            $surchargeTotalUnpaid = $surchargeTotalUnpaid + floatval(Bills::getSurchargeFinal($item));
                            $overAllTotal = $overAllTotal + (floatval($item->NetAmount) + floatval(Bills::getSurchargeFinal($item)));
                        }
                        
                    } else {
                        $unpaid++;
                        $totalAmountUnpaid = $totalAmountUnpaid + floatval($item->NetAmount);
                        $surchargeTotalUnpaid = $surchargeTotalUnpaid + floatval(Bills::getSurchargeFinal($item));
                        $overAllTotal = $overAllTotal + (floatval($item->NetAmount) + floatval(Bills::getSurchargeFinal($item)));
                    }
                    
                @endphp
            @endforeach
            <tr>
                <td style="border-top: 1px solid #454544;"></td>
                <td style="border-top: 1px solid #454544;" class="text-right"></td>
                <td style="border-top: 1px solid #454544;" class="text-right">KWH</td>
                <td style="border-top: 1px solid #454544;" class="text-right"> =></td>
                <td style="border-top: 1px solid #454544;" class="text-right">{{ number_format($totalKwh, 2) }}</td>
                <td style="border-top: 1px solid #454544;" class="text-right"></td>
                <td style="border-top: 1px solid #454544;" class="text-right"></td>
                <td style="border-top: 1px solid #454544;" class="text-right"></td>
                <td style="border-top: 1px solid #454544;" class="text-right"></td>
                <td style="border-top: 1px solid #454544;" class="text-right"></td>
                <td style="border-top: 1px solid #454544;" class="text-right"></td>
                <td style="border-top: 1px solid #454544;" class="text-right"></td>
                <td style="border-top: 1px solid #454544;" class="text-right"></td>
                <td style="border-top: 1px solid #454544;" class="text-right"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-right"></td>
                <td class="text-right">DEMAND</td>
                <td class="text-right"> =></td>
                <td class="text-right">{{ number_format($totalDemand, 2) }}</td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
            </tr>
        </tbody>
    </table>
    
    <br>
    <table>
        <thead>
            <th style="border-bottom: 1px solid #454544; padding-right: 20px;">SUMMARY</th>
            <th style="border-bottom: 1px solid #454544; padding-right: 20px;">BILLS</th>
            <th style="border-bottom: 1px solid #454544; padding-right: 20px;">AMOUNT</th>
            <th style="border-bottom: 1px solid #454544; padding-right: 20px;">SUR/INT</th>
            <th style="border-bottom: 1px solid #454544; padding-right: 20px;">TOTAL</th>
        </thead>
        <tbody>
            <tr>
                <td>PAID:</td>
                <td>{{ $paid }}</td>
                <td>{{ number_format($totalAmountPaid, 2) }}</td>
                <td>{{ number_format($surchargeTotal, 2) }}</td>
                <td>{{ number_format(floatval($totalAmountPaid) + floatval($surchargeTotal), 2) }}</td>
            </tr>
            <tr>
                <td>UNPAID:</td>
                <td>{{ $unpaid }}</td>
                <td>{{ number_format($totalAmountUnpaid, 2) }}</td>
                <td>{{ number_format($surchargeTotalUnpaid, 2) }}</td>
                <td>{{ number_format(floatval($totalAmountUnpaid) + floatval($surchargeTotalUnpaid), 2) }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <div style="width: 100%;">GRAND TOTAL: <span style="padding-left: 60px;">{{ number_format($overAllTotal, 2) }}</span><span style="padding-left: 60px;">{{ count($ledgers) }} BILL(S)</span></div>
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>