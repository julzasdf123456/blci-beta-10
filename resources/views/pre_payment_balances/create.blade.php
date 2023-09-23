@php
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Add Pre-Payment/Deposit Balance</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content row px-3">
        {{-- SEARCH --}}
        <div class="col-lg-5 col-md-6">
            <div class="card" style="height: 70vh;">
                <div class="card-header border-0">
                    <div class="row">
                        <div class="col-lg-8">
                            <input type="text" class="form-control" id="search-field" placeholder="Search Account Number or Name" autofocus>
                        </div>
                        <div class="col-lg-4">
                            <div class="card-tools">
                                <button class="btn btn-primary" id="searchBtn">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive px-0">
                    <table class="table table-hover table-sm" id="res-table">
                        <thead>
                            <th>OR Number</th>
                            <th>Account No</th>
                            <th>Consumer Name</th>
                            <th>Amount</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- FORM --}}
        <div class="col-lg-7 col-lg-6">
            <div class="card" style="height: 70vh">
                <div class="card-header">
                    <span class="card-title">Details</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td>Account No:</td>
                                    <th id="accountNumber"></th>
                                </tr>
                                <tr>
                                    <td>Account Name:</td>
                                    <th id="accountName"></th>
                                </tr>
                            </table>
                            <div class="divider"></div>
                        </div>
                        
                        <div class="col-lg-12 text-center">
                            <p class="text-center"><strong>Balance/Deposits</strong></p>
                            <p style="font-size: 4em;" class="text-center text-success" id="balance">P 0.0</p>
                            <a href="" class="btn btn-sm btn-warning">View Deposit History</a>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="card-footer row">
                    <div class="form-group col-lg-4">
                        <label for="amount">Deposit Amount</label>
                        <input type="number" step="any" id="amount" class="form-control" placeholder="Enter Amount...">
                    </div>  
                    <div class="form-group col-lg-4">
                        <label for="amount">OR Number</label>
                        <input type="number" id="orno" class="form-control" placeholder="Enter OR Number">
                    </div> 
                    <div class="form-group col-lg-4">
                        <label for="remarks">Notes/Comments/Remarks</label>
                        <input type="text" id="remarks" class="form-control" placeholder="Notes/Comments/Remarks...">
                    </div>  
                    <div class="col-lg-12">
                        <button class="btn btn-primary" id="depositBtn">Deposit</button> 
                    </div>                      
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        var accountNo = ""
        var balance = 0
        var orno = ""

        $(document).ready(function() {
            getNextOr()
            $('#search-field').keyup(function() {
                var letterCount = this.value.length;

                if (!jQuery.isEmptyObject(this.value)) {
                    if (letterCount > 4) {
                        performSearch(this.value)
                    }
                }                
            })

            $('#searchBtn').on('click', function() {
                performSearch($('#search-field').val())
            })

            $('#depositBtn').on('click', function() {
                var amountProvided = $('#amount').val()

                if (!jQuery.isEmptyObject(amountProvided) || !jQuery.isEmptyObject(orno) || !jQuery.isEmptyObject(accountNo)) {
                    deposit(amountProvided)
                }                
            })
        })

        function performSearch(regex) {
            $.ajax({
                url : '/pre_payment_balances/search',
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

        function getNextOr() {
            $.ajax({
                url : '/o_r_assignings/get-next-or',
                type : 'GET',
                success : function(res) {
                    orno = res['ORNumber']
                    $('#orno').val(orno)
                },
                error : function(err) {
                    alert('Error getting OR Number')
                }
            })
        }

        function fetchDetails(id) {
            accountNo = id
            $('#accountNumber').text(accountNo)

            $.ajax({
                url : '/pre_payment_balances/get-balance-details',
                type : 'GET',
                data : {
                    AccountNumber : id,
                },
                success : function(res) {
                    balanceId = res['id']

                    $('#amount').focus()

                    $('#accountName').text(res['ServiceAccountName'])
                    if (jQuery.isEmptyObject(res['Balance'])) {
                        $('#balance').text('P 0.0')
                    } else {
                        $('#balance').text('P ' + res['Balance'])
                    }
                },
                error : function(err) {
                    alert('An error occurred while fetching the details')
                }
            })
        }

        function deposit(amount) {
            id = "{{ IDGenerator::generateIDandRandString() }}"
            $.ajax({
                url : '/prePaymentBalances',
                type : 'POST',
                data : {
                    _token : "{{ csrf_token() }}",
                    id : id,
                    AccountNumber : accountNo,
                    Balance : amount,
                    Remarks : $('#remarks').val(),
                    ORNumber : orno,
                },
                success : function(res) {
                    // fetchDetails(res['AccountNumber'])
                    // $('#amount').val('')
                    // $('#remarks').val('')
                    // $('#orno').val(getNextOr())
                    if (jQuery.isEmptyObject(res)) {

                    } else {
                        window.location.href = "{{ url('/transaction_indices/print-other-payments') }}" + "/" + res['id'];
                    }
                },
                error : function(err) {
                    alert('An error occurred while depositing the amount')
                    console.log(err)
                }
            })
        }
    </script>
@endpush
