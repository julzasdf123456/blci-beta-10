@php
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Register New Third Party Collection Partner</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::open(['route' => 'thirdPartyTokens.store']) !!}

            <div class="card-body">

                <div class="row">
                    <input type="hidden" name="id" id="id" value="{{ IDGenerator::generateIDandRandString() }}">
                    <input type="hidden" name="ThirdPartyToken" id="ThirdPartyToken" value="{{ IDGenerator::generateRandString(48) }}">

                    @include('third_party_tokens.fields')
                </div>

            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('thirdPartyTokens.index') }}" class="btn btn-default">Cancel</a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
