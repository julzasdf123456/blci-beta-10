@php
    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Upload Reading Schedule Text File from Old System</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-6 offset-md-3 col-md-8 offset-md-2">

            @include('flash::message')

            <div class="clearfix"></div>

            <div class="card">
                <form method="POST" enctype="multipart/form-data" action="{{ route('readings.process-uploaded-text-file') }}" >
                <div class="card-header">
                    <span class="card-title">Upload .txt File</span>
                </div>
                <div class="card-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group col-lg-8 offset-lg-2 col-md-12 col-sm-12">
                        {!! Form::label('ServicePeriod', 'Select Billing Month:') !!}
                        <select name="ServicePeriod" id="ServicePeriod" class="form-control">
                            @for ($i = 0; $i < count($months); $i++)
                                <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                            @endfor
                        </select>
                        <span class="text-danger">{{ $errors->first('ServicePeriod') }}</span>
                    </div>

                    <div class="form-group col-lg-8 offset-lg-2 col-md-12 col-sm-12">
                        {!! Form::label('MeterReader', 'Select Meter Reader:') !!}
                        <select name="MeterReader" id="MeterReader" class="form-control">
                            @if (count($meterReaders) > 0)
                                @foreach ($meterReaders as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @else
                                <option value="">No Meter Reader Found</option>
                            @endif
                        </select>
                        <span class="text-danger">{{ $errors->first('MeterReader') }}</span>
                    </div>

                    <div class="form-group col-lg-8 offset-lg-2 col-md-12 col-sm-12">
                        {!! Form::label('ReadingDate', 'Set Reading Date:') !!}
                        <input type="text" class="form-control" name="ReadingDate" id="ReadingDate">
                        @push('page_scripts')
                            <script type="text/javascript">
                                $('#ReadingDate').datetimepicker({
                                    format: 'YYYY-MM-DD',
                                    useCurrent: true,
                                    sideBySide: true
                                })
                            </script>
                        @endpush
                        <span class="text-danger">{{ $errors->first('ReadingDate') }}</span>
                    </div>

                    <div class="form-group col-lg-8 offset-lg-2 col-md-12 col-sm-12">
                        {!! Form::label('file', 'Select Reading Schedule Text (.txt) File:') !!}
                        <br>
                        <input type="file" name="file" placeholder="Choose File" id="file">
                        <span class="text-danger">{{ $errors->first('file') }}</span>
                    </div>
                </div>
                <div class="card-footer">
                    {!! Form::submit('Upload', ['class' => 'btn btn-primary']) !!}
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
