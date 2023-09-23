@php
    
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Aging of Outstanding Accounts Report</h4>
            </div>
            <div class="col-sm-6">
                <form action="{{ route('paidBills.aging-report') }}" method="GET">
                    <button class="btn btn-sm btn-warning float-right" id="print" style="margin-left: 8px;"><i class="fas fa-print"></i> Print</button>
                    <button class="btn btn-sm btn-primary float-right" type="submit" id="filter-btn" style="margin-left: 8px;"><i class="fas fa-filter"></i>Filter</button>
                    <select name="Town" id="Town" class="form-control form-control-sm float-right" style="width: 180px; margin-left: 8px;">
                        @foreach ($towns as $item)
                            <option value="{{ $item->id }}" {{ isset($_GET['Town']) && $_GET['Town']==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                        @endforeach
                    </select>
                    <label for="Town" class="float-right">Town</label>
                    <input type="text" id="AsOf" required="true" name="AsOf" class="form-control form-control-sm float-right" style="width: 180px; margin-left: 8px; margin-right: 5px;" value="{{ isset($_GET['AsOf']) ? $_GET['AsOf'] : date('Y-m-d') }}">
                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#AsOf').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush
                    <label for="AsOf" class="float-right">As Of</label>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="card shadow-none" style="height: 80vh;">
    <div class="card-body table-responsive p-0">
        <table class="table table-sm table-bordered table-hover">
            <thead>
                <tr>
                    <th class='text-center' rowspan="2">ROUTE</th>
                    <th class='text-center' colspan="3">CURRENT - 90 DAYS</th>
                    <th class='text-center' colspan="3">91 - 180 DAYS</th>
                    <th class='text-center' colspan="3">181 - 240 DAYS</th>
                    <th class='text-center' colspan="3">241 - 360 DAYS</th>
                    <th class='text-center' colspan="3">OVER 360 DAYS</th>
                    <th class='text-center' colspan="3">BOOKS TOTAL</th>
                    <th class='text-center' rowspan="2">TOTAL CONS</th>
                </tr>
                <tr>
                    <th class='text-center'>CONS</th>
                    <th class='text-center'>BILLS</th>
                    <th class='text-center'>TOTAL AMNT</th>
                    <th class='text-center'>CONS</th>
                    <th class='text-center'>BILLS</th>
                    <th class='text-center'>TOTAL AMNT</th>
                    <th class='text-center'>CONS</th>
                    <th class='text-center'>BILLS</th>
                    <th class='text-center'>TOTAL AMNT</th>
                    <th class='text-center'>CONS</th>
                    <th class='text-center'>BILLS</th>
                    <th class='text-center'>TOTAL AMNT</th>
                    <th class='text-center'>CONS</th>
                    <th class='text-center'>BILLS</th>
                    <th class='text-center'>TOTAL AMNT</th>
                    <th class='text-center'>CONS</th>
                    <th class='text-center'>BILLS</th>
                    <th class='text-center'>TOTAL AMNT</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $consCount90 = 0;
                    $billsCount90 = 0;
                    $billsAmount90 = 0;
                    $consCount180 = 0;
                    $billsCount180 = 0;
                    $billsAmount180 = 0;
                    $consCount240 = 0;
                    $billsCount240 = 0;
                    $billsAmount240 = 0;
                    $consCount360 = 0;
                    $billsCount360 = 0;
                    $billsAmount360 = 0;
                    $consCountOver360 = 0;
                    $billsCountOver360 = 0;
                    $billsAmountOver360 = 0;
                    $consCountBooksTotal = 0;
                    $billsCountBooksTotal = 0;
                    $billsAmountBooksTotal = 0;
                    $totalCons = 0;
                @endphp
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->Town }}-{{ $item->AreaCode }}</td>
                        <td class="text-right">{{ $item->ConsCountNinetyDays }}</td>
                        <td class="text-right">{{ $item->BillsCountNinetyDays }}</td>
                        <td class="text-right">{{ number_format($item->BillsAmountNinetyDays, 2) }}</td>
                        <td class="text-right">{{ $item->ConsCount180Days }}</td>
                        <td class="text-right">{{ $item->BillsCount180Days }}</td>
                        <td class="text-right">{{ number_format($item->BillsAmount180Days, 2) }}</td>
                        <td class="text-right">{{ $item->ConsCount240Days }}</td>
                        <td class="text-right">{{ $item->BillsCount240Days }}</td>
                        <td class="text-right">{{ number_format($item->BillsAmount240Days, 2) }}</td>
                        <td class="text-right">{{ $item->ConsCount360Days }}</td>
                        <td class="text-right">{{ $item->BillsCount360Days }}</td>
                        <td class="text-right">{{ number_format($item->BillsAmount360Days, 2) }}</td>
                        <td class="text-right">{{ $item->ConsCountOver360Days }}</td>
                        <td class="text-right">{{ $item->BillsCountOver360Days }}</td>
                        <td class="text-right">{{ number_format($item->BillsAmountOver360Days, 2) }}</td>
                        <td class="text-right">{{ $item->ConsCountBooksTotal }}</td>
                        <td class="text-right">{{ $item->BillsCountBooksTotal }}</td>
                        <td class="text-right">{{ number_format($item->BillsAmountBooksTotal, 2) }}</td>
                        <td class="text-right">{{ $item->TotalCons }}</td>
                    </tr>
                    @php
                        $consCount90 += floatval($item->ConsCountNinetyDays);
                        $billsCount90 += floatval($item->BillsCountNinetyDays);
                        $billsAmount90 += floatval($item->BillsAmountNinetyDays);
                        $consCount180 += floatval($item->ConsCount180Days);
                        $billsCount180 += floatval($item->BillsCount180Days);
                        $billsAmount180 += floatval($item->BillsAmount180Days);
                        $consCount240 += floatval($item->ConsCount240Days);
                        $billsCount240 += floatval($item->BillsCount240Days);
                        $billsAmount240 += floatval($item->BillsAmount240Days);
                        $consCount360 += floatval($item->ConsCount360Days);
                        $billsCount360 += floatval($item->BillsCount360Days);
                        $billsAmount360 += floatval($item->BillsAmount360Days);
                        $consCountOver360 += floatval($item->ConsCountOver360Days);
                        $billsCountOver360 += floatval($item->BillsCountOver360Days);
                        $billsAmountOver360 += floatval($item->BillsAmountOver360Days);
                        $consCountBooksTotal += floatval($item->ConsCountBooksTotal);
                        $billsCountBooksTotal += floatval($item->BillsCountBooksTotal);
                        $billsAmountBooksTotal += floatval($item->BillsAmountBooksTotal);
                        $totalCons += floatval($item->TotalCons);
                    @endphp
                @endforeach
                <tr>
                    <th>TOTAL</th>
                    <th class="text-right">{{ $consCount90 }}</th>
                    <th class="text-right">{{ $billsCount90 }}</th>
                    <th class="text-right">{{ number_format($billsAmount90, 2) }}</th>
                    <th class="text-right">{{ $consCount180 }}</th>
                    <th class="text-right">{{ $billsCount180 }}</th>
                    <th class="text-right">{{ number_format($billsAmount180, 2) }}</th>
                    <th class="text-right">{{ $consCount240 }}</th>
                    <th class="text-right">{{ $billsCount240 }}</th>
                    <th class="text-right">{{ number_format($billsAmount240, 2) }}</th>
                    <th class="text-right">{{ $consCount360 }}</th>
                    <th class="text-right">{{ $billsCount360 }}</th>
                    <th class="text-right">{{ number_format($billsAmount360, 2) }}</th>
                    <th class="text-right">{{ $consCountOver360 }}</th>
                    <th class="text-right">{{ $billsCountOver360 }}</th>
                    <th class="text-right">{{ number_format($billsAmountOver360, 2) }}</th>
                    <th class="text-right">{{ $consCountBooksTotal }}</th>
                    <th class="text-right">{{ $billsCountBooksTotal }}</th>
                    <th class="text-right">{{ number_format($billsAmountBooksTotal, 2) }}</th>
                    <th class="text-right">{{ $totalCons }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#print').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/paid_bills/print-aging-report') }}" + "/" + $('#Town').val()  + "/" + $('#AsOf').val()
            })
        })
    </script>
@endpush