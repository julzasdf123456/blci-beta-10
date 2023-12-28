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
                  <a class="nav-link active" href="#sms-settings" data-toggle="tab">SMS Settings</a>
                </li>
                {{-- <li class="nav-item">
                  <a class="nav-link" href="#environment-settings" data-toggle="tab">Environment</a>
                </li> --}}
            </ul>

            <div class="tab-content px-3">
                <div id="sms-settings" class="tab-pane active p-3">
                    <p class="text-muted">Configure when to send SMS notifications to customers</p>

                    <span class="text-muted"><strong>CRM - Applications</strong></span><br>
                    <table class="table table-hover table-bordered">
                        <tbody>
                            <tr>
                                <td style="width: 120px;">
                                    <input type="checkbox" id="ServiceConnectionReception" {{ $smsSettings == null ? '' : ($smsSettings->ServiceConnectionReception=="Yes" ? "checked" : '') }} data-bootstrap-switch data-off-color="default" data-on-color="success"/>
                                </td>
                                <td><strong>Upon Receiving Application</strong></td>
                            </tr>
                            <tr>
                                <td style="width: 120px;">
                                    <input type="checkbox" id="InspectionCreation" {{ $smsSettings == null ? '' : ($smsSettings->InspectionCreation=="Yes" ? "checked" : '') }} data-bootstrap-switch data-off-color="default" data-on-color="success"/>
                                </td>
                                <td><strong>Upon Creation of Inspection Schedule</strong></td>
                            </tr>
                            <tr>
                                <td style="width: 120px;">
                                    <input type="checkbox" id="InspectionToday" {{ $smsSettings == null ? '' : ($smsSettings->InspectionToday=="Yes" ? "checked" : '') }} data-bootstrap-switch data-off-color="default" data-on-color="success"/>
                                </td>
                                <td><strong>During Inspection Day (upon downloading of inspection schedule by the inspectors)</strong></td>
                            </tr>
                            <tr>
                                <td style="width: 120px;">
                                    <input type="checkbox" id="PaymentApproved" {{ $smsSettings == null ? '' : ($smsSettings->PaymentApproved=="Yes" ? "checked" : '') }} data-bootstrap-switch data-off-color="default" data-on-color="success"/>
                                </td>
                                <td><strong>Upon Approval of Payment</strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <span class="text-muted"><strong>Billing</strong></span><br>
                    <table class="table table-hover table-bordered">
                        <tbody>
                            <tr>
                                <td style="width: 120px;">
                                    <input type="checkbox" id="Bills" {{ $smsSettings == null ? '' : ($smsSettings->Bills=="Yes" ? "checked" : '') }} data-bootstrap-switch data-off-color="default" data-on-color="success"/>
                                </td>
                                <td><strong>Auto-send SMS Once Bill is Available</strong></td>
                            </tr>
                        </tbody>
                    </table>
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
