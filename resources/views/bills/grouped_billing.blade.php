@php
    use App\Models\MemberConsumers;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Grouped Billing Console</h4>
            </div>
            <div class="col-sm-6">
                <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-select">Create Grouped Billing</button>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-8 offset-lg-2 col-md-12">
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Current Grouped Billings</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-sm table-hover">
                    <thead>
                        <th>Account Holder Name</th>
                        <th>No. of Accounts</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $item)
                            <tr>
                                <td>{{ MemberConsumers::serializeMemberName($item) }}</td>
                                <td>{{ $item->NoOfAccounts }}</td>
                                <td class="text-right">
                                    <a href="{{ route('bills.grouped-billing-view', [$item->MemberConsumerId]) }}" class="btn btn-sm btn-default">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- CONFIRMATION MODAL --}}
<div class="modal fade" id="modal-select" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Option</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <a href="{{ route('bills.create-group-billing-step-one-pre-select') }}" class="btn btn-primary float-right">From Existing Membership</a>
                    </div>
                    <div class="col-lg-6">
                        <a href="{{ route('bills.create-group-billing-step-one') }}" class="btn btn-warning">Create New Parent Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection