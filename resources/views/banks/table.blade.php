<div class="table-responsive">
    <table class="table" id="banks-table">
        <thead>
        <tr>
            <th>Bankfullname</th>
        <th>Bankabbrev</th>
        <th>Address</th>
        <th>Tin</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($banks as $banks)
            <tr>
                <td>{{ $banks->BankFullName }}</td>
            <td>{{ $banks->BankAbbrev }}</td>
            <td>{{ $banks->Address }}</td>
            <td>{{ $banks->TIN }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['banks.destroy', $banks->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('banks.show', [$banks->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('banks.edit', [$banks->id]) }}"
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
