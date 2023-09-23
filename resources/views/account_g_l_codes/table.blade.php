<div class="table-responsive">
    <table class="table" id="accountGLCodes-table">
        <thead>
        <tr>
            <th>Accountcode</th>
        <th>Neacode</th>
        <th>Status</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($accountGLCodes as $accountGLCodes)
            <tr>
                <td>{{ $accountGLCodes->AccountCode }}</td>
            <td>{{ $accountGLCodes->NEACode }}</td>
            <td>{{ $accountGLCodes->Status }}</td>
            <td>{{ $accountGLCodes->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['accountGLCodes.destroy', $accountGLCodes->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('accountGLCodes.show', [$accountGLCodes->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('accountGLCodes.edit', [$accountGLCodes->id]) }}"
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
