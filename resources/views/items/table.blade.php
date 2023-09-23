<div class="table-responsive">
    <table class="table" id="items-table">
        <thead>
        <tr>
            <th>Itm Code</th>
        <th>Itm No</th>
        <th>Itm Desc</th>
        <th>Itm Specs</th>
        <th>Itm Uom</th>
        <th>Itm Aveqty</th>
        <th>Itm Cat</th>
        <th>Itm Yr</th>
        <th>Itm Date</th>
        <th>Itm Time</th>
        <th>Itm Pcode</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $items)
            <tr>
                <td>{{ $items->itm_code }}</td>
            <td>{{ $items->itm_no }}</td>
            <td>{{ $items->itm_desc }}</td>
            <td>{{ $items->itm_specs }}</td>
            <td>{{ $items->itm_uom }}</td>
            <td>{{ $items->itm_aveqty }}</td>
            <td>{{ $items->itm_cat }}</td>
            <td>{{ $items->itm_yr }}</td>
            <td>{{ $items->itm_date }}</td>
            <td>{{ $items->itm_time }}</td>
            <td>{{ $items->itm_pcode }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['items.destroy', $items->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('items.show', [$items->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('items.edit', [$items->id]) }}"
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
