@if ($lineAndMetering !== null)
    <div class="row p-2">
        {{-- METERING --}}
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-tachometer-alt ico-tab"></i>Metering Services</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm">
                        <tbody>
                            <tr>
                                <td class="text-muted v-align">Meter Seal No.</td>
                                <td class="v-align">{{ $lineAndMetering->MeterSealNumber }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Lead Seal</td>
                                <td class="v-align">{{ $lineAndMetering->IsLeadSeal }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Meter Status</td>
                                <td class="v-align">{{ $lineAndMetering->MeterStatus }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Meter Number</td>
                                <td class="v-align">{{ $lineAndMetering->MeterNumber }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Multiplier</td>
                                <td class="v-align">{{ $lineAndMetering->Multiplier }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Brand</td>
                                <td class="v-align">{{ $lineAndMetering->MeterBrand }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Findings/Remarks</td>
                                <td class="v-align">{{ $lineAndMetering->Notes }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Serviced On</td>
                                <td class="v-align">{{ $lineAndMetering->ServiceDate != null ? date('F d, Y h:i A', strtotime($lineAndMetering->ServiceDate)) : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Done By</td>
                                <td class="v-align">{{ $lineAndMetering->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Private Electrician</td>
                                <td class="v-align">{{ $lineAndMetering->PrivateElectrician }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>

        {{-- LINE --}}
        <div class="col-lg-6 col-md-12">
            {{-- LINE --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-plug ico-tab"></i>Line Services</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm">
                        <tbody>
                            <tr>
                                <td class="text-muted v-align">Length</td>
                                <td class="v-align">{{ $lineAndMetering->LineLength }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Conductor</td>
                                <td class="v-align">{{ $lineAndMetering->ConductorType }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Value</td>
                                <td class="v-align">{{ $lineAndMetering->ConductorSize }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Unit</td>
                                <td class="v-align">{{ $lineAndMetering->ConductorUnit }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Serviced On</td>
                                <td class="v-align">{{ $lineAndMetering->ServiceDate != null ? date('F d, Y h:i A', strtotime($lineAndMetering->ServiceDate)) : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Done By</td>
                                <td class="v-align">{{ $lineAndMetering->name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer"></div>
            </div>

            {{-- CUSTOMER SIGNATURE --}}
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
    </div>
@else
    <p class="text-center pt-5">No line and metering details recorded!</p>
@endif