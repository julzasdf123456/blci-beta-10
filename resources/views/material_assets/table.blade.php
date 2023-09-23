<div class="table-responsive">
    <table class="table" id="materialAssets-table">
        <thead>
        <tr>
            <th>NEA Code</th>
            <th>Description</th>
            <th>Amount</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($materialAssets as $materialAssets)
            <tr>
                <td>{{ $materialAssets->id }}</td>
                <td>{{ $materialAssets->Description }}</td>
            <td>{{ $materialAssets->Amount }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['materialAssets.destroy', $materialAssets->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('materialAssets.show', [$materialAssets->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('materialAssets.edit', [$materialAssets->id]) }}"
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
