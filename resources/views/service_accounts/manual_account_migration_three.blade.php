@php
    use App\Models\ServiceConnections;
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Account Migration Wizzard</h4>
                    <i class="text-muted">Step 3. Import transformer details</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- INFO CARDS --}}
    <div class="col-lg-3 col-md-4">
        {{-- CONSUMER INFO --}}
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Consumer Info</span>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover table-borderless table-sm">
                    <tr>
                        <th><i class="fas fa-user-circle ico-tab"></i>{{ $serviceAccount->ServiceAccountName }}</th>
                    </tr>
                    <tr>
                        <td><i class="fas fa-map-marker-alt ico-tab"></i>{{ ServiceConnections::getAddress($serviceAccount) }}</td>
                    </tr>
                    <tr>
                        <td title="Account Number Format: New Account Number (Old/Legacy Account Number)"><i class="fas fa-user-alt ico-tab"></i>{{ $serviceAccount->id }} ({{ $serviceAccount->OldAccountNo }})</td>
                    </tr>
                    <tr>
                        <td title="Area Code"><i class="fas fa-hashtag ico-tab"></i>{{ $serviceAccount->AreaCode }}</td>
                    </tr>
                    <tr>
                        <td title="Sequence Number"><i class="fas fa-hashtag ico-tab"></i>{{ $serviceAccount->SequenceCode }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- METER INFO --}}
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Meter Info</span>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body table-responsive">
                @if ($meters != null)
                    <table class="table table-hover table-borderless table-sm">
                        <tr>
                            <td>Brand</td>
                            <td>{{ $meters->Brand }}</td>
                        </tr>
                        <tr>
                            <td>Serial No</td>
                            <td>{{ $meters->SerialNumber }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{{ $meters->Status }}</td>
                        </tr>
                        <tr>
                            <td>Multiplier</td>
                            <td>{{ $meters->Multiplier }}</td>
                        </tr>
                        <tr>
                            <td>Connection Date</td>
                            <td>{{ $meters->ConnectionDate != null ? date('F d, Y', strtotime($meters->ConnectionDate)) : '' }}</td>
                        </tr>
                    </table>                    
                @endif
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <div class="col-lg-9 col-md-8">
        <div class="card">
            <div class="card-header">
                <span class="card-title"><strong>Step 3. </strong>Transformer Information</span>
            </div>
            {!! Form::open(['route' => 'serviceAccounts.store-transformer-manual']) !!}
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="ServiceAccountId" value="{{ $serviceAccount->id }}">                    
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Transformernumber Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('TransformerNumber', 'Transformer Number:') !!}
                            </div>

                            <div class="col-lg-5 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('TransformerNumber', null, ['class' => 'form-control','maxlength' => 120,'maxlength' => 120]) !!}
                                </div>
                            </div>

                            <!-- Rating Field -->
                            <div class="col-lg-2 col-md-2">
                                {!! Form::label('Rating', 'Rating (in KVA):') !!}
                            </div>

                            <div class="col-lg-3 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('Rating', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20]) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Rentalfee Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('RentalFee', 'Rental Fee:') !!}
                            </div>

                            <div class="col-lg-3 col-md-4">
                                <div class="input-group">
                                    {!! Form::number('RentalFee', null, ['class' => 'form-control','maxlength' => 30,'maxlength' => 30, 'step' => 'any']) !!}
                                </div>
                            </div>

                            <!-- Load Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('Load', 'Load:') !!}
                            </div>

                            <div class="col-lg-3 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('Load', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
                                </div>
                            </div>

                            <!-- CoreLoss Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('Coreloss', 'Coreloss:') !!}
                            </div>

                            <div class="col-lg-3 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('Coreloss', null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50, 'step' => 'any']) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="divider"></div>

                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Main Field -->
                            <div class="col-lg-1 col-md-1">
                                {!! Form::label('Main', 'Main:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    {{ Form::checkbox('Main', 'Yes', false, ['class' => 'custom-checkbox']) }}
                                </div>
                            </div>

                            <!-- 5% Field -->
                            <div class="col-lg-1 col-md-1">
                                {!! Form::label('Evat5Percent', '5% EVAT:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    {{ Form::checkbox('Evat5Percent', 'Yes', false, ['class' => 'custom-checkbox']) }}
                                </div>
                            </div>

                            <!-- 2% Field -->
                            <div class="col-lg-1 col-md-1">
                                {!! Form::label('Ewt2Percent', '2% EWT:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    {{ Form::checkbox('Ewt2Percent', 'Yes', false, ['class' => 'custom-checkbox']) }}
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="divider"></div>

                    <div class="form-group col-lg-12">
                        <div class="row">
                            <!-- BAPA Field -->
                            <div class="col-lg-1 col-md-1">
                                {!! Form::label('BAPA', 'BAPA:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    {{ Form::checkbox('BAPA', 'BAPA', false, ['class' => 'custom-checkbox', 'id' => 'BAPA']) }}
                                </div>
                            </div>

                            <!-- OrganizationParentAccount/BAPA NAME Field -->
                            <div class="col-lg-1 col-md-1">
                                {!! Form::label('OrganizationParentAccount', 'Select BAPA:') !!}
                            </div>

                            <div class="col-lg-3 col-md-4">
                                <div class="input-group">
                                    <select class="custom-select select2"  name="OrganizationParentAccount" id="OrganizationParentAccount" disabled>
                                        <option value="NULL">-- Select --</option>
                                        @foreach ($bapa as $item)
                                            <option value="{{ $item->OrganizationParentAccount }}">{{ $item->OrganizationParentAccount }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    @push('page_scripts')
                        <script>
                            $(document).ready(function() {
                                checkBapa()

                                $('#BAPA').change(function() {
                                    checkBapa()
                                })
                            })

                            function checkBapa() {
                                if ($('#BAPA').is(':checked')) {
                                    $('#OrganizationParentAccount').removeAttr('disabled')
                                } else {
                                    $('#OrganizationParentAccount').attr('disabled', 'disabled')
                                    $("#OrganizationParentAccount").val("NULL").change()
                                }
                            }
                        </script>
                    @endpush


                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit('Finish', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection