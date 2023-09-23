@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Reading Schedules/Account Grouper</h4>
                    <i class="text-muted">Assigning of reading schedules to accounts</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Districts</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover">
                    <thead>
                        <th>Town Code</th>
                        <th>District</th>
                        <th>No. of Consumers</th>
                        <th width="60px;"></th>
                    </thead>
                    <tbody>
                        @foreach ($towns as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->Town }}</td>
                                <td>{{ $item->ConsumerCount }}</td>
                                <td class="text-right">
                                    <a href="{{ route('serviceAccounts.account-grouper-view', [$item->id]) }}"><i class="fas fa-eye"></i></a>
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