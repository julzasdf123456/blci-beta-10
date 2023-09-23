@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Third-Party API Payments Console</h4>
            </div>
            <div class="col-sm-6">
                <a class="btn btn-primary btn-sm float-right" style="margin-left: 10px;"
                    href="{{ route('paidBills.third-party-collection') }}">
                    <i class="fas fa-coins ico-tab"></i>Third Party Payments
                </a>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none" style="height: 80vh">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-check-circle ico-tab-mini"></i>Recent Third-Party API Transactions</span>

                <div class="card-tools">
                    <form action="" class="form-inline">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-sm mb-2" style="margin-right: 3px;" id="Day" name="Day" placeholder="Select Date" value="{{ isset($_GET['Day']) ? $_GET['Day'] : date('Y-m-d') }}">
                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#Day').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush
                        </div>
                        
                        <button type="submit" class="btn btn-sm btn-warning mb-2"><i class="fas fa-filter ico-tab-mini"></i>Filter</button>
                    </form>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover table-bordered">
                    <thead>
                        <th class="text-center">Collection POS</th>
                        <th class="text-center">Transaction Date</th>
                        <th class="text-center">Bills Payments</th>
                        <th class="text-center">Other Payments</th>
                    </thead>
                    <tbody>
                        @foreach ($transacted as $item)
                            <tr>
                                <td><a href="{{ route('paidBills.third-party-collection-api-dcr', [$item->ObjectSourceId, $date]) }}"><i class="fas fa-eye ico-tab"></i>{{ $item->ObjectSourceId }}</a></td>
                                <td>{{ isset($_GET['Day']) ? date('M d, Y', strtotime($_GET['Day'])) : date('M d, Y') }}</td>
                                <td class='text-right'>{{ $item->TotalPayments }} <span class="text-primary">({{ $item->TotalPaymentsSum != null ? number_format($item->TotalPaymentsSum, 2) : '0.0' }})</span></td>
                                <td class='text-right'>{{ $item->OthersCount }} <span class="text-primary">({{ $item->OthersSum != null ? number_format($item->OthersSum, 2) : '0.0' }})</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection