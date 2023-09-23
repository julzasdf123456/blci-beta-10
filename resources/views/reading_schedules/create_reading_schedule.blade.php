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
                <div class="col-sm-6">
                    <h4>Create New Reading Schedule</h4>
                </div>
                <div class="col-sm-6">
                    {{-- <a class="btn btn-primary float-right"
                       href="{{ route('readingSchedules.create') }}">
                        Add New
                    </a> --}}
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="card">
                {!! Form::open(['route' => 'readingSchedules.store-reading-schedule']) !!}
                <div class="card-header">
                    <span class="card-title">Setup New Schedule</span>
                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <label for="Area" class="col-md-3">Area</label>
                        <select name="AreaCode" id="" class="form-control col-md-9">
                            @foreach ($towns as $item)
                                <option value="{{ $item->id }}" {{ env('APP_AREA_CODE') == $item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="ServicePeriod" class="col-md-3">Billing Month</label>
                        <select name="ServicePeriod" id="ServicePeriod" class="form-control col-md-9">
                            @for ($i = 0; $i < count($months); $i++)
                                <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group row">
                        <label for="ScheduledDate" class="col-md-5">Starting Reading Date</label>
                        {!! Form::text('ScheduledDate', null, ['class' => 'form-control col-md-7','id'=>'ScheduledDate']) !!}
                    </div>
                    
                    @push('page_scripts')
                        <script type="text/javascript">
                            $('#ScheduledDate').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: true,
                                sideBySide: true
                            })
                        </script>
                    @endpush
                </div>

                <div class="card-footer">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                    <a href="{{ route('readingSchedules.reading-schedule-index') }}" class="btn btn-default">Cancel</a>
                </div>

                {!! Form::close() !!}
            </div>
        </div>        
    </div>
@endsection