@php
    use App\Models\ServiceAccounts;
@endphp
<table class="table table-hover table-sm">
    <thead>
        <th>Account ID</th>
        <th>Legacy Account No.</th>
        <th>Service Account Name</th>
        <th>Address</th>
        <th>Account Type</th>
        <th>Status</th>
        <th>Meter Number</th>
        <th></th>
    </thead>
    <tbody>
        @foreach ($serviceAccounts as $item)
            <tr>
                <td>
                    <a href="{{ route('serviceAccounts.show', [$item->id]) }}">
                        {{ $item->id }}
                    </a>
                </td>
                <td>{{ $item->OldAccountNo != null ? $item->OldAccountNo : '-' }}</td>
                <td>{{ $item->ServiceAccountName }} {{ $item->AccountCount != null ? '(# ' . $item->AccountCount . ')' : '' }}</td>                
                <td>{{ ServiceAccounts::getAddress($item) }}</td>
                <td>{{ $item->AccountType }}</td>
                <td>{{ $item->AccountStatus }}</td>
                <td>{{ $item->MeterNumber != null ? $item->MeterNumber : '-' }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceAccounts.destroy', $item->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceAccounts.show', [$item->id]) }}"
                           class='btn btn-primary btn-xs'>
                            <i class="far fa-eye ico-tab-mini"></i>View
                        </a>
                        {{-- <a href="{{ route('serviceAccounts.edit', [$item->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!} --}}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
            
        @endforeach
    </tbody>
</table>

{{ $serviceAccounts->links() }}