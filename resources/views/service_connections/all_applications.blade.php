@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div id="app">
                <all-applications></all-applications>
            </div>
            @vite('resources/js/app.js')
        </div>
    </div>
@endsection

@push('page_scripts')
    
@endpush

