<div class="table-responsive">
    <table class="table" id="spanningIndices-table">
        <thead>
        <tr>
            <th>Neacode</th>
        <th>Structure</th>
        <th>Description</th>
        <th>Size</th>
        <th>Type</th>
        <th>Spliceneacode</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($spanningIndices as $spanningIndex)
            <tr>
                <td>{{ $spanningIndex->NeaCode }}</td>
            <td>{{ $spanningIndex->Structure }}</td>
            <td>{{ $spanningIndex->Description }}</td>
            <td>{{ $spanningIndex->Size }}</td>
            <td>{{ $spanningIndex->Type }}</td>
            <td>{{ $spanningIndex->SpliceNeaCode }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['spanningIndices.destroy', $spanningIndex->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('spanningIndices.show', [$spanningIndex->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('spanningIndices.edit', [$spanningIndex->id]) }}"
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
