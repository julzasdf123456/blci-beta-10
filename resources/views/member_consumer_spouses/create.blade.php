@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <p><strong><span class="badge-lg bg-success">Step 1.1</span>Register Spouse</strong></p>
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
                        <span>Since the applicant is married, you have the option to add a spouse.</span>
                        <div class="card-tools">
                            <a href="{{ route('serviceConnections.create_new', [$memberConsumer->Id]) }}" class="btn btn-info btn-sm">Skip</a>
                        </div>
                    </div>

                    {!! Form::open(['route' => 'memberConsumerSpouses.store']) !!}

                    <div class="card-body">

                        <div class="row">
                            @include('member_consumer_spouses.fields')
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('memberConsumerSpouses.index') }}" class="btn btn-default">Cancel</a>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
