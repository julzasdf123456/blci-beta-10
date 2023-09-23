<div class="table-responsive">
    <table class="table" id="serviceConnectionInspections-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Semaincircuitbreakerasplan</th>
        <th>Semaincircuitbreakerasinstalled</th>
        <th>Senoofbranchesasplan</th>
        <th>Senoofbranchesasinstalled</th>
        <th>Polegiestimateddiameter</th>
        <th>Polegiheight</th>
        <th>Poleginoofliftpoles</th>
        <th>Poleconcreteestimateddiameter</th>
        <th>Poleconcreteheight</th>
        <th>Poleconcretenoofliftpoles</th>
        <th>Polehardwoodestimateddiameter</th>
        <th>Polehardwoodheight</th>
        <th>Polehardwoodnoofliftpoles</th>
        <th>Poleremarks</th>
        <th>Sdwsizeasplan</th>
        <th>Sdwsizeasinstalled</th>
        <th>Sdwlengthasplan</th>
        <th>Sdwlengthasinstalled</th>
        <th>Geobuilding</th>
        <th>Geotappingpole</th>
        <th>Geometeringpole</th>
        <th>Geosepole</th>
        <th>Firstneighborname</th>
        <th>Firstneighbormeterserial</th>
        <th>Secondneighborname</th>
        <th>Secondneighbormeterserial</th>
        <th>Engineerinchargename</th>
        <th>Engineerinchargetitle</th>
        <th>Engineerinchargelicenseno</th>
        <th>Engineerinchargelicensevalidity</th>
        <th>Engineerinchargecontactno</th>
        <th>Status</th>
        <th>Inspector</th>
        <th>Dateofverification</th>
        <th>Estimateddateforreinspection</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnectionInspections as $serviceConnectionInspections)
            <tr>
                <td>{{ $serviceConnectionInspections->ServiceConnectionId }}</td>
            <td>{{ $serviceConnectionInspections->SEMainCircuitBreakerAsPlan }}</td>
            <td>{{ $serviceConnectionInspections->SEMainCircuitBreakerAsInstalled }}</td>
            <td>{{ $serviceConnectionInspections->SENoOfBranchesAsPlan }}</td>
            <td>{{ $serviceConnectionInspections->SENoOfBranchesAsInstalled }}</td>
            <td>{{ $serviceConnectionInspections->PoleGIEstimatedDiameter }}</td>
            <td>{{ $serviceConnectionInspections->PoleGIHeight }}</td>
            <td>{{ $serviceConnectionInspections->PoleGINoOfLiftPoles }}</td>
            <td>{{ $serviceConnectionInspections->PoleConcreteEstimatedDiameter }}</td>
            <td>{{ $serviceConnectionInspections->PoleConcreteHeight }}</td>
            <td>{{ $serviceConnectionInspections->PoleConcreteNoOfLiftPoles }}</td>
            <td>{{ $serviceConnectionInspections->PoleHardwoodEstimatedDiameter }}</td>
            <td>{{ $serviceConnectionInspections->PoleHardwoodHeight }}</td>
            <td>{{ $serviceConnectionInspections->PoleHardwoodNoOfLiftPoles }}</td>
            <td>{{ $serviceConnectionInspections->PoleRemarks }}</td>
            <td>{{ $serviceConnectionInspections->SDWSizeAsPlan }}</td>
            <td>{{ $serviceConnectionInspections->SDWSizeAsInstalled }}</td>
            <td>{{ $serviceConnectionInspections->SDWLengthAsPlan }}</td>
            <td>{{ $serviceConnectionInspections->SDWLengthAsInstalled }}</td>
            <td>{{ $serviceConnectionInspections->GeoBuilding }}</td>
            <td>{{ $serviceConnectionInspections->GeoTappingPole }}</td>
            <td>{{ $serviceConnectionInspections->GeoMeteringPole }}</td>
            <td>{{ $serviceConnectionInspections->GeoSEPole }}</td>
            <td>{{ $serviceConnectionInspections->FirstNeighborName }}</td>
            <td>{{ $serviceConnectionInspections->FirstNeighborMeterSerial }}</td>
            <td>{{ $serviceConnectionInspections->SecondNeighborName }}</td>
            <td>{{ $serviceConnectionInspections->SecondNeighborMeterSerial }}</td>
            <td>{{ $serviceConnectionInspections->EngineerInchargeName }}</td>
            <td>{{ $serviceConnectionInspections->EngineerInchargeTitle }}</td>
            <td>{{ $serviceConnectionInspections->EngineerInchargeLicenseNo }}</td>
            <td>{{ $serviceConnectionInspections->EngineerInchargeLicenseValidity }}</td>
            <td>{{ $serviceConnectionInspections->EngineerInchargeContactNo }}</td>
            <td>{{ $serviceConnectionInspections->Status }}</td>
            <td>{{ $serviceConnectionInspections->Inspector }}</td>
            <td>{{ $serviceConnectionInspections->DateOfVerification }}</td>
            <td>{{ $serviceConnectionInspections->EstimatedDateForReinspection }}</td>
            <td>{{ $serviceConnectionInspections->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnectionInspections.destroy', $serviceConnectionInspections->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnectionInspections.show', [$serviceConnectionInspections->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnectionInspections.edit', [$serviceConnectionInspections->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
