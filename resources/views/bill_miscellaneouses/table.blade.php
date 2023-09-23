<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="bill-miscellaneouses-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Balance</th>
                <th>Operation</th>
                <th>Status</th>
                <th>Terms</th>
                <th>Notes</th>
                <th>Enddate</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($billMiscellaneouses as $billMiscellaneous)
                <tr>
                    <td>{{ $billMiscellaneous->Name }}</td>
                    <td>{{ $billMiscellaneous->Description }}</td>
                    <td>{{ $billMiscellaneous->Balance }}</td>
                    <td>{{ $billMiscellaneous->Operation }}</td>
                    <td>{{ $billMiscellaneous->Status }}</td>
                    <td>{{ $billMiscellaneous->Terms }}</td>
                    <td>{{ $billMiscellaneous->Notes }}</td>
                    <td>{{ $billMiscellaneous->EndDate }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['billMiscellaneouses.destroy', $billMiscellaneous->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('billMiscellaneouses.show', [$billMiscellaneous->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('billMiscellaneouses.edit', [$billMiscellaneous->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $billMiscellaneouses])
        </div>
    </div>
</div>
