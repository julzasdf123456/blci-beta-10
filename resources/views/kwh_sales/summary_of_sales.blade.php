@php    
    use Illuminate\Support\Facades\DB;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-lg-6">
                <h4>Summary of Sales Per Consumer Type - {{ date('F Y', strtotime($period)) }}</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-header">
                <div class="card-tools">
                    <a href="{{ route('kwhSales.print-summary-of-sales', [$period]) }}" class="btn btn-warning btn-sm"><i class="fas fa-print"></i></a>
                    <a href="{{ route('kwhSales.download-summary-per-consumer-type', [$period]) }}" class="btn btn-success btn-sm" title="Download in Excel Format"><i class="fas fa-download"></i></a>
                </div>                
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center">Classification</th>
                            <th rowspan="2" class="text-center">Number of Consumers</th>
                            <th colspan="2" class="text-center">TOTAL SOLD</th>
                            <th rowspan="2" class="text-center">AMOUNT</th>
                            <th rowspan="2" class="text-center">REAL PROPERTY TAX</th>
                            <th colspan="5" class="text-center">VALUE ADDED TAX</th>
                            <th rowspan="2" class="text-center">TOTAL AMOUNT</th>
                        </tr>
                        <tr>
                            <th class="text-center">KWHR</th>
                            <th class="text-center">KW</th>
                            <th class="text-center">GENERATION</th>
                            <th class="text-center">TRANSMISSION</th>
                            <th class="text-center">SYSTEM LOSS</th>
                            <th class="text-center">DIST./OTHERS</th>
                            <th class="text-center">TOTAL</th>
                        </tr>
                    </thead>
                    @if ($sales != null && $sales->Status=='CLOSED')
                        @include('kwh_sales.closed_summary_of_sales')
                    @else
                        @include('kwh_sales.attach_summary_of_sales')
                    @endif                    
                </table>
            </div>
        </div>
    </div>
</div>
@endsection