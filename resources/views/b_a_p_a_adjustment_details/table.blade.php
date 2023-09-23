<div class="table-responsive">
    <table class="table" id="bAPAAdjustmentDetails-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Billid</th>
        <th>Discountpercentage</th>
        <th>Discountamount</th>
        <th>Bapaname</th>
        <th>Serviceperiod</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($bAPAAdjustmentDetails as $bAPAAdjustmentDetails)
            <tr>
                <td>{{ $bAPAAdjustmentDetails->AccountNumber }}</td>
            <td>{{ $bAPAAdjustmentDetails->BillId }}</td>
            <td>{{ $bAPAAdjustmentDetails->DiscountPercentage }}</td>
            <td>{{ $bAPAAdjustmentDetails->DiscountAmount }}</td>
            <td>{{ $bAPAAdjustmentDetails->BAPAName }}</td>
            <td>{{ $bAPAAdjustmentDetails->ServicePeriod }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['bAPAAdjustmentDetails.destroy', $bAPAAdjustmentDetails->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('bAPAAdjustmentDetails.show', [$bAPAAdjustmentDetails->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('bAPAAdjustmentDetails.edit', [$bAPAAdjustmentDetails->id]) }}"
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
