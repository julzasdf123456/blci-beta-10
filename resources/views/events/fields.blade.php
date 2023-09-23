<!-- Eventtitle Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EventTitle', 'Eventtitle:') !!}
    {!! Form::text('EventTitle', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Eventdescription Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EventDescription', 'Eventdescription:') !!}
    {!! Form::text('EventDescription', null, ['class' => 'form-control','maxlength' => 2000,'maxlength' => 2000]) !!}
</div>

<!-- Eventstart Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EventStart', 'Eventstart:') !!}
    {!! Form::text('EventStart', null, ['class' => 'form-control','id'=>'EventStart']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#EventStart').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Eventend Field -->
<div class="form-group col-sm-6">
    {!! Form::label('EventEnd', 'Eventend:') !!}
    {!! Form::text('EventEnd', null, ['class' => 'form-control','id'=>'EventEnd']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#EventEnd').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Registrationstart Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RegistrationStart', 'Registrationstart:') !!}
    {!! Form::text('RegistrationStart', null, ['class' => 'form-control','id'=>'RegistrationStart']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#RegistrationStart').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Registrationend Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RegistrationEnd', 'Registrationend:') !!}
    {!! Form::text('RegistrationEnd', null, ['class' => 'form-control','id'=>'RegistrationEnd']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#RegistrationEnd').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Userid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UserId', 'Userid:') !!}
    {!! Form::text('UserId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>