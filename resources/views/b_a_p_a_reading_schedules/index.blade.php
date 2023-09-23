@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>BAPA Reading Schedules</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('bAPAReadingSchedules.create') }}">
                        Create New Schedule
                    </a>
                </div>
            </div>
        </div>
    </section>

<div class="row">

    @include('flash::message')

    <div class="clearfix"></div>

    <div class="col-lg-6 offset-lg-3 col-md-12">
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Existing Schedules</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>Billing Month</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($periods as $item)
                            <tr>
                                <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                <td class="text-right">
                                    <a href="{{ route('bAPAReadingSchedules.show-schedules', [$item->ServicePeriod]) }}" style="margin-right: 20px;"><i class="fas fa-eye"></i></a>
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

