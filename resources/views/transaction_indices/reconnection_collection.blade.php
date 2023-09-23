@php
    use App\Models\ORAssigning;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-5">
                    <h4>Reconnection Payments</h4>
                </div>
                <div class="col-sm-5">
                    <div class="form-row align-items-center float-right">
                        {{-- <div class="col-auto">
                            <input id="area-search" type="text" maxlength="2" class="form-control" style="width: 60px;">
                        </div>
                        <div class="col-auto">
                            <input id="route-search" type="text" maxlength="5" class="form-control" style="width: 90px;">
                        </div>
                        <div class="col-auto">
                            <input id="sequence-search" type="text" maxlength="3" class="form-control" style="width: 70px;">
                        </div> --}}
                        <div class="col-auto">
                            <input class="form-control" id="old-account-no" data-inputmask="'alias': 'phonebe'" maxlength="12">
                        </div>
                    </div>    
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-success float-right" title="Search Consumer"  data-toggle="modal" data-target="#modal-search"><i class="fas fa-search-dollar ico-tab"></i>Search</button>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            {{-- PARTICULARS --}}
            <div class="col-lg-7 col-md-6">
                <div class="card" style="height: 70vh;">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-11">
                                <h4 class="card-title" id="account-name">Account Name</h4><br>
                                <address class="text-muted" id="account-no">Account No</address>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive px-0">
                        <a href="{{ route('paidBills.index') }}" class="btn btn-warning btn-sm float-right ico-tab"><i class="fas fa-share ico-tab-mini"></i>Go to Bills Collection</a>
                        <p style="padding-left: 20px;" class="text-muted"><i><strong>Arrears/Unpaid Bills</strong></i></p>
                        <table class="table" id="arrears-table">
                            <thead>
                                <th>Billing Month</th>
                                <th>Amount</th>
                                <th>Penalty/Surcharge</th>
                                <th>Total</th>
                                <th width="40px"></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td>Reconnection Fee</td>
                                <th class="text-right">{{ $reconnectionPayable != null ? (number_format($reconnectionPayable->DefaultAmount, 2)) : 'amount not specified, go to settings first' }}</th>
                            </tr>
                            <tr>
                                <td>VAT</td>
                                <th class="text-right">{{ $reconnectionPayable != null ? (number_format((floatval($reconnectionPayable->VATPercentage) * floatval($reconnectionPayable->DefaultAmount)), 2)) . ' (' . number_format(floatval($reconnectionPayable->VATPercentage) * 100, 0) . '%)' : 'amount not specified, go to settings first' }}</th>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <th style="font-size: 1.5em;" class="text-right">{{ $reconnectionPayable != null ? (number_format(((floatval($reconnectionPayable->VATPercentage) * floatval($reconnectionPayable->DefaultAmount)) + floatval($reconnectionPayable->DefaultAmount)), 2)) : 'amount not specified, go to settings first' }}</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- FORM --}}
            <div class="col-lg-5 col-md-6">
                <div class="card">
                    <div class="card-body">
                        {{-- FORM --}}
                        <table class="table table-borderless">
                            <tr>
                                <td>Total</td>
                                <td class="text-right">
                                    <input type="text" class="form-control text-right" style="font-size: 1.5em;" id="totalAmount" readonly="true" value="{{ $reconnectionPayable != null ? (round(((floatval($reconnectionPayable->VATPercentage) * floatval($reconnectionPayable->DefaultAmount)) + floatval($reconnectionPayable->DefaultAmount)), 2)) : '0' }}">
                                </td>
                            </tr>
                            <tr>
                                <td>OR Number</td>
                                <td class="text-right">
                                    <input type="number" class="form-control text-right" style="font-size: 1.5em;" id="ornumber" value="{{ ORAssigning::getORIncrement(1, $orAssignedLast) }}" autofocus>
                                </td>
                            </tr>
                            <tr>
                                <td>Amount Paid</td>
                                <td class="text-right">
                                    <input type="number" class="form-control text-right" style="font-size: 1.5em;" id="amountPaid" step="any">
                                </td>
                            </tr>
                            <tr>
                                <td>Change</td>
                                <td class="text-right">
                                    <input type="text" class="form-control text-right" style="font-size: 1.5em;" id="change" readonly="true">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button id="cashBtn" class="btn btn-lg btn-primary float-right" disabled><i class="fas fa-dollar-sign"></i> Cash</button>
                        <button id="checkBtn" class="btn btn-sm btn-default float-right ico-tab-mini" disabled><i class="fas fa-money-check-alt"></i> Check</button>
                        <button id="cardBtn" class="btn btn-sm btn-default float-right ico-tab-mini" disabled><i class="fas fa-credit-card"></i> Debit/Credit Card</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- MODAL FOR SEARCHING OF CONSUMERS --}}
<div class="modal fade" id="modal-search" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Search Consumer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- SEARCH --}}
                <div class="row">                    
                    <div class="form-group col-lg-8 offset-lg-1">
                        <input type="text" id="search" placeholder="Account Number, Account Name, or Meter Number" class="form-control" autofocus="true">
                    </div>
                    <div class="form-group col-lg-1">
                        <button id="search-consumer" class="btn btn-primary btn-lg"><i class="fas fa-search-dollar"></i></button>
                    </div>
                </div>

                {{-- RESULTS --}}
                <p class="text-muted"><i id="count">Results</i></p>
                <table class="table table-sm table-hover" id="res-table">
                    <thead>
                        <th>Account Number</th>
                        <th>Account Name</th>
                        <th>Address</th>
                        <th>Account Status</th>
                        <th></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        var accountNo = ""
        var total = 0.0;
        $(document).ready(function() {
            //basic search
            $('#old-account-no').focus()

            $("#old-account-no").inputmask({
                mask: '99-99999-999',
                placeholder: '',
                showMaskOnHover: false,
                showMaskOnFocus: false,
                onBeforePaste: function (pastedValue, opts) {
                    var processedValue = pastedValue;

                    //do something with it

                    return processedValue;
                }
            });

            $("#old-account-no").keyup(function() {
                if (this.value.length == 12) {
                    searchOldAccountNumber()
                }
            })

            total = parseFloat($('#totalAmount').val())
            payableDisablers(true)
            setFormAmount()
            $('#search').keyup(function() {
                var letterCount = this.value.length;

                if (letterCount > 4) {
                    performSearch(this.value)
                }
            })

            // ASSESS VALUE ON TYPE
            var change = 0;
            $('#amountPaid').keyup(function() {
                change = parseFloat(this.value) - parseFloat(total)

                if (parseFloat(change)) {
                    if (change > -1 && !jQuery.isEmptyObject($('#ornumber').val()) && !jQuery.isEmptyObject(accountNo)) {
                        $('#cashBtn').removeAttr('disabled')
                        $('#checkBtn').removeAttr('disabled')
                        $('#cardBtn').removeAttr('disabled')                        
                    } else {
                        $('#cashBtn').attr('disabled', 'true')
                        $('#checkBtn').attr('disabled', 'true')
                        $('#cardBtn').attr('disabled', 'true')
                    }
                    $('#change').val(Number(change.toFixed(2)).toLocaleString())
                } else {
                    $('#change').val('')
                    $('#cashBtn').attr('disabled', 'true')
                    $('#checkBtn').attr('disabled', 'true')
                    $('#cardBtn').attr('disabled', 'true')
                }                
            })

            $('#ornumber').keyup(function() { 
                if (parseFloat(change)) {
                    if (change > -1 && !jQuery.isEmptyObject($('#ornumber').val()) && !jQuery.isEmptyObject(accountNo)) {
                        $('#cashBtn').removeAttr('disabled')
                        $('#checkBtn').removeAttr('disabled')
                        $('#cardBtn').removeAttr('disabled')                        
                    } else {
                        $('#cashBtn').attr('disabled', 'true')
                        $('#checkBtn').attr('disabled', 'true')
                        $('#cardBtn').attr('disabled', 'true')
                    }
                    $('#change').val(Number(change.toFixed(2)).toLocaleString())
                } else {
                    $('#change').val('')
                    $('#cashBtn').attr('disabled', 'true')
                    $('#checkBtn').attr('disabled', 'true')
                    $('#cardBtn').attr('disabled', 'true')
                }   
            })

            // DETECT ON ENTER
            $(document).keypress(function(event){
                if (parseFloat(change)) {
                    if (change > -1 && !jQuery.isEmptyObject($('#ornumber').val()) && !jQuery.isEmptyObject(accountNo)) {
                        var keycode = (event.keyCode ? event.keyCode : event.which);
                        if(keycode == '13'){
                            saveReconnection('Cash')
                        }                      
                    } else {

                    }
                } else {

                }  
            });

            // CASH BUTTON EVENT
            $('#cashBtn').on('click', function() {
                if (parseFloat(change)) {
                    if (change > -1 && !jQuery.isEmptyObject($('#ornumber').val()) && !jQuery.isEmptyObject(accountNo)) {
                        saveReconnection('Cash')                    
                    } else {

                    }
                } else {

                }  
            })
        })

        /**
         * SAVE TRANSACTION AND CLEAR CACHE
         */
        function saveReconnection(type) {
            $.ajax({
                url : '/transaction_indices/save-reconnection-transaction',
                type : 'GET',
                data : {
                    AccountNumber : accountNo,
                    ORNumber : $('#ornumber').val(),
                    PaymentUsed : type,
                    SubTotal : "{{ $reconnectionPayable != null ? (round($reconnectionPayable->DefaultAmount, 2)) : 0 }}",
                    VAT : "{{ $reconnectionPayable != null ? (round((floatval($reconnectionPayable->VATPercentage) * floatval($reconnectionPayable->DefaultAmount)), 2)) : 0 }}",
                    Total : "{{ $reconnectionPayable != null ? (round(((floatval($reconnectionPayable->VATPercentage) * floatval($reconnectionPayable->DefaultAmount)) + floatval($reconnectionPayable->DefaultAmount)), 2)) : 0 }}"
                },
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {

                    } else {
                        window.location.href = "{{ url('/transaction_indices/print-reconnection-collection') }}" + "/" + res['id'];
                    }
                },
                error : function(err) {

                }
            })
        }

        function performSearch(regex) {
            $.ajax({
                url : '/transaction_indices/search-disconnected-consumers',
                type : 'GET',
                data : {
                    query : regex,
                },
                success : function(res) {
                    try {
                        if (jQuery.isEmptyObject(res)) {
                            $('#res-table tbody tr').remove()
                        } else {
                            $('#res-table tbody tr').remove()
                            $('#res-table tbody').append(res)
                        }   
                    } catch (err) {
                        $('#res-table tbody tr').remove()
                    }                                     
                },
                error : function(error) {
                    $('#res-table tbody tr').remove()
                    alert('Error fetching data')
                }
            })
        }

        function fetchDetails(id) {
            $('#modal-search').modal('hide')
            // payableDisablers(false)
            $.ajax({
                url : '/transaction_indices/fetch-account-details',
                type : 'GET',
                data : {
                    id : id,
                },
                success : function(res) {
                    if (res['AccountStatus'] == 'DISCONNECTED') {
                        accountNo = res['id']
                        $('#account-name').html('<i class="fas fa-check-circle text-success ico-tab"></i>' + res['ServiceAccountName'])
                        $('#account-no').text(accountNo)
                        getArrears(accountNo)
                    } else {
                        accountNo = ""
                        total = 0.0;

                        $('#account-name').text('...')
                        $('#account-no').text('...')

                        Swal.fire({
                            title: 'Invalid Entry!',
                            text: 'Account name ' + res['ServiceAccountName'] + ' is ' + res['AccountStatus'] + ', it should be marked DISCONNECTED to be able to pay the reconnection fee.',
                            icon: 'error',
                        })
                    }
                    
                },
                error : function(err) {
                    Swal.fire({
                            title: 'Oops...',
                            text: 'An error occurred while fetching the entry',
                            icon: 'error',
                        })
                }
            })
        }

        function getArrears(id) {
            $('#arrears-table tbody tr').remove()
            $.ajax({
                url : '/transaction_indices/get-arrears-data',
                type : 'GET',
                data : {
                    AccountNumber : id,
                },
                success : function(res) {
                    $('#arrears-table tbody').append(res)
                },
                error : function(err) {
                    alert('An error occurred while fetching arrears')
                }
            })
        }

        function setFormAmount() {
            $('#amount').val($('#payables option:selected').attr('def-amount'))
        }

        function payableDisablers(bool) {
            if (bool) {
                $('#amount').attr('readonly', true)
                $('#add-to-payable-btn').attr('disabled', true)
            } else {
                $('#amount').removeAttr('readonly')
                $('#add-to-payable-btn').removeAttr('disabled')
            }
        }

        function focusOrNumber() {
            $('#ornumber').focus()
        }

        function clearAll() {
            total = 0.0;
            accountNo = "";
            $('#payables-table tbody tr').remove()
            $('#total-amnt-text').text("")
            $('#totalAmount').val("")
            $('#amountPaid').val('')
            $('#change').val('')
            $('#ornumber').val('')
        }

        function searchOldAccountNumber() {            
            accountNo = ""
            total = 0.0;

            var oldAcctNo = $('#old-account-no').val()
            $.ajax({
                url : "{{ route('paidBills.fetch-account-by-old-account-number') }}",
                type : 'GET',
                data : {
                    OldAccountNo : oldAcctNo,
                },
                success : function(res) {
                    fetchDetails(res['id'])
                },
                error : function(err) {
                    Swal.fire({
                        title: 'Oops...',
                        text: 'An error occurred while adding the check. Contact support immediately!',
                        icon: 'error',
                    })
                },
                statusCode : {
                    404: function() {
                        Swal.fire({
                            title: 'Account Not Found!',
                            icon: 'error',
                        })
                    }
                }
            })
        }
    </script>
@endpush