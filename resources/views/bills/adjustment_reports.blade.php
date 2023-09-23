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
                    <h4>Adjustment and Office Billing Reports</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none" style="height: 80vh">
                <div class="card-header">
                    {!! Form::open(['route' => 'bills.adjustment-reports', 'method' => 'GET']) !!}
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="">Type</label>
                            <select id="Type" name="Type" class="form-control form-control-sm">
                                <option value="All">All</option>
                                <option value="Direct Adjustments" {{ isset($_GET['Type']) && "Direct Adjustments"==$_GET['Type'] ? 'selected' : '' }}>Direct Adjustments</option>
                                <option value="DM CM" {{ isset($_GET['Type']) && "DM CM"==$_GET['Type'] ? 'selected' : '' }}>DM CM</option>
                                <option value="Application" {{ isset($_GET['Type']) && "Application"==$_GET['Type'] ? 'selected' : '' }}>Application</option>
                                <option value="Office Billing" {{ isset($_GET['Type']) && "Office Billing"==$_GET['Type'] ? 'selected' : '' }}>Office Billings</option>
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
                <div class="card-body p-0 table-responsive">
                    <table class="table table-sm table-hover table-bordered table-head-fixed text-nowrap">
                        <thead>
                            <th style="width: 50px;">#</th>
                            <th>Account No</th>
                            <th>Account Name</th>
                            <th>Addres </th>
                            <th class="text-right">Bill No</th>
                            <th class="text-right">Route</th>
                            <th class="text-right">Kwh Used</th>
                            <th class="text-right">Amount</th>
                            <th>Adjustment Type</th>
                            <th>Adjusted By</th>
                            <th>Date Adjusted</th>
                        </thead>
                        <tbody>
                            @php
                                $i=0;
                            @endphp
                            @foreach ($data as $item)
                                <tr>
                                    <td class="text-right">{{ $i+1 }}</td>
                                    <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->OldAccountNo }}</a></td>
                                    <td>{{ $item->ServiceAccountName }}</td>
                                    <td>{{ ServiceAccounts::getAddress($item) }}</td>
                                    <td class="text-right"><a href="{{ route('bills.show', [$item->id]) }}">{{ $item->BillNumber }}</a></td>
                                    <td class="text-right">{{ $item->AreaCode }}</td>
                                    <td class="text-right">{{ $item->KwhUsed }}</td>
                                    <th class="text-right text-danger">{{ number_format($item->NetAmount, 2) }}</th>
                                    <td>{{ $item->AdjustmentType }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ date('m/d/Y h:i:s a', strtotime($item->updated_at)) }}</td>
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
            $('#print-btn').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('bills/print-adjustment-report') }}" + "/" + encodeURIComponent($('#Type').val()) + "/" + $('#ServicePeriod').val()
            })
        })
    </script>
@endpush