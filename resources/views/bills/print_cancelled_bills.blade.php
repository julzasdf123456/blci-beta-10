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
    <p class="text-center"><strong>CANCELLED BILLS REPORT</strong></p>
    <br> 

    <p>Area: {{ $area }}</p>  
    <p>From: {{ $from }}</p> 
    <p>To: {{ $to }}</p> 

    <br>
    <table style="width: 100%;">
        <thead>
            <th style="border-bottom: 1px solid #454545;">Account Number</th>
            <th style="border-bottom: 1px solid #454545;">Consumer Name</th>
            <th style="border-bottom: 1px solid #454545;">Address</th>
            <th style="border-bottom: 1px solid #454545;">Billing Month</th>
            <th style="border-bottom: 1px solid #454545;">Date Billed</th>
            <th style="border-bottom: 1px solid #454545;">Kwh Used</th>
            <th style="border-bottom: 1px solid #454545;">Amount Due</th>
            <th style="border-bottom: 1px solid #454545;">Requested By</th>
            <th style="border-bottom: 1px solid #454545;">Approved By</th>
            <th style="border-bottom: 1px solid #454545;">Remarks/Reason</th>
            <th style="border-bottom: 1px solid #454545;">Date/Time Cancelled</th>
        </thead>
        <tbody>
            @foreach ($bills as $item)
                <tr>
                    <td>{{ $item->OldAccountNo }}</td>
                    <td>{{ $item->ServiceAccountName }}</td>
                    <td>{{ ServiceAccounts::getAddress($item) }}</td>
                    <td>{{ $item->ServicePeriod != null ? date('F Y', strtotime($item->ServicePeriod)) : '-' }}</td>
                    <td>{{ date('M d, Y', strtotime($item->BillingDate)) }}</td>
                    <td class="text-right"><strong>{{ $item->KwhUsed }}</strong></td>
                    <td class="text-right text-danger"><strong>{{ is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : '0' }}</strong></td>
                    <td>{{ $item->Requested }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->Notes }}</td>
                    <td>{{ date('M d, Y h:i A', strtotime($item->created_at)) }}</td>
                </tr>
            @endforeach
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