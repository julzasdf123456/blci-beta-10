@php
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>
                    Create Material Presets
                    </h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                <div class="card">

                    {!! Form::open(['route' => 'materialPresets.store']) !!}
        
                    <div class="card-body">
        
                        <input type="hidden" name="id" value="{{ IDGenerator::generateIDandRandString() }}">
                        <input name="ServiceConnectionId" type="hidden" value="{{ $scId }}">
                        <div class="row">
                            @include('material_presets.fields')
                        </div>
        
                    </div>
        
                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('materialPresets.index') }}" class="btn btn-default"> Cancel </a>
                    </div>
        
                    {!! Form::close() !!}
        
                </div>
            </div>
        </div>

        
    </div>
@endsection
