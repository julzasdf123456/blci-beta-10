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
                        @foreach ($powerBillsCancelled as $item)
                            <tr>
                                <td>{{ $item->ORNumber }}</td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ $item->OldAccountNo }}</td>
                                <td class="text-right">{{ $item->CheckNo }}</td>
                                <td>{{ $item->Bank }}</td>
                                <td class="text-right">{{ $item->NetAmount != null ? number_format($item->NetAmount, 2) : '0' }}</td>
                            </tr>
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
                        @foreach ($nonPowerBillsCancelled as $item)
                            <tr>
                                <td>{{ $item->ORNumber }}</td>
                                <td>{{ $item->PayeeName }}</td>
                                <td>{{ $item->AccountNumber }}</td>
                                <td class="text-right">{{ $item->CheckNo }}</td>
                                <td>{{ $item->Bank }}</td>
                                <td class="text-right">{{ $item->Total != null ? number_format($item->Total, 2) : '0' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>