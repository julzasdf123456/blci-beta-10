<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Type', 'Type:') !!}
    {!! Form::text('Type', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Objectname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ObjectName', 'Objectname:') !!}
    {!! Form::text('ObjectName', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Feeder Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Feeder', 'Feeder:') !!}
    {!! Form::text('Feeder', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Town Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Town', 'Town:') !!}
    {!! Form::text('Town', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 3000,'maxlength' => 3000]) !!}
</div>

<!-- Datefixed Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateFixed', 'Datefixed:') !!}
    {!! Form::text('DateFixed', null, ['class' => 'form-control','id'=>'DateFixed']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateFixed').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Crewassigned Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CrewAssigned', 'Crewassigned:') !!}
    {!! Form::text('CrewAssigned', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Latitude Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Latitude', 'Latitude:') !!}
    {!! Form::text('Latitude', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Longitude Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Longitude', 'Longitude:') !!}
    {!! Form::text('Longitude', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>