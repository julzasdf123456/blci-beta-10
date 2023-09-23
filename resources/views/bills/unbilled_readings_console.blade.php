@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Unbilled Readings Console - {{ date('F Y', strtotime($servicePeriod)) }}</h4>
                </div>

                <div class="col-sm-6">
                    <a href="{{ route('bills.unbilled-no-meter-readers') }}" class="btn btn-warning btn-sm float-right"><i class="fas fa-share ico-tab"> </i>Unbilled Accounts With No Meter Readers</a>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        @include('flash::message')

        <div class="clearfix"></div>
        
        <div class="row">
            {{-- PARAMS --}}
            <div class="col-lg-12 px-1">
                <form class="row" action="{{ route("bills.unbilled-readings-console", ['servicePeriod' => $servicePeriod]) }}" method="get">
                    <div class="form-group col-lg-3">
                        <label for="Area">Area</label>
                        <select name="Area" id="Area" class="form-control">
                            @foreach ($towns as $item)
                                <option value="{{ $item->id }}" {{ isset($_GET['Area']) && $_GET['Area']==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="MeterReader">Meter Reader</label>
                        <select class="custom-select select2"  name="MeterReader" id="MeterReader">
                            {{-- @foreach ($meterReaders as $items)
                                <option value="{{ $items->id }}" {{ isset($_GET['MeterReader']) ? ($_GET['MeterReader']==$items->id ? 'selected' : '') : '' }}>{{ $items->name }}</option>
                            @endforeach --}}
                        </select>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="Group">Group/Day</label>
                        <select name="GroupCode" class="form-control">
                            <option value="01" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='01' ? 'selected' : '') : '' }}>01</option>
                            <option value="02" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='02' ? 'selected' : '') : '' }}>02</option>
                            <option value="03" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='03' ? 'selected' : '') : '' }}>03</option>
                            <option value="04" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='04' ? 'selected' : '') : '' }}>04</option>
                            <option value="05" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='05' ? 'selected' : '') : '' }}>05</option>
                            <option value="06" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='06' ? 'selected' : '') : '' }}>06</option>
                            <option value="07" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='07' ? 'selected' : '') : '' }}>07</option>
                            <option value="08" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='08' ? 'selected' : '') : '' }}>08</option>
                            <option value="09" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='09' ? 'selected' : '') : '' }}>09</option>
                            <option value="10" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='10' ? 'selected' : '') : '' }}>10</option>
                            <option value="11" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='11' ? 'selected' : '') : '' }}>11</option>
                            <option value="12" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='12' ? 'selected' : '') : '' }}>12</option>
                            <option value="13" {{ isset($_GET['GroupCode']) ? ($_GET['GroupCode']=='13' ? 'selected' : '') : '' }}>13</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="Action">Action</label><br>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
            {{-- ZERO READINGS --}}
            <div class="col-lg-4">
                <div class="card" style="height: 70vh;">
                    <div class="card-header border-0">
                        <span class="card-title">Zero Readings ({{ $zeroReadings != null ? count($zeroReadings) : '0' }})</span>

                        <div class="card-tools">
                            <a href="#" class="btn btn-sm btn-primary">Average All</a>
                        </div>
                    </div>

                    <div class="card-body table-responsive px-0">
                        <table class="table table-sm table-hover">
                            <thead>
                                <th>Account No</th>
                                <th>Consumer Name</th>
                                <th>Status</th>
                                <th width="8%"></th>
                            </thead>
                            <tbody>
                                @if ($zeroReadings != null && count($zeroReadings) > 0)
                                    @foreach ($zeroReadings as $item)
                                        <tr>
                                            <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->AccountNumber }}</a></td>
                                            <td>{{ $item->ServiceAccountName }}</td>
                                            <td>{{ $item->FieldStatus }}</td>
                                            <td>
                                                <a href="{{ route('bills.zero-readings-view', [$item->id]) }}" class="btn btn-link btn-sm"><i class="fas fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- DISCONNECTED ACCOUNTS --}}
            <div class="col-lg-4">  
                <div class="card" style="height: 70vh;">
                    <div class="card-header border-0">
                        <span class="card-title">Disconnected Account Readings ({{ $disconnectedReadings != null ? count($disconnectedReadings) : '0' }})</span>
                    </div>

                    <div class="card-body table-responsive px-0">
                        <table class="table table-sm table-hover">
                            <thead>
                                <th>Account No</th>
                                <th>Consumer Name</th>
                                <th>Status</th>
                                <th>Kwh Used</th>
                            </thead>
                            <tbody>
                                @if ($disconnectedReadings != null && count($disconnectedReadings) > 0)
                                    @foreach ($disconnectedReadings as $item)
                                        <tr>
                                            <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->AccountNumber }}</a></td>
                                            <td>{{ $item->ServiceAccountName }}</td>
                                            <td>{{ $item->FieldStatus }}</td>
                                            <td>{{ $item->KwhUsed }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- CHANGE METERS --}}
            <div class="col-lg-4">
                <div class="card" style="height: 70vh">
                    <div class="card-header">
                        <span class="card-title">Change Meters ({{ $changeMeters != null ? count($changeMeters) : '0' }})</span>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <thead>
                                <th>Account No</th>
                                <th>Consumer Name</th>
                                {{-- <th>Status</th> --}}
                                <th>Kwh Used</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @if ($changeMeters != null && count($changeMeters) > 0)
                                    @foreach ($changeMeters as $item)
                                        <tr>
                                            <td><a href="{{ route('serviceAccounts.show', [$item->AccountNumber]) }}">{{ $item->AccountNumber }}</a></td>
                                            <td>{{ $item->ServiceAccountName }}</td>
                                            {{-- <td>{{ $item->FieldStatus }}</td> --}}
                                            <td>{{ $item->KwhUsed }}</td>
                                            <td class="text-right">
                                                <a href="{{ route('bills.change-meter-readings', [$item->AccountNumber, $item->ServicePeriod]) }}" class="btn btn-link btn-sm"><i class="fas fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<p id="MReaderHidden" style="display: none;">{{ isset($_GET['MeterReader']) ? $_GET['MeterReader'] : '' }}</p>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            fetchMeterReaders()

            $('#Area').on('change', function() {
                fetchMeterReaders()
            })
        })

        function fetchMeterReaders() {
            $.ajax({
                url : '{{ route("readings.get-meter-readers") }}',
                type : 'GET',
                data : {
                    Town : $('#Area').val(),
                },
                success : function(res) {
                    $('#MeterReader option').remove()
                    if (!jQuery.isEmptyObject(res)) {
                        $.each(res, function(index, element) {
                            if ($('#MReaderHidden').text() == res[index]["MeterReader"]) {
                                $('#MeterReader').append('<option value="' + res[index]["MeterReader"] + '" selected>' + res[index]["name"] + '</option>')
                            } else {
                                $('#MeterReader').append('<option value="' + res[index]["MeterReader"] + '">' + res[index]["name"] + '</option>')
                            }                            
                        })
                    }
                },
                error : function(err) {
                    Swal.fire({
                        title : 'Error fetching meter readers',
                        icon : 'error'
                    })
                }
            })
        }
    </script>
@endpush