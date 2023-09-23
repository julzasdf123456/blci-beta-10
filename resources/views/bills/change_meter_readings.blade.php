@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Change Meter Readings Review</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        {{-- READING DETAILS --}}
        <div class="col-lg-4">            
            <div class="card">
                <div class="card-header border-0">
                    <span class="card-title">Reading Details</span>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-sm">
                        @if ($reading != null && $account != null && $changeMeterLogs != null)
                            <tbody>
                                <tr>
                                    <td>Account Number</td>
                                    <th><a href="{{ route('serviceAccounts.show', [$account->id]) }}">{{ $account->OldAccountNo }}</a></th>
                                </tr>
                                <tr>
                                    <td>Account Name</td>
                                    <th>{{ $account->ServiceAccountName }}</th>
                                </tr>
                                <tr>
                                    <td>Billing Month</td>
                                    <th>{{ date('F Y', strtotime($reading->ServicePeriod)) }}</th>
                                </tr>
                                <tr>
                                    <td>Reading</td>
                                    <th>{{ $reading->KwhUsed }} kWh</th>
                                </tr>
                            </tbody>
                        @endif
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-0">
                    <span class="card-title">Change Meter Details</span>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-sm">
                        @if ($changeMeterLogs != null)
                            <tbody>
                                <tr>
                                    <td>Old Meter Serial</td>
                                    <th>{{ $changeMeterLogs->OldMeterSerial }}</th>
                                </tr>
                                <tr>
                                    <td>Old Meter Pull-out Reading</td>
                                    <th>{{ $changeMeterLogs->PullOutReading }} kWh</th>
                                </tr>
                                <tr>
                                    <td>Pull-out Date</td>
                                    <th>{{ date('F d, Y h:i:s A', strtotime($changeMeterLogs->created_at)) }}</th>
                                </tr>
                                <tr>
                                    <td>New Meter Serial</td>
                                    <th>{{ $changeMeterLogs->NewMeterSerial }}</th>
                                </tr>
                                <tr>
                                    <td>New Meter Start kWh</td>
                                    <th>{{ $changeMeterLogs->NewMeterStartKwh }} kWh</th>
                                </tr>
                            </tbody>
                        @endif
                    </table>
                </div>
                <div class="card-footer">
                    <span>
                        Suggested Average Additional kWh Used<br>
                        <i class="text-muted">Based on number of days from the last reading date to the day the new meter was installed.</i>
                    </span>
                    <h4><strong>{{ $changeMeterLogs != null ? $changeMeterLogs->AdditionalKwhForNextBilling : '0' }} kWh</strong></h4>
                    @if ($changeMeterLogs != null && $reading != null)
                        <span><i>{{ $changeMeterLogs->AdditionalKwhForNextBilling }} + {{ $reading->KwhUsed }} = {{ intval($changeMeterLogs->AdditionalKwhForNextBilling) + intval($reading->KwhUsed) }} kWh</i></span>
                    @endif
                </div>
            </div>
        </div>

        {{-- PREVIOUS READINGS --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header border-0">
                    <span class="card-title">Previous Readings</span>
                </div>
                <div class="card-body table-responsive px-0">
                    <table class="table table-hover table-sm">
                        <thead>
                            <th>Billing Month</th>
                            <th>kWh Used</th>
                            <th>Reading Date</th>
                        </thead>
                        <tbody>
                            @foreach ($prevReadings as $item)
                                <tr>
                                    <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                    <td>{{ $item->KwhUsed }}</td>
                                    <td>{{ date('F d, Y', strtotime($item->ReadingTimestamp)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- UPDATE FORM --}}
        <div class="col-lg-4">
            <div class="card">
                {!! Form::open(['route' => 'bills.bill-change-meters']) !!}
                <div class="card-header bg-primary">
                    <span class="card-title"><i class="fas fa-info-circle ico-tab"></i> Perform Billing Here</span>
                </div>
                <div class="card-body">
                    @if ($account != null && $reading != null)
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        
                        <input type="hidden" name="AccountNumber" value="{{ $account->id }}">

                        <input type="hidden" name="ServicePeriod" value="{{ $reading->ServicePeriod }}">

                        <div class="row">
                            <label class="col-md-5">kWh Used</label>
                            <input type="number" step="any" name="KwhUsed" class="form-control col-md-7" autofocus>
                        </div>
                    @else
                        <p class="text-danger"><i>Account not found!</i></p>
                    @endif                    
                </div>
                <div class="card-footer">
                    {!! Form::submit('Proceed Billing', ['class' => 'btn btn-primary']) !!}
                </div>
    
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection