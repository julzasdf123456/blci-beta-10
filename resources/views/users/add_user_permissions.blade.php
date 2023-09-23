@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Set Permissions for {{ $user->name }}</h5>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-md-4 offset-md-4 col-lg-4 offset-lg-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <span><i class="fas fa-lock ico-tab"></i>Confirm and Update Permissions</span>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10 offset-md-1 col-lg-10 offset-lg-1">
                            <form class="form-horizontal" method="POST" action="{{ url('users/create-user-permissions') }}">
                            @csrf
                            @foreach($permissions as $permission) 
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" value="{{ $permission->id }}" name="permissions[]" @if($user->getAllPermissions()) @if(in_array($permission->id, $user->getAllPermissions()->pluck('id')->toArray())) checked @endif @endif>
                                    <label for="permissions" class="form-check-label">{{ $permission->name }}</label>
                                </div>
                            @endforeach
                            <!-- ADD USER ID -->
                            <input type="hidden" name="userId" value="{{ $user->id }}">

                            <br>
                            <!-- SUBMIT -->
                            <button type="submit" class="btn btn-sm btn-info"><i class="fas fa-fw fa-forward"></i> Next</button> 
                            </form>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection