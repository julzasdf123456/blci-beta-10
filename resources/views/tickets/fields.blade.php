
@php
    use App\Models\TicketsRepository;
    use App\Models\Tickets;
@endphp
@if ($cond == 'new')
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                {!! Form::label('ConsumerName', 'Consumer Name:') !!}
            </div>

            <div class="col-lg-9 col-md-7">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                    </div>
                    {!! Form::text('ConsumerName', $serviceAccount==null ? '' : $serviceAccount->ServiceAccountName, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500, 'placeholder' => 'Consumer Name']) !!}
                </div>
            </div>  
        </div> 
    </div>

    <!-- Town Field -->
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                {!! Form::label('Town', 'Town') !!}
            </div>

            <div class="col-lg-9 col-md-7">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    {!! Form::select('Town', $towns, $serviceAccount==null ? '' : $serviceAccount->TownId, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>    
    </div>
@else
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                {!! Form::label('ConsumerName', 'Consumer name:') !!}
            </div>

            <div class="col-lg-9 col-md-7">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                    </div>
                    {!! Form::text('ConsumerName', $tickets->ConsumerName, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500, 'placeholder' => 'Consumer Name']) !!}
                </div>
            </div>  
        </div> 
    </div>

    <!-- Town Field -->
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                {!! Form::label('Town', 'Town') !!}
            </div>

            <div class="col-lg-9 col-md-7">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    {!! Form::select('Town', $towns, $tickets->Town, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>    
    </div>
@endif

<!-- Barangay Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Barangay', 'Barangay') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                {!! Form::select('Barangay', [], null, ['class' => 'form-control',]) !!}
            </div>
        </div>
    </div>    
</div>


@if ($cond == 'new')
    <!-- Sitio Field -->
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                {!! Form::label('Sitio', 'Sitio') !!}
            </div>

            <div class="col-lg-9 col-md-7">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    {!! Form::text('Sitio', $serviceAccount==null ? '' : $serviceAccount->Purok, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000, 'placeholder' => 'Sitio']) !!}
                </div>
            </div>
        </div> 
    </div>
@else
    <!-- Sitio Field -->
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                {!! Form::label('Purok', 'Sitio') !!}
            </div>

            <div class="col-lg-9 col-md-7">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    {!! Form::text('Sitio', $tickets->Sitio, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000, 'placeholder' => 'Sitio']) !!}
                </div>
            </div>
        </div> 
    </div>
@endif

<!-- Contactnumber Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('ContactNumber', 'Contact Number:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                </div>
                {!! Form::text('ContactNumber', "0", ['class' => 'form-control','maxlength' => 100,'maxlength' => 100, 'placeholder' => 'Contact Number']) !!}
            </div>
        </div>
    </div> 
</div>

<div class="divider"></div>
<br>

<!-- Ticket Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Ticket', 'Ticket Type:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                <select class="custom-select select2"  name="Ticket">
                    @foreach ($parentTickets as $items)
                        <optgroup label="{{ $items->Name }}">
                            @php
                                $ticketsRep = TicketsRepository::where('ParentTicket', $items->id)->whereNotIn('Id', Tickets::getMeterRelatedComplainsId())->orderBy('Name')->get();
                            @endphp
                            @foreach ($ticketsRep as $item)
                                <option value="{{ $item->id }}">{{ $item->Name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
        </div>
    </div> 
</div>

<!-- Reason Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Reason', 'Reason:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-file-video"></i></span>
                </div>
                {!! Form::textarea('Reason', null, ['class' => 'form-control','maxlength' => 2000,'maxlength' => 2000, 'placeholder' => 'Reason', 'rows' => 2]) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Reportedby Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('ReportedBy', 'Reported By:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                </div>
                {!! Form::text('ReportedBy', null, ['class' => 'form-control','maxlength' => 200,'maxlength' => 200, 'placeholder' => 'Personnel who reported']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Crewassigned Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('CrewAssigned', 'Crew Assigned:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-hard-hat"></i></span>
                </div>
                {!! Form::select('CrewAssigned', $crew, null, ['class' => 'form-control',]) !!}
            </div>
        </div>
    </div>    
</div>

{{-- <div class="divider"></div>
<br>

<!-- Ornumber Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('ORNumber', 'Ornumber:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-money-check"></i></span>
                </div>
                {!! Form::text('ORNumber', null, ['class' => 'form-control','maxlength' => 200,'maxlength' => 200, 'placeholder' => 'OR Number']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Ordate Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('ORDate', 'Ordate:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-money-check"></i></span>
                </div>
                {!! Form::text('ORDate', null, ['class' => 'form-control', 'placeholder' => 'OR Date']) !!}
            </div>
        </div>
    </div> 
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ORDate').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<div class="divider"></div>
<br> --}}

{{-- GEOLOCATION IS FETCHED FROM SERVICE ACCOUNTS --}}

<!-- Neighbor1 Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Neighbor1', 'Neighbor1:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-street-view"></i></span>
                </div>
                {!! Form::text('Neighbor1', null, ['class' => 'form-control', 'placeholder' => 'Neighbor 1']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Neighbor2 Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Neighbor2', 'Neighbor2:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-street-view"></i></span>
                </div>
                {!! Form::text('Neighbor2', null, ['class' => 'form-control', 'placeholder' => 'Neighbor 2']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Crewassigned Field -->
{{-- <div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('CrewAssigned', 'Crew Assigned:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-hard-hat"></i></span>
                </div>
                {!! Form::select('CrewAssigned', $crew, null, ['class' => 'form-control',]) !!}
            </div>
        </div>
    </div>    
</div> --}}

<!-- Notes Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Notes', 'Notes:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-file-video"></i></span>
                </div>
                {!! Form::textarea('Notes', null, ['class' => 'form-control','maxlength' => 2000,'maxlength' => 2000, 'placeholder' => 'Notes/Remarks', 'rows' => 2]) !!}
            </div>
        </div>
    </div> 
</div>

@if ($cond == 'new')
    <p id="Def_Brgy" style="display: none;">{{ $serviceAccount==null ? '' : $serviceAccount->BarangayId }}</p>
@else
    <p id="Def_Brgy" style="display: none;">{{ $tickets->Barangay }}</p> 
@endif
