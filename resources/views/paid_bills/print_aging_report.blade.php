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
                <th colspan="21" class="text-center">AGING OF OUTSTANDING BILLS AS OF {{ date('F d, Y') }}</th>
            </tr>
            <tr>
                <th colspan="21" class="text-left">TOWN/CITY: {{ $towns->id }} - {{ $towns->Town }}</th>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid #454455" class='text-center' rowspan="2">ROUTE</th>
                <th class='text-center' colspan="3">CURRENT - 90 DAYS</th>
                <th class='text-center' colspan="3">91 - 180 DAYS</th>
                <th class='text-center' colspan="3">181 - 240 DAYS</th>
                <th class='text-center' colspan="3">241 - 360 DAYS</th>
                <th class='text-center' colspan="3">OVER 360 DAYS</th>
                <th class='text-center' colspan="3">BOOKS TOTAL</th>
                <th style="border-bottom: 1px solid #454455" class='text-center' rowspan="2">TOTAL<br>CONS</th>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid #454455" class='text-center'>CONS</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>BILLS</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>TOTAL AMNT</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>CONS</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>BILLS</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>TOTAL AMNT</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>CONS</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>BILLS</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>TOTAL AMNT</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>CONS</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>BILLS</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>TOTAL AMNT</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>CONS</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>BILLS</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>TOTAL AMNT</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>CONS</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>BILLS</th>
                <th style="border-bottom: 1px solid #454455" class='text-center'>TOTAL AMNT</th>
            </tr>      
        </thead>
        <tbody>
            @php
                $consCount90 = 0;
                $billsCount90 = 0;
                $billsAmount90 = 0;
                $consCount180 = 0;
                $billsCount180 = 0;
                $billsAmount180 = 0;
                $consCount240 = 0;
                $billsCount240 = 0;
                $billsAmount240 = 0;
                $consCount360 = 0;
                $billsCount360 = 0;
                $billsAmount360 = 0;
                $consCountOver360 = 0;
                $billsCountOver360 = 0;
                $billsAmountOver360 = 0;
                $consCountBooksTotal = 0;
                $billsCountBooksTotal = 0;
                $billsAmountBooksTotal = 0;
                $totalCons = 0;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->Town }}-{{ $item->AreaCode }}</td>
                    <td class="text-right">{{ $item->ConsCountNinetyDays }}</td>
                    <td class="text-right">{{ $item->BillsCountNinetyDays }}</td>
                    <td class="text-right">{{ number_format($item->BillsAmountNinetyDays, 2) }}</td>
                    <td class="text-right">{{ $item->ConsCount180Days }}</td>
                    <td class="text-right">{{ $item->BillsCount180Days }}</td>
                    <td class="text-right">{{ number_format($item->BillsAmount180Days, 2) }}</td>
                    <td class="text-right">{{ $item->ConsCount240Days }}</td>
                    <td class="text-right">{{ $item->BillsCount240Days }}</td>
                    <td class="text-right">{{ number_format($item->BillsAmount240Days, 2) }}</td>
                    <td class="text-right">{{ $item->ConsCount360Days }}</td>
                    <td class="text-right">{{ $item->BillsCount360Days }}</td>
                    <td class="text-right">{{ number_format($item->BillsAmount360Days, 2) }}</td>
                    <td class="text-right">{{ $item->ConsCountOver360Days }}</td>
                    <td class="text-right">{{ $item->BillsCountOver360Days }}</td>
                    <td class="text-right">{{ number_format($item->BillsAmountOver360Days, 2) }}</td>
                    <td class="text-right">{{ $item->ConsCountBooksTotal }}</td>
                    <td class="text-right">{{ $item->BillsCountBooksTotal }}</td>
                    <td class="text-right">{{ number_format($item->BillsAmountBooksTotal, 2) }}</td>
                    <td class="text-right">{{ $item->TotalCons }}</td>
                </tr>
                @php
                    $consCount90 += floatval($item->ConsCountNinetyDays);
                    $billsCount90 += floatval($item->BillsCountNinetyDays);
                    $billsAmount90 += floatval($item->BillsAmountNinetyDays);
                    $consCount180 += floatval($item->ConsCount180Days);
                    $billsCount180 += floatval($item->BillsCount180Days);
                    $billsAmount180 += floatval($item->BillsAmount180Days);
                    $consCount240 += floatval($item->ConsCount240Days);
                    $billsCount240 += floatval($item->BillsCount240Days);
                    $billsAmount240 += floatval($item->BillsAmount240Days);
                    $consCount360 += floatval($item->ConsCount360Days);
                    $billsCount360 += floatval($item->BillsCount360Days);
                    $billsAmount360 += floatval($item->BillsAmount360Days);
                    $consCountOver360 += floatval($item->ConsCountOver360Days);
                    $billsCountOver360 += floatval($item->BillsCountOver360Days);
                    $billsAmountOver360 += floatval($item->BillsAmountOver360Days);
                    $consCountBooksTotal += floatval($item->ConsCountBooksTotal);
                    $billsCountBooksTotal += floatval($item->BillsCountBooksTotal);
                    $billsAmountBooksTotal += floatval($item->BillsAmountBooksTotal);
                    $totalCons += floatval($item->TotalCons);
                @endphp
            @endforeach
            <tr>
                <th style="border-top: 1px solid #454455">TOTAL</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $consCount90 }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $billsCount90 }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ number_format($billsAmount90, 2) }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $consCount180 }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $billsCount180 }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ number_format($billsAmount180, 2) }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $consCount240 }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $billsCount240 }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ number_format($billsAmount240, 2) }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $consCount360 }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $billsCount360 }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ number_format($billsAmount360, 2) }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $consCountOver360 }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $billsCountOver360 }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ number_format($billsAmountOver360, 2) }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $consCountBooksTotal }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $billsCountBooksTotal }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ number_format($billsAmountBooksTotal, 2) }}</th>
                <th style="border-top: 1px solid #454455" class="text-right">{{ $totalCons }}</th>
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