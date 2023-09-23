<div class="table-responsive">
    <table class="table" id="oRAssignings-table">
        <thead>
        <tr>
            <th>Ornumber</th>
        <th>Userid</th>
        <th>Dateassigned</th>
        <th>Issetmanually</th>
        <th>Timeassigned</th>
        <th>Office</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($oRAssignings as $oRAssigning)
            <tr>
                <td>{{ $oRAssigning->ORNumber }}</td>
            <td>{{ $oRAssigning->UserId }}</td>
            <td>{{ $oRAssigning->DateAssigned }}</td>
            <td>{{ $oRAssigning->IsSetManually }}</td>
            <td>{{ $oRAssigning->TimeAssigned }}</td>
            <td>{{ $oRAssigning->Office }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['oRAssignings.destroy', $oRAssigning->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('oRAssignings.show', [$oRAssigning->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('oRAssignings.edit', [$oRAssigning->id]) }}"
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
