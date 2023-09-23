@php
    use App\Models\ServiceAccounts;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Account Migration Wizzard</h4>
                    <i class="text-muted">Step 1. Validate Consumer Information</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row px-2">
    <div class="col-lg-12">
        <div class="card">
            {!! Form::open(['route' => 'serviceAccounts.store']) !!}
            <div class="card-header">
                <span class="card-title"><strong>Step 1. </strong>Account Information</span>

                <div class="card-tools">
                    <!-- New Account Number Field -->
                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                    </div>
                                    {!! Form::text('id', $serviceConnection->Town . '-' . $serviceConnection->id, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50, 'readonly' => true]) !!}
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>

            <div class="card-body">

                <div class="row">                    
                    {{-- HIDDEN FIELDS --}}
                    <input type="hidden" value="{{ $serviceConnection->ContactNumber }}" name="ContactNumber">
                    <input type="hidden" value="{{ $serviceConnection->EmailAddress }}" name="EmailAddress">
                    <input type="hidden" value="{{ $serviceConnection->id }}" name="ServiceConnectionId">
                    <input type="hidden" value="{{ $inspection->GeoMeteringPole }}" name="GPSMeter">
                    <input type="hidden" value="{{ $serviceConnection->AccountCount }}" name="AccountCount">
                    <input type="hidden" value="{{ $serviceConnection != null ? $serviceConnection->DateTimeOfEnergization : null }}" name="ConnectionDate">
                    <input type="hidden" value="{{ $serviceConnection != null ? $serviceConnection->MemberConsumerId : null }}" name="MemberConsumerId">

                    <input type="hidden" name="Latitude" value="{{ ServiceAccounts::getLatitude($inspection->GeoMeteringPole) }}"/>
                    <input type="hidden" name="Longitude" value="{{ ServiceAccounts::getLongitude($inspection->GeoMeteringPole) }}"/>

                    @include('service_accounts.fields')
                </div>

            </div>

            <div class="card-footer">
                {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('serviceAccounts.index') }}" class="btn btn-default">Cancel</a>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection