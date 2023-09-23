@php
    use App\Models\ServiceAccounts;
@endphp

<div class="row">
    <div class="col-lg-6">
        <span class="card-title" style="margin-left: 15px;">Accounts in this BAPA <i>(Press <strong>F3</strong> to Search)</i></span>         
    </div>

    <div class="col-lg-6">
        <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modal-search" style="margin-bottom: 10px; margin-right: 10px;">Add Account</button>    
    </div>

    <div class="col-lg-12">
        <table class="table table-hover table-sm table-bordered table-head-fixed text-nowrap">
            <thead>
                <th>Account ID</th>
                <th>Account No</th>
                <th>Service Account Name</th>
                <th>Address</th>
                <th>Route</th>
                <th>Status</th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($serviceAccounts as $item)
                    <tr>
                        <td><a href="{{ route('serviceAccounts.show', [$item->id]) }}">{{ $item->id }}</a></td>
                        <td>{{ $item->OldAccountNo }}</td>
                        <td>{{ $item->ServiceAccountName }}</td>
                        <td>{{ ServiceAccounts::getAddress($item) }}</td>
                        <td>{{ $item->AreaCode }}</td>
                        <td>{{ $item->AccountStatus }}</td>
                        <td class="text-right">
                            <button onclick="removeByAccount('{{ $item->id }}')" class="btn btn-sm btn-link text-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>     

