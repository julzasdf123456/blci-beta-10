@extends('layouts.app')

@php
    $permissions = $role->getAllPermissions();
@endphp

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4><span class="badge badge-danger">{{ $role->name }}</span> </h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-sm btn-default float-right"
                       href="{{ route('roles.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            <div class="card-header ui-sortable-handle">
                <h4 class="card-title"><i class="fas fa-lock ico-tab"></i> Permissions</h4>
                <div class="card-tools">
                    <a href="{{ url('/roles/add-permissions/' . $role->id) }}" title="Edit Permissions" class="btn btn-sm btn-link"><i class="fas fa-key"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                   @foreach($permissions as $permission) 
                        <span class="badge {{ $permission->name=='create' ? 'badge-success' : ($permission->name=='update' ? 'badge-info' : ($permission->name  =='delete' ? 'badge-danger' : 'badge-secondary')) }} ico-tab-mini" style="font-size: .9em; font-weight: normal;">
                            <i class="fas {{ $permission->name=='create' ? 'fa-plus-circle' : ($permission->name=='update' ? 'fa-pencil-alt' : ($permission->name  =='delete' ? 'fa-trash' : 'fa-dot-circle')) }} ico-tab-mini"></i>
                            {{ $permission->name }}
                        </span>
                   @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection
