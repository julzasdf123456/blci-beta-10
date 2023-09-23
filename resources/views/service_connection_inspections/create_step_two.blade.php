<?php

use App\Models\IDGenerator;

?>

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <p><strong><span class="badge-lg bg-warning">Step 5</span>Service Connection - Inspection and Staking</strong></p>
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
                        <span class="card-title">In-office Initial Assessment</span>
                    </div>

                    {!! Form::open(['route' => 'serviceConnectionInspections.store']) !!}

                    <div class="card-body">

                        <div class="row">
                            <!-- HIDDEN INPUTS -->
                            <input type="hidden" name="id" value="{{ IDGenerator::generateID() }}">

                            <input type="hidden" name="ServiceConnectionId" value="{{ $serviceConnection->id }}">

                            <input type="hidden" name="Status" value="FOR INSPECTION">

                            @include('service_connection_inspections.fields')
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
