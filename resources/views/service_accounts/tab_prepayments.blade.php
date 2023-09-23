@php
    use App\Models\IDGenerator;
@endphp

<div class="row">
    <div class="col-lg-12">
        <p class="text-center text-muted">Balance</p>
        <p class="text-center text-success" style="font-size: 2.5em;">₱ {{ $prepaymentBalance != null ? (number_format($prepaymentBalance->Balance, 2)) : "0.0" }}</p>
        @if (Auth::user()->hasAnyRole(['Administrator'])) 
            <button id="remove-deposit" class="btn btn-danger btn-sm float-right" style="margin-right: 15px;" title="Remove Prepayment/Deposit Balance"><i class="fas fa-trash"></i></button>
            <button id="add-deposit" class="btn btn-success btn-sm float-right" data-toggle="modal" data-target="#modal-add-balance" style="margin-right: 15px;" title="Add Prepayment/Deposit Balance Manual"><i class="fas fa-plus"></i></button>
        @endif
    </div>

    <div class="col-lg-12">
        <div class="divider"></div>

        <p>Transaction History</p>
        <table class="table table-hover table-sm table-borderless">
            <thead>
                <th>Transaction ID</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Personnel</th>
                <th>Transaction Date</th>
            </thead>
            <tbody>
                @foreach ($prepaymentHistory as $item)
                    <tr title="{{ $item->Notes }}">
                        <td>{{ $item->id }}</td>
                        <td class="{{ $item->Method == 'DEPOSIT' ? 'text-success' : 'text-danger' }}">{{ $item->Method }}</td>
                        <td>{{ number_format($item->Amount, 2) }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ date('M d, Y h:i:s A', strtotime($item->created_at)) }}</td>
                        @if (Auth::user()->hasAnyRole(['Administrator'])) 
                        <td width="20">
                            {!! Form::open(['route' => ['prePaymentTransHistories.destroy', $item->id], 'method' => 'delete']) !!}
                            <div class='btn-group'>
                                {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


{{-- UPDATE COLLECTIBLES MODAL --}}
<div class="modal fade" id="modal-add-balance" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Balance Manually</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Balance Field -->
                <div class="form-group col-sm-12">
                    {!! Form::number('BalanceFigure', null, ['id' => 'BalanceFigure', 'class' => 'form-control', 'step' => 'any', 'placeholder' => 'Enter amount', 'autofocus' => 'true']) !!}
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="save-balance" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#remove-deposit').on('click', function() {
                (async () => {
                    const { value: text } = await Swal.fire({
                        title: 'Clear Deposit/Prepayment?',
                        text : 'Are you sure to want to clear this prepayment/deposit balance?',
                        showCancelButton: true
                    })

                    if (text) {
                        $.ajax({
                            url : '{{ route("paidBills.clear-deposit") }}',
                            type : 'GET',
                            data : {
                                AccountNumber : "{{ $serviceAccounts->id }}",
                            },
                            success : function(res) {
                                Swal.fire('Deposit/Prepayment Cleared', 'Deposit/Prepayment cleared successfully!', 'success')
                                location.reload()
                            },
                            error : function(err) {
                                Swal.fire('Deposit/Prepayment Clearing Error', 'Contact support immediately', 'error')
                            }
                        })
                    }
                })()
            })

            $('#save-balance').on('click', function(e) {
                e.preventDefault()
                var amount = $('#BalanceFigure').val()
                console.log(amount)
                if (jQuery.isEmptyObject(amount)) {
                    Toast.fire({
                        title : 'Please specify an amount!',
                        icon : 'warning'
                    })
                } else {
                    $.ajax({
                        url : "{{ route('serviceAccounts.add-prepayment-balance-manually') }}",
                        type : 'GET',
                        data : {
                            id : "{{ $serviceAccounts->id }}",
                            Amount : amount
                        },
                        success : function(res) {
                            Toast.fire({
                                title : 'Balance added!',
                                icon : 'success'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Toast.fire({
                                title : 'Error adding balance!',
                                icon : 'error'
                            })
                        }
                    })
                }
                
            })
        })
    </script>
@endpush