@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit Service Connection Account Types</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::model($serviceConnectionAccountTypes, ['route' => ['serviceConnectionAccountTypes.update', $serviceConnectionAccountTypes->id], 'method' => 'patch']) !!}

            <div class="card-body">
                
                <div class="row">
                    <input type="hidden" name="id" value="{{ $serviceConnectionAccountTypes->id }}">
                    @include('service_connection_account_types.fields')
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('serviceConnectionAccountTypes.index') }}" class="btn btn-default">Cancel</a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
