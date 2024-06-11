@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div id="app">
                <applied-requests></applied-requests>
            </div>
            @vite('resources/js/app.js')
        </div>
    </div>
@endsection
@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
        })
    </script>
@endpush