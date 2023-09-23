<div class="table-responsive">
    <table class="table" id="accountNameHistories-table">
        <thead>
        <tr>
            <th>Accountnumber</th>
        <th>Oldaccountname</th>
        <th>Notes</th>
        <th>Userid</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($accountNameHistories as $accountNameHistory)
            <tr>
                <td>{{ $accountNameHistory->AccountNumber }}</td>
            <td>{{ $accountNameHistory->OldAccountName }}</td>
            <td>{{ $accountNameHistory->Notes }}</td>
            <td>{{ $accountNameHistory->UserId }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['accountNameHistories.destroy', $accountNameHistory->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('accountNameHistories.show', [$accountNameHistory->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('accountNameHistories.edit', [$accountNameHistory->id]) }}"
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
