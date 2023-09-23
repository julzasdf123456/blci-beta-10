<div class="table-responsive">
    <table class="table" id="warehouseHeads-table">
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
        <th>Tot Amt</th>
        <th>Chkby</th>
        <th>Appby</th>
        <th>Stat</th>
        <th>Rdate</th>
        <th>Rtime</th>
        <th>Walk In</th>
        <th>Appl No</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($warehouseHeads as $warehouseHead)
            <tr>
                <td>{{ $warehouseHead->orderno }}</td>
            <td>{{ $warehouseHead->ent_no }}</td>
            <td>{{ $warehouseHead->misno }}</td>
            <td>{{ $warehouseHead->tdate }}</td>
            <td>{{ $warehouseHead->emp_id }}</td>
            <td>{{ $warehouseHead->ccode }}</td>
            <td>{{ $warehouseHead->dept }}</td>
            <td>{{ $warehouseHead->pcode }}</td>
            <td>{{ $warehouseHead->reqby }}</td>
            <td>{{ $warehouseHead->invoice }}</td>
            <td>{{ $warehouseHead->orno }}</td>
            <td>{{ $warehouseHead->purpose }}</td>
            <td>{{ $warehouseHead->serv_code }}</td>
            <td>{{ $warehouseHead->account_no }}</td>
            <td>{{ $warehouseHead->cust_name }}</td>
            <td>{{ $warehouseHead->tot_amt }}</td>
            <td>{{ $warehouseHead->chkby }}</td>
            <td>{{ $warehouseHead->appby }}</td>
            <td>{{ $warehouseHead->stat }}</td>
            <td>{{ $warehouseHead->rdate }}</td>
            <td>{{ $warehouseHead->rtime }}</td>
            <td>{{ $warehouseHead->walk_in }}</td>
            <td>{{ $warehouseHead->appl_no }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['warehouseHeads.destroy', $warehouseHead->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('warehouseHeads.show', [$warehouseHead->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('warehouseHeads.edit', [$warehouseHead->id]) }}"
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
