
<!-- Meterbrand Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('MeterBrand', 'Meter Brand') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                </div>
                {!! Form::select('MeterBrand', ['EDMI' => 'EDMI', 'TECHINE' => 'TECHINE'], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Meterserialnumber Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('MeterSerialNumber', 'Meter Serial No.') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                </div>
                {!! Form::text('MeterSerialNumber', null, ['class' => 'form-control','maxlength' => 150,'maxlength' => 150, 'placeholder' => 'Use QR or Barcode Scanner for faster encoding']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Metersealnumber Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('MeterSealNumber', 'Meter Seal No.') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                </div>
                {!! Form::text('MeterSealNumber', null, ['class' => 'form-control','maxlength' => 200,'maxlength' => 200, 'placeholder' => 'Meter Seal Number']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Meterkwhstart Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('MeterKwhStart', 'Meter Kwh Start') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                </div>
                {!! Form::text('MeterKwhStart', null, ['class' => 'form-control','maxlength' => 200,'maxlength' => 200, 'placeholder' => 'This is for the reused meters']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Meterenclosuretype Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('MeterEnclosureType', 'Meter Box Type') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                </div>
                {!! Form::select('MeterEnclosureType', ['' => 'n/a', 'Galvanized Iron' => 'Galvanized Iron', 'Plastic/Fiber Glass' => 'Plastic/Fiber Glass'], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Meterheight Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('MeterHeight', 'Meter Height') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                </div>
                {!! Form::text('MeterHeight', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20, 'placeholder' => 'Height in meters (m)']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Meternotes Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('MeterNotes', 'Meter Notes') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                </div>
                {!! Form::textarea('MeterNotes', null, ['class' => 'form-control', 'placeholder' => 'Meter remarks', 'rows' => 1]) !!}
            </div>
        </div>
    </div> 
</div>

<div class="divider"></div>
<br>

<!-- Transformerbrand Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('TransformerBrand', 'Transformer Brand') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-charging-station"></i></span>
                </div>
                {!! Form::select('TransformerBrand', ['EDMI' => 'EDMI', 'TECHINE' => 'TECHINE'], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- TransformerNumber Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('TransformerNumber', 'Transformer No.') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-charging-station"></i></span>
                </div>
                {!! Form::text('TransformerNumber', null, ['class' => 'form-control','maxlength' => 150,'maxlength' => 150, 'placeholder' => 'Transformer Serial Number']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Transformerquantity Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('TransformerQuantity', 'Transformer Qty') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-charging-station"></i></span>
                </div>
                {!! Form::text('TransformerQuantity', null, ['class' => 'form-control','maxlength' => 20,'maxlength' => 20, 'placeholder' => 'Number of transformer installed']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Transformerrating Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('TransformerRating', 'Transformer Ratings') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-charging-station"></i></span>
                </div>
                {!! Form::text('TransformerRating', null, ['class' => 'form-control','maxlength' => 150,'maxlength' => 150, 'placeholder' => 'in kVA (Separate by comma if more than 1)']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Transformerownership Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('TransformerOwnership', 'Ownership') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-charging-station"></i></span>
                </div>
                {!! Form::select('TransformerOwnership', [env('APP_COMPANY_ABRV') => env('APP_COMPANY_ABRV'), 'Privately Owned' => 'Privately Owned'], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Transformerownershiptype Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('TransformerOwnershipType', 'Ownership Category') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-charging-station"></i></span>
                </div>
                {!! Form::select('TransformerOwnershipType', ['Shared' => 'Shared', 'Solo' => 'Solo'], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div> 
</div>

<div class="divider"></div>
<br>

<!-- TypeOfMetering -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('TypeOfMetering', 'Metering Type') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-bezier-curve"></i></span>
                </div>

                <div class='radio-group-horizontal'>
                    <div class="form-check">
                        <input class="form-check-input" id="direct" type="radio" name="TypeOfMetering" value="DIRECT"  {{ ($serviceConnectionMtrTrnsfrmr != null ? ($serviceConnectionMtrTrnsfrmr->TypeOfMetering=="DIRECT" ? "checked" : "") : "") }}>
                        <label class="form-check-label">Direct</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" id="instrument_rated" type="radio" name="TypeOfMetering" value="INSTRUMENT RATED" {{ ($serviceConnectionMtrTrnsfrmr != null ? ($serviceConnectionMtrTrnsfrmr->TypeOfMetering=="INSTRUMENT RATED" ? "checked" : "") : "") }}>
                        <label class="form-check-label">Instrument Rated (CT/PT)</label>
                    </div>
                </div>                
            </div>
        </div>
    </div> 
</div>
@push('page_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#direct_section').hide();
            $('#indirect_section').hide();

            if($('#direct').is(':checked')) {
                $('#direct_section').show();
                $('#indirect_section').hide();
            }

            if ($('#instrument_rated').is(':checked')) {
                $('#indirect_section').show();
                $('#direct_section').hide();
            } 

            $('#direct').on('change', function() {
                $('#direct_section').show();
                $('#indirect_section').hide();

                // UNCHECK INSTRUMENT RATED
                // $('#Phase1').prop('checked', false);
                // $('#Phase2').prop('checked', false);
                // $('#Phase3').prop('checked', false);

                // UNCHECK INSTRUMENT FIELD
                // $('#InstrumentRatedCapacity1').prop('checked', false);
                // $('#InstrumentRatedCapacity2').prop('checked', false);
            });

            $('#instrument_rated').on('change', function() {            
                $('#indirect_section').show();
                $('#direct_section').hide();

                // UNCHECK DIRECT
                // $('#Phase1').prop('checked', false);
                // $('#Phase2').prop('checked', false);
                // $('#Phase3').prop('checked', false);

                // UNCHECK CAPACITY FIELD
                // $('#DirectRatedCapacity1').prop('checked', false);
                // $('#DirectRatedCapacity2').prop('checked', false);
                // $('#DirectRatedCapacity3').prop('checked', false);
            });           
        });
        
    </script>
@endpush

<!-- Phase Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Phase', 'Phase') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-bezier-curve"></i></span>
                </div>

                <div class='radio-group-horizontal'>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="Phase1" name="Phase" value="ONE" {{ ($serviceConnectionMtrTrnsfrmr != null ? ($serviceConnectionMtrTrnsfrmr->Phase=="ONE" ? "checked" : "") : "") }}>
                        <label class="form-check-label">One</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="Phase2" name="Phase" value="TWO" {{ ($serviceConnectionMtrTrnsfrmr != null ? ($serviceConnectionMtrTrnsfrmr->Phase=="TWO" ? "checked" : "") : "") }}>
                        <label class="form-check-label">Two</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="Phase3" name="Phase" value="THREE" {{ ($serviceConnectionMtrTrnsfrmr != null ? ($serviceConnectionMtrTrnsfrmr->Phase=="THREE" ? "checked" : "") : "") }}>
                        <label class="form-check-label">Three</label>
                    </div>
                </div>                
            </div>
        </div>
    </div> 
</div>

<div id='direct_section' style="width: 100%;">
    <!-- Directratedcapacity Field -->
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                {!! Form::label('DirectRatedCapacity', 'Capacity') !!}
            </div>

            <div class="col-lg-9 col-md-7">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-bezier-curve"></i></span>
                    </div>

                    <div class='radio-group-horizontal'>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="DirectRatedCapacity1" name="DirectRatedCapacity" value="60A" {{ ($serviceConnectionMtrTrnsfrmr != null ? ($serviceConnectionMtrTrnsfrmr->DirectRatedCapacity=="60A" ? "checked" : "") : "") }}>
                            <label class="form-check-label">60 A</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="DirectRatedCapacity2" name="DirectRatedCapacity" value="100A" {{ ($serviceConnectionMtrTrnsfrmr != null ? ($serviceConnectionMtrTrnsfrmr->DirectRatedCapacity=="100A" ? "checked" : "") : "") }}>
                            <label class="form-check-label">100 A</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="DirectRatedCapacity3" name="DirectRatedCapacity" value="200A" {{ ($serviceConnectionMtrTrnsfrmr != null ? ($serviceConnectionMtrTrnsfrmr->DirectRatedCapacity=="200A" ? "checked" : "") : "") }}>
                            <label class="form-check-label">200 A</label>
                        </div>
                    </div>                
                </div>
            </div>
        </div> 
    </div>
</div>

<div id='indirect_section' style="width: 100%;">
    <!-- Instrumentratedcapacity Field -->
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                {!! Form::label('InstrumentRatedCapacity', 'Capacity') !!}
            </div>

            <div class="col-lg-9 col-md-7">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-bezier-curve"></i></span>
                    </div>

                    <div class='radio-group-horizontal'>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="InstrumentRatedCapacity1" name="InstrumentRatedCapacity" value="FORM 3S">
                            <label class="form-check-label">FORM 3S</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="InstrumentRatedCapacity2" name="InstrumentRatedCapacity" value="FORM 48E">
                            <label class="form-check-label">FORM 48E</label>
                        </div>
                    </div>                
                </div>
            </div>
        </div> 
    </div>

    <!-- Instrumentratedlinetype Field -->
    <div class="form-group col-sm-12">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                {!! Form::label('InstrumentRatedLineType', 'Line Type') !!}
            </div>

            <div class="col-lg-9 col-md-7">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-bezier-curve"></i></span>
                    </div>

                    <div class='radio-group-horizontal'>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="InstrumentRatedLineType" value="PRIMARY">
                            <label class="form-check-label">PRIMARY</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="InstrumentRatedLineType" value="SECONDARY">
                            <label class="form-check-label">SECONDARY</label>
                        </div>
                    </div>                
                </div>
            </div>
        </div> 
    </div>

    <!-- TABLE FORM -->
    <table class="table" style="width: 100%;">
        <tr>
            <th></th>
            <th>Phase A</th>
            <th>Phase B</th>
            <th>Phase C</th>
        </tr>
        <tr>
            <th>CT Rated Capacity</th>
            <td>
                {!! Form::select('CTPhaseA', ['' => '-', '15:5' => '15:5', '25:5' => '25:5', '50:5' => '50:5', '100:5' => '100:5'], null, ['class' => 'form-control']) !!}
            </td>
            <td>
                {!! Form::select('CTPhaseB', ['' => '-', '15:5' => '15:5', '25:5' => '25:5', '50:5' => '50:5', '100:5' => '100:5'], null, ['class' => 'form-control']) !!}
            </td>
            <td>
                {!! Form::select('CTPhaseC', ['' => '-', '15:5' => '15:5', '25:5' => '25:5', '50:5' => '50:5', '100:5' => '100:5'], null, ['class' => 'form-control']) !!}
            </td>
        </tr>
        <tr>
            <th>PT Rated Capacity</th>
            <td>
                {!! Form::select('PTPhaseA', ['' => '-', '8400:120' => '8400:120'], null, ['class' => 'form-control']) !!}
            </td>
            <td>
                {!! Form::select('PTPhaseB', ['' => '-', '8400:120' => '8400:120'], null, ['class' => 'form-control']) !!}
            </td>
            <td> 
                {!! Form::select('PTPhaseC', ['' => '-', '8400:120' => '8400:120'], null, ['class' => 'form-control']) !!}
            </td>
        </tr>
        <tr>
            <th>Brand</th>
            <td>
                {!! Form::text('BrandPhaseA', null, ['class' => 'form-control','maxlength' => 150,'maxlength' => 150]) !!}
            </td>
            <td>
                {!! Form::text('BrandPhaseB', null, ['class' => 'form-control','maxlength' => 150,'maxlength' => 150]) !!}
            </td>
            <td>
                {!! Form::text('BrandPhaseC', null, ['class' => 'form-control','maxlength' => 150,'maxlength' => 150]) !!}
            </td>
        </tr>
        <tr>
            <th>Serial Number</th>
            <td>
                {!! Form::text('SNPhaseA', null, ['class' => 'form-control','maxlength' => 150,'maxlength' => 150]) !!}
            </td>
            <td>
                {!! Form::text('SNPhaseB', null, ['class' => 'form-control','maxlength' => 150,'maxlength' => 150]) !!}
            </td>
            <td>
                {!! Form::text('SNPhaseC', null, ['class' => 'form-control','maxlength' => 150,'maxlength' => 150]) !!}
            </td>
        </tr>
        <tr>
            <th>Security Seal No.</th>
            <td>
                {!! Form::text('SecuritySealPhaseA', null, ['class' => 'form-control','maxlength' => 150,'maxlength' => 150]) !!}
            </td>
            <td>
                {!! Form::text('SecuritySealPhaseB', null, ['class' => 'form-control','maxlength' => 150,'maxlength' => 150]) !!}
            </td>
            <td>
                {!! Form::text('SecuritySealPhaseC', null, ['class' => 'form-control','maxlength' => 150,'maxlength' => 150]) !!}
            </td>
        </tr>
    </table>
</div>





