<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="customer-deposit-logs-table">
            <thead>
            <tr>
                <th>Accountnumber</th>
                <th>Logdetails</th>
                <th>Userid</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($customerDepositLogs as $customerDepositLogs)
                <tr>
                    <td>{{ $customerDepositLogs->AccountNumber }}</td>
                    <td>{{ $customerDepositLogs->LogDetails }}</td>
                    <td>{{ $customerDepositLogs->UserId }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['customerDepositLogs.destroy', $customerDepositLogs->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('customerDepositLogs.show', [$customerDepositLogs->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('customerDepositLogs.edit', [$customerDepositLogs->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $customerDepositLogs])
        </div>
    </div>
</div>
