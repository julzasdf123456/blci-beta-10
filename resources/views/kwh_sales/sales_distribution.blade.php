@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Sales Distribution Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-6 offset-lg-3">
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Browse By Billing Months</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover">
                    <thead>
                        <th>Billing Month</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($periods as $item)
                            <tr>
                                <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                <td class="text-right">
                                    <a href="{{ route('kwhSales.sales-distribution-view', [$item->ServicePeriod]) }}"><i class="fas fa-eye"></i></a>
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