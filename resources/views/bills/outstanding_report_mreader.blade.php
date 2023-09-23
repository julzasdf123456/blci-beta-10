@php
    // GET PREVIOUS MONTHS
    for ($i = -1; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }

    ini_set('memory_limit','4096M');
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Outstanding Accounts Per Meter Reader</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- FORM --}}
    <div class="col-lg-12">
        <div class="card shadow-none p-0">
            <div class="card-body px-4 py-1">
                <form id="form-submit" action="{{ route('bills.outstanding-report-mreader') }}" method="GET">
                    <div class="row">
                        <div class="form-group col-lg-2">
                            <label for="AsOf">As Of</label>
                            <input type="text" class="form-control form-control-sm" id="AsOf" name="AsOf" placeholder="Select date" value="{{ isset($_GET['AsOf']) ? $_GET['AsOf'] : date('Y-m-d') }}" required>
                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#AsOf').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush
                        </div>

                        <div class="form-group col-lg-2">
                            <label for="MeterReader">Meter Readers</label>
                            <select name="MeterReader" id="MeterReader" class="form-control form-control-sm">
                                <option value="All">All</option>
                                @foreach ($meterReaders as $item)
                                    <option value="{{ $item->id }}" {{ isset($_GET['MeterReader']) && $_GET['MeterReader']==$item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-lg-2">
                            <label for="Status">Account Status</label>
                            <select name="Status" id="Status" class="form-control form-control-sm">
                                <option value="All" {{ isset($_GET['Status']) && $_GET['Status']=='All' ? 'selected' : '' }}>ALL</option>
                                <option value="ACTIVE" {{ isset($_GET['Status']) && $_GET['Status']=='ACTIVE' ? 'selected' : '' }}>ACTIVE</option>
                                <option value="APPREHENDED" {{ isset($_GET['Status']) && $_GET['Status']=='APPREHENDED' ? 'selected' : '' }}>APPREHENDED</option>
                                <option value="DISCONNECTED" {{ isset($_GET['Status']) && $_GET['Status']=='DISCONNECTED' ? 'selected' : '' }}>DISCONNECTED</option>
                                <option value="PULLOUT" {{ isset($_GET['Status']) && $_GET['Status']=='PULLOUT' ? 'selected' : '' }}>PULLOUT</option>                                
                            </select>
                        </div>

                        <div class="form-group col-lg-2">
                            <label for="Action">Action</label>
                            <br>
                            <button class="btn btn-sm btn-primary" id="filter-btn"><i class="fas fa-filter"></i>Filter</button>
                            {{-- <button class="btn btn-sm btn-warning" id="print"><i class="fas fa-print"></i> Print</button> --}}
                            <button class="btn btn-sm btn-success" id="download" title="Download in Excel"><i class="fas fa-download"></i> Download</button>
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
                        <th style="width: 50px;"></th>
                        <th>Account No</th>
                        <th>Consumer Name</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Account Type</th>
                        <th>M. Reader</th>
                        <th>Billing Month</th>
                        <th class="text-right">Net Amount</th>
                        <th class="text-right">RPT</th>
                        <th class="text-right">Gen. VAT</th>
                        <th class="text-right">Trans. VAT</th>
                        <th class="text-right">Sys. Loss VAT</th>
                        <th class="text-right">Dist,/Others VAT</th>
                    </thead>
                    <tbody>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ $item->Purok }}</td>
                                <td>{{ $item->AccountStatus }}</td>
                                <td>{{ $item->AccountType }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                <td class="text-right">{{ $item->NetAmount != null && is_numeric($item->NetAmount) ? number_format($item->NetAmount, 2) : 0 }}</td>
                                <td class="text-right">{{ $item->RealPropertyTax != null && is_numeric($item->RealPropertyTax) ? number_format($item->RealPropertyTax, 2) : 0 }}</td>
                                <td class="text-right">{{ $item->GenerationVAT != null && is_numeric($item->GenerationVAT) ? number_format($item->GenerationVAT, 2) : 0 }}</td>
                                <td class="text-right">{{ $item->TransmissionVAT != null && is_numeric($item->TransmissionVAT) ? number_format($item->TransmissionVAT, 2) : 0 }}</td>
                                <td class="text-right">{{ $item->SystemLossVAT != null && is_numeric($item->SystemLossVAT) ? number_format($item->SystemLossVAT, 2) : 0 }}</td>
                                <td class="text-right">{{ $item->DistributionVAT != null && is_numeric($item->DistributionVAT) ? number_format($item->DistributionVAT, 2) : 0 }}</td>
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
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
            $('#filter-btn').on('click', function(e) {
                e.preventDefault()
                if ($('#Town').val() == 'All') {
                    Swal.fire({
                        icon : 'warning',
                        text : 'Due to the amount of data to be displayed, the "ALL" option in Town is only available in the download feature.',
                    })
                } else {
                    $('#form-submit').submit()
                }
            })

            $('#download').on('click', function(e) {
                e.preventDefault()
                if (jQuery.isEmptyObject($('#AsOf').val())) {
                    Swal.fire({
                        icon : 'warning',
                        text : 'Input As Of Date'
                    })
                } else {
                    window.location.href = "{{ url('/bills/download-outstanding-report-mreader') }}" + "/" + $('#AsOf').val() + "/" + $('#MeterReader').val() + "/" + $('#Status').val()
                }
            })
            // $('#print').on('click', function(e) {
            //     e.preventDefault()
            //     if (jQuery.isEmptyObject($('#From').val()) | jQuery.isEmptyObject($('#To').val())) {
            //         Swal.fire({
            //             icon : 'warning',
            //             text : 'Fill in the FROM and TO dates to print'
            //         })
            //     } else {
            //         window.location.href = "{{ url('/paid_bills/print-collection-summary-report') }}" + "/" + $('#From').val() + "/" + $('#To').val() + "/" + $('#Town').val()
            //     }
            // })
        })
    </script>
@endpush