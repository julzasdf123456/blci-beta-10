<div class="table-responsive">
    <table class="table" id="costCenters-table">
        <thead>
        <tr>
            <th>Costcode</th>
        <th>Costname</th>
        <th>Costdepartment</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($costCenters as $costCenters)
            <tr>
                <td>{{ $costCenters->CostCode }}</td>
            <td>{{ $costCenters->CostName }}</td>
            <td>{{ $costCenters->CostDepartment }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['costCenters.destroy', $costCenters->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('costCenters.show', [$costCenters->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('costCenters.edit', [$costCenters->id]) }}"
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
