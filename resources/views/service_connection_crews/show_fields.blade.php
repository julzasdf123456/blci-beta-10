<!-- Stationname Field -->
<div class="col-sm-12">
    {!! Form::label('StationName', 'Stationname:') !!}
    <p>{{ $serviceConnectionCrew->StationName }}</p>
</div>

<!-- Crewleader Field -->
<div class="col-sm-12">
    {!! Form::label('CrewLeader', 'Crewleader:') !!}
    <p>{{ $serviceConnectionCrew->CrewLeader }}</p>
</div>

<!-- Members Field -->
<div class="col-sm-12">
    {!! Form::label('Members', 'Members:') !!}
    <p>{{ $serviceConnectionCrew->Members }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    <p>{{ $serviceConnectionCrew->Notes }}</p>
</div>

