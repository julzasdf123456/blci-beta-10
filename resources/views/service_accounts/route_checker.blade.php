@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Route Checker</h4>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- METER READERS --}}
    <div class="col-lg-8 offset-lg-2">
        <div class="card shadow-none" style="height: 75vh;">
            <div class="card-header">
                <form action="{{ route('serviceAccounts.route-checker') }}" method="GET" class="row">
                    <div class="form-group col-md-5">
                        <input type="number" name="Route" class="form-control form-control-sm" placeholder="Search Route" value="{{ isset($_GET['Route']) ? $_GET['Route'] : '' }}">
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-sm btn-primary">Search</button>
                    </div>
                </form>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <th>Route</th>
                        <th>Meter Reader</th>
                        <th>Day</th>
                        <th>No. of Consumers</th>
                    </thead>
                    <tbody>
                        @if ($data != null)
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->AreaCode }}</td>
                                    <td>{{ $item->name != null ? $item->name : 'No Meter Reader Assigned' }}</td>
                                    <td>{{ $item->GroupCode }}</td>
                                    <td>{{ number_format($item->NoOfConsumers) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection