@php
    use App\Models\ServiceAccounts;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Change Name Confirmation</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- ACCOUNT DETAILS --}}
    <div class="col-lg-7">
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title">Original Account Details</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm">
                    <tr>
                        <td>Account No</td>
                        <th><a href="{{ $serviceAccount != null ? route('serviceAccounts.show', [$serviceAccount->id]) : '' }}">{{ $serviceAccount != null ? $serviceAccount->OldAccountNo : '' }}</a></th>
                    </tr>
                    <tr>
                        <td>Account Name</td>
                        <th>{{ $serviceAccount != null ? $serviceAccount->ServiceAccountName : '' }}</th>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <th>{{ $serviceAccount != null ? ServiceAccounts::getAddress($serviceAccount) : '' }}</th>
                    </tr>
                    <tr>
                        <td>Route</td>
                        <th>{{ $serviceAccount != null ? $serviceAccount->AreaCode : '' }}</th>
                    </tr>
                    <tr>
                        <td>Reason for Change</td>
                        <th>{{ $serviceConnection != null ? $serviceConnection->Notes : '' }}</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- COFIRMATION --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header bg-primary">
                <span class="card-title">Confirmation</span>
            </div>
            <div class="card-body">
                {!! Form::open(['route' => 'serviceAccounts.update-name']) !!}

                <div class="form-group">
                    <label for="">Change Name To</label>
                    <input type="text" class="form-control" name="NewName" value={{ $serviceConnection != null ? $serviceConnection->OrganizationAccountNumber : '' }}>
                </div>

                <input type="hidden" name="id" value="{{ $serviceAccount->id }}">

                <input type="hidden" name="Notes" value="{{ $serviceConnection->Notes }}">

                <input type="hidden" name="ServiceConnectionId" value="{{ $serviceConnection->id }}">

                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-check-circle ico-tab-mini"></i>Confirm</button>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection