@php
    use App\Models\ServiceAccounts;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Credit Memo Wizzard</h4>
            </div>
            
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-5">
        {{-- ACCOUNT DETAILS --}}
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-arrow-left ico-tab"></i>From Account</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm">
                    <tbody>
                        <tr>
                            <td class="text-muted">Account Number</td>
                            <td><strong>{{ $account->OldAccountNo }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Account Name</td>
                            <td><strong>{{ $account->ServiceAccountName }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Account Address</td>
                            <td><strong>{{ ServiceAccounts::getAddress($account) }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Account Type</td>
                            <td><strong>{{ $account->AccountType }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Account Status</td>
                            <td><strong>{{ $account->AccountStatus }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAYMENT DETAILS --}}
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Bill & Payment Details</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <p class="no-pads text-muted text-center">Billing Month</p>
                        <h2 class="text-center no-pads text-info">{{ date('M Y', strtotime($bill->ServicePeriod)) }}</h2>
                    </div>

                    <div class="col-lg-6">
                        <p class="no-pads text-muted text-center">Billing Amount</p>
                        <h2 class="text-center no-pads text-primary">{{ number_format($bill->NetAmount, 2) }}</h2>
                    </div>

                    <div class="col-lg-12">
                        <br>
                        <div class="divider"></div>
                        <span class="text-muted"><i>Payment(s) Made</i></span>
                        <table class="table table-sm table-hover" id="paid-bills-table">
                            <thead>
                                <th></th>
                                <th>OR Number</th>
                                <th>OR Date</th>
                                <th>Amount</th>
                            </thead>
                            <tbody>
                                @foreach ($paidBills as $item)
                                    <tr onclick="validate(`{{ $item->id }}`)">
                                        <td>
                                            <i id="checker-{{ $item->id }}" ischecked=false class="fas fa-check-circle text-muted" value="{{ $item->id }}"></i>
                                        </td>
                                        <td>{{ $item->ORNumber }}</td>
                                        <td>{{ date('M d, Y', strtotime($item->ORDate)) }}</td>
                                        <td><strong class="text-success">{{ number_format($item->NetAmount, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-arrow-right ico-tab"></i>To Account</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-7 offset-lg-2 col-md-10 col-sm-10">
                        <input type="text" placeholder="Search Account Number or Name" class="form-control form-control-sm" autofocus id="search-field">
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2">
                        <button class="btn btn-primary btn-sm" id="search-btn"><i class="fas fa-search ico-tab-mini"></i></button>
                    </div>

                    <div class="col-lg-12">
                        <table class="table table-sm table-hover" id="results-table">
                            <thead>
                                <th>Account Number</th>
                                <th>Account Name</th>
                                <th>Account Address</th>
                                <th>Account Type</th>
                                <th></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL UPDATE READING FOR ZERO READINGS --}}
<div class="modal fade" id="modal-credit-memo" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4>Charge Payment To</h4>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="account-name" class="no-pads"></h4>
                <p class="no-pads" id="account-number"></p>

                <div class="divider"></div>

                <p class="text-muted"><i>Select Billing Month</i></p>
                <table class="table table-hover table-sm" id="bills-table">
                    <thead>
                        <th>Billing Month</th>
                        <th>Amount Due</th>
                        <th>Surcharge</th>
                        <th>Balance</th>
                        <th></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save"><i class="fas fa-save ico-tab-mini"></i>Save</button>
            </div> --}}
        </div>
    </div>
 </div>
@endsection

@push('page_scripts')
    <script>
        var selectedPaidBill = ""
        $(document).ready(function() {
            $('#search-field').on('keyup', function() {
                if (this.value.length > 4) {
                    fetchAccounts(this.value)
                } else {
                    $('#results-table tbody tr').remove()
                }
            })  

            $('#search-btn').on('click', function() {
                if (jQuery.isEmptyObject($('#search-field').val())) {
                    Toast.fire({
                        icon : 'warning',
                        text : 'Please input search value'
                    })
                } else {
                    fetchAccounts($('#search-field').val())
                }
            })
        })

        function fetchAccounts(regex) {
            $('#results-table tbody tr').remove()
            $.ajax({
                url : "{{ route('paidBills.search-account-for-credit-memo') }}",
                type : "GET",
                data : {
                    search : regex,
                },
                success : function(res) {
                    $('#results-table tbody').append(res)
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting accounts'
                    })
                    console.log(err)
                }
            })
        }

        function validate(id) {
            $("#paid-bills-table tbody tr").each(function() {
                $(this).find("i").attr('ischecked', false)
                $(this).find("i").removeClass('text-success').addClass('text-muted')
            });

            if ($('#checker-' + id).attr('ischecked') == 'false') {
                $('#checker-' + id).attr('ischecked', true)
                $('#checker-' + id).removeClass('text-muted').addClass('text-success')
                selectedPaidBill = id
            } else {
                $('#checker-' + id).attr('ischecked', false)
                $('#checker-' + id).removeClass('text-success').addClass('text-muted')
                selectedPaidBill = null
            }
        }

        function setCreditMemo(id, accountName, accountNo) {
            if (jQuery.isEmptyObject(selectedPaidBill)) {
                Toast.fire({
                    icon : 'info',
                    text : 'Select payment first!'
                })
            } else {
                $('#modal-credit-memo').modal({backdrop: 'static', keyboard: false}, 'show')

                $('#account-name').text(accountName)
                $('#account-number').text(accountNo)
                $('#bills-table tbody tr').remove()

                $.ajax({
                    url : "{{ route('paidBills.get-unpaid-bills-for-credit-memo') }}",
                    type : "GET",
                    data : {
                        AccountNumber : id,
                    },
                    success : function(res) {
                        $('#bills-table tbody').append(res)
                    },
                    error : function(err) {
                        Toast.fire({
                            icon : 'error',
                            text : 'Error getting bill data'
                        })
                        console.log(err)
                    }
                })
            }
        }

        function applyCreditMemo(billId) {
            Swal.fire({
                title: 'Credit Memo Confirmation',
                text : 'Transfer payment to this account?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('paidBills.apply-credit-memo') }}",
                        type : "GET",
                        data : {
                            BillIdTo : billId,
                            AccountNumberFrom : "{{ $account->id }}",
                            PeriodFrom : "{{ $bill->ServicePeriod }}",
                            PaidBillId : selectedPaidBill,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Credit memo success!'
                            })
                            window.location.href = "{{ url('/serviceAccounts') }}/{{ $account->id }}"
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error performing credit memo!'
                            })
                            console.log(err)
                        }
                    })
                } 
            })
        }
    </script>
@endpush