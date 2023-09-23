<div class="table-responsive">
    <table class="table" id="prePaymentBalances-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Balance</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($prePaymentBalances as $prePaymentBalance)
            <tr>
                <td>{{ $prePaymentBalance->AccountNumber }}</td>
            <td>{{ $prePaymentBalance->Balance }}</td>
            <td>{{ $prePaymentBalance->Status }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['prePaymentBalances.destroy', $prePaymentBalance->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('prePaymentBalances.show', [$prePaymentBalance->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('prePaymentBalances.edit', [$prePaymentBalance->id]) }}"
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
