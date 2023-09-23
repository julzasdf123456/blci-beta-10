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
    {{-- SUMMARY --}}
    <table style="page-break-before: always; width: 100%;">
        <thead>
            <tr>
                <th colspan="10" class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-center">LIFELINERS SUMMARY FOR BILLING MONTH {{ date('F Y', strtotime($period)) }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-left">TOWN/CITY: {{ $town }}</th>
            </tr>
            <tr>
                <!-- <th style="width: 25px;"></th> -->
                <th style="border-bottom: 1px solid #454455">Kwh Category</th>
                <th style="border-bottom: 1px solid #454455" class="text-left">Number of Consumers</th>
                <th style="border-bottom: 1px solid #454455" class="text-left">Total Kwh Consumed</th>
                <th style="border-bottom: 1px solid #454455" class="text-left">Total Lifeline Discount</th>
                <th style="border-bottom: 1px solid #454455; padding-left: 5px !important;" class="text-left">Total Amount</th>
            </tr>            
        </thead>
        <tbody>
            @php
                $totalKwhUsed = 0;
                $totalCount = 0;
                $totalAmount = 0;
                $totalDsc = 0;
            @endphp
            @foreach ($summary as $item)
                <tr>
                    <td>{{ $item->KwhUsed }} kWh</td>
                    <td class="text-right">{{ $item->TotalCount }}</td>
                    <td class="text-right">{{ number_format($item->TotalKwhUsed) }}</td>
                    <td class="text-right">{{ number_format($item->TotalDsc) }}</td>
                    <td class="text-right">{{ number_format($item->TotalAmount, 2) }}</td>
                </tr>
                @php
                    $totalKwhUsed += floatval($item->TotalKwhUsed);
                    $totalCount += floatval($item->TotalCount);
                    $totalAmount += floatval($item->TotalAmount);
                    $totalDsc += floatval($item->TotalDsc);
                @endphp
            @endforeach
            <tr>
                <th  style="border-top: 1px solid #454455">TOTAL</th>
                <th  style="border-top: 1px solid #454455" class="text-right">{{ $totalCount }}</th>
                <th  style="border-top: 1px solid #454455" class="text-right">{{ number_format($totalKwhUsed) }}</th>
                <th  style="border-top: 1px solid #454455" class="text-right">{{ number_format($totalDsc) }}</th>
                <th  style="border-top: 1px solid #454455" class="text-right">{{ number_format($totalAmount, 2) }}</th>
            </tr>
        </tbody>
    </table>

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
                <th colspan="10" class="text-center">LIFELINERS FOR BILLING MONTH {{ date('F Y', strtotime($period)) }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-left">TOWN/CITY: {{ $town }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-left">KWH CONSUMED: {{ $kwhUsed }}</th>
            </tr>
            <tr>
                <!-- <th style="width: 25px;"></th> -->
                <th style="border-bottom: 1px solid #454455">Acct. #</th>
                <th style="border-bottom: 1px solid #454455">Consumer Name</th>
                <th style="border-bottom: 1px solid #454455">Acct. Type</th>
                <th style="border-bottom: 1px solid #454455" class="text-left">Acct.<br>Status</th>
                <th style="border-bottom: 1px solid #454455; padding-left: 5px !important;" class="text-center">Kwh <br> Used</th>
                <th style="border-bottom: 1px solid #454455; padding-left: 5px !important;" class="text-center">Lifeline <br> Discount</th>
                <th style="border-bottom: 1px solid #454455; padding-left: 5px !important;" class="text-center">Amount</th>
            </tr>            
        </thead>
        <tbody>
            @foreach ($bills as $item)
                <tr>
                    <td>{{ $item->OldAccountNo }}</td>
                    <td>{{ $item->ServiceAccountName }}</td>
                    <td>{{ $item->AccountType }}</td>
                    <td>{{ $item->AccountStatus }}</td>
                    <td class="text-right">{{ $item->KwhUsed }}</td>
                    <td class="text-right">{{ number_format($item->LifelineRate) }}</td>
                    <td class="text-right">{{ number_format($item->NetAmount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 1600);
</script>