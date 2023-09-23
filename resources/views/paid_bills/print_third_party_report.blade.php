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
                <th colspan="21" class="text-center">THIRD PARTY COLLECTION PAYMENTS FOR {{ date('F d, Y', strtotime($day)) }}</th>
            </tr>
            <tr>
                <th colspan="21" class="text-left">TOWN/CITY: {{ $town }}</th>
            </tr>
            <tr>
                <th  style="border-bottom: 1px solid #454455" class="text-center">Account No.</th>
                <th  style="border-bottom: 1px solid #454455" class="text-center">Account Name</th>
                <th  style="border-bottom: 1px solid #454455" class="text-center">Account Address</th>
                <th  style="border-bottom: 1px solid #454455" class="text-center">Amount Paid</th>
                <th  style="border-bottom: 1px solid #454455" class="text-center">Collection <br> Partner</th>
                <th  style="border-bottom: 1px solid #454455" class="text-center">OR Number</th>
                <th  style="border-bottom: 1px solid #454455" class="text-center">OR Date</th>
                <th  style="border-bottom: 1px solid #454455" class="text-center">Teller</th>
                <th  style="border-bottom: 1px solid #454455" class="text-center">Posted By</th>
            </tr>      
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->OldAccountNo }}</td>
                    <td>{{ $item->ServiceAccountName }}</td>
                    <td>{{ ServiceAccounts::getAddress($item) }}</td>
                    <td class="text-right">{{ number_format($item->NetAmount, 2) }}</td>
                    <td>{{ $item->ObjectSourceId }}</td>
                    <td>{{ $item->ORNumber }}</td>
                    <td>{{ date('M d, Y', strtotime($item->ORDate)) }}</td>
                    <td>{{ $item->CheckNo }}</td>
                    <td>{{ $item->name }}</td>
                </tr>
                @php
                    $total += floatval($item->NetAmount);
                @endphp
            @endforeach
            <tr>
                <th style="border-top: 1px solid #454455" colspan="3" class="text-left">TOTAL</th>
                <th style="border-top: 1px solid #454455" colspan="7" class="text-left">{{ number_format($total, 2) }}</th>
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