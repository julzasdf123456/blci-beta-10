<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="local-warehouse-items-table">
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
                <th>Amnt</th>
                <th>Itemno</th>
                <th>Rdate</th>
                <th>Rtime</th>
                <th>Salesprice</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($localWarehouseItems as $localWarehouseItems)
                <tr>
                    <td>{{ $localWarehouseItems->reqno }}</td>
                    <td>{{ $localWarehouseItems->ent_no }}</td>
                    <td>{{ $localWarehouseItems->tdate }}</td>
                    <td>{{ $localWarehouseItems->itemcd }}</td>
                    <td>{{ $localWarehouseItems->ascode }}</td>
                    <td>{{ $localWarehouseItems->qty }}</td>
                    <td>{{ $localWarehouseItems->uom }}</td>
                    <td>{{ $localWarehouseItems->cst }}</td>
                    <td>{{ $localWarehouseItems->amnt }}</td>
                    <td>{{ $localWarehouseItems->itemno }}</td>
                    <td>{{ $localWarehouseItems->rdate }}</td>
                    <td>{{ $localWarehouseItems->rtime }}</td>
                    <td>{{ $localWarehouseItems->salesprice }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['localWarehouseItems.destroy', $localWarehouseItems->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('localWarehouseItems.show', [$localWarehouseItems->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('localWarehouseItems.edit', [$localWarehouseItems->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $localWarehouseItems])
        </div>
    </div>
</div>
