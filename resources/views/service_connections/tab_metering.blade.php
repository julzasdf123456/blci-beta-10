@php
    use App\Models\ServiceConnections;
@endphp

@if ($serviceConnectionMeter == null)
    <p class="pt-5 text-center">No meter information assigned yet.</p>
@else
    <div class="row p-3">
        <div class="col-lg-12 mb-2">            
            <a href="{{ route('meterInstallations.edit', [$serviceConnectionMeter->id]) }}" class="btn btn-primary-skinny float-right btn-sm"><i class="fas fa-pen ico-tab-mini"></i>Update Meter Data</a>
        </div>
        {{-- kWH METER --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Meter Information (kWH Meter)</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover">
                        <tr>
                            <td>Meter Number</td>
                            <td><strong>{{ $serviceConnectionMeter->NewMeterNumber }}</strong></td>
                        </tr>
                        <tr>
                            <td>Meter Brand</td>
                            <td><strong>{{ $serviceConnectionMeter->NewMeterBrand }}</strong></td>
                        </tr>
                        <tr>
                            <td>Ampere Rating</td>
                            <td><strong>{{ $serviceConnectionMeter->NewMeterAmperes }}</strong></td>
                        </tr>
                        <tr>
                            <td>Old Meter No.</td>
                            <td><strong>{{ $serviceConnectionMeter->OldMeterNumber }}</strong></td>
                        </tr>
                        <tr>
                            <td>Initial Reading</td>
                            <td><strong>{{ $serviceConnectionMeter->NewMeterInitialReading }} kWh</strong></td>
                        </tr>
                        <tr>
                            <td>Multiplier</td>
                            <td><strong>{{ $serviceConnectionMeter->NewMeterMultiplier }}</strong></td>
                        </tr>
                        <tr>
                            <td>Date Installed</td>
                            <td><strong>{{ $serviceConnectionMeter->DateInstalled != null ? date('M d, Y', strtotime($serviceConnectionMeter->DateInstalled)) : "" }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- NET METERING --}}
        @if ($netMeteringMeter != null)
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Meter Information - Solar Generation (Net Metering)</span>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-sm table-hover">
                            <tr>
                                <td>Meter Number</td>
                                <td><strong>{{ $netMeteringMeter->NewMeterNumber }}</strong></td>
                            </tr>
                            <tr>
                                <td>Meter Brand</td>
                                <td><strong>{{ $netMeteringMeter->NewMeterBrand }}</strong></td>
                            </tr>
                            <tr>
                                <td>Ampere Rating</td>
                                <td><strong>{{ $netMeteringMeter->NewMeterAmperes }}</strong></td>
                            </tr>
                            <tr>
                                <td>Old Meter No.</td>
                                <td><strong>{{ $netMeteringMeter->OldMeterNumber }}</strong></td>
                            </tr>
                            <tr>
                                <td>Initial Reading</td>
                                <td><strong>{{ $netMeteringMeter->NewMeterInitialReading }} kWh</strong></td>
                            </tr>
                            <tr>
                                <td>Multiplier</td>
                                <td><strong>{{ $netMeteringMeter->NewMeterMultiplier }}</strong></td>
                            </tr>
                            <tr>
                                <td>Date Installed</td>
                                <td><strong>{{ $netMeteringMeter->DateInstalled != null ? date('M d, Y', strtotime($netMeteringMeter->DateInstalled)) : "" }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- TRANSFORMER --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Transformer and Line Information</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover">
                        <tr>
                            <td>Line to Neutral Voltage</td>
                            <td><strong>{{ $serviceConnectionMeter->NewMeterLineToNeutral }}</strong></td>
                        </tr>
                        <tr>
                            <td>Line to Ground Voltage</td>
                            <td><strong>{{ $serviceConnectionMeter->NewMeterLineToGround }}</strong></td>
                        </tr>
                        <tr>
                            <td>Neutral to Ground Voltage</td>
                            <td><strong>{{ $serviceConnectionMeter->NewMeterNeutralToGround }}</strong></td>
                        </tr>
                        <tr>
                            <td>Transformer Capacity</td>
                            <td><strong>{{ $serviceConnectionMeter->TransformerCapacity }}</strong></td>
                        </tr>
                        <tr>
                            <td>Transformer ID</td>
                            <td><strong>{{ $serviceConnectionMeter->TransformerID }}</strong></td>
                        </tr>
                        <tr>
                            <td>Pole ID</td>
                            <td><strong>{{ $serviceConnections->PoleNumber }}</strong></td>
                        </tr>
                        <tr>
                            <td>Feeder</td>
                            <td><strong>{{ $serviceConnections->Feeder }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- CUSTOMER SIGNATURE --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Customer Signature</span>
                </div>
                <div class="card-body p-0">
                    <p class="text-center">
                        {{-- <img src="data:image/png;base64,{{ $serviceConnectionMeter->CustomerSignature }}" alt="Signature" width="140"> --}}
                        <img src="{{ URL::asset('scfiles/' . $serviceConnections->id . '/images/SIGN_' . $serviceConnections->id . '.png') }}" alt="Signature" width="140">
                    </p>
                </div>
            </div>
        </div>

        {{-- LOGS --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Installation Logs</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover">
                        <tr>
                            <td>Installed By</td>
                            <td><strong>{{ $serviceConnectionMeter->InstalledBy }}</strong></td>
                        </tr>
                        <tr>
                            <td>Notes/Remarks</td>
                            <td><strong>{{ $serviceConnectionMeter->Notes }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif



