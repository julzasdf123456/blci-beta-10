<div class="table-responsive">
    <table class="table table-hover" id="users-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Last Password<br>Update</th>
            {{-- <th>Employee Id</th> --}}
            {{-- <th>Email Verified At</th> --}}
            {{-- <th>Password</th> --}}
            {{-- <th>Remember Token</th> --}}
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $users)
            <tr>
                <td>{{ $users->name }}</td>
                <td>{{ $users->email }}</td>
                <td>{{ $users->username }}</td>
                <td>{{ date('M d, Y', strtotime($users->LastPasswordUpdateDate)) }}</td>
                {{-- <td>{{ $users->employee_id }}</td>
                <td>{{ $users->email_verified_at }}</td>
                <td>{{ $users->password }}</td>
                <td>{{ $users->remember_token }}</td> --}}
                <td width="120">
                    {!! Form::open(['route' => ['users.destroy', $users->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('users.show', [$users->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('users.edit', [$users->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        <a href="{{ route('users.add_user_roles', [$users->id]) }}" class='btn btn-default btn-xs' title="Edit Permissions">
                            <i class="fas fa-key"></i>
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
