@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>Applications for Turn-on Approval</h4>
                <i class="text-muted">Paid applications to be approved for turn-on/energization issuance.</i>
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
                        <th>OR Number</th>
                        <th>Payment Date</th>
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
                              <td class="v-align">{{ $item->ORNumber }}</td>
                              <td class="v-align">{{ $item->ORDate != null ? date('M d, Y', strtotime($item->ORDate)) : 'n/a' }}</td>
                              <td class="text-right v-align" style="min-width: 150px;">
                                <button id="comments-{{ $item->id }}" class="btn btn-sm btn-default" onclick="viewComments(`{{ $item->id }}`)" title="View Comments"><i class="fas fa-comments"></i> ({{ $item->CommentCount }})</button>
                                <button class="btn btn-sm btn-warning" onclick="comment(`{{ $item->id }}`, `{{ $item->CommentCount }}`)" title="Add Comment"><i class="fas fa-comment"></i></button>
                                <button class="btn btn-sm btn-success" onclick="approveTurnOn(`{{ $item->id }}`)"><i class="fas fa-check-circle ico-tab-mini"></i>Approve</button>
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

@include('service_connections.modal_comments')

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })

        function approveTurnOn(id) {
            Swal.fire({
                title: "Approve Turn-On Order?",
                text : 'This application will be forwarded to the energization section for assigning of crew. Proceed to aprove.',
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
                            Status : 'Approved for Energization'
                        },
                        success : function(res) {
                            $('#' + id).remove()
                            Toast.fire({
                                icon : 'success',
                                text : 'Application approved for Turn-on order issuance!'
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

        
        function comment(id, count) {
            (async () => {
                const { value: text } = await Swal.fire({
                    input: 'textarea',
                    inputPlaceholder: 'Type your remarks here...',
                    inputAttributes: {
                        'aria-label': 'Type your remarks here'
                    },
                    text : 'Provide comment/remarks/notes.',
                    showCancelButton: true
                })

                if (text) {
                    $.ajax({
                        url : "{{ route('serviceConnectionComments.store') }}",
                        type : "POST",
                        data : {
                            _token : "{{ csrf_token() }}",
                            ServiceConnectionId : id,
                            Comments : text, 
                            UserId : "{{ Auth::id() }}"
                        }, 
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Comment added!'
                            })      
                            
                            $('#comments-' + id).html('<i class="fas fa-comments"></i> (' + (parseInt(count) + 1) + ')')
                        },
                        error : function(err) {
                            console.log(err)
                            Swal.fire({
                                icon : 'error',
                                text : 'Error adding comment!'
                            })
                        }
                    })
                }
            })()
        }

        function viewComments(id) {
            $('#modal-comments').modal('show')

            $('#comment-results tbody tr').remove()

            $.ajax({
                url : "{{ route('serviceConnectionComments.get-comments') }}",
                type : "GET",
                data : {
                    ServiceConnectionId : id,
                },
                success : function(res) {
                    if (!isNull(res)) {
                        $.each(res, function(index, el) {
                            $('#comment-results tbody').append(`
                                <tr>
                                    <td class='v-align py-3' style='width: 35px;'>
                                        <i class='fas fa-comment'></i>
                                    </td>
                                    <td class='v-align'>
                                        ` + res[index]['Comments'] + `
                                        <br>
                                        <span class='text-muted' style='font-size: 0.8em;'><i class='fas fa-user-circle ico-tab-mini'></i>` + res[index]['name'] + ` | ` + moment(res[index]['created_at']).format("MMM DD, YYYY @ h:m A") + `</span>
                                    </td>    
                                </tr>
                            `)
                        })
                    }
                },
                error : function(err) {
                    console.log(err)
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting comments'
                    })
                }
            })
        }
    </script>
@endpush