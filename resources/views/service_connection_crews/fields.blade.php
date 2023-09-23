<!-- Stationname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('StationName', 'Station Name') !!}
    {!! Form::text('StationName', null, ['class' => 'form-control','maxlength' => 140,'maxlength' => 140]) !!}
</div>

<!-- Crewleader Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CrewLeader', 'Crew/Team Leader') !!}
    {!! Form::text('CrewLeader', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Members Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Members', 'Members:') !!}
    {!! Form::textarea('Members', null, ['class' => 'form-control','maxlength' => 1500,'maxlength' => 1500, 'rows' => 3, 'placeholder' => 'Separate by comma per name']) !!}
</div>

<!-- OFFICE -->
<div class="form-group col-sm-6">
    {!! Form::label('Office', 'Office:') !!}
    {!! Form::select('Office', ['MAIN' => 'MAIN', 'CADIZ' => 'CADIZ', 'ESCALANTE' => 'ESCALANTE', 'MANAPLA' => 'MANAPLA', 'VICTORIAS' => 'VICTORIAS', 'SAN CARLOS' => 'SAN CARLOS', 'EB MAGALONA' => 'EB MAGALONA',  'SAGAY' => 'SAGAY', 'TOBOSO' => 'TOBOSO', 'CALATRAVA' => 'CALATRAVA'], null, ['class' => 'form-control',]) !!}
</div>

<!-- Grouping -->
<div class="form-group col-sm-6">
    {!! Form::label('Grouping', 'Grouping:') !!}
    {!! Form::select('Grouping', ['Group' => 'Group', 'Individual' => 'Individual'], null, ['class' => 'form-control',]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Notes', 'Notes/Remarks') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>