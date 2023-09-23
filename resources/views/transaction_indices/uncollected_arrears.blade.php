@php
    use App\Models\ORAssigning;
    use App\Models\IDGenerator;

    $transactionId = IDGenerator::generateID();
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Uncollected Arrears Collection</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            {{-- SEARCH BIN --}}
            <div class="col-lg-5">
                <div class="card" style="height: 70vh;">
                    <div class="card-header border-0">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search Account Name or Account Number..." id="search" autofocus>
                        </div>
                    </div>
                    <div class="card-body table-responsive px-0">
                        <table class="table table-hover table-sm" id="search-table">
                            <thead>
                                <th>Account No.</th>
                                <th>Account Name</th>
                                <th>Balance</th>
                                <th></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- FORM --}}
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        {{-- FORM --}}
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td>Current Balance</td>
                                                <td class="text-right">
                                                    <input type="text" class="form-control text-right" style="font-size: 1.5em;" id="totalAmount" readonly="true">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>OR Number</td>
                                                <td class="text-right">
                                                    <input type="number" class="form-control text-right" style="font-size: 1.5em;" id="ornumber" value="{{ ORAssigning::getORIncrement(1, $orAssignedLast) }}" autofocus>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Cash Payment</td>
                                                <td class="text-right">
                                                    <input type="number" class="form-control text-right" style="font-size: 1.5em;" id="cashAmount" step="any">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Check Payments</td>
                                                <td class="text-right">
                                                    <button id="addCheckBtn" class="btn btn-xs btn-primary float-right ico-tab-mini" data-toggle="modal" data-target="#modal-check-payment"><i class="fas fa-plus ico-tab-mini"></i>Add Check</button>
                                                </td>
                                            </tr>
                                        </table>

                                        <table class="table table-borderless table-sm" id="check-table">

                                        </table>

                                        <div class="divider"></div>

                                        {{-- FORM TOTAL --}}
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td>Total Amount Paid</td>
                                                <td class="text-right">
                                                    <input type="number" class="form-control text-right" style="font-size: 1.5em;" id="amountPaid" step="any" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Remaining Balance</td>
                                                <td class="text-right">
                                                    <input type="text" class="form-control text-right" style="font-size: 1.5em;" id="remaining-balance" readonly="true">
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="card-footer">
                                        <button id="cashBtn" class="btn btn-lg btn-primary float-right" disabled><i class="fas fa-dollar-sign"></i> Transact</button>
                                        {{-- <button id="checkBtn" class="btn btn-sm btn-default float-right ico-tab-mini" disabled><i class="fas fa-money-check-alt"></i> Check</button> --}}
                                        <button id="cardBtn" class="btn btn-sm btn-default float-right ico-tab-mini" disabled><i class="fas fa-credit-card"></i> Debit/Credit Card</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('paid_bills.check_modal')

@push('page_scripts')
    <script>
        var amountPaid = 0.0
        var currentBalance = 0.0
        var remainingBalance = 0.0
        var accountNo = ""
        var checkIds = []
        var checkAmountTotal = 0

        $(document).ready(function() {
            // SEARCH EVENT
            $('#search').keyup(function() {
                var searchVal = this.value

                if (searchVal.length > 4) {
                    $.ajax({
                        url : '/transaction_indices/search-arrear-collectibles',
                        type : 'GET',
                        data : {
                            query : searchVal,
                        },
                        success : function(res) {
                            $('#search-table tbody tr').remove()
                            $('#ledger-table tbody tr').remove()

                            if (jQuery.isEmptyObject(res)) {

                            } else {
                                $('#search-table tbody').append(res)
                            }
                        },
                        error : function(err) {
                            console.log(err)
                            alert('An error occurred while performing the search')
                        }
                    })
                }
            })

            // AMOUNT PAID EVENT
            $('#amountPaid').keyup(function() {
                amountPaid = parseFloat(this.value)
                remainingBalance = currentBalance - amountPaid;

                if (parseFloat(remainingBalance) | remainingBalance == 0) {
                    if (remainingBalance < 0) {
                        buttonEnablers(false)
                    } else {
                        buttonEnablers(true)
                    }
                    $('#remaining-balance').val(remainingBalance.toFixed(2))
                } else {
                    $('#remaining-balance').val('')
                    buttonEnablers(false)
                }                
            })

            $('#amountPaid').on('change', function() {
                amountPaid = parseFloat(this.value)
                remainingBalance = currentBalance - amountPaid;

                if (parseFloat(remainingBalance) | remainingBalance == 0) {
                    if (remainingBalance < 0) {
                        buttonEnablers(false)
                    } else {
                        buttonEnablers(true)
                    }
                    $('#remaining-balance').val(remainingBalance.toFixed(2))
                } else {
                    $('#remaining-balance').val('')
                    buttonEnablers(false)
                }                
            })

            $('#cashAmount').keyup(function() {
                computePayments()
            })

            // CASH BUTTON SAVE EVENT
            $('#cashBtn').on('click', function() {
                saveTransaction()
            })

            // TRANSACT CHECK
            $('#save-check-transaction').on('click', function() {
                addCheckPayment()           
            })
        })

        function fetchDetails(id) {
            // POPULATE DETAILS
            resetForm()
            $('#cashAmount').focus()
            $.ajax({
                url : '/transaction_indices/fetch-arrear-details',
                type : 'GET',
                data : {
                    AccountNumber : id,
                },
                success : function(res) {
                    try {
                        if (jQuery.isEmptyObject(res)) {

                        } else {
                            $('#totalAmount').val(res['Balance'])
                            currentBalance = parseFloat($('#totalAmount').val())
                            accountNo = res['AccountNumber']
                        }
                    } catch (err) {
                        console.log(err)
                    }
                }
            })
        }

        function buttonEnablers(bool) {
            if (bool) {
                // enable
                $('#cashBtn').removeAttr('disabled')   
                $('#checkBtn').removeAttr('disabled')  
                $('#cardBtn').removeAttr('disabled')               
            } else {
                // disable
                $('#cashBtn').attr('disabled', 'true')
                $('#checkBtn').attr('disabled', 'true')
                $('#cardBtn').attr('disabled', 'true')
            }   
        }

        function resetForm() {
            $('#totalAmount').val('')
            $('#amountPaid').val('')
            $('#remaining-balance').val('')
            checkIds = []
            checkAmountTotal = 0
            amountPaid = 0.0
            currentBalance = 0.0
            remainingBalance = 0.0
            accountNo = ""
        }

        function computePayments() {
            var cashAmnt = parseFloat($('#cashAmount').val()) ? parseFloat($('#cashAmount').val()) : 0
            var totalX = cashAmnt + checkAmountTotal
            $('#amountPaid').val(totalX.toFixed(2)).change()
        }

        // DETEC IF ENTER KEY IS PRESSED
        $(document).keypress(function(event){
            if (parseFloat(remainingBalance) | remainingBalance == 0) {
                if (remainingBalance < 0 && jQuery.isEmptyObject($('#ornumber').val())) {
                                         
                } else {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if(keycode == '13'){
                        saveTransaction()
                    } 
                }
            } else {

            }  
        });

        function saveTransaction() {
            var paymentUsed = ''
            if (jQuery.isEmptyObject($('#cashAmount').val())) {
                paymentUsed = 'Check'
            } else {
                if (checkIds.length > 0) {
                    paymentUsed = 'Cash and Check'
                } else {
                    paymentUsed = 'Cash'
                }
            }

            $.ajax({
                url : '/transaction_indices/save-arrear-transaction',
                type : 'GET',
                data : {
                    AccountNumber : accountNo,
                    AmountPaid : amountPaid,
                    RemainingBalance : remainingBalance, 
                    PaymentUsed : paymentUsed,
                    ORNumber : $('#ornumber').val(),
                    TransactionId : '{{ $transactionId }}',
                    CheckAmount : checkAmountTotal,
                    CheckIds : checkIds,
                    CashAmount : $('#cashAmount').val(),
                }, 
                success : function(res) {
                    alert('Print OR')
                    location.reload()
                },
                error : function(err) {
                    Swal.fire({
                        title: 'Oops...',
                        text: 'An error occurred while performing the transaction. Contact support immediately!',
                        icon: 'error',
                    })
                }
            })
        }

        function clearModalFields() {
            // clear fields
            $('#checkAmount').val('')
            $('#checkNo').val('')
        }

        function removeCheckFromArray(id) {
            var index = checkIds.indexOf(id)
            if (index > -1) {
                checkIds.splice(index, 1)
            }
        }

        function addCheckPayment() {
            $.ajax({
                url : "{{ route('transactionIndices.add-check-payment') }}",
                type : 'GET',
                data : {
                    TransactionIndexId : '{{ $transactionId }}',
                    Amount : $('#checkAmount').val(),
                    CheckNo : $('#checkNo').val(),
                    Bank : $('#bank').val(),
                    CheckNo : $('#checkNo').val(),
                    ORNumber : $('#ornumber').val(),
                    CheckExpiration : null,
                },
                success : function(res) {
                    checkIds.push(res['id'])
                    $('#check-table').append(addCheckToTable(res['id'], res['Bank'], res['Amount'], res['CheckNo']))
                    $('#modal-check-payment').modal('hide')

                    checkAmountTotal = checkAmountTotal + parseFloat(res['Amount'])
                    clearModalFields()

                    computePayments()
                },
                error : function(err) {
                    Swal.fire({
                        title: 'Oops...',
                        text: 'An error occurred while adding the check. Contact support immediately!',
                        icon: 'error',
                    })
                }
            })
        }

        function addCheckToTable(id, bank, amount, checkNo) {
            return "<tr id='" + id + "' title='Check Number: " + checkNo + "' style='background-color: #e3f2fd'>" +
                        "<td>" + bank + "</td>" +
                        "<td class='text-right'><strong>P " + Number(parseFloat(amount).toFixed(2)).toLocaleString() + "<strong></td>" +
                        "<td class='text-right'><button onclick=deleteCheck('" + id + "') class='btn btn-xs text-danger'><i class='fas fa-trash'></i></button></td>" +
                    "</tr>"
        }

        function deleteCheck(id) {
            $.ajax({
                url : "{{ route('transactionIndices.delete-check-payment') }}",
                type : 'GET',
                data : {
                    id : id,
                },
                success : function(res) {
                    removeCheckFromArray(id)
                    $('#' + id).remove()

                    // deduct check
                    checkAmountTotal = checkAmountTotal - parseFloat(res['Amount'])
                    computePayments()
                },
                error : function(err) {
                    Swal.fire({
                        title: 'Oops...',
                        text: 'An error occurred while adding the check. Contact support immediately!',
                        icon: 'error',
                    })
                }
            })
        }
    </script>
@endpush