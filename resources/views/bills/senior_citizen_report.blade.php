@php
    use App\Models\ServiceAccounts;

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
                    <h4>Senior Citizen Report</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        {{-- PARAMS --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body">
                    {!! Form::open(['route' => 'bills.senior-citizen-report', 'method' => 'GET']) !!}
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="">Town</label>
                            <select id="Town" name="Town" class="form-control form-control-sm">
                                <option value="All">All</option>
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}" {{ !isset($_GET['Town']) ? ($item->id==env('APP_AREA_CODE') ? 'selected' : '') : ($_GET['Town']==$item->id ? 'selected' : '') }}>{{ $item->Town }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Billing Month</label>
                            <select id="ServicePeriod" name="ServicePeriod" class="form-control form-control-sm">
                                @for ($i = 0; $i < count($months); $i++)
                                    <option value="{{ $months[$i] }}" {{ isset($_GET['ServicePeriod']) && $months[$i]==$_GET['ServicePeriod'] ? 'selected' : '' }}>{{ date('F Y', strtotime($months[$i])) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Action</label><br>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye ico-tab-mini"></i>View</button>
                            <button id="print-btn" class="btn btn-sm btn-warning"><i class="fas fa-print ico-tab-mini"></i>Print</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        {{-- RESULTS --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#summary" data-toggle="tab">
                            <i class="fas fa-list"></i>
                            Senior Citizen Summary</a></li>

                        <li class="nav-item"><a class="nav-link" href="#details" data-toggle="tab">
                            <i class="fas fa-user"></i>
                            Senior Citizen Details</a></li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content">
                        {{-- SUMMARY --}}
                        <div class="tab-pane active table-responsive p-0" id="summary" style="height: 60vh">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <th>Kwh Category</th>
                                    <th class="text-right">Number of Consumers</th>
                                    <th class="text-right">Total Kwh Consumed</th>
                                    <th class="text-right">Total Discount</th>
                                    <th class="text-right">Total Amount</th>          
                                </thead>
                                <tbody>
                                    @php
                                        $totalKwhUsed = 0;
                                        $totalCount = 0;
                                        $totalAmount = 0;
                                        $totalDsc = 0;
                                    @endphp
                                    @foreach ($summary as $item)
                                        <tr>
                                            <td>{{ number_format($item->KwhUsed) }} kWh</td>
                                            <td class="text-right">{{ $item->NoOfConsumers }}</td>
                                            <td class="text-right">{{ number_format($item->TotalKwhUsed) }}</td>
                                            <td class="text-right">{{ number_format($item->TotalDiscount) }}</td>
                                            <td class="text-right">{{ number_format($item->TotalAmount, 2) }}</td>
                                        </tr>
                                        @php
                                            $totalKwhUsed += floatval($item->TotalKwhUsed);
                                            $totalCount += floatval($item->NoOfConsumers);
                                            $totalAmount += floatval($item->TotalAmount);
                                            $totalDsc += floatval($item->TotalDiscount);
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <th>TOTAL</th>
                                        <th class="text-right">{{ $totalCount }}</th>
                                        <th class="text-right">{{ number_format($totalKwhUsed) }}</th>
                                        <th class="text-right">{{ number_format($totalDsc) }}</th>
                                        <th class="text-right">{{ number_format($totalAmount, 2) }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- DETAILS --}}
                        <div class="tab-pane table-responsive p-0" id="details" style="height: 60vh">
                            <table class="table table-sm table-hover table-bordered table-head-fixed text-nowrap">
                                <thead>
                                    <th style="width: 50px;">#</th>
                                    <th>Account No</th>
                                    <th>Account Name</th>
                                    <th>Addres </th>
                                    <th class="text-right">Bill No</th>
                                    <th class="text-right">Kwh Used</th>
                                    <th class="text-right">Discount</th>
                                    <th class="text-right">Amount</th>
                                </thead>
                                <tbody>
                                    @php
                                        $i=0;
                                    @endphp
                                    @foreach ($bills as $item)
                                        <tr>
                                            <td class="text-right">{{ $i+1 }}</td>
                                            <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></td>
                                            <td>{{ $item->ServiceAccountName }}</td>
                                            <td>{{ ServiceAccounts::getAddress($item) }}</td>
                                            <td class="text-right"><a href="{{ route('bills.show', [$item->id]) }}">{{ $item->BillNumber }}</a></td>
                                            <td class="text-right">{{ $item->KwhUsed }}</td>
                                            <th class="text-right text-success">{{ number_format($item->SeniorCitizenSubsidy, 2) }}</th>
                                            <th class="text-right text-danger">{{ number_format($item->NetAmount, 2) }}</th>
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
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#print-btn').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('bills/print-senior-citizen') }}" + "/" + $('#Town').val() + "/" + $('#ServicePeriod').val()
            })
        })
    </script>
@endpush