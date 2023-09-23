@php
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Create Tickets Repository</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::open(['route' => 'ticketsRepositories.store']) !!}

            <div class="card-body">

                <div class="row">
                    <input type="hidden" name="id" value="{{ IDGenerator::generateID() }}">
                    <!-- Name Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('Name', 'Name:') !!}
                        {!! Form::text('Name', null, ['class' => 'form-control','maxlength' => 600,'maxlength' => 600]) !!}
                    </div>

                    <!-- Description Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('Description', 'Description:') !!}
                        {!! Form::text('Description', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
                    </div>

                    <!-- Parentticket Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('ParentTicket', 'Parent Ticket:') !!}
                        {!! Form::select('ParentTicket', $parentReps, null, ['class' => 'form-control', 'placeholder' => 'This is a parent ticket']) !!}
                    </div>

                    <!-- Type Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('Type', 'Type:') !!}
                        {!! Form::select('Type', ['Request' => 'Request', 'Complain' => 'Complain'], null, ['class' => 'form-control', 'placeholder' => 'This is a parent ticket']) !!}
                    </div>

                    <!-- Kpscategory Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('KPSCategory', 'KPS Category:') !!}
                        {!! Form::number('KPSCategory', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
                    </div>

                    <input type="hidden" value="2021" name="KPSIssue">
                </div>

            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('ticketsRepositories.index') }}" class="btn btn-default">Cancel</a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
