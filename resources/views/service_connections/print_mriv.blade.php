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

<div>
    {{-- SUMMARY --}}
    <table style="page-break-before: always; width: 100%;">
        <thead>
            <tr>
                <th colspan="5" class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</th>
            </tr>
            <tr>
                <th colspan="5" class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</th>
            </tr>
            <tr>
                <th colspan="5" class="text-center">MATERIAL REQUEST ISSUANCE VOUCHER</th>
            </tr>
            <tr>
                <th colspan="3" class="text-left">MIV Control No: __________________________________</th>
                <th colspan="2" class="text-left">J.O. #: ___________</th>
            </tr>
            <tr>
                <th colspan="3" class="text-left">AREA: {{ $town }}</th>
                <th colspan="2" class="text-left">DATE: _____________</th>
            </tr>   
            <tr>
                <th colspan="5" class="text-left">TO: WAREHOUSE SECTION</th>
            </tr>   
            <tr>
                <th style="border-bottom: 1px solid #454455">#</th>
                <th style="border-bottom: 1px solid #454455">Consumer Name</th>    
                <th style="border-bottom: 1px solid #454455">Consumer Address</th>
                <th style="border-bottom: 1px solid #454455">Turn On No.</th>
                <th style="border-bottom: 1px solid #454455">Length (in mtrs)</th>
            </tr>     
        </thead>
        <tbody>
            @php
                $total = 0;
                $i=1;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ strtoupper($item->ServiceAccountName) }}</td>
                    <td>{{ strtoupper(ServiceConnections::getAddress($item)) }}</td>
                    <td class="text-right">{{ $item->ConsumerId }}</td>
                    <td class="text-right">{{ $item->SDWLengthAsInstalled }} mtrs</td>
                </tr>
                @php
                    $i++;
                    $total += floatval($item->SDWLengthAsInstalled);
                @endphp
            @endforeach
            <tr>
                <th  style="border-top: 1px solid #454455" class="text-right"></th>
                <th  style="border-top: 1px solid #454455" class="text-left">TOTAL</th>
                <th  style="border-top: 1px solid #454455" class="text-right"></th>
                <th  style="border-top: 1px solid #454455" class="text-right"></th>
                <th  style="border-top: 1px solid #454455" class="text-right">{{ number_format($total, 2) }} mtrs</th>
            </tr>
        </tbody>
    </table>
    
    <p class="text-indent">I hereby certify that the Electrical Materials/Supplies requiresitioned above are necessary and will be used soley for the purpose stated above. </p>
    <br>
    <table class="table" style="width: 100%;">
        <tbody>
            <tr>
                <td class="text-left">Requested By:</td>
                <td class="text-left">Recommending Approval:</td>
                <td class="text-left">Approved By:</td>
            </tr>
            <tr>
                <td class="text-left"></td>
                <td class="text-left"></td>
                <td class="text-left"></td>
            </tr>
            <tr>
                <td class="text-left"></td>
                <td class="text-left"></td>
                <td class="text-left"></td>
            </tr>
            <tr>
                <td class="text-left"></td>
                <td class="text-left"></td>
                <td class="text-left"></td>
            </tr>
            <tr>
                <td class="text-left"></td>
                <td class="text-left"></td>
                <td class="text-left"></td>
            </tr>
            <tr>
                <th class="text-center">{{ env('MRIV_MSD_COORDINATOR') }}</th>
                <th class="text-center">{{ env('MRIV_OPERATIONS_MAINTENANCE_SUPERVISOR') }}</th>
                <th class="text-center">{{ env('AREA_MANAGER') }}</th>
            </tr>
            <tr>
                <td class="text-center">MSD Coordinator</td>
                <td class="text-center">Ops. and Maintenance Supervisor</td>
                <td class="text-center">Area Manager</td>
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