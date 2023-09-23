<!-- Checklist Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Checklist', 'Checklist Item Name') !!}
    {!! Form::text('Checklist', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Description/Notes') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>