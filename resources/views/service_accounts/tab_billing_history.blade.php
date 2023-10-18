@php
    use App\Models\Bills;
@endphp
<style>
    .dropdown-toggle::after {
        display: none;
    }
</style>
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
            <table class="table table-sm table-hover table-bordered">
                <thead>
                    <th>Bill No.</th>
                    <th>Billing Mo.</th>
                    <th class="text-center">Prev.<br>Read</th>
                    <th class="text-center">Pres.<br>Read</th>
                    <th class="text-center">Kwh</th>
                    <th class="text-center" title="Multiplier">x*</th>
                    <th class="text-center">Total<br>Kwh</th>
                    {{-- <th class="text-right">Rate</th> --}}
                    <th class="text-center">Bill<br>Amount</th>
                    <th class="text-center">Paid<br>Amount</th>
                    <th class="text-center">Balance</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($bills as $item)
                        <tr title="{{ $item->AdjustmentType=='Application' ? 'Application Adjustment' : '' }}" id="{{ $item->id }}" fivePercent="{{ Bills::getFivePercent($item) }}" twoPercent="{{ Bills::getTwoPercent($item) }}">
                            @if ($item->AdjustmentType=='Application')
                                <td>
                                    <i class="fas fa-info-circle text-info ico-tab" title="Marked as paid manually"></i>
                                    <a href="{{ route('bills.show', [$item->id]) }}">{{ $item->BillNumber }}</a>
                                </td>
                            @else
                                <td>
                                    <i class="fas {{ $item->ORNumber != null ? 'fa-check-circle text-success' : 'fa-exclamation-circle text-danger' }} ico-tab-mini" title="{{ $item->ORNumber != null ? 'Paid' : 'Unpaid' }}"></i>
                                    @if ($item->SurchargeWaived != null)
                                        <i onclick="toast(`info`, `Surcharge Waived | Status: {{ $item->SurchargeWaived }}`)" class="fas fa-minus text-danger ico-tab-mini" title="Surcharge Waived | Status: {{ $item->SurchargeWaived }}"></i>
                                    @endif

                                    @if ($item->Item3 != null) 
                                        <i onclick="toast(`info`, `This bill is allowed to be skipped from the cashiering app.`)" class="fas fa-clipboard-check text-danger ico-tab-mini" title="This bill is allowed to be skipped from the cashiering app"></i>
                                    @endif
                                    <a href="{{ route('bills.show', [$item->id]) }}">{{ $item->BillNumber }}</a>
                                </td>
                            @endif
                            
                            <td>{{ date('M Y', strtotime($item->ServicePeriod)) }}</td>
                            <td class="text-right">{{ $item->PreviousKwh }}</td>
                            <td class="text-right">{{ $item->PresentKwh }}</td>
                            <td class="text-right text-info" title="Multiplier">{{ is_numeric($item->Multiplier) ? round(floatval($item->PresentKwh) - floatval($item->PreviousKwh),2) : 'MULT_ERR' }}</td>
                            <td class="text-right text-warning">{{ $item->Multiplier }}</td>
                            <th class="text-right text-primary">{{ is_numeric($item->KwhUsed) ? round(floatval($item->KwhUsed), 2) : $item->KwhUsed }}</th>
                            {{-- <td class="text-right">{{ $item->EffectiveRate != null ? number_format($item->EffectiveRate, 4) : '0' }}</td> --}}
                            <th class="text-right text-info">P {{ $item->NetAmount != null ? (is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : '0') : '0' }}</th>
                            <th class="text-right text-success">P {{ $item->PaidAmount != null ? (is_numeric($item->PaidAmount) ? number_format($item->PaidAmount, 2) : '0') : '0' }}</th>
                            <th class="text-right text-danger">P {{ $item->Balance != null ? (is_numeric($item->Balance) ? number_format($item->Balance, 2) : '0') : '0' }}</th>
                            <td class="text-right">
                                @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Data Administrator'])) 
                                    @if ($item->ORNumber == null)
                                        @if ($item->IsUnlockedForPayment == 'CLOSED')
                                            <span class="badge bg-success">CLOSED</span>
                                        @endif   
                                        @if ($serviceAccounts->NetMetered=='Yes')     
                                            <a href="{{ route('bills.adjust-bill-net-metering', [$item->id]) }}" class="btn btn-link btn-xs text-warning" title="Adjust Reading"><i class="fas fa-pen"></i></a>
                                        @else
                                            @if ($item->AdjustmentStatus == 'PENDING ADJUSTMENT APPROVAL')
                                                <a class="btn btn-xs btn-danger" title="{{ $item->AdjustmentStatus }}"><strong>! </strong> <i class="fas fa-pen"></i></a>
                                            @else
                                                <a href="{{ route('bills.adjust-bill', [$item->id]) }}" class="btn btn-link btn-xs text-warning" title="Adjust Reading"><i class="fas fa-pen"></i></a>
                                            @endif
                                        @endif                                        
                                    @endif
                                @endif
                                @if ($serviceAccounts->NetMetered=='Yes')
                                    <a href="{{ route('bills.print-single-net-metering', [$item->id]) }}" class="btn btn-xs btn-link" title="Print New Formatted Bill"><i class="fas fa-print"></i></a>
                                @else
                                    <a href="{{ route('bills.print-single-bill-new-format', [$item->id]) }}" class="btn btn-xs btn-link" title="Print New Formatted Bill"><i class="fas fa-print"></i></a>
                                @endif
                                
                                {{-- OVERFLOW ELLIPSIS --}}
                                <button class="btn btn-link btn-xs text-default" title="Adjustment History" onclick="showBillHistory('{{ $item->id }}')"><i class="fas fa-history"></i></button>
                                
                                <div class="btn-group" title="More options">
                                    <button type="button" class="btn btn-sm btn-link text-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v" style="color: #878787;"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @if ($item->ORNumber == null)
                                            @if (Bills::isBillDue($item))
                                                {{-- SKIPPABLE --}}
                                                @if ($item->Item3 != null)
                                                    <button class="dropdown-item btn btn-link" onclick="disallowSkipping(`{{ $item->id }}`)" title="Restrict cashier/teller to skip this bill."><i class="fas fa-user-slash ico-tab"></i>Disallow Skipping</button>                    
                                                @else
                                                    <button class="dropdown-item btn btn-link" onclick="allowSkipping(`{{ $item->id }}`)" title="Allow This Bill to Be Paid in the Future"><i class="fas fa-clipboard-check ico-tab"></i>Allow Skipping</button>
                                                @endif

                                                {{-- WAIVE SURCHARGE --}}
                                                @if ($item->SurchargeWaived != null)
                                                    <button onclick="unwaiveSurcharge(`{{ $item->id }}`)" class="dropdown-item btn btn-link" title="Unwaive Surcharges"><i class="fas fa-minus-circle ico-tab"></i>Unwaive Surcharges</button>
                                                @else
                                                    <button onclick="requestWaiveSurcharges(`{{ $item->id }}`)" class="dropdown-item btn btn-link" title="Waive Surcharges"><i class="fas fa-minus ico-tab"></i>Waive Surcharges</button>
                                                @endif
                                            @endif
                                            
                                            <button class="dropdown-item btn btn-link" title="Withholding taxes computation" onclick="withHoldingTaxes('{{ $item->id }}')"><i class="fas fa-percent ico-tab"></i>Withholding Taxes</button>
                                            <button class="dropdown-item btn btn-link" title="Mark as Paid (Application Adjustment)" onclick="markAsPaid('{{ $item->id }}')"><i class="fas fa-check-circle ico-tab"></i>Mark as Paid</button>
                                            <div class="dropdown-divider"></div>
                                            <button class="dropdown-item btn btn-link text-danger" title="Cancel this Bill" onclick="requestCancel('{{ $item->id }}')"><i class="fas fa-trash ico-tab"></i> Cancel Bill</button>                            
                                        @else
                                            <a class="dropdown-item btn btn-link" href="{{ $item->PaidBillId != null ? (route('transactionIndices.browse-ors-view', [$item->PaidBillId, 'BILLS PAYMENT'])) : '' }}"><i class="fas fa-info-circle ico-tab"></i>View Payment Details</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item btn btn-link" href="#" title="Transfer this payment to another bill"><i class="fas fa-exchange-alt ico-tab"></i>Credit Memo</a>
                                        @endif
                                    </div>
                                </div>
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

{{-- ALLOW SKIP --}}
<div class="modal fade" id="modal-allow-skip" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Skip Bills Payment?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Allow this Bill to be skipped from the Cashiering app? </p>
            </div>
            <div class="modal-footer justify-content-between">
                <button onclick="allowSkip(`SKIP_AUTO`)" class="btn btn-success float-right">Skip Automatically</button>
                <button onclick="allowSkip(`SKIP_MANUAL`)" class="btn btn-primary float-right">Allow Cashier to Manually Skip</button>
            </div>
        </div>
    </div>
</div>

{{-- WITHOLDING TAXES --}}
<div class="modal fade" id="modal-withholding-taxes" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Withholding Taxes</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="withholding-loader" class="spinner-border text-info gone" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <table class="table table-borderless table-hover table-sm">
                    <tbody>
                        <tr>
                            <td>Bill Amount</td>
                            <td class="text-right"><strong id="bill-amnt">0.0</strong></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="two-percent-toggle">
                                    <label class="custom-control-label" for="two-percent-toggle" style="font-weight: normal">2% WT</label>
                                </div>
                            </td>
                            <td class="text-right">                                
                                <input type="number" step="any" class="form-control form-control-sm text-right" id="2percent-amnt">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="five-percent-toggle">
                                    <label class="custom-control-label" for="five-percent-toggle" style="font-weight: normal">5% WT</label>
                                </div>
                            </td>
                            <td class="text-right">
                                <input type="number" step="any" class="form-control form-control-sm text-right" id="5percent-amnt">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Net Amount Due</strong></td>
                            <td class="text-right text-success"><strong id="net-amnt">0.0</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button onclick="saveWithholdingTax()" class="btn btn-primary float-right"><i class="fas fa-check-circle ico-tab"></i>Save</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        var selectedBillId = ""

        /**
         *  WITHOLDING AREA
         */
        var isFivePercent = false
        var isTwoPercent = false
        var fivePercentAmnt = 0 
        var twoPercentAmnt = 0
        var netAmntBeforeWT = 0
        var netAmntAfterWT = 0

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

        function allowSkipping(id) {
            $('#modal-allow-skip').modal('show')
            selectedBillId = id
        }

        function allowSkip(skipStatus) {
            $.ajax({
                url : "{{ route('bills.allow-skip') }}",
                type : 'GET',
                data : {
                    id : selectedBillId,
                    SkipStatus : skipStatus,
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Billed allowed to be skipped'
                    })
                    location.reload()
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error skipping bill to cashier'
                    })
                }
            })
        }

        function requestWaiveSurcharges(id) {
            Swal.fire({
                title: 'Waive Surcharges?',
                text : "Are you sure you want to waive this bill's surcharges? This will still be a subject for approval.",
                showCancelButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('bills.request-waive-surcharges') }}",
                        type : 'GET',
                        data : {
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Waiving of surcharges requested!'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error waiving surcharges!'
                            })
                        }
                    })
                }
            })
        }

        function withHoldingTaxes (id) {
            $('#withholding-loader').removeClass('gone')
            selectedBillId = id
            $('#modal-withholding-taxes').modal('show')

            // FETCH BILL
            $.ajax({
                url : "{{ route('bills.get-bill-ajax') }}",
                type : "GET",
                data : {
                    id : selectedBillId,
                },
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {
                        $('#modal-withholding-taxes').modal('hide')
                        Swal.fire({
                            icon : 'info',
                            text : 'Bill not found!'
                        })
                    } else {
                        netAmntBeforeWT = parseFloat(res['NetAmount'])
                        $('#bill-amnt').text(Number(parseFloat(res['NetAmount'])).toLocaleString())
                        netAmntAfterWT = parseFloat(res['Balance'])
                        $('#net-amnt').text(Number(parseFloat(res['Balance'])).toLocaleString())

                        fivePercentAmnt = jQuery.isEmptyObject(res['Evat5Percent']) ? 0 : parseFloat(res['Evat5Percent'])
                        twoPercentAmnt = jQuery.isEmptyObject(res['Evat2Percent']) ? 0 :  parseFloat(res['Evat2Percent'])

                        if (fivePercentAmnt > 0) {
                            $('#five-percent-toggle').prop('checked', true)
                            isFivePercent = true
                        }

                        if (twoPercentAmnt > 0) {
                            $('#two-percent-toggle').prop('checked', true)
                            isTwoPercent = true
                        }

                        validateWithholding()
                    }
                    $('#withholding-loader').addClass('gone')
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error getting bill!'
                    })
                    $('#withholding-loader').addClass('gone')
                }
            })
        }

        function saveWithholdingTax() {
            Swal.fire({
                title: 'Do you want to save the changes?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('bills.save-withholding-taxes') }}",
                        type : "GET",
                        data : {
                            id : selectedBillId,
                            TwoPercent : $('#2percent-amnt').val(),
                            FivePercent : $('#5percent-amnt').val(),
                            NetAmount : netAmntAfterWT
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Withholding applied to bill!'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error applying witholding tax to bill!'
                            })
                        }
                    })
                }
            })
        }

        function validateWithholding() {
            if (isTwoPercent) {
                $('#2percent-amnt').prop('disabled', false)
            } else {
                $('#2percent-amnt').prop('disabled', true)
            }
            
            if (isFivePercent) {
                $('#5percent-amnt').prop('disabled', false)
            } else {
                $('#5percent-amnt').prop('disabled', true)
            }

            $('#2percent-amnt').val(twoPercentAmnt)
            $('#5percent-amnt').val(fivePercentAmnt)

            var wt = parseFloat(twoPercentAmnt) + parseFloat(fivePercentAmnt)

            netAmntAfterWT = netAmntBeforeWT - wt
            $('#net-amnt').text(Number(parseFloat(netAmntAfterWT)).toLocaleString())
        }

        function unwaiveSurcharge(id) {
            Swal.fire({
                title: 'Unwaive Surcharges?',
                text : "Are you sure you want to undo waiving of this bill's surcharges?",
                showCancelButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('bills.unwaive-surcharges') }}",
                        type : 'GET',
                        data : {
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Waiving of surcharges removed!'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error unwaiving surcharges!'
                            })
                        }
                    })
                }
            })
        }

        function disallowSkipping(id) {
            Swal.fire({
                title: 'Disallow Skipping?',
                text : "Restrict cashier/teller to skip this bill?",
                showCancelButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('bills.allow-skip') }}",
                        type : 'GET',
                        data : {
                            id : id,
                            SkipStatus : null,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Billed disallowed to be skipped'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error performing task!'
                            })
                        }
                    })
                }
            })
        }

        $(document).ready(function() {
            $('#two-percent-toggle').on('change', function(e) {
                if (isTwoPercent) {
                    isTwoPercent = false
                    twoPercentAmnt = 0
                } else {
                    isTwoPercent = true
                    twoPercentAmnt = $('#' + selectedBillId).attr('twoPercent')
                }
                validateWithholding()
            })

            $('#five-percent-toggle').on('change', function(e) {
                if (isFivePercent) {
                    isFivePercent = false
                    fivePercentAmnt = 0
                } else {
                    isFivePercent = true
                    fivePercentAmnt = $('#' + selectedBillId).attr('fivePercent')
                }
                validateWithholding()
            })

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

            $('#modal-withholding-taxes').on('hidden.bs.modal', function (e) {
                selectedBillId = ""
                var isFivePercent = false
                var isTwoPercent = false
                var fivePercentAmnt = 0 
                var twoPercentAmnt = 0
                var netAmntBeforeWT = 0
                var netAmntAfterWT = 0

                $('#2percent-amnt').val("0")
                $('#5percent-amnt').val("0")
                $('#bill-amnt').text("0")
                $('#net-amnt').text("0")

                $('#five-percent-toggle').prop('checked', false)
                $('#two-percent-toggle').prop('checked', false)
            });

            $('#2percent-amnt').on('change', function() {
                twoPercentAmnt = this.value
                validateWithholding()
            })

            $('#2percent-amnt').on('keyup', function() {
                twoPercentAmnt = this.value
                validateWithholding()
            })

            $('#5percent-amnt').on('change', function() {
                fivePercentAmnt = this.value
                validateWithholding()
            })

            $('#5percent-amnt').on('keyup', function() {
                fivePercentAmnt = this.value
                validateWithholding()
            })
        })
    </script>
@endpush