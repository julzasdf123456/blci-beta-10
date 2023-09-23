<!-- Material Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Material', 'Material:') !!}
    {{-- {!! Form::text('Material', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!} --}}
    {!! Form::select('Material', ['Lighting Outlets' => 'Lighting Outlets', 
                                    'Convenience Outlets' => 'Convenience Outlets', 
                                    'Service Entrance' => 'Service Entrance',
                                    'Street Light' => 'Street Light'], null, ['class' => 'form-control']) !!}
</div>

<!-- Rate Field -->
<div class="form-group col-sm-3">
    {!! Form::label('Rate', 'Rate (in Pesos per material):') !!}
    {!! Form::number('Rate', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
</div>

<!-- Vatpercentage Field -->
<div class="form-group col-sm-3">
    {!! Form::label('VatPercentage', 'Vat Percentage (in Decimal form, e.g., 20% is .20):') !!}
    {!! Form::number('VatPercentage', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50, 'step'=>'any']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Description', 'Description:') !!}
    {!! Form::text('Description', null, ['class' => 'form-control','maxlength' => 800,'maxlength' => 800]) !!}
</div>

<!-- BuildingType Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BuildingType', 'Building Type:') !!}
    {{-- {!! Form::text('BuildingType', null, ['class' => 'form-control','maxlength' => 800,'maxlength' => 800]) !!} --}}
    {!! Form::select('BuildingType', ['Concrete' => 'Concrete', 
                                    'Non-Concrete' => 'Non-Concrete', 
                                    'Special Lighting' => 'Special Lighting'], null, ['class' => 'form-control']) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Notes', 'Notes/Remarks:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
</div>