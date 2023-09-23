<div class="table-responsive">
    <table class="table" id="katasNgVatTotals-table">
        <thead>
        <tr>
            <th>Balance</th>
        <th>Seriesno</th>
        <th>Description</th>
        <th>Year</th>
        <th>Userid</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($katasNgVatTotals as $katasNgVatTotal)
            <tr>
                <td>{{ $katasNgVatTotal->Balance }}</td>
            <td>{{ $katasNgVatTotal->SeriesNo }}</td>
            <td>{{ $katasNgVatTotal->Description }}</td>
            <td>{{ $katasNgVatTotal->Year }}</td>
            <td>{{ $katasNgVatTotal->UserId }}</td>
            <td>{{ $katasNgVatTotal->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['katasNgVatTotals.destroy', $katasNgVatTotal->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('katasNgVatTotals.show', [$katasNgVatTotal->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('katasNgVatTotals.edit', [$katasNgVatTotal->id]) }}"
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
