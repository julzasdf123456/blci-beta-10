@php
    use App\Models\ServiceConnectionChecklists;
@endphp
<div class="table-responsive">
    <table class="table table-sm table-hover table-borderless">
        <tr>
            <td class="text-muted">Classification of Service</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnections->AccountType }}</td>
            <td class="text-muted" style="padding-left: 50px;">Metering Type</td>
            <td><i class="fas fa-exclamation-circle text-danger"></i></td>
        </tr>
        <tr>
            <td class="text-muted">Service Applied For</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnections->AccountApplicationType }}</td>
            <td class="text-muted" style="padding-left: 50px;">Status</td>
            <td>{{ $serviceConnections->Status }}</td>
        </tr>
        <tr>
            <td class="text-muted">Service Number</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnections->ServiceNumber }}</td>
            <td class="text-muted" style="padding-left: 50px;">Inspector</td>
            <td>{{ $serviceConnectionInspections != null && $serviceConnectionInspections->name != null ? $serviceConnectionInspections->name : '-' }}</td>
        </tr>
        <tr>
            <td class="text-muted">Serial Number</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnectionMeter != null ? $serviceConnectionMeter->NewMeterNumber : '-' }}</td>
            <td class="text-muted" style="padding-left: 50px;">Date of Inspection</td>
            <td>{{ $serviceConnectionInspections != null && $serviceConnectionInspections->DateOfVerification != null ? date('M d, Y', strtotime($serviceConnectionInspections->DateOfVerification)) : '-' }}</td>
        </tr>
        <tr>
            <td class="text-muted">Cert. of Conn. Issued On</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnections->CertificateOfConnectionIssuedOn != null ? date('M d, Y', strtotime($serviceConnections->CertificateOfConnectionIssuedOn)) : '-' }}</td>
            <td class="text-muted" style="padding-left: 50px;">Latest Re-Inspection Date</td>
            <td><i class="fas fa-exclamation-circle text-danger"></i></td>  {{-- GET DATA FROM RE-INSPECTION LOGS --}}        
        </tr>
        <tr>
            <td class="text-muted">Application Received By</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnections->name }}</td>
            <td class="text-muted" style="padding-left: 50px;">Date Of Payment</td>
            <td>{{ $serviceConnections->ORDate != null ? date('M d, Y', strtotime($serviceConnections->ORDate)) : '-' }}</td>  
        </tr>
        <tr>
            <td class="text-muted">Date of Application</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnections->DateOfApplication != null ? date('M d, Y', strtotime($serviceConnections->DateOfApplication)) : '-' }}</td>
            <td class="text-muted" style="padding-left: 50px;">Time Of Payment</td>
            <td><i class="fas fa-exclamation-circle text-danger"></i></td>  
        </tr>
        <tr>
            <td class="text-muted">Time of Application</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnections->TimeOfApplication != null ? date('h:i A', strtotime($serviceConnections->TimeOfApplication)) : '-' }}</td>
            <td class="text-muted" style="padding-left: 50px;">Connection Schedule</td>
            <td>{{ $serviceConnections->ConnectionSchedule != null ? date('M d, Y', strtotime($serviceConnections->ConnectionSchedule)) : '-' }}</td>  
        </tr>
        <tr>
            <td class="text-muted">Inspection Schedule</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnectionInspections != null &&$serviceConnectionInspections->InspectionSchedule != null ? date('M d, Y', strtotime($serviceConnectionInspections->InspectionSchedule)) : '-' }}</td>
            <td class="text-muted" style="padding-left: 50px;">Date of Connection</td>
            <td>{{ $serviceConnections->DateTimeOfEnergization != null ? date('F d, Y', strtotime($serviceConnections->DateTimeOfEnergization)) : '-' }}</td>  
        </tr>
        <tr>
            <td class="text-muted">Re-Inspection Schedule</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnectionInspections != null &&$serviceConnectionInspections->ReInspectionSchedule != null ? date('M d, Y', strtotime($serviceConnectionInspections->ReInspectionSchedule)) : '-' }}</td>
            <td class="text-muted" style="padding-left: 50px;">Time of Connection</td>
            <td>{{ $serviceConnections->DateTimeOfEnergization != null ? date('h:i A', strtotime($serviceConnections->DateTimeOfEnergization)) : '-' }}</td>  
        </tr>
        <tr>
            <td class="text-muted">Load Type</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnections->LoadType }}</td>
            <td class="text-muted" style="padding-left: 50px;">Days Lost</td>
            <td><i class="fas fa-exclamation-circle text-danger"></i></td>  
        </tr>
        <tr>
            <td class="text-muted">Zone and Block</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnections->Zone }}-{{ $serviceConnections->Block }}</td>
            <td class="text-muted" style="padding-left: 50px;">TA (Days)</td>
            <td><i class="fas fa-exclamation-circle text-danger"></i></td>  
        </tr>
        <tr>
            <td class="text-muted">Transformer ID</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnectionInspections->TransformerNo==null ? '-' : $serviceConnectionInspections->TransformerNo }}</td>
            <td class="text-muted" style="padding-left: 50px;">Installed By</td>
            <td>{{ $serviceConnections->StationName }}</td>  
        </tr>
        <tr>
            <td class="text-muted">Transformer Load (kVa)</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnectionInspections->SizeOfTransformer==null ? '-' : $serviceConnectionInspections->SizeOfTransformer }}</td>
            <td class="text-muted" style="padding-left: 50px;">TIN</td>
            <td>{{ $serviceConnections->TIN }}</td> 
        </tr>
        <tr>
            <td class="text-muted">Pole Number</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnections->PoleNumber }}</td>
            <td class="text-muted" style="padding-left: 50px;"></td>
            <td></td>  
        </tr>
        <tr>
            <td class="text-muted">Feeder Number</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnections->Feeder }}</td>
            <td class="text-muted" style="padding-left: 50px;"></td>
            <td></td>  
        </tr>
        <tr>
            <td class="text-muted">Charge To</td>
            <td style="border-right: 1px solid #adadad;">{{ $serviceConnections->ChargeTo }}</td>
            <td class="text-muted" style="padding-left: 50px;"></td>
            <td></td>  
        </tr>
    </table>
</div>



