<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('Name', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500, 'required' => true]) !!}
</div>

<!-- Office Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Office', 'Office:') !!}
    <select name="Office" id="Office" class="form-control">
        <option value="All">All</option>
        @foreach ($towns as $item)
            <option value="{{ $item->id }}" {{ isset($signatories) && $signatories->Office==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
        @endforeach
    </select>
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Designation:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Signature Field -->
<div class="form-group col-sm-6 col-lg-6">
    {!! Form::label('Signature', 'Signature:') !!}
    <br>
    <input type="file" name="RawSign" accept=".jpg,.jpeg,.bmp,.png" required>
</div>

