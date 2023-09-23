@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Distribution System Loss Report - {{ $sales != null ? date('F Y', strtotime($sales->ServicePeriod)) : '-' }}</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    @if ($sales != null && $sales->Status == 'CLOSED') 
                        <span class="badge bg-warning ico-tab">CLOSED</span>
                        @if ($sales->CalatravaSubstation == 'FINALIZED')
                            <span class="badge bg-success ico-tab">FINALIZED</span>
                        @else
                            <a href="{{ route('distributionSystemLosses.edit', $sales->id) }}" class="ico-tab-mini" title="Update"><i class="fas fa-pen"></i></a>
                            <button id="finalize" class="btn btn-link text-success ico-tab-mini" title="Finalize"><i class="fas fa-check-circle"></i></button>
                        @endif                        
                    @else
                        <a href="{{ route('distributionSystemLosses.edit', $sales->id) }}" class="ico-tab-mini" title="Update"><i class="fas fa-pen"></i></a>
                    @endif
                    
                    <a href="{{ route('kwhSales.print-report', $sales->id) }}" title="Update"><i class="fas fa-print"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <table class="table table-hover table-borderless table-sm">
                        <thead>
                            @php
                                $substationTotal = floatval($sales->CalatravaSubstation) + 
                                                    floatval($sales->VictoriasSubstation) + 
                                                    floatval($sales->SagaySubstation) + 
                                                    floatval($sales->SanCarlosSubstation) + 
                                                    floatval($sales->EscalanteSubstation) + 
                                                    floatval($sales->LopezSubstation) + 
                                                    floatval($sales->CadizSubstation) + 
                                                    floatval($sales->IpiSubstation) + 
                                                    floatval($sales->TobosoCalatravaSubstation);

                                $generatorTotal = floatval($sales->VictoriasMillingCompany) + 
                                                    floatval($sales->SanCarlosBionergy);
                            @endphp
                        </thead>
                        <tbody>
                            <tr>
                                <th>ENERGY INPUT SUB-TRANSMISSION (in kWh)</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th style="padding-left: 40px;">ENERGY INPUT DELIVERED BY TRANSMISSION SYSTEM</th>
                                <th class="text-right">{{ number_format($substationTotal, 2) }}</th>
                            </tr>
                            <tr>            
                                <td style="padding-left: 80px;">Victorias Substation</td>
                                <td class="text-right">{{ $sales != null ? number_format($sales->VictoriasSubstation, 2) : 0 }}</td>
                            </tr>
                            <tr>            
                                <td style="padding-left: 80px;">Sagay Substation</td>
                                <td class="text-right">{{ $sales != null ? number_format($sales->SagaySubstation, 2) : 0 }}</td>
                            </tr>
                            <tr>            
                                <td style="padding-left: 80px;">San Sarlos Substation</td>
                                <td class="text-right">{{ $sales != null ? number_format($sales->SanCarlosSubstation, 2) : 0 }}</td>
                            </tr>
                            <tr>            
                                <td style="padding-left: 80px;">Escalante Substation</td>
                                <td class="text-right">{{ $sales != null ? number_format($sales->EscalanteSubstation, 2) : 0 }}</td>
                            </tr>
                            <tr>            
                                <td style="padding-left: 80px;">Lopez Substation</td>
                                <td class="text-right">{{ $sales != null ? number_format($sales->LopezSubstation, 2) : 0 }}</td>
                            </tr>
                            <tr>            
                                <td style="padding-left: 80px;">Cadiz Substation</td>
                                <td class="text-right">{{ $sales != null ? number_format($sales->CadizSubstation, 2) : 0 }}</td>
                            </tr>
                            <tr>            
                                <td style="padding-left: 80px;">IPI Substation</td>
                                <td class="text-right">{{ $sales != null ? number_format($sales->IpiSubstation, 2) : 0 }}</td>
                            </tr>
                            <tr>            
                                <td style="padding-left: 80px;">Toboso-Calatrava Substation</td>
                                <td class="text-right">{{ $sales != null ? number_format($sales->TobosoCalatravaSubstation, 2) : 0 }}</td>
                            </tr>
                            <tr>
                                <th style="padding-left: 40px;">ENERGY INPUT DELIVERED BY EMBEDDED GENERATOR</th>
                                <th class="text-right">{{ number_format($generatorTotal, 2) }}</th>
                            </tr>
                            <tr>            
                                <td style="padding-left: 80px;">Victorias Milling Company</td>
                                <td class="text-right">{{ $sales != null ? number_format($sales->VictoriasMillingCompany, 2) : 0 }}</td>
                            </tr>        
                            <tr>            
                                <td style="padding-left: 80px;">San Carlos Bionergy</td>
                                <td class="text-right">{{ $sales != null ? number_format($sales->SanCarlosBionergy, 2) : 0 }}</td>
                            </tr>
                            <tr>
                                <th>TOTAL ENERGY INPUT SUB-TRANSMISSION (in kWh)</th>
                                <th class="text-right">{{ $sales != null ? number_format($sales->TotalEnergyInput, 2) : 0 }}</th>
                            </tr>
                            <tr>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                            </tr>
                            <tr>
                                <th>ENERGY OUTPUT DISTRIBUTION FEEDER</th>
                                <td class="text-right"></td>
                            </tr>
                            <tr>
                                <td style="padding-left: 80px;">Energy Sales</td>
                                <td class="text-right">{{ $sales != null ? number_format($sales->EnergySales, 2) : 0 }}</td>
                            </tr>
                            <tr>
                                <td style="padding-left: 80px;">Energy Recoveries (Adjustments)</td>
                                <td class="text-right">{{ $sales != null ? number_format($sales->EnergyAdjustmentRecoveries, 2) : 0 }}</td>
                            </tr>
                            <tr>
                                <th>TOTAL ENERGY OUTPUT DISTRIBUTION FEEDER</th>
                                <th class="text-right">{{ $sales != null ? number_format($sales->TotalEnergyOutput, 2) : 0 }}</th>
                            </tr>
                            <tr>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                            </tr>
                            <tr>
                                <th>TOTAL SYSTEM LOSS IN kWh</th>
                                <th class="text-right">{{ $sales != null ? number_format($sales->TotalSystemLoss, 2) : 0 }}</th>
                            </tr><tr>
                                <th>TOTAL SYSTEM LOSS IN %</th>
                                <th class="text-right">{{ $sales != null ? number_format($sales->TotalSystemLossPercentage, 2) : 0 }}%</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#finalize').on('click', function() {
                Swal.fire({
                    title: 'Input Password',
                    text : 'Input Password for Anthony Lagrada',
                    input: 'password',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    showLoaderOnConfirm: true,
                preConfirm: (login) => {
                    return fetch('{{ route("kwhSales.validate-confirm-user") }}' + "?Password=" + `${login}` + "&id={{ urlencode($sales->id) }}")
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        )
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload()
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Sales Finalized Successfully!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                })
            })
        })
    </script>
@endpush
