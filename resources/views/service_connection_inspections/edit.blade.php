@php
    use App\Models\ServiceConnectionInspections;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Update Inspection Data</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-12">
                <div class="card shadow-none">

                    {!! Form::model($serviceConnectionInspections, ['route' => ['serviceConnectionInspections.update', $serviceConnectionInspections->id], 'method' => 'patch']) !!}

                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="id" value="{{ $serviceConnectionInspections->id }}">

                            <input type="hidden" name="ServiceConnectionId" value="{{ $serviceConnectionInspections->ServiceConnectionId }}">
                            
                            <div class="col-lg-12 table-responsive">
                                <table class="table table-hover table-borderless table-sm">
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="text-muted">LOAD PROFILE</td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Current Rate</td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" onkeyup="updateDeposit()" step="any" name="Rate" id="Rate" value="{{ $serviceConnectionInspections->Rate }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">No. of LO</td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" step="any" name="LightingOutlets" id="LightingOutlets" value="{{ $serviceConnectionInspections->LightingOutlets }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">No. of CO</td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" step="any" name="ConvenienceOutlets" id="ConvenienceOutlets" value="{{ $serviceConnectionInspections->ConvenienceOutlets }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Motor</td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" step="any" name="Motor" id="Motor" value="{{ $serviceConnectionInspections->Motor }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Total Load</td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" onkeyup="updateDeposit()" step="any" name="TotalLoad" id="TotalLoad" value="{{ $serviceConnectionInspections->TotalLoad }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Contracted Demand</td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" step="any" name="ContractedDemand" id="ContractedDemand" value="{{ $serviceConnectionInspections->ContractedDemand }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Contracted Energy</td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" step="any" name="ContractedEnergy" id="ContractedEnergy" value="{{ $serviceConnectionInspections->ContractedEnergy }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align"><strong>Bill Deposit</strong></td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" step="any" name="BillDeposit" id="BillDeposit" value="{{ $serviceConnectionInspections->BillDeposit }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Distance From Secondary</td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" step="any" name="DistanceFromSecondaryLine" id="DistanceFromSecondaryLine" value="{{ $serviceConnectionInspections->DistanceFromSecondaryLine }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Size of Secondary</td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" step="any" name="SizeOfSecondary" id="SizeOfSecondary" value="{{ $serviceConnectionInspections->SizeOfSecondary }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Load Type</td>
                                            <td class="v-align" colspan="3">
                                                <div class="input-group-radio">
                                                    <input type="radio" id="DEDICATED" name="LoadType" value="DEDICATED" class="custom-radio" {{ $serviceConnection->LoadType != null && $serviceConnection->LoadType == 'DEDICATED' ? 'checked' : '' }}>
                                                    <label for="DEDICATED" class="custom-radio-label">DEDICATED</label>
                                
                                                    <input type="radio" id="COMMON" name="LoadType" value="COMMON" class="custom-radio"  {{ $serviceConnection->LoadType != null && $serviceConnection->LoadType == 'COMMON' ? 'checked' : '' }}>
                                                    <label for="COMMON" class="custom-radio-label">COMMON</label>
                                                </div> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">MeteringType</td>
                                            <td class="v-align" colspan="3">
                                                <select name="MeteringType" id="MeteringType" class="form-control form-control-sm">
                                                    <option value="RESIDENTIAL" {{ $serviceConnectionInspections->MeteringType != null && $serviceConnectionInspections->MeteringType== 'RESIDENTIAL' ? 'selected' : '' }}>RESIDENTIAL</option>
                                                    <option value="COMMERCIAL LV" {{ $serviceConnectionInspections->MeteringType != null && $serviceConnectionInspections->MeteringType== 'COMMERCIAL LV' ? 'selected' : '' }}>COMMERCIAL LV</option>
                                                    <option value="INDUSTRIAL LV" {{ $serviceConnectionInspections->MeteringType != null && $serviceConnectionInspections->MeteringType== 'INDUSTRIAL LV' ? 'selected' : '' }}>INDUSTRIAL LV</option>
                                                    <option value="INDUSTRIAL HV" {{ $serviceConnectionInspections->MeteringType != null && $serviceConnectionInspections->MeteringType== 'INDUSTRIAL HV' ? 'selected' : '' }}>INDUSTRIAL HV</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Zone</td>
                                            <td class="v-align" colspan="3">
                                                <select name="Zone" id="Zone" class="form-control form-control-sm">
                                                    @foreach ($zones as $zone)
                                                        <option value="{{ $zone->Zone }}" {{ $serviceConnection->Zone != null && $serviceConnection->Zone == $zone->Zone ? 'checked' : '' }}>{{ $zone->Zone }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Block</td>
                                            <td class="v-align" colspan="3">
                                                <select name="Block" id="Block" class="form-control form-control-sm">
                                                    @foreach ($blocks as $block)
                                                        <option value="{{ $block->Block }}" {{ $serviceConnection->Block != null && $serviceConnection->Block == $block->Block ? 'checked' : '' }}>{{ $block->Block }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Feeder</td>
                                            <td class="v-align" colspan="3">
                                                <input type="text" name="Feeder" id="Feeder" value="{{ $serviceConnection->Feeder }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="4" class="text-muted">CIRCUIT BREAKERS</td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Main Circuit Breaker Rating</td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" step="any" name="SEMainCircuitBreakerAsInstalled" id="SEMainCircuitBreakerAsInstalled" value="{{ $serviceConnectionInspections->SEMainCircuitBreakerAsInstalled }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">No. of Branches</td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" step="any" name="SENoOfBranchesAsInstalled" id="SENoOfBranchesAsInstalled" value="{{ $serviceConnectionInspections->SENoOfBranchesAsInstalled }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="4" class="text-muted">SERVICE DROP WIRE</td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">SDW Size</td>
                                            <td class="v-align" colspan="3">
                                                <input type="text" name="SDWSizeAsInstalled" id="SDWSizeAsInstalled" value="{{ $serviceConnectionInspections->SDWSizeAsInstalled }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">SDW Length</td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" step="any" name="SDWLengthAsInstalled" id="SDWLengthAsInstalled" value="{{ $serviceConnectionInspections->SDWLengthAsInstalled }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">SDW Type</td>
                                            <td class="v-align" colspan="3">
                                                <div class="input-group-radio">
                                                    <input type="radio" id="Aluminum" name="TypeOfSDW" value="Aluminum" class="custom-radio" {{ $serviceConnectionInspections->TypeOfSDW != null && $serviceConnectionInspections->TypeOfSDW == 'Aluminum' ? 'checked' : '' }}>
                                                    <label for="Aluminum" class="custom-radio-label">Aluminum</label>
                                
                                                    <input type="radio" id="Copper" name="TypeOfSDW" value="Copper" class="custom-radio"  {{ $serviceConnectionInspections->TypeOfSDW != null && $serviceConnectionInspections->TypeOfSDW == 'Copper' ? 'checked' : '' }}>
                                                    <label for="Copper" class="custom-radio-label">Copper</label>
                                                </div> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Pole Vertical Height</td>
                                            <td class="v-align" colspan="3">
                                                <div class="input-group-radio">
                                                    <input type="radio" id="12ft" name="HeightOfSDW" value="12ft" class="custom-radio" {{ $serviceConnectionInspections->HeightOfSDW != null && $serviceConnectionInspections->HeightOfSDW == '12ft' ? 'checked' : '' }}>
                                                    <label for="12ft" class="custom-radio-label">12ft</label>
                                
                                                    <input type="radio" id="14ft" name="HeightOfSDW" value="14ft" class="custom-radio"  {{ $serviceConnectionInspections->HeightOfSDW != null && $serviceConnectionInspections->HeightOfSDW == '14ft' ? 'checked' : '' }}>
                                                    <label for="14ft" class="custom-radio-label">14ft</label>
                                                    
                                                    <input type="radio" id="20ft" name="HeightOfSDW" value="20ft" class="custom-radio"  {{ $serviceConnectionInspections->HeightOfSDW != null && $serviceConnectionInspections->HeightOfSDW == '20ft' ? 'checked' : '' }}>
                                                    <label for="20ft" class="custom-radio-label">20ft</label>
                                                    
                                                    <input type="radio" id="25ft" name="HeightOfSDW" value="25ft" class="custom-radio"  {{ $serviceConnectionInspections->HeightOfSDW != null && $serviceConnectionInspections->HeightOfSDW == '25ft' ? 'checked' : '' }}>
                                                    <label for="25ft" class="custom-radio-label">25ft</label>
                                                </div> 
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="4" class="text-muted">TRANSFORMER</td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Distance from Transformer</td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" step="any" name="DistanceFromTransformer" id="DistanceFromTransformer" value="{{ $serviceConnectionInspections->DistanceFromTransformer }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Size of Transformer (kVA)</td>
                                            <td class="v-align" colspan="3">
                                                <input type="number" step="any" name="SizeOfTransformer" id="SizeOfTransformer" value="{{ $serviceConnectionInspections->SizeOfTransformer }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Transformer ID Number</td>
                                            <td class="v-align" colspan="3">
                                                <input type="text" name="TransformerNo" id="TransformerNo" value="{{ $serviceConnectionInspections->TransformerNo }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="4" class="text-muted">SERVICE POLES</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="v-align text-muted text-center">GI</td>
                                            <td class="v-align text-muted text-center">CONCRETE</td>
                                            <td class="v-align text-muted text-center">HARD WOOD</td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Diameter</td>
                                            <td class="v-align">
                                                <input type="number" step="any" name="PoleGIEstimatedDiameter" id="PoleGIEstimatedDiameter" value="{{ $serviceConnectionInspections->PoleGIEstimatedDiameter }}" class="form-control fom-control-sm">
                                            </td>
                                            <td class="v-align">
                                                <input type="number" step="any" name="PoleConcreteEstimatedDiameter" id="PoleConcreteEstimatedDiameter" value="{{ $serviceConnectionInspections->PoleConcreteEstimatedDiameter }}" class="form-control fom-control-sm">
                                            </td>
                                            <td class="v-align">
                                                <input type="number" step="any" name="PoleHardwoodEstimatedDiameter" id="PoleHardwoodEstimatedDiameter" value="{{ $serviceConnectionInspections->PoleHardwoodEstimatedDiameter }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Height</td>
                                            <td class="v-align">
                                                <input type="number" step="any" name="PoleGIHeight" id="PoleGIHeight" value="{{ $serviceConnectionInspections->PoleGIHeight }}" class="form-control fom-control-sm">
                                            </td>
                                            <td class="v-align">
                                                <input type="number" step="any" name="PoleConcreteHeight" id="PoleConcreteHeight" value="{{ $serviceConnectionInspections->PoleConcreteHeight }}" class="form-control fom-control-sm">
                                            </td>
                                            <td class="v-align">
                                                <input type="number" step="any" name="PoleHardwoodHeight" id="PoleHardwoodHeight" value="{{ $serviceConnectionInspections->PoleHardwoodHeight }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Quantity</td>
                                            <td class="v-align">
                                                <input type="number" step="any" name="PoleGINoOfLiftPoles" id="PoleGINoOfLiftPoles" value="{{ $serviceConnectionInspections->PoleGINoOfLiftPoles }}" class="form-control fom-control-sm">
                                            </td>
                                            <td class="v-align">
                                                <input type="number" step="any" name="PoleConcreteNoOfLiftPoles" id="PoleConcreteNoOfLiftPoles" value="{{ $serviceConnectionInspections->PoleConcreteNoOfLiftPoles }}" class="form-control fom-control-sm">
                                            </td>
                                            <td class="v-align">
                                                <input type="number" step="any" name="PoleHardwoodNoOfLiftPoles" id="PoleHardwoodNoOfLiftPoles" value="{{ $serviceConnectionInspections->PoleHardwoodNoOfLiftPoles }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Pole Number</td>
                                            <td class="v-align" colspan="3">
                                                <input type="text" name="PoleNo" id="PoleNo" value="{{ $serviceConnectionInspections->PoleNo }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Pole Remarks</td>
                                            <td class="v-align" colspan="3">
                                                <input type="text" name="PoleRemarks" id="PoleRemarks" value="{{ $serviceConnectionInspections->PoleRemarks }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="4" class="text-muted">NEIGHBORING</td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Neighbor 1 Name/Acct. No</td>
                                            <td class="v-align" colspan="3">
                                                <input type="text" name="FirstNeighborName" id="FirstNeighborName" value="{{ $serviceConnectionInspections->FirstNeighborName }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Neighbor 1 Meter No.</td>
                                            <td class="v-align" colspan="3">
                                                <input type="text" name="FirstNeighborMeterSerial" id="FirstNeighborMeterSerial" value="{{ $serviceConnectionInspections->FirstNeighborMeterSerial }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Neighbor 2 Name/Acct. No</td>
                                            <td class="v-align" colspan="3">
                                                <input type="text" name="SecondNeighborName" id="SecondNeighborName" value="{{ $serviceConnectionInspections->SecondNeighborName }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Neighbor 2 Meter No.</td>
                                            <td class="v-align" colspan="3">
                                                <input type="text" name="SecondNeighborMeterSerial" id="SecondNeighborMeterSerial" value="{{ $serviceConnectionInspections->SecondNeighborMeterSerial }}" class="form-control fom-control-sm">
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="4" class="text-muted">LEGALS/PROPERTY RELATED</td>
                                        </tr>
                                        <tr>
                                            <td class="v-align" colspan="3">Is the service line passing through private property (ies)?</td>
                                            <td class="v-align">
                                                <div class="input-group-radio">
                                                    <input type="radio" id="LinePassingPrivatePropertyYes" name="LinePassingPrivateProperty" value="Yes" class="custom-radio" {{ $serviceConnectionInspections->LinePassingPrivateProperty != null && $serviceConnectionInspections->LinePassingPrivateProperty == 'Yes' ? 'checked' : '' }}>
                                                    <label for="LinePassingPrivatePropertyYes" class="custom-radio-label">Yes</label>
                                
                                                    <input type="radio" id="LinePassingPrivatePropertyNo" name="LinePassingPrivateProperty" value="No" class="custom-radio"  {{ $serviceConnectionInspections->LinePassingPrivateProperty != null && $serviceConnectionInspections->LinePassingPrivateProperty == 'No' ? 'checked' : '' }}>
                                                    <label for="LinePassingPrivatePropertyNo" class="custom-radio-label">No</label>
                                                </div> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align" colspan="3">Is there any written consent by the property owner (s)?</td>
                                            <td class="v-align">
                                                <div class="input-group-radio">
                                                    <input type="radio" id="WrittenConsentByPropertyOwnerYes" name="WrittenConsentByPropertyOwner" value="Yes" class="custom-radio" {{ $serviceConnectionInspections->WrittenConsentByPropertyOwner != null && $serviceConnectionInspections->WrittenConsentByPropertyOwner == 'Yes' ? 'checked' : '' }}>
                                                    <label for="WrittenConsentByPropertyOwnerYes" class="custom-radio-label">Yes</label>
                                
                                                    <input type="radio" id="WrittenConsentByPropertyOwnerNo" name="WrittenConsentByPropertyOwner" value="No" class="custom-radio"  {{ $serviceConnectionInspections->WrittenConsentByPropertyOwner != null && $serviceConnectionInspections->WrittenConsentByPropertyOwner == 'No' ? 'checked' : '' }}>
                                                    <label for="WrittenConsentByPropertyOwnerNo" class="custom-radio-label">No</label>
                                                </div> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align" colspan="3">Is there any obstruction of service line such as trees or antennas?</td>
                                            <td class="v-align">
                                                <div class="input-group-radio">
                                                    <input type="radio" id="ObstructionOfLinesYes" name="ObstructionOfLines" value="Yes" class="custom-radio" {{ $serviceConnectionInspections->ObstructionOfLines != null && $serviceConnectionInspections->ObstructionOfLines == 'Yes' ? 'checked' : '' }}>
                                                    <label for="ObstructionOfLinesYes" class="custom-radio-label">Yes</label>
                                
                                                    <input type="radio" id="ObstructionOfLinesNo" name="ObstructionOfLines" value="No" class="custom-radio"  {{ $serviceConnectionInspections->ObstructionOfLines != null && $serviceConnectionInspections->ObstructionOfLines == 'No' ? 'checked' : '' }}>
                                                    <label for="ObstructionOfLinesNo" class="custom-radio-label">No</label>
                                                </div> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align" colspan="3">Is the service line passing across the roads?</td>
                                            <td class="v-align">
                                                <div class="input-group-radio">
                                                    <input type="radio" id="LinePassingRoadsYes" name="LinePassingRoads" value="Yes" class="custom-radio" {{ $serviceConnectionInspections->LinePassingRoads != null && $serviceConnectionInspections->LinePassingRoads == 'Yes' ? 'checked' : '' }}>
                                                    <label for="LinePassingRoadsYes" class="custom-radio-label">Yes</label>
                                
                                                    <input type="radio" id="LinePassingRoadsNo" name="LinePassingRoads" value="No" class="custom-radio"  {{ $serviceConnectionInspections->LinePassingRoads != null && $serviceConnectionInspections->LinePassingRoads == 'No' ? 'checked' : '' }}>
                                                    <label for="LinePassingRoadsNo" class="custom-radio-label">No</label>
                                                </div> 
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="4" class="text-muted">ASSESSMENT</td>
                                        </tr>
                                        <tr>
                                            <td class="v-align" colspan="2">Approved</td>
                                            <td class="v-align" colspan="2">
                                                <div class="input-group-radio">
                                                    <input type="radio" id="StatusApproved" name="Status" value="Approved" class="custom-radio" {{ $serviceConnectionInspections->Status != null && $serviceConnectionInspections->Status == 'Approved' ? 'checked' : '' }}>
                                                    <label for="StatusApproved" class="custom-radio-label">Approved</label>
                                
                                                    <input type="radio" id="StatusReInspection" name="Status" value="Re-Inspection" class="custom-radio"  {{ $serviceConnectionInspections->Status != null && $serviceConnectionInspections->Status == 'Re-Inspection' ? 'checked' : '' }}>
                                                    <label for="StatusReInspection" class="custom-radio-label">Re-Inspection</label>
                                                </div> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align">Remarks</td>
                                            <td class="v-align" colspan="3">
                                                <textarea type="text" name="Notes" id="Notes" class="form-control fom-control-sm">{{ $serviceConnectionInspections->Notes }}</textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{{ route('serviceConnectionInspections.index') }}" class="btn btn-default">Cancel</a>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
        
    </div>
@endsection

@push('page_scripts')
    <script>
        function updateDeposit() {
            getContractedDemand()
            getContractedEnergy()
            getBillDeposit()
        }

        function getContractedDemand() {
            var totalLoad = $('#TotalLoad').val()

            if (isNull(totalLoad)) {
                $('#ContractedDemand').val(0)
            } else {
                var demand = parseFloat(totalLoad) * parseFloat('{{ ServiceConnectionInspections::df() }}') * parseFloat('{{ ServiceConnectionInspections::df() }}')
                $('#ContractedDemand').val(round(demand))
            }
        }

        function getContractedEnergy() {
            var rate = $('#Rate').val()
            var totalLoad = $('#TotalLoad').val()

            if (isNull(rate) | isNull(totalLoad)) {
                $('#ContractedEnergy').val(0)
            } else {
                var demand = parseFloat(totalLoad) * parseFloat('{{ ServiceConnectionInspections::df() }}') * parseFloat('{{ ServiceConnectionInspections::df() }}')
                var energy = (demand / 1000) * (8 * 30 * parseFloat(rate))
                $('#ContractedEnergy').val(round(energy))
            }
        }

        function getBillDeposit() {
            var pfCom = parseFloat('{{ ServiceConnectionInspections::pfCommercial() }}')
            var pfRes = parseFloat('{{ ServiceConnectionInspections::pfResidential() }}')
            var df = parseFloat('{{ ServiceConnectionInspections::df() }}')
            var commercialThreshold = parseFloat('{{ ServiceConnectionInspections::commercialThreshold() }}')
            var residentialThreshold = parseFloat('{{ ServiceConnectionInspections::resdidentialThreshold() }}')
            var type = '{{ $serviceConnection->AccountType }}'
            var totalLoad = $('#TotalLoad').val()
            var rate = $('#Rate').val()
           
            if (isNull(totalLoad) | isNull(rate)) {
                $('#BillDeposit').val('0')
            } else {
                totalLoad = parseFloat(totalLoad)
                rate = parseFloat(rate)

                if (!isNull(type) && type === 'COMMERCIAL') {
                    if (totalLoad > commercialThreshold) {
                        var excess = totalLoad - commercialThreshold
                        var excessPf = excess * pfCom
                        var wattage = (excessPf + commercialThreshold) * df
                        var deposit = (wattage * rate * 8 * 26) / 1000
                        $('#BillDeposit').val(round(deposit))
                    } else {
                        var wattage = totalLoad * df
                        var deposit = (wattage * rate * 8 * 26) / 1000
                        $('#BillDeposit').val(round(deposit))
                    }
                } else if (!isNull(type) && type === 'RESIDENTIAL') {
                    if (totalLoad > residentialThreshold) {
                        var excess = totalLoad - residentialThreshold
                        var excessPf = excess * pfRes
                        var wattage = (excessPf + residentialThreshold) * df
                        var deposit = (wattage * rate * 8 * 30) / 1000
                        $('#BillDeposit').val(round(deposit))
                    } else {
                        var wattage = totalLoad * df
                        var deposit = (wattage * rate * 8 * 30) / 1000
                        $('#BillDeposit').val(round(deposit))
                    }
                } else {
                    $('#BillDeposit').val('0')
                }
            }
        }
    </script>
@endpush
