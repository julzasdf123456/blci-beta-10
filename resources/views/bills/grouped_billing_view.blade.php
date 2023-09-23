@php
    use App\Models\ServiceAccounts;
    use App\Models\MemberConsumers;

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
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Grouped Billing {{ $memberConsumer != null ? (' - ' . MemberConsumers::serializeMemberName($memberConsumer)) : '' }}</h4>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- ACCOUNTS --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Accounts in this Group</span>
                <div class="card-tools">
                    <button class="btn btn-default btn-xs" title="Print Reading List" data-toggle="modal" data-target="#modal-select-period-reading"><i class="fas fa-print ico-tab-mini"></i>Reading List</button>
                    <button class="btn btn-danger btn-xs" title="Bill All Accounts" data-toggle="modal" data-target="#modal-select-period"><i class="fas fa-coins ico-tab-mini"></i> Bill All</button>
                    <a href="{{ route('bills.create-group-billing-step-two', [$memberConsumer->ConsumerId]) }}" class="btn btn-primary btn-xs"><i class="fas fa-pen ico-tab-mini"></i>Edit</a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-borderd table-sm">
                    <thead>
                        <th>Account ID</th>
                        <th>Account No</th>
                        <th>Consumer Name</th>
                        <th>Consumer Address</th>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $item)
                            <tr>
                                <td><a href="{{ route('serviceAccounts.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                <td>{{ $item->OldAccountNo }}</td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ ServiceAccounts::getAddress($item) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <p>No. of Consumers: <strong>{{ count($accounts) }}</strong></p>
            </div>
        </div>
    </div>

    {{-- LEDGERS --}}
    <div class="col-lg-5">
        <div class="card" style="height: 80vh;">
            <div class="card-header border-0">
                <span class="card-title">Ledgers</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm table-bordered">
                    <thead>
                        <th>Billing Month</th>
                        <th>No. of Bills</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($ledgers as $item)
                            <tr>
                                <td>{{ date('F d, Y', strtotime($item->ServicePeriod)) }}</td>
                                <td>{{ $item->BillCount }}</td>
                                <td class="text-right">
                                    <a title="Print Bills in List Form" href="{{ route('serviceAccounts.print-group-bills-list', [$item->ServicePeriod, $memberConsumer->ConsumerId]) }}" class="text-success ico-tab"><i class="fas fa-clipboard-list"></i></a>
                                    <a title="Print Bills in New Format" href="{{ route('bills.print-bulk-bill-new-format-group', [$item->ServicePeriod, $memberConsumer->ConsumerId]) }}" class="text-primary ico-tab"><i class="fas fa-print"></i></a>
                                    <a title="Print Bills in Old Format" href="{{ route('bills.print-bulk-bill-old-format-group', [$item->ServicePeriod, $memberConsumer->ConsumerId]) }}" class="text-warning ico-tab"><i class="fas fa-print"></i></a>
                                    {{-- <a title="Print Statement Summary" href="{{ route('bills.print-group-billing', [$memberConsumer->ConsumerId, $item->ServicePeriod]) }}" class="text-info ico-tab"><i class="fas fa-print"></i></a> --}}
                                    <button class="btn btn-link" title="Print Statement Summary" onclick="printGroup('{{ $memberConsumer->ConsumerId }}', '{{ $item->ServicePeriod }}')"><i class="fas fa-print text-info"></i></button>
                                    <a title="View Details" href="{{ route('bills.grouped-billing-bill-view', [$memberConsumer->ConsumerId, $item->ServicePeriod]) }}"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- PRINTOPTIONS MODAL --}}
<div class="modal fade" id="modal-print-options" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Print Options</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">     
                <input type="hidden" id="Period"> 
                <input type="hidden" id="MemId">          
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="WaiveSurcharge">
                    <label class="form-check-label" for="WaiveSurcharge">
                        Waive Surcharge
                    </label>
                  </div>
            </div>
            <div class="modal-footer">
                <button id="print-group" class="btn btn-primary"><i class="fas fa-print ico-tab-mini"></i>Print</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL SELECT PERIOD MODAL --}}
<div class="modal fade" id="modal-select-period" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Parameters</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="ServicePeriod">Billing Month</label>
                    <select name="ServicePeriod" id="ServicePeriod" class="form-control">
                        @for ($i = 0; $i < count($months); $i++)
                            <option value="{{ $months[$i] }}" {{ $rate != null ? (date('Y-m-d', strtotime($rate->ServicePeriod)) == $months[$i] ? 'selected' : '') : '' }}>{{ date('F Y', strtotime($months[$i])) }}</option>
                        @endfor
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="KwhUsed">Static Kwh</label>
                    <input type="number" id="KwhUsed" step="any" class="form-control" placeholder="Kwh Used">
                </div>

                <div class="form-group">
                    <label for="Demand">Demand</label>
                    <input type="number" id="Demand" step="any" class="form-control" placeholder="Kwh Used">
                </div>
            </div>
            <div class="modal-footer">
                <button id="bill-all-btn" class="btn btn-primary"><i class="fas fa-check-circle ico-tab-mini"></i>Bill All</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL SELECT PERIOD FOR READINGS --}}
<div class="modal fade" id="modal-select-period-reading" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4>Select Billing Month To Print</h4>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="ServicePeriodPrint">Billing Month</label>
                    <select name="ServicePeriodPrint" id="ServicePeriodPrint" class="form-control">
                        @for ($i = 0; $i < count($months); $i++)
                            <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="print-reading-list"><i class="fas fa-print ico-tab-mini"></i>Print</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        var withSurcharge = "No"

        $(document).ready(function() {
            $('#bill-all-btn').on('click', function() {
                billAll($('#ServicePeriod').val())
            })

            $('#print-group').on('click', function() {
                if($('#WaiveSurcharge').prop('checked')) {
                    withSurcharge = 'Yes'
                } else {
                    withSurcharge = 'No'
                }
                window.location.href = "{{ url('/bills/print-group-billing') }}" + "/" + $('#MemId').val() + "/" + $('#Period').val() + "/" + withSurcharge
            })

            $('#print-reading-list').on('click', function() {
                window.location.href = "{{ url('/readings/print-group-reading-list') }}" + "/" + "{{ $memberConsumer->ConsumerId }}" + "/" + $('#ServicePeriodPrint').val()
            })
        })

        function billAll(period) {
            $.ajax({
                url : "{{ route('bills.group-bill-all') }}",
                type : 'GET',
                data : {
                    ServicePeriod : period,
                    GroupId : "{{ $memberConsumer->ConsumerId }}",
                    KwhUsed : $('#KwhUsed').val(),
                    Demand : $('#Demand').val()
                },
                success : function(res) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'All accounts billed successfully',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    location.reload()
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Billing error',
                        icon : 'error'
                    })
                }
            })
        }

        function printGroup(memId, period) {            
            $('#modal-print-options').modal('show')
            $('#WaiveSurcharge').prop('checked', false)
            $('#Period').val(period)
            $('#MemId').val(memId)
        }
    </script>
@endpush