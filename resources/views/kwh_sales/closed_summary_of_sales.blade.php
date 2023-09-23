<tbody>
    {{-- RESIDENTIAL --}}
    <tr>
        @php
            $residential = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('RESIDENTIAL', 'RURAL RESIDENTIAL') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $totalVat = floatval($residential->RealPropertyTax) +
                floatval($residential->GenerationVAT) +
                floatval($residential->TransmissionVAT) +
                floatval($residential->SystemLossVAT) +
                floatval($residential->DistributionVAT);
        @endphp
        <th style="text-indent: 20px;">RESIDENTIAL</th>
        <td class="text-right">{{ number_format($residential->NoOfConsumers) }}</td>
        <td class="text-right">{{ number_format($residential->KwhUsed, 2) }}</td>
        <td class="text-right">{{ number_format($residential->DemandKwh, 2) }}</td>
        <th class="text-right">{{ number_format(floatval($residential->NetAmount) - $totalVat, 2) }}</th>
        <td class="text-right">{{ number_format($residential->RealPropertyTax, 2) }}</td>
        <td class="text-right">{{ number_format($residential->GenerationVAT, 2) }}</td>
        <td class="text-right">{{ number_format($residential->TransmissionVAT, 2) }}</td>
        <td class="text-right">{{ number_format($residential->SystemLossVAT, 2) }}</td>
        <td class="text-right">{{ number_format($residential->DistributionVAT, 2) }}</td>
        <td class="text-right text-primary">{{ number_format($totalVat, 2) }}</td>
        <th class="text-right text-success">{{ number_format($residential->NetAmount, 2) }}</th>
    </tr>

    <tr>
        <th colspan="12">LOWER VOLTAGE</th>
    </tr>

    {{-- COMMERCIAL --}}
    <tr>
        @php
            $commercial = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('COMMERCIAL') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $commercialTotalVat = floatval($commercial->RealPropertyTax) +
                floatval($commercial->GenerationVAT) +
                floatval($commercial->TransmissionVAT) +
                floatval($commercial->SystemLossVAT) +
                floatval($commercial->DistributionVAT);
        @endphp
        <th style="text-indent: 20px;">COMMERCIAL</th>
        <td class="text-right">{{ number_format($commercial->NoOfConsumers) }}</td>
        <td class="text-right">{{ number_format($commercial->KwhUsed, 2) }}</td>
        <td class="text-right">{{ number_format($commercial->DemandKwh, 2) }}</td>
        <th class="text-right">{{ number_format(floatval($commercial->NetAmount) - $commercialTotalVat, 2) }}</th>
        <td class="text-right">{{ number_format($commercial->RealPropertyTax, 2) }}</td>
        <td class="text-right">{{ number_format($commercial->GenerationVAT, 2) }}</td>
        <td class="text-right">{{ number_format($commercial->TransmissionVAT, 2) }}</td>
        <td class="text-right">{{ number_format($commercial->SystemLossVAT, 2) }}</td>
        <td class="text-right">{{ number_format($commercial->DistributionVAT, 2) }}</td>
        <td class="text-right text-primary">{{ number_format($commercialTotalVat, 2) }}</td>
        <th class="text-right text-success">{{ number_format($commercial->NetAmount, 2) }}</th>
    </tr>

    {{-- IRRIGATION/WATER SYSTEMS --}}
    <tr>
        @php
            $irrigation = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('IRRIGATION/WATER SYSTEMS') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $irrigationTotalVat = floatval($irrigation->RealPropertyTax) +
                floatval($irrigation->GenerationVAT) +
                floatval($irrigation->TransmissionVAT) +
                floatval($irrigation->SystemLossVAT) +
                floatval($irrigation->DistributionVAT);
        @endphp
        <th style="text-indent: 20px;">IRRIGATION/WATER SYSTEMS</th>
        <td class="text-right">{{ number_format($irrigation->NoOfConsumers) }}</td>
        <td class="text-right">{{ number_format($irrigation->KwhUsed, 2) }}</td>
        <td class="text-right">{{ number_format($irrigation->DemandKwh, 2) }}</td>
        <th class="text-right">{{ number_format(floatval($irrigation->NetAmount) - $irrigationTotalVat, 2) }}</th>
        <td class="text-right">{{ number_format($irrigation->RealPropertyTax, 2) }}</td>
        <td class="text-right">{{ number_format($irrigation->GenerationVAT, 2) }}</td>
        <td class="text-right">{{ number_format($irrigation->TransmissionVAT, 2) }}</td>
        <td class="text-right">{{ number_format($irrigation->SystemLossVAT, 2) }}</td>
        <td class="text-right">{{ number_format($irrigation->DistributionVAT, 2) }}</td>
        <td class="text-right text-primary">{{ number_format($irrigationTotalVat, 2) }}</td>
        <th class="text-right text-success">{{ number_format($irrigation->NetAmount, 2) }}</th>
    </tr>

    {{-- INDUSTRIAL --}}
    <tr>
        @php
            $industrial = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('INDUSTRIAL') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $industrialTotalVat = floatval($industrial->RealPropertyTax) +
                floatval($industrial->GenerationVAT) +
                floatval($industrial->TransmissionVAT) +
                floatval($industrial->SystemLossVAT) +
                floatval($industrial->DistributionVAT);
        @endphp
        <th style="text-indent: 20px;">INDUSTRIAL</th>
        <td class="text-right">{{ number_format($industrial->NoOfConsumers) }}</td>
        <td class="text-right">{{ number_format($industrial->KwhUsed, 2) }}</td>
        <td class="text-right">{{ number_format($industrial->DemandKwh, 2) }}</td>
        <th class="text-right">{{ number_format(floatval($industrial->NetAmount) - $industrialTotalVat, 2) }}</th>
        <td class="text-right">{{ number_format($industrial->RealPropertyTax, 2) }}</td>
        <td class="text-right">{{ number_format($industrial->GenerationVAT, 2) }}</td>
        <td class="text-right">{{ number_format($industrial->TransmissionVAT, 2) }}</td>
        <td class="text-right">{{ number_format($industrial->SystemLossVAT, 2) }}</td>
        <td class="text-right">{{ number_format($industrial->DistributionVAT, 2) }}</td>
        <td class="text-right text-primary">{{ number_format($industrialTotalVat, 2) }}</td>
        <th class="text-right text-success">{{ number_format($industrial->NetAmount, 2) }}</th>
    </tr>

    {{-- STREET LIGHTS --}}
    <tr>
        @php
            $streetlights = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('STREET LIGHTS') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $streetlightsTotalVat = floatval($streetlights->RealPropertyTax) +
                floatval($streetlights->GenerationVAT) +
                floatval($streetlights->TransmissionVAT) +
                floatval($streetlights->SystemLossVAT) +
                floatval($streetlights->DistributionVAT);
        @endphp
        <th style="text-indent: 20px;">STREET LIGHTS</th>
        <td class="text-right">{{ number_format($streetlights->NoOfConsumers) }}</td>
        <td class="text-right">{{ number_format($streetlights->KwhUsed, 2) }}</td>
        <td class="text-right">{{ number_format($streetlights->DemandKwh, 2) }}</td>
        <th class="text-right">{{ number_format(floatval($streetlights->NetAmount) - $streetlightsTotalVat, 2) }}</th>
        <td class="text-right">{{ number_format($streetlights->RealPropertyTax, 2) }}</td>
        <td class="text-right">{{ number_format($streetlights->GenerationVAT, 2) }}</td>
        <td class="text-right">{{ number_format($streetlights->TransmissionVAT, 2) }}</td>
        <td class="text-right">{{ number_format($streetlights->SystemLossVAT, 2) }}</td>
        <td class="text-right">{{ number_format($streetlights->DistributionVAT, 2) }}</td>
        <td class="text-right text-primary">{{ number_format($streetlightsTotalVat, 2) }}</td>
        <th class="text-right text-success">{{ number_format($streetlights->NetAmount, 2) }}</th>
    </tr>

    {{-- PUBLIC BUILDING --}}
    <tr>
        @php
            $publicbuilding = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('PUBLIC BUILDING') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $publicbuildingTotalVat = floatval($publicbuilding->RealPropertyTax) +
                floatval($publicbuilding->GenerationVAT) +
                floatval($publicbuilding->TransmissionVAT) +
                floatval($publicbuilding->SystemLossVAT) +
                floatval($publicbuilding->DistributionVAT);
        @endphp
        <th style="text-indent: 20px;">PUBLIC BUILDING</th>
        <td class="text-right">{{ number_format($publicbuilding->NoOfConsumers) }}</td>
        <td class="text-right">{{ number_format($publicbuilding->KwhUsed, 2) }}</td>
        <td class="text-right">{{ number_format($publicbuilding->DemandKwh, 2) }}</td>
        <th class="text-right">{{ number_format(floatval($publicbuilding->NetAmount) - $publicbuildingTotalVat, 2) }}</th>
        <td class="text-right">{{ number_format($publicbuilding->RealPropertyTax, 2) }}</td>
        <td class="text-right">{{ number_format($publicbuilding->GenerationVAT, 2) }}</td>
        <td class="text-right">{{ number_format($publicbuilding->TransmissionVAT, 2) }}</td>
        <td class="text-right">{{ number_format($publicbuilding->SystemLossVAT, 2) }}</td>
        <td class="text-right">{{ number_format($publicbuilding->DistributionVAT, 2) }}</td>
        <td class="text-right text-primary">{{ number_format($publicbuildingTotalVat, 2) }}</td>
        <th class="text-right text-success">{{ number_format($publicbuilding->NetAmount, 2) }}</th>
    </tr>

    {{-- TOTAL LOW VOLTAGE --}}
    <tr>
        @php
            $totallv = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('PUBLIC BUILDING', 'STREET LIGHTS', 'INDUSTRIAL', 'IRRIGATION/WATER SYSTEMS', 'COMMERCIAL') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $totallvTotalVat = floatval($totallv->RealPropertyTax) +
                floatval($totallv->GenerationVAT) +
                floatval($totallv->TransmissionVAT) +
                floatval($totallv->SystemLossVAT) +
                floatval($totallv->DistributionVAT);
        @endphp
        <th style="text-indent: 20px;">TOTAL</th>
        <th class="text-right">{{ number_format($totallv->NoOfConsumers) }}</th>
        <th class="text-right">{{ number_format($totallv->KwhUsed, 2) }}</th>
        <th class="text-right">{{ number_format($totallv->DemandKwh, 2) }}</th>
        <th class="text-right">{{ number_format(floatval($totallv->NetAmount) - $totallvTotalVat, 2) }}</th>
        <th class="text-right">{{ number_format($totallv->RealPropertyTax, 2) }}</th>
        <th class="text-right">{{ number_format($totallv->GenerationVAT, 2) }}</th>
        <th class="text-right">{{ number_format($totallv->TransmissionVAT, 2) }}</th>
        <th class="text-right">{{ number_format($totallv->SystemLossVAT, 2) }}</th>
        <th class="text-right">{{ number_format($totallv->DistributionVAT, 2) }}</th>
        <th class="text-right text-primary">{{ number_format($totallvTotalVat, 2) }}</th>
        <th class="text-right text-success">{{ number_format($totallv->NetAmount, 2) }}</th>
    </tr>

    <tr>
        <th colspan="12">HIGHER VOLTAGE</th>
    </tr>
    {{-- COMMERCIAL HIGH VOLTAGE --}}
    <tr>
        @php
            $commercialhv = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('COMMERCIAL HIGH VOLTAGE') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $commercialhvTotalVat = floatval($commercialhv->RealPropertyTax) +
                floatval($commercialhv->GenerationVAT) +
                floatval($commercialhv->TransmissionVAT) +
                floatval($commercialhv->SystemLossVAT) +
                floatval($commercialhv->DistributionVAT);
        @endphp
        <th style="text-indent: 20px;">COMMERCIAL</th>
        <td class="text-right">{{ number_format($commercialhv->NoOfConsumers) }}</td>
        <td class="text-right">{{ number_format($commercialhv->KwhUsed, 2) }}</td>
        <td class="text-right">{{ number_format($commercialhv->DemandKwh, 2) }}</td>
        <th class="text-right">{{ number_format(floatval($commercialhv->NetAmount) - $commercialhvTotalVat, 2) }}</th>
        <td class="text-right">{{ number_format($commercialhv->RealPropertyTax, 2) }}</td>
        <td class="text-right">{{ number_format($commercialhv->GenerationVAT, 2) }}</td>
        <td class="text-right">{{ number_format($commercialhv->TransmissionVAT, 2) }}</td>
        <td class="text-right">{{ number_format($commercialhv->SystemLossVAT, 2) }}</td>
        <td class="text-right">{{ number_format($commercialhv->DistributionVAT, 2) }}</td>
        <td class="text-right text-primary">{{ number_format($commercialhvTotalVat, 2) }}</td>
        <th class="text-right text-success">{{ number_format($commercialhv->NetAmount, 2) }}</th>
    </tr>

    {{-- INDUSTRIAL HIGH VOLTAGE --}}
    <tr>
        @php
            $industrialhv = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('INDUSTRIAL HIGH VOLTAGE') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $industrialhvTotalVat = floatval($industrialhv->RealPropertyTax) +
                floatval($industrialhv->GenerationVAT) +
                floatval($industrialhv->TransmissionVAT) +
                floatval($industrialhv->SystemLossVAT) +
                floatval($industrialhv->DistributionVAT);
        @endphp
        <th style="text-indent: 20px;">INDUSTRIAL</th>
        <td class="text-right">{{ number_format($industrialhv->NoOfConsumers) }}</td>
        <td class="text-right">{{ number_format($industrialhv->KwhUsed, 2) }}</td>
        <td class="text-right">{{ number_format($industrialhv->DemandKwh, 2) }}</td>
        <th class="text-right">{{ number_format(floatval($industrialhv->NetAmount) - $industrialhvTotalVat, 2) }}</th>
        <td class="text-right">{{ number_format($industrialhv->RealPropertyTax, 2) }}</td>
        <td class="text-right">{{ number_format($industrialhv->GenerationVAT, 2) }}</td>
        <td class="text-right">{{ number_format($industrialhv->TransmissionVAT, 2) }}</td>
        <td class="text-right">{{ number_format($industrialhv->SystemLossVAT, 2) }}</td>
        <td class="text-right">{{ number_format($industrialhv->DistributionVAT, 2) }}</td>
        <td class="text-right text-primary">{{ number_format($industrialhvTotalVat, 2) }}</td>
        <th class="text-right text-success">{{ number_format($industrialhv->NetAmount, 2) }}</th>
    </tr>

    {{-- PUBLIC BUILDING HIGH VOLTAGE --}}
    <tr>
        @php
            $publicbldghv = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $publicbldghvTotalVat = floatval($publicbldghv->RealPropertyTax) +
                floatval($publicbldghv->GenerationVAT) +
                floatval($publicbldghv->TransmissionVAT) +
                floatval($publicbldghv->SystemLossVAT) +
                floatval($publicbldghv->DistributionVAT);
        @endphp
        <th style="text-indent: 20px;">PUBLIC BUILDING</th>
        <td class="text-right">{{ number_format($publicbldghv->NoOfConsumers) }}</td>
        <td class="text-right">{{ number_format($publicbldghv->KwhUsed, 2) }}</td>
        <td class="text-right">{{ number_format($publicbldghv->DemandKwh, 2) }}</td>
        <th class="text-right">{{ number_format(floatval($publicbldghv->NetAmount) - $publicbldghvTotalVat, 2) }}</th>
        <td class="text-right">{{ number_format($publicbldghv->RealPropertyTax, 2) }}</td>
        <td class="text-right">{{ number_format($publicbldghv->GenerationVAT, 2) }}</td>
        <td class="text-right">{{ number_format($publicbldghv->TransmissionVAT, 2) }}</td>
        <td class="text-right">{{ number_format($publicbldghv->SystemLossVAT, 2) }}</td>
        <td class="text-right">{{ number_format($publicbldghv->DistributionVAT, 2) }}</td>
        <td class="text-right text-primary">{{ number_format($publicbldghvTotalVat, 2) }}</td>
        <th class="text-right text-success">{{ number_format($publicbldghv->NetAmount, 2) }}</th>
    </tr>

    {{-- TOTAL HIGH VOLTAGE --}}
    <tr>
        @php
            $totalhv = DB::table('Billing_Bills')
                ->whereRaw("ConsumerType IN ('PUBLIC BUILDING HIGH VOLTAGE', 'INDUSTRIAL HIGH VOLTAGE', 'COMMERCIAL HIGH VOLTAGE') AND ForCancellation='SALES_REPORT' AND ServicePeriod='" . $period . "'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $totalhvTotalVat = floatval($totalhv->RealPropertyTax) +
                floatval($totalhv->GenerationVAT) +
                floatval($totalhv->TransmissionVAT) +
                floatval($totalhv->SystemLossVAT) +
                floatval($totalhv->DistributionVAT);
        @endphp
        <th style="text-indent: 20px;">TOTAL</th>
        <th class="text-right">{{ number_format($totalhv->NoOfConsumers) }}</th>
        <th class="text-right">{{ number_format($totalhv->KwhUsed, 2) }}</th>
        <th class="text-right">{{ number_format($totalhv->DemandKwh, 2) }}</th>
        <th class="text-right">{{ number_format(floatval($totalhv->NetAmount) - $totalhvTotalVat, 2) }}</th>
        <th class="text-right">{{ number_format($totalhv->RealPropertyTax, 2) }}</th>
        <th class="text-right">{{ number_format($totalhv->GenerationVAT, 2) }}</th>
        <th class="text-right">{{ number_format($totalhv->TransmissionVAT, 2) }}</th>
        <th class="text-right">{{ number_format($totalhv->SystemLossVAT, 2) }}</th>
        <th class="text-right">{{ number_format($totalhv->DistributionVAT, 2) }}</th>
        <th class="text-right text-primary">{{ number_format($totalhvTotalVat, 2) }}</th>
        <th class="text-right text-success">{{ number_format($totalhv->NetAmount, 2) }}</th>
    </tr>

    {{-- GRAND TOTAL --}}
    <tr>
        @php
            $grandTotal = DB::table('Billing_Bills')
                ->whereRaw("ServicePeriod='" . $period . "' AND ForCancellation='SALES_REPORT'")
                ->select(
                    DB::raw("SUM(TRY_CAST(BillNumber AS decimal(10,2))) AS NoOfConsumers"),
                    DB::raw("SUM(TRY_CAST(KwhUsed AS DECIMAL(12,2))) AS KwhUsed"),
                    DB::raw("SUM(TRY_CAST(DemandPresentKwh AS DECIMAL(12,2))) AS DemandKwh"),
                    DB::raw("SUM(TRY_CAST(RealPropertyTax AS DECIMAL(12,2))) AS RealPropertyTax"),
                    DB::raw("SUM(TRY_CAST(GenerationVAT AS DECIMAL(12,2))) AS GenerationVAT"),
                    DB::raw("SUM(TRY_CAST(TransmissionVAT AS DECIMAL(12,2))) AS TransmissionVAT"),
                    DB::raw("SUM(TRY_CAST(SystemLossVAT AS DECIMAL(12,2))) AS SystemLossVAT"),
                    DB::raw("SUM(TRY_CAST(DistributionVAT AS DECIMAL(12,2))) AS DistributionVAT"),
                    DB::raw("SUM(TRY_CAST(NetAmount AS DECIMAL(12,2))) AS NetAmount"),
                )
                ->first();

            $grandTotalTotalVat = floatval($grandTotal->RealPropertyTax) +
                floatval($grandTotal->GenerationVAT) +
                floatval($grandTotal->TransmissionVAT) +
                floatval($grandTotal->SystemLossVAT) +
                floatval($grandTotal->DistributionVAT);
        @endphp
        <th>GRAND TOTAL</th>
        <th class="text-right">{{ number_format($grandTotal->NoOfConsumers) }}</th>
        <th class="text-right">{{ number_format($grandTotal->KwhUsed, 2) }}</th>
        <th class="text-right">{{ number_format($grandTotal->DemandKwh, 2) }}</th>
        <th class="text-right">{{ number_format(floatval($grandTotal->NetAmount) - $grandTotalTotalVat, 2) }}</th>
        <th class="text-right">{{ number_format($grandTotal->RealPropertyTax, 2) }}</th>
        <th class="text-right">{{ number_format($grandTotal->GenerationVAT, 2) }}</th>
        <th class="text-right">{{ number_format($grandTotal->TransmissionVAT, 2) }}</th>
        <th class="text-right">{{ number_format($grandTotal->SystemLossVAT, 2) }}</th>
        <th class="text-right">{{ number_format($grandTotal->DistributionVAT, 2) }}</th>
        <th class="text-right text-primary">{{ number_format($grandTotalTotalVat, 2) }}</th>
        <th class="text-right text-success">{{ number_format($grandTotal->NetAmount, 2) }}</th>
    </tr>
</tbody>