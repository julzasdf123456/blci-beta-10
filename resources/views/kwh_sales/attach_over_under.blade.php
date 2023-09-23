@php    
    use Illuminate\Support\Facades\DB;
@endphp
<table class="table table-sm table-borderless table-hover">
    <thead>
        <th>Rate</th>
        <th class="text-right">Residential</th>
        <th class="text-right">Low Voltage</th>
        <th class="text-right">High Voltage</th>
        <th class="text-right">Total Amount</th>
    </thead>
    <tbody>       
        <tr>
            @php
                $ogaKwh = DB::table('Billing_Rates')
                    ->select(
                        DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL')) AS Residential"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'STREET LIGHTS', 'IRRIGATION/WATER SYSTEMS')) AS LowVoltage"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS HighVoltage"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherGenerationRateAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                    )
                    ->limit(1)
                    ->first();
            @endphp
            <th style="padding-left: 40px;">Other Generation Rate Adjustment (OGA) (KWH)</th>
            <td class="text-right">{{ number_format($ogaKwh->Residential, 2) }}</td>
            <td class="text-right">{{ number_format($ogaKwh->LowVoltage, 2) }}</td>
            <td class="text-right">{{ number_format($ogaKwh->HighVoltage, 2) }}</td>
            <td class="text-right">{{ number_format($ogaKwh->TotalAmount, 2) }}</td>
        </tr>
        <tr>
            @php
                $otcaKw = DB::table('Billing_Rates')
                    ->select(
                        DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL')) AS Residential"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltage"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS HighVoltage"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKW AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                    )
                    ->limit(1)
                    ->first();
            @endphp
            <th style="padding-left: 40px;">Other Transmission Cost Adjustment (OTCA) (KW)</th>
            <td class="text-right">{{ number_format($otcaKw->Residential, 2) }}</td>
            <td class="text-right">{{ number_format($otcaKw->LowVoltage, 2) }}</td>
            <td class="text-right">{{ number_format($otcaKw->HighVoltage, 2) }}</td>
            <td class="text-right">{{ number_format($otcaKw->TotalAmount, 2) }}</td>
        </tr>
        <tr>
            @php
                $otcaKwh = DB::table('Billing_Rates')
                    ->select(
                        DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL')) AS Residential"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltage"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS HighVoltage"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherTransmissionCostAdjustmentKWH AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                    )
                    ->limit(1)
                    ->first();
            @endphp
            <th style="padding-left: 40px;">Other Transmission Cost Adjustment (OTCA) (KWH)</th>
            <td class="text-right">{{ number_format($otcaKwh->Residential, 2) }}</td>
            <td class="text-right">{{ number_format($otcaKwh->LowVoltage, 2) }}</td>
            <td class="text-right">{{ number_format($otcaKwh->HighVoltage, 2) }}</td>
            <td class="text-right">{{ number_format($otcaKwh->TotalAmount, 2) }}</td>
        </tr>
        <tr>
            @php
                $osla = DB::table('Billing_Rates')
                    ->select(
                        DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL')) AS Residential"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltage"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS HighVoltage"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherSystemLossCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                    )
                    ->limit(1)
                    ->first();
            @endphp
            <th style="padding-left: 40px;">Other System Loss Cost Adjustment (OSLA) (KWH)</th>
            <td class="text-right">{{ number_format($osla->Residential, 2) }}</td>
            <td class="text-right">{{ number_format($osla->LowVoltage, 2) }}</td>
            <td class="text-right">{{ number_format($osla->HighVoltage, 2) }}</td>
            <td class="text-right">{{ number_format($osla->TotalAmount, 2) }}</td>
        </tr>
        <tr>
            @php
                $scAdj = DB::table('Billing_Rates')
                    ->select(
                        DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL')) AS Residential"),
                        DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltage"),
                        DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS HighVoltage"),
                        DB::raw("(SELECT SUM(TRY_CAST(SeniorCitizenDiscountAndSubsidyAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                    )
                    ->limit(1)
                    ->first();
            @endphp
            <th style="padding-left: 40px;">Senior Citizen Discount & Subsidy Adjustment (KWH)</th>
            <td class="text-right">{{ number_format($scAdj->Residential, 2) }}</td>
            <td class="text-right">{{ number_format($scAdj->LowVoltage, 2) }}</td>
            <td class="text-right">{{ number_format($scAdj->HighVoltage, 2) }}</td>
            <td class="text-right">{{ number_format($scAdj->TotalAmount, 2) }}</td>
        </tr>
        <tr>
            @php
                $othersAdj = DB::table('Billing_Rates')
                    ->select(
                        DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL')) AS Residential"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL', 'INDUSTRIAL', 'PUBLIC BUILDING', 'IRRIGATION/WATER SYSTEMS', 'STREET LIGHTS')) AS LowVoltage"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "' AND ConsumerType IN ('COMMERCIAL HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'PUBLIC BUILDING HIGH VOLTAGE')) AS HighVoltage"),
                        DB::raw("(SELECT SUM(TRY_CAST(OtherLifelineRateCostAdjustment AS decimal(10,2))) FROM Billing_Bills WHERE (AdjustmentType IS NULL OR AdjustmentType NOT IN ('DM/CM')) AND ServicePeriod='" . $period . "') AS TotalAmount")                  
                    )
                    ->limit(1)
                    ->first();
            @endphp
            <th style="padding-left: 40px;">Other Lifeline Rate Cost Adjustment (OLRA) (KWH)</th>
            <td class="text-right">{{ number_format($othersAdj->Residential, 2) }}</td>
            <td class="text-right">{{ number_format($othersAdj->LowVoltage, 2) }}</td>
            <td class="text-right">{{ number_format($othersAdj->HighVoltage, 2) }}</td>
            <td class="text-right">{{ number_format($othersAdj->TotalAmount, 2) }}</td>
        </tr>
        <tr>
            @php
                $residentialTtl = floatval($ogaKwh->Residential) + floatval($otcaKw->Residential) + floatval($otcaKwh->Residential) + floatval($osla->Residential) + floatval($scAdj->Residential) + floatval($othersAdj->Residential);
                $lowVoltTtl = floatval($ogaKwh->LowVoltage) + floatval($otcaKw->LowVoltage) + floatval($otcaKwh->LowVoltage) + floatval($osla->LowVoltage) + floatval($scAdj->LowVoltage) + floatval($othersAdj->LowVoltage);
                $highVoltTtl = floatval($ogaKwh->HighVoltage) + floatval($otcaKw->HighVoltage) + floatval($otcaKwh->HighVoltage) + floatval($osla->HighVoltage) + floatval($scAdj->HighVoltage) + floatval($othersAdj->HighVoltage);
                $grandTtl = floatval($residentialTtl) + floatval($lowVoltTtl) + floatval($highVoltTtl);
            @endphp
            <th class="text-right">TOTAL</th>
            <th class="text-right">{{ number_format($residentialTtl, 2) }}</th>
            <th class="text-right">{{ number_format($lowVoltTtl, 2) }}</th>
            <th class="text-right">{{ number_format($highVoltTtl, 2) }}</th>
            <th class="text-right">{{ number_format($grandTtl, 2) }}</th>
        </tr>
    </tbody>
</table>