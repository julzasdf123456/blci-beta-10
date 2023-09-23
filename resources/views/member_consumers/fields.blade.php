@php 
use App\Models\IDGenerator;
@endphp

@if($cond == 'new') 
    <input type="hidden" name="Id" id="Membership_Id" value="{{ IDGenerator::generateID() }}">
@else 

@endif


<!-- Membershiptype Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('MembershipType', 'Membership Type', ['class' => 'right']) !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                </div>
                {!! Form::select('MembershipType', $types, null, ['class' => 'form-control',]) !!}
            </div>
        </div>
    </div>    
</div>

<!-- Non Juridical Group -->
<div id="NonJuridicals" class="col-sm-12">
    <!-- Firstname Field -->
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                {!! Form::label('FirstName', 'First Name') !!}
            </div>

            <div class="col-lg-9 col-md-7">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    {!! Form::text('FirstName', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300, 'placeholder' => 'First Name']) !!}
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
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    {!! Form::text('MiddleName', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300, 'placeholder' => 'Middle Name']) !!}
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
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    {!! Form::text('LastName', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300, 'placeholder' => 'Last Name']) !!}
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
                    {!! Form::select('Gender', ['' => 'Prefer not to state', 'Male' => 'Male', 'Female' => 'Female', 'LGBTQ+' => 'LGBTQ+'], null, ['class' => 'form-control',]) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Organizationname Field -->
<div id="OrgranizationNameModule" class="col-sm-12">
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                {!! Form::label('OrganizationName', 'Entity Name') !!}
            </div>

            <div class="col-lg-9 col-md-7">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-university"></i></span>
                    </div>
                    {!! Form::text('OrganizationName', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000, 'placeholder' => 'Entity Name']) !!}
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                {!! Form::label('OrganizationRepresentative', 'Representative') !!}
            </div>

            <div class="col-lg-9 col-md-7">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    {!! Form::text('OrganizationRepresentative', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000, 'placeholder' => 'Representative']) !!}
                </div>
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
                {!! Form::text('Birthdate', null, ['class' => 'form-control','id'=>'Birthdate', 'placeholder' => 'Birtdate']) !!}
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
                {!! Form::select('Town', $towns, null, ['class' => 'form-control']) !!}
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

<!-- Civilstatus Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('CivilStatus', 'Civil Status') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                </div>
                {!! Form::select('CivilStatus', ['' => 'Not Applicable', 'Single' => 'Single', 'Married' => 'Married', 'Widow' => 'Widow', 'Separated' => 'Separated'], null, ['class' => 'form-control',]) !!}
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
<input type="hidden" name="DateApplied" value="<?= date('Y-m-d') ?>">

<input type="hidden" name="Trashed" value="No">

<input type="hidden" name="ApplicationStatus" value="Pending">

<p id="Def_Brgy" style="display: none;">{{ $memberConsumers==null ? '' : $memberConsumers->Barangay }}</p>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            /**
            * MEMBERSHIP RELATED SCRIPTS
            */

            /**
             * Initialize Juridical fields
             */
            if ($('#MembershipType option:selected').text() == 'Juridical') {
                $('#OrgranizationNameModule').show();
                $('#NonJuridicals').hide();
            } else {
                $('#OrgranizationNameModule').hide();
                $('#NonJuridicals').show();
            }

            $('#MembershipType').on('change', function() {
                if ($('#MembershipType option:selected').text() == 'Juridical') {
                    $('#OrgranizationNameModule').show();
                    $('#NonJuridicals').hide();
                } else {
                    $('#OrgranizationNameModule').hide();
                    $('#NonJuridicals').show();
                }
            });
        })
    </script>
@endpush