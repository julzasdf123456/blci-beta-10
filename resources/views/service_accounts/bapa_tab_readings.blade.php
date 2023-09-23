<table class="table table-hover table-bordered table-sm p-0">
    <thead>
        <th>Billing Month</th>
        <th class="text-right">Total No. Readings</th>
        <th class="text-right">Total Kwh</th>
        <th class="text-right">Total Amount</th>
        <th></th>
    </thead>
    <tbody>
        @foreach ($readings as $item)
            <tr>
                <td><a href="{{ route('bills.bapa-view-readings', [$item->ServicePeriod, urlencode($bapaName)]) }}">{{ date('F Y', strtotime($item->ServicePeriod)) }}</a></td>
                <td class="text-right">{{ number_format($item->NoOfReadings) }}</td>
                <td class="text-right"><strong class="text-success">{{ number_format($item->TotalKwh) }}</strong></td>
                <td class="text-right"><strong class="text-danger">{{ number_format($item->TotalAmount, 2) }}</strong></td>
                <td class="text-right">
                    <a href="{{ route('readings.view-full-report-bapa', [$item->ServicePeriod, urlencode($bapaName)]) }}" title="View Full Report" style="margin-right: 15px;"><i class="fas fa-file text-success"></i></a>
                    <a href="{{ route('serviceAccounts.print-bapa-bills-list', [urlencode($bapaName), $item->ServicePeriod]) }}" title="Print Bills in List" style="margin-right: 6px;"><i class="fas fa-print text-success"></i></a>
                    <button class="btn btn-link text-warning" title="Print Using Old Format (Green pre-printed)" onclick="verifyBillNo('{{ $item->ServicePeriod }}', '{{ urlencode($bapaName) }}')" ><i class="fas fa-print text-warning"></i></button>
                    <button class="btn btn-link text-info" title="Print Using New Format" onclick="verifyBillNoNew('{{ $item->ServicePeriod }}', '{{ urlencode($bapaName) }}')" ><i class="fas fa-print text-info"></i></button>
                    {{-- <a href="{{ route('bills.print-bulk-bill-old-format-bapa', [$item->ServicePeriod, urlencode($bapaName), 'All']) }}" style="margin-right: 10px;"><i class="fas fa-print text-warning"></i></a> --}}
                    <button class="btn btn-link text-danger" title="Adjust Due Date" onclick="adjustDueDate('{{ $item->ServicePeriod }}', '{{ $bapaName }}')" style="margin-right: 5px;" ><i class="fas fa-edit text-danger"></i></button>
                    <a title="View Reading" href="{{ route('bills.bapa-view-readings', [$item->ServicePeriod, urlencode($bapaName)]) }}"><i class="fas fa-eye"></i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Print Ledger History --}}
<div class="modal fade" id="modal-print-bill" aria-hidden="true" style="display: none;" period="">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Print Bill</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="period">
                    <input type="hidden" id="bapaname">
                    <div class="form-group col-lg-12">
                        <label for="FromBillNo">From Bill #</label>
                        <input type="text" id="FromBillNo" class="form-control">
                    </div>

                    <div class="form-group col-lg-12">
                        <label for="Route">Route</label>
                        <input type="text" maxlength="5" id="Route" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="print-bill-bapa" class="btn btn-primary"><i class="fas fa-print ico-tab-mini"></i>Print</button>
            </div>
        </div>
    </div>
</div>

{{-- Print New Format --}}
<div class="modal fade" id="modal-print-bill-new-format" aria-hidden="true" style="display: none;" period="">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Print Bill New Format</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="period">
                    <input type="hidden" id="bapaname">
                    <div class="form-group col-lg-12">
                        <label for="FromBillNo">From Bill #</label>
                        <input type="text" id="FromBillNo" class="form-control">
                    </div>

                    <div class="form-group col-lg-12">
                        <label for="RouteBapa">Route</label>
                        <input type="text" maxlength="5" id="RouteBapa" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="print-bill-bapa-new-format" class="btn btn-primary"><i class="fas fa-print ico-tab-mini"></i>Print</button>
            </div>
        </div>
    </div>
</div>

{{-- Adjust Due Date --}}
<div class="modal fade" id="modal-adjust-duedate" aria-hidden="true" style="display: none;" period="">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Adjust Due Date</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="period">
                    <input type="hidden" id="bapaname">
                    <div class="form-group col-lg-12">
                        <label for="NewDueDate">New Due Date</label>
                        <input type="text" id="NewDueDate" class="form-control">
                        @push('page_scripts')
                            <script type="text/javascript">
                                $('#NewDueDate').datetimepicker({
                                    format: 'YYYY-MM-DD',
                                    useCurrent: true,
                                    sideBySide: true
                                })
                            </script>
                        @endpush
                    </div>

                    <div class="form-group col-lg-12">
                        <label for="RouteDueDate">Route</label>
                        <select name="RouteDueDate" id="RouteDueDate" class="form-control">
                            <option value="All">All</option>
                            @foreach ($routes as $item)
                                <option value="{{ $item->AreaCode }}">{{ $item->AreaCode }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="change-due-date" class="btn btn-danger"><i class="fas fa-pen ico-tab-mini"></i>Change Due Date</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        var periodx = ''
        var bapaname =''
        function verifyBillNo(period, bapaName) {
            periodx = period
            bapaname = bapaName
            $('#modal-print-bill').modal('show')
        }

        function verifyBillNoNew(period, bapaName) {
            periodx = period
            bapaname = bapaName
            $('#modal-print-bill-new-format').modal('show')
        }

        function adjustDueDate(period, bapaName) {
            periodx = period
            bapaname = bapaName
            $('#modal-adjust-duedate').modal('show')
        }

        $(document).ready(function() {
            $('#modal-print-bill').on('shown.bs.modal', function () {
                $('#period').val(periodx)
                $('#bapaname').val(bapaname)
            })

            $('#print-bill-bapa').on('click', function() {
                if(jQuery.isEmptyObject($('#FromBillNo').val()) && jQuery.isEmptyObject($('#Route').val())) {
                    window.location.href  = "{{ url('/bills/print-bulk-bill-old-format-bapa') }}/" + periodx + "/" + bapaname + "/All/All"
                } else if (jQuery.isEmptyObject($('#FromBillNo').val()) && !jQuery.isEmptyObject($('#Route').val())) {
                    window.location.href  = "{{ url('/bills/print-bulk-bill-old-format-bapa') }}/" + periodx + "/" + bapaname + "/All/" + $('#Route').val()
                } else if (!jQuery.isEmptyObject($('#FromBillNo').val()) && jQuery.isEmptyObject($('#Route').val())) {
                    window.location.href  = "{{ url('/bills/print-bulk-bill-old-format-bapa') }}/" + periodx + "/" + bapaname + "/" + $('#FromBillNo').val() + "/All"
                } else {
                    window.location.href  = "{{ url('/bills/print-bulk-bill-old-format-bapa') }}/" + periodx + "/" + bapaname + "/" + $('#FromBillNo').val() + "/" + $('#Route').val()
                }            
            })

            $('#print-bill-bapa-new-format').on('click', function() {
                if(jQuery.isEmptyObject($('#FromBillNo').val()) && jQuery.isEmptyObject($('#RouteBapa').val())) {
                    window.location.href  = "{{ url('/bills/print-bulk-bill-new-format-bapa') }}/" + periodx + "/" + bapaname + "/All/All"
                } else if (jQuery.isEmptyObject($('#FromBillNo').val()) && !jQuery.isEmptyObject($('#RouteBapa').val())) {
                    window.location.href  = "{{ url('/bills/print-bulk-bill-new-format-bapa') }}/" + periodx + "/" + bapaname + "/All/" + $('#RouteBapa').val()
                } else if (!jQuery.isEmptyObject($('#FromBillNo').val()) && jQuery.isEmptyObject($('#RouteBapa').val())) {
                    window.location.href  = "{{ url('/bills/print-bulk-bill-new-format-bapa') }}/" + periodx + "/" + bapaname + "/" + $('#FromBillNo').val() + "/All"
                } else {
                    window.location.href  = "{{ url('/bills/print-bulk-bill-new-format-bapa') }}/" + periodx + "/" + bapaname + "/" + $('#FromBillNo').val() + "/" + $('#RouteBapa').val()
                }            
            })

            $('#change-due-date').on('click', function() {
                if(jQuery.isEmptyObject($('#NewDueDate').val())) {
                    Swal.fire({
                        title : 'Due date should never be empty!',
                        icon : 'warning'
                    })
                } else {
                    $.ajax({
                        url : "{{ route('bills.change-bapa-duedate') }}",
                        type : 'GET',
                        data : {
                            Period : periodx,
                            BAPAName : bapaname,
                            Route : $('#RouteDueDate').val(),
                            NewDueDate : $('#NewDueDate').val()
                        },
                        success : function(res) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Due date updated!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Swal.fire({
                                title : 'An error occurred while changing the due date!',
                                error : 'warning'
                            })
                        }
                    })
                }
            })
        })
    </script>
@endpush