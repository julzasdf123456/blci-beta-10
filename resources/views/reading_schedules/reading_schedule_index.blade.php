@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Meter Reader Scheduler Console</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('readingSchedules.create-reading-schedule') }}"> <i class="fas fa-plus ico-tab"></i>
                        Create New Schedule
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-body table-responsive px-0">
                    <table class="table table-sm table-hover">
                        <thead>
                            <th>Billing Months</th>
                            <th>Schedules Created</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($periods as $item)
                                <tr>
                                    <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                    <td>{{ $item->SchedulesCreated }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('readingSchedules.view-meter-reading-scheds-in-period', [$item->ServicePeriod]) }}" class="btn btn-primary btn-xs"><i class="fas fa-eye ico-tab-mini"></i>View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection