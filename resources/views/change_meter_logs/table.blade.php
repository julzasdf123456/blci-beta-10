<div class="table-responsive">
    <table class="table" id="changeMeterLogs-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Oldmeterserial</th>
        <th>Newmeterserial</th>
        <th>Pulloutreading</th>
        <th>Additionalkwhfornextbilling</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($changeMeterLogs as $changeMeterLogs)
            <tr>
                <td>{{ $changeMeterLogs->AccountNumber }}</td>
            <td>{{ $changeMeterLogs->OldMeterSerial }}</td>
            <td>{{ $changeMeterLogs->NewMeterSerial }}</td>
            <td>{{ $changeMeterLogs->PullOutReading }}</td>
            <td>{{ $changeMeterLogs->AdditionalKwhForNextBilling }}</td>
            <td>{{ $changeMeterLogs->Status }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['changeMeterLogs.destroy', $changeMeterLogs->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('changeMeterLogs.show', [$changeMeterLogs->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('changeMeterLogs.edit', [$changeMeterLogs->id]) }}"
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
