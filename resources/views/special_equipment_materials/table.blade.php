<div class="table-responsive">
    <table class="table" id="specialEquipmentMaterials-table">
        <thead>
        <tr>
            <th>Neacode</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($specialEquipmentMaterials as $specialEquipmentMaterials)
            <tr>
                <td>{{ $specialEquipmentMaterials->NEACode }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['specialEquipmentMaterials.destroy', $specialEquipmentMaterials->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('specialEquipmentMaterials.show', [$specialEquipmentMaterials->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('specialEquipmentMaterials.edit', [$specialEquipmentMaterials->id]) }}"
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
