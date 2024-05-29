<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="system-settings-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Passworddaysexpire</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($systemSettings as $systemSettings)
                <tr>
                    <td>{{ $systemSettings->id }}</td>
                    <td>{{ $systemSettings->PasswordDaysExpire }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['systemSettings.destroy', $systemSettings->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('systemSettings.show', [$systemSettings->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('systemSettings.edit', [$systemSettings->id]) }}"
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

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $systemSettings])
        </div>
    </div>
</div>
