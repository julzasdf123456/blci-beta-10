<div class="table-responsive">
    <table class="table" id="readingImages-table">
        <thead>
        <tr>
            <th>Photo</th>
        <th>Readingid</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($readingImages as $readingImages)
            <tr>
                <td>{{ $readingImages->Photo }}</td>
            <td>{{ $readingImages->ReadingId }}</td>
            <td>{{ $readingImages->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['readingImages.destroy', $readingImages->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('readingImages.show', [$readingImages->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('readingImages.edit', [$readingImages->id]) }}"
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
