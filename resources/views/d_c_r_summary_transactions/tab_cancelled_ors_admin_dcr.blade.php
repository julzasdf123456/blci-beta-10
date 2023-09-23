<div class="card shadow-none" style="height: 70vh">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-head-fixed text-nowrap table-sm">
            <thead>
                <th>OR Number</th>
                <th>Account Number</th>
                <th>Account Name</th>
                <th>Payment Source</th>
                <th class="text-right">Amount</th>
                <th>Status</th>
            </thead>
            <tbody>
                @php
                    $i=0;
                    $total = 0;
                @endphp
                @if ($cancelledAllPayments != null)
                    @foreach ($cancelledAllPayments as $item)
                        <tr>
                            <td>{{ $item->ORNumber }}</td>
                            <td>{{ $item->AccountNumber }}</td>
                            <td>{{ $item->PayeeName }}</td>
                            <td>{{ $item->Source }}</td>
                            <td class="text-right text-info">{{ $item->Total != null ? number_format($item->Total, 2) : '0.0' }}</td>
                            <td>{{ $item->Status }}</td>
                        </tr>    
                        @php
                            $i++;
                            $total += floatval($item->Total);
                        @endphp
                    @endforeach     
                @endif        
            </tbody>
            <tfoot style="position: sticky; inset-block-end: 0; background-color: white;">
                <th colspan="2">Total ({{ $i }} payments)</th> 
                <th></th>
                <th></th>
                <th class="text-right text-primary">{{ number_format($total, 2) }}</th>
                <th></th>
                <th></th>
            </tfoot>
        </table>
    </div>
</div>

