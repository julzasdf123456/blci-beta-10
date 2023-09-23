<div class="table-responsive">
    <table class="table" id="pendingBillAdjustments-table">
        <thead>
        <tr>
            <th>Readingid</th>
        <th>Kwhused</th>
        <th>Accountnumber</th>
        <th>Serviceperiod</th>
        <th>Confirmed</th>
        <th>Readdate</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($pendingBillAdjustments as $pendingBillAdjustments)
            <tr>
                <td>{{ $pendingBillAdjustments->ReadingId }}</td>
            <td>{{ $pendingBillAdjustments->KwhUsed }}</td>
            <td>{{ $pendingBillAdjustments->AccountNumber }}</td>
            <td>{{ $pendingBillAdjustments->ServicePeriod }}</td>
            <td>{{ $pendingBillAdjustments->Confirmed }}</td>
            <td>{{ $pendingBillAdjustments->ReadDate }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['pendingBillAdjustments.destroy', $pendingBillAdjustments->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('pendingBillAdjustments.show', [$pendingBillAdjustments->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('pendingBillAdjustments.edit', [$pendingBillAdjustments->id]) }}"
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
