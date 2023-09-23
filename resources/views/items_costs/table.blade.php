<div class="table-responsive">
    <table class="table" id="itemsCosts-table">
        <thead>
        <tr>
            <th>Cst No</th>
        <th>Rrno</th>
        <th>It Code</th>
        <th>Ave Qty</th>
        <th>Qty</th>
        <th>Uom</th>
        <th>Cst</th>
        <th>Amt</th>
        <th>Rdate</th>
        <th>Rtime</th>
        <th>Categ</th>
        <th>Specs</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($itemsCosts as $itemsCost)
            <tr>
                <td>{{ $itemsCost->cst_no }}</td>
            <td>{{ $itemsCost->rrno }}</td>
            <td>{{ $itemsCost->it_code }}</td>
            <td>{{ $itemsCost->ave_qty }}</td>
            <td>{{ $itemsCost->qty }}</td>
            <td>{{ $itemsCost->uom }}</td>
            <td>{{ $itemsCost->cst }}</td>
            <td>{{ $itemsCost->amt }}</td>
            <td>{{ $itemsCost->rdate }}</td>
            <td>{{ $itemsCost->rtime }}</td>
            <td>{{ $itemsCost->categ }}</td>
            <td>{{ $itemsCost->specs }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['itemsCosts.destroy', $itemsCost->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('itemsCosts.show', [$itemsCost->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('itemsCosts.edit', [$itemsCost->id]) }}"
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
