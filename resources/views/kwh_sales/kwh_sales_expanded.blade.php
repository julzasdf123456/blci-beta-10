@php
    use App\Models\ServiceAccounts;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>Kwh Sales Expanded - TSD</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body">
                    {!! Form::open(['route' => 'kwhSales.kwh-sales-expanded', 'method' => 'GET']) !!}
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="">Town</label>
                            <select id="Town" name="Town" class="form-control form-control-sm">
                                <option value="All">All</option>
                                @foreach ($towns as $item)
                                    <option value="{{ $item->id }}" {{ !isset($_GET['Town']) ? ($item->id==env('APP_AREA_CODE') ? 'selected' : '') : ($_GET['Town']==$item->id ? 'selected' : '') }}>{{ $item->Town }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Billing Month</label>
                            <select id="ServicePeriod" name="ServicePeriod" class="form-control form-control-sm">
                                @foreach ($billingMonths as $item)
                                    <option value="{{ $item->ServicePeriod }}" {{ isset($_GET['ServicePeriod']) && $_GET['ServicePeriod']==$item->ServicePeriod ? 'selected' : '' }}>{{ date('F Y', strtotime($item->ServicePeriod)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Action</label><br>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-eye ico-tab-mini"></i>View</button>
                            @if (isset($_GET['Town']) && isset($_GET['ServicePeriod']))
                                <a href="{{ route('kwhSales.download-kwh-sales-expanded', [$_GET['ServicePeriod'], $_GET['Town']]) }}" class="btn btn-sm btn-success"><i class="fas fa-download ico-tab-mini"></i>Download</a>
                            @endif
                            
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        {{-- RESULTS --}}
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-borderd table-hover">
                        <thead>
                            <th>Route</th>
                            <th>Town</th>
                            <th class="text-right">No. Of <br> Consumers</th>
                            <th class="text-right">Residential</th>
                            <th class="text-right">Low Voltage</th>
                            <th class="text-center">High Voltage</th>
                            <th class="text-center">Total Kwh Sold</th>
                            <th class="text-right">Total Amnt</th>
                            <th class="text-right">Missionary</th>
                            <th class="text-center">Environmental<br>NPC Debt</th>
                            <th class="text-center">Stranded CC<br>REDCI</th>
                            <th class="text-center">NCC?<br>FIT ALL</th>
                            <th class="text-center">RPT<br>NPC Contract</th>
                            <th class="text-center">VAT</th>
                            <th class="text-center">SC Subsidy<br>Discount</th>
                        </thead>
                        <tbody>
                            @php
                                // COMPUTE TOTALS
                                $totalConsumers = 0;
                                $totalResidentials = 0;
                                $totalLowVoltage = 0;
                                $totalHighVoltage = 0;
                                $totalKwhSold = 0;
                                $totalAmnt = 0;
                                $totalMisionary = 0;
                                $totalEnv = 0;
                                $totalNpc = 0;
                                $totalStranded = 0;
                                $totalRedci = 0;
                                $totalFitAll = 0;
                                $totalRpt = 0;
                                $totalVat = 0;
                                $totalScSub = 0;
                                $totalScDisc = 0;
                            @endphp
                            {{-- FOR NOT NULL ROUTES --}}
                            @foreach ($data as $item)
                                <tr>
                                    @if ($item->AreaCode != null)
                                    <th><a href="{{ route('kwhSales.kwh-sales-expanded-view', [$item->AreaCode, $item->Town, $item->ServicePeriod]) }}">{{ $item->AreaCode }}</a></th>
                                    @else
                                    {{-- <th><a href="{{ route('kwhSales.kwh-sales-expanded-view', ['-', $item->Town, $item->ServicePeriod]) }}">{{ $item->AreaCode }}</a></th> --}}
                                    <th>{{ $item->AreaCode }}</th>
                                    @endif
                                    
                                    <th>{{ $item->Town }}</th>
                                    <td class="text-right">{{ number_format($item->ConsumerCount) }}</td>
                                    <td class="text-right">{{ number_format($item->Residentials, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->LowVoltKwh, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->DemandKwh, 2) }}</td>
                                    <th class="text-right text-primary">{{ number_format($item->KwhSold, 2) }}</th>
                                    <td class="text-right text-danger">{{ number_format($item->TotalAmount, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->Missionary, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->Environmental, 2) }}<br>{{ number_format($item->NPC, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->StrandedCC, 2) }}<br>{{ number_format($item->Redci, 2) }}</td>
                                    <td class="text-right">-<br>{{ number_format($item->FITAll, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->RPT, 2) }}<br>-</td>
                                    <td class="text-right">{{ number_format(floatval($item->GenVat) + floatval($item->TransVat) + floatval($item->SysLossVat) + floatval($item->DistVat), 2) }}</td>
                                    <td class="text-right">{{ number_format($item->SCSubsidy, 2) }}<br>{{ number_format($item->SCDsc, 2) }}</td>
                                </tr>
                                @php
                                    // COMPUTE TOTALS
                                    $totalConsumers += floatval($item->ConsumerCount);
                                    $totalResidentials += floatval($item->Residentials);
                                    $totalLowVoltage += floatval($item->LowVoltKwh);
                                    $totalHighVoltage += floatval($item->DemandKwh);
                                    $totalKwhSold += floatval($item->KwhSold);
                                    $totalAmnt += floatval($item->TotalAmount);
                                    $totalMisionary += floatval($item->Missionary);
                                    $totalEnv += floatval($item->Environmental);
                                    $totalNpc += floatval($item->NPC);
                                    $totalStranded += floatval($item->StrandedCC);
                                    $totalRedci += floatval($item->Redci);
                                    $totalFitAll += floatval($item->FITAll);
                                    $totalRpt += floatval($item->RPT);
                                    $totalVat += (floatval($item->GenVat) + floatval($item->TransVat) + floatval($item->SysLossVat) + floatval($item->DistVat));
                                    $totalScSub += floatval($item->SCSubsidy);
                                    $totalScDisc += floatval($item->SCDsc);
                                @endphp
                            @endforeach

                            {{-- FOR NULL ROUTES --}}
                            @foreach ($nullRouteData as $item)
                                <tr>
                                    <th>{{ $item->AreaCode }}</th>                                    
                                    <th>{{ $item->Town }}</th>
                                    <td class="text-right">{{ number_format($item->ConsumerCount) }}</td>
                                    <td class="text-right">{{ number_format($item->Residentials, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->LowVoltKwh, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->DemandKwh, 2) }}</td>
                                    <th class="text-right text-primary">{{ number_format($item->KwhSold, 2) }}</th>
                                    <td class="text-right text-danger">{{ number_format($item->TotalAmount, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->Missionary, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->Environmental, 2) }}<br>{{ number_format($item->NPC, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->StrandedCC, 2) }}<br>{{ number_format($item->Redci, 2) }}</td>
                                    <td class="text-right">-<br>{{ number_format($item->FITAll, 2) }}</td>
                                    <td class="text-right">{{ number_format($item->RPT, 2) }}<br>-</td>
                                    <td class="text-right">{{ number_format(floatval($item->GenVat) + floatval($item->TransVat) + floatval($item->SysLossVat) + floatval($item->DistVat), 2) }}</td>
                                    <td class="text-right">{{ number_format($item->SCSubsidy, 2) }}<br>{{ number_format($item->SCDsc, 2) }}</td>
                                </tr>
                                @php
                                    // COMPUTE TOTALS
                                    $totalConsumers += floatval($item->ConsumerCount);
                                    $totalResidentials += floatval($item->Residentials);
                                    $totalLowVoltage += floatval($item->LowVoltKwh);
                                    $totalHighVoltage += floatval($item->DemandKwh);
                                    $totalKwhSold += floatval($item->KwhSold);
                                    $totalAmnt += floatval($item->TotalAmount);
                                    $totalMisionary += floatval($item->Missionary);
                                    $totalEnv += floatval($item->Environmental);
                                    $totalNpc += floatval($item->NPC);
                                    $totalStranded += floatval($item->StrandedCC);
                                    $totalRedci += floatval($item->Redci);
                                    $totalFitAll += floatval($item->FITAll);
                                    $totalRpt += floatval($item->RPT);
                                    $totalVat += (floatval($item->GenVat) + floatval($item->TransVat) + floatval($item->SysLossVat) + floatval($item->DistVat));
                                    $totalScSub += floatval($item->SCSubsidy);
                                    $totalScDisc += floatval($item->SCDsc);
                                @endphp
                            @endforeach
                            <tr>
                                <th>GRAND TOTAL</th>
                                <th></th>
                                <th class="text-right">{{ number_format($totalConsumers) }}</th>
                                <th class="text-right">{{ number_format($totalResidentials, 2) }}</th>
                                <th class="text-right">{{ number_format($totalLowVoltage, 2) }}</th>
                                <th class="text-right">{{ number_format($totalHighVoltage, 2) }}</th>
                                <th class="text-right text-primary">{{ number_format($totalKwhSold, 2) }}</th>
                                <th class="text-right text-danger">{{ number_format($totalAmnt, 2) }}</th>
                                <th class="text-right">{{ number_format($totalMisionary, 2) }}</th>
                                <th class="text-right">{{ number_format($totalEnv, 2) }}<br>{{ number_format($totalNpc, 2) }}</th>
                                <th class="text-right">{{ number_format($totalStranded, 2) }}<br>{{ number_format($totalRedci, 2) }}</th>
                                <th class="text-right">-<br>{{ number_format($totalFitAll, 2) }}</th>
                                <th class="text-right">{{ number_format($totalRpt, 2) }}<br>-</th>
                                <th class="text-right">{{ number_format($totalVat, 2) }}</th>
                                <th class="text-right">{{ number_format($totalScSub, 2) }}<br>{{ number_format($totalScDisc, 2) }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection