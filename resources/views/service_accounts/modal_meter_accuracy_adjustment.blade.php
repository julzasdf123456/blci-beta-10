@php
    use App\Models\Bills;
@endphp

<div class="modal fade" id="modal-meter-accuracy" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="max-width: 90% !important; margin-top: 20px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Meter Accuracy Adjustment</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted no-pads">Input Kwh Reading Percentage Adjustment (in decimal)</p>
                <input id="percentage-adj" type="number" class="form-control form-control-sm text-right" step="any" style="width: 480px;" autofocus>

                <p class="text-muted">Select Starting Bill to Adjust</p>
                <table class="table table-sm table-hover table-bordered">
                    <thead>
                        <th>*</th>
                        <th>Billing Mo.</th>
                        <th class="text-center">Pres.<br>Read</th>
                        <th class="text-center" title="Multiplier">x*</th>
                        <th class="text-center">Total<br>Kwh</th>
                        <th class="text-center">Bill<br>Amount</th>
                        <th class="text-center">Paid<br>Amount</th>
                        <th class="text-center">Balance</th>
                        {{-- ADJUSTED --}}
                        <th class="text-center">Adj.<br>Read</th>
                        <th class="text-center">Adj.<br>Kwh</th>
                        <th class="text-center">Adj.<br>Amount</th>
                        <th class="text-center">Adj.<br>Balance</th>
                        <th></th>
                    </thead>
                    <tbody id="bills-list">
                        @foreach ($bills as $item)
                            <tr id="maa-{{ $item->id }}" title="{{ $item->AdjustmentType=='Application' ? 'Application Adjustment' : '' }}" id="{{ $item->id }}" fivePercent="{{ Bills::getFivePercent($item) }}" twoPercent="{{ Bills::getTwoPercent($item) }}">
                                <td>
                                    <i id="icon-{{ $item->id }}" 
                                        data-id="{{ $item->id }}" 
                                        data-reading="{{ $item->PresentKwh }}"
                                        onclick="selectInit(`{{ $item->id }}`)" 
                                        class="fas fa-check-circle text-muted"></i>
                                </td>
                                <td class="{{ $item->PaidBillId != null ? 'text-success' : 'text-danger' }}">{{ date('M Y', strtotime($item->ServicePeriod)) }}</td>
                                <td class="text-right text-muted">{{ $item->PresentKwh }}</td>
                                <td class="text-right text-muted"><strong>{{ $item->Multiplier }}</strong></td>
                                <th class="text-right text-muted">{{ $item->KwhUsed }}</th>
                                {{-- <td class="text-right">{{ $item->EffectiveRate != null ? number_format($item->EffectiveRate, 4) : '0' }}</td> --}}
                                <th class="text-right text-muted">P {{ $item->NetAmount != null ? (is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : '0') : '0' }}</th>
                                <th class="text-right text-muted">P {{ $item->PaidAmount != null ? (is_numeric($item->PaidAmount) ? number_format($item->PaidAmount, 2) : '0') : '0' }}</th>
                                <th class="text-right text-danger">P {{ $item->Balance != null ? (is_numeric($item->Balance) ? number_format($item->Balance, 2) : '0') : '0' }}</th>
                                <th class="text-success" id="adj-reading-{{ $item->id }}"></th>
                                <th class="text-success"></th>
                                <th class="text-success"></th>
                                <th class="text-success"></th>
                                <td class="text-right">
                                    <i class="fas fa-minus-circle text-danger" onclick="excempt(`{{ $item->id }}`)" title="Excempt this bill (removes this bill from the selection)"></i>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button class="btn btn-primary float-right"><i class="fas fa-check-circle ico-tab"></i>Save</button>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
    <script>
        var selection = []

        var initReading = 0

        function selectInit(id) {
            if (jQuery.isEmptyObject($('#percentage-adj').val())) {
                Toast.fire({
                    icon : 'warning',
                    text : 'Provide percentage first!'
                })
            } else {
                selection = []
                initReading = parseFloat($("#icon-" + id).attr("data-reading"))
                // REMOVE SELECTED FIRST
                $("#bills-list tr").each(function() {
                    $(this).find("i").removeClass('text-success').addClass('text-muted')
                });

                $("#bills-list tr").each(function() {
                    var idBase = $(this).find("i").attr("data-id")

                    // CHANGE COLOR ON SELECTED
                    $(this).find("i").removeClass('text-muted').addClass('text-success')

                    // ADD TO SELECTION
                    selection.push(idBase)
                    
                    if (id == idBase) {
                        // SELECTION STOPS ON SELECTED ID
                        return false
                    }
                });

                fetchData()
            }
        }

        function fetchData() {
            selection.reverse()
            var percentage = parseFloat($('#percentage-adj').val())
            var readingFlow = initReading
            var percentAdjustment = 0

            for (var i=0; i<selection.length; i++) {
                if (i <= 0) {
                    $('#adj-reading-' + selection[i]).text(readingFlow)
                    readingFlow = initReading
                    percentAdjustment = readingFlow * percentage
                } else {
                    // CALCULATE PERCENTAGE
                    readingFlow += percentAdjustment
                    readingFlow = Math.round((readingFlow + Number.EPSILON) * 100) / 100

                    $('#adj-reading-' + selection[i]).text(readingFlow)
                }                            
            }
        }

        function excempt(id) {
            $('#maa-' + id).remove()
            $("#bills-list tr").each(function() {
                $(this).find("i").removeClass('text-success').addClass('text-muted')
            });
            selection = []
            Toast.fire({
                icon : 'success',
                text : 'Bill removed from selection'
            })
        }
    </script>
@endpush