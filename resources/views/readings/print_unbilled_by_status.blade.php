@php
    use App\Models\ServiceAccounts;
    use App\Models\Bills;
    use App\Models\Readings;
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

<div id="print-area" class="content">
    <p>Generated On: {{ date('F d, Y h:i:s A') }}</p>
    <br>
    <p class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</p>
    <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>
    <p class="text-center"><strong>{{ $status }}</strong> READINGS REPORT FOR {{ date('F Y', strtotime($period)) }}</p>
    <br> 
    <p>Meter Reader: {{ $meterReader->name }}</p>  
    <p>Day: {{ $day }}</p> 
    <table style="width: 100%;">
        <thead>
            <th class="text-center" style="border-bottom: 1px solid #454455">#</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Account #</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Sequence #</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Name</th>
            <th class="text-left" style="border-bottom: 1px solid #454455">Acct<br>Status</th>
            <th class="text-right" style="border-bottom: 1px solid #454455">Meter #</th>
            <th class="text-left" style="padding-left: 20px; border-bottom: 1px solid #454455">Reading<br>Datetime</th>
            <th class="text-right" style="border-bottom: 1px solid #454455">Pres<br>Read</th>
            <th class="text-right" style="border-bottom: 1px solid #454455">Prev<br>Read</th>
            <th class="text-right" style="border-bottom: 1px solid #454455">Billed</th>
            <th class="text-right" style="border-bottom: 1px solid #454455">Field<br>Findings</th>
            <th class="text-right" style="border-bottom: 1px solid #454455">Remarks</th>
        </thead>
        <tbody>
            @php
                $i=0;
            @endphp
            @foreach ($readingReport as $item)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $item->OldAccountNo }}</td>                    
                    <td>{{ $item->SequenceCode }}</td>
                    <td>{{ $item->ServiceAccountName }}</td>
                    <td>{{ $item->AccountStatus }}</td>
                    <td class="text-right">{{ $item->MeterNumber }}</td>
                    <td style="padding-left: 20px;">{{ date('Y-m-d h:i:s A', strtotime($item->ReadingTimestamp )) }}</td>
                    <td class="text-right">{{ $item->KwhUsed }}</td>
                    <td class="text-right">{{ $item->PrevReading }}</td>
                    <td class="text-right">{{ $item->CurrentKwh != null ? 'Yes' : 'No' }}</td>
                    <td class="text-right">{{ $item->FieldStatus }}</td>
                    <td class="text-right">{{ $item->Notes }}</td>
                </tr>
                @php
                    $i++;
                @endphp
            @endforeach
            <tr>
                <th style="border-top: 1px solid #454455">TOTAL</th>
                <td style="border-top: 1px solid #454455"></td>                    
                <td style="border-top: 1px solid #454455">{{ $i }}</td>
                <td style="border-top: 1px solid #454455"></td>
                <td style="border-top: 1px solid #454455"></td>
                <td style="border-top: 1px solid #454455" class="text-right"></td>
                <td style="padding-left: 20px; border-top: 1px solid #454455"></td>
                <td style="border-top: 1px solid #454455" class="text-right"></td>
                <td style="border-top: 1px solid #454455" class="text-right"></td>
                <td style="border-top: 1px solid #454455" class="text-right"></td>
                <td style="border-top: 1px solid #454455" class="text-right"></td>
                <td style="border-top: 1px solid #454455" class="text-right"></td>
            </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>