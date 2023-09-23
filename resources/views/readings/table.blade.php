<div class="table-responsive">
    <table class="table" id="readings-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Serviceperiod</th>
        <th>Readingtimestamp</th>
        <th>Kwhused</th>
        <th>Demandkwhused</th>
        <th>Notes</th>
        <th>Latitude</th>
        <th>Longitude</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($readings as $readings)
            <tr>
                <td>{{ $readings->AccountNumber }}</td>
            <td>{{ $readings->ServicePeriod }}</td>
            <td>{{ $readings->ReadingTimestamp }}</td>
            <td>{{ $readings->KwhUsed }}</td>
            <td>{{ $readings->DemandKwhUsed }}</td>
            <td>{{ $readings->Notes }}</td>
            <td>{{ $readings->Latitude }}</td>
            <td>{{ $readings->Longitude }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['readings.destroy', $readings->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('readings.show', [$readings->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('readings.edit', [$readings->id]) }}"
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
