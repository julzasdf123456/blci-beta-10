<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="bohol-water-service-accounts-table">
            <thead>
            <tr>
                <th>Accountnumber</th>
                <th>Previousaccountnumber</th>
                <th>Consumername</th>
                <th>Connectiontype</th>
                <th>Meternumber</th>
                <th>Totalbill</th>
                <th>Waterbill</th>
                <th>Billspenalty</th>
                <th>Salescharge</th>
                <th>Salespenalty</th>
                <th>Othercharges</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($boholWaterServiceAccounts as $boholWaterServiceAccounts)
                <tr>
                    <td>{{ $boholWaterServiceAccounts->AccountNumber }}</td>
                    <td>{{ $boholWaterServiceAccounts->PreviousAccountNumber }}</td>
                    <td>{{ $boholWaterServiceAccounts->ConsumerName }}</td>
                    <td>{{ $boholWaterServiceAccounts->ConnectionType }}</td>
                    <td>{{ $boholWaterServiceAccounts->MeterNumber }}</td>
                    <td>{{ $boholWaterServiceAccounts->TotalBill }}</td>
                    <td>{{ $boholWaterServiceAccounts->WaterBill }}</td>
                    <td>{{ $boholWaterServiceAccounts->BillsPenalty }}</td>
                    <td>{{ $boholWaterServiceAccounts->SalesCharge }}</td>
                    <td>{{ $boholWaterServiceAccounts->SalesPenalty }}</td>
                    <td>{{ $boholWaterServiceAccounts->OtherCharges }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['boholWaterServiceAccounts.destroy', $boholWaterServiceAccounts->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('boholWaterServiceAccounts.show', [$boholWaterServiceAccounts->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('boholWaterServiceAccounts.edit', [$boholWaterServiceAccounts->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $boholWaterServiceAccounts])
        </div>
    </div>
</div>
