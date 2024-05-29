@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>
                        <i class="fas fa-cogs ico-tab"></i>System General Configuration
                    </h4>
                </div>
                <div class="col-sm-6">
                    <button id="save-settings" onclick="saveSettings()" class="btn btn-primary float-right">Save and Apply</button>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" href="#general-settings" data-toggle="tab">General</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#sms-settings" data-toggle="tab">SMS</a>
                </li>
                {{-- <li class="nav-item">
                  <a class="nav-link" href="#environment-settings" data-toggle="tab">Environment</a>
                </li> --}}
            </ul>

            <div class="tab-content px-3">
                <div id="general-settings" class="tab-pane active p-3">
                    @include('settings.tab_general')
                </div>
                <div id="sms-settings" class="tab-pane p-3">
                    @include('settings.tab_sms')
                </div>
                {{-- <div id="environment-settings" class="tab-pane">
                    <h1>Environment</h1>
                </div> --}}
            </div>
        </div>
        
    </div>
@endsection

@push('page_scripts')
    <script>
        function saveSettings() {
            $('#save-settings').addClass('disabled')
            $.ajax({
                url : "{{ route('settings.save-general-settings') }}",
                type : 'GET',
                data : {
                    Bills : $('#Bills').prop('checked') ? 'Yes' : null,
                    NoticeOfDisconnection : $('#NoticeOfDisconnection').prop('checked') ? 'Yes' : null,
                    ServiceConnectionReception : $('#ServiceConnectionReception').prop('checked') ? 'Yes' : null,
                    InspectionCreation : $('#InspectionCreation').prop('checked') ? 'Yes' : null,
                    PaymentApproved : $('#PaymentApproved').prop('checked') ? 'Yes' : null,
                    InspectionToday : $('#InspectionToday').prop('checked') ? 'Yes' : null,
                    PasswordDaysExpire : $('#PasswordDaysExpire').val(),
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Settings saved!'
                    })
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Settings not saved!'
                    })
                }
            })
            $('#save-settings').removeClass('disabled')
        }
    </script>
@endpush
