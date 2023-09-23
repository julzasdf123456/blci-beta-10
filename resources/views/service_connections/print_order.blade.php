
@php
    use App\Models\ServiceConnections;
    use App\Models\Users;

    if ($serviceConnectionInspections != null) {
        $inspector = Users::find($serviceConnectionInspections->Inspector);
    } else {
        $inspector = null;
    }
@endphp

<!-- AdminLTE -->
{{-- <link rel="stylesheet" href="{{ URL::asset('css/adminlte.min.css'); }} "> --}}

{{-- <style>
    @media print {
        @page {
            margin: 10px;
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

        .map {
            width: 90% auto;
            height: 400px;
        }

        p {
            margin: 0 !important;
            padding: 0 !important;
        }
    }  

    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    } 

    .map {
        width: 90% auto;
        height: 400px;
    }

    p {
        margin: 0 !important;
        padding: 0 !important;
    }
</style> --}}

<style>
    @font-face {
        font-family: 'sax-mono';
        /* src: url('/fonts/saxmono.ttf'); */
    }
    html, body {
        margin: 0;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: sans-serif;
        /* font-stretch: condensed; */
        font-size: .85em;
    }

    table tbody th,td,
    table thead th {
        font-family: sans-serif;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: .85em;
    }
    @media print {
        @page {
            margin: 10px;
        }

        header {
            display: none;
        }

        .divider {
            width: 100%;
            margin: 10px auto;
            height: 1px;
            background-color: #878787;
        }

        .left-indent {
            margin-left: 30px;
        }

        p {
            padding: 0px !important;
            margin: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }
    }  
    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #878787;
    } 

    p {
        padding: 0px !important;
        margin: 0px;
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .text-indent {
        text-indent: 40px;
    }

    .col-sm-4 {
        width: 33%;
        display: inline-block;    
    }

    .col-sm-6 {
        width: 42%;
        display: inline-block;    
    }

    .float-left {
        float: left;
    }

</style>    

<div id="print-area">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 15px;">
            {{-- HEADER --}}
            <img src="{{ URL::asset('imgs/noneco-official-logo.png'); }}" class="float-left" style="height: 60px;" alt="Image"> 
            <p class="text-center"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>
            <p class="text-center"><strong>{{ strtoupper(env('APP_ADDRESS')) }}</strong></p>
            <p class="text-center"><strong>{{ strtoupper(env('APP_COMPANY_CONTACT')) }}</strong></p>
            <h3 class="text-center">TURN ON ORDER</h3>
            <table class="table table-borderless table-sm" style="width: 100%;">
                <tr>
                    <td class="text-left"></td>
                    <td class="text-left"></td>
                    <th class="text-left">Date: </th>
                    <td class="text-left">{{ date('M d, Y h:i A') }}</td>
                </tr>
                <tr>
                    <th class="text-left">CONSUMER : </th>
                    <td class="text-left">{{ $serviceConnection->ServiceAccountName }}</td>
                    <th class="text-left">Turn On # : </th>
                    <td class="text-left">{{ $serviceConnection->id }}</td>
                </tr>
                <tr>
                    <th class="text-left">JOB LOCATION : </th>
                    <td class="text-left">{{ strtoupper(ServiceConnections::getAddress($serviceConnection)) }}</td>
                    <th class="text-left">COM No.: </th>
                    <td class="text-left">________________</td>
                </tr>
                <tr>
                    <th class="text-left">ROUTE NO : </th>
                    <td class="text-left">________________</td>
                    <th class="text-left">APP No.: </th>
                    <td class="text-left">________________</td>
                </tr>
                <tr>
                    <th class="text-left">SERVICE REQUIRED : </th>
                    <td class="text-left">{{ strtoupper($serviceConnection->AccountType) }}</td>
                    <th class="text-left">SERVICE DROP LENGTH : </th>
                    <td class="text-left">{{ $serviceConnectionInspections->SDWLengthAsInstalled }} mtrs</td>
                </tr>
            </table>

            {{-- PAYMENT --}}
            <table class="table table-sm" style="width: 100%;">
                <thead>
                    <th style="border-bottom: 1px solid #454455">Description</th>
                    <th style="border-bottom: 1px solid #454455" class="text-right">Quantity</th>
                    <th style="border-bottom: 1px solid #454455" class="text-right">Amount</th>
                    <th style="border-bottom: 1px solid #454455" class="text-right">OR Number</th>
                    <th style="border-bottom: 1px solid #454455" class="text-right">OR Date</th>
                    <th style="border-bottom: 1px solid #454455" class="text-right">Teller</th>
                </thead>
                <tbody>
                    @if ($transactionDetails != null)
                        @foreach ($transactionDetails as $item)
                            <tr>
                                <td>{{ $item->Particular }}</td>
                                <td class="text-right">1</td>
                                <td class="text-right">{{ $item->Total != null ? number_format($item->Total, 2) : 0 }}</td>
                                <td class="text-right">{{ $transactionIndex != null ? $transactionIndex->ORNumber : '-' }}</td>
                                <td class="text-right">{{ $transactionIndex != null ? ($transactionIndex->ORDate != null ? date('m/d/Y', strtotime($transactionIndex->ORDate)) : '-') : '-' }}</td>
                                <td class="text-right">{{ $transactionIndex != null ? ($transactionIndex->UserId != null ? (Users::find($transactionIndex->UserId) != null ? Users::find($transactionIndex->UserId)->name : '-' ) : '-') : '-' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th style="border-top: 1px solid #454455" >Total</th>
                            <th style="border-top: 1px solid #454455" ></th>
                            <th style="border-top: 1px solid #454455"  class="text-right">{{ $transactionIndex != null ? number_format($transactionIndex->Total, 2) : '-' }}</th>
                            <th style="border-top: 1px solid #454455" ></th>
                            <th style="border-top: 1px solid #454455" ></th>
                            <th style="border-top: 1px solid #454455" ></th>
                            <th style="border-top: 1px solid #454455" ></th>
                        </tr>
                    @else
                        <tr>
                            <td colspan="6" class="text-center">Payment not found!</td>
                        </tr>
                    @endif
                    
                </tbody>
            </table>
        </div>

        {{-- SIGNATORIES --}}
        <div class="col-sm-4">
            <p><strong>Processed By:</strong></p>
            <br>
            <p class="text-center"><strong>{{ env('SC_CUSTODIAN') }}</strong></p>
            <p class="text-center">Customer Welfare Coordinator</p>
        </div>

        <div class="col-sm-4">
            <p><strong>Checked By:</strong></p>
            <br>
            <p class="text-center"><strong>{{ env('MRIV_OPERATIONS_MAINTENANCE_SUPERVISOR') }}</strong></p>
            <p class="text-center">Ops. and Maintenance Supervisor</p>
        </div>

        <div class="col-sm-4">
            <p><strong>Approved:</strong></p>
            <br>
            <p class="text-center"><strong>{{ env('AREA_MANAGER') }}</strong></p>
            <p class="text-center">AOD Manager</p>
        </div>

        <div class="col-sm-12">
            <div class="divider"></div>
            <p class="text-center"><strong>{{ strtoupper('Accomplishment Report') }}</strong></p>
            <br>
            <table style="width: 100%;">
                <thead>
                    <th class="text-center"><strong>DESCRIPTION</strong></th>
                    <th class="text-center">NEW METER</th>
                    <th class="text-center">OLD METER</th>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>Meter Brand & No.</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>Type & Class</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>Seal No.</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>Demand/Initial Reading</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>kH</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>CT Ratio</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>Rr</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>Multiplier</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>TA</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>PTR</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>Voltage</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>Form</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>Wire</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>Remarks</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>Executed By</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                    <tr>
                        <td class="text-left" style="width: 28%; margin-right: 10px;"><strong>Date & Time</strong></td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%;">: </td>
                        <td class="text-left" style="border-bottom: 1px solid #454455; width: 35%; margin-left: 10px !important;">: </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p style="border: 1px solid #878787; margin: 30px !important; padding: 10px !important;">This is to certify that {{ env('APP_COMPANY_ABRV') }} has newly installed the kWh Meter in my residence / establishment which bears the 
            seal of {{ env('APP_COMPANY_ABRV') }}. The Meter glass cover, meter socket, and its terminals have been inspected in my presence and verified
            to be in good order and condition at the same time of installation in conformity with the standards of {{ env('APP_COMPANY_ABRV') }}.</p>

        <div class="col-sm-6" style="padding: 20px;">
            <p class="text-center" style="border-bottom: 1px solid #989898;"><strong>{{ $serviceConnection->ServiceAccountName }}</strong></p>
            <p class="text-center">NAME OF CONSUMER</p>
        </div>
        <div class="col-sm-6" style="padding: 20px;">
            <p class="text-center" style="border-bottom: 1px solid #989898;"><strong></strong></p>
            <p class="text-center">SIGNATURE OF CONSUMER</p>
        </div>
    </div>
</div>

<script type="text/javascript">   

    // function getLocData() {
    //     var centerLoc = "";

    //     if (document.getElementById("building-latlng").innerText === "") {
    //         if (document.getElementById("metering-latlng").innerText === "") {
    //             centerLoc = document.getElementById("tapping-latlng").innerText;
    //         } else {
    //             centerLoc = document.getElementById("metering-latlng").innerText;
    //         }            
    //     } else {
    //         centerLoc = document.getElementById("building-latlng").innerText;
    //     }

    //     return centerLoc;
    // }

    // // MAPBOX
    // mapboxgl.accessToken = 'pk.eyJ1IjoianVsemxvcGV6IiwiYSI6ImNqZzJ5cWdsMjJid3Ayd2xsaHcwdGhheW8ifQ.BcTcaOXmXNLxdO3wfXaf5A';

    // var centerLoc = getLocData();

    // var map = new mapboxgl.Map({
    //     container: 'map',
    //     zoom: 15,
    //     center: [centerLoc.split(",")[1], centerLoc.split(",")[0]],
    //     style: 'mapbox://styles/mapbox/streets-v11'
    // });

    // const marker = new mapboxgl.Marker()
    //     .setLngLat([centerLoc.split(",")[1], centerLoc.split(",")[0]])
    //     .addTo(map);

    // map.once('idle',function(){
    //     window.print();

    //     window.setTimeout(function(){
    //         window.location.href = "{{ route('serviceConnections.energization') }}";
    //     }, 1000);
    // });   
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 1000); 
</script>
