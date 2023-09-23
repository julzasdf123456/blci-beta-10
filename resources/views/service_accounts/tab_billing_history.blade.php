<div class="content">
    <div class="row">
        <div class="col-lg-4">
            <p class="text-center text-muted" style="margin: 0px !important; padding: 0px !important;">Balance</p>
            <h1 class="text-primary text-center ">{{ number_format($balances, 2) }}</h1>
        </div>

        <div class="col-lg-4">
            <p class="text-center text-muted" style="margin: 0px !important; padding: 0px !important;">Surcharges</p>
            <h1 class="text-info text-center ">{{ number_format($surcharges, 2) }}</h1>
        </div>

        <div class="col-lg-4">
            <p class="text-center text-muted" style="margin: 0px !important; padding: 0px !important;">Total Balance</p>
            <h1 class="text-danger text-center ">{{ number_format($balances + $surcharges, 2) }}</h1>
        </div>
    </div>

    <button class="btn btn-xs btn-success float-right" data-toggle="modal" data-target="#modal-print-ledger">Print Ledger</button>
    <button class="btn btn-xs btn-success float-right" data-toggle="modal" style="margin-right: 5px; margin-bottom: 5px;" data-target="#modal-ledger-history">View Full Ledger</button>
    <button class="btn btn-xs btn-default float-right" style="margin-right: 5px; margin-bottom: 5px;" data-toggle="modal" data-target="#modal-reading-history">View Reading History</button>
    <a href="{{ route('readings.manual-reading-console', [$serviceAccounts->id]) }}" class="btn btn-xs btn-warning float-right" style="margin-right: 5px; margin-bottom: 5px;">Manual Billing</a>

    @if ($bills == null)
        <p class="center-text"><i>No billing history recorded</i></p>
    @else
        <div class="table-responsive p-0" style="height: 60vh;">
            <table class="table table-sm table-hover table-bordered"">
                <thead>
                    <th>Bill No.</th>
                    <th>Billing Mo.</th>
                    <th class="text-center">Prev.<br>Read</th>
                    <th class="text-center">Pres.<br>Read</th>
                    <th class="text-center">Kwh</th>
                    <th class="text-center" title="Multiplier">x*</th>
                    <th class="text-center">Total<br>Kwh</th>
                    {{-- <th class="text-right">Rate</th> --}}
                    <th class="text-center">Amount</th>
                    <th class="text-center">OR No.</th>
                    <th class="text-center">Payment<br>Date</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($bills as $item)
                        <tr title="{{ $item->AdjustmentType=='Application' ? 'Application Adjustment' : '' }}">
                            @if ($item->AdjustmentType=='Application')
                                <td><i class="fas fa-info-circle text-info ico-tab"></i><a href="{{ route('bills.show', [$item->id]) }}">{{ $item->BillNumber }}</a></td>
                            @else
                                <td><i class="fas {{ $item->ORDate != null ? 'fa-check-circle text-success' : 'fa-exclamation-circle text-danger' }} ico-tab"></i><a href="{{ route('bills.show', [$item->id]) }}">{{ $item->BillNumber }}</a></td>
                            @endif
                            
                            <td>{{ date('M Y', strtotime($item->ServicePeriod)) }}</td>
                            <td class="text-right">{{ $item->PreviousKwh }}</td>
                            <td class="text-right">{{ $item->PresentKwh }}</td>
                            <th class="text-right text-info">{{ is_numeric($item->Multiplier) ? round(floatval($item->PresentKwh) - floatval($item->PreviousKwh),2) : 'MULT_ERR' }}</th>
                            <th class="text-right text-warning">{{ $item->Multiplier }}</th>
                            <th class="text-right text-primary">{{ is_numeric($item->KwhUsed) ? round(floatval($item->KwhUsed), 2) : $item->KwhUsed }}</th>
                            {{-- <td class="text-right">{{ $item->EffectiveRate != null ? number_format($item->EffectiveRate, 4) : '0' }}</td> --}}
                            <th class="text-right text-danger">P {{ $item->NetAmount != null ? (is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : '0') : '0' }}</th>
                            <td class="text-right"><a href="{{ $item->PaidBillId != null ? (route('transactionIndices.browse-ors-view', [$item->PaidBillId, 'BILLS PAYMENT'])) : '' }}">{{ $item->ORNumber != null ? $item->ORNumber : '-' }}</a></td>
                            <td class="text-right">{{ $item->ORDate != null ? date('M d, Y', strtotime($item->ORDate)) : '-' }}</td>
                            <td class="text-right">
                                @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Data Administrator'])) 
                                    @if ($item->ORDate == null)
                                        @if ($item->IsUnlockedForPayment == 'CLOSED')
                                            <span class="badge bg-success">CLOSED</span>
                                        @endif   
                                        @if ($serviceAccounts->NetMetered=='Yes')     
                                            <a href="{{ route('bills.adjust-bill-net-metering', [$item->id]) }}" class="btn btn-link btn-xs text-warning" title="Adjust Reading"><i class="fas fa-pen"></i></a>
                                        @else
                                            <a href="{{ route('bills.adjust-bill', [$item->id]) }}" class="btn btn-link btn-xs text-warning" title="Adjust Reading"><i class="fas fa-pen"></i></a>
                                        @endif
                                        <button class="btn btn-link btn-xs text-danger" title="Cancel this Bill" onclick="requestCancel('{{ $item->id }}')"><i class="fas fa-ban"></i></button>                            
                                        <button class="btn btn-link btn-xs text-info" title="Mark as Paid (Application Adjustment)" onclick="markAsPaid('{{ $item->id }}')"><i class="fas fa-check-circle"></i></button>
                                    @endif
                                @endif
                                @if ($serviceAccounts->NetMetered=='Yes')
                                    <a href="{{ route('bills.print-single-net-metering', [$item->id]) }}" class="btn btn-xs btn-link" title="Print New Formatted Bill"><i class="fas fa-print"></i></a>
                                @else
                                    <a href="{{ route('bills.print-single-bill-new-format', [$item->id]) }}" class="btn btn-xs btn-link" title="Print New Formatted Bill"><i class="fas fa-print"></i></a>
                                @endif
                                
                                {{-- <a href="{{ route('bills.print-single-bill-old', [$item->id]) }}" class="btn btn-link btn-xs text-warning" title="Print Pre-Formatted Bill (Old)"><i class="fas fa-print"></i></a> --}}
                                <button class="btn btn-link btn-xs text-default" title="Adjustment History" onclick="showBillHistory('{{ $item->id }}')"><i class="fas fa-history"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    @endif
</div>

{{-- Reading History --}}
<div class="modal fade" id="modal-reading-history" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reading History</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-sm table-bordered">
                    <thead>
                        <th>Billing Month</th>
                        <th>Reading</th>
                        <th>Reading Timestamp</th>
                        <th>Meter Reader</th>
                        <th>Remarks</th>
                        {{-- <th></th> --}}
                    </thead>
                    <tbody>
                        @foreach ($readings as $item)
                            <tr>
                                <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                <td>{{ $item->KwhUsed }}</td>
                                <td>{{ date('F d, Y h:i:s A', strtotime($item->ReadingTimestamp)) }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->Notes }}</td>
                                {{-- <td class="text-right">
                                <a href="{{ route('bills.zero-readings-view', [$item->id]) }}"><i class="fas fa-pen"></i></a>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Ledger History --}}
<div class="modal fade" id="modal-ledger-history" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ledger</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>OR Number</th>
                        <th>OR Date</th>
                        <th class="text-right">Amount</th>
                        <th>Payment For</th>
                    </thead>
                    <tbody>
                        @foreach ($ledger as $item)
                            <tr>
                                <td><a href="{{ route('transactionIndices.browse-ors-view', [$item->id, $item->PaymentType]) }}">{{ $item->ORNumber }}</a></td>
                                <td>{{ $item->ORDate }}</td>
                                <td class="text-right">{{ number_format($item->Total, 2) }}</td>
                                <td>{{ $item->Source }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Print Ledger History --}}
<div class="modal fade" id="modal-print-ledger" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Print Ledger</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label for="FromLedger">From Year</label>
                        <input type="text" id="FromLedger" maxlength=4 class="form-control">
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="ToLedger">To Year</label>
                        <input type="text" id="ToLedger" maxlength=4 class="form-control" value="{{ date('Y') }}">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="print-ledger"><i class="fas fa-print ico-tab-mini"></i>Print</button>
            </div>
        </div>
    </div>
</div>

{{-- Bill Adjustment History --}}
<div class="modal fade" id="modal-bill-history" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="adj-title">Bill Adjustment History</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loader-adjustment-hist" class="spinner-border text-info gone" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <table class="table table-hover table-sm table-bordered" id="bill-adj-table">
                    <thead>
                        <th>Billing Mo.</th>
                        <th>Pres. Read.</th>
                        <th>Prev. Read.</th>
                        <th>Kwh Used</th>
                        <th>Amount Due</th>
                        <th>Adjusted By</th>
                        <th>Date Adjusted</th>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        function requestCancel(id) {
            (async () => {
                const { value: text } = await Swal.fire({
                    input: 'textarea',
                    inputLabel: 'Remarks',
                    inputPlaceholder: 'Type your remarks here...',
                    inputAttributes: {
                        'aria-label': 'Type your remarks here'
                    },
                    title: 'Cancel this bill?',
                    showCancelButton: true
                })

                if (text) {
                    $.ajax({
                        url : '{{ route("bills.request-cancel-bill") }}',
                        type : 'GET',
                        data : {
                            id : id,
                            Remarks : text
                        },
                        success : function(res) {
                            Swal.fire('Cancel Request Successful', 'Your cancellation request has been forwarded to your Billing Head and is waiting for confirmation', 'success')
                        },
                        error : function(err) {
                            Swal.fire('Cancel Request Error', 'Contact support immediately', 'error')
                        }
                    })
                }
            })()
        }

        function markAsPaid(id) {
            (async () => {
                const { value: text } = await Swal.fire({
                    input: 'textarea',
                    inputLabel: 'Remarks/Notes',
                    inputPlaceholder: 'Type your remarks here...',
                    inputAttributes: {
                        'aria-label': 'Type your remarks here'
                    },
                    title: 'Mark this bill as Paid?',
                    text : 'Are you sure to make this an Application Adjustment?',
                    showCancelButton: true
                })

                if (text) {
                    $.ajax({
                        url : '{{ route("bills.mark-as-paid") }}',
                        type : 'GET',
                        data : {
                            id : id,
                            Remarks : text
                        },
                        success : function(res) {
                            Swal.fire('Application Adjustment Successful', 'Bill marked as paid!', 'success')
                            location.reload()
                        },
                        error : function(err) {
                            Swal.fire('Application Adjustment Error', 'Contact support immediately', 'error')
                        }
                    })
                }
            })()
        }

        function showBillHistory(id) {
            $('#modal-bill-history').modal('show')
            $('#bill-adj-table tbody tr').remove()
            $('#loader-adjustment-hist').removeClass('gone')

            $.ajax({
                url : "{{ route('bills.get-billing-adjustment-history') }}",
                type : 'GET',
                data : {
                    id : id
                },
                success : function(res) {
                    $('#bill-adj-table tbody').append(res)
                    $('#loader-adjustment-hist').addClass('gone')
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error getting bill adjustment history',
                        icon : 'error'
                    })
                    $('#loader-adjustment-hist').addClass('gone')
                }
            })
        }

        $(document).ready(function() {
            $('#print-ledger').on('click', function() {
                var from = $('#FromLedger').val()
                var to = $('#ToLedger').val()

                if (jQuery.isEmptyObject(from) | jQuery.isEmptyObject(to)) {
                    Swal.fire({
                        title : 'Provide Years First!',
                        icon : 'error'
                    })
                } else {                   
                    window.location.href  = "{{ url('/service_accounts/print-ledger') }}" + "/{{ $serviceAccounts->id }}/" + from + "/" + to
                }
            })
        })
    </script>
@endpush