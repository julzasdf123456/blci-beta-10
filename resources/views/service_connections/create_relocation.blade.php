@php
    use App\Models\IDGenerator;
    use App\Models\ServiceAccounts;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4>New Relocation</h4>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-8 offset-lg-2 col-md-12">
        {!! Form::open(['route' => 'serviceConnections.store']) !!}
        <div class="card">
            <div class="card-body">
                <input type="hidden" name="ConnectionApplicationType" value="Relocation">

                <input type="hidden" name="id" value="{{ IDGenerator::generateID() }}">

                <!-- Account Number Field -->
                <div class="form-group col-sm-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-5">
                            {!! Form::label('AccountNumber', 'Account ID') !!}
                        </div>

                        <div class="col-lg-9 col-md-7">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                </div>
                                {!! Form::text('AccountNumber', $account->id, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'readonly' => 'true']) !!}
                            </div>
                        </div>
                    </div> 
                </div>

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
                                {!! Form::text('ServiceAccountName', $account->ServiceAccountName, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'readonly' => 'true']) !!}
                            </div>
                        </div>
                    </div> 
                </div>

                <!-- FROM Field -->
                <div class="form-group col-sm-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-5">
                            {!! Form::label('From', 'From Address') !!}
                        </div>

                        <div class="col-lg-9 col-md-7">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                </div>
                                {!! Form::text('From', ServiceAccounts::getAddress($account), ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000, 'placeholder' => 'From']) !!}
                            </div>
                        </div>
                    </div> 
                </div>

                <div class="col-lg-12">
                    <div class="divider"></div>
                    <p class="text-muted"><i>To</i></p>
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
                                {!! Form::select('Town', $towns, $account != null ? $account->Town : '', ['class' => 'form-control']) !!}
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
                                {!! Form::text('Sitio', '', ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000, 'placeholder' => 'Sitio']) !!}
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
                                {!! Form::text('ContactNumber', $account != null && $account->ContactNumber != null ? $account->ContactNumber : '0', ['class' => 'form-control','maxlength' => 300,'maxlength' => 300, 'placeholder' => 'Contact Numbers']) !!}
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
                                {!! Form::select('StationCrewAssigned', $crew, null, ['class' => 'form-control']) !!}
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
                                {!! Form::text('DateOfApplication', date('Y-m-d'), ['class' => 'form-control','id'=>'DateOfApplication']) !!}
                                @push('page_scripts')
                                    <script type="text/javascript">
                                        $('#DateOfApplication').datetimepicker({
                                            format: 'YYYY-MM-DD',
                                            useCurrent: true,
                                            sideBySide: true
                                        })
                                    </script>
                                @endpush
                            </div>
                        </div>
                    </div> 
                </div>

                <!-- Notes Field -->
                <div class="form-group col-sm-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-5">
                            {!! Form::label('Notes', 'Reason for Relocation') !!}
                        </div>

                        <div class="col-lg-9 col-md-7">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-comments"></i></span>
                                </div>
                                {!! Form::textarea('Notes', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000, 'placeholder' => 'Type reason', 'rows' => '2']) !!}
                            </div>
                        </div>
                    </div> 
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}
                <!-- <a href="{{ route('serviceConnections.index') }}" class="btn btn-default">Cancel</a> -->
            </div>

        {!! Form::close() !!}   
        </div>
    </div>
    
</div>

<p id="Def_Brgy" style="display: none;">{{ $account->Barangay }}</p>
@endsection