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
                <div class="col-sm-5">
                    <h4>Bills Payment</h4>
                </div>
                <div class="col-sm-5">
                    <div class="form-row align-items-center float-right">
                        <div class="col-auto">
                            <input class="form-control" id="old-account-no" autocomplete="off" data-inputmask="'alias': 'phonebe'" maxlength="12" value="{{ env('APP_AREA_CODE') }}" style="font-size: 1.5em; color: #b91400; font-weight: bold;">
                        </div>
                    </div>    
                </div>
                <div class="col-sm-2">
                    {{-- <button class="btn btn-warning float-right" title="Search initial OR Number"  data-toggle="modal" data-target="#modal-set" style="margin-left: 20px;"><i class="fas fa-tools ico-tab"></i>Set Start OR</button> --}}
                    <button class="btn btn-success btn-sm float-right" title="Search Consumer"  data-toggle="modal" data-target="#modal-search"><i class="fas fa-search-dollar ico-tab"></i>Advanced Search <strong>(F2)</strong></button>                   
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            {{-- QUEUE --}}
            <div class="col-lg-7 col-md-6">
                {{-- DETAILS CARD --}}
                <div class="card" style="height: 60vh;">
                    <div class="card-header border-0">
                        <span class="card-title">
                            <h4 id="account-name" style="font-weight: bold; font-size: 1.5em;">...</h4>
                            <address class="text-muted" id="account-number">...</address>
                        </span>
                    </div>
                    <div class="card-body table-responsive px-0">
                        <table class="table table-hover" id="payables-table">
                            <thead>
                                <th>Bill No.</th>
                                <th>Billing Month</th>
                                <th>Due Date</th>
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
            <div class="col-lg-5 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td>Amount Due</td>
                                    <td class="text-right">
                                        <strong class="text-right" id="amount-due"></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Deductions/Subsidies</td>
                                    <td class="text-right">
                                        <strong class="text-right" id="deductions"></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Additional Charges</td>
                                    <td class="text-right">
                                        <strong class="text-right" id="additional-charges"></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Surcharges</td>
                                    <td class="text-right">
                                        <strong class="text-right" id="surcharges"></strong>
                                    </td>
                                </tr>
                                <tr>                        
                                    <td>
                                        <div class="input-group">
                                            <input type="hidden" value="" name="Vat2">
                                            <input type="checkbox" value="" name="Vat2" id="Vat2" class="custom-checkbox">
                                            <label for="Vat2">Witholding (2%)</label>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <strong class="text-right" id="vat2">-</strong>
                                    </td>
                                </tr>
                                <tr>                        
                                    <td>
                                        <div class="input-group">
                                            <input type="hidden" value="" name="Vat5">
                                            <input type="checkbox" value="" name="Vat5" id="Vat5" class="custom-checkbox">
                                            <label for="Vat5">Creditable VAT (5%)</label>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <strong class="text-right" id="vat5">-</strong>
                                    </td>
                                </tr>
                                <tr style="border-top: 1px solid #dcdcdc">
                                    <th>Total Amount Due</th>
                                    <th class="text-right">
                                        <h3 class="text-right text-danger" style="font-size: 1.5em; color: #b91400; font-weight: bold;" id="total-amount"></h3>
                                    </th>
                                </tr>                                    
                                <tr>
                                    <td>OR Number</td>
                                    <td class="text-right">
                                        <input readonly='true' type="number" class="form-control text-right float-left" style="font-size: 1.5em; color: #b91400; font-weight: bold; width: 84%; display: inline-block;" id="orNumber" value="{{ ORAssigning::getORIncrement(1, $orAssignedLast) }}">
                                        <button id="unlock-btn" class="btn btn-warning float-right ico-tab-mini" title="Unlock to edit OR number"><i class="fas fa-unlock"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cash Payment</td>
                                    <td class="text-right">
                                        <input type="number" class="form-control text-right float-left" style="font-size: 1.2em; width: 84%; display: inline-block;" id="cashAmount" step="any">
                                        <button id="denominationBtn" disabled class="btn btn-warning float-right ico-tab-mini" data-toggle="modal" data-target="#modal-denominate" title="Add Denomination"><i class="fas fa-list"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Check Payments</td>
                                    <td class="text-right">
                                        <button id="addCheckBtn" class="btn btn-xs btn-primary float-right ico-tab-mini" data-toggle="modal" data-target="#modal-check-payment"><i class="fas fa-plus ico-tab-mini"></i>Add Check</button>
                                    </td>
                                </tr>
                            </table>

                            {{-- CHECK PAYMENTS --}}
                            <table class="table table-borderless table-sm" id="check-table">

                            </table>

                            {{-- TOTAL --}}
                            <table class="table table-borderless table-sm">                                
                                <tr style="border-top: 1px solid #dcdcdc">
                                    <td>Total Amount Paid</td>
                                    <td class="text-right">
                                        <input type="number" class="form-control text-right" style="font-size: 1.2em;" id="amountPaid" step="any" readonly="true">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Change</td>
                                    <td class="text-right">
                                        <input type="text" class="form-control text-right" style="font-size: 1.2em;" id="change" readonly="true">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button id="sumOrBtn" class="btn btn-lg btn-warning" data-toggle="modal" data-target="#modal-sum-or"><i class="fas fa-plus-circle ico-tab-mini"></i> Sum OR</button>
                        <button id="cashBtn" class="btn btn-lg btn-primary float-right" disabled><i class="fas fa-dollar-sign"></i> Transact</button>
                        {{-- <button id="checkBtn" class="btn btn-sm btn-default float-right ico-tab-mini" disabled data-toggle="modal" data-target="#modal-check-payment"><i class="fas fa-money-check-alt"></i> Check</button> --}}
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
                        <th>Account ID</th>
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

@include('paid_bills.modal_confirm_payment')

@include('paid_bills.check_modal')

@include('paid_bills.modal_sum_or')

@include('paid_bills.modal_denomination')

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
        var vat2 = 0
        var vat5 = 0
        var vat2Checked = false
        var vat5Checked = false
        var checkAmountTotal = 0.0

        var selectedPayments = []
        var checkIds = []

        var hasTransacted = false

        var confirmModalShown = false
        var sumORModalShown = false

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

            $("#old-account-no").off('keyup').on('keyup', function(event) {
                if (this.value.length == 12) {
                    searchOldAccountNumber()
                }
            })

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

            $('#addCheckBtn').on('click', function() {
                $('#checkAmount').val(parseFloat(totalAmount).toFixed(2))
                $('#cashAmount').val('').change()
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

            // AMOUNT PAID ON CHANGE
            $('#amountPaid').on('change', function() {
                change = (parseFloat(this.value) - totalAmount).toFixed(2)
                
                if (parseFloat(change) || $('#amountPaid').val() == parseFloat(totalAmount).toFixed(2)) {
                    $('#change').val(change)

                    if (change >= 0 && !jQuery.isEmptyObject($('#orNumber').val()) && !jQuery.isEmptyObject(selectedPayments)) {
                        buttonEnablers(true)                        
                    } else {
                        buttonEnablers(false)
                    }
                } else {
                    $('#change').val('')
                    buttonEnablers(false)                    
                }                
            })

            $('#cashAmount').keyup(function() {
                computePayments()
            })

            $('#cashAmount').on('change', function() {
                computePayments()
            })

            $('#unlock-btn').on('click', function() {
                $('#orNumber').removeAttr('readonly')
            })

            // CASH BUTTON EVENT
            $('#cashBtn').on('click', function() {
                if (parseFloat(change) || $('#amountPaid').val() == parseFloat(totalAmount).toFixed(2)) {
                    if (change >= 0) {
                        if ($('#modal-confirm-payment').hasClass('show')) {
                            // transact because modal is shown alread
                            transact('Cash')         
                        } else {
                            // show confirm modal first
                            $('#modal-confirm-payment').modal('show');
                        }          
                    } else {

                    }
                } else {

                }  
            })

            $('#confirm-modal-btn').on('click', function() {
                if (parseFloat(change) || $('#amountPaid').val() == parseFloat(totalAmount).toFixed(2)) {
                    if (change >= 0) {
                        if ($('#modal-confirm-payment').hasClass('show')) {
                            // transact because modal is shown alread
                            transact('Cash')         
                        } else {
                            // show confirm modal first
                            $('#modal-confirm-payment').modal('show');
                        }          
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
                $('#search').val($('#old-account-no').val()).keyup()
            })

            // DISCOUNT 3% CHANGE
            $('#Vat2').change(function() {
                if($('#Vat2').prop('checked')) {
                    vat2Checked = true                           
                } else {
                    vat2Checked = false                    
                }
                computePayables()
                updatePaymentDisplays()
            })

            $('#Vat5').change(function() {
                if($('#Vat5').prop('checked')) {
                    vat5Checked = true                            
                } else {
                    vat5Checked = false                  
                }
                computePayables()
                updatePaymentDisplays()
            })

            // TRANSACT CHECK
            $('#save-check-transaction').on('click', function() {
                addCheck()           
            })

            // MODAL CONFIRM SHOW EVENT
            $("#modal-confirm-payment" ).on('shown.bs.modal', function(){      
                /**
                 *  DISPLAY MODAL
                 **/
                $('#cash-modal-confirm').val($('#cashAmount').val()).focus().select()
                $('#check-modal-confirm').val(parseFloat(checkAmountTotal).toFixed(2))
                
                var cashAmnt = parseFloat($('#cashAmount').val()) ? parseFloat($('#cashAmount').val()) : 0
                var totalAll = cashAmnt + checkAmountTotal

                $('#total-modal-confirm').val(parseFloat(totalAll).toFixed(2))
                $('#amntdue-modal-confirm').val(parseFloat(totalAmount).toFixed(2))
                $('#change-modal-confirm').val(change)
            });

            // MODAL CONFIRM HIDE EVENT
            $('#modal-confirm-payment').on('hidden.bs.modal', function () {
                $('#cashAmount').focus().select()
            })

            // MODAL CONFIRM CASH INPUT
            $('#cash-modal-confirm').keyup(function() {
                $('#cashAmount').val(this.value).change()
                computePayables()
                var cashAmnt = parseFloat($('#cashAmount').val()) ? parseFloat($('#cashAmount').val()) : 0
                var totalAll = cashAmnt + checkAmountTotal
                $('#total-modal-confirm').val(parseFloat(totalAll).toFixed(2))
                $('#change-modal-confirm').val(change)
            })

            /**
             *  SUM OR
             **/
            $('#modal-sum-or').on('shown.bs.modal', function(){      
                sumORModalShown = true    
            });

            $('#modal-sum-or').on('hidden.bs.modal', function(){      
                sumORModalShown = false
                $('#cashAmount').focus()
            });

            /**
             *  DENOMINATIONS
             **/
            $('#save-denomination').on('click', function() {
                saveDenomination()
            })
        })

        function addToPayables(id) {
            if ($('#' + id).attr('ischecked') == 'false') {
                $('#' + id).removeClass('text-muted').addClass('text-primary')

                // ADD TO ARRAY
                selectedPayments.push(id)

                $('#' + id).attr('ischecked', 'true')
                billId = id

                $('#denominationBtn').removeAttr('disabled')
            } else {
                $('#' + id).removeClass('text-primary').addClass('text-muted')

                // REMOVE ITEM FROM ARRAY
                removeItemFromArray(id)

                $('#' + id).attr('ischecked', 'false')
                billId = ''
            }

            // VALIDATE FORM
            computePayables(selectedPayments)
            updatePaymentDisplays()

            // VALIDATE BUTTONS
            change = (parseFloat($('#amountPaid').val()) - totalAmount).toFixed(2).toLocaleString()

            if (parseFloat(change) || $('#amountPaid').val() == parseFloat(totalAmount).toFixed(2)) {
                $('#change').val(change)
                if (change >= 0 && !jQuery.isEmptyObject($('#orNumber').val()) && !jQuery.isEmptyObject(selectedPayments)) {
                    buttonEnablers(true)                        
                } else {
                    buttonEnablers(false)
                }
            } else {
                $('#change').val('')
                buttonEnablers(false)
            } 
        }

        function updatePaymentDisplays() {
            $('#amount-due').text('P ' + Number(totalAmount.toFixed(2)).toLocaleString())
            $('#total-amount').text('P ' + Number(totalAmount.toFixed(2)).toLocaleString())
            $('#additional-charges').text('P ' + Number(additionals.toFixed(2)).toLocaleString())
            $('#deductions').text('P ' + Number(deductions.toFixed(2)).toLocaleString())
            $('#surcharges').text('P ' + Number(surcharge.toFixed(2)).toLocaleString())
            $('#cashAmount').val(parseFloat(totalAmount).toFixed(2)).change()
            $('#cashAmount').focus().select()

            if(vat2Checked == true) {
                $('#vat2').text(vat2)
            } else {
                $('#vat2').text('-')
            }

            // COMPUTE 5%
            if(vat5Checked == true) {
                $('#vat5').text(vat5)        
            } else {
                $('#vat5').text('-')
            }
        }

        function computePayables() {
            var len = selectedPayments.length
            totalAmount = 0.0
            deductions = 0
            additionals = 0
            surcharge = 0
            vat2 = 0
            vat5 = 0

            for(var i=0; i<len; i++) {
                var additionalCharges = (parseFloat($('#' + selectedPayments[i]).attr('additionalCharges')) ? parseFloat($('#' + selectedPayments[i]).attr('additionalCharges')) : 0)
                additionals += additionalCharges

                var deductibles = (parseFloat($('#' + selectedPayments[i]).attr('deductions')) ? parseFloat($('#' + selectedPayments[i]).attr('deductions')) : 0)
                deductions += deductibles

                var surcharges = (parseFloat($('#' + selectedPayments[i]).attr('surcharge')) ? parseFloat($('#' + selectedPayments[i]).attr('surcharge')) : 0)
                surcharge += surcharges

                var amount = (parseFloat($('#' + selectedPayments[i]).attr('amount')) ? parseFloat($('#' + selectedPayments[i]).attr('amount')) : 0)
                totalAmount += (amount + surcharges)

                var ewt = (parseFloat($('#' + selectedPayments[i]).attr('ewt')) ? parseFloat($('#' + selectedPayments[i]).attr('ewt')) : 0)
                vat2 += ewt

                var evat = (parseFloat($('#' + selectedPayments[i]).attr('evat')) ? parseFloat($('#' + selectedPayments[i]).attr('evat')) : 0)
                vat5 += evat
            }

            // COMPUTE 2%
            if(vat2Checked == true) {
                totalAmount = totalAmount - vat2              
            } 

            // COMPUTE 5%
            if(vat5Checked == true) {
                totalAmount = totalAmount + vat5                 
            } 
        }

        function computePayments() {
            var cashAmnt = parseFloat($('#cashAmount').val()) ? parseFloat($('#cashAmount').val()) : 0
            var totalX = cashAmnt + checkAmountTotal
            $('#amountPaid').val(totalX.toFixed(2)).change()
        }

        function requestUnlock(id) {
            $.ajax({
                url : '{{ route("paidBills.request-bills-payment-unlock") }}',
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

        function removeCheckFromArray(id) {
            var index = checkIds.indexOf(id)
            if (index > -1) {
                checkIds.splice(index, 1)
            }
        }

        function buttonEnablers(bool) {
            if (bool) {
                // enable
                $('#cashBtn').removeAttr('disabled')   
                $('#checkBtn').removeAttr('disabled')  
                $('#cardBtn').removeAttr('disabled')   
                $('#confirm-modal-btn').removeAttr('disabled')            
            } else {
                // disable
                $('#cashBtn').attr('disabled', 'true')
                $('#checkBtn').attr('disabled', 'true')
                $('#cardBtn').attr('disabled', 'true')
                $('#confirm-modal-btn').attr('disabled', 'true')
            }   
        }

        function performSearch(regex) {
            $.ajax({
                url : '{{ route("paidBills.search") }}',
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

            totalAmount = 0.0
            deductions = 0
            additionals = 0
            surcharge = 0
            vat2 = 0
            vat5 = 0
            selectedPayments = []
            checkIds = []
            checkAmountTotal = 0.0
            acctNo = ''
            billId = ''
            svcPeriod = ''

            // FETCH ACCOUNT DETAILS
            $.ajax({
                url : '{{ route("paidBills.fetch-account") }}',
                type : 'GET',
                data : {
                    AccountNumber : id,
                },
                success : function(res) {
                    $('#payables-table tbody tr').remove();
                    if (jQuery.isEmptyObject(res)) {

                    } else {
                        $('#account-name').html('<i class="fas fa-check-circle text-success ico-tab"></i>' + res['ServiceAccountName'])
                        acctNo = id
                    }
                    $('#account-number').text(res['OldAccountNo'])
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
                url : '{{ route("paidBills.fetch-details") }}',
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
            // buttonEnablers(false)
            // $('#amountPaid').val('')
            // $('#change').val('')
            // $.ajax({
            //     url : '/paid_bills/fetch-payable',
            //     type : 'GET',
            //     data : {
            //         BillId : id
            //     },
            //     success : function(res) {
            //         if (jQuery.isEmptyObject(res)) {
            //             $('#bill-no').text('-')
            //             $('#service-from').text('-')
            //             $('#service-to').text('-')
            //             $('#due-date').text('-')
            //             $('#kwh-used').text('-')
            //             $('#rate').text('-')
            //             $('#amount-due').text('-')
            //             $('#additional-charges').text('-')
            //             $('#deductions').text('-')
            //             $('#total-amount').text('-')
            //             $('#surcharges').text('-')
            //         } else {
            //             $('#cashAmount').focus()  
            //             // INVOICE
            //             $('#bill-no').text(res['BillNumber'])
            //             $('#service-from').text(moment(res['ServiceDateFrom']).format('LL'))
            //             $('#service-to').text(moment(res['ServiceDateTo']).format('LL'))
            //             $('#due-date').text(moment(res['DueDate']).format('LL'))
            //             $('#kwh-used').text(res['KwhUsed'])
            //             $('#rate').text((parseFloat(res['EffectiveRate']).toFixed(4)).toLocaleString())

            //             // GLOBAL VARIABLES
            //             billNumber = res['BillNumber']
            //             acctNo = res['AccountNumber']
            //             svcPeriod = res['ServicePeriod']
            //             kwhUsed = res['KwhUsed']
            //             billId = id

            //             // PAYABLES
            //             deductions = parseFloat(res['Deductions'] != null ? res['Deductions'] : 0)
            //             additionals = parseFloat(res['AdditionalCharges'] != null ? res['AdditionalCharges'] : 0)
            //             dbAmount = parseFloat(res['NetAmount'] != null ? res['NetAmount'] : 0)
            //             netAmount = dbAmount - additionals + deductions
            //             surcharge = 0

            //             var today = moment()
            //             var dueDate = moment(res['DueDate'])
            //             var dueDateDiff = today.diff(dueDate, 'days') 
                        
            //             if (dueDateDiff > 0) {
            //                 // ADD DUE DATE
            //                 surcharge = (dbAmount * .3)
            //                 $('#surcharges').text('+ ₱ ' + surcharge.toFixed(2).toLocaleString())
            //             } else {
            //                 $('#surcharges').text('+ ₱ 0')
            //                 surcharge = 0
            //             }

            //             totalAmount = dbAmount + surcharge

            //             $('#amount-due').text('₱ ' + netAmount.toFixed(2).toLocaleString())
            //             $('#additional-charges').text('+ ₱ ' + additionals.toFixed(2).toLocaleString())
            //             $('#deductions').text('- ₱ ' + deductions.toFixed(2).toLocaleString())
            //             $('#total-amount').text('₱ ' + totalAmount.toFixed(2).toLocaleString())
            //         }
            //     },
            //     error : function(err) {
            //         console.log(err)
            //         alert('An error occurred while fetching the bill\n' + err)
            //     }
            // })
        }

        // DETECT ENTER
        $(document).keydown(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);  
            if(keycode == '13'){
                if ($('#modal-check-payment').hasClass('show') | sumORModalShown) {
                    // ENTER KEY IS DISABLED IF SHOW CHECK MODAL OR SUM OR IS SHOWN
                } else {
                    if (parseFloat(change) || $('#amountPaid').val() == parseFloat(totalAmount).toFixed(2)) {
                        if (change >= 0 && !jQuery.isEmptyObject($('#orNumber').val()) && !jQuery.isEmptyObject(selectedPayments)) {  
                            if ($('#modal-confirm-payment').hasClass('show')) {
                                // transact because modal is shown alread
                                transact('Cash')         
                            } else {
                                // show confirm modal first
                                $('#modal-confirm-payment').modal('show');
                            }                                        
                        } else {

                        }
                    } else {

                    }
                }
                
            } else if (keycode == 113) {
                $('#modal-search').modal('show')
            }
        });

        function transact(paymentUsed) {            
            if (!hasTransacted) {
                hasTransacted = true
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
                    url : '{{ route("paidBills.save-paid-bill-and-print") }}',
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
                        Ewt : vat2Checked,
                        VAT : vat5Checked,
                        PaymentUsed : paymentUsed,
                        CheckNo : $('#checkNo').val(),
                        Bank : $('#bank').val(),
                        CheckIds : checkIds,
                        CashAmount : $('#cashAmount').val(),
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
            
        }

        function addCheck() {
            if (jQuery.isEmptyObject(billId)) {
                Swal.fire({
                    title: 'Error',
                    text: "Select consumer's bill first!",
                    icon: 'error',
                })
            } else {
                if (jQuery.isEmptyObject($('#checkAmount').val()) || jQuery.isEmptyObject($('#checkNo').val())) {
                    Swal.fire({
                        title: 'Oops...',
                        text: 'Provide the Check Number and Amount first!',
                        icon: 'error',
                    })
                } else {
                    $.ajax({
                        url : "{{ route('paidBills.add-check-payments') }}",
                        type : 'GET',
                        data : {
                            AccountNumber : acctNo,
                            ServicePeriod : svcPeriod,
                            BillId : billId,
                            ORNumber : $('#orNumber').val(),
                            Amount : $('#checkAmount').val(),
                            CheckNo : $('#checkNo').val(),
                            Bank : $('#bank').val(),
                            CheckExpiration : null,
                        },
                        success : function(res) {
                            checkIds.push(res['id'])
                            $('#check-table').append(addCheckToTable(res['id'], res['Bank'], res['Amount'], res['CheckNo']))
                            $('#modal-check-payment').modal('hide')

                            clearModalFields()

                            // compute payments
                            checkAmountTotal = checkAmountTotal + parseFloat(res['Amount'])
                            computePayments()
                        },
                        error : function(err) {
                            $('#modal-check-payment').modal('hide')
                            Swal.fire({
                                title: 'Oops...',
                                text: 'An error occurred while adding the check. Contact support immediately!',
                                icon: 'error',
                            })
                        }
                    })
                }                
            }            
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
                url : "{{ route('paidBills.delete-check-payment') }}",
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

        function clearModalFields() {
            // clear fields
            $('#checkAmount').val('')
            $('#checkNo').val('')
        }

        function searchOldAccountNumber() {
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
            $('#account-name').html('')
            $('#account-number').text('')
            $('#payables-table tbody tr').remove()
            $('#cashAmount').val('')
            $('#amountPaid').val('')
            $('#change').val('')
            $('#check-table tr').remove()
            
            totalAmount = 0.0
            deductions = 0
            additionals = 0
            surcharge = 0
            vat2 = 0
            vat5 = 0
            selectedPayments = []
            checkIds = []
            checkAmountTotal = 0.0
            acctNo = ''
            billId = ''
            svcPeriod = ''

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

        /**
         *  DENOMINATIONS
         **/
         $('#modal-denominate').on('hidden.bs.modal', function () {
            $('#cashAmount').focus().select()
        })

        function saveDenomination() {
            var thousand = jQuery.isEmptyObject($('#thousand').val()) ? '' : $('#thousand').val()
            var fivehundred = jQuery.isEmptyObject($('#fivehundred').val()) ? '' : $('#fivehundred').val()
            var onehundred = jQuery.isEmptyObject($('#onehundred').val()) ? '' : $('#onehundred').val()
            var fifty = jQuery.isEmptyObject($('#fifty').val()) ? '' : $('#fifty').val()
            var twenty = jQuery.isEmptyObject($('#twenty').val()) ? '' : $('#twenty').val()
            var ten = jQuery.isEmptyObject($('#ten').val()) ? '' : $('#ten').val()
            var five = jQuery.isEmptyObject($('#five').val()) ? '' : $('#five').val()
            var one = jQuery.isEmptyObject($('#one').val()) ? '' : $('#one').val()
            var cents = jQuery.isEmptyObject($('#cents').val()) ? '' : $('#cents').val()

            $.ajax({
                url : "{{ route('paidBills.add-denomination') }}",
                type : 'GET',
                data : {
                    AccountNumber : acctNo,
                    ServicePeriod : '',
                    ORNumber : $('#orNumber').val(),
                    OneThousand : thousand,
                    FiveHundred : fivehundred,
                    OneHundred : onehundred,
                    Fifty : fifty,
                    Twenty : twenty,
                    Ten : ten,
                    Five : five,
                    Peso : one,
                    Cents : cents,
                },
                success : function(res) {
                    if (!jQuery.isEmptyObject($('#thousand').val())) {
                        $('#thousand').val(res['OneThousand'])
                        $('#fivehundred').val(res['FiveHundred'])
                        $('#onehundred').val(res['OneHundred'])
                        $('#fifty').val(res['Fifty'])
                        $('#twenty').val(res['Twenty'])
                        $('#ten').val(res['Ten'])
                        $('#five').val(res['Five'])
                        $('#one').val(res['Peso'])
                        $('#cents').val(res['Cents'])
                    }
                    $('#modal-denominate').modal('hide')
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Oops!',
                        text : 'An error occurred while inserting the denominations',
                        icon : 'error'
                    })
                }
            })
        }
    </script>
@endpush
