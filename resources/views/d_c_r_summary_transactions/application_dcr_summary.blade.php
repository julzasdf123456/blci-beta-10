@extends('layouts.app')

@section('content')
<p style="padding-top: 8px;"><i class="fas fa-chart-line ico-tab"></i>Application Payments DCR Summary</p>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none p-0">
            <div class="card-body px-4 py-1">
                <form action="{{ route('dCRSummaryTransactions.application-dcr-summary') }}" method="GET">
                    <div class="row">
                        <div class="form-group col-lg-2">
                            <label for="From">From</label>
                            <input type="text" class="form-control form-control-sm" id="From" name="From" placeholder="From" value="{{ isset($_GET['From']) ? $_GET['From'] : '' }}">
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
                            <input type="text" class="form-control form-control-sm" id="To" name="To" placeholder="To" value="{{ isset($_GET['To']) ? $_GET['To'] : '' }}">
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
                            <label for="Office">Office</label>
                            <select name="Office" id="Office" class="form-control form-control-sm">
                                <option value="All">All</option>
                                @foreach ($offices as $item)
                                    <option value="{{ $item->Office }}" {{ isset($_GET['Office']) && $_GET['Office']==$item->Office ? 'selected' : '' }}>{{ $item->Office }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-lg-2">
                            <label for="Action">Action</label>
                            <br>
                            <button class="btn btn-sm btn-warning" type="submit" id="filter-btn"><i class="fas fa-filter"></i>Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- RESULTS --}}
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#dcr-summary" data-toggle="tab">
                        <i class="fas fa-list"></i>
                        Application DCR Summary</a></li>

                    <li class="nav-item"><a class="nav-link" href="#power-bills" data-toggle="tab">
                        <i class="fas fa-user"></i>
                        Power Bills Application Payments</a></li>
                </ul>
            </div>
            <div class="card-body p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="dcr-summary">
                        @include('d_c_r_summary_transactions.dcr_summary')
                    </div>

                    <div class="tab-pane" id="power-bills">
                        @include('d_c_r_summary_transactions.power_bills')
                    </div>
                </div>                    
            </div>
        </div>
    </div>
</div>
@endsection