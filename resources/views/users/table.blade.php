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
                        <button onclick="updatePasswordAdmin(event, `{{ $users->id }}`)" class='btn btn-default btn-xs'  title="Reset Password">
                            <i class="fas fa-unlock-alt"></i>
                        </button>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@push('page_scripts')
    <script>
        function updatePasswordAdmin(event, userid) {
            event.preventDefault()
            Swal.fire({
                title: 'Reset User Password',
                html:
                    `<p>Validate new password below.</p>
                    <input id="password" class="swal2-input" type="password" placeholder="Enter password...">
                    <input id="password-confirm" class="swal2-input" type="password" placeholder="Confirm password...">`,
                focusConfirm: false,
                confirmButtonText: 'Update Password',
                preConfirm: () => {
                    const pword = document.getElementById('password').value;
                    const pwordConfirm = document.getElementById('password-confirm').value;

                    if (!pword || !pwordConfirm) {
                        Swal.showValidationMessage('Both passwords are required.');
                        return false;
                    }

                    if (pword !== pwordConfirm) {
                        Swal.showValidationMessage('Passwords do not match.');
                        return false;
                    }

                    return fetch('{{ route("users.update-password-admin") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            password: pword,
                            password_confirmation: pwordConfirm,
                            user_id : userid,
                        })
                    })
                    .then(response => response.json().then(data => ({
                        status: response.status,
                        body: data
                    })))
                    .then(({ status, body }) => {
                        if (status !== 200) {
                            console.log('Server response:', body);
                            Swal.showValidationMessage(`Request failed: ${body.message || 'Unknown error'}`);
                            return false;
                        }
                        return body
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                        console.log(error.message)
                    })
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Success', 'Password updated successfully', 'success');
                }
            })
        }
    </script>
@endpush
