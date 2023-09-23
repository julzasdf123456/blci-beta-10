@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Katas Ng VAT</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('katasNgVatTotals.create') }}">
                        Add New Katas
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="row">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="col-lg-6 offset-lg-3">
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title">Recorded Katas Subsidies</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <th>Katas Amount</th>
                            <th>Number of Consumers</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($katas as $item)
                                <tr>
                                    <td>{{ number_format($item->Balance, 2) }}</td>
                                    <td>{{ $item->ConsumerCount }}</td>
                                    <td class="text-right">
                                        <a class="btn btn-sm btn-primary" href="{{ route('katasNgVats.add-katas', [$item->SeriesNo]) }}"><i class="fas fa-eye ico-tab-mini"></i>View</a>
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

