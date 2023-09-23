<!-- Inspector Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-5 col-md-6">
            {!! Form::label('Inspector', 'Inspector') !!}
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                </div>
                <select name="Inspector" id="Inspector" class="form-control">
                    @foreach ($inspectors as $item)
                        <option value="{{ $item->id }}">{{ $item->Name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>  
</div>

<div class="divider"></div>
<br>

<!-- Semaincircuitbreakerasplan Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-5 col-md-6">
            {!! Form::label('SEMainCircuitBreakerAsPlan', 'SE Main Circuit Breaker As Planned') !!}
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-bolt"></i></span>
                </div>
                {!! Form::text('SEMainCircuitBreakerAsPlan', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'placeholder' => 'Main Circuit Breaker in Amps']) !!}
            </div>
        </div>
    </div> 
</div>


<!-- Senoofbranchesasplan Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-5 col-md-6">
            {!! Form::label('SENoOfBranchesAsPlan', 'SE No. Of Branches As Planned') !!}
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                </div>
                {!! Form::text('SENoOfBranchesAsPlan', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'placeholder' => 'SE No. Of Branches As Planned']) !!}
            </div>
        </div>
    </div> 
</div>


<!-- Sdwsizeasplan Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-5 col-md-6">
            {!! Form::label('SDWSizeAsPlan', 'SDW Size As Planned') !!}
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-text-width"></i></span>
                </div>
                {!! Form::text('SDWSizeAsPlan', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'placeholder' => 'Service Drop Wire Size (in mm)']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Sdwlengthasplan Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-5 col-md-6">
            {!! Form::label('SDWLengthAsPlan', 'SDW Length As Planned') !!}
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-ruler"></i></span>
                </div>
                {!! Form::text('SDWLengthAsPlan', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'placeholder' => 'Service Drop Wire Length (in meters)']) !!}
            </div>
        </div>
    </div> 
</div>

<div class="divider"></div>
<br>

<!-- Engineerinchargename Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-5 col-md-6">
            {!! Form::label('EngineerInchargeName', 'Engineer Incharge') !!}
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-hard-hat"></i></span>
                </div>
                {!! Form::text('EngineerInchargeName', null, ['class' => 'form-control','maxlength' => 600,'maxlength' => 600, 'placeholder' => 'Name of Electrical Engineer In Charge']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Engineerinchargetitle Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-5 col-md-6">
            {!! Form::label('EngineerInchargeTitle', 'Engineer Incharge Title') !!}
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-hard-hat"></i></span>
                </div>
                {!! Form::select('EngineerInchargeTitle', ['PEE' => 'PEE', 'EE' => 'EE', 'RME' => 'RME'], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>  
</div>

<!-- Engineerinchargelicenseno Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-5 col-md-6">
            {!! Form::label('EngineerInchargeLicenseNo', 'License No.') !!}
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-hard-hat"></i></span>
                </div>
                {!! Form::text('EngineerInchargeLicenseNo', null, ['class' => 'form-control','maxlength' => 600,'maxlength' => 600, 'placeholder' => 'License Number of Electrical Engineer In Charge']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Engineerinchargelicensevalidity Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-5 col-md-6">
            {!! Form::label('EngineerInchargeLicenseValidity', 'License Validity') !!}
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-hard-hat"></i></span>
                </div>
                {!! Form::text('EngineerInchargeLicenseValidity', null, ['class' => 'form-control','id'=>'EngineerInchargeLicenseValidity']) !!}
            </div>
        </div>
    </div> 
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#EngineerInchargeLicenseValidity').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Engineerinchargecontactno Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-5 col-md-6">
            {!! Form::label('EngineerInchargeContactNo', 'Contact Number') !!}
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                </div>
                {!! Form::text('EngineerInchargeContactNo', null, ['class' => 'form-control','maxlength' => 600,'maxlength' => 600, 'placeholder' => 'Contact Number of Electrical Engineer In Charge']) !!}
            </div>
        </div>
    </div> 
</div>


<!-- Notes Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-5 col-md-6">
            {!! Form::label('Notes', 'Notes/Comments') !!}
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-comments"></i></span>
                </div>
                {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100, 'placeholder' => 'Notes or Comments']) !!}
            </div>
        </div>
    </div> 
</div>