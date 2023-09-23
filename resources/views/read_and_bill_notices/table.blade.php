<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="read-and-bill-notices-table">
            <thead>
            <tr>
                <th>Notes</th>
                <th>Serviceperiod</th>
                <th>Userid</th>
                <th>Zone</th>
                <th>Block</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($readAndBillNotices as $readAndBillNotices)
                <tr>
                    <td>{{ $readAndBillNotices->Notes }}</td>
                    <td>{{ $readAndBillNotices->ServicePeriod }}</td>
                    <td>{{ $readAndBillNotices->UserId }}</td>
                    <td>{{ $readAndBillNotices->Zone }}</td>
                    <td>{{ $readAndBillNotices->Block }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['read-and-bill-notices.destroy', $readAndBillNotices->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('read-and-bill-notices.show', [$readAndBillNotices->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('read-and-bill-notices.edit', [$readAndBillNotices->id]) }}"
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
</div>
