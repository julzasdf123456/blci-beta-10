@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span>
                    <h4 style="display: inline; margin-right: 15px;">Update Account Wizzard</h4>
                    <i class="text-muted">Edit Consumer Information</i>
                </span>
            </div>
        </div>
    </div>
</section>

<div class="row px-2">
    <div class="col-lg-12">
        @include('adminlte-templates::common.errors')
        <div class="card">
            {!! Form::model($serviceAccount, ['route' => ['serviceAccounts.update', $serviceAccount->id], 'method' => 'patch']) !!}

            <input type="hidden" name="ServiceAccountName" value="{{ $serviceAccount->ServiceAccountName }}">
            <div class="card-header">
                <span class="card-title">Account Information - <strong>{{ $serviceAccount->ServiceAccountName }}</strong></span>

                <div class="card-tools">
                    <!-- New Account Number Field -->
                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>

            <div class="card-body">

                <div class="row"> 
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('OldAccountNo', 'Legacy Acct. No:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('OldAccountNo', $serviceAccount != null ? $serviceAccount->OldAccountNo : null, ['class' => 'form-control','maxlength' => 12, 'data-inputmask' => "'alias': 'phonebe'"]) !!}
                                </div>
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

                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('SequenceCode', 'Seq. No:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('SequenceCode', $serviceAccount != null ? $serviceAccount->SequenceCode : null, ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
                                </div>
                            </div>

                            <!-- Areacode Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('AreaCode', 'Areacode/Route:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('AreaCode', $serviceAccount != null ? $serviceAccount->AreaCode : null, ['class' => 'form-control','maxlength' => 5]) !!}
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
                                    {!! Form::text('Purok', $serviceAccount != null ? $serviceAccount->Purok : null, ['class' => 'form-control','maxlength' => 600,'maxlength' => 600]) !!}
                                </div>
                            </div>

                            <!-- Barangay Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('Barangay', 'Barangay:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    <select name="Barangay" id="BarangaySA" class="form-control">                                        
                                        <option value="">-- Select --</option>
                                        @foreach ($barangays as $item)
                                            <option value="{{ $item->id }}" {{ $serviceAccount->Barangay != null && $serviceAccount->Barangay==$item->id ? 'selected' : '' }}>{{ $item->Barangay }}</option>
                                        @endforeach
                                    </select>
                                    {{-- {!! Form::select('Barangay', $barangays, $serviceAccount != null ? $serviceAccount->Barangay : null, ['class' => 'form-control', 'id' => 'BarangaySA']) !!} --}}
                                </div>
                            </div>

                            <!-- Town Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('Town', 'Town:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::select('Town', $towns, $serviceAccount != null ? $serviceAccount->Town : null, ['class' => 'form-control', 'id' => 'TownSA']) !!}
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="divider"></div>

                    {{-- STATUS --}}
                    <div class="form-group col-sm-12">
                        <div class="row">

                            <!-- Purok Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('AccountType', 'Acct. Type:') !!}
                            </div>

                            <div class="col-lg-2 col-md-2">
                                <div class="input-group">
                                    <select class="form-control" name="AccountType" id="AccountType">
                                        @foreach ($accountTypes as $item)
                                            <option value="{{ $item->AccountType }}" f-name="{{ $item->Alias }}" {{ $item->AccountType==$serviceAccount->AccountType ? 'selected' : '' }}>{{ $item->AccountType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-1 col-md-1" style="margin-top: 5px;">
                                <i id="AccountTypeFull"></i>
                            </div>

                            <!-- Meter Reader Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('MeterReader', 'Meter Reader:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    <select class="custom-select select2"  name="MeterReader">
                                        <option value="">-- Select --</option>
                                        @foreach ($meterReaders as $items)
                                            <option value="{{ $items->id }}" {{ $serviceAccount->MeterReader==$items->id ? 'selected' : '' }}>{{ $items->name }}</option>
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
                                    <select name="GroupCode" class="form-control">
                                        <option value="01" {{ $serviceAccount->GroupCode=='01' ? 'selected' : '' }}>01</option>
                                        <option value="02" {{ $serviceAccount->GroupCode=='02' ? 'selected' : '' }}>02</option>
                                        <option value="03" {{ $serviceAccount->GroupCode=='03' ? 'selected' : '' }}>03</option>
                                        <option value="04" {{ $serviceAccount->GroupCode=='04' ? 'selected' : '' }}>04</option>
                                        <option value="05" {{ $serviceAccount->GroupCode=='05' ? 'selected' : '' }}>05</option>
                                        <option value="06" {{ $serviceAccount->GroupCode=='06' ? 'selected' : '' }}>06</option>
                                        <option value="07" {{ $serviceAccount->GroupCode=='07' ? 'selected' : '' }}>07</option>
                                        <option value="08" {{ $serviceAccount->GroupCode=='08' ? 'selected' : '' }}>08</option>
                                        <option value="09" {{ $serviceAccount->GroupCode=='09' ? 'selected' : '' }}>09</option>
                                        <option value="10" {{ $serviceAccount->GroupCode=='10' ? 'selected' : '' }}>10</option>
                                        <option value="11" {{ $serviceAccount->GroupCode=='11' ? 'selected' : '' }}>11</option>
                                        <option value="12" {{ $serviceAccount->GroupCode=='12' ? 'selected' : '' }}>12</option>
                                        <option value="13" {{ $serviceAccount->GroupCode=='13' ? 'selected' : '' }}>13</option>
                                    </select>
                                </div>
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
                                    {!! Form::select('AccountRetention', ['Permanent' => 'Permanent', 'Temporary' => 'Temporary'], $serviceAccount != null ? $serviceAccount->AccountRetention : 'Permanent', ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <!-- Account Duration -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('Duration', 'Application Duration:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('DurationInMonths', ($serviceAccount != null ? $serviceAccount->DurationInMonths : null), ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
                                </div>
                            </div>

                            <!-- Account Expiration -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('AccountExpiration', 'Account Expiration:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('AccountExpiration', ($serviceAccount != null ? $serviceAccount->AccountExpiration : null), ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
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

                    {{-- CHECKBOXES --}}
                    <div class="form-group col-sm-12">
                        <div class="row">

                            <!-- Senior Citizen Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('SeniorCitizen', 'Senior Citizen:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    <input type="hidden" value="" name="SeniorCitizen">
                                    <input type="checkbox" value="Yes" name="SeniorCitizen" class="custom-checkbox" {{ $serviceAccount->SeniorCitizen=='Yes' ? 'checked' : '' }}>
                                </div>
                            </div>

                            {{-- SENIOR CITIZEN APPLIED --}}
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('SeniorCitizenDateApplied', 'Date Applied:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('SeniorCitizenDateApplied', ($serviceAccount != null ? $serviceAccount->SeniorCitizenDateApplied : null), ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
                                </div>
                            </div>

                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#SeniorCitizenDateApplied').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush

                            {{-- SENIOR CITIZEN APPLIED --}}
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('SeniorCitizenDateExpire', 'Date Expire:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('SeniorCitizenDateExpire', ($serviceAccount != null ? $serviceAccount->SeniorCitizenDateExpire : null), ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
                                </div>
                            </div>

                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#SeniorCitizenDateExpire').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush
                        </div> 
                    </div>

                    {{-- CONTESTABLE --}}
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <!-- Lifeliner Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('Lifeliner', 'Lifeliner:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    <input type="hidden" value="" name="Lifeliner">
                                    <input type="checkbox" value="Yes" name="Lifeliner" class="custom-checkbox" {{ $serviceAccount->Lifeliner=='Yes' ? 'checked' : '' }}>
                                </div>
                            </div>

                            {{-- LIFELNER APPLIED --}}
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('LifelinerDateApplied', 'Date Applied:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('LifelinerDateApplied', ($serviceAccount != null ? $serviceAccount->LifelinerDateApplied : null), ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
                                </div>
                            </div>

                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#LifelinerDateApplied').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush

                            {{-- LIFELNER EXPIRED --}}
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('LifelinerDateExpire', 'Date Expire:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::text('LifelinerDateExpire', ($serviceAccount != null ? $serviceAccount->LifelinerDateExpire : null), ['class' => 'form-control','maxlength' => 50,'maxlength' => 50]) !!}
                                </div>
                            </div>

                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#LifelinerDateExpire').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush
                        </div> 
                    </div>

                    {{-- FOR DISTRIBUTION --}}
                    <div class="form-group col-sm-12">
                        <div class="row">                            
                            @if ($serviceAccount->AccountType=='RESIDENTIAL' | $serviceAccount->AccountType == 'RESIDENTIAL RURAL')

                            @else
                                <!-- EVAT Field -->
                                <div class="col-lg-1 col-md-2">
                                    {!! Form::label('Evat5Percent', '5% EVAT:') !!}
                                </div>

                                <div class="col-lg-1 col-md-1">
                                    <div class="input-group">
                                        <input type="hidden" value="" name="Evat5Percent">
                                        <input type="checkbox" value="Yes" name="Evat5Percent" class="custom-checkbox" {{ $serviceAccount->Evat5Percent=='Yes' ? 'checked' : '' }}>
                                    </div>
                                </div>

                                <!-- EWT Field -->
                                <div class="col-lg-1 col-md-2">
                                    {!! Form::label('Ewt2Percent', '2% EWT:') !!}
                                </div>

                                <div class="col-lg-1 col-md-1">
                                    <div class="input-group">
                                        <input type="hidden" value="" name="Ewt2Percent">
                                        <input type="checkbox" value="Yes" name="Ewt2Percent" class="custom-checkbox" {{ $serviceAccount->Ewt2Percent=='Yes' ? 'checked' : '' }}>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Accountstatus Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('AccountStatus', 'Status:') !!}
                            </div>

                            <div class="col-lg-3 col-md-2">
                                <div class="input-group">
                                    {!! Form::select('AccountStatus', ['ACTIVE' => 'ACTIVE', 'DISCONNECTED' => 'DISCONNECTED'], null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <!-- Contestable Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('Contestable', 'Contestable:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    <input type="hidden" value="" name="Contestable">
                                    <input type="checkbox" value="Yes" name="Contestable" class="custom-checkbox" {{ $serviceAccount->Contestable=='Yes' ? 'checked' : '' }}>
                                </div>
                            </div>

                            <!-- Net Metered Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('NetMetered', 'Net Metered:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    <input type="hidden" value="" name="NetMetered">
                                    <input type="checkbox" value="Yes" name="NetMetered" class="custom-checkbox" {{ $serviceAccount->NetMetered=='Yes' ? 'checked' : '' }}>
                                </div>
                            </div>

                            <!-- Coop Consumption Field -->
                            <div class="col-lg-1 col-md-2">
                                {!! Form::label('Item1', 'Coop Consumption:') !!}
                            </div>

                            <div class="col-lg-1 col-md-1">
                                <div class="input-group">
                                    <input type="hidden" value="" name="Item1">
                                    <input type="checkbox" value="Yes" name="Item1" class="custom-checkbox" {{ $serviceAccount->Item1=='Yes' ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('serviceAccounts.index') }}" class="btn btn-default">Cancel</a>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection

@push('page_scripts')
<script>
    $(document).ready(function() {
        checkBapa()
        checkDistribution()

        $('#BAPA').change(function() {
            checkBapa()
        })

        $('#ForDistribution').change(function() {
            checkDistribution()
        })
    })

    function checkBapa() {
        if ($('#BAPA').is(':checked')) {
            $('#OrganizationParentAccount').removeAttr('disabled')
        } else {
            $('#OrganizationParentAccount').attr('disabled', 'disabled')
            $("#OrganizationParentAccount").val("NULL").change()
        }
    }

    function checkDistribution() {
        if ($('#ForDistribution').is(':checked')) {
            $('#DistributionAccountCode').removeAttr('disabled')
        } else {
            $('#DistributionAccountCode').attr('disabled', 'disabled')
            $("#DistributionAccountCode").val("").change()
        }
    }
</script>
@endpush