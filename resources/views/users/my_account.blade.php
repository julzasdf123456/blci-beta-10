@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 bg-dark d-flex align-items-center" style="height: 25vh;">
            <img src="{{ URL::asset('imgs/company_logo.png') }}"
                             class="img-circle elevation-2"
                             style="margin-left: 20px; height: 80%;"
                             alt="User Image"> 
                        <br>
        </div>

        <div class="col-lg-12 p-4">
            <h1 class="no-pads"><strong>{{ Auth::check() ? strtoupper(Auth::user()->name) : '' }}</strong></h1>
            <p class="text-muted">{{ Auth::user()->email }}</p>
        </div>

        <div class="col-lg-8 px-4">
            <table class="table table-hover table-sm">
                <tbody>
                    <tr>
                        <td style="width: 30%;" class="text-muted">Username</td>
                        <td>{{ Auth::user()->username }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Password Hash</td>
                        <td>{{ Auth::user()->password }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-lg-4" style="border-left: 1px solid #e6e6e6;">
            <p class="text-muted">My Permissions</p>

            <table class="table table-hover table-sm table-borderless">
                <tbody>
                    @foreach ($userPermissions as $item)
                        <tr>
                            <td class="badge" style="background-color: #e6e6e6; font-size: .9em; margin-bottom: 2px; padding-left: 9px; padding-right: 9px;">{{ $item->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection