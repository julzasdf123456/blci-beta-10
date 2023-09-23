<div class="table-responsive">
    <table class="table" id="rateItems-table">
        <thead>
        <tr>
            <th>Rateitem</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rateItems as $rateItems)
            <tr>
                <td>{{ $rateItems->RateItem }}</td>
            <td>{{ $rateItems->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['rateItems.destroy', $rateItems->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('rateItems.show', [$rateItems->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('rateItems.edit', [$rateItems->id]) }}"
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
