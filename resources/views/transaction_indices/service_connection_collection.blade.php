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
                    <h4>Service Connection Collection</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            {{-- QUEUE --}}
            <div class="col-lg-3 col-md-5">
                <div class="card">
                    <div class="card-header border-0">
                        <span class="card-title">Queue</span>
                    </div>
                    <div class="card-body table-responsive px-0">
                        <table class="table table-hover table-sm">
                            <thead>
                                <th>Applicants</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($applications as $item)
                                    <tr onclick="fetchPayables('{{ $item->id }}', '{{ $item->LoadCategory }}')">
                                        <td>{{ $item->ServiceAccountName }}</td>
                                        <td class="text-right">
                                            <button onclick="fetchPayables('{{ $item->id }}', '{{ $item->LoadCategory }}')" class="btn btn-sm btn-link {{ $item->LoadCategory == 'above 5kVa' ? 'text-danger' : 'text-primary' }}"><i class="fas fa-forward"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- PAYMENT MODULE --}}
            <div class="col-lg-9 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-7 col-md-6">
                                <span>
                                    <h5><strong id="consumerName">...</strong></h5>
                                    <address class="text-muted" id="address">...</address>
                                </span>
    
                                <table class="table table-sm table-hover" id="payable-table">
                                    <thead>
                                        <th>Payables</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-right">VAT</th>
                                        <th class="text-right">Total</th>
                                    </thead>
                                    <tbody>
    
                                    </tbody>
                                </table>

                                <div id="power-load-area" class="gone">
                                    <p><strong>Power Load Payables</strong></p>
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <th>Particulars</th>
                                            <th>Amount</th>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-5 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        {{-- FORM --}}
                                        <table class="table table-borderless table-sm">
                                            <tr id="power-load-row" class="gone">
                                                <td>Power Load</td>
                                                <td class="text-right">
                                                    <input type="text" class="form-control text-right" style="font-size: 1.5em;" id="powerLoadAmount" readonly="true">
                                                </td>
                                            </tr>
                                            <tr id="sa-row" class="gone">
                                                <td>Service Application</td>
                                                <td class="text-right">
                                                    <input type="text" class="form-control text-right" style="font-size: 1.5em;" id="saAmount" readonly="true">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Total Payable</td>
                                                <td class="text-right">
                                                    <input type="text" class="form-control text-right" style="font-size: 1.5em;" id="totalAmount" readonly="true">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>OR Number</td>
                                                <td class="text-right">
                                                    <input type="number" class="form-control text-right" style="font-size: 1.5em;" id="ornumber" value="{{ ORAssigning::getORIncrement(1, $orAssignedLast) }}">
                                                </td>
                                            </tr>
                                        </table>

                                        <table class="table table-borderless table-sm">  
                                            <tr>
                                                <td>Cash Payments</td>
                                                <td class="text-right">
                                                    <input type="number" class="form-control text-right" style="font-size: 1.5em;" id="cashAmountPaid" step="any" autofocus="true">
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

                                        <div class="divider"></div>
                                        {{-- TOTAL FORM --}}
                                        <table class="table table-borderless table-sm">                                            
                                            <tr>
                                                <td>Total Amount Paid</td>
                                                <td class="text-right">
                                                    <input type="number" class="form-control text-right" style="font-size: 1.5em;" id="amountPaid" step="any" readonly="true">
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
        var activeId = null;
        var total = 0;
        var saTotal = 0;
        var powerLoadTtl = 0;
        var loadCategory = "";
        var checkIds = []
        var checkAmountTotal = 0

        function fetchPayables(id, category) {
            activeId = id;
            total = 0;
            saTotal = 0;
            powerLoadTtl = 0;
            loadCategory = category;
            $('#amountPaid').val('')
            $('#change').val('')
            checkIds = []
            checkAmountTotal = 0
            
            // GET TOTAL
            $.ajax({
                url : '/transaction_indices/get-payable-total',
                type : 'GET',
                data : {
                    svcId : id,
                },
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {
                        // $('#totalAmount').val('')
                        $('#consumerName').text('')
                        $('#address').text('')
                    } else {
                        total += parseFloat(res['Total'])
                        saTotal = parseFloat(res['Total'])
                        $('#consumerName').html('<i class="fas fa-check-circle text-success ico-tab"></i>' + res['ServiceAccountName'])
                        $('#address').text(res['Barangay'] + ', ' + res['Town'])
                    }

                    // IF POWER LOAD
                    if (category == 'above 5kVa') {
                        $('#power-load-area').removeClass('gone');
                        $('#power-load-row').removeClass('gone');
                        $('#sa-row').removeClass('gone');

                        $.ajax({
                            url : '/transaction_indices/get-power-load-payables',
                            type : 'GET',
                            data : {
                                ServiceConnectionId : id,
                            },
                            success : function(res) {
                                $('#power-load-area tbody tr').remove()
                                if (!jQuery.isEmptyObject(res)) {
                                    $('#power-load-area tbody').append(addRowToPowerLoad('Materials', res['SubTotal']))
                                    $('#power-load-area tbody').append(addRowToPowerLoad('Transformer', res['TransformerTotal']))
                                    $('#power-load-area tbody').append(addRowToPowerLoad('Total Labor Cost', res['LaborCost']))
                                    $('#power-load-area tbody').append(addRowToPowerLoad('Handling Cost', res['HandlingCost']))
                                    $('#power-load-area tbody').append(addRowToPowerLoad('VAT', res['TotalVAT']))
                                    $('#power-load-area tbody').append(addRowToPowerLoad('Total', res['Total']))
                                    total += parseFloat(res['Total'])
                                }

                                $('#totalAmount').val(Number(total.toFixed(2)).toLocaleString())
                                $('#powerLoadAmount').val(Number(parseFloat(res['Total']).toFixed(2)).toLocaleString())
                                $('#saAmount').val(Number(saTotal.toFixed(2)).toLocaleString())
                            },
                            error : function(error) {
                                alert(error)
                            }
                        })
                    } else {
                        $('#power-load-area').addClass('gone');
                        $('#power-load-row').addClass('gone');
                        $('#sa-row').addClass('gone');
                        $('#powerLoadAmount').val('')
                        $('#saAmount').val('')
                        $('#totalAmount').val(Number(total.toFixed(2)).toLocaleString())
                    }
                }, 
                error : function(err) {
                    alert('An error occurred while fetching total payables for this application')
                    console.log(err)
                }
            })

            // GET PAYABLES
            $.ajax({
                url : '/transaction_indices/get-payable-details',
                type : 'GET',
                data : {
                    svcId : id,
                },
                success : function(res) {
                    $('#payable-table tbody tr').remove();
                    if (jQuery.isEmptyObject(res)) {

                    } else {
                        $.each(res, function(index, element) {
                            $('#payable-table tbody').append(addRowToTable(res[index]['Particular'], res[index]['Amount'], res[index]['Vat'], res[index]['Total']))
                        })
                    }
                }, 
                error : function(err) {
                    alert('An error occurred while fetching payables for this application')
                    console.log(err)
                }
            })

            $('#cashAmountPaid').focus()
        }

        function addRowToPowerLoad(particular, amount) {
            return '<tr>' +
                    '<td>' + particular + '</td>' +
                    '<th class="text-right">' + Number(parseFloat(amount).toFixed(2)).toLocaleString() + '</th>' +
                '</tr>';
        }

        function addRowToTable(payables, amount, vat, total) {
            return "<tr>" + 
                        "<td>" + payables + "</td>" +
                        "<td class='text-right'>" + Number(parseFloat(amount).toFixed(2)).toLocaleString() + "</td>" +
                        "<td class='text-right'>" + vat + "</td>" +
                        "<th class='text-right'>" + total + "</th>" +
                    "</tr>"
        }

        function computePayments() {
            var cashAmnt = parseFloat($('#cashAmountPaid').val()) ? parseFloat($('#cashAmountPaid').val()) : 0
            var totalX = cashAmnt + checkAmountTotal
            $('#amountPaid').val(totalX.toFixed(2)).change()
        }

        function computeTotal(powerLoad, serviceConnection) {
            return powerLoad + serviceConnection;
        }

        $(document).ready(function() {
            // ASSESS VALUE ON TYPE
            var change = 0;
            $('#amountPaid').keyup(function() {
                change = parseFloat(this.value) - parseFloat(total)

                if (parseFloat(change)) {
                    if (change > -1 && !jQuery.isEmptyObject($('#ornumber').val())) {
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

            $('#amountPaid').on('change', function() {
                change = parseFloat(this.value) - parseFloat(total)

                if (parseFloat(change)) {
                    if (change > -1 && !jQuery.isEmptyObject($('#ornumber').val())) {
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

            $('#cashAmountPaid').keyup(function() {
                computePayments()
            })

            $('#ornumber').keyup(function() { 
                if (parseFloat(change)) {
                    if (change > -1 && !jQuery.isEmptyObject($('#ornumber').val())) {
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

            $(document).keypress(function(event){
                if (parseFloat(change)) {
                    if (change > -1 && !jQuery.isEmptyObject($('#ornumber').val())) {
                        var keycode = (event.keyCode ? event.keyCode : event.which);
                        if(keycode == '13'){
                            saveAndPrint('Cash')
                        }                      
                    } else {

                    }
                } else {

                }  
            });

            $('#cashBtn').on('click', function() {
                if (parseFloat(change)) {
                    if (change > -1 && !jQuery.isEmptyObject($('#ornumber').val())) {
                        saveAndPrint('Cash')                    
                    } else {

                    }
                } else {

                }  
            })

            // TRANSACT CHECK
            $('#save-check-transaction').on('click', function() {
                addCheckPayment()           
            })
        })

        function saveAndPrint(paymentUsed) {
            if (jQuery.isEmptyObject($('#cashAmountPaid').val())) {
                paymentUsed = 'Check'
            } else {
                if (checkIds.length > 0) {
                    paymentUsed = 'Cash and Check'
                } else {
                    paymentUsed = 'Cash'
                }
            }

            $.ajax({
                url : '/transaction_indices/save-and-print-or-service-connections',
                type : 'GET',
                data : {
                    svcId : activeId,
                    ORNumber : $('#ornumber').val(),
                    PaymentUsed : paymentUsed,
                    Total : total,
                    LoadCategory : loadCategory,
                    TransactionId : '{{ $transactionId }}',
                    CheckAmount : checkAmountTotal,
                    CheckIds : checkIds,
                    CashAmount : $('#cashAmountPaid').val(),
                },
                success : function(res) {
                    window.location.href = "{{ url('/transaction_indices/print-or-transactions') }}" + "/" + res['id'];
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