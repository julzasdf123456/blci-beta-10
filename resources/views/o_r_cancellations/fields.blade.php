<!-- Ornumber Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORNumber', 'Ornumber:') !!}
    {!! Form::text('ORNumber', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Ordate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ORDate', 'Ordate:') !!}
    {!! Form::text('ORDate', null, ['class' => 'form-control','id'=>'ORDate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ORDate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- From Field -->
<div class="form-group col-sm-6">
    {!! Form::label('From', 'From:') !!}
    {!! Form::text('From', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Objectid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ObjectId', 'Objectid:') !!}
    {!! Form::text('ObjectId', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Datetimefiled Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateTimeFiled', 'Datetimefiled:') !!}
    {!! Form::text('DateTimeFiled', null, ['class' => 'form-control','id'=>'DateTimeFiled']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateTimeFiled').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Datetimeapproved Field -->
<div class="form-group col-sm-6">
    {!! Form::label('DateTimeApproved', 'Datetimeapproved:') !!}
    {!! Form::text('DateTimeApproved', null, ['class' => 'form-control','id'=>'DateTimeApproved']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateTimeApproved').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush