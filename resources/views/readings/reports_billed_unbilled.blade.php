@php
    use App\Models\ServiceAccounts;
    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }

    // GROUPS/DAY
    $groups = [
        '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'
    ];
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Billed and Unbilled Reports</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        {{-- FORM --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body">
                    {!! Form::open(['route' => 'readings.billed-and-unbilled-reports', 'method' => 'GET']) !!}
                    <div class="row">
                        <div class="form-group col-lg-1">
                            <label for="Office">Office</label>
                            <select name="Office" id="Office" class="form-control form-control-sm">
                                <option value="All">All</option>
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}" {{ isset($_GET['Office']) && $_GET['Office']==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                                @endforeach
                            </select>
                            {{-- <input type="text" value="{{ isset($_GET['Office']) ? $_GET['Office'] : env('APP_AREA_CODE') }}" class="form-control form-control-sm" id="Office" name="Office"> --}}
                        </div>
                        <div class="form-group col-lg-1">
                            <label for="">Type</label>
                            <select name="Type" id="Type" class="form-control form-control-sm">
                                <option value="Billed" {{ isset($_GET['Type']) && $_GET['Type']=='Billed' ? 'selected' : '' }}>Billed</option>
                                <option value="Unbilled" {{ isset($_GET['Type']) && $_GET['Type']=='Unbilled' ? 'selected' : '' }}>Unbilled</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="ServicePeriod">Billing Month</label>
                            <select name="ServicePeriod" id="ServicePeriod" class="form-control form-control-sm">
                                @for ($i = 0; $i < count($months); $i++)
                                    <option value="{{ $months[$i] }}" {{ isset($_GET['ServicePeriod']) && $_GET['ServicePeriod']==$months[$i] ? 'selected' : '' }}>{{ date('F Y', strtotime($months[$i])) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="MeterReader">Meter Reader</label>
                            <select name="MeterReader" id="MeterReader" class="form-control form-control-sm">
                                <option value="All">All</option>
                                @foreach ($meterReaders as $item)
                                    <option value="{{ $item->id }}" {{ isset($_GET['MeterReader']) && $_GET['MeterReader']==$item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-1">
                            <label for="">Day</label>
                            <select name="Day" id="Day" class="form-control form-control-sm">
                                <option value="All">All</option>
                                @for ($i = 0; $i < count($groups); $i++)
                                    <option value="{{ $groups[$i] }}" {{ isset($_GET['Day']) && $_GET['Day']==$groups[$i] ? 'selected' : '' }}>{{ $groups[$i] }}</option>
                                @endfor
                            </select>
                        </div><div class="form-group col-lg-1">
                            <label for="">Account Status</label>
                            <select name="AccountStatus" id="AccountStatus" class="form-control form-control-sm">
                                <option value="All">All</option>
                                @foreach ($acctStatus as $item)
                                    <option value="{{ $item->AccountStatus }}" {{ isset($_GET['AccountStatus']) && $_GET['AccountStatus']==$item->AccountStatus ? 'selected' : '' }}>{{ $item->AccountStatus }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Action</label><br>
                            {!! Form::submit('View', ['class' => 'btn btn-primary btn-sm']) !!}
                            <button class="btn btn-sm btn-warning" id="printBtnReport">Print</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card shadow-none" style="height: 70vh">
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-bordered table-hover table-head-fixed text-nowrap">
                        <thead>
                            <th style="width: 20px;">#</th>
                            <th>Account No</th>
                            <th>Consumer Name</th>
                            <th>Address</th>
                            <th class="text-right">Meter No</th>
                            <th class="text-center">Acct.<br>Status</th>
                            <th class="text-right">Sequence No</th>
                            <th class="text-right">Route</th>
                            <th class="text-right">Reading</th>
                            <th class="text-right">Kwh Used</th>
                            <th class="text-center">Reading <br> Timestamp</th>
                            <th>Field Status</th>
                            <th>Remarks</th>
                        </thead>
                        <tbody>
                            @php
                                $i=0;
                            @endphp
                            @foreach ($readingReport as $item)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td><a href="{{ $item->OldAccountNo != null ? route('serviceAccounts.show', [$item->AccountId]) : '' }}">{{ $item->OldAccountNo }}</a></td>
                                    <td>{{ $item->ServiceAccountName }}</td>
                                    <td>{{ ServiceAccounts::getAddress($item) }}</td>
                                    <td>{{ $item->MeterNumber }}</td>
                                    <td>{{ $item->AccountStatus }}</td>
                                    <td class="text-right">{{ $item->SequenceCode }}</td>
                                    <td class="text-right">{{ $item->AreaCode }}</td>
                                    <td class="text-right">{{ $item->Reading }}</td>
                                    <td class="text-right">{{ $item->CurrentKwh }}</td>
                                    <td>{{ $item->ReadingTimestamp != null ? date('M d, Y h:i:s A', strtotime($item->ReadingTimestamp)) : "" }}</td>
                                    <td>{{ $item->FieldStatus }}</td>
                                    <td>{{ $item->Notes }}</td>
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
            $('#printBtnReport').on('click', function(e) {
                e.preventDefault()
                window.location.href = "{{ url('/readings/print-billed-unbilled') }}" + "/" + $('#Type').val() + "/" + $('#MeterReader').val() + "/" + $('#Day').val() + "/" + $('#ServicePeriod').val() + "/" + $('#Office').val() + "/" + $('#AccountStatus').val()
            })
        })
    </script>
@endpush