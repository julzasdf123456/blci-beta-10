<div class="table-responsive">
    <table class="table" id="serviceConnectionMatPayables-table">
        <thead>
        <tr>
            <th>Material</th>
        <th>Rate</th>
        <th>Building Type</th>
        <th>VAT Percentage</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnectionMatPayables as $serviceConnectionMatPayables)
            <tr>
                <td>{{ $serviceConnectionMatPayables->Material }}</td>
            <td>{{ $serviceConnectionMatPayables->Rate }}</td>
            <td>{{ $serviceConnectionMatPayables->BuildingType }}</td>
            <td>{{ $serviceConnectionMatPayables->VatPercentage }}</td>
            <td>{{ $serviceConnectionMatPayables->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnectionMatPayables.destroy', $serviceConnectionMatPayables->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnectionMatPayables.show', [$serviceConnectionMatPayables->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnectionMatPayables.edit', [$serviceConnectionMatPayables->id]) }}"
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
