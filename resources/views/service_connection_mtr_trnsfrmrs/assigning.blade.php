@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0">Meter Assigning</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Meter Assigning</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Applications</h3>
                  {{-- <div class="card-tools">
                    <a href="#" class="btn btn-tool btn-sm">
                      <i class="fas fa-download"></i>
                    </a>
                    <a href="#" class="btn btn-tool btn-sm">
                      <i class="fas fa-bars"></i>
                    </a>
                  </div> --}}
                </div>
                <div class="card-body table-responsive p-0">
                    @if ($serviceConnections == null)
                        <p class="text-center"><i>No Service Connection Applications with Unassigned Meters.</i></p>
                    @else
                        <table class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Service Account Name</th>
                                    <th>Address</th>
                                    <th>Account Type</th>
                                    <th width="35"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($serviceConnections as $item)
                                    <tr>
                                        <td><a href="{{ route('serviceConnections.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                        <td>{{ $item->ServiceAccountName }}</td>
                                        <td>{{ ServiceConnections::getAddress($item) }}</td>
                                        <td>{{ $item->AccountType }}</td>
                                        <td>
                                            <a href="{{ route('serviceConnectionMtrTrnsfrmrs.create-step-three', [$item->id]) }}" class="text-muted" title="Proceed Assigning"> <i class="fas fa-arrow-alt-circle-right"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach                                
                            </tbody>
                        </table>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection