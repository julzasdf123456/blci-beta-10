@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Applications for Payment Approval</h4>
                <i class="text-muted">Inspection-approved applications to be approved for payment.</i>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
               <div class="card-header">
                  <span class="card-title"><i class="fas fa-check-circle ico-tab"></i>Paid Applications for Turn-on Approvals</span>
               </div>
               <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-bordered table-sm">
                     <thead>
                        <th>ID</th>
                        <th>Account Name</th>
                        <th class="text-center">Status</th>
                        <th>Inspector</th>
                        <th>Inspection Date</th>
                        <td></td>
                     </thead>
                     <tbody>
                        @foreach ($data as $item)
                           <tr id="{{ $item->id }}">
                              <td class="v-align"><a target="_blank" href="{{ route('serviceConnections.show', [$item->id]) }}">{{ $item->id }}</a></td>
                              <td class="v-align" style="min-width: 180px;">
                                 <strong>{{ $item->ServiceAccountName }}</strong><br>
                                 <span class="text-muted">{{ ServiceConnections::getAddress($item) }}</span>
                              </td>
                              <td class="text-center v-align"><span class="badge bg-success">{{ $item->Status }}</span></td>
                              <td class="v-align">{{ $item->name }}</td>
                              <td class="v-align">{{ $item->DateOfVerification != null ? date('M d, Y', strtotime($item->DateOfVerification)) : 'n/a' }}</td>
                              <td class="text-right v-align" style="min-width: 150px;">
                                 <button class="btn btn-sm btn-success" onclick="approveForPayment(`{{ $item->id }}`)"><i class="fas fa-check-circle ico-tab-mini"></i>Approve</button>
                              </td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
               <div class="card-footer"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })

        function approveForPayment(id) {
            Swal.fire({
                title: "Approve for Payment?",
                showCancelButton: true,
                confirmButtonText: "Approve",
                confirmButtonColor : "{{ env('SUCCESS') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('serviceConnections.update-status') }}",
                        type : 'GET',
                        data : {
                            id : id,
                            Status : 'Payment Approved'
                        },
                        success : function(res) {
                            $('#' + id).remove()
                            Toast.fire({
                                icon : 'success',
                                text : 'Application approved for payment!'
                            })
                        },
                        error : function(err) {
                            console.log(err)
                            Toast.fire({
                            icon : 'error',
                            text : 'error approving application'
                            })
                        }
                    })
                }
            });
        }
    </script>
@endpush