@php
    use App\Models\PaidBillsDetails;
    use App\Models\TransacionPaymentDetails;
@endphp

<div class="row">
    <div class="col-lg-6">
        <div class="card" style="height: 70vh;">
            <div class="card-header border-0">
                <span class="card-title">Power Bills</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>OR No.</th>
                        <th>Consumer Name</th>
                        <th>Account No.</th>
                        <th>Check No.</th>
                        <th>Bank</th>
                        <th>Amount</th>
                    </thead>
                    <tbody>
                        @foreach ($powerBillsCheck as $item)
                            @php
                                $checks = PaidBillsDetails::where('ORNumber', $item->ORNumber)
                                    ->where('AccountNumber', $item->AccountNumber)
                                    ->where('PaymentUsed', 'Check')
                                    ->get();
                            @endphp
                            @foreach ($checks as $check)
                                <tr>
                                    <td>{{ $check->ORNumber }}</td>
                                    <td>{{ $item->ServiceAccountName }}</td>
                                    <td>{{ $item->OldAccountNo }}</td>
                                    <td class="text-right">{{ $check->CheckNo }}</td>
                                    <td>{{ $check->Bank }}</td>
                                    <td class="text-right">{{ $check->Amount != null ? number_format($check->Amount, 2) : '0' }}</td>
                                </tr>
                            @endforeach
                            
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card" style="height: 70vh;">
            <div class="card-header border-0">
                <span class="card-title">Non-Power Bills</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>OR No.</th>
                        <th>Consumer Name</th>
                        <th>Account No.</th>
                        <th>Check No.</th>
                        <th>Bank</th>
                        <th>Amount</th>
                    </thead>
                    <tbody>
                        @foreach ($nonPowerBillsCheck as $item)
                            @php
                                $checks = TransacionPaymentDetails::where('ORNumber', $item->ORNumber)
                                    ->where('PaymentUsed', 'Check')
                                    ->get();
                            @endphp
                            @foreach ($checks as $check)
                                <tr>
                                    <td>{{ $check->ORNumber }}</td>
                                    <td>{{ $item->PayeeName }}</td>
                                    <td>{{ $item->AccountNumber }}</td>
                                    <td class="text-right">{{ $item->CheckNo }}</td>
                                    <td>{{ $item->Bank }}</td>
                                    <td class="text-right">{{ $item->Total != null ? number_format($item->Total, 2) : '0' }}</td>
                                </tr>
                            @endforeach
                            
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>