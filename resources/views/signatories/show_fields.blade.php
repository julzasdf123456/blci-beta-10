<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('Name', 'Name:') !!}
    <p>{{ $signatories->Name }}</p>
</div>

<!-- Office Field -->
<div class="col-sm-12">
    {!! Form::label('Office', 'Office:') !!}
    <p>{{ $signatories->Office }}</p>
</div>

<!-- Signature Field -->
<div class="col-sm-12">
    {!! Form::label('Signature', 'Signature:') !!}
    <img src="{{ $signatories->Signature }}" alt="" style="height: 160px;">
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    <p>{{ $signatories->Notes }}</p>
</div>

