@php
    use App\Models\IDGenerator;
    use Illuminate\Support\Facades\Auth;
@endphp

<table class="table table-hover table-borderless table-sm">
    <thead>

    </thead>
    <tbody>
        <tr>
            <th>ENERGY INPUT SUB-TRANSMISSION (in kWh)</th>
            <th></th>
        </tr>
        <tr>
            <th style="padding-left: 40px;">ENERGY INPUT DELIVERED BY TRANSMISSION SYSTEM</th>
            <td><input type="number" step="any" class="form-control text-right" id="totalInputDelivered" readonly=true></td>
        </tr>
        <tr>            
            <td style="padding-left: 80px;">{!! Form::label('CalatravaSubstation', 'Calatrava Substation:') !!}</td>
            <td>{!! Form::number('CalatravaSubstation', null, ['class' => 'form-control text-right','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}</td>
        </tr>
        <tr>            
            <td style="padding-left: 80px;">{!! Form::label('VictoriasSubstation', 'Victorias Substation:') !!}</td>
            <td>{!! Form::number('VictoriasSubstation', null, ['class' => 'form-control text-right','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}</td>
        </tr>
        <tr>            
            <td style="padding-left: 80px;">{!! Form::label('SagaySubstation', 'Sagay Substation:') !!}</td>
            <td>{!! Form::number('SagaySubstation', null, ['class' => 'form-control text-right','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}</td>
        </tr>
        <tr>            
            <td style="padding-left: 80px;">{!! Form::label('SanCarlosSubstation', 'San Sarlos Substation:') !!}</td>
            <td>{!! Form::number('SanCarlosSubstation', null, ['class' => 'form-control text-right','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}</td>
        </tr>
        <tr>            
            <td style="padding-left: 80px;">{!! Form::label('EscalanteSubstation', 'Escalante Substation:') !!}</td>
            <td>{!! Form::number('EscalanteSubstation', null, ['class' => 'form-control text-right','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}</td>
        </tr>
        <tr>            
            <td style="padding-left: 80px;">{!! Form::label('LopezSubstation', 'Lopez Substation:') !!}</td>
            <td>{!! Form::number('LopezSubstation', null, ['class' => 'form-control text-right','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}</td>
        </tr>
        <tr>            
            <td style="padding-left: 80px;">{!! Form::label('CadizSubstation', 'Cadiz Substation:') !!}</td>
            <td>{!! Form::number('CadizSubstation', null, ['class' => 'form-control text-right','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}</td>
        </tr>
        <tr>            
            <td style="padding-left: 80px;">{!! Form::label('IpiSubstation', 'IPI Substation:') !!}</td>
            <td>{!! Form::number('IpiSubstation', null, ['class' => 'form-control text-right','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}</td>
        </tr>
        <tr>            
            <td style="padding-left: 80px;">{!! Form::label('TobosoCalatravaSubstation', 'Toboso-Calatrava Substation:') !!}</td>
            <td>{!! Form::number('TobosoCalatravaSubstation', null, ['class' => 'form-control text-right','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}</td>
        </tr>
        <tr>
            <th style="padding-left: 40px;">ENERGY INPUT DELIVERED BY EMBEDDED GENERATOR</th>
            <td><input type="number" step="any" class="form-control text-right" id="generators" readonly=true></td>
        </tr>
        <tr>            
            <td style="padding-left: 80px;">{!! Form::label('VictoriasMillingCompany', 'Victorias Milling Company:') !!}</td>
            <td>{!! Form::number('VictoriasMillingCompany', null, ['class' => 'form-control text-right','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}</td>
        </tr>        
        <tr>            
            <td style="padding-left: 80px;">{!! Form::label('SanCarlosBionergy', 'San Carlos Bionergy:') !!}</td>
            <td>{!! Form::number('SanCarlosBionergy', null, ['class' => 'form-control text-right','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}</td>
        </tr>
        <tr>
            <th>TOTAL ENERGY INPUT SUB-TRANSMISSION (in kWh)</th>
            <td>{!! Form::number('TotalEnergyInput', null, ['class' => 'form-control text-right','maxlength' => 255,'maxlength' => 255, 'step' => 'any', 'readonly' => 'true', 'id' => 'TotalEnergyInput']) !!}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th>ENERGY OUTPUT DISTRIBUTION FEEDER</th>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left: 80px;">{!! Form::label('EnergySales', 'Energy Sales:') !!}</td>
            <td>{!! Form::number('EnergySales', $data != null ? $data->TotalKwhConsumption : 0, ['class' => 'form-control text-right','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}</td>
        </tr>
        <tr>
            <td style="padding-left: 80px;">{!! Form::label('EnergyAdjustmentRecoveries', 'Energy Recoveries (Adjustments):') !!}</td>
            <td>{!! Form::number('EnergyAdjustmentRecoveries', $data != null ? $data->Adjustments : 0, ['class' => 'form-control text-right','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}</td>
        </tr>
        <tr>
            <th>TOTAL ENERGY OUTPUT DISTRIBUTION FEEDER</th>
            <td><input type="number" name="TotalEnergyOutput" step="any" class="form-control text-right" id="TotalEnergyOutput" readonly=true></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th>TOTAL SYSTEM LOSS IN kWh</th>
            <td><input type="number" name="TotalSystemLoss" step="any" class="form-control text-right" id="TotalSystemLoss" readonly=true></td>
        </tr><tr>
            <th>TOTAL SYSTEM LOSS IN %</th>
            <td><input type="number" name="TotalSystemLossPercentage" step="any" class="form-control text-right" id="TotalSystemLossPercentage" readonly=true></td>
        </tr>
    </tbody>
</table>
<input type="hidden" name="id" value="{{ IDGenerator::generateIDandRandString() }}">
<!-- Serviceperiod Field -->
<input type="hidden" name="ServicePeriod" value="{{ $period }}">

<input type="hidden" name="UserId" value="{{ Auth::id() }}">
{{-- 
<!-- From Field -->
<div class="form-group col-sm-6">
    {!! Form::label('From', 'From:') !!}
    {!! Form::text('From', null, ['class' => 'form-control','id'=>'From']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#From').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- To Field -->
<div class="form-group col-sm-6">
    {!! Form::label('To', 'To:') !!}
    {!! Form::text('To', null, ['class' => 'form-control','id'=>'To']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#To').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush --}}
