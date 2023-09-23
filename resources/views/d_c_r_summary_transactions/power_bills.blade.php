<div class="card shadow-none" style="height: 70vh">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-head-fixed text-nowrap table-sm">
            <thead>
                <th>OR Number</th>
                <th>OR Date</th>
                <th>Account Number</th>
                <th>Account Name</th>
                <th>Billing Month</th>
                <th class="text-right">KwhUsed</th>
                <th class="text-right">Surcharges</th>
                <th class="text-right">2% EWT</th>
                <th class="text-right">5% EVAT</th>
                <th class="text-right">OCL</th>
                <th class="text-right">Deductions/<br>Discounts</th>
                <th class="text-right">Amount Paid</th>
            </thead>
            <tbody>
                @if ($powerBills != null)
                    @php
                        $total = 0;
                        $i=0;
                    @endphp
                    @foreach ($powerBills as $item)
                        <tr>
                            <td>{{ $item->ORNumber }}</td>
                            <td>{{ $item->ORDate }}</td>
                            <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></td>
                            <td>{{ $item->ServiceAccountName }}</td>
                            <td>{{ $item->ServicePeriod }}</td>
                            <td class="text-right">{{ number_format($item->KwhUsed, 2) }}</td>
                            <td class="text-right text-danger">{{ number_format($item->Surcharge, 2) }}</td>
                            <td class="text-right text-info">{{ number_format($item->Form2307TwoPercent, 2) }}</td>
                            <td class="text-right text-info">{{ number_format($item->Form2307FivePercent, 2) }}</td>
                            <td class="text-right">{{ number_format($item->AdditionalCharges, 2) }}</td>
                            <td class="text-right text-info">{{ number_format($item->Deductions, 2) }}</td>
                            <td class="text-right text-primary">{{ number_format($item->CashPaid, 2) }}</td>
                        </tr>   
                        @php
                            $total = $total + floatval($item->CashPaid);
                            $i++;
                        @endphp   
                    @endforeach    
                @endif        
            </tbody>
            <tfoot style="position: sticky; inset-block-end: 0; background-color: white;">                  
                <tr>
                    <th colspan="2">Total ({{ $i }} payments)</th> 
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>   
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="text-right text-primary">{{ number_format($total, 2) }}</th>
                </tr>      
            </tfoot>
        </table>
    </div>
</div>