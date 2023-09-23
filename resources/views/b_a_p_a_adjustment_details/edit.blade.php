@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit B A P A Adjustment Details</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::model($bAPAAdjustmentDetails, ['route' => ['bAPAAdjustmentDetails.update', $bAPAAdjustmentDetails->id], 'method' => 'patch']) !!}

            <div class="card-body">
                <div class="row">
                    @include('b_a_p_a_adjustment_details.fields')
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('bAPAAdjustmentDetails.index') }}" class="btn btn-default">Cancel</a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
