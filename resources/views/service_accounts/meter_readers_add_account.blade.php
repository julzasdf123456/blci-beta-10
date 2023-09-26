@php
    use App\Models\ServiceAccounts;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
   <div class="container-fluid">
       <div class="row mb-2">
           <div class="col-sm-6">
               <h4><strong>{{ $meterReader->name }}</strong> Day {{ $groupCode }} | Add Accounts</h4>
           </div>
       </div>
   </div>
</section>

<div class="row">
   {{-- DAYS --}}
   <div class="col-lg-5">
      <div class="card shadow-none">
         <div class="card-header">
            <input type="text" id="search" placeholder="Search account number or name" class="form-control" autofocus>
         </div>
         <div class="card-body table-responsive p-0">
            <table class="table table-hover table-sm table-bordered" id="res-table">
               <thead>
                  <th>Account No</th>
                  <th>Account Name</th>
                  <th>M.Reader</th>
                  <th></th>
               </thead>
               <tbody>

               </tbody>
            </table>
         </div>
      </div>
   </div>

   {{-- ACCOUNTS --}}
   <div class="col-lg-7">
      <div class="card shadow-none">
         <div class="card-header">
            <span class="card-title"><strong id="title">Accounts in this Group Schedule</strong></span>
         </div>
         <div class="card-body table-responsive p-0">
            <table class="table table-hover table-sm table-bordered">
               <thead>
                  <th>Account Number</th>
                  <th>Account Name</th>
                  <th>Address</th>
                  <th>Account Status</th>
               </thead>
               <tbody>
                  @foreach ($serviceAccounts as $item)
                      <tr>
                        <td><a href="{{ route('serviceAccounts.show', [$item->id]) }}">{{ $item->OldAccountNo }}</a></td>
                        <td>{{ $item->ServiceAccountName }}</td>
                        <td>{{ ServiceAccounts::getAddress($item) }}</td>
                        <td>{{ $item->AccountStatus }}</td>
                      </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

@endsection

@push('page_scripts')
    <script>
      var group = ""
      $(document).ready(function() {
         $('#search').keyup(function() {
            var word = this.value

            if (word.length > 4) {
               getAccounts(word)
            }
         })
      })

       function getAccounts(search) {
         $('#res-table tbody tr').remove()
         $.ajax({
            url : "{{ route('serviceAccounts.search-account-for-meter-reader') }}",
            type : "GET",
            data : {
               Search : search,
            },
            success : function(success) {
               $('#res-table tbody').append(success)
            },
            error : function(err) {
               Toast.fire({
                  icon : 'error',
                  text : 'Error getting accounts!'
               })
            }
         })
       }

       function changeMeterReader(accountId) {
         $.ajax({
            url : "{{ route('serviceAccounts.change-meter-reader') }}",
            type : 'GET',
            data : {
               AccountNumber : accountId,
               MeterReader : "{{ $meterReader->id }}",
               Group : "{{ $groupCode }}"
            },
            success : function(res) {
                $('#' + accountId).remove()
               Toast.fire({
                  icon : 'success',
                  text : 'Account added! Refresh to view.'
               })
            },
            error : function(err) {
               Swal.fire({
                  icon : 'error',
                  text : 'Error adding account!'
               })
            }
         })
       }
    </script>
@endpush

