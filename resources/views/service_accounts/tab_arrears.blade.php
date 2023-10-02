@php
    use App\Models\Bills;
    use App\Models\IDGenerator;

    // GET PREVIOUS MONTHS
    for ($i = -1; $i <= 90; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' ) . " +$i months"));
    }
@endphp
<div class="content">
    <div class="row">
        {{-- Uncollected Arrears --}}
        <div class="col-lg-6">
            {{-- COLLECTIBLES --}}
            <div class="card" style="height: 60vh;">
                <div class="card-header border-0">
                    <span class="card-title">Termed Payments</span>
                    <div class="card-tools">
                        @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Data Administrator'])) 
                            <button class="btn btn-tool" title="Add New Termed Payment" data-toggle="modal" data-target="#modal-add-termed-payment"><i class="fas fa-plus"></i></button>
                            {{-- <button class="btn btn-tool" title="Update Figure" data-toggle="modal" data-target="#modal-update-collectible"><i class="fas fa-pen"></i></button>      --}}
                        @endif                    
                    </div>
                </div>
                <div class="card-body table-responsive">
                    @if ($collectibles != null)
                        @php
                            $totalCol = 0;
                        @endphp
                        @foreach ($collectibles as $item)
                            @php
                                $totalCol += floatval($item->Balance);
                            @endphp
                        @endforeach
                        <span class="text-muted"><i>Total Termed Payments</i></span>
                        <h3 class="text-danger">₱ {{ number_format($totalCol, 2) }}</h3>

                        @if ($collectibles != null)
                            <div class="divider"></div>
                            <p><i>Termed Payment Profiles</i></p>

                            <table class="table table-sm table-hover">
                                <thead>
                                    <th>Description</th>
                                    <th class="text-right">Amount to Pay</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach ($collectibles as $item)
                                        <tr>
                                            <td>{{ $item->Notes }}</td>
                                            <td class="text-right text-danger">₱ {{ number_format($item->Balance, 2) }}</td>
                                            <td class="text-right">
                                                <button onclick="viewDetails(`{{ $item->id }}`, `{{ $item->Notes }}`, `{{ $item->Balance }}`)" class="btn btn-link text-primary" title="View Details"><i class="fas fa-eye"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- <button class="btn btn-link text-success float-right" data-toggle="modal" data-target="#modal-add-month"><i class="fas fa-plus"></i></button>
                            {!! Form::open(['route' => ['collectibles.clear-ledger', $serviceAccounts->id], 'method' => 'post']) !!}
                            {!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-sm btn-link text-danger float-right', 'title' => 'Clear ledger', 'onclick' => "return confirm('Are you sure you want to clear this ledger?')"]) !!}
                            {!! Form::close() !!} --}}
                        @else
                            <p>No Termed Payments Recorded</p>
                        @endif                    
                    @else
                        <h3 class="text-success">₱ 0.00</h3>
                    @endif
                </div>
            </div>
        </div>

        {{-- Monthly Bill Arrears --}}
        <div class="col-lg-6">
            <div class="card" style="height: 60vh;">
                <div class="card-header border-0">
                    <span class="card-title">Monthly Bill Arrears</span>

                    <div class="card-tools">
                        @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Data Administrator'])) 
                            @if (count($unmergedArrears) > 0)
                                <a href="{{ route('serviceAccounts.merge-all-bill-arrears', [ $serviceAccounts->id]) }}" class="btn btn-xs btn-danger" title="Merge All Arrears"><i class="fas fa-stream ico-tab-mini"></i>Merge All</a>
                            @else
                                <a href="{{ route('serviceAccounts.unmerge-all-bill-arrears', [ $serviceAccounts->id]) }}" class="btn btn-xs btn-primary" title="Unmerge All Arrears"><i class="fas fa-folder-minus ico-tab-mini"></i>Unmerge All</a> 
                            @endif
                        @endif
                    </div>
                </div>
                <div class="card-body table-responsive px-0">
                    <table class="table table-hover">
                        <thead>
                            <th>Bill No.</th>
                            <th>Billing Mnth.</th>
                            <th>Amount</th>
                            <th>Penalty</th>
                            <th width="60px"></th>
                        </thead>
                        <tbody>
                            @if (count($billArrears) > 0) 
                                @foreach ($billArrears as $item)
                                    <tr>
                                        <td>{{ $item->BillNumber }}</td>
                                        <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                        <td>{{ is_numeric($item->NetAmount) ? number_format(str_replace(',', '', $item->NetAmount), 2) : $item->NetAmount }}</td>
                                        <td>{{ number_format(Bills::getFinalPenalty($item), 2) }}</td>
                                        <td>
                                            @if (Auth::user()->hasAnyRole(['Administrator', 'Heads and Managers', 'Data Administrator'])) 
                                                @if ($item->MergedToCollectible == 'Yes')
                                                    <a href="{{ route('serviceAccounts.unmerge-bill-arrear', [$item->id]) }}" class="btn btn-xs btn-link text-primary" title="Unmerge this arrear to collectibles"><i class="fas fa-folder-minus"></i></a>
                                                @else
                                                    <a href="{{ route('serviceAccounts.merge-bill-arrear', [$item->id]) }}" class="btn btn-xs btn-link text-danger" title="Merge this arrear to collectibles"><i class="fas fa-stream"></i></a>
                                                @endif    
                                            @endif                                        
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>

        {{-- Arrears transaction history --}}
        <div class="col-lg-12">
            <div class="card collapsed-card">
                <div class="card-header">
                    <span class="card-title">Arrears Transaction History</span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive px-0">
                    <table class="table table-sm table-hover">
                        <thead>
                            <th>Transaction No.</th>
                            <th>OR Number</th>
                            <th>OR Date</th>
                            <th>Payment Details</th>
                            <th class="text-right" style="padding-right: 20px;">Total</th>
                            <th>Payment Type</th>
                        </thead>
                        <tbody>
                            @foreach ($arrearTransactionHistory as $item)
                                <tr>
                                    <td>{{ $item->TransactionNumber }}</td>
                                    <td>{{ $item->ORNumber }}</td>
                                    <td>{{ date('F d, Y', strtotime($item->ORDate)) }}</td>
                                    <td>{{ $item->PaymentTitle }}</td>
                                    <td class="text-right" style="padding-right: 20px;">₱ {{ number_format($item->Total, 2) }}</td>
                                    <td>{{ $item->PaymentUsed }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ADD NEW TERMED PAYMENT MODAL --}}
<div class="modal fade" id="modal-add-termed-payment" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">New Termed Payment Profile</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-sm-12">
                    <label for="Notes">Termed Payment Name/Description</label>
                    <input type="text" class="form-control form-control-sm" id="NewNotes">
                </div>
                
                <div class="form-group col-sm-12">
                    <label for="Balance">Amount</label>
                    <input type="number" step="any" class="form-control form-control-sm" id="NewBalance">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button onclick="saveNewTermedPayment()" class="btn btn-success"><i class="fas fa-check-circle ico-tab"></i>Save</button>
            </div>
        </div>
    </div>
</div>

{{-- UPDATE COLLECTIBLES MODAL --}}
{{-- <div class="modal fade" id="modal-update-collectible" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            @if ($collectibles != null)
                {!! Form::model($collectibles, ['route' => ['collectibles.update', $collectibles->id], 'method' => 'patch']) !!}
            @else
                {!! Form::open(['route' => 'collectibles.store']) !!}
            @endif
            <div class="modal-header">
                <h4 class="modal-title">Update Uncollected Arrears</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @if ($collectibles == null)
                    <input type="hidden" name="id" value="{{ IDGenerator::generateIDandRandString() }}">
                @endif

                <input type="hidden" name="AccountNumber" value="{{ $serviceAccounts->id }}">

                <!-- Balance Field -->
                <div class="form-group col-sm-12">
                    {!! Form::label('Balance', 'Balance') !!}
                    {!! Form::number('Balance', null, ['class' => 'form-control', 'step' => 'any', 'maxlength' => 60,'maxlength' => 60]) !!}
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div> --}}

{{-- LEDGERIZE MODAL --}}
<div class="modal fade" id="modal-ledgerize" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Split Payment Into Terms</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="Term">Term (in months)</label>
                    <input type="number" id="Term" placeholder="Enter number of months to pay" class="form-control"/>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="term-btn">Proceed</button>
            </div>
        </div>
    </div>
</div>

{{-- ADD MONTH MODAL ON LEDGER/OCL --}}
<div class="modal fade" id="modal-add-month" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Another Month in this OCL</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="Month">Month</label>
                    <select name="Month" id="Month" class="form-control">
                        @for ($i = 0; $i < count($months); $i++)
                            <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label for="AmountToAdd">Amount</label>
                    <input type="number" id="AmountToAdd" step="any" placeholder="Enter amount" class="form-control"/>
                </div>
                
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="add-month-btn">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-termed-view" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="termed-view-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <span class="text-muted">Balance</span>
                        <h3 id="termed-view-balance" class="text-danger"></h3>

                        <span class="text-muted"><i>Monthly Ammortization Ledger</i></span>
                        <div id="termed-view-btn-tools"></div>
                        <br>
                        <table id="termed-view-ledger-table" class="table table-sm table-hover">
                            <thead>
                                <th>Billing Month</th>
                                <th class="text-right">Amount</th>
                                <th></th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            
        })

        function saveNewTermedPayment() {
            if (jQuery.isEmptyObject($('#NewNotes').val()) | jQuery.isEmptyObject($('#NewBalance').val())) {
                Toast.fire({
                    icon : 'warning',
                    text : 'Please input name and balance'
                })
            } else {
                Swal.fire({
                    title: 'Save New Termed Payment?',
                    text : 'You can always edit or remove this later.',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    denyButtonText: `No`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{{ route('collectibles.add-new') }}",
                            type : "GET",
                            data : {
                                AccountNumber : "{{ $serviceAccounts->id }}",
                                Notes : $('#NewNotes').val(),
                                Balance : $('#NewBalance').val(),
                            },
                            success : function(res) {
                                Toast.fire({
                                    icon : 'success',
                                    text : 'Termed Payment Saved!'
                                })
                                location.reload()
                                $('#modal-add-termed-payment').modal('hide')
                            },
                            error : function(err) {
                                Swal.fire({
                                    icon : 'error',
                                    title : 'Error saving termed payment!'
                                })
                            }
                        })
                    } 
                })
            }            
        }

        function viewDetails(id, title, balance) {
            $('#modal-termed-view').modal('show')
            $('#termed-view-title').text(title)
            $('#termed-view-balance').text('₱ ' + Number(parseFloat(balance)).toLocaleString())
            $('#termed-view-ledger-table tbody tr').remove()
            $('#termed-view-btn-tools button').remove()

            $.ajax({
                url : "{{ route('collectibles.get-ledger-from-collectible') }}",
                type : "GET",
                data : {
                    id : id,
                },
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {
                        $('#termed-view-btn-tools').append('<button onclick=term(`' + id + '`) class="btn btn-tool btn-sm text-warning float-right" title="Split into multiple months (termed payment)"><i class="fas fa-clipboard-list"></i></button>')
                    } else {
                        $('#termed-view-btn-tools').append('<button onclick=clearLedger(`' + id + '`) class="btn btn-link btn-sm text-danger float-right"><i class="fas fa-trash"></i></button>')
                    }
                    $('#termed-view-ledger-table tbody').append(res)
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting ledger data!'
                    })
                }
            })
        }

        function term(id) {
            (async () => {
                const { value: text } = await Swal.fire({
                    input: 'number',
                    inputLabel: 'Input No. of Months',
                    inputPlaceholder: 'No. of Months Here',
                    title: 'Ammortization Schedule',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Specify number of months!'
                        }
                    }
                })

                if (text) {
                    $.ajax({
                        url : '{{ route("collectibles.ledgerize") }}',
                        type : 'GET',
                        data : {
                            CollectibleId : id,
                            Term : text,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Ammortization schedule set!'
                            })
                            location.reload()
                        }, 
                        error : function(err) {
                            Swal.fire({
                                title : 'Oops!',
                                text : 'An error occurred during the transaction',
                                icon : 'error'
                            })
                        }
                    })
                }
            })()
        }

        function clearLedger(id) {
            Swal.fire({
                title: 'Clear Unpaid Termed Payments?',
                text : 'This cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: `No`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('collectibles.clear-ledger') }}",
                        type : "GET",
                        data : {
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Termed Payment Ammortization Cleared!'
                            })
                            location.reload()
                        },
                        error : function(err) {                            
                            Toast.fire({
                                icon : 'error',
                                text : 'Error clearing termed payment ammotization!'
                            })
                        }
                    })
                } 
            })
        }
    </script>
@endpush