<div class="table-responsive">
    <table class="table" id="paidBills-table">
        <thead>
        <tr>
            <th>Billnumber</th>
        <th>Accountnumber</th>
        <th>Serviceperiod</th>
        <th>Ornumber</th>
        <th>Ordate</th>
        <th>Dcrnumber</th>
        <th>Kwhused</th>
        <th>Teller</th>
        <th>Officetransacted</th>
        <th>Postingdate</th>
        <th>Postingtime</th>
        <th>Surcharge</th>
        <th>Form2307Twopercent</th>
        <th>Form2307Fivepercent</th>
        <th>Additionalcharges</th>
        <th>Deductions</th>
        <th>Netamount</th>
        <th>Source</th>
        <th>Objectsourceid</th>
        <th>Userid</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($paidBills as $paidBills)
            <tr>
                <td>{{ $paidBills->BillNumber }}</td>
            <td>{{ $paidBills->AccountNumber }}</td>
            <td>{{ $paidBills->ServicePeriod }}</td>
            <td>{{ $paidBills->ORNumber }}</td>
            <td>{{ $paidBills->ORDate }}</td>
            <td>{{ $paidBills->DCRNumber }}</td>
            <td>{{ $paidBills->KwhUsed }}</td>
            <td>{{ $paidBills->Teller }}</td>
            <td>{{ $paidBills->OfficeTransacted }}</td>
            <td>{{ $paidBills->PostingDate }}</td>
            <td>{{ $paidBills->PostingTime }}</td>
            <td>{{ $paidBills->Surcharge }}</td>
            <td>{{ $paidBills->Form2307TwoPercent }}</td>
            <td>{{ $paidBills->Form2307FivePercent }}</td>
            <td>{{ $paidBills->AdditionalCharges }}</td>
            <td>{{ $paidBills->Deductions }}</td>
            <td>{{ $paidBills->NetAmount }}</td>
            <td>{{ $paidBills->Source }}</td>
            <td>{{ $paidBills->ObjectSourceId }}</td>
            <td>{{ $paidBills->UserId }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['paidBills.destroy', $paidBills->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('paidBills.show', [$paidBills->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('paidBills.edit', [$paidBills->id]) }}"
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
