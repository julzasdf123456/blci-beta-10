<div class="table-responsive">
    <table class="table" id="arrearsLedgerDistributions-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Serviceperiod</th>
        <th>Amount</th>
        <th>Isbilled</th>
        <th>Ispaid</th>
        <th>Linkedbillnumber</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($arrearsLedgerDistributions as $arrearsLedgerDistribution)
            <tr>
                <td>{{ $arrearsLedgerDistribution->AccountNumber }}</td>
            <td>{{ $arrearsLedgerDistribution->ServicePeriod }}</td>
            <td>{{ $arrearsLedgerDistribution->Amount }}</td>
            <td>{{ $arrearsLedgerDistribution->IsBilled }}</td>
            <td>{{ $arrearsLedgerDistribution->IsPaid }}</td>
            <td>{{ $arrearsLedgerDistribution->LinkedBillNumber }}</td>
            <td>{{ $arrearsLedgerDistribution->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['arrearsLedgerDistributions.destroy', $arrearsLedgerDistribution->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('arrearsLedgerDistributions.show', [$arrearsLedgerDistribution->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('arrearsLedgerDistributions.edit', [$arrearsLedgerDistribution->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
