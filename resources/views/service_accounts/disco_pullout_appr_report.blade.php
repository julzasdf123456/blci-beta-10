@php
    use App\Models\ServiceAccounts;
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
                <h4>Disconnected, Reconnected, Pullout, and Apprehended Accounts Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- FORM --}}
    <div class="col-lg-12">
        <div class="card shadow-none p-0">
            <div class="card-body px-4 py-1">
                <form id="form-submit" action="{{ route('serviceAccounts.disco-pullout-appr') }}" method="GET">
                    <div class="row">
                        <div class="form-group col-lg-2">
                            <label for="From">From</label>
                            <input type="text" class="form-control form-control-sm" id="From" name="From" placeholder="Select date" value="{{ isset($_GET['From']) ? $_GET['From'] : date('Y-m-d') }}" required>
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
                            <input type="text" class="form-control form-control-sm" id="To" name="To" placeholder="Select date" value="{{ isset($_GET['To']) ? $_GET['To'] : date('Y-m-d') }}" required>
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
                            <label for="ServicePeriod">Billing Month</label>
                            <select name="ServicePeriod" id="ServicePeriod" class="form-control form-control-sm">
                                @foreach ($periods as $item)
                                    <option value="{{ $item->ServicePeriod }}">{{ date('F Y', strtotime($item->ServicePeriod)) }}</option>
                                @endforeach
                            </select>                            
                        </div>

                        <div class="form-group col-lg-2">
                            <label for="Status">Status</label>
                            <select name="Status" id="Status" class="form-control form-control-sm">
                                <option value="DISCONNECTED" {{ isset($_GET['Status']) && $_GET['Status']=='DISCONNECTED' ? 'selected' : '' }}>DISCONNECTED</option>
                                <option value="RECONNECTED" {{ isset($_GET['Status']) && $_GET['Status']=='RECONNECTED' ? 'selected' : '' }}>RECONNECTED</option>
                                <option value="APPREHENDED" {{ isset($_GET['Status']) && $_GET['Status']=='APPREHENDED' ? 'selected' : '' }}>APPREHENDED</option>
                                <option value="PULLOUT" {{ isset($_GET['Status']) && $_GET['Status']=='PULLOUT' ? 'selected' : '' }}>PULLOUT</option>
                            </select>
                        </div>

                        <div class="form-group col-lg-2">
                            <label for="Town">Town</label>
                            <select name="Town" id="Town" class="form-control form-control-sm">
                                <option value="All">All</option>
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}" {{ isset($_GET['Town']) && $_GET['Town']==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                                @endforeach
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
        <div class="card" style="height: 70vh;">
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover table-bordered">
                    <thead>
                        <th>Account No</th>
                        <th>Consumer Name</th>
                        <th>Consumer Address</th>
                        <th>Account Type</th>
                        <th>Status</th>
                        <th>Date Disco/Reco/PO/Appr</th>
                        <th>Bill Amnt. for<br>this Billing Month</th>
                        <th>Arrears</th>
                        <th>Notes/Remarks</th>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ ServiceAccounts::getAddress($item) }}</td>
                                <td>{{ $item->AccountType }}</td>
                                <td>{{ $item->AccountStatus }}</td>
                                <td>{{ date('M d, Y', strtotime($item->DateDisconnected)) }}, {{ date('h:i:s A', strtotime($item->TimeDisconnected)) }}</td>
                                <td class="text-right">{{ is_numeric($item->BillAmount) ? number_format($item->BillAmount, 2) : $item->BillAmount }}</td>
                                <td class="text-right">{{ is_numeric($item->Arrears) ? number_format($item->Arrears, 2) : $item->Arrears }}</td>
                                <td>{{ $item->Notes }}</td>
                            </tr>
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
            $('#download').on('click', function(e) {
                e.preventDefault()
                if (jQuery.isEmptyObject($('#From').val()) || jQuery.isEmptyObject($('#To').val())) {
                    Swal.fire({
                        icon : 'warning',
                        text : 'Input From and To Dates'
                    })
                } else {
                    window.location.href = "{{ url('/service_accounts/download-disco-pullout-appr') }}" + "/" + $('#From').val()  + "/" + $('#To').val() + "/" + $('#Town').val()  + "/" + $('#Status').val() + "/" + $('#ServicePeriod').val()
                }
            })
        })
    </script>
@endpush