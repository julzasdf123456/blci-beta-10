<table class="table table-borderless table-hover table-sm">
    <tr>
        <td><label for="OldAccountNo">Account Number</label></td>
        <td>
            <input type="text" class="form-control form-control-sm" name="OldAccountNo" id="OldAccountNo" placeholder="Account Number" autofocus required>
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('ServiceAccountName', 'Account Name:') !!}</td>
        <td>
            {!! Form::text('ServiceAccountName', $serviceConnection!=null ? $serviceConnection->ServiceAccountName : '', ['class' => 'form-control form-control-sm','maxlength' => 600,'maxlength' => 600]) !!}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('SequenceCode', 'Sequence No:') !!}</td>
        <td>
            {!! Form::text('SequenceCode', $serviceAccount != null ? $serviceAccount->SequenceCode : null, ['class' => 'form-control form-control-sm','maxlength' => 50,'maxlength' => 50, 'required' => true]) !!}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('Purok', 'Purok:') !!}</td>
        <td>
            {!! Form::text('Purok', $serviceConnection!=null ? $serviceConnection->Sitio : '', ['class' => 'form-control form-control-sm','maxlength' => 600,'maxlength' => 600]) !!}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('Barangay', 'Barangay:') !!}</td>
        <td>
            {!! Form::select('Barangay', $barangays, $serviceConnection!=null ? $serviceConnection->Barangay : '', ['class' => 'form-control form-control-sm', 'id' => 'BarangaySA']) !!}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('Town', 'Town:') !!}</td>
        <td>
            {!! Form::select('Town', $town, $serviceConnection!=null ? $serviceConnection->Town : '', ['class' => 'form-control form-control-sm', 'id' => 'TownSA']) !!}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('Zone', 'Zone:') !!}</td>
        <td>
            <select name="Zone" id="Zone" class="form-control form-control-sm" required>
                @foreach ($zones as $item)
                    <option value="{{ $item->Zone }}" {{ $serviceConnection !=null && $serviceConnection->Zone==$item->Zone ? 'selected' : '' }}>{{ $item->Zone }}</option>
                @endforeach
            </select>
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('BlockCode', 'Block:') !!}</td>
        <td>
            <select name="BlockCode" id="BlockCode" class="form-control form-control-sm" required>
                @foreach ($blocks as $item)
                    <option value="{{ $item->Block }}" {{ $serviceConnection !=null && $serviceConnection->Block==$item->Block ? 'selected' : '' }}>{{ $item->Block }}</option>
                @endforeach
            </select>
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('Feeder', 'Feeder:') !!}</td>
        <td>
            <input type="text" name="Feeder" id="Feeder" class="form-control form-control-sm" value="{{ $serviceConnection != null ? $serviceConnection->Feeder : null }}">
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('AccountStatus', 'Status:') !!}</td>
        <td>
            {!! Form::select('AccountStatus', ['ACTIVE' => 'ACTIVE', 'DISCONNECTED' => 'DISCONNECTED'], null, ['class' => 'form-control form-control-sm']) !!}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('AccountType', 'Account Type:') !!}</td>
        <td>
            <select class="form-control form-control-sm" name="AccountType" id="AccountType" required>
                @foreach ($accountTypes as $item)
                    <option value="{{ $item->AccountType }}" f-name="{{ $item->Alias }}" {{ $item->id==$serviceConnection->AccountType ? 'selected' : '' }}>{{ $item->AccountType }}</option>
                @endforeach
            </select>
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('AccountRetention', 'Account Longevity:') !!}</td>
        <td>
            {!! Form::select('AccountRetention', ['Permanent' => 'Permanent', 'Temporary' => 'Temporary'], $serviceConnection != null ? $serviceConnection->AccountApplicationType : 'Permanent', ['class' => 'form-control form-control-sm']) !!}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('DurationInMonths', 'Application Duration:') !!}</td>
        <td>
            {!! Form::text('DurationInMonths', ($serviceConnection != null ? $serviceConnection->TemporaryDurationInMonths : null), ['class' => 'form-control form-control-sm','maxlength' => 50,'maxlength' => 50, 'readonly' => true]) !!}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('AccountExpiration', 'Account Expiration:') !!}</td>
        <td>
            {!! Form::text('AccountExpiration', ($serviceConnection != null ? ($serviceConnection->AccountApplicationType=='Temporary' ? date('Y-m-d', strtotime($serviceConnection->DateTimeOfEnergization . ' +' . ($serviceConnection->TemporaryDurationInMonths != null ? $serviceConnection->TemporaryDurationInMonths : '3') . ' months')) : null) : null), ['class' => 'form-control form-control-sm','maxlength' => 50,'maxlength' => 50]) !!}
        </td>
        @push('page_scripts')
            <script type="text/javascript">
                $('#AccountExpiration').datetimepicker({
                    format: 'YYYY-MM-DD',
                    useCurrent: true,
                    sideBySide: true
                })
            </script>
        @endpush
    </tr>    
    <tr>
        <td>{!! Form::label('MeterReader', 'Meter Reader:') !!}</td>
        <td>
            <select class="custom-select select2" name="MeterReader" required>
                <option value="">n/a</option>
                @foreach ($meterReaders as $items)
                    <option value="{{ $items->id }}">{{ $items->name }}</option>
                @endforeach
            </select>
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('GroupCode', 'Reading Day:') !!}</td>
        <td>
            <select name="GroupCode" class="form-control form-control-sm">
                <option value="01">Day 01</option>
                <option value="02">Day 02</option>
                <option value="03">Day 03</option>
                <option value="04">Day 04</option>
                <option value="05">Day 05</option>
                <option value="06">Day 06</option>
                <option value="07">Day 07</option>
                <option value="08">Day 08</option>
                <option value="09">Day 09</option>
                <option value="10">Day 10</option>
                <option value="11">Day 11</option>
                <option value="12">Day 12</option>
                <option value="13">Day 13</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('SeniorCitizen', 'Senior Citizen:') !!}</td>
        <td>
            {{ Form::checkbox('SeniorCitizen', 'Yes', false, ['class' => 'custom-checkbox']) }}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('Contestable', 'Contestable:') !!}</td>
        <td>
            {{ Form::checkbox('Contestable', 'Yes', false, ['class' => 'custom-checkbox']) }}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('NetMetered', 'Net Metered:') !!}</td>
        <td>
            {{ Form::checkbox('NetMetered', 'Yes', false, ['class' => 'custom-checkbox']) }}
        </td>
    </tr>
    <tr>
        <td>{!! Form::label('Lifeliner', 'Lifeliner:') !!}</td>
        <td>
            {{ Form::checkbox('Lifeliner', 'Yes', false, ['class' => 'custom-checkbox']) }}
        </td>
    </tr>
</table>

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
