<div class="table-responsive">
    <table class="table" id="katasNgVats-table">
        <thead>
        <tr>
            <th>Id</th>
        <th>Accountnumber</th>
        <th>Balance</th>
        <th>Seriesno</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($katasNgVats as $katasNgVat)
            <tr>
                <td>{{ $katasNgVat->id }}</td>
            <td>{{ $katasNgVat->AccountNumber }}</td>
            <td>{{ $katasNgVat->Balance }}</td>
            <td>{{ $katasNgVat->SeriesNo }}</td>
            <td>{{ $katasNgVat->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['katasNgVats.destroy', $katasNgVat->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('katasNgVats.show', [$katasNgVat->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('katasNgVats.edit', [$katasNgVat->id]) }}"
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
