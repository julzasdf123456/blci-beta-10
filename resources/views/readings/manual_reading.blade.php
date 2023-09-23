@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div id="app">
            <manual-reading-search></manual-reading-search>
        </div>
        @vite('resources/js/app.js')
    </div>
</div>

@endsection