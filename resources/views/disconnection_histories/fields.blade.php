<!-- Serviceperiod Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ServicePeriod', 'Billing Month:') !!}
    <select name="ServicePeriod" id="ServicePeriod" class="form-control">
        @foreach ($periods as $item)
            <option value="{{ $item->ServicePeriod }}">{{ date('F Y', strtotime($item->ServicePeriod)) }}</option>
        @endforeach
    </select>
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>