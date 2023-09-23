<div class="table-responsive">
    <table class="table" id="excemptions-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Serviceperiod</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($excemptions as $excemptions)
            <tr>
                <td>{{ $excemptions->AccountNumber }}</td>
            <td>{{ $excemptions->ServicePeriod }}</td>
            <td>{{ $excemptions->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['excemptions.destroy', $excemptions->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('excemptions.show', [$excemptions->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('excemptions.edit', [$excemptions->id]) }}"
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
