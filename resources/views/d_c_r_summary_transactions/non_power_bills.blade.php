<div class="card shadow-none" style="height: 70vh">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-head-fixed text-nowrap table-sm">
            <thead>
                <th>OR Number</th>
                <th>Account Number</th>
                <th>Account Name</th>
                {{-- <th>GL Code</th> --}}
                <th>Particulars</th>
                <th class="text-right">Amount</th>
            </thead>
            <tbody>
                @php
                    // dd($nonPowerBills);
                    $prevHolder = null;
                    $prev = null;
                    $total = 0;
                    $i=0;
                @endphp
                @foreach ($nonPowerBills as $item)
                    @php
                        $prevHolder = $item->ORNumber;
                    @endphp
                    <tr>
                        <td>
                            @if ($prevHolder!=$prev)
                                {{ $item->ORNumber }}
                                {{-- <a href="{{ route('transactionIndices.browse-ors-view', [$item->id, 'OTHER PAYMENTS']) }}">{{ $item->ORNumber }}</a> --}}
                            @else
                                
                            @endif
                        <td>{{ $prevHolder==$prev ? '' : ($item->OldAccountNo) }}</td>
                        <td>{{ $prevHolder==$prev ? '' : $item->PayeeName }}</td>
                        {{-- <td>{{ $item->AccountCode }}</td> --}}
                        <td>{{ $item->PaymentDetails }}</td>
                        <td class="text-right text-info">{{ $item->Total != null ? number_format($item->Total, 2) : '0.0' }}</td>
                    </tr>   
                    @php
                        $prev = $prevHolder;    
                        $total = $total + floatval($item->Total);
                        $i++;                
                    @endphp   
                @endforeach         
            </tbody>
            <tfoot style="position: sticky; inset-block-end: 0; background-color: white;">
                <th colspan="2">Total ({{ $i }} payments)</th> 
                <th></th>
                <th></th>
                {{-- <th></th> --}}
                <th class="text-right text-primary">{{ number_format($total, 2) }}</th>
            </tfoot>
        </table>
    </div>
</div>

