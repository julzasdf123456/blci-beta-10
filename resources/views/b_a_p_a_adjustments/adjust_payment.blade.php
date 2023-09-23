@php
    use Illuminate\Support\Facades\Auth; 
    use App\Models\IDGenerator;
    use App\Models\ORAssigning;

    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>BAPA Payment Adjustment Console - <strong>{{ $bapaName }}</strong></h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-none" style="height: 80vh;">
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
                                <option value="{{ $months[$i] }}" {{ $rate!=null && date('Y-m-d', strtotime($rate->ServicePeriod))==$months[$i] ? 'selected' : '' }}>{{ date('F Y', strtotime($months[$i])) }}</option>
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
                <span><i>Press <strong>F3</strong> to search</i></span>
                <input class="form-control float-right" id="old-account-no" data-inputmask="'alias': 'phonebe'" maxlength="12" value="{{ env('APP_AREA_CODE') }}" style="font-size: 1.3em; color: #b91400; font-weight: bold; width: 250px;">
                <table class="table table-sm table-hover" id="accounts-table">
                    <thead>
                        <th>Account Number</th>
                        <th>Account Name</th>
                        <th>Account Status</th>
                        <th>kWh Used</th>
                        <th>Billing Mo.</th>
                        <th>Bill Number</th>
                        <th>Amount Due</th>
                        <th></th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- FORM DISCOUNT --}}
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td>Select Discount</td>
                        <td class="text-right">
                            <select id="discountPercent" class="form-control form-control-sm">
                                <option value="0">No Discount</option>
                                <option value=".08">8% (3% + 5%)</option>
                                <option value=".05">5%</option>
                                <option value=".03">3%</option>
                                <option value=".034">3.4%</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Total # of Consumers To Pay</td>
                        <td class="text-right">
                            <h4 class="text-right" id="totalConsumersToPay">-</h4>
                        </td>
                    </tr>
                    <tr>
                        <td>Total Amount</td>
                        <td class="text-right">
                            <h4 class="text-right" id="totalAmount">-</h4>
                        </td>
                    </tr>
                    <tr>
                        <td>Discount Amount</td>
                        <td class="text-right">
                            <h4 class="text-right" id="discountAmount">0</h4>
                        </td>
                    </tr>
                    <tr>                        
                        <td>Amount Due</td>
                        <td class="text-right">
                            <h4 class="text-right" id="amountDue">-</h4>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <button id="saveBtn" class="btn btn-primary float-right" disabled=true><i class="fas fa-check ico-tab-mini"></i> Save</button>
            </div>
        </div>

        {{-- LIST OF PAID --}}
        <div class="card shadow-none">
            <div class="card-body">
                <table id="queues-list" class="table table-hover table-sm">
                    <thead>
                        <th>Account No</th>
                        <th>Account Name</th>
                        <th>Amount</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
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
        var discount = 0
        var change = 0
        var amountDue = 0
        var totalCountInit = 0

        $(document).ready(function() {
            // init
            $('#spinner').show()

            $("#old-account-no").inputmask({
                mask: '99-99999-999',
                placeholder: '',
                showMaskOnHover: false,
                showMaskOnFocus: false,
                onBeforePaste: function (pastedValue, opts) {
                    var processedValue = pastedValue;

                    return processedValue;
                }
            });

            $("#old-account-no").keyup(function() {
                if (this.value.length == 12) {
                    var value = $("#old-account-no").val().toLowerCase()
                    $("#accounts-table tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                }
            })

            fetchBills($('#ServicePeriod').val())

            $('#ServicePeriod').on('change', function() {                
                $('#spinner').show()
                $('#saveBtn').attr('disabled', 'true')

                totalAmount = 0
                totalCountInit = 0
                amountDue = 0
                accountsAdded = []
                updatePaymentDisplays()

                fetchBills(this.value)
            })

            $('#discountPercent').on('change', function(){
                discount = totalAmount * parseFloat(this.value)
                computeAmounts()
                updatePaymentDisplays()
            })

            $('#saveBtn').on('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Proceed'
                }).then((result) => {
                    if (result.isConfirmed) {
                        transact()
                    }
                })
            })
        })

        function fetchBills(period) {
            $('#accounts-table tbody tr').remove()
            $('#ServicePeriod').attr('disabled', 'disabled')
            $.ajax({
                url : "{{ route('paidBills.get-bills-from-bapa') }}",
                type : 'GET',
                data : {
                    BAPAName : "{{ $bapaName }}",
                    Period : period,
                },
                success : function(res) {
                    // getDetails(res)
                    var acctInit = ""
                    $.each(res, function(index, element) {
                        $('#accounts-table tbody').append(addToRow(res[index]['OldAccountNo'],
                                res[index]['ServiceAccountName'],
                                res[index]['AccountStatus'],
                                res[index]['KwhUsed'],
                                res[index]['ServicePeriod'],
                                res[index]['BillNumber'],
                                res[index]['NetAmount'],
                                res[index]['ORNumber'],
                                res[index]['BillId'],
                                res[index]['AccountNumber']))
                        acctInit = res[index]['OldAccountNo']
                    })
                    $('#spinner').hide()
                    $('#ServicePeriod').removeAttr('disabled')
                    $('#saveBtn').removeAttr('disabled')
                    $("#old-account-no").val(acctInit.slice(0, 8)).focus()
                },
                error : function(err) {
                    alert('An error occurred while fetching accounts')
                    $('#ServicePeriod').removeAttr('disabled')
                }
            })
        }

        function addToRow(acctNo, acctName, status, kwhused, billmo, billno, amtdue, or, id, acctId) {
            if (id != null) {
                if (or != null) { // PAID AREA
                    return '<tr title="OR Number: ' + or + '" class="bg-teal disabled">' +
                                '<td><i class="ico-tab fas fa-check text-success"></i>' + acctNo + '</td>' +
                                '<td>' + acctName + '</td>' +
                                '<td>' + status + '</td>' +
                                '<td class="text-right">' + Number(parseFloat(kwhused).toFixed(2)).toLocaleString() + '</td>' +
                                '<td>' + billmo + '</td>' +
                                '<td class="text-right">' + (billno != null ? billno : '') + '</td>' +
                                '<td class="text-right">' + (parseFloat(amtdue) ? Number(parseFloat(amtdue).toFixed(2)).toLocaleString() : '') + '</td>' +
                                '<td></td>' +
                            '</tr>';
                } else { // PAYABLE AREA
                    if (billmo == $('#ServicePeriod').val()) {
                        return '<tr onclick=addToPayables("' + id + '") acctNo="' + acctNo + '" acctName="' + acctName + '" amnt="' + amtdue + '" id="' + id + '">' +
                                    '<td><i class="ico-tab fas fa-exclamation text-primary"></i>' + acctNo + '</td>' +
                                    '<td>' + acctName + '</td>' +
                                    '<td>' + status + '</td>' +
                                    '<td class="text-right">' + Number(parseFloat(kwhused).toFixed(2)).toLocaleString() + '</td>' +
                                    '<td>' + billmo + '</td>' +
                                    '<td class="text-right">' + (billno != null ? billno : '') + '</td>' +
                                    '<td class="text-right">' + (parseFloat(amtdue) ? Number(parseFloat(amtdue).toFixed(2)).toLocaleString() : '') + '</td>' +
                                    '<td><button class="btn btn-link btn-sm"><i ischecked=false amount="' + amtdue + '" id="btn-' + id + '" acctNo="' + acctId + '" class="fas fa-check-circle text-muted"></i></button></td>' +
                                '</tr>';
                    } else { // arrears
                        return '<tr onclick=addToPayables("' + id + '") acctNo="' + acctNo + '" acctName="' + acctName + '" amnt="' + amtdue + '" id="' + id + '" style="background-color: #ef9a9a;">' +
                                    '<td><i class="ico-tab fas fa-exclamation text-primary"></i>' + acctNo + '</td>' +
                                    '<td>' + acctName + '</td>' +
                                    '<td>' + status + '</td>' +
                                    '<td class="text-right">' + Number(parseFloat(kwhused).toFixed(2)).toLocaleString() + '</td>' +
                                    '<td>' + billmo + '</td>' +
                                    '<td class="text-right">' + (billno != null ? billno : '') + '</td>' +
                                    '<td class="text-right">' + (parseFloat(amtdue) ? Number(parseFloat(amtdue).toFixed(2)).toLocaleString() : '') + '</td>' +
                                    '<td><button class="btn btn-link btn-sm"><i ischecked=false amount="' + amtdue + '" id="btn-' + id + '" acctNo="' + acctId + '" class="fas fa-check-circle text-muted"></i></button></td>' +
                                '</tr>';
                    }                    
                }
            } else { // UNBILLED AREA
                return '<tr class="bg-secondary disabled">' +
                                '<td><i class="ico-tab fas fa-exclamation text-danger"></i>' + acctNo + '</td>' +
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

        function addToPayables(id) {
            acctNo = $('#btn-' + id).attr('acctNo')
            if ($('#btn-' + id).attr('ischecked') == 'false') {
                $('#btn-' + id).attr('ischecked', 'true')
                $('#btn-' + id).removeClass('text-muted').addClass('text-primary')

                // ADD TO ARRAY
                accountsAdded.push(id)
                // ADD PAYABLE
                totalAmount += parseFloat($('#btn-' + id).attr('amount'))
                amountDue = totalAmount

                // ADD TO QUEUE
                $('#queues-list tbody').append(
                    "<tr id='queue-" + id + "'>" +
                        "<td>" + $('#' + id).attr('acctNo') + "</td>" +
                        "<td>" + $('#' + id).attr('acctName') + "</td>" +
                        "<td>" + (parseFloat($('#' + id).attr('amnt')) ? Number(parseFloat($('#' + id).attr('amnt')).toFixed(2)).toLocaleString() : '') + "</td>" +
                    "</tr>"
                )

                Toast.fire({
                    icon: 'success',
                    title: 'Account Added'
                })
            } else if ($('#btn-' + id).attr('ischecked') == 'true') {
                $('#btn-' + id).attr('ischecked', 'false')
                $('#btn-' + id).removeClass('text-primary').addClass('text-muted')

                // REMOVE ITEM FROM ARRAY
                removeItemFromArray(id)
                totalAmount -= parseFloat($('#btn-' + id).attr('amount'))
                amountDue = totalAmount

                $('#queue-' + id).remove()

                Toast.fire({
                    icon: 'warning',
                    title: 'Account Removed!'
                })
            }

            computeAmounts()
            $('#totalConsumersToPay').text(accountsAdded.length) 
            updatePaymentDisplays()
        }

        function computeAmounts(update = false) {
            // COMPUTE DISCOUNT
            discount = parseFloat($('#discountPercent').val()) * totalAmount
            amountDue = totalAmount - discount
        }

        function updatePaymentDisplays() {
            $('#totalAmount').text(Number(totalAmount.toFixed(2)).toLocaleString())
            $('#amountDue').text(Number(amountDue.toFixed(2)).toLocaleString())
            $('#discountAmount').text(Number(discount.toFixed(2)).toLocaleString())
        }

        function removeItemFromArray(id) {
            var index = accountsAdded.indexOf(id)
            console.log(id)
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

                    if (!jQuery.isEmptyObject(res[index]['BillId'])) {
                        ttlBills += 1;
                        // COMPUTE TOTAL ON INITALIZE
                        if (jQuery.isEmptyObject(res[index]['ORNumber'])){
                            // ADD ACCOUNTS ON INITIALIZE
                            accountsAdded.push(res[index]['BillId'])
                            totalAmount += parseFloat(res[index]['NetAmount'])
                            totalCountInit++
                        }    
                        // ADD TOTAL NUMBER OF CONSUMERS TO PAY
                        $('#totalConsumersToPay').text(totalCountInit)                    
                    }

                    ttlAccts += 1;

                    // SHOW AMOUNTS
                    amountDue = totalAmount
                    $('#totalAmount').text(Number(totalAmount.toFixed(2)).toLocaleString())
                    $('#amountDue').text(Number(amountDue.toFixed(2)).toLocaleString())
                })

                $('#total-acts').text(ttlAccts)
                $('#active-total').text(ttlActive)
                $('#bills-total').text(ttlBills)
                $('#indiv-paid').text(ttlPaid)
            }
        }

        function transact() {
            $.ajax({
                url : "{{ route('bAPAAdjustments.save-bapa-adjustments') }}",
                type : 'GET',
                data : {
                    Period : $('#ServicePeriod').val(),
                    BillNumbers : accountsAdded,
                    DiscountPercentage : $('#discountPercent').val(),
                    DiscountAmount : discount,
                    NetAmount : amountDue,
                    NoOfConsumers : accountsAdded.length,
                    BAPAName : "{{ urlencode($bapaName) }}",
                    SubTotal : totalAmount,
                },
                success : function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Bills Adjusted for BAPA ' + "{{ $bapaName }}",
                        confirmButtonText: 'Close',
                        allowEnterKey : false,
                        allowOutsideClick : false,
                        allowEscapeKey : false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ url('/b_a_p_a_adjustments/bapa-collection-monitor-console') }}" + "/" + encodeURI("{{ $bapaName }}") + "?Period=" + encodeURI($('#ServicePeriod').val())
                        }
                    })
                },
                error : function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred during the transaction.',
                    })
                }
            })
        }

        $(document).keydown(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);  
            if(keycode == '13'){
                var row = $('tr[acctNo="' + $('#old-account-no').val() + '"]')
                var index = row.index() + 1
                var idSelected = row.attr('id')
                // console.log(idSelected)
                addToPayables(idSelected)

                var value = ''
                $("#accounts-table tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });

                if (jQuery.isEmptyObject(idSelected)) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Account Not Found!'
                    })
                }

                $("#old-account-no").val($("#old-account-no").val().slice(0, 8)).focus()
            } 
        });

    </script>
@endpush