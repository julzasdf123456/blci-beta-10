<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="local-warehouse-heads-table">
            <thead>
            <tr>
                <th>Orderno</th>
                <th>Ent No</th>
                <th>Misno</th>
                <th>Tdate</th>
                <th>Emp Id</th>
                <th>Ccode</th>
                <th>Dept</th>
                <th>Pcode</th>
                <th>Reqby</th>
                <th>Invoice</th>
                <th>Orno</th>
                <th>Purpose</th>
                <th>Serv Code</th>
                <th>Account No</th>
                <th>Cust Name</th>
                <th>Tot Amnt</th>
                <th>Chkby</th>
                <th>Appby</th>
                <th>Stat</th>
                <th>Rdate</th>
                <th>Rtime</th>
                <th>Walk In</th>
                <th>Appl No</th>
                <th>Address</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($localWarehouseHeads as $localWarehouseHead)
                <tr>
                    <td>{{ $localWarehouseHead->orderno }}</td>
                    <td>{{ $localWarehouseHead->ent_no }}</td>
                    <td>{{ $localWarehouseHead->misno }}</td>
                    <td>{{ $localWarehouseHead->tdate }}</td>
                    <td>{{ $localWarehouseHead->emp_id }}</td>
                    <td>{{ $localWarehouseHead->ccode }}</td>
                    <td>{{ $localWarehouseHead->dept }}</td>
                    <td>{{ $localWarehouseHead->pcode }}</td>
                    <td>{{ $localWarehouseHead->reqby }}</td>
                    <td>{{ $localWarehouseHead->invoice }}</td>
                    <td>{{ $localWarehouseHead->orno }}</td>
                    <td>{{ $localWarehouseHead->purpose }}</td>
                    <td>{{ $localWarehouseHead->serv_code }}</td>
                    <td>{{ $localWarehouseHead->account_no }}</td>
                    <td>{{ $localWarehouseHead->cust_name }}</td>
                    <td>{{ $localWarehouseHead->tot_amnt }}</td>
                    <td>{{ $localWarehouseHead->chkby }}</td>
                    <td>{{ $localWarehouseHead->appby }}</td>
                    <td>{{ $localWarehouseHead->stat }}</td>
                    <td>{{ $localWarehouseHead->rdate }}</td>
                    <td>{{ $localWarehouseHead->rtime }}</td>
                    <td>{{ $localWarehouseHead->walk_in }}</td>
                    <td>{{ $localWarehouseHead->appl_no }}</td>
                    <td>{{ $localWarehouseHead->address }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['localWarehouseHeads.destroy', $localWarehouseHead->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('localWarehouseHeads.show', [$localWarehouseHead->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('localWarehouseHeads.edit', [$localWarehouseHead->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $localWarehouseHeads])
        </div>
    </div>
</div>
