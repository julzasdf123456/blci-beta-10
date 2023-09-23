<div class="table-responsive">
    <table class="table" id="damageAssessments-table">
        <thead>
        <tr>
            <th>Type</th>
        <th>Objectname</th>
        <th>Feeder</th>
        <th>Town</th>
        <th>Status</th>
        <th>Notes</th>
        <th>Datefixed</th>
        <th>Crewassigned</th>
        <th>Latitude</th>
        <th>Longitude</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($damageAssessments as $damageAssessment)
            <tr>
                <td>{{ $damageAssessment->Type }}</td>
            <td>{{ $damageAssessment->ObjectName }}</td>
            <td>{{ $damageAssessment->Feeder }}</td>
            <td>{{ $damageAssessment->Town }}</td>
            <td>{{ $damageAssessment->Status }}</td>
            <td>{{ $damageAssessment->Notes }}</td>
            <td>{{ $damageAssessment->DateFixed }}</td>
            <td>{{ $damageAssessment->CrewAssigned }}</td>
            <td>{{ $damageAssessment->Latitude }}</td>
            <td>{{ $damageAssessment->Longitude }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['damageAssessments.destroy', $damageAssessment->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('damageAssessments.show', [$damageAssessment->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('damageAssessments.edit', [$damageAssessment->id]) }}"
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
