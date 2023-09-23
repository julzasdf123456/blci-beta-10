@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Create Account Payables</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                @include('adminlte-templates::common.errors')

                <div class="card">

                    {!! Form::open(['route' => 'accountPayables.store']) !!}

                    <div class="card-body">

                        <div class="row">
                            <input type="hidden" name="id" value="{{ \App\Models\IDGenerator::generateIDandRandString() }}">
                            @include('account_payables.fields')
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('accountPayables.index') }}" class="btn btn-default">Cancel</a>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>        
    </div>
@endsection
