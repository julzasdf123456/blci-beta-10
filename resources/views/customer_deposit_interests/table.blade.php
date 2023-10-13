<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="customer-deposit-interests-table">
            <thead>
            <tr>
                <th>Accountnumber</th>
                <th>Interestearned</th>
                <th>Currentamountremaining</th>
                <th>Originalamount</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($customerDepositInterests as $customerDepositInterests)
                <tr>
                    <td>{{ $customerDepositInterests->AccountNumber }}</td>
                    <td>{{ $customerDepositInterests->InterestEarned }}</td>
                    <td>{{ $customerDepositInterests->CurrentAmountRemaining }}</td>
                    <td>{{ $customerDepositInterests->OriginalAmount }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['customerDepositInterests.destroy', $customerDepositInterests->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('customerDepositInterests.show', [$customerDepositInterests->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('customerDepositInterests.edit', [$customerDepositInterests->id]) }}"
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

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $customerDepositInterests])
        </div>
    </div>
</div>
