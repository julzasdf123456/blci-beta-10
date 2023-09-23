@php
    use App\Models\ServiceAccounts;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Account Migration Wizzard</h4>
                    <i class="text-muted">Step 1. Validate Consumer Information</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row px-2">
    <div class="col-lg-12">
        <div class="card">
            {!! Form::open(['route' => 'serviceAccounts.store-manual']) !!}
            <div class="card-header">
                <span class="card-title"><strong>Step 1. </strong>Account Information</span>
            </div>

            <div class="card-body">

                <div class="row">                    
                    {{-- HIDDEN FIELDS --}}

                    <!-- Legacy Account Number Field -->
                    <div class="form-group col-lg-7 col-md-8 col-sm-12">
                        <div class="row">
                            <div class="col-lg-2 col-md-4">
                                {!! Form::label('OldAccountNo', 'Legacy Acct. No:') !!}
                            </div>

                            <div class="col-lg-6 col-md-4">
                                <div class="input-group">
                                    {!! Form::text('OldAccountNo', env('APP_AREA_CODE'), ['class' => 'form-control form-control-sm','maxlength' => 12, 'data-inputmask' => "'alias': 'phonebe'"]) !!}
                                </div>
                                <small id="account-validation" class="form-text"></small>
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <button class="btn btn-sm btn-info" id="check-acct-availability">Check Available Account Nos.</button>
                            </div>

                            @push('page_scripts')
                                <script>
                                    $("#OldAccountNo").focus()
                                    $("#OldAccountNo").inputmask({
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
                                </script>
                            @endpush
                        </div> 
                    </div>

                    <!-- Sequencecode Field -->
                    <div class="form-group col-lg-5 col-md-4 col-sm-12">
                        <div class="row">
                            <div class="col-lg-3 col-md-5">
                                {!! Form::label('SequenceCode', 'Seq. No:') !!}
                            </div>

                            <div class="col-lg-9 col-md-7">
                                <div class="input-group">
                                    {!! Form::text('SequenceCode', null, ['class' => 'form-control form-control-sm','maxlength' => 50,'maxlength' => 50]) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="divider"></div>

                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Serviceaccountname Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('ServiceAccountName', 'Account Name:') !!}
                            </div>

                            <div class="col-lg-6 col-md-6">
                                <div class="input-group">
                                    {!! Form::text('ServiceAccountName', null, ['class' => 'form-control form-control-sm','maxlength' => 600,'maxlength' => 600]) !!}
                                </div>
                            </div>

                            <!-- Areacode Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('AreaCode', 'Areacode/Route:') !!}
                            </div>

                            <div class="col-lg-4 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('AreaCode', null, ['class' => 'form-control form-control-sm','maxlength' => 5]) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    {{-- ADDRESS --}}
                    <div class="form-group col-sm-12">
                        <div class="row">

                            <!-- Town Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('Town', 'Town:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::select('Town', $town, env('APP_AREA_CODE'), ['class' => 'form-control form-control-sm']) !!}
                                </div>
                            </div>

                            <!-- Barangay Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('Barangay', 'Barangay:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::select('Barangay', [], null, ['class' => 'form-control form-control-sm',]) !!}
                                </div>
                            </div>

                            <!-- Purok Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('Purok', 'Purok:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('Purok', null, ['class' => 'form-control form-control-sm','maxlength' => 600,'maxlength' => 600]) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="divider"></div>

                    {{-- STATUS --}}
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Accountstatus Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('AccountStatus', 'Status:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::select('AccountStatus', ['ACTIVE' => 'ACTIVE', 'DISCONNECTED' => 'DISCONNECTED'], null, ['class' => 'form-control form-control-sm']) !!}
                                </div>
                            </div>

                            <!-- Purok Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('AccountType', 'Acct. Type:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    <select class="form-control form-control-sm" name="AccountType" id="AccountType">
                                        @foreach ($accountTypes as $item)
                                            <option value="{{ $item->AccountType }}" f-name="{{ $item->Alias }}">{{ $item->AccountType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-2" style="margin-top: 5px;">
                                <i id="AccountTypeFull"></i>
                            </div>
                        </div> 
                    </div>

                    {{-- ACCOUNT RETENTION --}}
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- AccountRetention Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('AccountRetention', 'Account Longevity:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::select('AccountRetention', ['Permanent' => 'Permanent', 'Temporary' => 'Temporary'], 'Permanent', ['class' => 'form-control form-control-sm']) !!}
                                </div>
                            </div>

                            <!-- Account Duration -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('DurationInMonths', 'Application Duration:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('DurationInMonths', null, ['class' => 'form-control form-control-sm','maxlength' => 50,'maxlength' => 50]) !!}
                                </div>
                            </div>

                            <!-- Account Expiration -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('AccountExpiration', 'Account Expiration:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('AccountExpiration', null, ['class' => 'form-control form-control-sm','maxlength' => 50,'maxlength' => 50]) !!}
                                </div>
                            </div>

                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#AccountExpiration').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush
                        </div> 
                    </div>

                    {{-- METER READER --}}
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Meter Reader Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('MeterReader', 'Meter Reader:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    <select class="custom-select select2"  name="MeterReader">
                                        <option value="">n/a</option>
                                        @foreach ($meterReaders as $items)
                                            <option value="{{ $items->id }}">{{ $items->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Group Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('GroupCode', 'Group:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    <select name="GroupCode" class="form-control form-control-sm">
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Distribution Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('ForDistribution', 'For Distribution:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    {{ Form::checkbox('ForDistribution', 'Yes', false, ['class' => 'custom-checkbox']) }}
                                </div>
                            </div>

                            <!-- Senior Citizen Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('SeniorCitizen', 'Senior Citizen:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    {{ Form::checkbox('SeniorCitizen', 'Yes', false, ['class' => 'custom-checkbox']) }}
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="divider"></div>

                    {{-- CONTESTABLE --}}
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Contestable Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('Contestable', 'Contestable:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    {{ Form::checkbox('Contestable', 'Yes', false, ['class' => 'custom-checkbox']) }}
                                </div>
                            </div>

                            <!-- Net Metered Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('NetMetered', 'Net Metered:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    {{ Form::checkbox('NetMetered', 'Yes', false, ['class' => 'custom-checkbox']) }}
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="divider"></div>

                    @push('page_scripts')
                        <script>
                            $(document).ready(function() {
                                $('#AccountTypeFull').text($('#AccountType option:selected').attr('f-name'))

                                $('#AccountType').on('change', function() {
                                    $('#AccountTypeFull').text($('#AccountType option:selected').attr('f-name'))
                                })
                            })
                        </script>
                    @endpush

                </div>

            </div>

            <div class="card-footer">
                {!! Form::submit('Next', ['class' => 'btn btn-primary', 'id' => 'nextBtn', 'disabled' => 'disabled']) !!}
                <a href="{{ route('serviceAccounts.index') }}" class="btn btn-default">Cancel</a>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
<p id="Def_Brgy" style="display: none;"></p>
@include('service_accounts.modal_check_available_accountno')
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#OldAccountNo').focusout(function() {
                validateOldAccountNo()
            })

            $('#check-acct-availability').on('click', function(e) {
                e.preventDefault()
                if ($('#OldAccountNo').val().length < 8) {
                    Swal.fire({
                        title : 'Provide Route Code First',
                        text : 'You need to provide a complete Route Code to fetch for available account numbers. e.g., 01-06052, 05-08950',
                        icon : 'info'
                    })
                } else {
                    $('#modal-check-available-acctno').modal('show')
                    $('#check-acct-no-route').val('').val($('#OldAccountNo').val())
                }
                
            })
        })

        function validateOldAccountNo() {
            var acctNo = $('#OldAccountNo').val()
            if (acctNo.length >= 12) {
                $.ajax({
                    url : "{{ route('serviceAccounts.validate-old-account-no') }}",
                    type : 'GET',
                    data : {
                        OldAccountNo : acctNo,
                    },
                    success : function(res) {
                        if (res == 'ok') {
                            $('#account-validation').text('Account Number is available!').removeClass('text-danger').addClass('text-success')
                            $('#nextBtn').removeAttr('disabled')
                        } else {
                            $('#account-validation').text('Account Number taken!').removeClass('text-success').addClass('text-danger')
                        }
                    },
                    error : function(err) {
                        console.log('Error validating account')
                    }
                })
            } else {
                $('#account-validation').text('Account Number invalid!').removeClass('text-success').addClass('text-danger')
            }            
        }
    </script>
@endpush