<!-- Glcode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('GLCode', 'Glcode:') !!}
    {!! Form::text('GLCode', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Neacode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NEACode', 'Neacode:') !!}
    {!! Form::text('NEACode', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Description', 'Description:') !!}
    {!! Form::text('Description', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Amount', 'Amount:') !!}
    {!! Form::text('Amount', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Day Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Day', 'Day:') !!}
    {!! Form::text('Day', null, ['class' => 'form-control','id'=>'Day']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#Day').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Time', 'Time:') !!}
    {!! Form::text('Time', null, ['class' => 'form-control']) !!}
</div>

<!-- Teller Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Teller', 'Teller:') !!}
    {!! Form::text('Teller', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Dcrnumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DCRNumber', 'Dcrnumber:') !!}
    {!! Form::text('DCRNumber', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Status', 'Status:') !!}
    {!! Form::text('Status', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>