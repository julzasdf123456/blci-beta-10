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
                {!! Form::text('SequenceCode', $serviceAccount != null ? $serviceAccount->SequenceCode : null, ['class' => 'form-control form-control-sm','maxlength' => 50,'maxlength' => 50]) !!}
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
                {!! Form::text('ServiceAccountName', $serviceConnection!=null ? $serviceConnection->ServiceAccountName : '', ['class' => 'form-control form-control-sm','maxlength' => 600,'maxlength' => 600]) !!}
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
        <!-- Purok Field -->
        <div class="col-lg-1 col-md-2">
            {!! Form::label('Purok', 'Purok:') !!}
        </div>

        <div class="col-lg-3 col-md-2">
            <div class="input-group">
                {!! Form::text('Purok', $serviceConnection!=null ? $serviceConnection->Sitio : '', ['class' => 'form-control form-control-sm','maxlength' => 600,'maxlength' => 600]) !!}
            </div>
        </div>

        <!-- Barangay Field -->
        <div class="col-lg-1 col-md-2">
            {!! Form::label('Barangay', 'Barangay:') !!}
        </div>

        <div class="col-lg-3 col-md-2">
            <div class="input-group">
                {!! Form::select('Barangay', $barangays, $serviceConnection!=null ? $serviceConnection->Barangay : '', ['class' => 'form-control form-control-sm', 'id' => 'BarangaySA']) !!}
            </div>
        </div>

        <!-- Town Field -->
        <div class="col-lg-1 col-md-2">
            {!! Form::label('Town', 'Town:') !!}
        </div>

        <div class="col-lg-3 col-md-2">
            <div class="input-group">
                {!! Form::select('Town', $town, $serviceConnection!=null ? $serviceConnection->Town : '', ['class' => 'form-control form-control-sm', 'id' => 'TownSA']) !!}
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
                        <option value="{{ $item->AccountType }}" f-name="{{ $item->Alias }}" {{ $item->id==$serviceConnection->AccountType ? 'selected' : '' }}>{{ $item->AccountType }}</option>
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
                {!! Form::select('AccountRetention', ['Permanent' => 'Permanent', 'Temporary' => 'Temporary'], $serviceConnection != null ? $serviceConnection->AccountApplicationType : 'Permanent', ['class' => 'form-control form-control-sm']) !!}
            </div>
        </div>

        <!-- Account Duration -->
        <div class="col-lg-1 col-md-2">
            {!! Form::label('DurationInMonths', 'Application Duration:') !!}
        </div>

        <div class="col-lg-3 col-md-2">
            <div class="input-group">
                {!! Form::text('DurationInMonths', ($serviceConnection != null ? $serviceConnection->TemporaryDurationInMonths : null), ['class' => 'form-control form-control-sm','maxlength' => 50,'maxlength' => 50, 'readonly' => true]) !!}
            </div>
        </div>

        <!-- Account Expiration -->
        <div class="col-lg-1 col-md-2">
            {!! Form::label('AccountExpiration', 'Account Expiration:') !!}
        </div>

        <div class="col-lg-3 col-md-2">
            <div class="input-group">
                {!! Form::text('AccountExpiration', ($serviceConnection != null ? ($serviceConnection->AccountApplicationType=='Temporary' ? date('Y-m-d', strtotime($serviceConnection->DateTimeOfEnergization . ' +' . ($serviceConnection->TemporaryDurationInMonths != null ? $serviceConnection->TemporaryDurationInMonths : '3') . ' months')) : null) : null), ['class' => 'form-control form-control-sm','maxlength' => 50,'maxlength' => 50]) !!}
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
@include('service_accounts.modal_check_available_accountno')

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#AccountTypeFull').text($('#AccountType option:selected').attr('f-name'))

            $('#AccountType').on('change', function() {
                $('#AccountTypeFull').text($('#AccountType option:selected').attr('f-name'))
            })

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
