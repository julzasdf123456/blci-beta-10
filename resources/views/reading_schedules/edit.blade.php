@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Update Reading Schedule</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                <div class="card">

                    <div class="card-header">
                        <span class="card-title">Update {{ $user->name }}'s Schedule</span>

                        <div class="card-tools">
                            {!! Form::open(['route' => ['readingSchedules.destroy', $readingSchedules->id], 'method' => 'delete']) !!}
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure you want to delete this?')"]) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>

                    {!! Form::model($readingSchedules, ['route' => ['readingSchedules.update', $readingSchedules->id], 'method' => 'patch']) !!}

                    <div class="card-body">
                        <div class="row">
                            @php
                                // GET PREVIOUS MONTHS
                                for ($i = -1; $i <= 12; $i++) {
                                    $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
                                }

                                // GROUPS/DAY
                                $groups = [
                                    '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'
                                ];
                            @endphp

                            <!-- Serviceperiod Field -->
                            <div class="form-group col-sm-6">
                                {!! Form::label('ServicePeriod', 'Select Billing Month') !!}
                                
                                <select name="ServicePeriod" id="ServicePeriod" class="form-control">
                                    @for ($i = 0; $i < count($months); $i++)
                                        <option value="{{ $months[$i] }}" {{ date('Y-m-d', strtotime($readingSchedules->ServicePeriod))==$months[$i] ? 'selected' : '' }}>{{ date('F Y', strtotime($months[$i])) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <!-- Areacode Field -->
                            <div class="form-group col-sm-6">
                                {!! Form::label('AreaCode', 'Area') !!}
                                <select name="AreaCode" id="AreaCode" class="form-control">
                                    @foreach ($towns as $item)
                                        <option value="{{ $item->id }}" {{ $readingSchedules->AreaCode==$item->id ? 'selected' : '' }}>{{ $item->id . ' - ' . $item->Town }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Groupcode Field -->
                            <div class="form-group col-sm-6">
                                {!! Form::label('GroupCode', 'Day/Group') !!}
                                <select name="GroupCode" id="GroupCode" class="form-control">
                                    @for ($i = 0; $i < count($groups); $i++)
                                        <option value="{{ $groups[$i] }}" {{ $readingSchedules->GroupCode==$groups[$i] ? 'selected' : '' }}>{{ $groups[$i] }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Scheduleddate Field -->
                            <div class="form-group col-sm-6">
                                {!! Form::label('ScheduledDate', 'Reading Date') !!}
                                {!! Form::text('ScheduledDate', null, ['class' => 'form-control','id'=>'ScheduledDate']) !!}
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

                            <!-- Status Field -->
                            <div class="form-group col-sm-6">
                                {!! Form::label('Status', 'Status') !!}
                                <select name="Status" id="Status" class="form-control">
                                    <option value="" {{ $readingSchedules->Status==null ? 'selected' : '' }}>Not Yet Downloaded</option>
                                    <option value="Downloaded" {{ $readingSchedules->Status=='Downloaded' ? 'selected' : '' }}>Downloaded</option>
                                </select>
                            </div>

                            <div class="divider"></div>

                            <div class="col-lg-12">
                                <span class="text-muted">3rd-day Reading Rule Unlock Code</span>
                                <p><strong>{{ explode("-", $readingSchedules->id)[0] }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('readingSchedules.index') }}" class="btn btn-default">Cancel</a>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>

        
    </div>
@endsection
