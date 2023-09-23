<div class="table-responsive">
    <table class="table" id="memberConsumerTypes-table">
        <thead>
        <tr>
            <th>Type</th>
        <th>Description</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($memberConsumerTypes as $memberConsumerTypes)
            <tr>
                <td>{{ $memberConsumerTypes->Type }}</td>
            <td>{{ $memberConsumerTypes->Description }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['memberConsumerTypes.destroy', $memberConsumerTypes->Id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('memberConsumerTypes.show', [$memberConsumerTypes->Id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('memberConsumerTypes.edit', [$memberConsumerTypes->Id]) }}"
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
