@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Upload New Data</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-6 offset-md-3 col-md-8 offset-md-2">

            @include('flash::message')

            <div class="clearfix"></div>

            <div class="card">
                <form method="POST" enctype="multipart/form-data" action="{{ route('boholWaterServiceAccounts.validate-uploaded-file') }}" >
                <div class="card-header">
                    <span class="card-title">Upload Form</span>
                </div>
                <div class="card-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group col-lg-8 offset-lg-2 col-md-12 col-sm-12">
                        {!! Form::label('file', 'Select BWUI Text File (.txt):') !!}
                        <br>
                        <input type="file" name="file" placeholder="Choose File" id="file">
                        <span class="text-danger">{{ $errors->first('file') }}</span>
                    </div>
                </div>
                <div class="card-footer">
                    {!! Form::submit('Upload', ['class' => 'btn btn-primary']) !!}
                    <a href="{{ route('boholWaterServiceAccounts.upload-file') }}" class="btn btn-default">Done</a>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
