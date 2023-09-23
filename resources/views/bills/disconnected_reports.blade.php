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
                <h4>Disconnected Accounts with Reconnection Payments</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- FORM --}}
    <div class="col-lg-12">
        <div class="card shadow-none p-0">
            <div class="card-body px-4 py-1">
                <form id="form-submit" action="{{ route('bills.disconnected-reports') }}" method="GET">
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
                            {{-- <button class="btn btn-sm btn-success" id="download" title="Download in Excel"><i class="fas fa-download"></i> Download</button> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- RESULT --}}
    <div class="col-lg-12">
        <div class="card shadow-none" style="height: 70vh;">
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-bordered table-hover">
                    <thead>
                        <th>Account No</th>
                        <th>Consumer Name</th>
                        <th>Consumer Address</th>
                        <th>Account Type</th>
                        <th>Account Status</th>
                        <th>Reconnection Paid On</th>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></td>
                                <td>{{ $item->ServiceAccountName }}</td>
                                <td>{{ ServiceAccounts::getAddress($item) }}</td>
                                <td>{{ $item->AccountType }}</td>
                                <td>{{ $item->AccountStatus }}</td>
                                <td>{{ date('F d, Y', strtotime($item->ORDate)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection