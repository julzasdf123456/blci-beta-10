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
                    <h4>Bills Payment Console</h4>
                </div>
                <div class="col-sm-6">
                    {{-- <button class="btn btn-warning float-right" title="Search initial OR Number"  data-toggle="modal" data-target="#modal-set" style="margin-left: 20px;"><i class="fas fa-tools ico-tab"></i>Set Start OR</button> --}}
                    <button class="btn btn-success float-right" title="Search Consumer"  data-toggle="modal" data-target="#modal-search"><i class="fas fa-search-dollar ico-tab"></i>Search <strong>(F2)</strong></button>                   
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            {{-- QUEUE --}}
            <div class="col-lg-8 col-md-6">
                <div class="card" style="height: 60vh;">
                    <div class="card-header border-0">
                        <span class="card-title">
                            <h4 id="account-name">...</h4>
                            <address class="text-muted" id="account-number">...</address>
                        </span>
                    </div>
                    <div class="card-body table-responsive px-0">
                        <table class="table table-hover" id="payables-table">
                            <thead>
                                <th>Bill No.</th>
                                <th>Billing Month</th>
                                <th>Amount Due</th>
                                <th width="50px"></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>                  
            </div>

            {{-- PAYMENT SECTION --}}
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-header border-0">
                        
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td>Amount Due</td>
                                    <td class="text-right">
                                        <h4 class="text-right" id="amount-due"></h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Deductions/Subsidies</td>
                                    <td class="text-right">
                                        <h4 class="text-right" id="deductions"></h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Additional Charges</td>
                                    <td class="text-right">
                                        <h4 class="text-right" id="additional-charges"></h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Surcharges</td>
                                    <td class="text-right">
                                        <h4 class="text-right" id="surcharges"></h4>
                                    </td>
                                </tr>
                                <tr style="border-top: 1px solid #dcdcdc">
                                    <th>Total Amount Due</th>
                                    <th class="text-right">
                                        <h1 class="text-right" id="total-amount"></h1>
                                    </th>
                                </tr>                                    
                                <tr>
                                    <td>OR Number</td>
                                    <td class="text-right">
                                        <input type="number" class="form-control text-right" style="font-size: 1.5em;" id="orNumber" value="{{ ORAssigning::getORIncrement(1, $orAssignedLast) }}">
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
                        <th>Meter Number</th>
                        <th></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            {{-- <div class="modal-footer justify-content-between">--}}
                {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
                {{-- <button type="button" class="btn btn-primary" id="submit">Save changes</button> --}}
                {{-- <input type="submit" value="Add" id="submit" class="btn btn-primary"> --}}
            {{-- </div>  --}}
        </div>
    </div>
</div>

{{-- MODAL FOR SETTING OR NO --}}
<div class="modal fade" id="modal-set" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Set Initial OR Number</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- SEARCH --}}
                <div class="row">                    
                    <div class="form-group col-lg-12">
                        <input type="number" id="orno" placeholder="Input OR Number Start" class="form-control" autofocus="true">
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <button id="setOr" class="btn btn-primary btn-sm"><i class="fas fa-check ico-tab-mini"></i> Set</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        var change = 0
        var totalAmount = 0
        var deductions = 0
        var additionals = 0
        var dbAmount = 0
        var netAmount = 0
        var surcharge = 0
        var billNumber = ''
        var acctNo = ''
        var svcPeriod = ''
        var kwhUsed = ''
        var billId = ''

        var selectedPayments = []

        $(document).ready(function() {
            $('#search').keyup(function() {
                var letterCount = this.value.length;

                if (letterCount > 4) {
                    performSearch(this.value)
                }
            })

            $('#search-consumer').on('click', function() {
                var letterCount = $('#search').val().length;
                if (letterCount > 4) {
                    performSearch($('#search').val())
                }
            })

            // AMOUNT PAID ON KEY PRESSED
            $('#amountPaid').keyup(function() {
                change = (parseFloat(this.value) - totalAmount).toFixed(2).toLocaleString()

                if (parseFloat(change)) {
                    $('#change').val(change)
                    if (change > -1 && !jQuery.isEmptyObject($('#orNumber').val()) && !jQuery.isEmptyObject(selectedPayments)) {
                        buttonEnablers(true)                        
                    } else {
                        buttonEnablers(false)
                    }
                } else {
                    $('#change').val('')
                    buttonEnablers(false)
                }                
            })

            // CASH BUTTON EVENT
            $('#cashBtn').on('click', function() {
                if (parseFloat(change)) {
                    if (change > -1) {
                        transact()            
                    } else {

                    }
                } else {

                }  
            })

            // OR NO ON KEY PRESSED
            $('#orNumber').keyup(function() {
                change = (parseFloat($('#amountPaid').val()) - totalAmount).toFixed(2).toLocaleString()

                if (parseFloat(change)) {
                    $('#change').val(change)
                    if (change > -1 && !jQuery.isEmptyObject(this.value) && !jQuery.isEmptyObject(selectedPayments)) {
                        buttonEnablers(true)                        
                    } else {
                        buttonEnablers(false)
                    }
                } else {
                    $('#change').val('')
                    buttonEnablers(false)
                }                
            })

            // SET INIT OR
            $('#setOr').on('click', function() {
                $.ajax({
                    url : '/oRAssignings/',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : "{{ IDGenerator::generateIDandRandString() }}",
                        ORNumber : $('#orno').val(),
                        UserId : "{{ Auth::id() }}",
                        DateAssigned : "{{ date('Y-m-d') }}",
                        IsSetManually : "Yes",
                        TimeAssigned : "{{ date('H:i:s') }}",
                        Office : "{{ env('APP_LOCATION') }}",
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        alert('An error occurred while setting initial OR. Contact IT for support')
                    }
                });
            })

            $('#modal-search').on('shown.bs.modal', function () {
                $('#search').focus();
            })
        })

        function addToPayables(id) {
            if ($('#' + id).attr('ischecked') == 'false') {
                $('#' + id).removeClass('text-muted').addClass('text-primary')

                // ADD TO ARRAY
                selectedPayments.push(id)

                $('#' + id).attr('ischecked', 'true')
            } else {
                $('#' + id).removeClass('text-primary').addClass('text-muted')

                // REMOVE ITEM FROM ARRAY
                removeItemFromArray(id)

                $('#' + id).attr('ischecked', 'false')
            }

            // VALIDATE FORM
            computePayables(selectedPayments)
            $('#amount-due').text('P ' + Number(totalAmount.toFixed(2)).toLocaleString())
            $('#total-amount').text('P ' + Number(totalAmount.toFixed(2)).toLocaleString())
            $('#additional-charges').text('P ' + Number(additionals.toFixed(2)).toLocaleString())
            $('#deductions').text('P ' + Number(deductions.toFixed(2)).toLocaleString())
            $('#surcharges').text('P ' + Number(surcharge.toFixed(2)).toLocaleString())
            $('#amountPaid').focus()

            // VALIDATE BUTTONS
            change = (parseFloat($('#amountPaid').val()) - totalAmount).toFixed(2).toLocaleString()

            if (parseFloat(change)) {
                $('#change').val(change)
                if (change > -1 && !jQuery.isEmptyObject($('#orNumber').val()) && !jQuery.isEmptyObject(selectedPayments)) {
                    buttonEnablers(true)                        
                } else {
                    buttonEnablers(false)
                }
            } else {
                $('#change').val('')
                buttonEnablers(false)
            } 
        }

        function computePayables() {
            var len = selectedPayments.length
            totalAmount = 0.0
            deductions = 0
            additionals = 0
            surcharge = 0

            for(var i=0; i<len; i++) {
                var additionalCharges = (parseFloat($('#' + selectedPayments[i]).attr('additionalCharges')) ? parseFloat($('#' + selectedPayments[i]).attr('additionalCharges')) : 0)
                additionals += additionalCharges

                var deductibles = (parseFloat($('#' + selectedPayments[i]).attr('deductions')) ? parseFloat($('#' + selectedPayments[i]).attr('deductions')) : 0)
                deductions += deductibles

                var surcharges = (parseFloat($('#' + selectedPayments[i]).attr('surcharge')) ? parseFloat($('#' + selectedPayments[i]).attr('surcharge')) : 0)
                surcharge += surcharges

                var amount = (parseFloat($('#' + selectedPayments[i]).attr('amount')) ? parseFloat($('#' + selectedPayments[i]).attr('amount')) : 0)
                totalAmount += (amount + surcharges)
            }
        }

        function requestUnlock(id) {
            $.ajax({
                url : '/paid_bills/request-bills-payment-unlock',
                type : 'GET',
                data : {
                    id : id
                },
                success : function(res) {
                    $('#lock-' + id).removeClass('fa-lock')
                    $('#lock-' + id).addClass('fa-exclamation-circle')
                    alert('Unlocking Requested')
                },
                error : function(err) {
                    alert('An error occurred while trying to request unlocking')
                }
            })
        }

        function removeItemFromArray(id) {
            var index = selectedPayments.indexOf(id)
            if (index > -1) {
                selectedPayments.splice(index, 1)
            }
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

        function performSearch(regex) {
            $.ajax({
                url : '/paid_bills/search',
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
                    console.log(error)
                }
            })
        }

        function fetchDetails(id) {
            $('#account-number').text(id)

            // CLEAR DETAILS
            $('#bill-no').text('-')
            $('#service-from').text('-')
            $('#service-to').text('-')
            $('#due-date').text('-')
            $('#kwh-used').text('-')
            $('#rate').text('-')
            $('#amount-due').text('-')
            $('#additional-charges').text('-')
            $('#deductions').text('-')
            $('#total-amount').text('-')
            $('#surcharges').text('-')

            // FETCH ACCOUNT DETAILS
            $.ajax({
                url : '/paid_bills/fetch-account',
                type : 'GET',
                data : {
                    AccountNumber : id,
                },
                success : function(res) {
                    $('#payables-table tbody tr').remove();
                    if (jQuery.isEmptyObject(res)) {

                    } else {
                        $('#account-name').text(res['ServiceAccountName'])
                    }
                    $('#modal-search').modal('hide')
                },
                error : function(err) {
                    $('#modal-search').modal('hide')
                    console.log(err)
                    alert('Error fetching account details. Contact support for more.')
                } 
            })

            // FETCH UNPAID BILLS
            $.ajax({
                url : '/paid_bills/fetch-details',
                type : 'GET',
                data : {
                    AccountNumber : id,
                }, 
                success : function(res) {
                    $('#payables-table tbody tr').remove();
                    if (jQuery.isEmptyObject(res)) {

                    } else {
                        $('#payables-table tbody').append(res)
                    }
                },
                error : function(err) {
                    console.log(err)
                    alert('Error fetching account details. Contact support for more.')
                }
            })            
        }

        function fetchPayable(id) {
            buttonEnablers(false)
            $('#amountPaid').val('')
            $('#change').val('')
            $.ajax({
                url : '/paid_bills/fetch-payable',
                type : 'GET',
                data : {
                    BillId : id
                },
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {
                        $('#bill-no').text('-')
                        $('#service-from').text('-')
                        $('#service-to').text('-')
                        $('#due-date').text('-')
                        $('#kwh-used').text('-')
                        $('#rate').text('-')
                        $('#amount-due').text('-')
                        $('#additional-charges').text('-')
                        $('#deductions').text('-')
                        $('#total-amount').text('-')
                        $('#surcharges').text('-')
                    } else {
                        $('#amountPaid').focus()
                        // INVOICE
                        $('#bill-no').text(res['BillNumber'])
                        $('#service-from').text(moment(res['ServiceDateFrom']).format('LL'))
                        $('#service-to').text(moment(res['ServiceDateTo']).format('LL'))
                        $('#due-date').text(moment(res['DueDate']).format('LL'))
                        $('#kwh-used').text(res['KwhUsed'])
                        $('#rate').text((parseFloat(res['EffectiveRate']).toFixed(4)).toLocaleString())

                        // GLOBAL VARIABLES
                        billNumber = res['BillNumber']
                        acctNo = res['AccountNumber']
                        svcPeriod = res['ServicePeriod']
                        kwhUsed = res['KwhUsed']
                        billId = id

                        // PAYABLES
                        deductions = parseFloat(res['Deductions'] != null ? res['Deductions'] : 0)
                        additionals = parseFloat(res['AdditionalCharges'] != null ? res['AdditionalCharges'] : 0)
                        dbAmount = parseFloat(res['NetAmount'] != null ? res['NetAmount'] : 0)
                        netAmount = dbAmount - additionals + deductions
                        surcharge = 0

                        var today = moment()
                        var dueDate = moment(res['DueDate'])
                        var dueDateDiff = today.diff(dueDate, 'days') 
                        
                        if (dueDateDiff > 0) {
                            // ADD DUE DATE
                            surcharge = (dbAmount * .3)
                            $('#surcharges').text('+ ₱ ' + surcharge.toFixed(2).toLocaleString())
                        } else {
                            $('#surcharges').text('+ ₱ 0')
                            surcharge = 0
                        }

                        totalAmount = dbAmount + surcharge

                        $('#amount-due').text('₱ ' + netAmount.toFixed(2).toLocaleString())
                        $('#additional-charges').text('+ ₱ ' + additionals.toFixed(2).toLocaleString())
                        $('#deductions').text('- ₱ ' + deductions.toFixed(2).toLocaleString())
                        $('#total-amount').text('₱ ' + totalAmount.toFixed(2).toLocaleString())
                    }
                },
                error : function(err) {
                    console.log(err)
                    alert('An error occurred while fetching the bill\n' + err)
                }
            })
        }

        // DETECT ENTER
        $(document).keydown(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);  
            if(keycode == '13'){
                if (parseFloat(change)) {
                    if (change > -1 && !jQuery.isEmptyObject($('#orNumber').val()) && !jQuery.isEmptyObject(selectedPayments)) {  
                        transact()                     
                    } else {

                    }
                } else {

                }
            } else if (keycode == 113) {
                $('#modal-search').modal('show')
                console.log('test')
            }
        });

        function transact() {
            $.ajax({
                url : '/paid_bills/save-paid-bill-and-print',
                type : 'GET',
                data : {
                    BillNumber : billNumber,
                    AccountNumber : acctNo,
                    ServicePeriod: svcPeriod,
                    KwhUsed : kwhUsed,
                    Surcharge : surcharge,
                    AdditionalCharges : additionals,
                    Deductions : deductions,
                    NetAmount : totalAmount,
                    BillId : billId,
                    ORNumber : $('#orNumber').val(),
                    BillsId : selectedPayments,
                }, 
                success : function(res) {
                    window.location.href = "{{ url('/paid_bills/print-bill-payment') }}" + "/" + res['ORNumber']
                },
                error : function(err) {
                    alert('An error occurred while performing the transaction. Contact support for more.')
                    console.log(err)
                }
            })
        }
    </script>
@endpush
