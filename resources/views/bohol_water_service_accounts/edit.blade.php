@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                        Edit Bohol Water Service Accounts
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::model($boholWaterServiceAccounts, ['route' => ['boholWaterServiceAccounts.update', $boholWaterServiceAccounts->id], 'method' => 'patch']) !!}

            <div class="card-body">
                <div class="row">
                    @include('bohol_water_service_accounts.fields')
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('boholWaterServiceAccounts.index') }}" class="btn btn-default"> Cancel </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
