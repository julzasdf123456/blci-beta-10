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
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: sans-serif;
        font-stretch: condensed;
        font-size: .85em;
    }

    table tbody th,td,
    table thead th {
        font-family: sans-serif;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-stretch: condensed;
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

    .text-right {
        text-align: right;
    }

</style>

<div id="print-area" class="content">
    <p class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</p>
    <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>
    <p class="text-center">{{ $bapaName }} BILLING REPORT FOR {{ date('F Y', strtotime($period)) }}</p>
    <br>    
    <table style="width: 100%;">
        <thead>
            <th class="text-center">#</th>
            <th>Account #</th>
            <th>Sequence #</th>
            <th class="text-center">Name</th>
            <th>Acct. Status</th>
            <th class="text-right">Pres Read</th>
            <th class="text-right">Prev Read</th>
            <th class="text-right">Current <br>Kwh Used</th>
            <th class="text-right">Previous <br>Kwh Used</th>
            <th class="text-right">Amount Due</th>
            <th class="text-right">OR #</th>
            <th class="text-right">Meter #</th>
        </thead>
        <tbody>
            @php
                $i=1;
            @endphp
            @foreach ($readingReport as $item)
                @php
                    // NUMBER OF DAYS
                    $noOfDays = Readings::getDaysBetweenDates($item->PrevReadingTimestamp, $item->ReadingTimestamp);

                    // COMPUTE PERCENTAGE
                    $currentKwh = $item->CurrentKwh != null ? $item->CurrentKwh : (round(floatval($item->KwhUsed) - floatval($item->PrevReading), 2));
                    $currentKwh = floatval($currentKwh);
                    $prevKwh = $item->PrevKwh != null ? $item->PrevKwh : 0;
                    $diffKwh = $currentKwh - $prevKwh;
                    if ($currentKwh != 0) {
                        $percentage = $diffKwh/$currentKwh;
                    } else {
                        $percentage = 0;
                    }
                    $percentage = $item->CurrentKwh != null ? round($percentage, 4) : 0;                                    

                @endphp
                <tr title="{{ $item->CurrentKwh != null ? '' : 'No Bill' }}">
                    <td>{{ $i }}</td>
                    <td>{{ $item->OldAccountNo }}</td>                    
                    <td>{{ $item->SequenceCode }}</td>
                    <td>{{ $item->ServiceAccountName }}</td>
                    <td>{{ $item->AccountStatus }}</td>
                    {{-- <td>{{ date('Y-m-d h:i:s A', strtotime($item->ReadingTimestamp )) }}</td> --}}
                    <td class="text-right">{{ $item->KwhUsed }}</td>
                    <td class="text-right">{{ $item->PrevReading }}</td>
                    @if ($item->CurrentKwh != null)
                        <td class="{{ $item->CurrentKwh != null ? 'text-success' : 'text-danger' }} text-right">{{ $item->CurrentKwh != null ? $item->CurrentKwh : (round(floatval($item->KwhUsed) - floatval($item->PrevReading), 2)) }}</td>
                    @else
                        <td class="text-right"><i>No Bill</i></td>
                    @endif
                    <td class="text-right text-info">{{ $item->PrevKwh != null ? $item->PrevKwh : '0' }}</td>
                    <td class="text-right">{{ $item->AmountDue != null ? number_format($item->AmountDue, 2) : '' }}</td>
                    <td class="text-right">{{ $item->ORNumber }}</td>
                    <td class="text-right">{{ $item->MeterNumber }}</td>
                </tr>
                @php
                    $i++;
                @endphp
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