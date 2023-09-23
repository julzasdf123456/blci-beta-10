@php
    use App\Models\ServiceAccounts;
    use App\Models\Bills;
    use App\Models\Readings;
    ini_set('max_execution_time', '600');
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
    {{-- @foreach ($routes as $route) --}}
    <table style="page-break-before: always; width: 100%;">
        <thead>
            <tr>
                <td colspan="10" class="text-left">Generated on {{ date('m/d/Y h:i:s A') }}</td>
            </tr>
            <tr>
                <th colspan="10" class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-center">TURN OFF LIST FOR BILLING MONTH {{ date('F Y', strtotime($period)) }}</th>
            </tr>
            <tr>
                <th colspan="7" class="text-left">METER READER: {{ $meterReader != null ? $meterReader->name : '-' }}</th>
                <th colspan="2" class="text-left">DAY: {{ $day }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-left">NO. OF DISCONNECTIONS: {{ count($list) }}</th>
            </tr>
            {{-- <tr>
                <th colspan="9" class="text-left">ROUTE: {{ $route->AreaCode }}</th>
            </tr> --}}
            <tr>
                <th style="width: 25px; border-bottom: 1px solid #454455"></th>
                <th style="border-bottom: 1px solid #454455">Consumer Details</th>
                <th style="border-bottom: 1px solid #454455" class="text-left">Meter No</th>
                <th style="border-bottom: 1px solid #454455" class="text-left">Seq. No</th>
                <th style="border-bottom: 1px solid #454455" class="text-left">Acct.<br>Status</th>
                <th style="border-bottom: 1px solid #454455" class="text-center">Last <br> Reading</th>
                <th style="border-bottom: 1px solid #454455" class="text-center">Amount</th>
                <th style="border-bottom: 1px solid #454455" class="text-center">Arrears</th>
                <th style="border-bottom: 1px solid #454455" class="text-center">Disco <br> Reading</th>
                <th style="border-bottom: 1px solid #454455">Remarks</th>
            </tr>            
        </thead>
        <tbody>
            @php
                $i=0;
            @endphp
            @foreach ($list as $item)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>
                    {{ $item->OldAccountNo }} <br>
                    {{ $item->ServiceAccountName }} <br>
                    {{ ServiceAccounts::getAddress($item) }}
                </td>
                <td>{{ $item->MeterNumber }}</td>
                <td>{{ $item->SequenceCode }}</td>
                <td>{{ $item->AccountStatus }}</td>
                <td class="text-right" style="border-bottom: 1px solid #454545;">{{ $item->PresentKwh }}</td>
                <td class="text-right" style="border-bottom: 1px solid #454545;">{{ $item->NetAmount != null && is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : $item->NetAmount }}</td>
                <td class="text-right" style="border-bottom: 1px solid #454545;">{{ $item->Arrears != null && is_numeric($item->Arrears) ? number_format($item->Arrears, 2) : $item->Arrears }}</td>
                <td class="text-right" style="border-bottom: 1px solid #454545;"></td>
                <td style="border-bottom: 1px solid #454545;"></td>
                @php
                    $i++;
                @endphp
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- @endforeach --}}
    
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>