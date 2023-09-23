@php
    use App\Models\IDGenerator;
    use Illuminate\Support\Facades\Auth; 
@endphp

<!-- Balance Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Balance', 'Katas Ng VAT Amount:') !!}
    {!! Form::number('Balance', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}
</div>

<!-- Seriesno Field -->
<input type="hidden" name="SeriesNo" id="SeriesNo" value="{{ IDGenerator::generateID() }}">

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Description', 'Description:') !!}
    {!! Form::text('Description', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>

<!-- Year Field -->
<input type="hidden" name="Year" id="Year" value="{{ date('Y') }}">

<!-- Userid Field -->
<input type="hidden" name="UserId" id="UserId" value="{{ Auth::id() }}">

<!-- Notes Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
</div>