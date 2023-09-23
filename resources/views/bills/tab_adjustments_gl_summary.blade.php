@php    
    use App\Models\ServiceAccounts;
    use App\Models\DCRSummaryTransactions;
@endphp

<div class="card shadow-none" style="height: 70vh;">
    <div class="card-body p-0 table-responsive">
        <table class="table table-sm table-hover table-head-fixed text-nowrap">
            <thead>
                <th style="width: 25px;">#</th>
                <th>Account No</th>
                <th>Account Name</th>
                <th class="text-right">Current Adjusted Kwh</th>
                <th class="text-right">Current Adjusted Amount</th>
                <th class="text-right">Old Kwh</th>
                <th class="text-right">Old Amount</th>
                <th class="text-right">Kwh Diff.</th>
                <th class="text-right">Amount Diff.</th>
                <th class="text-right">Date Adjusted</th>
                <th class="text-right">Adjusted By</th>
            </thead>
            <tbody>
                {{-- FORMULA: DATA = OLD - NEW --}}
                @php
                    $i = 1;
                @endphp
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $i }}</td>
                        <td><a href="{{ route('serviceAccounts.show', [$item->BillsAccountNumber]) }}">{{ $item->OldAccountNo }}</a></td>
                        <td>{{ $item->ServiceAccountName }}</td>
                        <td class="text-right" style="background-color: #95dcfc;">{{ is_numeric($item->OriginalKwhUsed) ? number_format($item->BillsKwhUsed, 2) : $item->BillsKwhUsed }}</td>
                        <td class="text-right" style="background-color: #95dcfc;">{{ is_numeric($item->OriginalKwhUsed) ? number_format($item->BillsNetAmount, 2) : $item->BillsNetAmount }}</td>
                        <td class="text-right" style="background-color: #ffbdd1;">{{ is_numeric($item->OriginalKwhUsed) ? number_format($item->OriginalKwhUsed, 2) : $item->OriginalKwhUsed }}</td>
                        <td class="text-right" style="background-color: #ffbdd1;">{{ is_numeric($item->OriginalKwhUsed) ? number_format($item->OriginalNetAmount, 2) : $item->OriginalNetAmount }}</td>
                        <td class="text-right" style="background-color: #16875e; color: white;">{{ is_numeric($item->OriginalKwhUsed) && is_numeric($item->BillsKwhUsed) ? number_format($item->OriginalKwhUsed - $item->BillsKwhUsed, 2) : '' }}</td>
                        <td class="text-right" style="background-color: #16875e; color: white;">{{ is_numeric($item->OriginalNetAmount) && is_numeric($item->BillsNetAmount) ? number_format($item->OriginalNetAmount - $item->BillsNetAmount, 2) : '' }}</td>
                        <td class="text-right">{{ date('M d, Y', strtotime($item->OriginalDateAdjusted)) }}</td>
                        <td class="text-right">{{ $item->name }}</td>
                    </tr>     
                    @php
                        $i++;
                        
                    @endphp                         
                @endforeach
                {{-- <tr>
                    <th></th>
                    <th class="text-right">TOTAL</th>
                    <th class="text-center">====></th>
                    <th class="text-right">{{ number_format($kwhTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($arTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($rfscTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($npcTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($fitAllTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($redciTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($meTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($genTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($transTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($slTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($distTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($rptTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($ftTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($bizTotal, 2) }}</th>
                </tr> --}}
            </tbody>
        </table>
    </div>
</div>