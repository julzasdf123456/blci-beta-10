<div class="table-responsive">
    <table class="table" id="poleIndices-table">
        <thead>
        <tr>
            <th>Neacode</th>
        <th>Type</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($poleIndices as $poleIndex)
            <tr>
                <td>{{ $poleIndex->NEACode }}</td>
            <td>{{ $poleIndex->Type }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['poleIndices.destroy', $poleIndex->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('poleIndices.show', [$poleIndex->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('poleIndices.edit', [$poleIndex->id]) }}"
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
