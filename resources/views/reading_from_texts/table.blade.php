<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="reading-from-texts-table">
            <thead>
            <tr>
                <th>Housenumber</th>
                <th>Consumername</th>
                <th>Oldaccountno</th>
                <th>Newmeternumber</th>
                <th>Readingmonth</th>
                <th>Serviceperiod</th>
                <th>Lastreading</th>
                <th>Oldmeternumber</th>
                <th>Meterreader</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($readingFromTexts as $readingFromText)
                <tr>
                    <td>{{ $readingFromText->HouseNumber }}</td>
                    <td>{{ $readingFromText->ConsumerName }}</td>
                    <td>{{ $readingFromText->OldAccountNo }}</td>
                    <td>{{ $readingFromText->NewMeterNumber }}</td>
                    <td>{{ $readingFromText->ReadingMonth }}</td>
                    <td>{{ $readingFromText->ServicePeriod }}</td>
                    <td>{{ $readingFromText->LastReading }}</td>
                    <td>{{ $readingFromText->OldMeterNumber }}</td>
                    <td>{{ $readingFromText->MeterReader }}</td>
                    <td>{{ $readingFromText->Status }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['readingFromTexts.destroy', $readingFromText->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('readingFromTexts.show', [$readingFromText->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('readingFromTexts.edit', [$readingFromText->id]) }}"
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

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $readingFromTexts])
        </div>
    </div>
</div>
