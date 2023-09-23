<div class="table-responsive">
    <table class="table" id="collectibles-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Balance</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($collectibles as $collectibles)
            <tr>
                <td>{{ $collectibles->AccountNumber }}</td>
            <td>{{ $collectibles->Balance }}</td>
            <td>{{ $collectibles->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['collectibles.destroy', $collectibles->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('collectibles.show', [$collectibles->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('collectibles.edit', [$collectibles->id]) }}"
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
