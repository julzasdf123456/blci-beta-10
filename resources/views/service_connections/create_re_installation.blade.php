<?php

use App\Models\MemberConsumers;
use App\Models\ServiceConnections;
use App\Models\IDGenerator;

?>

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <p>Create Re-Installation Application</p>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12">
            <div class="content px-3">


                @include('adminlte-templates::common.errors')

                <div class="card">

                    <div class="card-header">
                        {{-- <span class="card-title">Step <strong>1</strong> of 4 - <strong>Applicant Basic Account Info</strong></span> --}}
                        <span class="card-title">Applicant Basic Account Info</strong></span>
                    </div>

                    {!! Form::open(['route' => 'serviceConnections.store']) !!}

                    <div class="card-body">

                        <div class="row">
                            <input type="hidden" name="Status" value="For Inspection">

                            <input type="hidden" name="Office" value="{{ env("APP_LOCATION") }}">

                            
                            @if($cond == 'new') 
                                <input type="hidden" name="id" id="Membership_Id" value="{{ IDGenerator::generateID() }}">

                                <input type="hidden" name="ConnectionApplicationType" id="ConnectionApplicationType" value="Re-Installation">

                                <!-- Accountapplicationtype Field -->
                                <div class="form-group col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-5">
                                            {!! Form::label('AccountApplicationType', 'Application Type') !!}
                                        </div>

                                        <div class="col-lg-9 col-md-7"> 
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                                </div>

                                                <div class="radio-group-horizontal">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="AccountApplicationType" value="Permanent" checked>
                                                        <label class="form-check-label">Permanent</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="AccountApplicationType" value="Temporary">
                                                        <label class="form-check-label">Temporary</label>
                                                    </div>
                                                </div>                    
                                            </div>
                                        </div>
                                    </div>  
                                </div>

                                @push('page_scripts')
                                    <script type="text/javascript">
                                        $(document).ready(function(){
                                            $('#duration').hide();
                                            $('#TemporaryDurationInMonths').val('');
                                        });
                                        $("input[name='AccountApplicationType']").change(function() {
                                            if (this.value == 'Permanent') {
                                                // alert('Permanent');
                                                $('#duration').hide();
                                                $('#TemporaryDurationInMonths').val('');
                                            } else {
                                                // alert('Temporary');
                                                $('#duration').show();
                                                $('#TemporaryDurationInMonths').val('3');
                                            }
                                        });
                                    </script>
                                @endpush

                                {{-- Temporary Duration Field --}}
                                <div class="form-group col-sm-12" id="duration">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-5">
                                            {!! Form::label('TemporaryDurationInMonths', 'Duration (in Months)') !!}
                                        </div>

                                        <div class="col-lg-9 col-md-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                                {!! Form::text('TemporaryDurationInMonths', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
                                            </div>
                                        </div>
                                    </div> 
                                </div>

                                <!-- Accounttype Field -->
                                <div class="form-group col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-5">
                                            {!! Form::label('AccountType', 'Classification of Service') !!}
                                        </div>

                                        <div class="col-lg-9 col-md-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                                </div>

                                                <div class="radio-group">
                                                    @if ($accountTypes != null)
                                                        @foreach ($accountTypes as $item)
                                                        <div class="form-check" style="margin-left: 30px;">
                                                            <input class="form-check-input" type="radio" name="AccountType" value="{{ $item->id }}" {{ $item->AccountType=='RESIDENTIAL' ? 'checked' : '' }}>
                                                            <label class="form-check-label">{{ $item->AccountType }}</label>
                                                        </div>
                                                        @endforeach
                                                    @endif
                                                </div> 
                                            </div>
                                        </div>
                                    </div>  
                                </div>

                                <!-- Accountapplicationtype Field -->
                                <div class="form-group col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-5">
                                            {!! Form::label('LoadCategory', 'Projected Load') !!}
                                        </div>

                                        <div class="col-lg-9 col-md-7"> 
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                                </div>

                                                <div class="radio-group-horizontal">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="LoadCategory" value="below 5kVa" checked>
                                                        <label class="form-check-label">Below 5KVA</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="LoadCategory" value="above 5kVa">
                                                        <label class="form-check-label">Above 5KVA</label>
                                                    </div>
                                                </div>                    
                                            </div>
                                        </div>
                                    </div>  
                                </div>

                                <!-- LongSpan Field -->
                                {{-- <div class="form-group col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-5">
                                            {!! Form::label('LongSpan', 'Spanning') !!}
                                        </div>

                                        <div class="col-lg-9 col-md-7"> 
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                                </div>

                                                <div class="radio-group-horizontal">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="LongSpan" value="Yes" >
                                                        <label class="form-check-label">Above 70 meters</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="LongSpan" value="No" checked>
                                                        <label class="form-check-label">Below 70 meters</label>
                                                    </div>
                                                </div>                    
                                            </div>
                                        </div>
                                    </div>  
                                </div> --}}

                                <!-- SEP Field -->
                                <div class="form-group col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-5">
                                            {!! Form::label('IsNIHE', 'SEP') !!}
                                        </div>

                                        <div class="col-lg-9 col-md-7"> 
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                                </div>

                                                <div class="radio-group-horizontal">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="IsNIHE" value="YES" >
                                                        <label class="form-check-label">YES</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="IsNIHE" value="NO" checked>
                                                        <label class="form-check-label">NO</label>
                                                    </div>
                                                </div>                    
                                            </div>
                                        </div>
                                    </div>  
                                </div>

                                <!-- TypeOfOccupancy Field -->
                                <div class="form-group col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-5">
                                            {!! Form::label('TypeOfOccupancy', 'Type of Occupancy: ') !!}
                                        </div>

                                        <div class="col-lg-9 col-md-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                                </div>
                                                <div class="radio-group-horizontal">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="TypeOfOccupancy" value="Owns House/Lot" checked>
                                                        <label class="form-check-label">Owns House/Lot</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="TypeOfOccupancy" value="Owns House">
                                                        <label class="form-check-label">Owns House</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="TypeOfOccupancy" value="Leasing/Renting">
                                                        <label class="form-check-label">Leasing/Renting</label>
                                                    </div>
                                                </div>   
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            @else 
                                <!-- Accounttype Field -->
                                <div class="form-group col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-5">
                                            {!! Form::label('AccountType', 'Classification of Service') !!}
                                        </div>

                                        <div class="col-lg-9 col-md-7">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                                </div>

                                                <div class="radio-group">
                                                    @if ($accountTypes != null)
                                                        @foreach ($accountTypes as $item)
                                                        <div class="form-check" style="margin-left: 30px;">
                                                            <input class="form-check-input" type="radio" name="AccountType" value="{{ $item->id }}">
                                                            <label class="form-check-label">{{ $item->AccountType }}</label>
                                                        </div>
                                                        @endforeach
                                                    @endif
                                                </div> 
                                            </div>
                                        </div>
                                    </div>  
                                </div>

                                <input type="hidden" name="id" id="Membership_Id" value="{{ $serviceConnections->id }}">
                            @endif

                            @push('page_scripts')
                            <script type="text/javascript">
                                $('#DateOfApplication').datetimepicker({
                                    format: 'YYYY-MM-DD',
                                    useCurrent: true,
                                    sideBySide: true
                                })
                            </script>
                            @endpush

                            <div class="divider"></div>
                            <br>

                            <!-- Serviceaccountname Field -->
                            <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('ServiceAccountName', 'Service Account Name') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                        </div>
                                        {!! Form::text('ServiceAccountName', $cond=='new' ? ($account != null ? $account->ServiceAccountName : '') : '', ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
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
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        </div>
                                        {!! Form::select('Town', $towns, $cond=='new' ? ($account != null ? $account->TownId : '') : '', ['class' => 'form-control']) !!}
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
                                        {!! Form::text('Sitio', $cond=='new' ?  ($account != null ? $account->Purok : '') : '', ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000, 'placeholder' => 'Sitio']) !!}
                                    </div>
                                </div>
                            </div> 
                            </div>

                            <!-- Contactnumbers Field -->
                            <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('ContactNumber', 'Contact Numbers') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                        </div>
                                        {!! Form::text('ContactNumber', '0', ['class' => 'form-control','maxlength' => 300,'maxlength' => 300, 'placeholder' => 'Contact Numbers']) !!}
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
                                        {!! Form::text('EmailAddress', '', ['class' => 'form-control','maxlength' => 300,'maxlength' => 300, 'placeholder' => 'Email Address']) !!}
                                    </div>
                                </div>
                            </div> 
                            </div>

                            <!-- Residence Number Field -->
                            <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('ResidenceNumber', 'Residence Number') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-store"></i></span>
                                        </div>
                                        {!! Form::text('ResidenceNumber', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300, 'placeholder' => 'Residence Number']) !!}
                                    </div>
                                </div>
                            </div> 
                            </div>

                            <div class="divider"></div>
                            <br>

                            <!-- Accountorganization Field -->
                            <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('AccountOrganization', 'Account Classification') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
                                        </div>
                                        {!! Form::select('AccountOrganization', ['Individual' => 'Individual', 'BAPA' => 'BAPA'], $cond=='new' ? '' : $serviceConnections->AccountOrganization, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>  
                            </div>

                            <!-- Organizationaccountnumber Field -->
                            <div id="organizationNo" class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('OrganizationAccountNumber', 'Organization Account No') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                        </div>
                                        {!! Form::text('OrganizationAccountNumber', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100, 'placeholder' => 'Organization Account Number']) !!}
                                    </div>
                                </div>
                            </div> 
                            </div>

                            @push('page_scripts')
                            <script type="text/javascript">
                                $('#AccountOrganization').on('change', function() {
                                    if (this.value == 'Individual') {
                                        $('#organizationNo').hide();
                                    } else {
                                        $('#organizationNo').show();
                                    }
                                });
                            </script>
                            @endpush

                            <!-- Station Field -->
                            <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('StationCrewAssigned', 'Station Crew') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-hard-hat"></i></span>
                                        </div>
                                        {!! Form::select('StationCrewAssigned', $crew, $cond=='new' ? '' : $serviceConnections->StationCrewAssigned, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>  
                            </div>

                            <!-- BuildingType Field -->
                            <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('BuildingType', 'Building Type') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                        </div>
                                        {!! Form::select('BuildingType', ['Concrete' => 'Concrete', 'Non-Concrete' => 'Non-Concrete', 'Special Lighting' => 'Special Lighting'], $cond=='new' ? '' : $serviceConnections->BuildingType, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>  
                            </div>

                            <!-- Dateofapplication Field -->
                            <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('DateOfApplication', 'Date of Application') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                        </div>
                                            {!! Form::text('DateOfApplication', $cond=='new' ? date('Y-m-d') : $serviceConnections->DateOfApplication, ['class' => 'form-control','id'=>'DateOfApplication']) !!}
                                    </div>
                                </div>
                            </div> 
                            </div>


                            <!-- Isnihe Field -->
                            <input type="hidden" name="IsNIHE" value="NO">

                            <!-- Notes Field -->
                            <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('Notes', 'Notes/Comments') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-comments"></i></span>
                                        </div>
                                        {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100, 'placeholder' => 'Notes or Comments']) !!}
                                    </div>
                                </div>
                            </div> 
                            </div>


                            <div class="divider"></div>
                            <br>

                            {{-- OR UPDATING ON ADMINS --}}
                            <!-- OR Field -->
                            <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('ORNumber', 'OR Number') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                        </div>
                                        {!! Form::text('ORNumber', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000, 'placeholder' => 'OR Number']) !!}
                                    </div>
                                </div>
                            </div> 
                            </div>

                            <!-- OR Date Field -->
                            <div class="form-group col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    {!! Form::label('ORDate', 'Payment Date') !!}
                                </div>

                                <div class="col-lg-9 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                        </div>
                                            {!! Form::text('ORDate', null, ['class' => 'form-control','id'=>'ORDate']) !!}
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

                            <p id="Def_Brgy" style="display: none;">{{ $cond=='new' ? $account->BarangayId : $serviceConnections->Barangay }}</p>
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}
                    </div>

                    {!! Form::close() !!}

                </div>
             </div>
        </div>
    </div>
@endsection
