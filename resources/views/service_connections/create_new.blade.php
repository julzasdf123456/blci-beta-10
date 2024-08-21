@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\IDGenerator;
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <span class="text-muted"><strong>New Application</strong></span>
    </section>
    
    {!! Form::open(['route' => 'serviceConnections.store']) !!}
    <div class="row">

        {{-- HIDDEN FIELDS --}}
        <input type="hidden" name="Status" value="For Inspection">
        <input type="hidden" name="Town" value="01" id="Town">
        <input type="hidden" name="UserId" value="{{ Auth::id() }}">
        <input type="hidden" name="DateOfApplication" value="{{ date('Y-m-d') }}">
        <input type="hidden" name="TimeOfApplication" value="{{ date('H:i:s') }}">
        <input type="hidden" name="Office" value="{{ env("APP_LOCATION") }}">

        <p id="Def_Brgy" style="display: none;">01</p>

        {{-- UPPER CARD --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-borderless">
                        <tr style="border-bottom: 1px solid #9a9a9a;">
                            <td>
                                <div class="form-check pt-1">
                                    <input type="radio" id="new-inst" name="customer-profile" value="New Installation" class="custom-radio" checked>
                                    <label for="new-inst" class="custom-radio-label">New Installation</label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check pt-1">
                                    <input type="radio" id="existing" name="customer-profile" value="Existing" class="custom-radio">
                                    <label for="existing" class="custom-radio-label">Existing</label>
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                Customer Name: <strong class="text-danger">*</strong>
                                <button class="btn btn-sm btn-info float-right gone" data-toggle="modal" data-target="#modal-select-customers" id="select-costumer">Select Costumer</button>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm uc-text-smooth" required autofocus name="ServiceAccountName" id="ServiceAccountName" value="">
                            </td>
                            <td>District/Barangay:  <strong class="text-danger">*</strong></td>
                            <td>
                                <select name="Barangay" id="Barangay" class="form-control form-control-sm">
                                    
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Application No: </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" readonly name="id" id="id" value="{{ IDGenerator::generateID() }}">
                            </td>
                            <td>Sitio/Purok:  <strong class="text-danger">*</strong></td>
                            <td>
                                <input type="text" class="form-control form-control-sm uc-text-smooth" name="Sitio" id="Sitio" value="" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Email Address</td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="EmailAddress" id="EmailAddress" value="">
                            </td>
                            <td>Contact/Tel. No:  <strong class="text-danger">*</strong></td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="ContactNumber" id="ContactNumber" value="" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Account No.</td>
                            <td>
                                <input type="text" style="width: 120px; display: inline;" class="form-control form-control-sm" name="AccountNumber" id="AccountNumber" maxlength="5">
                                -
                                <input type="text" style="width: 60px; display: inline;" class="form-control form-control-sm" name="NumberOfAccounts" id="NumberOfAccounts" maxlength="2">
                            </td>
                            <td>
                                <input type="checkbox" class="form-checkbox" name="BLCIInitiated" id="BLCIInitiated" value="Yes" style="width: 24px; display: inline;">
                                <label for="BLCIInitiated">BLCI Initiated?</label>
                            </td>
                            <td>
                                {{-- <input type="checkbox" class="form-checkbox" name="NetMetered" id="NetMetered" value="Yes" style="width: 24px; display: inline;">
                                <label for="NetMetered">Net Metered</label> --}}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- LOWER CARD --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-borderless">
                        {{-- ROW 1 --}}
                        <tr>
                            <td>Classification of Service: </td>
                            <td>
                                <select name="AccountType" id="AccountType" class="form-control form-control-sm">
                                    @foreach ($accountTypes as $item)
                                        <option value="{{ $item->AccountType }}">{{ $item->AccountType }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>Inspector: <strong class="text-danger">*</strong></td>
                            <td>
                                <select name="Inspector" id="Inspector" class="form-control form-control-sm">
                                    @foreach ($inspectors as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        {{-- ROW 2 --}}
                        <tr>
                            <td>Type of Customer:   <strong class="text-danger">*</strong></td>
                            <td>
                                <select name="TypeOfCustomer" id="TypeOfCustomer" class="form-control form-control-sm" required>
                                    @php
                                        $typeOfCustomer = ServiceConnections::typesOfConsumer();
                                    @endphp
                                    <option value="">--Select--</option>
                                    @foreach ($typeOfCustomer as $key => $item)
                                        <option value="{{ $key }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>Inspection Schedule: <strong class="text-danger">*</strong></td>
                            <td style="position: relative;">
                                <input type="text" class="form-control form-control-sm" required name="InspectionSchedule" id="InspectionSchedule">
                            </td>
                        </tr>
                        {{-- ROW 3 --}}
                        <tr>
                            <td>Service Applied For:   <strong class="text-danger">*</strong></td>
                            <td>
                                <select name="AccountApplicationType" id="AccountApplicationType" class="form-control form-control-sm" required>
                                    <option value="">--Select--</option>
                                    @foreach ($serviceAppliedFor as $item)
                                        <option value="{{ $item->ServiceAppliedFor }}" {{ $item->ServiceAppliedFor=='NEW INSTALLATION' ? 'selected' : '' }}>{{ $item->ServiceAppliedFor }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>Connection Schedule: </td>
                            <td style="position: relative;">
                                <input type="text" class="form-control form-control-sm" name="ConnectionSchedule" id="ConnectionSchedule">
                            </td>
                        </tr>
                        {{-- ROW 4 --}}
                        <tr>
                            
                            <td>Service Number: </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="ServiceNumber" id="ServiceNumber">
                            </td>
                            <td>Crew Assigned: </td>
                            <td>
                                <select name="StationCrewAssigned" id="StationCrewAssigned" class='form-control form-control-sm'>
                                    <option value="">-</option>
                                    @foreach ($crew as $itemx)
                                       <option value="{{ $itemx->id }}" {{ $item->StationCrewAssigned==$itemx->id ? 'selected' : '' }}>{{ $itemx->StationName }}</option>
                                    @endforeach
                                 </select>
                            </td>
                        </tr>
                        {{-- ROW 5 --}}
                        <tr>
                            <td>Cert. Of Conn. Issued On: </td>
                            <td style="position: relative;">
                                <input type="text" class="form-control form-control-sm" name="CertificateOfConnectionIssuedOn" id="CertificateOfConnectionIssuedOn">
                            </td>
                            <td>Remarks: </td>
                            <td>
                                <textarea type="text" class="form-control form-control-sm" rows="3" name="Notes" id="Notes"></textarea>
                            </td>
                            
                        </tr>
                        {{-- ROW 7 --}}
                        <tr>
                            <td>Load Type: </td>
                            <td>
                                <select name="LoadType" id="LoadType" class="form-control form-control-sm">
                                    <option value="">-- Select --</option>
                                    <option value="DEDICATED">DEDICATED</option>
                                    <option value="COMMON">COMMON</option>
                                </select>
                            </td>
                            <td>Load in kVa (Trans. kVa): </td>
                            <td>
                                <input type="number" step="any" class="form-control form-control-sm" name="LoadInKva" id="LoadInKva">
                            </td>
                            
                        </tr>
                        {{-- ROW 8 --}}
                        <tr>
                            <td>Transformer ID: </td>
                            <td>
                                <input type="number" step="any" class="form-control form-control-sm" name="TransformerID" id="TransformerID">
                            </td>
                            <td>TIN</td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="TIN" id="TIN">
                            </td>
                        </tr>
                        {{-- ROW 9 --}}
                        <tr>
                            <td>Pole Number: </td>
                            <td>
                                <input type="number" step="any" class="form-control form-control-sm" name="PoleNumber" id="PoleNumber">
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        {{-- ROW 10 --}}
                        <tr>
                            <td>Feeder: </td>
                            <td>
                                <input type="number" step="any" class="form-control form-control-sm" name="Feeder" id="Feeder">
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Charge To: </td>
                            <td>
                                <input type="number" step="any" class="form-control form-control-sm" name="ChargeTo" id="ChargeTo">
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

{{-- MODAL EXISTING CUSTOMERS --}}
@include('service_connections.modal_select_customers')

@push('page_scripts')
    <script>
        $('#InspectionSchedule').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true,
            icons : {
                previous : 'fas fa-caret-left',
                next : 'fas fa-caret-right',
            }
        })

        $('#ConnectionSchedule').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true,
            icons : {
                previous : 'fas fa-caret-left',
                next : 'fas fa-caret-right',
            }
        })

        $('#CertificateOfConnectionIssuedOn').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true,
            icons : {
                previous : 'fas fa-caret-left',
                next : 'fas fa-caret-right',
            }
        })

        $(document).ready(function() {
            defaultInspector()

            const forceKeyPressUppercase = (e) => {
                let el = e.target;
                let charInput = e.keyCode;
                if((charInput >= 97) && (charInput <= 122)) { // lowercase
                    if(!e.ctrlKey && !e.metaKey && !e.altKey) { // no modifier key
                        let newChar = charInput - 32;
                        let start = el.selectionStart;
                        let end = el.selectionEnd;
                        el.value = el.value.substring(0, start) + String.fromCharCode(newChar) + el.value.substring(end);
                        el.setSelectionRange(start+1, start+1);
                        e.preventDefault();
                    }
                }
            };

            document.querySelectorAll(".uc-text-smooth").forEach(function(current) {
                current.addEventListener("keypress", forceKeyPressUppercase);
            });

            $("input[name=customer-profile]").change(function() {
                var selected = $("input[name=customer-profile]:checked").val()
                if (selected == 'New Installation') {
                    $('#AccountApplicationType').val('NEW INSTALLATION')
                    $('#select-costumer').addClass('gone')
                    $('#AccountNumber').val('')
                } else {
                    $('#AccountApplicationType').val('')
                    $('#select-costumer').removeClass('gone')
                }
            });

            $('#AccountApplicationType').on('change', function() {
                defaultInspector()
            })
        })

        function defaultInspector() {
            if ($('#AccountApplicationType').val() === 'NEW INSTALLATION' | $('#AccountApplicationType').val() === 'TEMPORARY') {
                $('#Inspector').val(`{{ ServiceConnections::defaultNewConnectionInspector() }}`)
            } else {
                $('#Inspector').val(`{{ ServiceConnections::defaultOtherApplicationsInspector() }}`)
            }
        }
    </script>
@endpush
