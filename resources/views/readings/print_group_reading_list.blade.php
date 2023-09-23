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
    <table style="page-break-before: always; width: 100%;">
        <thead>
            <tr>
                <th colspan="10" class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-center">GROUP READING LIST FOR BILLING MONTH {{ date('F Y', strtotime($period)) }}</th>
            </tr>
            <tr>
                <th colspan="10" class="text-left">GROUP: {{ $memberConsumer != null ? (' - ' . MemberConsumers::serializeMemberName($memberConsumer)) : '' }}</th>
            </tr>
            <tr>
                <!-- <th style="width: 25px;"></th> -->
                <th style="border-bottom: 1px solid #454455">Acct. #</th>
                <th style="border-bottom: 1px solid #454455">Consumer Name</th>
                <th style="border-bottom: 1px solid #454455" class="text-left">Meter No</th>
                <th style="border-bottom: 1px solid #454455" class="text-left">Acct.<br>Status</th>
                <th style="border-bottom: 1px solid #454455;" class="text-center">Prev <br> Reading</th>
                <th></th>
                <th style="border-bottom: 1px solid #454455; padding-left: 5px !important;" class="text-center">Pres <br> Reading</th>
                <th></th>
                <th style="border-bottom: 1px solid #454455; padding-left: 5px !important;" class="text-center">Kwh <br> Used</th>
                <th></th>
                <th style="border-bottom: 1px solid #454455;">Remarks</th>
            </tr>            
        </thead>
        <tbody>
            @foreach ($accounts as $item)
                <tr>
                    <td style="padding-top: 18px !important; padding-bottom: 1px !important;">{{ $item->OldAccountNo }}</td>
                    <td style="padding-top: 18px !important; padding-bottom: 1px !important;">{{ $item->ServiceAccountName }}</td>
                    <td style="padding-top: 18px !important; padding-bottom: 1px !important;">{{ $item->MeterNumber }}</td>
                    <td style="padding-top: 18px !important; padding-bottom: 1px !important;">{{ $item->AccountStatus }}</td>
                    <td class="text-right" style="border-bottom: 1px solid #454545;">{{ $item->PreviousKwhUsed }}</td>
                    <th></th>
                    <td class="text-right" style="border-bottom: 1px solid #454545; padding-left: 5px !important;"></td> 
                    <th></th>
                    <td class="text-right" style="border-bottom: 1px solid #454545; padding-left: 5px !important;"></td> 
                    <th></th>
                    <td style="width: 15%; border-bottom: 1px solid #454545;"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>