@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Reading Monitoring Console | Home</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="card">
                    <div class="card-header border-0">
                        <span class="card-title">Select Period</span>
                    </div>
                    <div class="card-body table-responsive px-0">
                        <table class="table table-hover">
                            <thead>
                                <th>Billing Month</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @if (count($servicePeriods) > 0)
                                    @foreach ($servicePeriods as $item)
                                        <tr>
                                            <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                            <td class="text-right">
                                                <a href="{{ route('readings.reading-monitor-view', [$item->ServicePeriod]) }}"><i class="fas fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <p><i>No readings found</i></p>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection