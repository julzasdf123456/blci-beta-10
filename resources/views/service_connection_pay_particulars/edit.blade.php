@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit Service Connection Pay Particulars</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::model($serviceConnectionPayParticulars, ['route' => ['serviceConnectionPayParticulars.update', $serviceConnectionPayParticulars->id], 'method' => 'patch']) !!}

            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="id" value="{{ $serviceConnectionPayParticulars->id }}">
                    @include('service_connection_pay_particulars.fields')
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('serviceConnectionPayParticulars.index') }}" class="btn btn-default">Cancel</a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
