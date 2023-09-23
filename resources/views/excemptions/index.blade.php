@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Account Excemptions</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('excemptions.new-excemptions') }}">
                        <i class="fas fa-plus ico-tab"></i>Create New
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title">Billing Month</span>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-sm table-hover">
                            <thead>
                                <th>Select</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($excemptions as $item)
                                    <tr>
                                        <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                        <td>
                                            <a href=""><i class="fas fa-eye"></i></a>
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

