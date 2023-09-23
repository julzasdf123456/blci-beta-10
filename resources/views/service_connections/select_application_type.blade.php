@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <p><strong><span class="badge-lg bg-warning">Step 3</span>Apply for Service Connection</strong></p>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-12">
            <div class="content px-3">

                @include('adminlte-templates::common.errors')

                <div class="card">
                    <div class="card-header">
                        <span><strong>Select account application type</strong></span>

                        
                        <div class="card-tools">
                            <a href="{{ route('memberConsumers.show', [$consumerId]) }}">Skip For Now</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            {!! Form::open(['route' => ['serviceConnections.relay-account-type', $consumerId], 'class' => "form-horizontal"]) !!}
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" value="Permanent">
                                    <label class="form-check-label">Residential and Small Commercials</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" value="Temporary">
                                    <label class="form-check-label">Temporary Connection</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" value="Large Load">
                                    <label class="form-check-label">Large Load</label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Continue', ['class' => 'btn btn-primary']) !!}
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
    
@endsection
