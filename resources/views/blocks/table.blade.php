<div class="table-responsive">
    <table class="table" id="blocks-table">
        <thead>
        <tr>
            <th>Block</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($blocks as $blocks)
            <tr>
                <td>{{ $blocks->Block }}</td>
            <td>{{ $blocks->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['blocks.destroy', $blocks->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('blocks.show', [$blocks->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('blocks.edit', [$blocks->id]) }}"
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
