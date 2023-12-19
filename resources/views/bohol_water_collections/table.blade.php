<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="bohol-water-collections-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Accountnumber</th>
                <th>Previousaccountnumber</th>
                <th>Consumername</th>
                <th>Ornumber</th>
                <th>Ordate</th>
                <th>Amountpaid</th>
                <th>Cash</th>
                <th>Particulars</th>
                <th>Checkno</th>
                <th>Checkdate</th>
                <th>Bankcode</th>
                <th>Checkamount</th>
                <th>Collector</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($boholWaterCollections as $boholWaterCollection)
                <tr>
                    <td>{{ $boholWaterCollection->id }}</td>
                    <td>{{ $boholWaterCollection->AccountNumber }}</td>
                    <td>{{ $boholWaterCollection->PreviousAccountNumber }}</td>
                    <td>{{ $boholWaterCollection->ConsumerName }}</td>
                    <td>{{ $boholWaterCollection->ORNumber }}</td>
                    <td>{{ $boholWaterCollection->ORDate }}</td>
                    <td>{{ $boholWaterCollection->AmountPaid }}</td>
                    <td>{{ $boholWaterCollection->Cash }}</td>
                    <td>{{ $boholWaterCollection->Particulars }}</td>
                    <td>{{ $boholWaterCollection->CheckNo }}</td>
                    <td>{{ $boholWaterCollection->CheckDate }}</td>
                    <td>{{ $boholWaterCollection->BankCode }}</td>
                    <td>{{ $boholWaterCollection->CheckAmount }}</td>
                    <td>{{ $boholWaterCollection->Collector }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['boholWaterCollections.destroy', $boholWaterCollection->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('boholWaterCollections.show', [$boholWaterCollection->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('boholWaterCollections.edit', [$boholWaterCollection->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $boholWaterCollections])
        </div>
    </div>
</div>
