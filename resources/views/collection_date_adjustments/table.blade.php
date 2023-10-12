<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="collection-date-adjustments-table">
            <thead>
            <tr>
                <th>Userid</th>
                <th>Dateassigned</th>
                <th>Notes</th>
                <th>Status</th>
                <th>Assignedby</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($collectionDateAdjustments as $collectionDateAdjustments)
                <tr>
                    <td>{{ $collectionDateAdjustments->UserId }}</td>
                    <td>{{ $collectionDateAdjustments->DateAssigned }}</td>
                    <td>{{ $collectionDateAdjustments->Notes }}</td>
                    <td>{{ $collectionDateAdjustments->Status }}</td>
                    <td>{{ $collectionDateAdjustments->AssignedBy }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['collection-date-adjustments.destroy', $collectionDateAdjustments->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('collection-date-adjustments.show', [$collectionDateAdjustments->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('collection-date-adjustments.edit', [$collectionDateAdjustments->id]) }}"
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

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $collectionDateAdjustments])
        </div>
    </div>
</div>
