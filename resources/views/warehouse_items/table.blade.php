<div class="table-responsive">
    <table class="table" id="warehouseItems-table">
        <thead>
        <tr>
            <th>Reqno</th>
        <th>Ent No</th>
        <th>Tdate</th>
        <th>Itemcd</th>
        <th>Ascode</th>
        <th>Qty</th>
        <th>Uom</th>
        <th>Cst</th>
        <th>Amt</th>
        <th>Itemno</th>
        <th>Rdate</th>
        <th>Rtime</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($warehouseItems as $warehouseItems)
            <tr>
                <td>{{ $warehouseItems->reqno }}</td>
            <td>{{ $warehouseItems->ent_no }}</td>
            <td>{{ $warehouseItems->tdate }}</td>
            <td>{{ $warehouseItems->itemcd }}</td>
            <td>{{ $warehouseItems->ascode }}</td>
            <td>{{ $warehouseItems->qty }}</td>
            <td>{{ $warehouseItems->uom }}</td>
            <td>{{ $warehouseItems->cst }}</td>
            <td>{{ $warehouseItems->amt }}</td>
            <td>{{ $warehouseItems->itemno }}</td>
            <td>{{ $warehouseItems->rdate }}</td>
            <td>{{ $warehouseItems->rtime }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['warehouseItems.destroy', $warehouseItems->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('warehouseItems.show', [$warehouseItems->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('warehouseItems.edit', [$warehouseItems->id]) }}"
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
