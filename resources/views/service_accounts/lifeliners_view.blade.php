@php
    use App\Models\ServiceAccounts;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Lifeliner Accounts</h4>
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
                <table class="table table-hover table-sm">
                    <thead>
                        <th>Account Number</th>
                        <th>Account Name</th>
                        <th>Address</th>
                        <th>Zone</th>
                        <th>Block</th>
                        <th>Start Date</th>
                        <th>Expiration Date</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($serviceAccounts as $item)
                            <tr>
                                <td><a href="{{ route('serviceAccounts.show', [$item->id]) }}">{{ $item->OldAccountNo }}</a></td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ ServiceAccounts::getAddress($item) }}</td>
                                <td>{{ $item->Zone }}</td>
                                <td>{{ $item->BlockCode }}</td>
                                <td>{{ $item->LifelinerDateApplied != null ? date('M d, Y', strtotime($item->LifelinerDateApplied)) : '' }}</td>
                                <td>{{ $item->LifelinerDateExpire != null ? date('M d, Y', strtotime($item->LifelinerDateExpire)) : ''}}</td>
                                <td class="text-right">
                                    <a target="_blank" href="{{ route('serviceAccounts.update-step-one', [$item->id]) }}"><i class="fas fa-pen"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection