@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Account-Reading Day Console</h4>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Groupings for <strong>{{ $town->Town }} District</strong></span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover">
                    <thead>
                        <th>Day</th>
                        <th>Consumers in this Reading Day</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($groupings as $item)
                            <tr>
                                <td>{{ $item->GroupCode == null ? 'Unassigned' : 'Day ' . $item->GroupCode }}</td>
                                <td>{{ number_format($item->ConsumerCount) }}</td>
                                <td class="text-right">
                                    @if ($item->GroupCode != null)
                                        <a href="{{ route('serviceAccounts.account-grouper-edit', [$item->GroupCode, $town->id]) }}" class="btn btn-sm btn-primary">View and Organize</a>
                                    @endif                                    
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