@php
    use App\Models\ServiceAccounts;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
   <div class="container-fluid">
       <div class="row mb-2">
           <div class="col-sm-6">
               <h4><strong>{{ $meterReader->name }}</strong> | Console</h4>
           </div>
       </div>
   </div>
</section>

<div class="row">
   {{-- DAYS --}}
   <div class="col-lg-2">
      <div class="card shadow-none">
         <div class="card-header">
            <span class="card-title">Day/Group Schedule</span>
         </div>
         <div class="card-body table-responsive p-0">
            <table class="table table-hover">
               <tbody>
                  @foreach ($groupings as $item)
                      <tr>
                        <td onclick="getAccounts('{{ $item->GroupCode }}')"><strong>Day {{ $item->GroupCode }}</strong></td>
                      </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>

   {{-- ACCOUNTS --}}
   <div class="col-lg-10">
      <div class="card shadow-none">
         <div class="card-header">
            <span class="card-title"><strong id="title">...</strong></span>
            <span class="card-tools">
               <button id="add-account" class="btn btn-sm btn-primary gone">Add Account</button>
            </span>
         </div>
         <div class="card-body table-responsive p-0">
            <table class="table table-hover table-sm table-bordered" id="res-table">
               <thead>
                  <th>#</th>
                  <th>Account Number</th>
                  <th>Account Name</th>
                  <th>Address</th>
                  <th>Account Status</th>
                  <th>Zone</th>
                  <th>Block</th>
               </thead>
               <tbody>

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
         $('#add-account').on('click', function() {
            window.location.href = "{{ url('/service_accounts/meter-readers-add-account') }}/{{ $meterReader->id }}/" + group
         })
      })

       function getAccounts(groupCode) {
         group = groupCode
         $('#res-table tbody tr').remove()
         $('#add-account').removeClass('gone')
         $('#title').text('Accounts on Day ' + groupCode)
         $.ajax({
            url : "{{ route('serviceAccounts.get-accounts-by-meter-reader') }}",
            type : "GET",
            data : {
               Day : groupCode,
               MeterReader : "{{ $meterReader->id }}"
            },
            success : function(success) {
               $('#res-table tbody').append(success)
            },
            error : function(err) {
               Swal.fire({
                  icon : 'error',
                  text : 'Error getting accounts!'
               })
            }
         })
       }
    </script>
@endpush

