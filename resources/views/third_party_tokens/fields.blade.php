<!-- Thirdpartycompany Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ThirdPartyCompany', 'Company:') !!}
    {!! Form::text('ThirdPartyCompany', null, ['class' => 'form-control','maxlength' => 300,'maxlength' => 300]) !!}
</div>

<!-- Expiresin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Expires In:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','id'=>'Notes']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#Notes').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Thirdpartycode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ThirdPartyCode', 'Company Code:') !!}
    {!! Form::text('ThirdPartyCode', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100, 'placeholder' => 'Company Abbrev, Secret Codes, Others']) !!}
</div>
