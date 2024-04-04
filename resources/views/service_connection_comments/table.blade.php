<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="service-connection-comments-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Serviceconnectionid</th>
                <th>Userid</th>
                <th>Comments</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($serviceConnectionComments as $serviceConnectionComments)
                <tr>
                    <td>{{ $serviceConnectionComments->id }}</td>
                    <td>{{ $serviceConnectionComments->ServiceConnectionId }}</td>
                    <td>{{ $serviceConnectionComments->UserId }}</td>
                    <td>{{ $serviceConnectionComments->Comments }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['serviceConnectionComments.destroy', $serviceConnectionComments->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('serviceConnectionComments.show', [$serviceConnectionComments->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('serviceConnectionComments.edit', [$serviceConnectionComments->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $serviceConnectionComments])
        </div>
    </div>
</div>
