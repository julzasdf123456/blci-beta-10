@php
    use App\Models\ServiceConnectionChecklists;
    use App\Models\ServiceConnections;

    $typesOfCustomer = ServiceConnections::typesOfConsumer();
@endphp
<div class="row">
    {{-- details --}}
    <div class="col-lg-10">
        <div class="table-responsive">
            <table class="table table-sm table-hover table-borderless">
                <tr>
                    <td class="text-muted">Classification of Service</td>
                    <td>{{ $serviceConnections->AccountType }}</td>
                    <td class="text-muted" style="padding-left: 50px;">Metering Type</td>
                    <td><i class="fas fa-exclamation-circle text-danger"></i></td>
                </tr>
                <tr>
                    <td class="text-muted">Type of Customer</td>
                    <td>{{ $serviceConnections->TypeOfCustomer != null ? isset($typesOfCustomer[$serviceConnections->TypeOfCustomer]) ? $typesOfCustomer[$serviceConnections->TypeOfCustomer] : '-' : '-' }}</td>
                    <td class="text-muted" style="padding-left: 50px;">Status</td>
                    <td>{{ $serviceConnections->Status }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Service Applied For</td>
                    <td><strong>{{ $serviceConnections->AccountApplicationType }}</strong></td>
                    <td class="text-muted" style="padding-left: 50px;">Inspector</td>
                    <td>{{ $serviceConnectionInspections != null && $serviceConnectionInspections->name != null ? $serviceConnectionInspections->name : '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Service Number</td>
                    <td>{{ $serviceConnections->ServiceNumber }}</td>
                    <td class="text-muted" style="padding-left: 50px;">Date of Inspection</td>
                    <td>{{ $serviceConnectionInspections != null && $serviceConnectionInspections->DateOfVerification != null ? date('M d, Y', strtotime($serviceConnectionInspections->DateOfVerification)) : '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Serial Number</td>
                    <td>{{ $serviceConnectionMeter != null ? $serviceConnectionMeter->NewMeterNumber : '-' }}</td>
                    <td class="text-muted" style="padding-left: 50px;">Latest Re-Inspection Date</td>
                    <td><i class="fas fa-exclamation-circle text-danger"></i></td>  {{-- GET DATA FROM RE-INSPECTION LOGS --}}  
                </tr>
                <tr>
                    <td class="text-muted">Cert. of Conn. Issued On</td>
                    <td>{{ $serviceConnections->CertificateOfConnectionIssuedOn != null ? date('M d, Y', strtotime($serviceConnections->CertificateOfConnectionIssuedOn)) : '-' }}</td> 
                    <td class="text-muted" style="padding-left: 50px;">Date Of Payment</td>
                    <td>{{ $serviceConnections->ORDate != null ? date('M d, Y', strtotime($serviceConnections->ORDate)) : '-' }}</td>       
                </tr>
                <tr>
                    <td class="text-muted">Application Received By</td>
                    <td>{{ $serviceConnections->name }}</td>
                    <td class="text-muted" style="padding-left: 50px;">Time Of Payment</td>
                    <td><i class="fas fa-exclamation-circle text-danger"></i></td>  
                </tr>
                <tr>
                    <td class="text-muted">Date of Application</td>
                    <td>{{ $serviceConnections->DateOfApplication != null ? date('M d, Y', strtotime($serviceConnections->DateOfApplication)) : '-' }}</td>
                    <td class="text-muted" style="padding-left: 50px;">Connection Schedule</td>
                    <td>{{ $serviceConnections->ConnectionSchedule != null ? date('M d, Y', strtotime($serviceConnections->ConnectionSchedule)) : '-' }}</td>  
                </tr>
                <tr>
                    <td class="text-muted">Time of Application</td>
                    <td>{{ $serviceConnections->TimeOfApplication != null ? date('h:i A', strtotime($serviceConnections->TimeOfApplication)) : '-' }}</td>
                    <td class="text-muted" style="padding-left: 50px;">Date of Connection</td>
                    <td>{{ $serviceConnections->DateTimeOfEnergization != null ? date('F d, Y', strtotime($serviceConnections->DateTimeOfEnergization)) : '-' }}</td>  
                </tr>
                <tr>
                    <td class="text-muted">Inspection Schedule</td>
                    <td>{{ $serviceConnectionInspections != null &&$serviceConnectionInspections->InspectionSchedule != null ? date('M d, Y', strtotime($serviceConnectionInspections->InspectionSchedule)) : '-' }}</td>
                    <td class="text-muted" style="padding-left: 50px;">Time of Connection</td>
                    <td>{{ $serviceConnections->DateTimeOfEnergization != null ? date('h:i A', strtotime($serviceConnections->DateTimeOfEnergization)) : '-' }}</td>  
                </tr>
                <tr>
                    <td class="text-muted">Re-Inspection Schedule</td>
                    <td>{{ $serviceConnectionInspections != null &&$serviceConnectionInspections->ReInspectionSchedule != null ? date('M d, Y', strtotime($serviceConnectionInspections->ReInspectionSchedule)) : '-' }}</td>
                    <td class="text-muted" style="padding-left: 50px;">Days Lost</td>
                    <td><i class="fas fa-exclamation-circle text-danger"></i></td>  
                </tr>
                <tr>
                    <td class="text-muted">Load Type</td>
                    <td>{{ $serviceConnections->LoadType }}</td>
                    <td class="text-muted" style="padding-left: 50px;">TA (Days)</td>
                    <td><i class="fas fa-exclamation-circle text-danger"></i></td>  
                </tr>
                <tr>
                    <td class="text-muted">Zone and Block</td>
                    <td>{{ $serviceConnections->Zone }}-{{ $serviceConnections->Block }}</td>
                    <td class="text-muted" style="padding-left: 50px;">Installed By</td>
                    <td>{{ $serviceConnections->StationName }}</td>  
                </tr>
                <tr>
                    <td class="text-muted">Transformer ID</td>
                    <td>{{ $serviceConnectionInspections->TransformerNo==null ? '-' : $serviceConnectionInspections->TransformerNo }}</td>
                    <td class="text-muted" style="padding-left: 50px;">TIN</td>
                    <td>{{ $serviceConnections->TIN }}</td> 
                </tr>
                <tr>
                    <td class="text-muted">Transformer Load (kVa)</td>
                    <td>{{ $serviceConnectionInspections->SizeOfTransformer==null ? '-' : $serviceConnectionInspections->SizeOfTransformer }}</td>
                    <td class="text-muted" style="padding-left: 50px;"></td>
                    <td></td> 
                </tr>
                <tr>
                    <td class="text-muted">Pole Number</td>
                    <td>{{ $serviceConnections->PoleNumber }}</td> 
                    <td class="text-muted" style="padding-left: 50px;"></td>
                    <td></td>  
                </tr>
                <tr>
                    <td class="text-muted">Feeder Number</td>
                    <td>{{ $serviceConnections->Feeder }}</td>
                    <td class="text-muted" style="padding-left: 50px;"></td>
                    <td></td>  
                </tr>
                <tr>
                    <td class="text-muted">Charge To</td>
                    <td>{{ $serviceConnections->ChargeTo }}</td>
                    <td class="text-muted" style="padding-left: 50px;"></td>
                    <td></td>  
                </tr>
            </table>
        </div>
    </div>

    {{-- timeline --}}
    <div class="col-lg-2">
        <div class="timeline mt-4">
            <div>
                <i style="font-size: .8em !important;" class="fas fa-check bg-success"></i>
                <div class="timeline-item">
                    <div class="timeline-body">
                        <span style="font-size: .9em;">Applied</span>
                        <br>
                        <span class="text-muted" style="font-size: .8em;"><i class="fas fa-clock"></i> {{ $serviceConnections->DateOfApplication != null ? date('M d, Y', strtotime($serviceConnections->DateOfApplication)) : '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                @if (ServiceConnections::statusCheckPreviousArray($serviceConnections->Status) >= 1)
                    <i style="font-size: .8em !important;" class="fas fa-check bg-success"></i>
                @else
                    <i style="font-size: .8em !important;" class="fas fa-ellipsis-h bg-gray"></i>
                @endif
                
                <div class="timeline-item">
                    <div class="timeline-body">
                        <span style="font-size: .9em;">Paid Inspection Fee</span>
                        <br>
                        <span class="text-muted" style="font-size: .8em;"><i class="fas fa-clock"></i> {{ $paymentOrder != null && $paymentOrder->InspectionFeeORDate != null ? date('M d, Y', strtotime($paymentOrder->InspectionFeeORDate)) : '-' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                @if (ServiceConnections::statusCheckPreviousArray($serviceConnections->Status) >= 2)
                    <i style="font-size: .8em !important;" class="fas fa-check bg-success"></i>
                @else
                    <i style="font-size: .8em !important;" class="fas fa-ellipsis-h bg-gray"></i>
                @endif
                <div class="timeline-item">
                    <div class="timeline-body">
                        <span style="font-size: .9em;">Approved Inspection</span>
                        <br>
                        <span class="text-muted" style="font-size: .8em;"><i class="fas fa-clock"></i>  {{ $serviceConnectionInspections != null && $serviceConnectionInspections->DateOfVerification != null ? date('M d, Y', strtotime($serviceConnectionInspections->DateOfVerification)) : '-' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                @if (ServiceConnections::statusCheckPreviousArray($serviceConnections->Status) >= 2 && $serviceConnections->ORNumber != null && $serviceConnections->ORNumber !== '0')
                    <i style="font-size: .8em !important;" class="fas fa-check bg-success"></i>
                @else
                    <i style="font-size: .8em !important;" class="fas fa-ellipsis-h bg-gray"></i>
                @endif
                <div class="timeline-item">
                    <div class="timeline-body">
                        <span style="font-size: .9em;">Paid</span>
                        <br>
                        <span class="text-muted" style="font-size: .8em;"><i class="fas fa-clock"></i> {{ $serviceConnections != null && $serviceConnections->ORDate != null ? date('M d, Y', strtotime($serviceConnections->ORDate)) : '-' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                @if (ServiceConnections::statusCheckPreviousArray($serviceConnections->Status) >= 4)
                    <i style="font-size: .8em !important;" class="fas fa-check bg-success"></i>
                @else
                    <i style="font-size: .8em !important;" class="fas fa-ellipsis-h bg-gray"></i>
                @endif
                <div class="timeline-item">
                    <div class="timeline-body">
                        <span style="font-size: .9em;">Approved Turn-on</span>
                        <br>
                        <span title="Connection Schedule" class="text-muted" style="font-size: .8em;"><i class="fas fa-clock"></i> {{ $serviceConnections != null && $serviceConnections->ConnectionSchedule != null ? date('M d, Y', strtotime($serviceConnections->ConnectionSchedule)) : '-' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                @if (ServiceConnections::statusCheckPreviousArray($serviceConnections->Status) >= 5)
                    <i style="font-size: .8em !important;" class="fas fa-check bg-success"></i>
                @else
                    <i style="font-size: .8em !important;" class="fas fa-ellipsis-h bg-gray"></i>
                @endif
                <div class="timeline-item">
                    <div class="timeline-body">
                        <span style="font-size: .9em;">Energized</span>
                        <br>
                        <span class="text-muted" style="font-size: .8em;"><i class="fas fa-clock"></i> {{ $serviceConnections != null && $serviceConnections->DateTimeOfEnergization != null ? date('M d, Y', strtotime($serviceConnections->DateTimeOfEnergization)) : '-' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                @if (ServiceConnections::statusCheckPreviousArray($serviceConnections->Status) >= 6)
                    <i style="font-size: .8em !important;" class="fas fa-check bg-success"></i>
                @else
                    <i style="font-size: .8em !important;" class="fas fa-ellipsis-h bg-gray"></i>
                @endif
                <div class="timeline-item">
                    <div class="timeline-body">
                        <span style="font-size: .9em;">Closed</span>
                        {{-- <br>
                        <span class="text-muted" style="font-size: .8em;"><i class="fas fa-clock"></i> 04/04/2024</span> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



