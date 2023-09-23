<div class="table-responsive">
    <table class="table" id="towns-table">
        <thead>
        <tr>
            <th>Town</th>
        <th>District</th>
        <th>Station</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($towns as $towns)
            <tr>
                <td>{{ $towns->Town }}</td>
            <td>{{ $towns->District }}</td>
            <td>{{ $towns->Station }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['towns.destroy', $towns->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('towns.show', [$towns->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('towns.edit', [$towns->id]) }}"
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
