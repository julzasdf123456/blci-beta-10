<div class="table-responsive">
    <table class="table" id="memberConsumers-table">
        <thead>
        <tr>
            <th>Id</th>
            <th>Firstname</th>
            <th>Middlename</th>
            <th>Lastname</th>
            <th>Organizationname</th>
            <th>CivilStatus</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($memberConsumers as $memberConsumers)
            <tr>
                <td>{{ $memberConsumers->Id }}</td>
            <td>{{ $memberConsumers->FirstName }}</td>
            <td>{{ $memberConsumers->MiddleName }}</td>
            <td>{{ $memberConsumers->LastName }}</td>
            <td>{{ $memberConsumers->OrganizationName }}</td>
            <td>{{ $memberConsumers->CivilStatus }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['memberConsumers.destroy', $memberConsumers->Id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('memberConsumers.show', [$memberConsumers->Id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('memberConsumers.edit', [$memberConsumers->Id]) }}"
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
