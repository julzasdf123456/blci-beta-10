@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Edit Meter Installation</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-12">
                <div class="card shadow-none">

                    {!! Form::model($meterInstallation, ['route' => ['meterInstallations.update', $meterInstallation->id], 'method' => 'patch']) !!}
        
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
