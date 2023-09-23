<div class="table-responsive">
    <table class="table" id="meterInstallations-table">
        <thead>
        <tr>
            <th>Serviceconnectionid</th>
        <th>Type</th>
        <th>Newmeternumber</th>
        <th>Newmeterbrand</th>
        <th>Newmetersize</th>
        <th>Newmetertype</th>
        <th>Newmeteramperes</th>
        <th>Newmeterinitialreading</th>
        <th>Newmeterlinetoneutral</th>
        <th>Newmeterlinetoground</th>
        <th>Newmeterneutraltoground</th>
        <th>Dateinstalled</th>
        <th>Newmetermultiplier</th>
        <th>Transfomercapacity</th>
        <th>Transformerid</th>
        <th>Poleid</th>
        <th>Ctserialnumber</th>
        <th>Newmeterremarks</th>
        <th>Oldmeternumber</th>
        <th>Oldmeterbrand</th>
        <th>Oldmetersize</th>
        <th>Oldmetertype</th>
        <th>Dateremoved</th>
        <th>Reasonforchanging</th>
        <th>Oldmetermultiplier</th>
        <th>Oldmeterremarks</th>
        <th>Installedby</th>
        <th>Checkedby</th>
        <th>Witness</th>
        <th>Blcirepresentative</th>
        <th>Approvedby</th>
        <th>Removedby</th>
        <th>Customersignature</th>
        <th>Witnesssignature</th>
        <th>Installedbysignature</th>
        <th>Approvedbysignature</th>
        <th>Checkedbysignature</th>
        <th>Blcirepresentativesignature</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($meterInstallations as $meterInstallation)
            <tr>
                <td>{{ $meterInstallation->ServiceConnectionId }}</td>
            <td>{{ $meterInstallation->Type }}</td>
            <td>{{ $meterInstallation->NewMeterNumber }}</td>
            <td>{{ $meterInstallation->NewMeterBrand }}</td>
            <td>{{ $meterInstallation->NewMeterSize }}</td>
            <td>{{ $meterInstallation->NewMeterType }}</td>
            <td>{{ $meterInstallation->NewMeterAmperes }}</td>
            <td>{{ $meterInstallation->NewMeterInitialReading }}</td>
            <td>{{ $meterInstallation->NewMeterLineToNeutral }}</td>
            <td>{{ $meterInstallation->NewMeterLineToGround }}</td>
            <td>{{ $meterInstallation->NewMeterNeutralToGround }}</td>
            <td>{{ $meterInstallation->DateInstalled }}</td>
            <td>{{ $meterInstallation->NewMeterMultiplier }}</td>
            <td>{{ $meterInstallation->TransfomerCapacity }}</td>
            <td>{{ $meterInstallation->TransformerID }}</td>
            <td>{{ $meterInstallation->PoleID }}</td>
            <td>{{ $meterInstallation->CTSerialNumber }}</td>
            <td>{{ $meterInstallation->NewMeterRemarks }}</td>
            <td>{{ $meterInstallation->OldMeterNumber }}</td>
            <td>{{ $meterInstallation->OldMeterBrand }}</td>
            <td>{{ $meterInstallation->OldMeterSize }}</td>
            <td>{{ $meterInstallation->OldMeterType }}</td>
            <td>{{ $meterInstallation->DateRemoved }}</td>
            <td>{{ $meterInstallation->ReasonForChanging }}</td>
            <td>{{ $meterInstallation->OldMeterMultiplier }}</td>
            <td>{{ $meterInstallation->OldMeterRemarks }}</td>
            <td>{{ $meterInstallation->InstalledBy }}</td>
            <td>{{ $meterInstallation->CheckedBy }}</td>
            <td>{{ $meterInstallation->Witness }}</td>
            <td>{{ $meterInstallation->BLCIRepresentative }}</td>
            <td>{{ $meterInstallation->ApprovedBy }}</td>
            <td>{{ $meterInstallation->RemovedBy }}</td>
            <td>{{ $meterInstallation->CustomerSignature }}</td>
            <td>{{ $meterInstallation->WitnessSignature }}</td>
            <td>{{ $meterInstallation->InstalledBySignature }}</td>
            <td>{{ $meterInstallation->ApprovedBySignature }}</td>
            <td>{{ $meterInstallation->CheckedBySignature }}</td>
            <td>{{ $meterInstallation->BLCIRepresentativeSignature }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['meterInstallations.destroy', $meterInstallation->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('meterInstallations.show', [$meterInstallation->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('meterInstallations.edit', [$meterInstallation->id]) }}"
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
