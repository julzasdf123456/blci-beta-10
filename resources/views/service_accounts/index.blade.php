@extends('layouts.app')

@section('content')
    {{-- <section class="content-header">
        <div class="container-fluid">
            <a href="{{ route('serviceAccounts.accounts-map-view') }}" class="btn btn-xs btn-warning float-right"><i class="fas fa-map-marker-alt ico-tab-mini"></i>Go to Map View</a>
            {!! Form::open(['route' => 'serviceAccounts.index', 'method' => 'GET']) !!}
                <div class="row mb-2">
                    <div class="col-md-2 offset-md-1">
                        <input class="form-control" id="old-account-no" name="oldaccount" autocomplete="off" data-inputmask="'alias': 'phonebe'" maxlength="12" value="{{ env('APP_AREA_CODE') }}" style="font-size: 1.5em; color: #b91400; font-weight: bold;">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Search" name="params" value="{{ old('params') }}">
                    </div>
                    <div class="col-md-3">
                        {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </section> --}}

    {{-- <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        @include('service_accounts.table')
    </div> --}}
    

    <div class="row">
        <div class="col-lg-12">
            <div id="app">
                <service-accounts></service-accounts>
            </div>
            @vite('resources/js/app.js')
        </div>
    </div>
@endsection

@push('page_scripts')
    {{-- <script>
        $(document).ready(function() {
            $('#old-account-no').focus()

            $("#old-account-no").inputmask({
                mask: '99-99999-999',
                placeholder: '',
                showMaskOnHover: false,
                showMaskOnFocus: false,
                onBeforePaste: function (pastedValue, opts) {
                    var processedValue = pastedValue;

                    //do something with it

                    return processedValue;
                }
            });
        })
    </script> --}}
@endpush

