<div class="table-responsive">
    <table class="table" id="bAPAAdjustments-table">
        <thead>
        <tr>
            <th>Bapaname</th>
        <th>Serviceperiod</th>
        <th>Discountpercentage</th>
        <th>Discountamount</th>
        <th>Numberofconsumers</th>
        <th>Subtotal</th>
        <th>Netamount</th>
        <th>Userid</th>
        <th>Route</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($bAPAAdjustments as $bAPAAdjustments)
            <tr>
                <td>{{ $bAPAAdjustments->BAPAName }}</td>
            <td>{{ $bAPAAdjustments->ServicePeriod }}</td>
            <td>{{ $bAPAAdjustments->DiscountPercentage }}</td>
            <td>{{ $bAPAAdjustments->DiscountAmount }}</td>
            <td>{{ $bAPAAdjustments->NumberOfConsumers }}</td>
            <td>{{ $bAPAAdjustments->SubTotal }}</td>
            <td>{{ $bAPAAdjustments->NetAmount }}</td>
            <td>{{ $bAPAAdjustments->UserId }}</td>
            <td>{{ $bAPAAdjustments->Route }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['bAPAAdjustments.destroy', $bAPAAdjustments->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('bAPAAdjustments.show', [$bAPAAdjustments->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('bAPAAdjustments.edit', [$bAPAAdjustments->id]) }}"
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
