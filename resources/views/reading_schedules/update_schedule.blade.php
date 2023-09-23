@php
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Reading Schedule Wizzard</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                <div class="card">

                    {!! Form::open(['route' => 'readingSchedules.store']) !!}

                    <div class="card-header">
                        <span class="card-title">Create new reading schedule for {{ $user->name }}</span>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            {{-- HIDDEN FIELDS --}}
                            <input type="hidden" name="id" value="{{ IDGenerator::generateIDandRandString() }}">
                            <input type="hidden" value="{{ $user->id }}" name="MeterReader">

                            @include('reading_schedules.fields')
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('readingSchedules.index') }}" class="btn btn-default">Cancel</a>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>

        
    </div>
@endsection
