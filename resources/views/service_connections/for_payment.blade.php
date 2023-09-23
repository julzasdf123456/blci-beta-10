@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 style="display: inline; margin-right: 15px;">Approved Inspections For Payment Monitor</h4>
                <i class="text-muted">All approved application inspections, reviewed for payment.</i>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
               <div class="card-header">
                  <span class="card-title"><i class="fas fa-check-circle ico-tab"></i>Approved Applications for Review</span>
               </div>
               <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-bordered table-sm">
                     <thead>
                        <th>ID</th>
                        <th>Account Name</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Inspector</th>
                        <th>Inspection Date</th>
                        <th>Total Amount Payable</th>
                        <th></th>
                     </thead>
                     <tbody>
                        @foreach ($data as $item)
                           <tr id="{{ $item->id }}">
                              <td><a href="{{ route('serviceConnections.show', [$item->id]) }}">{{ $item->id }}</a></td>
                              <td>{{ $item->ServiceAccountName }}</td>
                              <td>{{ ServiceConnections::getAddress($item) }}</td>
                              <td>{{ $item->Status }}</td>
                              <td>{{ $item->name }}</td>
                              <td>{{ $item->DateOfVerification != null ? date('M d, Y', strtotime($item->DateOfVerification)) : 'n/a' }}</td>
                              <td class="text-right">{{ is_numeric($item->OverAllTotal) ? number_format($item->OverAllTotal, 2) : $item->OverAllTotal }}</td>
                              <td class="text-right">
                                 @if ($item->OverAllTotal != null)
                                    <button class="btn btn-sm btn-success" onclick="approvePayment(`{{ $item->id }}`)"><i class="fas fa-check-circle ico-tab-mini"></i>Approve For Payment</button>
                                 @else
                                    <a class="btn btn-sm btn-warning" target="_blank" href="{{ route('serviceConnections.payment-order', [$item->id]) }}"><i class="fas fa-file-invoice-dollar ico-tab-mini"></i>Create Payment Order</a>
                                 @endif
                              </td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })

        function approvePayment(id) {
            $.ajax({
               url : "{{ route('serviceConnections.update-status') }}",
               type : "GET",
               data : {
                  id : id,
                  Status : 'For Payment',
               },
               success : function(res) {
                  $('#' + id).remove()
                  Toast.fire({
                     icon : 'success',
                     text : 'application approved for payment!'
                  })
               },
               error : function(err) {
                  console.log(err)
                  Toast.fire({
                     icon : 'error',
                     text : 'error approving payment!'
                  })
               }
            })
        }
    </script>
@endpush