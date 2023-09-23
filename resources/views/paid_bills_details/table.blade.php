<div class="table-responsive">
    <table class="table" id="paidBillsDetails-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Serviceperiod</th>
        <th>Billid</th>
        <th>Ornumber</th>
        <th>Amount</th>
        <th>Paymentused</th>
        <th>Checkno</th>
        <th>Bank</th>
        <th>Checkexpiration</th>
        <th>Userid</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($paidBillsDetails as $paidBillsDetails)
            <tr>
                <td>{{ $paidBillsDetails->AccountNumber }}</td>
            <td>{{ $paidBillsDetails->ServicePeriod }}</td>
            <td>{{ $paidBillsDetails->BillId }}</td>
            <td>{{ $paidBillsDetails->ORNumber }}</td>
            <td>{{ $paidBillsDetails->Amount }}</td>
            <td>{{ $paidBillsDetails->PaymentUsed }}</td>
            <td>{{ $paidBillsDetails->CheckNo }}</td>
            <td>{{ $paidBillsDetails->Bank }}</td>
            <td>{{ $paidBillsDetails->CheckExpiration }}</td>
            <td>{{ $paidBillsDetails->UserId }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['paidBillsDetails.destroy', $paidBillsDetails->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('paidBillsDetails.show', [$paidBillsDetails->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('paidBillsDetails.edit', [$paidBillsDetails->id]) }}"
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
