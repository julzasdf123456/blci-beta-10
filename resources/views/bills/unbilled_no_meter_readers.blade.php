@php
    use App\Models\ServiceAccounts;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Unbilled Accounts with No Meter Readers Assigned</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        @include('flash::message')

        <div class="clearfix"></div>
        
        <div class="row">
            {{-- PARAMS --}}
            <div class="col-lg-12 px-1">
                <form class="row" action="{{ route("bills.unbilled-no-meter-readers") }}" method="get">
                    <div class="form-group col-lg-3">
                        <label for="Town">Town</label>
                        <select name="Town" id="Town" class="form-control">
                            <option value="All">All</option>
                            @foreach ($towns as $item)
                                <option value="{{ $item->id }}" {{ isset($_GET['Town']) && $_GET['Town']==$item->id ? 'selected' : '' }}>{{ $item->Town }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="Status">Status</label>
                        <select name="Status" id="Status" class="form-control">
                            <option value="All">All</option>
                            @foreach ($status as $item)
                                <option value="{{ $item->AccountStatus }}" {{ isset($_GET['Status']) && $_GET['Status']==$item->AccountStatus ? 'selected' : '' }}>{{ $item->AccountStatus }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="Action">Action</label><br>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
            {{-- ZERO READINGS --}}
            <div class="col-lg-12">
                <div class="card shadow-none" style="height: 70vh;">
                    <div class="card-header border-0">
                        <span class="card-title">Accounts</span>
                    </div>

                    <div class="card-body table-responsive px-0">
                        <table class="table table-sm table-hover table-bordered">
                            <thead>
                                <th style="width: 30px">#</th>
                                <th>Account No</th>
                                <th>Consumer Name</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Reading Day</th>
                                <th>Account Type</th>
                                <th width="60px"></th>
                            </thead>
                            <tbody>
                                @php
                                    $i=1;
                                @endphp
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td><a href="{{ route('serviceAccounts.show', [$item->id]) }}">{{ $item->OldAccountNo }}</a></td>
                                        <td>{{ $item->ServiceAccountName }}</td>
                                        <td>{{ ServiceAccounts::getAddress($item) }}</td>
                                        <td>{{ $item->AccountStatus }}</td>
                                        <td>{{ $item->GroupCode }}</td>
                                        <td>{{ $item->AccountType }}</td>
                                        <td>
                                            <a class="float-right" href="{{ route('serviceAccounts.update-step-one', [$item->id]) }}"><i class="fas fa-pen"></i></a>
                                        </td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection