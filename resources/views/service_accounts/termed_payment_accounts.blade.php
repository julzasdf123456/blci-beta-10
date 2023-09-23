@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Termed Payment Accounts</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card" style="height: 80vh;">
            <div class="card-header border-0">
                <span class="card-title">Press <strong>F3</strong> to Search</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table">
                    <thead>
                        <th>Account Number</th>
                        <th>Account Name</th>
                        <th>Account ID</th>
                    </thead>
                    <tbody>
                        @foreach ($termedAccounts as $item)
                            <tr>
                                <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ $item->AccountNumber }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection