<div class="table-responsive">
    <table class="table" id="readingSchedules-table">
        <thead>
        <tr>
            <th>Areacode</th>
        <th>Groupcode</th>
        <th>Serviceperiod</th>
        <th>Scheduleddate</th>
        <th>Meterreader</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($readingSchedules as $readingSchedules)
            <tr>
                <td>{{ $readingSchedules->AreaCode }}</td>
            <td>{{ $readingSchedules->GroupCode }}</td>
            <td>{{ $readingSchedules->ServicePeriod }}</td>
            <td>{{ $readingSchedules->ScheduledDate }}</td>
            <td>{{ $readingSchedules->MeterReader }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['readingSchedules.destroy', $readingSchedules->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('readingSchedules.show', [$readingSchedules->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('readingSchedules.edit', [$readingSchedules->id]) }}"
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
