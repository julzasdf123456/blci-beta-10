<div class="table-responsive">
    <table class="table" id="accountLocationHistories-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Town</th>
        <th>Barangay</th>
        <th>Purok</th>
        <th>Areacode</th>
        <th>Sequencecode</th>
        <th>Meterreader</th>
        <th>Serviceconnectionid</th>
        <th>Relocationdate</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($accountLocationHistories as $accountLocationHistory)
            <tr>
                <td>{{ $accountLocationHistory->AccountNumber }}</td>
            <td>{{ $accountLocationHistory->Town }}</td>
            <td>{{ $accountLocationHistory->Barangay }}</td>
            <td>{{ $accountLocationHistory->Purok }}</td>
            <td>{{ $accountLocationHistory->AreaCode }}</td>
            <td>{{ $accountLocationHistory->SequenceCode }}</td>
            <td>{{ $accountLocationHistory->MeterReader }}</td>
            <td>{{ $accountLocationHistory->ServiceConnectionId }}</td>
            <td>{{ $accountLocationHistory->RelocationDate }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['accountLocationHistories.destroy', $accountLocationHistory->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('accountLocationHistories.show', [$accountLocationHistory->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('accountLocationHistories.edit', [$accountLocationHistory->id]) }}"
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
