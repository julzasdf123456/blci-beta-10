<div class="table-responsive">
    <table class="table" id="prePaymentTransHistories-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Method</th>
        <th>Amount</th>
        <th>Userid</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($prePaymentTransHistories as $prePaymentTransHistory)
            <tr>
                <td>{{ $prePaymentTransHistory->AccountNumber }}</td>
            <td>{{ $prePaymentTransHistory->Method }}</td>
            <td>{{ $prePaymentTransHistory->Amount }}</td>
            <td>{{ $prePaymentTransHistory->UserId }}</td>
            <td>{{ $prePaymentTransHistory->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['prePaymentTransHistories.destroy', $prePaymentTransHistory->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('prePaymentTransHistories.show', [$prePaymentTransHistory->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('prePaymentTransHistories.edit', [$prePaymentTransHistory->id]) }}"
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
