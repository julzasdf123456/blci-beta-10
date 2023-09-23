@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Auth;
    use App\Models\ServiceAccounts;
    use App\Models\Users;
    use App\Models\IDGenerator;

    $files = Storage::disk('public')->allFiles('/documents/' . $reading->AccountNumber . '/images');
@endphp

@push('page_css')
    <style>
        .image-box {
            display: inline-block;
        }

        .images-application {
            width: 48%;
            display: inline;
            margin: 2px;
        }
    </style>
@endpush

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Zero Readings Console</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            <div class="col-lg-5">
                {{-- REEADING DETAILS --}}
                <div class="card">
                    <div class="card-header border-0">
                        <span class="card-title">Reading Details</span>

                        <div class="card-tools">
                            {{-- <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-sa"><i class="fas fa-shield-alt ico-tab-mini"></i>Adjust Reading</button> --}}
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-confirm">Average Bill</button>
                        </div>
                    </div>

                    <div class="card-body table-responsive px-0">
                        <table class="table table-sm">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <td>Account Number</td>
                                    <th><a href="{{ route('serviceAccounts.show', [$reading->AccountNumber]) }}">{{ $account->OldAccountNo }}</a></th>
                                </tr>
                                <tr>
                                    <td>Consumer Name</td>
                                    <th>{{ $account->ServiceAccountName }}</th>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <th>{{ ServiceAccounts::getAddress($account) }}</th>
                                </tr>
                                <tr>
                                    <td>Consumer Type</td>
                                    <th>{{ $account->AccountType }}</th>
                                </tr>
                                <tr>
                                    <td>Area</td>
                                    <th>{{ $account->AreaCode }}</th>
                                </tr>
                                <tr>
                                    <td>Meter Number</td>
                                    <th>{{ $meterInfo != null ? $meterInfo->SerialNumber : 'not indicated' }}</th>
                                </tr>
                                <tr>
                                    <td>Meter Reader</td>
                                    <th>{{ $reading->MeterReader != null ? Users::find($reading->MeterReader)->name : 'not indicated' }}</th>
                                </tr>
                                <tr>
                                    <td>Meter Status</td>
                                    <th>{{ $reading->FieldStatus }}</th>
                                </tr>
                                <tr>
                                    <td>Field Notes/Remarks</td>
                                    <th>{{ $reading->Notes }}</th>
                                </tr>
                                <tr>
                                    <td>Last Recorded Kwh Used</td>
                                    <th>{{ number_format($reading->KwhUsed, 2) }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <p>Adjust Reading</p>

                        <div class="row">
                            <div class="form-group col-lg-6">
                                <input type="number" step="any" class="form-control" id="KwhUsed" placeholder="Kwh Used" value="{{ $pendingAdjustments != null ? $pendingAdjustments->KwhUsed : '' }}">
                            </div>

                            <div class="col-lg-6">
                                <button class="btn btn-primary" id="adjustBtn">Adjust</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PREVIOUS 3 READINGS --}}
                <div class="card">
                    <div class="card-header border-0">
                        <span class="card-title">Previous Bills (latest 3 months)</span>
                    </div>
                    <div class="card-body table-responsive px-0">
                        <table class="table table-sm">
                            <thead>
                                <th>Billing Month</th>
                                <th class="text-right">Kwh Used</th>
                                <th class="text-right">Rate</th>
                                <th class="text-right">Net Amount</th>
                            </thead>
                            <tbody>
                                @if (count($previousBills) > 0)
                                    @foreach ($previousBills as $item)
                                        <tr>
                                            <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                            <td class="text-right">{{ $item->KwhUsed }}</td>
                                            <td class="text-right">{{ number_format($item->EffectiveRate, 4) }}</td>
                                            <td class="text-right">{{ number_format($item->NetAmount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Field Images</span>
                    </div>
                    <div class="card-body">
                        <div class="image-box">
                            @if (count($images) > 0)
                                @foreach ($images as $item)
                                    <img class="images-application" src="{{ url('/storage/documents/' . $item->AccountNumber . '/images/' . $item->Photo) }}" alt="{{ $item->Photo }}">
                                @endforeach        
                            @else
                                <p class="center-text">No images recorded</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CONFIRMATION MODAL --}}
    <div class="modal fade" id="modal-confirm" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to average this reading? Averaging will be based on this consumer's previous three (3) billings.</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a href="{{ route('bills.average-bill', [$reading->id]) }}" class="btn btn-primary">Proceed</a>
                </div>
            </div>
        </div>
    </div>

    {{-- SUPERADMIN OR SUPERVISOR PASSWORD MODAL --}}
    <div class="modal fade" id="modal-sa" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Admin Password Required</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-sm-12">
                        <label for="Username">Username:</label>
                        <input type="text" id="Username" name="Username" class="form-control"/>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="Password">Password:</label>
                        <input type="password" id="Password" name="Password" class="form-control"/>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="authenticate-btn">Proceed</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $('#authenticate-btn').on('click', function() {
            $.ajax({
                url : '/users/authenticate',
                type : 'POST',
                data : {
                    _token : "{{ csrf_token() }}",
                    username : $('#Username').val(),
                    permission : 'billing re-bill',
                    password : $('#Password').val(),
                },
                success : function(res) {
                    // REDIRECT TO UPDATE BILLING
                    window.location.href = "{{ route('bills.rebill-reading-adjustment', [$reading->id]) }}"
                },
                error : function(error) {
                    console.log(error)
                    alert('Password authentication failed')
                }
            })
        })

        $(document).ready(function() {
            $('#adjustBtn').on('click', function() {
                $.ajax({
                    url : '{{ route("pendingBillAdjustments.store") }}',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : "{{ IDGenerator::generateIDandRandString() }}",
                        ReadingId : "{{ $reading->id }}",
                        KwhUsed : $('#KwhUsed').val(),
                        AccountNumber : "{{ $reading->AccountNumber }}",
                        ServicePeriod : "{{ $reading->ServicePeriod }}",
                        ReadDate : "{{ date('Y-m-d', strtotime($reading->ReadingTimestamp)) }}",
                        UserId : "{{ Auth::id() }}",
                        Office : "{{ env('APP_LOCATION') }}",
                    },
                    success : function(res) {
                        window.location.href = "{{ url('/bills/unbilled-readings-console') }}" + "/" + "{{ $reading->ServicePeriod }}"
                    },
                    error : function(err) {
                        alert('An error occurred while adjusting this reading')
                    }
                })
            })
        })
    </script>
@endpush