<div class="table-responsive">
    <table class="table" id="notifiers-table">
        <thead>
        <tr>
            <th>Notification</th>
        <th>From</th>
        <th>To</th>
        <th>Status</th>
        <th>Intent</th>
        <th>Intentlink</th>
        <th>Objectid</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($notifiers as $notifiers)
            <tr>
                <td>{{ $notifiers->Notification }}</td>
            <td>{{ $notifiers->From }}</td>
            <td>{{ $notifiers->To }}</td>
            <td>{{ $notifiers->Status }}</td>
            <td>{{ $notifiers->Intent }}</td>
            <td>{{ $notifiers->IntentLink }}</td>
            <td>{{ $notifiers->ObjectId }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['notifiers.destroy', $notifiers->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('notifiers.show', [$notifiers->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('notifiers.edit', [$notifiers->id]) }}"
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
