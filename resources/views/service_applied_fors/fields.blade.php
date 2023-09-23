<!-- Serviceappliedfor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServiceAppliedFor', 'Service Applied For:') !!}
    {!! Form::text('ServiceAppliedFor', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'autofocus' => true]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Remarks:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>