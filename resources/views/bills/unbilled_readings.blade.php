@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Unbilled Readings</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Statistics</span>
                    </div>
                    <div class="card-body table-resposive px-0">
                        <table class="table table-hover">
                            <thead>
                                <th>Billing Month</th>
                                <th>Reading to Bills Percentage</th>
                                <th width="120px"></th>
                                <th width="80px"></th>
                            </thead>
                            <tbody>
                                @if ($stats != null)
                                    @foreach ($stats as $item)
                                        <tr>
                                            <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                            <td>
                                                <div class="progress progress-xs" style="margin-top: 10px;">
                                                    <div class="progress-bar bg-warning" style="width: {{ $item->Readings > 0 ? ((floatval($item->Bills)/floatval($item->Readings)) * 100) : 100 }}%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-danger">{{ $item->Readings > 0 ? number_format(((floatval($item->Bills)/floatval($item->Readings)) * 100), 2) : 100 }}%</span></td>
                                            <td>
                                                <a href="{{ route('bills.unbilled-readings-console', [$item->ServicePeriod]) }}"><i class="fas fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach                                    
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection