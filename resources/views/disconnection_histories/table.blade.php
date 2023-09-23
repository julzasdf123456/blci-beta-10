<div class="table-responsive">
    <table class="table" id="disconnectionHistories-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Serviceperiod</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Billid</th>
        <th>Disconnectionpayment</th>
        <th>Status</th>
        <th>Userid</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($disconnectionHistories as $disconnectionHistory)
            <tr>
                <td>{{ $disconnectionHistory->AccountNumber }}</td>
            <td>{{ $disconnectionHistory->ServicePeriod }}</td>
            <td>{{ $disconnectionHistory->Latitude }}</td>
            <td>{{ $disconnectionHistory->Longitude }}</td>
            <td>{{ $disconnectionHistory->BillId }}</td>
            <td>{{ $disconnectionHistory->DisconnectionPayment }}</td>
            <td>{{ $disconnectionHistory->Status }}</td>
            <td>{{ $disconnectionHistory->UserId }}</td>
            <td>{{ $disconnectionHistory->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['disconnectionHistories.destroy', $disconnectionHistory->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('disconnectionHistories.show', [$disconnectionHistory->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('disconnectionHistories.edit', [$disconnectionHistory->id]) }}"
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
