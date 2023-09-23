@php
    use App\Models\ORAssigning;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Uncollected Termed (Ledgerized) Arrears Collection</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            {{-- LEDGERS --}}
            <div class="col-lg-4 offset-lg-2 col-md-6">
                <div class="card" style="height: 80vh">
                    <div class="card-header">
                        <span><strong>{{ $account != null ? $account->ServiceAccountName : 'No account name' }}</strong></span><br>
                        <span class="text-muted">{{ $account != null ? $account->id : 'No account no' }}</span><br>
                        <span>Active Balance: <strong>₱ {{ $collectibles != null ? number_format($collectibles->Balance, 2) : '0.0' }} ({{ count($ledger) }} months)</strong></span>
                    </div>
                    <div class="card-body table-responsive px-0">
                        <table class="table table-hover table-sm">
                            <thead>
                                <th>Billing Month</th>
                                <th>Amount</th>
                                <th width="30px;"></th>
                            </thead>
                            <tbody>
                                @foreach ($ledger as $item)
                                    <tr>
                                        <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                        <td>{{ number_format($item->Amount, 2) }}</td>
                                        @if ($item->IsPaid)
                                            <td><i class="fas fa-check text-primary"></i></td>
                                        @else
                                            <td>
                                                <button id="{{ $item->id }}" ischecked="false" amount="{{ $item->Amount }}" onclick="addToPayments('{{ $item->id }}')" class="btn btn-sm text-muted btn-link"><i class="fas fa-check-circle"></i></button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- FORM --}}
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Payment</span>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td>Total Amount</td>
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
                        </table>
                    </div>
                    <div class="card-footer">
                        <button id="cashBtn" class="btn btn-lg btn-primary float-right" disabled><i class="fas fa-dollar-sign"></i> Cash</button>
                        {{-- <button id="checkBtn" class="btn btn-sm btn-default float-right ico-tab-mini" disabled data-toggle="modal" data-target="#modal-check-payment"><i class="fas fa-money-check-alt"></i> Check</button> --}}
                        <button id="cardBtn" class="btn btn-sm btn-default float-right ico-tab-mini" disabled><i class="fas fa-credit-card"></i> Debit/Credit Card</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('paid_bills.check_modal')

@push('page_scripts')
    <script>
        var selectedPayments = []
        var totalPayable = 0.0;

        function addToPayments(id) {
            if ($('#' + id).attr('ischecked') == 'false') {
                $('#' + id).removeClass('text-muted').addClass('text-success')

                // ADD TO ARRAY
                selectedPayments.push(id)

                $('#' + id).attr('ischecked', 'true')
            } else {
                $('#' + id).removeClass('text-success').addClass('text-muted')

                // REMOVE ITEM FROM ARRAY
                removeItemFromArray(id)

                $('#' + id).attr('ischecked', 'false')
            }

            // VALIDATE FORM
            computePayables(selectedPayments)
            $('#totalAmount').val(totalPayable.toFixed(2))
            $('#ornumber').focus()

            // VALIDATE BUTTONS
            if (selectedPayments.length > 0 && !jQuery.isEmptyObject($('#ornumber').val())) {
                buttonEnablers(true)
            } else {
                buttonEnablers(false)
            }
        }

        function computePayables() {
            var len = selectedPayments.length
            totalPayable = 0.0;

            for(var i=0; i<len; i++) {
                var amount = parseFloat($('#' + selectedPayments[i]).attr('amount'))
                totalPayable += amount;
            }
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
                // $('#cardBtn').removeAttr('disabled')               
            } else {
                // disable
                $('#cashBtn').attr('disabled', 'true')
                $('#checkBtn').attr('disabled', 'true')
                // $('#cardBtn').attr('disabled', 'true')
            }   
        }

        // DETEC IF ENTER KEY IS PRESSED
        $(document).keypress(function(event){
            if (selectedPayments.length > 0 && !jQuery.isEmptyObject($('#ornumber').val())) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode == '13'){
                    if ($('#modal-check-payment').hasClass('show')) {
                        // ENTER KEY IS DISABLED IF SHOW CHECK MODAL IS SHOWN
                    } else {
                        transact('Cash')
                    }                    
                } 
            } else {

            }  
        });

        $(document).ready(function() {
            // OR INPUT KEYUP
            $('#ornumber').keyup(function() {
                if (selectedPayments.length > 0 && !jQuery.isEmptyObject(this.value)) {
                    buttonEnablers(true)
                } else {
                    buttonEnablers(false)
                }
            })

            // CASH BUTTON EVENT
            $('#cashBtn').on('click', function() {
                if (selectedPayments.length > 0 && !jQuery.isEmptyObject($('#ornumber').val())) {
                    transact('Cash')
                } else {

                }  
            })

            // TRANSACT CHECK
            $('#save-check-transaction').on('click', function() {
                transact('Check')
            })
        })

        function transact(paymentUsed) {
            $.ajax({
                url : '/transaction_indices/save-ledger-arrear-transaction',
                type : 'GET',
                data : {
                    LedgerIds : selectedPayments,
                    TotalPayment : totalPayable,
                    ORNumber : $('#ornumber').val(),
                    PaymentUsed : paymentUsed,
                    AccountNumber : "{{ $account != null ? $account->id : '-' }}",
                    CheckNo : $('#checkNo').val(),
                    Bank : $('#bank').val()
                },
                success : function(res) {
                    window.location.href = "{{ url('/transaction_indices/print-or-termed-ledger-arrears') }}" + "/" + res['id'];
                },
                error : function(err) {
                    console.log(err)
                    alert('An error occurred while performing the transaction')
                }
            })
        }
    </script>
@endpush