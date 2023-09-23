<div class="table-responsive">
    <table class="table" id="preDefinedMaterials-table">
        <thead>
        <tr>
            <th>Neacode</th>
        <th>Quantity</th>
        <th>Options</th>
        <th>Applicationtype</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($preDefinedMaterials as $preDefinedMaterials)
            <tr>
                <td>{{ $preDefinedMaterials->NEACode }}</td>
            <td>{{ $preDefinedMaterials->Quantity }}</td>
            <td>{{ $preDefinedMaterials->Options }}</td>
            <td>{{ $preDefinedMaterials->ApplicationType }}</td>
            <td>{{ $preDefinedMaterials->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['preDefinedMaterials.destroy', $preDefinedMaterials->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('preDefinedMaterials.show', [$preDefinedMaterials->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('preDefinedMaterials.edit', [$preDefinedMaterials->id]) }}"
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
