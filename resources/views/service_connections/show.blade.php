<?php

use App\Models\ServiceConnections;
use Illuminate\Support\Facades\Auth;

?>

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <p class="badge-md bg-warning" style="display: inline;"><strong>{{ $serviceConnections->Status }}</strong></p>
                    {{-- DELETE --}}
                    <div class="dropdown" style="display: inline;">
                        <a class="btn btn-default btn-sm dropdown-toggle float-right" href="#" id="more-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        More
                        </a>
                        <div class="dropdown-menu" aria-labelledby="more-menu">
                            {!! Form::open(['route' => ['serviceConnections.destroy', $serviceConnections->id], 'method' => 'delete']) !!}
                                {!! Form::button('<i class="fas fa-trash ico-tab"></i> Delete', ['type' => 'submit', 'title' => 'Delete this application', 'class' => 'dropdown-item', 'onclick' => "return confirm('Are you sure you want to delete this?')"]) !!}
                            {!! Form::close() !!}
                            {{-- <div class="dropdown-divider"></div> --}}
                        </div>
                    </div>
                    
                    {{-- PAYMENT ORDER --}}
                    {{-- DELETE --}}
                    <div class="dropdown" style="display: inline;">
                        <a class="btn btn-default btn-sm dropdown-toggle float-right" style="margin-right: 5px;" href="#" id="payment-order-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Payment Order
                        </a>
                        <div class="dropdown-menu" aria-labelledby="payment-order-menu">
                            @if($paymentOrder == null)
                                <a href="{{ route('serviceConnections.payment-order', [$serviceConnections->id]) }}" class="dropdown-item" title="Create Payment Order">
                                    <i class="fas fa-file-invoice-dollar ico-tab"></i>Create Payment Order</a>
                            @else
                                <a href="{{ route('serviceConnections.update-payment-order', [$serviceConnections->id]) }}" class="dropdown-item" title="Edit Payment Order">
                                    <i class="fas fa-file-invoice-dollar ico-tab"></i>Edit Payment Order</a>

                                <a href="{{ route('serviceConnections.print-payment-order', [$serviceConnections->id]) }}" class="dropdown-item" title="Print Payment Order Stub">
                                    <i class="fas fa-print ico-tab"></i>Print Payment Order Stub</a>
                                    
                                <a href="{{ route('serviceConnections.print-order-materials', [$serviceConnections->id]) }}" class="dropdown-item" title="Print Payment Order Materials">
                                    <i class="fas fa-print ico-tab"></i>Print Order - Materials</a>

                                <a href="{{ route('serviceConnections.print-order-meters', [$serviceConnections->id]) }}" class="dropdown-item" title="Print Payment Order Materials">
                                    <i class="fas fa-print ico-tab"></i>Print Order - Meter</a>
    
                                <button onclick="revalidatePayment(`{{ $serviceConnections->id }}`)" class="dropdown-item" title="Re-flush payment order materials to warehouse database">
                                    <i class="fas fa-file-invoice-dollar ico-tab"></i>Revalidate Payment Order Materials</button>
                            @endif
                            {{-- <div class="dropdown-divider"></div> --}}
                        </div>
                    </div>
                    
                    {{-- EDIT --}}
                    <a href="{{ route('serviceConnections.edit', [$serviceConnections->id]) }}" style="margin-right: 5px;" title="Update Application Details" class="btn btn-sm btn-primary float-right"><i class="fas fa-pen ico-tab-mini"></i>Edit Application</a>
                </div>
            </div>
        </div>
    </section>
    <meta name="scId" content="{{ $serviceConnections->id }}">

    <div class="content px-1">
        <div class="row">
            {{-- HEADER DETAILS --}}
            <div class="col-lg-12">
                <div class="card shadow-none">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="p-x0 text-primary">{{ $serviceConnections->ServiceAccountName }}</h4>
                                <p class="p-x0">{{ strtoupper(ServiceConnections::getAddress($serviceConnections)) }}</p>
                                <p class="p-x0"><span class="text-muted">Account Number: </span><strong>{{ $serviceConnections->AccountNumber }}</strong></p>
                            </div>
                            <div class="col-lg-6">
                                <p class="p-x0"><span class="text-muted">Application No: </span>{{ $serviceConnections->id }}</p>
                                <p class="p-x0"><span class="text-muted">Contact No:  </span> <span style="margin-left: 20px;">{{ $serviceConnections->ContactNumber }}</span></p>
                                <p class="p-x0"><span class="text-muted">Email Add.:  </span> <span style="margin-left: 20px;">{{ $serviceConnections->EmailAddress }}</span></p>
                                <p class="px-0">
                                    @if (Auth::user()->hasAnyRole(['Administrator'])) 
                                        <button id="override" class="btn btn-danger btn-sm float-right" style="margin-left: 10px;">Override Status</button>
                                        <select name="Status" id="Status" class="form-control form-control-sm float-right" style="width: 200px;">
                                            <option {{ $serviceConnections->Status=="Approved" ? 'selected' : '' }} value="Approved">Approved</option>
                                            <option {{ $serviceConnections->Status=="Payment Approved" ? 'selected' : '' }} value="Payment Approved">Payment Approved</option>
                                            <option {{ $serviceConnections->Status=="Approved for Energization" ? 'selected' : '' }} value="Approved for Energization">Approved for Energization</option>
                                            <option {{ $serviceConnections->Status=="Closed" ? 'selected' : '' }} value="Closed">Closed</option>
                                            <option {{ $serviceConnections->Status=="Downloaded by Crew" ? 'selected' : '' }} value="Downloaded by Crew">Downloaded by Crew</option>
                                            <option {{ $serviceConnections->Status=="Energized" ? 'selected' : '' }} value="Energized">Energized</option>
                                            <option {{ $serviceConnections->Status=="For Inspection" ? 'selected' : '' }} value="For Inspection">For Inspection</option>
                                            <option {{ $serviceConnections->Status=="Forwarded To Planning" ? 'selected' : '' }} value="Forwarded To Planning">Forwarded To Planning</option>
                                        </select>
                                    @endif
                                </p>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>

            {{-- APPLICATION DETAILS --}}
            <div class="col-lg-12">
                <div class="card shadow-none">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link custom-tab active" href="#details" data-toggle="tab">
                                <i class="fas fa-info-circle"></i>
                                Application Basic Details</a></li>
                            <li class="nav-item"><a class="nav-link custom-tab" href="#verification" data-toggle="tab">
                                <i class="fas fa-clipboard-check"></i>
                                Verification</a></li>
                            <li class="nav-item"><a class="nav-link custom-tab" href="#metering" data-toggle="tab">
                                <i class="fas fa-tachometer-alt"></i>
                                Metering and Transformer</a></li>
                            <li class="nav-item"><a class="nav-link custom-tab" href="#invoice" data-toggle="tab">
                                <i class="fas fa-file-invoice-dollar"></i>
                                Payment Order</a></li>
                            <li class="nav-item"><a class="nav-link custom-tab" href="#files" data-toggle="tab">
                                <i class="fas fa-paperclip"></i>
                                Files</a></li>
                            <li class="nav-item"><a class="nav-link custom-tab" href="#photos" data-toggle="tab">
                                <i class="fas fa-images"></i>
                                Photos</a></li>
                            {{-- @if ($serviceConnections->LoadCategory == 'above 5kVa' | $serviceConnections->LongSpan == 'Yes')
                            <li class="nav-item"><a class="nav-link custom-tab" href="#bom" data-toggle="tab">
                                <i class="fas fa-toolbox"></i>
                                Bill of Materials</a></li>
                            @endif --}}
                            <li class="nav-item"><a class="nav-link custom-tab" href="#logs" data-toggle="tab">
                                <i class="fas fa-list"></i>
                                Logs</a></li>
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="details">
                                @include('service_connections.tab_details')
                            </div>

                            <div class="tab-pane" id="verification">
                                @include('service_connections.tab_verification')
                            </div>

                            <div class="tab-pane" id="metering">
                                @include('service_connections.tab_metering')
                            </div>

                            <div class="tab-pane" id="invoice">
                                @include('service_connections.tab_invoice')
                            </div>

                            <div class="tab-pane" id="files">
                                @include('service_connections.tab_files')
                            </div>

                            <div class="tab-pane" id="photos">
                                @include('service_connections.tab_photos')
                            </div>
                            
                            {{-- <div class="tab-pane" id="bom">
                                @include('service_connections.tab_bom_details')
                            </div> --}}

                            <div class="tab-pane" id="logs">
                                @include('service_connections.tab_logs')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // LOAD IMAGE
            $.ajax({
                url : '/member_consumer_images/get-image/' + "{{ $serviceConnections->MemberConsumerId }}",
                type : 'GET',
                success : function(result) {
                    var data = JSON.parse(result)
                    $('#prof-img').attr('src', data['img'])
                },
                error : function(error) {
                    console.log(error);
                }
            })

            $('#override').on('click', function(e) {
                e.preventDefault()
                var status = $('#Status').val()

                $.ajax({
                    url : "{{ route('serviceConnections.update-status') }}",
                    type : 'GET',
                    data : {
                        id : "{{ $serviceConnections->id }}",
                        Status : status
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        Swal.fire({
                            icon : 'error',
                            text : 'Error updating status'
                        })
                    }
                })
            })
        });

        function revalidatePayment(id) {
            Swal.fire({
                title: "Re-validate Order Materials",
                text: "This will re-insert the payment order with all materials to warehouse database. Proceed?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('serviceConnections.revalidate-payment-order-materials') }}",
                        type : "GET",
                        data : {
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Payment order revalidated!'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error revalidating payment order'
                            })
                        }
                    })      
                }
            })
        }
    </script>
@endpush
