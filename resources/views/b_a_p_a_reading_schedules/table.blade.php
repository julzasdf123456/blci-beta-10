<div class="table-responsive">
    <table class="table" id="bAPAReadingSchedules-table">
        <thead>
        <tr>
            <th>Serviceperiod</th>
        <th>Town</th>
        <th>Bapaname</th>
        <th>Status</th>
        <th>Downloadedby</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($bAPAReadingSchedules as $bAPAReadingSchedules)
            <tr>
                <td>{{ $bAPAReadingSchedules->ServicePeriod }}</td>
            <td>{{ $bAPAReadingSchedules->Town }}</td>
            <td>{{ $bAPAReadingSchedules->BAPAName }}</td>
            <td>{{ $bAPAReadingSchedules->Status }}</td>
            <td>{{ $bAPAReadingSchedules->DownloadedBy }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['bAPAReadingSchedules.destroy', $bAPAReadingSchedules->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('bAPAReadingSchedules.show', [$bAPAReadingSchedules->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('bAPAReadingSchedules.edit', [$bAPAReadingSchedules->id]) }}"
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
