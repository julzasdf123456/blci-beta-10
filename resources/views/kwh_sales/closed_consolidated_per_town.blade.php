@php    
    use Illuminate\Support\Facades\DB;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-lg-6">
                <h4>Sales Consolidated Per Town - {{ date('F Y', strtotime($period)) }}</h4>
            </div>
            <div class="col-lg-6">
                <form class="row" action="{{ route('kwhSales.consolidated-per-town', $period) }}" method="GET">
                    <div class="col-lg-5">
                        <label for="" class="text-right float-right">Select Town</label>
                    </div>
                    <div class="col-lg-4">
                        <select name="Town" id="" class="form-control">
                            <option value="">All</option>
                            @foreach ($towns as $item)
                                <option value="{{ $item->id }}" {{ isset($_GET['Town']) && $_GET['Town'] != null ? ($_GET['Town']==$item->id ? 'selected' : '') : '' }}>{{ $item->Town }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-12">
        @if (isset($_GET['Town']) && $_GET['Town'] != null)
            {{-- PER TOWN --}}
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th colspan="2" class="text-center">RESIDENTIAL</th>
                        <th colspan="2" class="text-center">COMMERCIAL</th>
                        <th></th>
                        <th colspan="2" class="text-center">INDUSTRIAL</th>
                        <th></th>
                        <th colspan="2" class="text-center">PUBLIC BUILDINGS</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th rowspan="2">Reference</th>
                        <th class="text-right">Poblacion</th>
                        <th class="text-right">Rural</th>
                        <th class="text-right">Low Voltage</th>
                        <th class="text-right">High Voltage</th>
                        <th rowspan="2" class="text-center">Irrigation</th>
                        <th class="text-right">Low Voltage</th>
                        <th class="text-right">High Voltage</th>
                        <th rowspan="2" class="text-center">Street Lights</th>
                        <th class="text-right">Low Voltage</th>
                        <th class="text-right">High Voltage</th>
                        <th rowspan="2" class="text-center">Communal</th>                    
                        <th rowspan="2" class="text-center">Total Amount</th>
                    </tr>
                    
                    
                </thead>
                <tbody>
                    <tr>
                        <th>GENERATION AND TRANSMISSION CHARGES:</th>
                    </tr>
                    <tr>
                        @php
                            $generationSystem = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Generation System</td>
                        <td class="text-right">{{ number_format($generationSystem->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($generationSystem->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $transmissionSystemKw = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Transmission Delivery Charge (kW)</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($transmissionSystemKw->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $transmissionSystemKwh = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Transmission Delivery Charge (kWH)</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $systemLossCharge = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">System Loss Charge</td>
                        <td class="text-right">{{ number_format($systemLossCharge->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($systemLossCharge->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $ogaKwh = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <th style="padding-left: 40px;">Other Generation Rate Adjustment (OGA) (KWH)</th>
                        <td class="text-right">{{ number_format($ogaKwh->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($ogaKwh->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $otcaKw = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <th style="padding-left: 40px;">Other Transmission Cost Adjustment (OTCA) (KW)</th>
                        <td class="text-right">{{ number_format($otcaKw->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($otcaKw->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $otcaKwh = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <th style="padding-left: 40px;">Other Transmission Cost Adjustment (OTCA) (KWH)</th>
                        <td class="text-right">{{ number_format($otcaKwh->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($otcaKwh->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $osla = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <th style="padding-left: 40px;">Other System Loss Cost Adjustment (OSLA) (KWH)</th>
                        <td class="text-right">{{ number_format($osla->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($osla->TotalAmount, 2) }}</td>
                    </tr>
                    {{-- <tr>
                        @php
                            $totalGenTransResidential = floatval($osla->Residential) + 
                                floatval($otcaKwh->Residential) + 
                                floatval($otcaKw->Residential) + 
                                floatval($ogaKwh->Residential) + 
                                floatval($systemLossCharge->Residential) + 
                                floatval($transmissionSystemKwh->Residential) + 
                                floatval($transmissionSystemKw->Poblacion) + 
                                floatval($generationSystem->Poblacion);
                            $totalGenTransLowVoltage = floatval($osla->LowVoltage) + 
                                floatval($otcaKwh->LowVoltage) + 
                                floatval($otcaKw->LowVoltage) + 
                                floatval($ogaKwh->LowVoltage) + 
                                floatval($systemLossCharge->LowVoltage) + 
                                floatval($transmissionSystemKwh->LowVoltage) + 
                                // floatval($transmissionSystemKw->LowVoltage) + 
                                floatval($generationSystem->LowVoltage);
                            $totalGenTransHighVoltage = 
                                floatval($osla->HighVoltage) + 
                                floatval($otcaKwh->HighVoltage) + 
                                floatval($otcaKw->HighVoltage) + 
                                floatval($ogaKwh->HighVoltage) + 
                                floatval($systemLossCharge->HighVoltage) + 
                                floatval($transmissionSystemKwh->HighVoltage) + 
                                // floatval($transmissionSystemKw->HighVoltage) + 
                                floatval($generationSystem->HighVoltage);
                            $totalGenTrans = floatval($osla->TotalAmount) + 
                                floatval($otcaKwh->TotalAmount) + 
                                floatval($otcaKw->TotalAmount) + 
                                floatval($ogaKwh->TotalAmount) + 
                                floatval($systemLossCharge->TotalAmount) + 
                                floatval($transmissionSystemKwh->TotalAmount) + 
                                // floatval($transmissionSystemKw->TotalAmount) + 
                                floatval($generationSystem->TotalAmount);
                        @endphp
                        <th class="text-right">Sub-total</th>
                        <th class="text-right">{{ number_format($totalGenTransResidential, 2) }}</th>
                        <th class="text-right">{{ number_format($totalGenTransLowVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalGenTransHighVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalGenTrans, 2) }}</th>
                    </tr> --}}
                    <tr>
                        <th>DISTRIBUTION/SUPPLY/METERING CHARGES:</th>
                    </tr>
                    <tr>
                        @php
                            $distDemandCharge = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Distribution Demand Charge</td>
                        <td class="text-right">{{ number_format($distDemandCharge->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($distDemandCharge->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $distSystemCharge = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Distribution System Charge</td>
                        <td class="text-right">{{ number_format($distSystemCharge->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($distSystemCharge->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $supplyRetCustCharge = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Supply Retail Customer Charge</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $supplySystemCharge = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Supply System Charge</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($supplySystemCharge->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $meteringRetCharge = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Metering Retail Customer Charge</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($meteringRetCharge->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $meteringSystemCharge = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Metering System Charge</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($meteringSystemCharge->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $rfsc = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Reinvestment Fund For Sust. CAPEX (RFSC)</td>
                        <td class="text-right">{{ number_format($rfsc->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($rfsc->TotalAmount, 2) }}</td>
                    </tr>
                    {{-- <tr>
                        @php
                            $totalDistResidential = floatval($rfsc->Residential) + floatval($meteringSystemCharge->Residential) + floatval($meteringRetCharge->Residential) + floatval($supplySystemCharge->Residential) + floatval($supplyRetCustCharge->Residential) + floatval($distSystemCharge->Residential) + floatval($distDemandCharge->Residential);
                            $totalDistLowVoltage = floatval($rfsc->LowVoltage) + floatval($meteringSystemCharge->LowVoltage) + floatval($meteringRetCharge->LowVoltage) + floatval($supplySystemCharge->LowVoltage) + floatval($supplyRetCustCharge->LowVoltage) + floatval($distSystemCharge->LowVoltage) + floatval($distDemandCharge->LowVoltage);
                            $totalDistHighVoltage = floatval($rfsc->HighVoltage) + floatval($meteringSystemCharge->HighVoltage) + floatval($meteringRetCharge->HighVoltage) + floatval($supplySystemCharge->HighVoltage) + floatval($supplyRetCustCharge->HighVoltage) + floatval($distSystemCharge->HighVoltage) + floatval($distDemandCharge->HighVoltage);
                            $totalDist = floatval($rfsc->TotalAmount) + floatval($meteringSystemCharge->TotalAmount) + floatval($meteringRetCharge->TotalAmount) + floatval($supplySystemCharge->TotalAmount) + floatval($supplyRetCustCharge->TotalAmount) + floatval($distSystemCharge->TotalAmount) + floatval($distDemandCharge->TotalAmount);
                        @endphp
                        <th class="text-right">Sub-total</th>
                        <th class="text-right">{{ number_format($totalDistResidential, 2) }}</th>
                        <th class="text-right">{{ number_format($totalDistLowVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalDistHighVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalDist, 2) }}</th>
                    </tr> --}}
                    <tr>
                        <th>OTHERS:</th>
                    </tr>
                    <tr>
                        @php
                            $lifelineRate = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Lifeline Rate (Discount/Subsidy)</td>
                        <td class="text-right">{{ number_format($lifelineRate->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($lifelineRate->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $iccSubsidy = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Inter-Class Cross Subsidy Charge</td>
                        <td class="text-right">{{ number_format($iccSubsidy->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($iccSubsidy->TotalAmount, 2) }}</td>
                    </tr>
                    {{-- <tr>
                        @php
                            $totalOthersResidential = floatval($iccSubsidy->Residential) + floatval($lifelineRate->Residential);
                            $totalOthersLowVoltage = floatval($iccSubsidy->LowVoltage) + floatval($lifelineRate->LowVoltage);
                            $totalOthersHighVoltage = floatval($iccSubsidy->HighVoltage) + floatval($lifelineRate->HighVoltage);
                            $totalOthers = floatval($iccSubsidy->TotalAmount) + floatval($lifelineRate->TotalAmount);
                        @endphp
                        <th class="text-right">Sub-total</th>
                        <th class="text-right">{{ number_format($totalOthersResidential, 2) }}</th>
                        <th class="text-right">{{ number_format($totalOthersLowVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalOthersHighVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalOthers, 2) }}</th>
                    </tr>         --}}
                    <tr>
                        @php
                            $rpt = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Real Property Tax (RPT)</td>
                        <td class="text-right">{{ number_format($rpt->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($rpt->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>UNIVERSAL CHARGE:</th>
                    </tr>
                    <tr>
                        @php
                            $missionary = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Missionary Electrification Charge</td>
                        <td class="text-right">{{ number_format($missionary->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($missionary->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $environmental = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Environmental Charge</td>
                        <td class="text-right">{{ number_format($environmental->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($environmental->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $strandedcc = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Stranded Contract Costs</td>
                        <td class="text-right">{{ number_format($strandedcc->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($strandedcc->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $redci = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">REDCI</td>
                        <td class="text-right">{{ number_format($redci->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($redci->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $fitAll = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Feed-inTariff Allowance</td>
                        <td class="text-right">{{ number_format($fitAll->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($fitAll->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 40px;">NPC Contract Cost</td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                    </tr>
                    <tr>
                        @php
                            $npcStrandedDebt = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">NPC Stranded Debt</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($npcStrandedDebt->TotalAmount, 2) }}</td>
                    </tr>
                    {{-- <tr>
                        @php
                            $totalUnivResidential = floatval($npcStrandedDebt->Residential) + floatval($fitAll->Residential) + floatval($strandedcc->Residential) + floatval($environmental->Residential) + floatval($missionary->Residential);
                            $totalUnivLowVoltage = floatval($npcStrandedDebt->LowVoltage) + floatval($fitAll->LowVoltage) + floatval($strandedcc->LowVoltage) + floatval($environmental->LowVoltage) + floatval($missionary->LowVoltage);
                            $totalUnivHighVoltage = floatval($npcStrandedDebt->HighVoltage) + floatval($fitAll->HighVoltage) + floatval($strandedcc->HighVoltage) + floatval($environmental->HighVoltage) + floatval($missionary->HighVoltage);
                            $totalUniv = floatval($npcStrandedDebt->TotalAmount) + floatval($fitAll->TotalAmount) + floatval($strandedcc->TotalAmount) + floatval($environmental->TotalAmount) + floatval($missionary->TotalAmount);
                        @endphp
                        <th class="text-right">Sub-total</th>
                        <th class="text-right">{{ number_format($totalUnivResidential, 2) }}</th>
                        <th class="text-right">{{ number_format($totalUnivLowVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalUnivHighVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalUniv, 2) }}</th>
                    </tr>   --}}
                    <tr>
                        <td style="padding-left: 40px;">UC Refund <strong>(?)</strong></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                    </tr>
                    <tr>
                        @php
                            $scDisc = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Senior Citizen Discount</td>
                        <td class="text-right">{{ number_format($scDisc->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($scDisc->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $scSubsidy = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Senior Citizen Subsidy</td>
                        <td class="text-right">{{ number_format($scSubsidy->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($scSubsidy->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $scAdj = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <th style="padding-left: 40px;">Senior Citizen Discount & Subsidy Adjustment (KWH)</th>
                        <td class="text-right">{{ number_format($scAdj->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($scAdj->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $othersAdj = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <th style="padding-left: 40px;">Other Lifeline Rate Cost Adjustment (OLRA) (KWH)</th>
                        <td class="text-right">{{ number_format($othersAdj->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($othersAdj->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>GOVERNMENT REVENUES:</th>
                    </tr>
                    <tr>
                        @php
                            $genVat = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">VAT - Generation</td>
                        <td class="text-right">{{ number_format($genVat->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($genVat->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $transVat = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">VAT - Transmission</td>
                        <td class="text-right">{{ number_format($transVat->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($transVat->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $sysLossVat = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">VAT - System Loss</td>
                        <td class="text-right">{{ number_format($sysLossVat->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($sysLossVat->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $distVat = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">VAT - Distribution & Others</td>
                        <td class="text-right">{{ number_format($distVat->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($distVat->TotalAmount, 2) }}</td>
                    </tr>
                    {{-- <tr>
                        @php
                            $totalVatResidential = floatval($distVat->Residential) + floatval($sysLossVat->Residential) + floatval($transVat->Residential) + floatval($genVat->Residential);
                            $totalVatLowVoltage = floatval($distVat->LowVoltage) + floatval($sysLossVat->LowVoltage) + floatval($transVat->LowVoltage) + floatval($genVat->LowVoltage);
                            $totalVatHighVoltage = floatval($distVat->HighVoltage) + floatval($sysLossVat->HighVoltage) + floatval($transVat->HighVoltage) + floatval($genVat->HighVoltage);
                            $totalVat = floatval($distVat->TotalAmount) + floatval($sysLossVat->TotalAmount) + floatval($transVat->TotalAmount) + floatval($genVat->TotalAmount);
                        @endphp
                        <th class="text-right">Sub-total</th>
                        <th class="text-right">{{ number_format($totalVatResidential, 2) }}</th>
                        <th class="text-right">{{ number_format($totalVatLowVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalVatHighVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalVat, 2) }}</th>
                    </tr>  --}}
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    {{-- <tr>
                        @php
                            $grandTotalResidential = $totalGenTransResidential + $totalDistResidential + $totalOthersResidential + floatval($rpt->Residential) + $totalUnivResidential + floatval($scDisc->Residential) + floatval($scSubsidy->Residential) + floatval($scAdj->Residential) + floatval($othersAdj->Residential) + $totalVatResidential;
                            $grandTotalLowVoltage = $totalGenTransLowVoltage + $totalDistLowVoltage + $totalOthersLowVoltage + floatval($rpt->LowVoltage) + $totalUnivLowVoltage + floatval($scDisc->LowVoltage) + floatval($scSubsidy->LowVoltage) + floatval($scAdj->LowVoltage) + floatval($othersAdj->LowVoltage) + $totalVatLowVoltage;
                            $grandTotalHighVoltage = $totalGenTransHighVoltage + $totalDistHighVoltage + $totalOthersHighVoltage + floatval($rpt->HighVoltage) + $totalUnivHighVoltage + floatval($scDisc->HighVoltage) + floatval($scSubsidy->HighVoltage) + floatval($scAdj->HighVoltage) + floatval($othersAdj->HighVoltage) + $totalVatHighVoltage;
                            $grandTotal = $totalGenTrans + $totalDist + $totalOthers + floatval($rpt->TotalAmount) + $totalUniv + floatval($scDisc->TotalAmount) + floatval($scSubsidy->TotalAmount) + floatval($scAdj->TotalAmount) + floatval($othersAdj->TotalAmount) + $totalVat;
                        @endphp
                        <th class="text-right">GRAND TOTAL</th>
                        <th class="text-right">{{ number_format($grandTotalResidential, 2) }}</th>
                        <th class="text-right">{{ number_format($grandTotalLowVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($grandTotalHighVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($grandTotal, 2) }}</th>
                    </tr>  --}}
                    <tr>
                        @php
                            $totalQry = DB::table("Billing_Bills")
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('RESIDENTIAL')) AS TotalResidential"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TotalResidentialRural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('COMMERCIAL')) AS TotalCommercial"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TotalCommercialHv"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TotalIrrigation"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('INDUSTRIAL')) AS TotalIndustrial"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TotalIndustrialHv"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('STREET LIGHTS')) AS TotalStreetlights"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('PUBLIC BUILDING')) AS TotalPB"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TotalPBHv"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS Total"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('RESIDENTIAL')) AS TotalResidentialDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TotalResidentialRuralDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('COMMERCIAL')) AS TotalCommercialDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TotalCommercialHvDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TotalIrrigationDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('INDUSTRIAL')) AS TotalIndustrialDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TotalIndustrialHvDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('STREET LIGHTS')) AS TotalStreetlightsDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('PUBLIC BUILDING')) AS TotalPBDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TotalPBHvDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND AccountNumber LIKE '" . $_GET['Town'] . "%' AND ServicePeriod='" . $period . "') AS TotalDemand"),
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <th class="text-right">TOTAL KWH USED</th>
                        <th class="text-right">{{ number_format($totalQry->TotalResidential, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalResidentialRural, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalCommercial, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalCommercialHv, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalIrrigation, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalIndustrial, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalIndustrialHv, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalStreetlights, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalPB, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalPBHv, 2) }}</th>
                        <th></th>
                        <th class="text-right">{{ number_format($totalQry->Total, 2) }}</th>
                    </tr> 
                    <tr>
                        <th class="text-right">TOTAL DEMAND KW</th>
                        <th class="text-right">{{ number_format($totalQry->TotalResidentialDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalResidentialRuralDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalCommercialDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalCommercialHvDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalIrrigationDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalIndustrialDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalIndustrialHvDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalStreetlightsDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalPBDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalPBHvDemand, 2) }}</th>
                        <th></th>
                        <th class="text-right"></th>
                    </tr> 
                </tbody>
            </table>
        @else
            {{-- ALL --}}
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th colspan="2" class="text-center">RESIDENTIAL</th>
                        <th colspan="2" class="text-center">COMMERCIAL</th>
                        <th></th>
                        <th colspan="2" class="text-center">INDUSTRIAL</th>
                        <th></th>
                        <th colspan="2" class="text-center">PUBLIC BUILDINGS</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th rowspan="2">Reference</th>
                        <th class="text-right">Poblacion</th>
                        <th class="text-right">Rural</th>
                        <th class="text-right">Low Voltage</th>
                        <th class="text-right">High Voltage</th>
                        <th rowspan="2" class="text-center">Irrigation</th>
                        <th class="text-right">Low Voltage</th>
                        <th class="text-right">High Voltage</th>
                        <th rowspan="2" class="text-center">Street Lights</th>
                        <th class="text-right">Low Voltage</th>
                        <th class="text-right">High Voltage</th>
                        <th rowspan="2" class="text-center">Communal</th>                    
                        <th rowspan="2" class="text-center">Total Amount</th>
                    </tr>
                    
                    
                </thead>
                <tbody>
                    <tr>
                        <th>GENERATION AND TRANSMISSION CHARGES:</th>
                    </tr>
                    <tr>
                        @php
                            $generationSystem = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Generation System</td>
                        <td class="text-right">{{ number_format($generationSystem->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($generationSystem->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($generationSystem->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $transmissionSystemKw = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Transmission Delivery Charge (kW)</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKw->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($transmissionSystemKw->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $transmissionSystemKwh = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionDeliveryChargeKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Transmission Delivery Charge (kWH)</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($transmissionSystemKwh->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $systemLossCharge = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">System Loss Charge</td>
                        <td class="text-right">{{ number_format($systemLossCharge->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($systemLossCharge->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($systemLossCharge->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $ogaKwh = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <th style="padding-left: 40px;">Other Generation Rate Adjustment (OGA) (KWH)</th>
                        <td class="text-right">{{ number_format($ogaKwh->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($ogaKwh->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($ogaKwh->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $otcaKw = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <th style="padding-left: 40px;">Other Transmission Cost Adjustment (OTCA) (KW)</th>
                        <td class="text-right">{{ number_format($otcaKw->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKw->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($otcaKw->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $otcaKwh = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <th style="padding-left: 40px;">Other Transmission Cost Adjustment (OTCA) (KWH)</th>
                        <td class="text-right">{{ number_format($otcaKwh->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($otcaKwh->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($otcaKwh->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $osla = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <th style="padding-left: 40px;">Other System Loss Cost Adjustment (OSLA) (KWH)</th>
                        <td class="text-right">{{ number_format($osla->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($osla->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($osla->TotalAmount, 2) }}</td>
                    </tr>
                    {{-- <tr>
                        @php
                            $totalGenTransResidential = floatval($osla->Residential) + 
                                floatval($otcaKwh->Residential) + 
                                floatval($otcaKw->Residential) + 
                                floatval($ogaKwh->Residential) + 
                                floatval($systemLossCharge->Residential) + 
                                floatval($transmissionSystemKwh->Residential) + 
                                floatval($transmissionSystemKw->Poblacion) + 
                                floatval($generationSystem->Poblacion);
                            $totalGenTransLowVoltage = floatval($osla->LowVoltage) + 
                                floatval($otcaKwh->LowVoltage) + 
                                floatval($otcaKw->LowVoltage) + 
                                floatval($ogaKwh->LowVoltage) + 
                                floatval($systemLossCharge->LowVoltage) + 
                                floatval($transmissionSystemKwh->LowVoltage) + 
                                // floatval($transmissionSystemKw->LowVoltage) + 
                                floatval($generationSystem->LowVoltage);
                            $totalGenTransHighVoltage = 
                                floatval($osla->HighVoltage) + 
                                floatval($otcaKwh->HighVoltage) + 
                                floatval($otcaKw->HighVoltage) + 
                                floatval($ogaKwh->HighVoltage) + 
                                floatval($systemLossCharge->HighVoltage) + 
                                floatval($transmissionSystemKwh->HighVoltage) + 
                                // floatval($transmissionSystemKw->HighVoltage) + 
                                floatval($generationSystem->HighVoltage);
                            $totalGenTrans = floatval($osla->TotalAmount) + 
                                floatval($otcaKwh->TotalAmount) + 
                                floatval($otcaKw->TotalAmount) + 
                                floatval($ogaKwh->TotalAmount) + 
                                floatval($systemLossCharge->TotalAmount) + 
                                floatval($transmissionSystemKwh->TotalAmount) + 
                                // floatval($transmissionSystemKw->TotalAmount) + 
                                floatval($generationSystem->TotalAmount);
                        @endphp
                        <th class="text-right">Sub-total</th>
                        <th class="text-right">{{ number_format($totalGenTransResidential, 2) }}</th>
                        <th class="text-right">{{ number_format($totalGenTransLowVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalGenTransHighVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalGenTrans, 2) }}</th>
                    </tr> --}}
                    <tr>
                        <th>DISTRIBUTION/SUPPLY/METERING CHARGES:</th>
                    </tr>
                    <tr>
                        @php
                            $distDemandCharge = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionDemandCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Distribution Demand Charge</td>
                        <td class="text-right">{{ number_format($distDemandCharge->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distDemandCharge->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($distDemandCharge->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $distSystemCharge = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Distribution System Charge</td>
                        <td class="text-right">{{ number_format($distSystemCharge->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distSystemCharge->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($distSystemCharge->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $supplyRetCustCharge = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplyRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Supply Retail Customer Charge</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($supplyRetCustCharge->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $supplySystemCharge = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(SupplySystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Supply System Charge</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($supplySystemCharge->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($supplySystemCharge->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $meteringRetCharge = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringRetailCustomerCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Metering Retail Customer Charge</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringRetCharge->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($meteringRetCharge->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $meteringSystemCharge = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(MeteringSystemCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Metering System Charge</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($meteringSystemCharge->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($meteringSystemCharge->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $rfsc = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(RFSC AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">RFSC</td>
                        <td class="text-right">{{ number_format($rfsc->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rfsc->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($rfsc->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 40px;">Member's Contribution for CAPEX <strong>(?)</strong></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                    </tr>
                    {{-- <tr>
                        @php
                            $totalDistResidential = floatval($rfsc->Residential) + floatval($meteringSystemCharge->Residential) + floatval($meteringRetCharge->Residential) + floatval($supplySystemCharge->Residential) + floatval($supplyRetCustCharge->Residential) + floatval($distSystemCharge->Residential) + floatval($distDemandCharge->Residential);
                            $totalDistLowVoltage = floatval($rfsc->LowVoltage) + floatval($meteringSystemCharge->LowVoltage) + floatval($meteringRetCharge->LowVoltage) + floatval($supplySystemCharge->LowVoltage) + floatval($supplyRetCustCharge->LowVoltage) + floatval($distSystemCharge->LowVoltage) + floatval($distDemandCharge->LowVoltage);
                            $totalDistHighVoltage = floatval($rfsc->HighVoltage) + floatval($meteringSystemCharge->HighVoltage) + floatval($meteringRetCharge->HighVoltage) + floatval($supplySystemCharge->HighVoltage) + floatval($supplyRetCustCharge->HighVoltage) + floatval($distSystemCharge->HighVoltage) + floatval($distDemandCharge->HighVoltage);
                            $totalDist = floatval($rfsc->TotalAmount) + floatval($meteringSystemCharge->TotalAmount) + floatval($meteringRetCharge->TotalAmount) + floatval($supplySystemCharge->TotalAmount) + floatval($supplyRetCustCharge->TotalAmount) + floatval($distSystemCharge->TotalAmount) + floatval($distDemandCharge->TotalAmount);
                        @endphp
                        <th class="text-right">Sub-total</th>
                        <th class="text-right">{{ number_format($totalDistResidential, 2) }}</th>
                        <th class="text-right">{{ number_format($totalDistLowVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalDistHighVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalDist, 2) }}</th>
                    </tr> --}}
                    <tr>
                        <th>OTHERS:</th>
                    </tr>
                    <tr>
                        @php
                            $lifelineRate = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(LifelineRate AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Lifeline Rate (Discount/Subsidy)</td>
                        <td class="text-right">{{ number_format($lifelineRate->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($lifelineRate->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($lifelineRate->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $iccSubsidy = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(InterClassCrossSubsidyCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Inter-Class Cross Subsidy Charge</td>
                        <td class="text-right">{{ number_format($iccSubsidy->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($iccSubsidy->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($iccSubsidy->TotalAmount, 2) }}</td>
                    </tr>
                    {{-- <tr>
                        @php
                            $totalOthersResidential = floatval($iccSubsidy->Residential) + floatval($lifelineRate->Residential);
                            $totalOthersLowVoltage = floatval($iccSubsidy->LowVoltage) + floatval($lifelineRate->LowVoltage);
                            $totalOthersHighVoltage = floatval($iccSubsidy->HighVoltage) + floatval($lifelineRate->HighVoltage);
                            $totalOthers = floatval($iccSubsidy->TotalAmount) + floatval($lifelineRate->TotalAmount);
                        @endphp
                        <th class="text-right">Sub-total</th>
                        <th class="text-right">{{ number_format($totalOthersResidential, 2) }}</th>
                        <th class="text-right">{{ number_format($totalOthersLowVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalOthersHighVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalOthers, 2) }}</th>
                    </tr>         --}}
                    <tr>
                        @php
                            $rpt = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(RealPropertyTax AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Real Property Tax (RPT)</td>
                        <td class="text-right">{{ number_format($rpt->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($rpt->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($rpt->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>UNIVERSAL CHARGE:</th>
                    </tr>
                    <tr>
                        @php
                            $missionary = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Missionary Electrification Charge</td>
                        <td class="text-right">{{ number_format($missionary->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($missionary->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($missionary->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $environmental = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(EnvironmentalCharge AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Environmental Charge</td>
                        <td class="text-right">{{ number_format($environmental->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($environmental->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($environmental->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $strandedcc = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(StrandedContractCosts AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Stranded Contract Costs</td>
                        <td class="text-right">{{ number_format($strandedcc->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($strandedcc->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($strandedcc->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $redci = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(MissionaryElectrificationREDCI AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">REDCI</td>
                        <td class="text-right">{{ number_format($redci->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($redci->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($redci->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $fitAll = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(FeedInTariffAllowance AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Feed-inTariff Allowance</td>
                        <td class="text-right">{{ number_format($fitAll->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($fitAll->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($fitAll->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 40px;">NPC Contract Cost <strong>(?)</strong></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                    </tr>
                    <tr>
                        @php
                            $npcStrandedDebt = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(NPCStrandedDebt AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">NPC Stranded Debt</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($npcStrandedDebt->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($npcStrandedDebt->TotalAmount, 2) }}</td>
                    </tr>
                    {{-- <tr>
                        @php
                            $totalUnivResidential = floatval($npcStrandedDebt->Residential) + floatval($fitAll->Residential) + floatval($strandedcc->Residential) + floatval($environmental->Residential) + floatval($missionary->Residential);
                            $totalUnivLowVoltage = floatval($npcStrandedDebt->LowVoltage) + floatval($fitAll->LowVoltage) + floatval($strandedcc->LowVoltage) + floatval($environmental->LowVoltage) + floatval($missionary->LowVoltage);
                            $totalUnivHighVoltage = floatval($npcStrandedDebt->HighVoltage) + floatval($fitAll->HighVoltage) + floatval($strandedcc->HighVoltage) + floatval($environmental->HighVoltage) + floatval($missionary->HighVoltage);
                            $totalUniv = floatval($npcStrandedDebt->TotalAmount) + floatval($fitAll->TotalAmount) + floatval($strandedcc->TotalAmount) + floatval($environmental->TotalAmount) + floatval($missionary->TotalAmount);
                        @endphp
                        <th class="text-right">Sub-total</th>
                        <th class="text-right">{{ number_format($totalUnivResidential, 2) }}</th>
                        <th class="text-right">{{ number_format($totalUnivLowVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalUnivHighVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalUniv, 2) }}</th>
                    </tr>   --}}
                    <tr>
                        <td style="padding-left: 40px;">UC Refund <strong>(?)</strong></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                    </tr>
                    <tr>
                        @php
                            $scDisc = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0 AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) < 0) AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Senior Citizen Discount</td>
                        <td class="text-right">{{ number_format($scDisc->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scDisc->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($scDisc->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $scSubsidy = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0 AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND  TRY_CAST(SeniorCitizenSubsidy AS decimal(10,2)) > 0) AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">Senior Citizen Subsidy</td>
                        <td class="text-right">{{ number_format($scSubsidy->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scSubsidy->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($scSubsidy->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $scAdj = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <th style="padding-left: 40px;">Senior Citizen Discount & Subsidy Adjustment (KWH)</th>
                        <td class="text-right">{{ number_format($scAdj->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($scAdj->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($scAdj->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $othersAdj = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <th style="padding-left: 40px;">Other Lifeline Rate Cost Adjustment (OLRA) (KWH)</th>
                        <td class="text-right">{{ number_format($othersAdj->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($othersAdj->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($othersAdj->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>GOVERNMENT REVENUES:</th>
                    </tr>
                    <tr>
                        @php
                            $genVat = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(GenerationVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">VAT - Generation</td>
                        <td class="text-right">{{ number_format($genVat->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($genVat->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($genVat->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $transVat = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(TransmissionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">VAT - Transmission</td>
                        <td class="text-right">{{ number_format($transVat->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($transVat->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($transVat->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $sysLossVat = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(SystemLossVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">VAT - System Loss</td>
                        <td class="text-right">{{ number_format($sysLossVat->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($sysLossVat->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($sysLossVat->TotalAmount, 2) }}</td>
                    </tr>
                    <tr>
                        @php
                            $distVat = DB::table('Billing_Rates')
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS Poblacion"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS Rural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS ComLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS ComHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS WaterSystems"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS IndLowVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS IndHighVoltage"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS StreetLights"), 
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS PbLowVoltage"),  
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS PbHighVoltage"),      
                                    DB::raw("(SELECT SUM(TRY_CAST(DistributionVAT AS decimal(10,2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <td style="padding-left: 40px;">VAT - Distribution & Others</td>
                        <td class="text-right">{{ number_format($distVat->Poblacion, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->Rural, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->ComLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->ComHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->WaterSystems, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->IndLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->IndHighVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->StreetLights, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->PbLowVoltage, 2) }}</td>
                        <td class="text-right">{{ number_format($distVat->PbHighVoltage, 2) }}</td>
                        <th></th>
                        <td class="text-right">{{ number_format($distVat->TotalAmount, 2) }}</td>
                    </tr>
                    {{-- <tr>
                        @php
                            $totalVatResidential = floatval($distVat->Residential) + floatval($sysLossVat->Residential) + floatval($transVat->Residential) + floatval($genVat->Residential);
                            $totalVatLowVoltage = floatval($distVat->LowVoltage) + floatval($sysLossVat->LowVoltage) + floatval($transVat->LowVoltage) + floatval($genVat->LowVoltage);
                            $totalVatHighVoltage = floatval($distVat->HighVoltage) + floatval($sysLossVat->HighVoltage) + floatval($transVat->HighVoltage) + floatval($genVat->HighVoltage);
                            $totalVat = floatval($distVat->TotalAmount) + floatval($sysLossVat->TotalAmount) + floatval($transVat->TotalAmount) + floatval($genVat->TotalAmount);
                        @endphp
                        <th class="text-right">Sub-total</th>
                        <th class="text-right">{{ number_format($totalVatResidential, 2) }}</th>
                        <th class="text-right">{{ number_format($totalVatLowVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalVatHighVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($totalVat, 2) }}</th>
                    </tr>  --}}
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    {{-- <tr>
                        @php
                            $grandTotalResidential = $totalGenTransResidential + $totalDistResidential + $totalOthersResidential + floatval($rpt->Residential) + $totalUnivResidential + floatval($scDisc->Residential) + floatval($scSubsidy->Residential) + floatval($scAdj->Residential) + floatval($othersAdj->Residential) + $totalVatResidential;
                            $grandTotalLowVoltage = $totalGenTransLowVoltage + $totalDistLowVoltage + $totalOthersLowVoltage + floatval($rpt->LowVoltage) + $totalUnivLowVoltage + floatval($scDisc->LowVoltage) + floatval($scSubsidy->LowVoltage) + floatval($scAdj->LowVoltage) + floatval($othersAdj->LowVoltage) + $totalVatLowVoltage;
                            $grandTotalHighVoltage = $totalGenTransHighVoltage + $totalDistHighVoltage + $totalOthersHighVoltage + floatval($rpt->HighVoltage) + $totalUnivHighVoltage + floatval($scDisc->HighVoltage) + floatval($scSubsidy->HighVoltage) + floatval($scAdj->HighVoltage) + floatval($othersAdj->HighVoltage) + $totalVatHighVoltage;
                            $grandTotal = $totalGenTrans + $totalDist + $totalOthers + floatval($rpt->TotalAmount) + $totalUniv + floatval($scDisc->TotalAmount) + floatval($scSubsidy->TotalAmount) + floatval($scAdj->TotalAmount) + floatval($othersAdj->TotalAmount) + $totalVat;
                        @endphp
                        <th class="text-right">GRAND TOTAL</th>
                        <th class="text-right">{{ number_format($grandTotalResidential, 2) }}</th>
                        <th class="text-right">{{ number_format($grandTotalLowVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($grandTotalHighVoltage, 2) }}</th>
                        <th class="text-right">{{ number_format($grandTotal, 2) }}</th>
                    </tr>  --}}
                    <tr>
                        @php
                            $totalQry = DB::table("Billing_Bills")
                                ->select(
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS TotalResidential"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TotalResidentialRural"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS TotalCommercial"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TotalCommercialHv"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TotalIrrigation"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS TotalIndustrial"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TotalIndustrialHv"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS TotalStreetlights"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS TotalPB"),
                                    DB::raw("(SELECT SUM(TRY_CAST(KwhUsed AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TotalPBHv"),
                                    DB::raw("(SELECT SUM(TRY_CAST(NetAmount AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS Total"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL')) AS TotalResidentialDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RURAL RESIDENTIAL')) AS TotalResidentialRuralDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL')) AS TotalCommercialDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE')) AS TotalCommercialHvDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('IRRIGATION/WATER SYSTEMS')) AS TotalIrrigationDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL')) AS TotalIndustrialDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE')) AS TotalIndustrialHvDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('STREET LIGHTS')) AS TotalStreetlightsDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING')) AS TotalPBDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "' AND ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE')) AS TotalPBHvDemand"),
                                    DB::raw("(SELECT SUM(TRY_CAST(DemandPresentKwh AS decimal(15, 2))) FROM Billing_Bills WHERE ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "') AS TotalDemand"),
                                )
                                ->limit(1)
                                ->first();
                        @endphp
                        <th class="text-right">TOTAL KWH USED</th>
                        <th class="text-right">{{ number_format($totalQry->TotalResidential, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalResidentialRural, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalCommercial, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalCommercialHv, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalIrrigation, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalIndustrial, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalIndustrialHv, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalStreetlights, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalPB, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalPBHv, 2) }}</th>
                        <th></th>
                        <th class="text-right">{{ number_format($totalQry->Total, 2) }}</th>
                    </tr> 
                    <tr>
                        <th class="text-right">TOTAL DEMAND KW</th>
                        <th class="text-right">{{ number_format($totalQry->TotalResidentialDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalResidentialRuralDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalCommercialDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalCommercialHvDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalIrrigationDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalIndustrialDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalIndustrialHvDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalStreetlightsDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalPBDemand, 2) }}</th>
                        <th class="text-right">{{ number_format($totalQry->TotalPBHvDemand, 2) }}</th>
                        <th></th>
                        <th class="text-right"></th>
                    </tr> 
                </tbody>
            </table>
        @endif
        
    </div>
</div>

@endsection