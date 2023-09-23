<div class="table-responsive">
    <table class="table" id="kwhSales-table">
        <thead>
        <tr>
            <th>Serviceperiod</th>
        <th>Town</th>
        <th>Billedkwh</th>
        <th>Consumedkwh</th>
        <th>Noofconsumers</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($kwhSales as $kwhSales)
            <tr>
                <td>{{ $kwhSales->ServicePeriod }}</td>
            <td>{{ $kwhSales->Town }}</td>
            <td>{{ $kwhSales->BilledKwh }}</td>
            <td>{{ $kwhSales->ConsumedKwh }}</td>
            <td>{{ $kwhSales->NoOfConsumers }}</td>
            <td>{{ $kwhSales->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['kwhSales.destroy', $kwhSales->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('kwhSales.show', [$kwhSales->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('kwhSales.edit', [$kwhSales->id]) }}"
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
