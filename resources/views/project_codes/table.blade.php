<div class="table-responsive">
    <table class="table" id="projectCodes-table">
        <thead>
        <tr>
            <th>Projectcode</th>
        <th>Projectdescription</th>
        <th>Projectcategory</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($projectCodes as $projectCodes)
            <tr>
                <td>{{ $projectCodes->ProjectCode }}</td>
            <td>{{ $projectCodes->ProjectDescription }}</td>
            <td>{{ $projectCodes->ProjectCategory }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['projectCodes.destroy', $projectCodes->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('projectCodes.show', [$projectCodes->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('projectCodes.edit', [$projectCodes->id]) }}"
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
