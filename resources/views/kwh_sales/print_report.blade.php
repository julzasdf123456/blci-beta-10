@php
    
@endphp

<style>
    @media print {
        @page {
            orientation: portrait !important;
        }

        header {
            display: none;
        }

        .divider {
            width: 100%;
            margin: 10px auto;
            height: 1px;
            background-color: #dedede;
        }

        .left-indent {
            margin-left: 30px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        p {
            font-size: 1.2em !important;
        }

        td, th {
            font-size: 1.2em !important;
        }
    }  

    p {
        font-size: 1.2em !important;
    }

    td, th {
        font-size: 1.2em !important;
    }

    html {
        margin: 10px !important;
    }

    .left-indent {
        margin-left: 50px;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    } 
</style>

<link rel="stylesheet" href="{{ URL::asset('adminlte.min.css') }}">

<div class="print-area">
    <br>
    <br>
    <h4 style="margin: 0px !important; padding: 0px !important;" class="text-center">{{ env('APP_COMPANY') }}</h4>
    <p style="margin: 0px !important; padding: 0px !important;" class="text-center">{{ env('APP_COMPANY_ABRV') }}</p>
    <p style="margin: 0px !important; padding: 0px !important;" class="text-center">{{ env('APP_ADDRESS') }}</p>
    <br>
    <h4 style="margin: 0px !important; padding: 0px !important;" class="text-center"><strong>DISTRIBUTION SYSTEM LOSS</strong></h4>
    <p style="margin: 0px !important; padding: 0px !important;" class="text-center">For the Month of {{ date('F Y', strtotime($sales->ServicePeriod)) }}</p>
    <br>
    <br>
    <div  style="padding-left: 60px; padding-right: 60px;">
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
                {{-- <tr>            
                    <td style="padding-left: 80px;">Calatrava Substation</td>
                    <td class="text-right">{{ $sales != null ? number_format($sales->CalatravaSubstation, 2) : 0 }}</td>
                </tr> --}}
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
    
    <br>
    <div class="row">
        <div class="col-lg-12">
            <p>Prepared By:</p>
            <br>
            <p><u><strong>{{ strtoupper(Auth::user()->name) }}</strong></u></p>

            <br>
            <p>Recommending Approval:</p>
        </div>

        <div class="col-sm-4">
            <br>
            <p class="text-center"><strong>ENGR. WILBE O. BILBAO</strong></p>
            <p class="text-center"><i>TSD Manager</i></p>
        </div>

        <div class="col-sm-4">
            <br>
            <p class="text-center"><strong>ANTHONY B. LAGRADA</strong></p>
            <p class="text-center"><i>OIC, CorPlan Manager</i></p>
        </div>

        <div class="col-sm-4">
            <br>
            <p class="text-center"><strong>ELREEN JANE Z. BANOT</strong></p>
            <p class="text-center"><i>Finance Manager</i></p>
        </div>

        <div class="col-sm-12">
            <p class="text-center">Approved By:</p>
            <br>
            <p class="text-center"><strong>ATTY. DANNY L. PONDEVILLA</strong></p>
            <p class="text-center"><i>General Manager</i></p>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>