@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Set Roles for {{ $user->name }}</h5>
                </div>
            </div>
        </div>
    </section>
    
    <div>
        <form class="form" method="POST" action="{{ url('users/create-user-roles') }}">

            <div class="row">

                <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <span class="card-title">Customize Roles</span>
                        </div>

                        <div class="card-body">
                            @csrf
                            @foreach($roles as $role)  
                                <div class="form-check">  
                                    <input type="checkbox" class="form-check-input" value="{{ $role->id }}" name="roles[]"  @if($user->roles) @if(in_array($role->id, $user->roles->pluck('id')->toArray())) checked @endif @endif>
                                    <label for="roles" class="form-check-label">{{ $role->name }}</label>
                                </div>    
                            @endforeach
                            <!-- ADD USER ID -->
                            <input type="hidden" name="userId" value="{{ $user->id }}">     
                        </div>

                        <div class="card-footer">
                            <!-- SUBMIT -->
                            <button type="submit" class="btn btn-sm btn-info" style="margin-bottom: 15px;"><i class="fas fa-fw fa-check"></i> Set</button> 
                        </div>
                    </div>
                </div>
                
                          
            
            </div>
        </form>
    </div>
@endsection