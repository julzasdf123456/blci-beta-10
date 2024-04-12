@php
    use App\Models\ServiceConnections;
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

    .table {
        border-collapse: collapse;
    }

    .table td, 
    .table th {
        border: 1px solid #454455;
        padding-top: 4px;
        padding-bottom: 4px;
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

    .text-indent {
        text-indent: 40px;
    }

</style>

<div class="content">
    <p class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</p>
    <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>
    <br>
    <p class="text-center"><strong>PAYMENT ORDER MATERIALS</strong></p>
    <br>  
    <table style="width: 100%;">
        <tbody>
            <tr>
                <td>Applicant:</td>
                <td><strong>{{ $serviceConnection->ServiceAccountName }}</strong></td>
                <td>Date Applied: </td>
                <td><strong>{{ date('F d, Y', strtotime($serviceConnection->DateOfApplication)) }}</strong></td>
            </tr>
            <tr>
                <td>Address:</td>
                <td><strong>{{ ServiceConnections::getAddress($serviceConnection) }}</strong></td>
                <td>Type of Application: </td>
                <td><strong>{{ $serviceConnection->AccountApplicationType }}</strong></td>
            </tr>
            <tr>
                <td>Order Number:</td>
                <td><strong>{{ $whHead != null ? $whHead->orderno : '-' }}</strong></td>
                <td>Account Number:</td>
                <td><strong>{{ $serviceConnection->BarangayCode . '-' . $serviceConnection->TypeOfCustomer . '-' . $serviceConnection->AccountNumber . '-' . $serviceConnection->NumberOfAccounts }}</strong></td>
            </tr>
        </tbody>
    </table>
    {{-- SUMMARY --}}
    <table style="width: 100%; margin-top: 10px;" class="table">
        <thead>
            <th class="text-left" style="border-bottom: 1px solid #454455">#</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Item Code</th>    
            <th class="text-left" style="border-bottom: 1px solid #454455">Description</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Asset Code</th>
            <th style="border-bottom: 1px solid #454455">Quantity</th>
            <th style="border-bottom: 1px solid #454455">UOM</th>
            <th style="border-bottom: 1px solid #454455">Sales Price</th>
            <th style="border-bottom: 1px solid #454455">Total Cost</th>
        </thead>
        <tbody>
            @php
                $salesprice = 0;
                $amt = 0;
            @endphp
            @foreach ($whItems as $key => $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $item->itemcd }}</td>
                    <td>{{ $item->itm_desc }}</td>
                    <td>{{ $item->ascode }}</td>
                    <td class="text-right">{{ $item->qty }}</td>
                    <td class="text-right">{{ $item->uom }}</td>
                    <td class="text-right">{{ is_numeric($item->salesprice) ? number_format($item->salesprice, 2) : $item->salesprice }}</td>
                    <td class="text-right">{{ is_numeric($item->amt) ? number_format($item->amt, 2) : $item->amt }}</td>
                </tr>
                @php
                    $salesprice += (is_numeric($item->salesprice) ? floatval($item->salesprice) : 0);
                    $amt += (is_numeric($item->amt) ? floatval($item->amt) : 0);
                @endphp
            @endforeach
            <tr>
                <td colspan="6"><strong>TOTAL</strong></td>
                <td class="text-right"><strong>{{ number_format($salesprice, 2) }}</strong></td>
                <td class="text-right"><strong>{{ number_format($amt, 2) }}</strong></td>
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