<div class="table-responsive">
    <table class="table" id="bAPAPayments-table">
        <thead>
        <tr>
            <th>Bapaname</th>
        <th>Serviceperiod</th>
        <th>Ornumber</th>
        <th>Ordate</th>
        <th>Subtotal</th>
        <th>Twopercentdiscount</th>
        <th>Fivepercentdiscount</th>
        <th>Additionalcharges</th>
        <th>Deductions</th>
        <th>Vat</th>
        <th>Total</th>
        <th>Teller</th>
        <th>Noofconsumerspaid</th>
        <th>Status</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($bAPAPayments as $bAPAPayments)
            <tr>
                <td>{{ $bAPAPayments->BAPAName }}</td>
            <td>{{ $bAPAPayments->ServicePeriod }}</td>
            <td>{{ $bAPAPayments->ORNumber }}</td>
            <td>{{ $bAPAPayments->ORDate }}</td>
            <td>{{ $bAPAPayments->SubTotal }}</td>
            <td>{{ $bAPAPayments->TwoPercentDiscount }}</td>
            <td>{{ $bAPAPayments->FivePercentDiscount }}</td>
            <td>{{ $bAPAPayments->AdditionalCharges }}</td>
            <td>{{ $bAPAPayments->Deductions }}</td>
            <td>{{ $bAPAPayments->VAT }}</td>
            <td>{{ $bAPAPayments->Total }}</td>
            <td>{{ $bAPAPayments->Teller }}</td>
            <td>{{ $bAPAPayments->NoOfConsumersPaid }}</td>
            <td>{{ $bAPAPayments->Status }}</td>
            <td>{{ $bAPAPayments->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['bAPAPayments.destroy', $bAPAPayments->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('bAPAPayments.show', [$bAPAPayments->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('bAPAPayments.edit', [$bAPAPayments->id]) }}"
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
