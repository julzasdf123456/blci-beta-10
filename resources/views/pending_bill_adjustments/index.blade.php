@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Zero Reading Adjustments Monitoring</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="card">
                    <div class="card-header border-0">
                        <span class="card-title">Billing Months</span>
                    </div>

                    <div class="card-body table-responsive px-0">
                        <table class="table table-hover">
                            <thead></thead>
                            <tbody>
                                @foreach ($pendingBillAdjustments as $item)
                                    <tr>
                                        <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                        <td class="text-right">
                                            <a class="btn btn-sm btn-primary" href="{{ route('pendingBillAdjustments.open-reading-adjustments', [$item->ServicePeriod]) }}">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

@endsection

