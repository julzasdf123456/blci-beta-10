@php
    use App\Models\MemberConsumers;
    use App\Models\ServiceConnections;
    use App\Models\Users;
@endphp

<style>
    @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    }

    html, body {
        font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
        /* font-family: sans-serif; */
        /* font-stretch: condensed; */
        font-size: .85em;
    }

    table tbody th,td,
    table thead th {
        /* font-family: sans-serif; */
        font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: .72em;
    }

    @media print {
    @page {
        /* size: landscape !important; */
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
        margin-left: 30px !important;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .print-area {        
        page-break-after: always;
    }

    .print-area:last-child {        
        page-break-after: auto;
    }

    .left-indent-more {
        margin-left: 90px !important;
    }
}  

html {
    margin: 10px !important;
}

.left-indent {
    margin-left: 50px !important;
}

.left-indent-more {
    margin-left: 90px !important;
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

p {
    padding: 0px !important;
    margin: 0px !important;
}

.row {
    width: 100%;
}

.col-sm-4 {
    width: 33%;
    display: inline-block;    
}

.col-sm-6 {
    width: 48%;
    display: inline-block;    
}

.col-sm-12 {
    width: 95%;
    display: inline-block;    
}
</style>

@for ($i = 0; $i < 2; $i++)
    @if ($i==1)
        <br>
    @endif
    <div  class="content" style="margin: 15px;">
        <div class="row">
            <div class="col-sm-12">
                {{-- <img src="{{ URL::asset('imgs/company_logo.png'); }}" class="float-left img-circle" style="height: 80px;" alt="Image">  --}}
                <p class="text-center" style="font-size: 1.2em;"><strong>{{ env('APP_COMPANY') }} ({{ env('APP_COMPANY_ABRV') }})</strong></p>
                <p class="text-center">{{ env('APP_ADDRESS') }}</p>
                <br>
            </div>
            <div class="col-sm-12">
                <p class="text-center" style="font-size: 1em;"><strong>SERVICE CONNECTION INSPECTION FEE STUB</strong></p>
                <br>
                <table class="table table-sm" style="width: 100%;">
                    <tbody>
                        <tr>
                            <td>Customer Name:</td>
                            <td><strong>{{ $serviceConnection->ServiceAccountName }}</strong></td>
                            <td>Address: </td>
                            <td><strong>{{ ServiceConnections::getAddress($serviceConnection) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Account Number:</td>
                            <td><strong>{{ $serviceConnection->BarangayCode . '-' . $serviceConnection->TypeOfCustomer . '-' . $serviceConnection->AccountNumber . '-' . $serviceConnection->NumberOfAccounts }}</strong></td>
                            <td>Application Type: </td>
                            <td><strong>{{ $serviceConnection->AccountApplicationType }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-sm-6" style="margin-top: 20px;">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td>Inspection Fee</td>
                            <td class="text-right"><strong>50.00</strong></td>
                        </tr>
                        <tr>
                            <td>Local F. Tax</td>
                            <td class="text-right"><strong>0.38</strong></td>
                        </tr>
                        <tr>
                            <td>Sub-total w/o VAT</td>
                            <td class="text-right"><strong>50.38</strong></td>
                        </tr>
                        <tr>
                            <td>VAT</td>
                            <td class="text-right"><strong>6.00</strong></td>
                        </tr>
                        <tr>
                            <td><strong>OVERALL TOTAL</strong></td>
                            <td class="text-right"><strong><h2>{{ $paymentOrder != null && $paymentOrder->InspectionFee != null ? number_format($paymentOrder->InspectionFee, 2) : '-' }}</h2></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endfor


<script type="text/javascript">
    window.print();
    
    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>