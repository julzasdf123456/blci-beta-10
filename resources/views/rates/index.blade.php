@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Rate Management Console</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('rates.upload-rate') }}">
                        <i class="fas fa-file-upload ico-tab"></i>Upload Rate
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                @include('flash::message')

                <div class="clearfix"></div>

                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Current Rates</span>
                    </div>
                    <div class="card-body table-responsive px-0">                        
                        <table class="table table-hover">
                            <thead>
                                <th>Billing Period</th>
                                <th width="8%"></th>
                            </thead>
                            <tbody>
                                @foreach ($rates as $item)
                                    <tr>
                                        <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                        <td>
                                            <a href="{{ route('rates.view-rates', [$item->ServicePeriod]) }}" class="float-right"><i class="fas fa-eye"></i></a>
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

