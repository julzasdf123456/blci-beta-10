@php
    use App\Models\ServiceAccounts;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Coop Consumption Accounts</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- ACCOUNT DETAILS --}}
    <div class="col-lg-12">
      <table class="table table-hover table-sm table-bordered">
         <thead>
            <th>Account No.</th>
            <th>Account Name</th>
            <th>Address</th>
            <th>Account Type</th>
         </thead>
         <tbody>
            @foreach ($serviceAccounts as $item)
                <tr>
                  <td><a href="{{ route('serviceAccounts.show', [$item->id]) }}">{{ $item->OldAccountNo }}</a></td>
                  <td>{{ $item->ServiceAccountName }}</td>
                  <td>{{ ServiceAccounts::getAddress($item) }}</td>
                  <td>{{ $item->AccountType }}</td>
                </tr>
            @endforeach
         </tbody>
      </table>
    </div>
</div>
@endsection