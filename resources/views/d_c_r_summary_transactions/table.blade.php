<div class="table-responsive">
    <table class="table" id="dCRSummaryTransactions-table">
        <thead>
        <tr>
            <th>Glcode</th>
        <th>Neacode</th>
        <th>Description</th>
        <th>Amount</th>
        <th>Day</th>
        <th>Time</th>
        <th>Teller</th>
        <th>Dcrnumber</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($dCRSummaryTransactions as $dCRSummaryTransactions)
            <tr>
                <td>{{ $dCRSummaryTransactions->GLCode }}</td>
            <td>{{ $dCRSummaryTransactions->NEACode }}</td>
            <td>{{ $dCRSummaryTransactions->Description }}</td>
            <td>{{ $dCRSummaryTransactions->Amount }}</td>
            <td>{{ $dCRSummaryTransactions->Day }}</td>
            <td>{{ $dCRSummaryTransactions->Time }}</td>
            <td>{{ $dCRSummaryTransactions->Teller }}</td>
            <td>{{ $dCRSummaryTransactions->DCRNumber }}</td>
            <td>{{ $dCRSummaryTransactions->Status }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['dCRSummaryTransactions.destroy', $dCRSummaryTransactions->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('dCRSummaryTransactions.show', [$dCRSummaryTransactions->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('dCRSummaryTransactions.edit', [$dCRSummaryTransactions->id]) }}"
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
