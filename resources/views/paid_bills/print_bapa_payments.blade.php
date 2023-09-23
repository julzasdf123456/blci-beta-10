@php
    use App\Models\ServiceAccounts;
    use App\Models\Bills;
    use App\Models\User;
@endphp
<style>
    @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    }
    html, body {
        font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
        /* font-stretch: condensed; */
        font-size: 1.05em;
    }

    table tbody th,td,
    table thead th {
        font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: 1.05em;
    }

    @media print {
        @page {
            /* margin-top: 80px; */
        }

        .print-area {
            page-break-before: always;
        }

        .print-area:last-child {
            page-break-after: auto;
        }

        header {
            display: none;
        }

    }
</style>

@php
    $i=0;
@endphp
@foreach ($paidBills as $item)
@if ($i<1)
<div class="print-area">
    <div>
        <span style="margin-left: 15px;">{{ $item->OldAccountNo }}</span>
        <span style="margin-left: 176px;">{{ $item != null ? $item->ORNumber : '-' }}</span><br>
        <span style="margin-left: 15px;">Meter #: {{ $item->MeterNumber != null ? $item->MeterNumber : '-' }}</span>
        <span style="margin-left: 26px;">{{ $item->ServiceAccountName }}</span><br>
        <span style="margin-left: 15px;">{{ ServiceAccounts::getAddress($item) }}</span>
        <span style="margin-left: 26px;">{{ $item->AccountStatus }}</span><br>
        <!-- <span style="margin-left: 10px;">{{ $item->AccountType }}</span> -->
        
        <br>
        <br>
        <span style="float: right; margin-right: 20px;">{{ $item->created_at != null ? date('h:i:s A m/d/Y', strtotime($item->created_at)) : '-' }}</span><br>
    </div>
    
    <br>
    @php
        $total = 0.0;
    @endphp
    <br>
    <table style="height: 49vh; margin-top: 1px;">
        <tbody>     
            @php
                // GET BILLS
                $bill = Bills::where('ServicePeriod', $item->ServicePeriod)
                    ->where('AccountNumber', $item->AccountNumber)
                    ->first();
                $total = $total + floatval($item->NetAmount);

                $user = User::find($item->Teller);
            @endphp
            <tr style="vertical-align: top;">
                <td>{{ $bill != null ? $bill->BillNumber : $item->ObjectSourceId }}</td>
                <td style="padding-left: 15px;">{{ date('MY', strtotime($item->ServicePeriod)) }}</td>
                <td style="padding-left: 20px;">{{ $bill != null ? date('m/d/Y', strtotime($bill->DueDate)) : 'n/a' }}</td>
                <td style="padding-left: 15px;">{{ number_format(floatval($item->NetAmount) - floatval($item->Surcharge), 2) }}</td>
                <td style="padding-left: 15px;">{{ number_format($item->Surcharge, 2) }}</td>
                <td style="padding-left: 15px;">{{ number_format($item->NetAmount, 2) }}</td>
            </tr>  
        </tbody>
    </table>
    <span style="margin-top: 60px">{{ $user != null ? $user->name : 'Teller: n/a' }}</span>
    <span style="float: right; margin-right: 50px;">{{ number_format($total, 2) }}</span>
</div>
@else
<div class="print-area">
    <div style="margin-top: 20px;">
        <span style="margin-left: 15px;">{{ $item->OldAccountNo }}</span>
        <span style="margin-left: 176px;">{{ $item != null ? $item->ORNumber : '-' }}</span><br>
        <span style="margin-left: 15px;">Meter #: {{ $item->MeterNumber != null ? $item->MeterNumber : '-' }}</span>
        <span style="margin-left: 26px;">{{ $item->ServiceAccountName }}</span><br>
        <span style="margin-left: 15px;">{{ ServiceAccounts::getAddress($item) }}</span>
        <span style="margin-left: 26px;">{{ $item->AccountStatus }}</span><br>
        <!-- <span style="margin-left: 10px;">{{ $item->AccountType }}</span> -->
        
        <br>
        <br>
        <span style="float: right; margin-right: 5px; margin-top: 7px;">{{ $item->created_at != null ? date('h:i:s A m/d/Y', strtotime($item->created_at)) : '-' }}</span><br>
    </div>
    
    <br>
    @php
        $total = 0.0;
    @endphp
    <br>
    <br>
    <table style="height: 46vh; margin-top: 1px;">
        <tbody>     
            @php
                // GET BILLS
                $bill = Bills::where('ServicePeriod', $item->ServicePeriod)
                    ->where('AccountNumber', $item->AccountNumber)
                    ->first();
                $total = $total + floatval($item->NetAmount);

                $user = User::find($item->Teller);
            @endphp
            <tr style="vertical-align: top;">
                <td>{{ $bill != null ? $bill->BillNumber : $item->ObjectSourceId }}</td>
                <td style="padding-left: 15px;">{{ date('MY', strtotime($item->ServicePeriod)) }}</td>
                <td style="padding-left: 20px;">{{ $bill != null ? date('m/d/Y', strtotime($bill->DueDate)) : 'n/a' }}</td>
                <td style="padding-left: 15px;">{{ number_format(floatval($item->NetAmount) - floatval($item->Surcharge), 2) }}</td>
                <td style="padding-left: 15px;">{{ number_format($item->Surcharge, 2) }}</td>
                <td style="padding-left: 15px;">{{ number_format($item->NetAmount, 2) }}</td>
            </tr>  
        </tbody>
    </table>
    <span style="margin-top: 60px;">{{ $user != null ? $user->name : 'Teller: n/a' }}</span>
    <span style="float: right; margin-right: 50px;">{{ number_format($total, 2) }}</span>
</div>
@endif

@php
    $i++;
@endphp
@endforeach

<script type="text/javascript">
    window.print();

window.setTimeout(function(){
    window.location.href = "{{ route('paidBills.bapa-payments') }}";
}, 800);
</script>