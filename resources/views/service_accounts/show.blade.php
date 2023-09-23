@php
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-5">
                    <h4>Service Account Management Console 
                        @if ($serviceAccounts->CheckBounceHistory == 'Yes')
                            <span class="badge bg-danger">Bouncing Check History</span>
                        @endif                         
                    </h4>
                </div>
                <div class="col-sm-7">
                    @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Data Administrator'])) 
                        @if ($serviceAccounts->AccountStatus == 'APPREHENDED')
                        
                        @else
                            @if ($serviceAccounts->AccountStatus == 'PULLOUT')
                            
                            @else
                                <button class="btn btn-xs btn-danger float-right" style="margin-right: 5px;" title="Tag this account as Pulled-out" data-toggle="modal" data-target="#modal-pullout"><i class="fas fa-times ico-tab-mini"></i> Pull-out</button>
                            @endif

                            <button class="btn btn-xs btn-danger float-right" style="margin-right: 5px;" title="Apprehend This Account" data-toggle="modal" data-target="#modal-apprehend"><i class="fas fa-exclamation-circle ico-tab-mini"></i> Apprehend</button>
                            @if ($serviceAccounts->AccountStatus == 'DISCONNECTED')
                                <button class="btn btn-xs btn-success float-right" style="margin-right: 5px;" title="Disconnect This Account" data-toggle="modal" data-target="#modal-reconnect"><i class="fas fa-check ico-tab-mini"></i> Reconnect</button>
                            @elseif ($serviceAccounts->AccountStatus == 'ACTIVE')
                                <button class="btn btn-xs btn-danger float-right" style="margin-right: 5px;" title="Disconnect This Account" data-toggle="modal" data-target="#modal-disconnect"><i class="fas fa-unlink ico-tab-mini"></i> Disconnect</button>
                            @endif
                        @endif   
                        
                        @if ($serviceAccounts->CheckBounceHistory == 'Yes')
                        <button onclick="clearCheckBounce(`{{ $serviceAccounts->id }}`)" class="btn btn-xs btn-success float-right" style="margin-right: 15px;" title="Clear bounce check history"><i class="fas fa-exclamation-triangle ico-tab-mini"></i> Clear Bounced Check</button>
                        @else
                            <button onclick="markCheckBounce(`{{ $serviceAccounts->id }}`)" class="btn btn-xs btn-danger float-right" style="margin-right: 15px;" title="Mark this account with bouncing check history"><i class="fas fa-exclamation-triangle ico-tab-mini"></i> Bounced Check</button>
                        @endif
                        
                        
                        <a href="{{ route('serviceAccounts.update-step-one', [$serviceAccounts->id]) }}" class="btn btn-xs btn-warning float-right" style="margin-right: 30px;" title="Update Consumer Info"><i class="fas fa-pen ico-tab-mini"></i> Update</a>
                    @endif
                    
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="row">
            <div class="col-lg-4 col-md-5">
                @include('service_accounts.show_account_info')
            </div>

            <div class="col-lg-8 col-md-7">
                <div class="card shadow-none">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#billing-hist" data-toggle="tab">
                                <i class="fas fa-file-invoice"></i>
                                Billing History</a></li>
                            <li class="nav-item"><a class="nav-link" href="#disco-hist" data-toggle="tab">
                                <i class="fas fa-unlink"></i>
                                Disco/Reco History</a></li>
                            <li class="nav-item"><a class="nav-link" href="#arrears" data-toggle="tab">
                                <i class="fas fa-receipt"></i>
                                Arrears</a></li>
                            <li class="nav-item"><a class="nav-link" href="#prepayments" data-toggle="tab">
                                <i class="fas fa-piggy-bank"></i>
                                Pre-Payments/Deposits</a></li>
                            <li class="nav-item"><a class="nav-link" href="#technical" data-toggle="tab">
                                <i class="fas fa-car-battery"></i>
                                Technical Info</a></li>
                            <li class="nav-item"><a class="nav-link" href="#ticket-hist" data-toggle="tab">
                                <i class="fas fa-exclamation-triangle"></i>
                                Ticket History</a></li>
                        </ul>
                    </div>

                    <div class="card-body px-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="billing-hist">
                                @include('service_accounts.tab_billing_history')
                            </div>

                            <div class="tab-pane" id="disco-hist">
                                @include('service_accounts.tab_disconnection_history')
                            </div>

                            <div class="tab-pane" id="arrears">
                                @include('service_accounts.tab_arrears')
                            </div>
                            <div class="tab-pane" id="prepayments">
                                @include('service_accounts.tab_prepayments')
                            </div>

                            <div class="tab-pane" id="technical">
                               @include('service_accounts.tab_technical')
                            </div>
                            <div class="tab-pane" id="ticket-hist">
                                @include('service_accounts.tab_ticket_history')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection

{{-- DISCONNECT --}}
<div class="modal fade" id="modal-disconnect" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Disconnect This Account</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="DisconnectionDate">Disconnection Date</label>
                    <input type="text" name="DisconnectionDate" id="DisconnectionDate" value="" class="form-control">

                    <label for="DisconnectionTime">Disconnection Time</label>
                    <input type="text" name="DisconnectionTime" id="DisconnectionTime" value="" class="form-control">

                    <textarea type="text" name="Notes" id="Notes" value="" placeholder="Notes/Remarks" class="form-control" style="margin-top: 8px;" rows="3"></textarea>
                </div>

                @push('page_scripts')
                    <script type="text/javascript">
                        $('#DisconnectionDate').datetimepicker({
                            format: 'YYYY-MM-DD',
                            useCurrent: false,
                            sideBySide: true
                        })

                        $('#DisconnectionTime').datetimepicker({
                            format: 'hh:mm:ss',
                            useCurrent: false,
                            sideBySide: true
                        })
                    </script>
                @endpush
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="disconnect-proceed">Proceed</button>
            </div>
        </div>
    </div>
</div>

{{-- APPREHEND --}}
<div class="modal fade" id="modal-apprehend" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Apprehend This Account</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="ApprehensionDate">Apprehension Date</label>
                    <input type="text" name="ApprehensionDate" id="ApprehensionDate" value="" class="form-control">

                    <label for="ApprehensionTime">Apprehension Time</label>
                    <input type="text" name="ApprehensionTime" id="ApprehensionTime" value="" class="form-control">

                    <textarea type="text" name="Notes" id="ApprehensionNotes" value="" placeholder="Notes/Remarks" class="form-control" style="margin-top: 8px;" rows="3"></textarea>
                </div>

                @push('page_scripts')
                    <script type="text/javascript">
                        $('#ApprehensionDate').datetimepicker({
                            format: 'YYYY-MM-DD',
                            useCurrent: false,
                            sideBySide: true
                        })

                        $('#ApprehensionTime').datetimepicker({
                            format: 'hh:mm:ss',
                            useCurrent: false,
                            sideBySide: true
                        })
                    </script>
                @endpush
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="apprehend-proceed">Proceed</button>
            </div>
        </div>
    </div>
</div>

{{-- PULL OUT --}}
<div class="modal fade" id="modal-pullout" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pull-out This Account</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="PulloutDate">Pullout Date</label>
                    <input type="text" name="PulloutDate" id="PulloutDate" value="" class="form-control">

                    <label for="PulloutTime">Pullout Time</label>
                    <input type="text" name="PulloutTime" id="PulloutTime" value="" class="form-control">

                    <textarea type="text" name="PulloutNotes" id="PulloutNotes" value="" placeholder="Notes/Remarks" class="form-control" style="margin-top: 8px;" rows="3"></textarea>
                </div>

                @push('page_scripts')
                    <script type="text/javascript">
                        $('#PulloutDate').datetimepicker({
                            format: 'YYYY-MM-DD',
                            useCurrent: false,
                            sideBySide: true
                        })

                        $('#PulloutTime').datetimepicker({
                            format: 'hh:mm:ss',
                            useCurrent: false,
                            sideBySide: true
                        })
                    </script>
                @endpush
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="pullout-proceed">Proceed</button>
            </div>
        </div>
    </div>
</div>

{{-- RECONNECT --}}
<div class="modal fade" id="modal-reconnect" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reconnect This Account</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="ReconnectionDate">Reconnection Date</label>
                    <input type="text" name="ReconnectionDate" id="ReconnectionDate" value="" class="form-control">

                    <label for="ReconnectionTime">Reconnection Time</label>
                    <input type="text" name="ReconnectionTime" id="ReconnectionTime" value="" class="form-control">

                    <textarea type="text" name="Notes" id="Notes" value="" placeholder="Notes/Remarks" class="form-control" style="margin-top: 8px;" rows="3"></textarea>
                </div>

                @push('page_scripts')
                    <script type="text/javascript">
                        $('#ReconnectionDate').datetimepicker({
                            format: 'YYYY-MM-DD',
                            useCurrent: false,
                            sideBySide: true
                        })

                        $('#ReconnectionTime').datetimepicker({
                            format: 'hh:mm:ss',
                            useCurrent: false,
                            sideBySide: true
                        })
                    </script>
                @endpush
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="reconnect-proceed">Proceed</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#disconnect-proceed').on('click', function() {
                disconnect()
            })

            $('#apprehend-proceed').on('click', function() {
                apprehend()
            })

            $('#pullout-proceed').on('click', function() {
                pullout()
            })

            $('#reconnect-proceed').on('click', function() {
                reconnect()
            })
        })

        function reconnect() {
            $.ajax({
                url : "{{ route('serviceAccounts.reconnect-manual') }}",
                type : 'GET',
                data : {
                    id : "{{ $serviceAccounts->id }}",
                    Notes : $('#Notes').val(),
                    DateDisconnected : $('#ReconnectionDate').val(),
                    TimeDisconnected : $('#ReconnectionTime').val(),
                },
                success : function(res) {
                    location.reload()
                },
                error : function (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred while reconnecting this account!',
                    })
                }
            })
        }

        function disconnect() {
            $.ajax({
                url : "{{ route('serviceAccounts.disconnect-manual') }}",
                type : 'GET',
                data : {
                    id : "{{ $serviceAccounts->id }}",
                    Notes : $('#Notes').val(),
                    DateDisconnected : $('#DisconnectionDate').val(),
                    TimeDisconnected : $('#DisconnectionTime').val(),
                },
                success : function(res) {
                    location.reload()
                },
                error : function (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred while disconnecting this account!',
                    })
                }
            })
        }

        function apprehend() {
            $.ajax({
                url : "{{ route('serviceAccounts.apprehend-manual') }}",
                type : 'GET',
                data : {
                    id : "{{ $serviceAccounts->id }}",
                    Notes : $('#ApprehensionNotes').val(),
                    DateDisconnected : $('#ApprehensionDate').val(),
                    TimeDisconnected : $('#ApprehensionTime').val(),
                },
                success : function(res) {
                    location.reload()
                },
                error : function (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred while apprehending this account!',
                    })
                }
            })
        }

        function pullout() {
            $.ajax({
                url : "{{ route('serviceAccounts.pullout-manual') }}",
                type : 'GET',
                data : {
                    id : "{{ $serviceAccounts->id }}",
                    Notes : $('#PulloutNotes').val(),
                    DateDisconnected : $('#PulloutDate').val(),
                    TimeDisconnected : $('#PulloutTime').val(),
                },
                success : function(res) {
                    location.reload()
                },
                error : function (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred while pulling this account out!',
                    })
                }
            })
        }

        function markCheckBounce(accountNo) {
            Swal.fire({
                title: 'Bounce Check History',
                text : 'Does this account had a history of check payment bouncing?',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: `No`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('serviceAccounts.mark-bouncing-check') }}",
                        type : 'GET',
                        data : {
                            id : accountNo,
                        },
                        success : function(res) {
                            Toast.fire({
                                text : 'Account Tagged With Bouncing Check',
                                icon : 'success'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Swal.fire({
                                text : 'Error tagging account!',
                                icon : 'error'
                            })
                        }
                    })
                }
            })
        }

        function clearCheckBounce(accountNo) {
            Swal.fire({
                title: 'Clear Bounce Check History?',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: `No`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('serviceAccounts.clear-bouncing-check') }}",
                        type : 'GET',
                        data : {
                            id : accountNo,
                        },
                        success : function(res) {
                            Toast.fire({
                                text : 'Bouncing Check History Cleared',
                                icon : 'success'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Swal.fire({
                                text : 'Error clearing bounch check history!',
                                icon : 'error'
                            })
                        }
                    })
                }
            })
        }
    </script>
@endpush
