<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('Name', 'Name:') !!}
    <p>{{ $ticketsRepository->Name }}</p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('Description', 'Description:') !!}
    <p>{{ $ticketsRepository->Description }}</p>
</div>

<!-- Parentticket Field -->
<div class="col-sm-12">
    {!! Form::label('ParentTicket', 'Parentticket:') !!}
    <p>{{ $ticketsRepository->ParentTicket }}</p>
</div>

<!-- Type Field -->
<div class="col-sm-12">
    {!! Form::label('Type', 'Type:') !!}
    <p>{{ $ticketsRepository->Type }}</p>
</div>

<!-- Kpscategory Field -->
<div class="col-sm-12">
    {!! Form::label('KPSCategory', 'Kpscategory:') !!}
    <p>{{ $ticketsRepository->KPSCategory }}</p>
</div>

