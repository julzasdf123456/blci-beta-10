<div class="table-responsive">
    <table class="table" id="discoNoticeHistories-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Serviceperiod</th>
        <th>Billid</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($discoNoticeHistories as $discoNoticeHistory)
            <tr>
                <td>{{ $discoNoticeHistory->AccountNumber }}</td>
            <td>{{ $discoNoticeHistory->ServicePeriod }}</td>
            <td>{{ $discoNoticeHistory->BillId }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['discoNoticeHistories.destroy', $discoNoticeHistory->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('discoNoticeHistories.show', [$discoNoticeHistory->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('discoNoticeHistories.edit', [$discoNoticeHistory->id]) }}"
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
