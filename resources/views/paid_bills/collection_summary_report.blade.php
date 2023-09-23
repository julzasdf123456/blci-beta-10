@php
    // GET PREVIOUS MONTHS
    for ($i = -1; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Collection Summary Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- FORM --}}
    <div class="col-lg-12">
        <div class="card shadow-none p-0">
            <div class="card-body px-4 py-1">
                <form action="{{ route('paidBills.collection-summary-report') }}" method="GET">
                    <div class="row">
                        <div class="form-group col-lg-2">
                            <label for="From">From</label>
                            <input type="text" class="form-control form-control-sm" id="From" name="From" placeholder="From" value="{{ isset($_GET['From']) ? $_GET['From'] : '' }}" required>
                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#From').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush
                        </div>

                        <div class="form-group col-lg-2">
                            <label for="To">To</label>
                            <input type="text" class="form-control form-control-sm" id="To" name="To" placeholder="To" value="{{ isset($_GET['To']) ? $_GET['To'] : '' }}" required> 
                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#To').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush
                        </div>

                        <div class="form-group col-lg-2">
                            <label for="Town">Town</label>
                            <select name="Town" id="Town" class="form-control form-control-sm">
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}" {{ isset($_GET['Town']) && $_GET['Town']==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-lg-2">
                            <label for="Action">Action</label>
                            <br>
                            <button class="btn btn-sm btn-primary" type="submit" id="filter-btn"><i class="fas fa-filter"></i>Filter</button>
                            <button class="btn btn-sm btn-warning" id="print"><i class="fas fa-print"></i> Print</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- RESULTS --}}
    <div class="col-lg-12">
        <div class="card shadow-none" style="height: 70vh;">
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" rowspan="2">DATE</th>
                            <th class="text-center" colspan="2">ARREARS</th>
                            <th class="text-center" colspan="2">PREVIOUS</th>
                            <th class="text-center" colspan="2">CURRENT</th>
                            <th class="text-center" rowspan="2">SURCHARGE/ <br>INTEREST</th>
                            <th class="text-center" rowspan="2">OTHERS</th>
                            <th class="text-center" rowspan="2">MISC.</th>
                            <th class="text-center" rowspan="2">TOTAL <br> COLLECTION</th>
                            <th class="text-center" rowspan="2">GENERATION<br>VAT</th>
                            <th class="text-center" rowspan="2">TRANSMISSION<br>VAT</th>
                            <th class="text-center" rowspan="2">SYSTEM<br>LOSS VAT</th>
                            <th class="text-center" rowspan="2">DISTRIBUTION<br>VAT</th>
                            <th class="text-center" rowspan="2">OTHERS<br>VAT</th>
                            <th class="text-center" rowspan="2">OTHER<br>INCOME VAT</th>
                            <th class="text-center" rowspan="2">5% EWT</th>
                            <th class="text-center" rowspan="2">2% EWT</th>
                            <th class="text-center" rowspan="2">RFSC</th>
                            <th class="text-center" rowspan="2">EC</th>
                            <th class="text-center" rowspan="2">ME</th>
                        </tr>
                        <tr>
                            <th class="text-center"># Bills</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center"># Bills</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center"># Bills</th>
                            <th class="text-center">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $arrearsCountTotal = 0;
                            $arrearsAmountTotal = 0;
                            $previousCountTotal = 0;
                            $previousAmountTotal = 0;
                            $currentCountTotal = 0;
                            $currentAmountTotal = 0;
                            $surchargeTotal = 0;
                            $miscTotal = 0;
                            $totalCol = 0;
                            $genVatTotal = 0;
                            $transVatTotal = 0;
                            $sysVatTotal = 0;
                            $distVatTotal = 0;
                            $miscVatTotal = 0;
                            $fiveTotal = 0;
                            $twoTotal = 0;
                            $rfscTotal = 0;
                            $ecTotal = 0;
                            $meTotal = 0;
                        @endphp
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ date('M d, Y', strtotime($item->ORDate)) }}</td>
                                <td class="text-right">{{ $item->ArrearsCount != null ? number_format($item->ArrearsCount) : '' }}</td>
                                <td class="text-right">{{ $item->ArrearsTotal != null ? number_format($item->ArrearsTotal, 2) : '' }}</td>
                                <td class="text-right">{{ $item->PreviousCount != null ? number_format($item->PreviousCount) : '' }}</td>
                                <td class="text-right">{{ $item->PreviousTotal != null ? number_format($item->PreviousTotal, 2) : '' }}</td>
                                <td class="text-right">{{ $item->CurrentCount != null ? number_format($item->CurrentCount) : '' }}</td>
                                <td class="text-right">{{ $item->CurrentTotal != null ? number_format($item->CurrentTotal, 2) : '' }}</td>
                                <td class="text-right">{{ $item->Surcharge != null ? number_format($item->Surcharge, 2) : '' }}</td>
                                <td></td>
                                <td class="text-right">{{ $item->Misc != null ? number_format($item->Misc, 2) : '' }}</td>
                                <td class="text-right">{{ number_format(floatval($item->Misc) + floatval($item->CurrentTotal) + floatval($item->PreviousTotal), 2) }}</td>
                                <td class="text-right">{{ $item->GenerationVat != null ? number_format($item->GenerationVat, 2) : '' }}</td>
                                <td class="text-right">{{ $item->TransmissionVat != null ? number_format($item->TransmissionVat, 2) : '' }}</td>
                                <td class="text-right">{{ $item->SystemLossVat != null ? number_format($item->SystemLossVat, 2) : '' }}</td>
                                <td class="text-right">{{ $item->DistributionVat != null ? number_format($item->DistributionVat, 2) : '' }}</td>
                                <td class="text-right">{{ $item->MiscVat != null ? number_format($item->MiscVat, 2) : '' }}</td>
                                <td></td>
                                <td class="text-right">{{ $item->FivePercent != null ? number_format($item->FivePercent, 2) : '' }}</td>
                                <td class="text-right">{{ $item->TwoPercent != null ? number_format($item->TwoPercent, 2) : '' }}</td>
                                <td class="text-right">{{ $item->RFSC != null ? number_format($item->RFSC, 2) : '' }}</td>
                                <td class="text-right">{{ $item->EnvironmentalCharge != null ? number_format($item->EnvironmentalCharge, 2) : '' }}</td>
                                <td class="text-right">{{ $item->MissionaryElectrification != null ? number_format($item->MissionaryElectrification, 2) : '' }}</td>
                            </tr>
                            @php
                                $arrearsCountTotal += floatval($item->ArrearsCount);
                                $arrearsAmountTotal += floatval($item->ArrearsTotal);
                                $previousCountTotal += floatval($item->PreviousCount);
                                $previousAmountTotal += floatval($item->PreviousTotal);
                                $currentCountTotal += floatval($item->CurrentCount);
                                $currentAmountTotal += floatval($item->CurrentTotal);
                                $surchargeTotal += floatval($item->Surcharge);
                                $miscTotal += floatval($item->Misc);
                                $totalCol += floatval($item->Misc) + floatval($item->CurrentTotal) + floatval($item->PreviousTotal);
                                $genVatTotal += floatval($item->GenerationVat);
                                $transVatTotal += floatval($item->TransmissionVat);
                                $sysVatTotal += floatval($item->SystemLossVat);
                                $distVatTotal += floatval($item->DistributionVat);
                                $miscVatTotal += floatval($item->MiscVat);
                                $fiveTotal += floatval($item->FivePercent);
                                $twoTotal += floatval($item->TwoPercent);
                                $rfscTotal += floatval($item->RFSC);
                                $ecTotal += floatval($item->EnvironmentalCharge);
                                $meTotal += floatval($item->MissionaryElectrification);
                            @endphp
                        @endforeach
                        <tr>
                            <th>Total</th>
                            <th class="text-right">{{ $arrearsCountTotal != null ? number_format($arrearsCountTotal) : '' }}</th>
                            <th class="text-right">{{ $arrearsAmountTotal != null ? number_format($arrearsAmountTotal, 2) : '' }}</th>
                            <th class="text-right">{{ $previousCountTotal != null ? number_format($previousCountTotal) : '' }}</th>
                            <th class="text-right">{{ $previousAmountTotal != null ? number_format($previousAmountTotal, 2) : '' }}</th>
                            <th class="text-right">{{ $currentCountTotal != null ? number_format($currentCountTotal) : '' }}</th>
                            <th class="text-right">{{ $currentAmountTotal != null ? number_format($currentAmountTotal, 2) : '' }}</th>
                            <th class="text-right">{{ $surchargeTotal != null ? number_format($surchargeTotal, 2) : '' }}</th>
                            <th></th>
                            <th class="text-right">{{ $miscTotal != null ? number_format($miscTotal, 2) : '' }}</th>
                            <th class="text-right">{{ $totalCol != null ? number_format($totalCol, 2) : '' }}</th>
                            <th class="text-right">{{ $genVatTotal != null ? number_format($genVatTotal, 2) : '' }}</th>
                            <th class="text-right">{{ $transVatTotal != null ? number_format($transVatTotal, 2) : '' }}</th>
                            <th class="text-right">{{ $sysVatTotal != null ? number_format($sysVatTotal, 2) : '' }}</th>
                            <th class="text-right">{{ $distVatTotal != null ? number_format($distVatTotal, 2) : '' }}</th>
                            <th class="text-right">{{ $miscVatTotal != null ? number_format($miscVatTotal, 2) : '' }}</th>
                            <th></th>
                            <th class="text-right">{{ $fiveTotal != null ? number_format($fiveTotal, 2) : '' }}</th>
                            <th class="text-right">{{ $twoTotal != null ? number_format($twoTotal, 2) : '' }}</th>
                            <th class="text-right">{{ $rfscTotal != null ? number_format($rfscTotal, 2) : '' }}</th>
                            <th class="text-right">{{ $ecTotal != null ? number_format($ecTotal, 2) : '' }}</th>
                            <th class="text-right">{{ $meTotal != null ? number_format($meTotal, 2) : '' }}</th>
                        </tr>
                    </tbody>
                </table>            
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#print').on('click', function(e) {
                e.preventDefault()
                if (jQuery.isEmptyObject($('#From').val()) | jQuery.isEmptyObject($('#To').val())) {
                    Swal.fire({
                        icon : 'warning',
                        text : 'Fill in the FROM and TO dates to print'
                    })
                } else {
                    window.location.href = "{{ url('/paid_bills/print-collection-summary-report') }}" + "/" + $('#From').val() + "/" + $('#To').val() + "/" + $('#Town').val()
                }
            })
        })
    </script>
@endpush