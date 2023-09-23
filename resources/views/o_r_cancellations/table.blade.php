<div class="table-responsive">
    <table class="table" id="oRCancellations-table">
        <thead>
        <tr>
            <th>Ornumber</th>
        <th>Ordate</th>
        <th>From</th>
        <th>Objectid</th>
        <th>Datetimefiled</th>
        <th>Datetimeapproved</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($oRCancellations as $oRCancellations)
            <tr>
                <td>{{ $oRCancellations->ORNumber }}</td>
            <td>{{ $oRCancellations->ORDate }}</td>
            <td>{{ $oRCancellations->From }}</td>
            <td>{{ $oRCancellations->ObjectId }}</td>
            <td>{{ $oRCancellations->DateTimeFiled }}</td>
            <td>{{ $oRCancellations->DateTimeApproved }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['oRCancellations.destroy', $oRCancellations->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('oRCancellations.show', [$oRCancellations->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('oRCancellations.edit', [$oRCancellations->id]) }}"
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
