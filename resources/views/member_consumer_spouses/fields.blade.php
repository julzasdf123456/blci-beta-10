@php 
use App\Models\IDGenerator;
@endphp

@if($cond == 'new') 
    <input type="hidden" name="id" id="Spouse_Id" value="{{ IDGenerator::generateID() }}">

    <input type="hidden" name="MemberConsumerId" id="MemberConsumerId" value="{{ $memberConsumer->Id }}">

    <p id="Def_Brgy" style="display: none;">{{ $memberConsumer==null ? '' : $memberConsumer->Barangay }}</p>
@else 
    <input type="hidden" name="id" id="Spouse_Id" value="{{ $memberConsumerSpouse->id }}">

    <p id="Def_Brgy" style="display: none;">{{ $memberConsumerSpouse->Barangay }}</p>
@endif

<!-- Firstname Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('FirstName', 'First Name') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user    "></i></span>
                </div>
                {!! Form::text('FirstName', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300, 'placeholder' => 'Spouse First Name']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Middlename Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('MiddleName', 'Middle Name') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user    "></i></span>
                </div>
                {!! Form::text('MiddleName', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300, 'placeholder' => 'Spouse Middle Name']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Lastname Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('LastName', 'Last Name') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user    "></i></span>
                </div>
                {!! Form::text('LastName', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300, 'placeholder' => 'Spouse Last Name']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Suffix Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Suffix', 'Suffix') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                {!! Form::select('Suffix', ['' => 'None', 'JR' => 'JR', 'SR' => 'SR', 'II' => 'II', 'III' => 'III', 'IV' => 'IV', 'V' => 'V'], null, ['class' => 'form-control',]) !!}
            </div>
        </div>
    </div>
</div>

<!-- Gender Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Gender', 'Gender') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                </div>
                {!! Form::select('Gender', ['' => 'Prefer not to state', 'Male' => 'Male', 'Female' => 'Female', 'LGBTQ+' => 'LGBTQ+'], $cond=='new' ? ($memberConsumer->Gender=='Male' ? 'Female' : 'Male') : $memberConsumerSpouse->Gender, ['class' => 'form-control',]) !!}
            </div>
        </div>
    </div>
</div>

<!-- Birthdate Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Birthdate', 'Birthdate') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                </div>
                {!! Form::text('Birthdate', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#Birthdate').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush


<!-- Town Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Town', 'Town') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                {!! Form::select('Town', $towns, $cond=='new' ? $memberConsumer->Town : $memberConsumerSpouse->Town, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>    
</div>

<!-- Barangay Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Barangay', 'Barangay') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                {!! Form::select('Barangay', [], null, ['class' => 'form-control',]) !!}
            </div>
        </div>
    </div>    
</div>

<!-- Sitio Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Sitio', 'Sitio') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                {!! Form::text('Sitio', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000, 'placeholder' => 'Sitio']) !!}
            </div>
        </div>
    </div> 
</div>


<!-- Contactnumbers Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('ContactNumbers', 'Contact Numbers') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                </div>
                {!! Form::text('ContactNumbers', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300, 'placeholder' => 'Contact Numbers']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Emailaddress Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('EmailAddress', 'Email Address') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-envelope-open"></i></span>
                </div>
                {!! Form::text('EmailAddress', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300, 'placeholder' => 'Email Address']) !!}
            </div>
        </div>
    </div> 
</div>



<!-- Religion Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Religion', 'Religion') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-cross"></i></span>
                </div>
                {!! Form::text('Religion', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'placeholder' => 'Religion']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Citizenship Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Citizenship', 'Citizenship') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                </div>
                {!! Form::text('Citizenship', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'placeholder' => 'Citizenship']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Notes Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Notes', 'Notes') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-comments"></i></span>
                </div>
                {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 2000,'maxlength' => 2000, 'placeholder' => 'Notes']) !!}
            </div>
        </div>
    </div>   
</div>

<!-- HIDDEN INPUTS -->


<input type="hidden" name="Trashed" value="No">
