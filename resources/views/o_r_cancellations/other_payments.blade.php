@php
    use Illuminate\Support\Facades\Auth; 
    use App\Models\IDGenerator;
    use App\Models\ORAssigning;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Other Transactions OR Cancellation Console</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        {{-- SEARCH --}}
        <div class="col-lg-5 col-md-6">
            <div class="card" style="height: 70vh;">
                <div class="card-header border-0">
                    <div class="row">
                        <div class="col-lg-8">
                            <input type="text" class="form-control" id="search-field" placeholder="Search OR Number or Consumer" autofocus>
                        </div>
                        <div class="col-lg-4">
                            <div class="card-tools">
                                <button class="btn btn-primary" id="search-btn">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive px-0">
                    <table class="table table-hover table-sm" id="result-table">
                        <thead>
                            <th>OR Number</th>
                            <th>Payment Description</th>
                            <th>Source</th>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- FORM --}}
        <div class="col-lg-7 col-md-6">
            <div class="card" style="height: 70vh;">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10 col-md-8">
                            <input type="text" class="form-control" placeholder="Why do you wanna cancel this OR? Provide remarks here." id="notes">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <button class="btn btn-danger" id="cancel-or-btn">Cancel OR</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="invoice p-3 mb-3">    
                        <div class="row invoice-info">
                            <div class="col-lg-8 invoice-col">
                                <address>
                                    <strong id="or-number">OR No: </strong><br>
                                    <span id="or-date">OR Date: </span><br>
                                    <span id="payment-title">Payment For: </span><br>
                                </address>
                            </div>
                            <!-- /.col -->    
                            <div class="col-lg-4 invoice-col">
                                <address>
                                    <span id="sub-sotal"></span><br>
                                    <span id="vat"></span><br>
                                    <strong id="total"></strong><br>
                                </address>
                            </div>
                            <!-- /.col -->
                                                    
                        </div>    
                    </div>
                    <p><strong>Particulars</strong></p>
                    <table class="table table-sm table-borderless" id="particulars-table">
                        <thead>
                            <th>Item</th>
                            <th>Amout</th>
                            <th>VAT</th>
                            <th>Total Amount</th>
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
        var transactionId = ""

        $(document).ready(function() {
            $('#search-field').keyup(function() {
                var letterCount = this.value.length;

                if (letterCount > 4) {
                    performSearchOR(this.value)
                }                
            })

            $('#search-btn').on('click', function() {
                performSearchOR($('#search-field').val())
            })

            $('#cancel-or-btn').on('click', function() {
                if (jQuery.isEmptyObject(transactionId)) {
                    alert('Select payment first!')
                } else {
                    if (confirm('Are you sure you want to cancel this OR?')) {
                        $.ajax({
                            url : '/o_r_cancellations/attempt-cancel-transaction-or',
                            type : 'GET',
                            data : {
                                id : transactionId,
                                Notes : $('#notes').val(),
                            },
                            success : function(res) {
                                window.location.reload()
                            },
                            error : function(err) {
                                alert('An error occurred while cancelling the OR. \n' . err)
                            }
                        })
                    }
                }
            })
        })

        function performSearchOR(value) {
            $('#result-table tbody tr').remove()
            $.ajax({
                url : '/o_r_cancellations/fetch-transaction-indices',
                type : 'GET',
                data : {
                    query : value
                },
                success : function(res) {
                    $('#result-table tbody').append(res)
                },
                error : function(err) {
                    $('#result-table tbody tr').remove()
                    alert('An error occurred during the search')
                }
            })
        }

        function fetchDetails(id) {
            transactionId = id
            $.ajax({
                url : '/o_r_cancellations/fetch-transaction-details',
                type : 'GET',
                data : {
                    id : id,
                },
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {

                    } else {
                        fetchParticulars(id)
                        $('#or-number').text('OR No: ' + res['ORNumber'])
                        $('#or-date').text('OR Date: ' + res['ORDate'])
                        $('#payment-title').text('Payment For: ' + res['PaymentTitle'])
                        $('#sub-sotal').text('Sub-Total: ' + res['SubTotal'])
                        $('#vat').text('VAT: ' + res['VAT'])
                        $('#total').text('Total: ' + res['Total'])
                    }
                },
                error : function(err) {
                    alert('An error occurred while fetching the data')
                }
            })
        }

        function fetchParticulars(id) {
            $('#particulars-table tbody tr').remove()
            $.ajax({
                url : '/o_r_cancellations/fetch-transaction-particulars',
                type : 'GET',
                data : {
                    id : id,
                },
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {

                    } else {
                        $('#particulars-table tbody').append(res)
                    }
                },
                error : function(err) {
                    alert('An error occurred while fetching the data')
                }
            })
        }
    </script>
@endpush