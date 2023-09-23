@php
    use App\Models\ServiceAccounts;
    use App\Models\PaidBills;
    use App\Models\Bills;
    use App\Models\PaidBillsDetails;
    use App\Models\User;
    use App\Models\TransactionIndex;
    use App\Models\TransactionDetails;
    use App\Models\TransacionPaymentDetails;
    use App\Models\DCRSummaryTransactions;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Payment Details - {{ $paymentType }}</h4>
            </div>

            <div class="col-sm-6">
                @if (Auth::user()->hasAnyRole(['Administrator']))
                    <button class="btn btn-danger float-right" title="Cancel This Payment" id="cancel"><i class="fas fa-trash"></i></button>
                @endif                
            </div>
        </div>
    </div>
</section>
<p id="payment-type" style="display: none">{{ $paymentType }}</p>
<div class="content">
    @if ($paymentType == 'BILLS PAYMENT')
        {{-- PAIDBILLS FORM --}}
        @php
            $paidBill = PaidBills::find($id);
            if ($paidBill != null) {
                $account = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->select('Billing_ServiceAccounts.id',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.AccountCount',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'Billing_ServiceAccounts.AccountStatus',
                            'Billing_ServiceAccounts.AreaCode',
                            'Billing_ServiceAccounts.Organization',
                            'Billing_ServiceAccounts.GroupCode',
                            'Billing_ServiceAccounts.SeniorCitizen',
                            'Billing_ServiceAccounts.Evat5Percent',
                            'Billing_ServiceAccounts.Ewt2Percent',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay')
                    ->where('Billing_ServiceAccounts.id', $paidBill->AccountNumber)
                    ->first();

                $bill = Bills::where('AccountNumber', $paidBill->AccountNumber)
                    ->where('ServicePeriod', $paidBill->ServicePeriod)
                    ->first();

                $paidBillDetails = PaidBillsDetails::where('AccountNumber', $paidBill->AccountNumber)
                    ->where('ServicePeriod', $paidBill->ServicePeriod)->orderBy('PaymentUsed')->get();

                $user = User::find($paidBill->UserId);

                $dcr = DB::table('Cashier_DCRSummaryTransactions')
                    ->leftJoin('Cashier_AccountGLCodes', 'Cashier_DCRSummaryTransactions.GLCode', '=', 'Cashier_AccountGLCodes.AccountCode')
                    ->whereRaw("Teller='" . $paidBill->Teller . "' AND ORNumber='" . $paidBill->ORNumber . "' AND AccountNumber='" . $paidBill->AccountNumber . "' 
                        AND (TRY_CAST(Amount AS DECIMAL(15,2)) != 0) AND Cashier_DCRSummaryTransactions.NEACode='" . $bill->ServicePeriod . "'")
                    ->where(function ($query) {
                        $query->where('Cashier_DCRSummaryTransactions.ReportDestination', 'COLLECTION')
                            ->orWhere('Cashier_DCRSummaryTransactions.ReportDestination', 'BOTH');
                    })  
                    ->select('GLCode',
                        'Amount',
                        'Cashier_AccountGLCodes.Notes'
                    )
                    ->orderBy('GLCode')
                    ->get();
            } else {
                $account = null;
                $paidBillDetails = null;
                $user = null;
                $dcr = null;
            }
            
        @endphp
        <div class="px-5">
            @if ($paidBill->Status == 'CANCELLED')
                <h4 class="badge bg-danger">CANCELLED</h4>

                @push('page_scripts')
                    <script>
                        $('#cancel').hide()
                    </script>
                @endpush
            @endif
            <div class="row">
                <div class="col-lg-5">
                    <p class="text-muted">Payor</p>
                    <span><strong><a href="{{ $account != null ? (route('serviceAccounts.show', [$account->id])) : '' }}">{{ $account != null ? $account->ServiceAccountName : '-' }}</a></strong></span><br>
                    <span>{{ $account != null ? $account->OldAccountNo : '-' }}</span><br>
                    <span>{{ $account != null ? ServiceAccounts::getAddress($account) : '' }}</span><br>
                    <span>{{ $account != null ? $account->AccountType : '-' }}</span><br>
                </div>

                <div class="col-lg-4">
                    <span class="text-muted">OR Number</span><br>
                    <span>
                        <strong>{{ $paidBill->ORNumber }}</strong>
                        @if ($paidBill->Source == 'THIRD-PARTY COLLECTION')
                            <span class="badge bg-info">THIRD PARTY COLLECTION</span>
                        @endif 
                    </span><br>
                    <span class="text-muted">OR Date</span><br>
                    <span><strong>{{ date('F d, Y', strtotime($paidBill->ORDate)) }}</strong></span><br>
                    <span class="text-muted">Teller</span><br>
                    @if ($paidBill->Source == 'THIRD-PARTY COLLECTION')
                        <span>
                            <strong>{{ $paidBill->CheckNo }}</strong>
                            <span class="badge bg-info">{{ $paidBill->ObjectSourceId }}</span>
                        </span><br>
                    @else
                        <span><strong>{{ $user != null ? $user->name : '-' }}</strong></span><br>
                    @endif                    
                </div>

                <div class="col-lg-3">
                    <span class="text-muted float-right">Total Amount Paid</span><br>
                    <h2 class="text-primary text-right"><strong>₱ {{ number_format($paidBill->NetAmount, 2) }}</strong></h2>
                    <a href="{{ $bill != null ? (route('bills.show', [$bill->id])) : '' }}" class="btn btn-xs btn-primary float-right">View Bill</a>
                </div>

                {{-- Paidbill Details table --}}
                <div class="col-lg-12">
                    <br>
                    <span class="text-muted">Payment Details</span><br>
                    <table class="table table-hover">
                        <thead>
                            <th>Transaction ID</th>
                            <th class="text-right">Amount Paid</th>
                            <th>Payment Used</th>
                            <th>Check No.</th>
                            <th>Bank</th>
                        </thead>
                        <tbody>
                            @if ($paidBillDetails != null)
                                @foreach ($paidBillDetails as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td class="text-right">₱ {{ number_format($item->Amount, 2) }}</td>
                                        <td class="{{ $item->PaymentUsed == 'Cash' ? 'text-info' : 'text-success' }}"><strong>{{ $item->PaymentUsed }}</strong></td>
                                        <td>{{ $item->CheckNo }}</td>
                                        <td>{{ $item->Bank }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- DCR Summary --}}
                <div class="col-lg-12">
                    <div class="card shadow-none">
                        <div class="card-header">
                            <span class="card-title">DCR Summary</span>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <th>GL Code</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                </thead>
                                <tbody>
                                    @foreach ($dcr as $item)
                                        <tr>
                                            <td>{{ $item->GLCode }}</td>
                                            <td>{{ $item->Notes }}</td>
                                            <td>{{ is_numeric($item->Amount) ? number_format($item->Amount, 2) : $item->Amount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- OTHER TRANSACTIONS FORM --}}
        @php
            $transactionIndex = TransactionIndex::find($id);

            if ($transactionIndex != null) {
                $user = User::find($transactionIndex->UserId);
                $transactionDetails = TransactionDetails::where('TransactionIndexId', $id)->get();
                $transactionLogs = TransacionPaymentDetails::where('ORNumber', $transactionIndex->ORNumber)->get();
            } else {
                $user = null;
                $transactionDetails = null;
                $transactionLogs = null;
            }
        @endphp
        <div class="px-5">
            @if ($transactionIndex->Status == 'CANCELLED')
                <h4 class="badge bg-danger">CANCELLED</h4>

                @push('page_scripts')
                    <script>
                        $('#cancel').hide()
                    </script>
                @endpush
            @endif
            <div class="row">
                <div class="col-lg-5">
                    <p class="text-muted">Invoice</p>
                    <span><strong>{{ $transactionIndex->PaymentTitle != null ? $transactionIndex->PaymentTitle : 'No Detail Provided' }}</strong></span><br>
                    <span class="text-muted">Payment Source: </span>{{ $transactionIndex->Source }}<br>
                    <span class="text-muted">Transaction ID: </span>{{ $transactionIndex->TransactionNumber }}<br>
                    <span class="text-muted">Payor: </span>{{ $transactionIndex->PayeeName != null ? $transactionIndex->PayeeName : '-' }}<br>
                </div>

                <div class="col-lg-4">
                    <span class="text-muted">OR Number</span><br>
                    <span><strong>{{ $transactionIndex->ORNumber }}</strong></span><br>
                    <span class="text-muted">OR Date</span><br>
                    <span><strong>{{ date('F d, Y', strtotime($transactionIndex->ORDate)) }}</strong></span><br>
                    <span class="text-muted">Teller</span><br>                    
                    <span><strong>{{ $user != null ? $user->name : '-' }}</strong></span><br>
                </div>

                <div class="col-lg-3">
                    <span class="text-muted float-right">Total Amount Paid</span><br>
                    <h2 class="text-primary text-right"><strong>₱ {{ number_format($transactionIndex->Total, 2) }}</strong></h2>
                    {{-- <a href="{{ $paidBill->ObjectSourceId != null ? (route('bills.show', [$paidBill->ObjectSourceId])) : '' }}" class="btn btn-xs btn-primary float-right">View Bill</a> --}}
                </div>

                {{-- PAYABLES --}}
                <div class="col-lg-5">
                    <br>
                    <span class="text-muted">Payables</span>
                    <table class="table table-hover table-sm">
                        <thead>
                            <th>Particulars</th>
                            <th>Account Code</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">VAT</th>
                            <th class="text-right">Total</th>
                        </thead>
                        <tbody>
                            @if ($transactionDetails != null)
                                @foreach ($transactionDetails as $item)
                                    <tr>
                                        <td>{{ $item->Particular }}</td>
                                        <td>{{ $item->AccountCode }}</td>
                                        <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                                        <td class="text-right">{{ number_format($item->VAT, 2) }}</td>
                                        <td class="text-right">{{ number_format($item->Total, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- PAYMENT DETAILS --}}
                <div class="col-lg-7">
                    <br>
                    <span class="text-muted">Payment Details</span>
                    <table class="table table-hover">
                        <thead>
                            <th>Transaction ID</th>
                            <th>Amount Paid</th>
                            <th>Payment Used</th>
                            <th>Check No.</th>
                            <th>Bank</th>
                        </thead>
                        <tbody>
                            @if ($transactionLogs != null)
                                @foreach ($transactionLogs as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td class="text-right">₱ {{ number_format($item->Amount, 2) }}</td>
                                        <td class="{{ $item->PaymentUsed == 'Cash' ? 'text-info' : 'text-success' }}"><strong>{{ $item->PaymentUsed }}</strong></td>
                                        <td>{{ $item->CheckNo }}</td>
                                        <td>{{ $item->Bank }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#cancel').on('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{{ route('paidBills.cancel-or-admin') }}",
                            type : 'GET',
                            data : {
                                PaymentType : $('#payment-type').text(),
                                id : "{{ $id }}"
                            },
                            success : function(res) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'OR Cancelled',
                                    showConfirmButton: false,
                                    timer: 1500
                                })

                                location.reload()
                            },
                            error : function(err) {
                                Swal.fire({
                                    title : 'Error cancelling OR',
                                    icon : 'error'
                                })
                            }
                        })
                    }
                })
                
            })
        })
    </script>
@endpush