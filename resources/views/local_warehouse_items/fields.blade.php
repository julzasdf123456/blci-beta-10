<!-- Reqno Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reqno', 'Reqno:') !!}
    {!! Form::text('reqno', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Ent No Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ent_no', 'Ent No:') !!}
    {!! Form::number('ent_no', null, ['class' => 'form-control']) !!}
</div>

<!-- Tdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tdate', 'Tdate:') !!}
    {!! Form::text('tdate', null, ['class' => 'form-control','id'=>'tdate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#tdate').datepicker()
    </script>
@endpush

<!-- Itemcd Field -->
<div class="form-group col-sm-6">
    {!! Form::label('itemcd', 'Itemcd:') !!}
    {!! Form::text('itemcd', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Ascode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ascode', 'Ascode:') !!}
    {!! Form::text('ascode', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Qty Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qty', 'Qty:') !!}
    {!! Form::number('qty', null, ['class' => 'form-control']) !!}
</div>

<!-- Uom Field -->
<div class="form-group col-sm-6">
    {!! Form::label('uom', 'Uom:') !!}
    {!! Form::text('uom', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Cst Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cst', 'Cst:') !!}
    {!! Form::number('cst', null, ['class' => 'form-control']) !!}
</div>

<!-- Amnt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('amnt', 'Amnt:') !!}
    {!! Form::number('amnt', null, ['class' => 'form-control']) !!}
</div>

<!-- Itemno Field -->
<div class="form-group col-sm-6">
    {!! Form::label('itemno', 'Itemno:') !!}
    {!! Form::number('itemno', null, ['class' => 'form-control']) !!}
</div>

<!-- Rdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rdate', 'Rdate:') !!}
    {!! Form::text('rdate', null, ['class' => 'form-control','id'=>'rdate']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#rdate').datepicker()
    </script>
@endpush

<!-- Rtime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rtime', 'Rtime:') !!}
    {!! Form::text('rtime', null, ['class' => 'form-control', 'maxlength' => 50, 'maxlength' => 50]) !!}
</div>

<!-- Salesprice Field -->
<div class="form-group col-sm-6">
    {!! Form::label('salesprice', 'Salesprice:') !!}
    {!! Form::number('salesprice', null, ['class' => 'form-control']) !!}
</div>