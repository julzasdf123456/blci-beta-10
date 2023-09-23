@php
    use App\Models\ServiceAccounts;
    use App\Models\Bills;
    use App\Models\Readings;
    use Illuminate\Support\Facades\Auth;
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
            orientation: landscape;
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

<div id="print-area" class="content">
    <p>Generated On: {{ date('F d, Y h:i:s A') }}</p>
    <br>
    <p class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</p>
    <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>
    <p class="text-center"><strong>{{ $type=='All' ? 'ADJUSTMENTS AND OFFICE BILLINGS' : strtoupper($type) }}</strong> REPORT FOR {{ date('F Y', strtotime($period)) }}</p>
    <br> 

    <p>Adjusted By: {{ Auth::user()->name }}</p>  
    <p>Area Office: {{ env('APP_LOCATION') }}</p> 

    <br>
    <table style="width: 100%;">
        <thead>
            <th style="width: 20px; border-bottom: 1px solid #454545;">#</th>
            <th style="border-bottom: 1px solid #454545;">Account No</th>
            <th style="border-bottom: 1px solid #454545;">Account Name</th>
            <th style="border-bottom: 1px solid #454545;">Addres </th>
            <th style="border-bottom: 1px solid #454545;" class="text-right">Bill No</th>
            <th style="border-bottom: 1px solid #454545;" class="text-right">Route</th>
            <th style="border-bottom: 1px solid #454545;" class="text-right">Kwh Used</th>
            <th style="border-bottom: 1px solid #454545;" class="text-right">Amount</th>
            <th style="border-bottom: 1px solid #454545;" class="text-right">Adjustment Type</th>
            <th style="border-bottom: 1px solid #454545;" class="text-right">Adjusted By</th>
            <th style="border-bottom: 1px solid #454545;" class="text-right">Date Adjusted</th>
        </thead>
        <tbody>
            @php
                $i=0;
                $total = 0;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td class="text-left">{{ $i+1 }}</td>
                    <td>{{ $item->OldAccountNo }}</td>
                    <td>{{ $item->ServiceAccountName }}</td>
                    <td>{{ ServiceAccounts::getAddress($item) }}</td>
                    <td class="text-right">{{ $item->BillNumber }}</td>
                    <td class="text-right">{{ $item->AreaCode }}</td>
                    <td class="text-right">{{ $item->KwhUsed }}</td>
                    <th class="text-right">{{ number_format($item->NetAmount, 2) }}</th>
                    <td class="text-right">{{ $item->AdjustmentType }}</td>
                    <td class="text-right">{{ $item->name }}</td>
                    <td class="text-right">{{ date('m/d/Y h:i:s a', strtotime($item->updated_at)) }}</td>
                </tr>  
                @php
                    $total += floatval($item->NetAmount);
                    $i++;
                @endphp                              
            @endforeach
            <tr>
                <td style="border-top: 1px solid #454545;"></td>
                <td style="border-top: 1px solid #454545;"><strong>Total</strong></td>
                <td style="border-top: 1px solid #454545;"></td>
                <td style="border-top: 1px solid #454545;"></td>
                <td style="border-top: 1px solid #454545;"></td>
                <td style="border-top: 1px solid #454545;"></td>
                <td style="border-top: 1px solid #454545;"></td>
                <td style="border-top: 1px solid #454545;" class="text-right"><strong>{{ number_format($total, 2) }}</strong></td>
                <td style="border-top: 1px solid #454545;"></td>
                <td style="border-top: 1px solid #454545;"></td>
                <td style="border-top: 1px solid #454545;"></td>
            </tr>
        </tbody>
    </table>

    <br><br>
    <span>Prepared By</span>
    <br>
    <br>
    <br>
    <span style="border-bottom: 1px solid #454545; padding-bottom: 2px; margin-left: 30px; padding-left: 10px; padding-right: 10px;"><strong>{{ strtoupper(Auth::user()->name) }}</strong></span>  
</div>
<script type="text/javascript">
    window.print();
    
    window.setTimeout(function(){
        window.history.go(-1)
    }, 1000);
</script>