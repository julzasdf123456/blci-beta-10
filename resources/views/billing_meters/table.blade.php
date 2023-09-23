<div class="table-responsive">
    <table class="table" id="billingMeters-table">
        <thead>
        <tr>
            <th>Serviceaccountid</th>
        <th>Serialnumber</th>
        <th>Sealnumber</th>
        <th>Brand</th>
        <th>Model</th>
        <th>Multiplier</th>
        <th>Status</th>
        <th>Connectiondate</th>
        <th>Latestreadingdate</th>
        <th>Datedisconnected</th>
        <th>Datetransfered</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($billingMeters as $billingMeters)
            <tr>
                <td>{{ $billingMeters->ServiceAccountId }}</td>
            <td>{{ $billingMeters->SerialNumber }}</td>
            <td>{{ $billingMeters->SealNumber }}</td>
            <td>{{ $billingMeters->Brand }}</td>
            <td>{{ $billingMeters->Model }}</td>
            <td>{{ $billingMeters->Multiplier }}</td>
            <td>{{ $billingMeters->Status }}</td>
            <td>{{ $billingMeters->ConnectionDate }}</td>
            <td>{{ $billingMeters->LatestReadingDate }}</td>
            <td>{{ $billingMeters->DateDisconnected }}</td>
            <td>{{ $billingMeters->DateTransfered }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['billingMeters.destroy', $billingMeters->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('billingMeters.show', [$billingMeters->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('billingMeters.edit', [$billingMeters->id]) }}"
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
