<div class="table-responsive">
    <table class="table" id="serviceAppliedFors-table">
        <thead>
        <tr>
            <th>Serviceappliedfor</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceAppliedFors as $serviceAppliedFor)
            <tr>
                <td>{{ $serviceAppliedFor->ServiceAppliedFor }}</td>
            <td>{{ $serviceAppliedFor->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceAppliedFors.destroy', $serviceAppliedFor->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceAppliedFors.show', [$serviceAppliedFor->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceAppliedFors.edit', [$serviceAppliedFor->id]) }}"
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
