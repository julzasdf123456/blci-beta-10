@php
    use Illuminate\Support\Facades\Auth; 
    use App\Models\IDGenerator;
    use App\Models\ORAssigning;

    // GET PREVIOUS MONTHS
    for ($i = -1; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>BAPA Bills Payment Console - <strong>{{ $bapaName }}</strong></h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-8">
        <div class="card" style="height: 80vh;">
            <div class="card-header">
                <div class="card-tools">
                    <div class="spinner-border text-primary" role="status" id="spinner">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <span class="card-title">Select Billing Month</span>
                    </div>
                    <div class="col-lg-4">
                        <select name="ServicePeriod" id="ServicePeriod" class="form-control">
                            @for ($i = 0; $i < count($months); $i++)
                                <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="divider"></div>
                    <div class="col-lg-12">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-right">Total # of Accts:</td>
                                <th id="total-acts"></th>
                                <td class="text-right">Active Accts:</td>
                                <th id="active-total"></th>
                                <td class="text-right">Total # of Bills:</td>
                                <th id="bills-total"></th>
                                <td class="text-right">Individually Paid:</td>
                                <th id="indiv-paid"></th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <p><i>Press <strong>F3</strong> to search</i></p>
                <table class="table table-sm table-hover" id="accounts-table">
                    <thead>
                        <th>Account Number</th>
                        <th>Account Name</th>
                        <th>Account Status</th>
                        <th>kWh Used</th>
                        <th>Billing Mo.</th>
                        <th>Bill Number</th>
                        <th>Amount Due</th>
                        <th>Discount</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>Number of Consumers</td>
                        <td class="text-right">
                            <strong class="text-right" id="noOfConsumers">-</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>Total Amount</td>
                        <td class="text-right">
                            <strong class="text-right" id="totalAmount">-</strong>
                        </td>
                    </tr>
                    <tr>                        
                        <td>Discount Percentage</td>
                        <td class="text-right">
                            <strong class="text-right" id="discountPercentage">-</strong>
                        </td>
                    </tr>
                    <tr>                        
                        <td>Discount Amount</td>
                        <td class="text-right">
                            <strong class="text-right" id="discountAmount">-</strong>
                        </td>
                    </tr>
                    <tr>                        
                        <td>Amount Due</td>
                        <th class="text-right">
                            <strong><h3 class="text-right" id="amountDue">-</h3></strong>
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
            <div class="card-footer">
                <button id="cashBtn" class="btn btn-lg btn-primary float-right" disabled><i class="fas fa-dollar-sign"></i> Transact</button>
                <button id="checkBtn" class="btn btn-sm btn-default float-right ico-tab-mini" disabled data-toggle="modal" data-target="#modal-check-payment"><i class="fas fa-money-check-alt"></i> Check</button>
                <button id="cardBtn" class="btn btn-sm btn-default float-right ico-tab-mini" disabled><i class="fas fa-credit-card"></i> Debit/Credit Card</button>
            </div>
        </div>
    </div>
</div>
@endsection

@include('paid_bills.check_modal')

@push('page_scripts')
    <script>
        var accountsAdded = []
        var totalAmount = 0
        var change = 0
        var amountDue = 0
        var discountPercentage = 0
        var discountAmount = 0

        $(document).ready(function() {
            // init
            $('#spinner').show()

            fetchBills($('#ServicePeriod').val())

            $('#ServicePeriod').on('change', function() {                
                $('#spinner').show()

                totalAmount = 0
                change = 0
                amountDue = 0
                discountPercentage = 0
                discountAmount = 0
                $('#amountPaid').val('')
                updatePaymentDisplays()

                fetchBills(this.value)
            })

            // AMOUNT PAID ON KEY PRESSED
            $('#amountPaid').keyup(function() {
                change = (parseFloat(this.value) - amountDue).toFixed(2).toLocaleString()

                if (parseFloat(change)) {
                    $('#change').val(change)
                    if (change > -1 && !jQuery.isEmptyObject($('#orNumber').val()) && !jQuery.isEmptyObject(accountsAdded)) {
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
                    if (change > -1 && !jQuery.isEmptyObject($('#orNumber').val()) && !jQuery.isEmptyObject(accountsAdded)) {  
                        transact('Cash')                     
                    } else {

                    }
                }
            })

            // TRANSACT CHECK
            $('#save-check-transaction').on('click', function() {
                transact('Check')
            })

            // OR NO ON KEY PRESSED
            $('#orNumber').keyup(function() {
                change = (parseFloat($('#amountPaid').val()) - amountDue).toFixed(2).toLocaleString()

                if (parseFloat(change)) {
                    $('#change').val(change)
                    if (change > -1 && !jQuery.isEmptyObject(this.value) && !jQuery.isEmptyObject(accountsAdded)) {
                        buttonEnablers(true)                        
                    } else {
                        buttonEnablers(false)
                    }
                } else {
                    $('#change').val('')
                    buttonEnablers(false)
                }                
            })
        })

        function fetchBills(period) {
            $('#accounts-table tbody tr').remove()
            $('#ServicePeriod').attr('disabled', 'disabled')
            $.ajax({
                url : "{{ route('paidBills.get-adjusted-bapa-bills') }}",
                type : 'GET',
                data : {
                    BAPAName : "{{ $bapaName }}",
                    Period : period,
                },
                success : function(res) {
                    getDetails(res)
                    $.each(res, function(index, element) {
                        $('#accounts-table tbody').append(addToRow(res[index]['AccountNumber'],
                            res[index]['ServiceAccountName'],
                            res[index]['AccountStatus'],
                            res[index]['KwhUsed'],
                            res[index]['ServicePeriod'],
                            res[index]['BillNumber'],
                            res[index]['NetAmount'],
                            res[index]['ORNumber'],
                            res[index]['DiscountPercentage'],
                            res[index]['DiscountAmount'],
                            res[index]['OldAccountNo']))
                    })
                    $('#spinner').hide()
                    $('#ServicePeriod').removeAttr('disabled')
                },
                error : function(err) {
                    alert('An error occurred while fetching accounts')
                    $('#ServicePeriod').removeAttr('disabled')
                }
            })
        }

        function addToRow(acctNo, acctName, status, kwhused, billmo, billno, amtdue, or, discountPercentage, discountAmount, oldAcctNo) {
            if (billno != null) {
                if (or != null) { // PAID AREA
                    return '<tr title="OR Number: ' + or + '" class="bg-teal disabled">' +
                                '<td><i class="ico-tab fas fa-check text-success"></i>' + oldAcctNo + '</td>' +
                                '<td>' + acctName + '</td>' +
                                '<td>' + status + '</td>' +
                                '<td class="text-right">' + Number(parseFloat(kwhused).toFixed(2)).toLocaleString() + '</td>' +
                                '<td>' + billmo + '</td>' +
                                '<td class="text-right">' + (billno != null ? billno : '') + '</td>' +
                                '<td class="text-right">' + (parseFloat(amtdue) ? Number(parseFloat(amtdue).toFixed(2)).toLocaleString() : '') + '</td>' +
                                '<td class="text-right"></td>' +
                            '</tr>';
                } else { // PAYABLE AREA
                    if (billmo == $('#ServicePeriod').val()) {
                        return '<tr>' +
                                    '<td><i class="ico-tab fas fa-exclamation text-primary"></i>' + oldAcctNo + '</td>' +
                                    '<td>' + acctName + '</td>' +
                                    '<td>' + status + '</td>' +
                                    '<td class="text-right">' + Number(parseFloat(kwhused).toFixed(2)).toLocaleString() + '</td>' +
                                    '<td>' + billmo + '</td>' +
                                    '<td class="text-right">' + (billno != null ? billno : '') + '</td>' +
                                    '<td class="text-right">' + (parseFloat(amtdue) ? Number(parseFloat(amtdue).toFixed(2)).toLocaleString() : '') + '</td>' +
                                    '<td class="text-right">' + (parseFloat(discountAmount) ? Number(parseFloat(discountAmount).toFixed(2)).toLocaleString() : '') + '</td>' +
                                '</tr>';
                    } else { // arears
                        return '<tr style="background-color: #ffcdd2;">' +
                                    '<td><i class="ico-tab fas fa-exclamation text-primary"></i>' + oldAcctNo + '</td>' +
                                    '<td>' + acctName + '</td>' +
                                    '<td>' + status + '</td>' +
                                    '<td class="text-right">' + Number(parseFloat(kwhused).toFixed(2)).toLocaleString() + '</td>' +
                                    '<td>' + billmo + '</td>' +
                                    '<td class="text-right">' + (billno != null ? billno : '') + '</td>' +
                                    '<td class="text-right">' + (parseFloat(amtdue) ? Number(parseFloat(amtdue).toFixed(2)).toLocaleString() : '') + '</td>' +
                                    '<td class="text-right">' + (parseFloat(discountAmount) ? Number(parseFloat(discountAmount).toFixed(2)).toLocaleString() : '') + '</td>' +
                                '</tr>';
                    }                    
                }
            } else { // UNBILLED AREA
                return '<tr class="bg-secondary disabled">' +
                                '<td><i class="ico-tab fas fa-exclamation text-danger"></i>' + oldAcctNo + '</td>' +
                                '<td>' + acctName + '</td>' +
                                '<td>' + status + '</td>' +
                                '<td class="text-right">' + Number(parseFloat(kwhused).toFixed(2)).toLocaleString() + '</td>' +
                                '<td>' + billmo + '</td>' +
                                '<td class="text-right">' + (billno != null ? billno : '') + '</td>' +
                                '<td class="text-right">' + (parseFloat(amtdue) ? Number(parseFloat(amtdue).toFixed(2)).toLocaleString() : '') + '</td>' +
                                '<td></td>' +
                            '</tr>';
            }
        }

        function updatePaymentDisplays() {
            $('#totalAmount').text(Number(totalAmount.toFixed(2)).toLocaleString())
            $('#amountDue').text(Number(amountDue.toFixed(2)).toLocaleString())
            $('#amountPaid').focus()

            change = (parseFloat($('#amountPaid').val()) - amountDue).toFixed(2).toLocaleString()

            if (parseFloat(change)) {
                $('#change').val(change)
                if (change > -1 && !jQuery.isEmptyObject($('#orNumber').val()) && !jQuery.isEmptyObject(accountsAdded)) {
                    buttonEnablers(true)                        
                } else {
                    buttonEnablers(false)
                }
            } else {
                $('#change').val('')
                buttonEnablers(false)
            } 
        }

        function removeItemFromArray(id) {
            var index = accountsAdded.indexOf(id)
            if (index > -1) {
                accountsAdded.splice(index, 1)
            }
        }

        function getDetails(res) {
            if (!jQuery.isEmptyObject(res)) {
                var ttlAccts = 0;
                var ttlActive = 0;
                var ttlBills = 0;
                var ttlPaid = 0;
                $.each(res, function(index, element) {
                    if (res[index]['AccountStatus'] != 'DISCONNECTED') {
                        ttlActive += 1;
                    }

                    if (!jQuery.isEmptyObject(res[index]['ORNumber'])) {
                        ttlPaid += 1;
                    }

                    if (!jQuery.isEmptyObject(res[index]['BillNumber'])) {
                        ttlBills += 1;
                        // COMPUTE TOTAL ON INITALIZE
                        if (jQuery.isEmptyObject(res[index]['ORNumber'])){
                            // ADD ACCOUNTS ON INITIALIZE
                            accountsAdded.push(res[index]['id'])
                            totalAmount += Number(parseFloat(res[index]['NetAmount']).toFixed(2))
                            discountAmount += Number(parseFloat(res[index]['DiscountAmount']).toFixed(2))

                            // GET PERCENTAGE
                            if (index == 0) {
                                discountPercentage = parseFloat(res[index]['DiscountPercentage']) * 100
                            }
                        }                        
                    }

                    ttlAccts += 1;

                    // SHOW AMOUNTS
                    amountDue = (totalAmount - discountAmount)
                    $('#totalAmount').text(Number(totalAmount.toFixed(2)).toLocaleString())
                    $('#amountDue').text(Number(amountDue.toFixed(2)).toLocaleString())
                    $('#discountPercentage').text(Number(discountPercentage.toFixed(2)) + " %")
                    $('#discountAmount').text('-' + Number(discountAmount.toFixed(2)).toLocaleString())
                    $('#noOfConsumers').text(accountsAdded.length)
                })

                $('#total-acts').text(ttlAccts)
                $('#active-total').text(ttlActive)
                $('#bills-total').text(ttlBills)
                $('#indiv-paid').text(ttlPaid)
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

        // DETECT ENTER
        $(document).keydown(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);  
            if(keycode == '13'){
                if ($('#modal-check-payment').hasClass('show')) {
                    // ENTER KEY IS DISABLED IF SHOW CHECK MODAL IS SHOWN
                } else {
                    if (parseFloat(change)) {
                        if (change > -1 && !jQuery.isEmptyObject($('#orNumber').val()) && !jQuery.isEmptyObject(accountsAdded)) {  
                            transact('Cash')                     
                        } else {

                        }
                    } else {

                    }
                }                
            } 
        });

        function transact(paymentUsed) {
            $('#spinner').show()
            $.ajax({
                url : "{{ route('paidBills.save-bapa-payments') }}",
                type : 'GET',
                data : {
                    Period : $('#ServicePeriod').val(),
                    AccountNumbers : accountsAdded,
                    TotalAmountPaid : amountDue,
                    ORNumber : "{{ $orAssignedLast->ORNumber }}",
                    BAPAName : "{{ urlencode($bapaName) }}",
                    SubTotal : totalAmount,
                    DiscountAmount : discountAmount,
                    PaymentUsed : paymentUsed,
                    CheckNo : $('#checkNo').val(),
                    Bank : $('#bank').val()
                },
                success : function(res) {
                    // alert('PRINT OR BAPA')
                    // location.reload()
                    $('#spinner').hide()
                    window.location.href = "{{ url('/paid_bills/print-bapa-payments') }}" + "/" + res
                    console.log(res)
                },
                error : function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred during the transaction.',
                    })
                    $('#spinner').hide()
                }
            })
        }
    </script>
@endpush