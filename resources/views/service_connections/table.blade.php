<div class="table-responsive">
    <table class="table" id="serviceConnections-table">
        <thead>
        <tr>
            <th>Memberconsumerid</th>
        <th>Dateofapplication</th>
        <th>Serviceaccountname</th>
        <th>Accountcount</th>
        <th>Sitio</th>
        <th>Barangay</th>
        <th>Town</th>
        <th>Contactnumber</th>
        <th>Emailaddress</th>
        <th>Accounttype</th>
        <th>Accountorganization</th>
        <th>Organizationaccountnumber</th>
        <th>Isnihe</th>
        <th>Accountapplicationtype</th>
        <th>Connectionapplicationtype</th>
        <th>Status</th>
        <th>Notes</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($serviceConnections as $serviceConnections)
            <tr>
                <td>{{ $serviceConnections->MemberConsumerId }}</td>
            <td>{{ $serviceConnections->DateOfApplication }}</td>
            <td>{{ $serviceConnections->ServiceAccountName }}</td>
            <td>{{ $serviceConnections->AccountCount }}</td>
            <td>{{ $serviceConnections->Sitio }}</td>
            <td>{{ $serviceConnections->Barangay }}</td>
            <td>{{ $serviceConnections->Town }}</td>
            <td>{{ $serviceConnections->ContactNumber }}</td>
            <td>{{ $serviceConnections->EmailAddress }}</td>
            <td>{{ $serviceConnections->AccountType }}</td>
            <td>{{ $serviceConnections->AccountOrganization }}</td>
            <td>{{ $serviceConnections->OrganizationAccountNumber }}</td>
            <td>{{ $serviceConnections->IsNIHE }}</td>
            <td>{{ $serviceConnections->AccountApplicationType }}</td>
            <td>{{ $serviceConnections->ConnectionApplicationType }}</td>
            <td>{{ $serviceConnections->Status }}</td>
            <td>{{ $serviceConnections->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['serviceConnections.destroy', $serviceConnections->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('serviceConnections.show', [$serviceConnections->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('serviceConnections.edit', [$serviceConnections->id]) }}"
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
