@php
    use App\Models\IDGenerator;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>New Meter Installation for {{ $serviceConnection->ServiceAccountName }}</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card shadow-none">

                    {!! Form::open(['route' => 'meterInstallations.store']) !!}
                    <input type="hidden" name="id" value="{{ IDGenerator::generateID() }}">
                    <input type="hidden" name="ServiceConnectionId" value="{{ $serviceConnection->id }}">
                    <div class="card-body">
        
                        <div class="row">
                            @include('meter_installations.fields')
                        </div>
        
                    </div>
        
                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('meterInstallations.index') }}" class="btn btn-default">Cancel</a>
                    </div>
        
                    {!! Form::close() !!}
        
                </div>
            </div>
        </div>
    </div>
@endsection
