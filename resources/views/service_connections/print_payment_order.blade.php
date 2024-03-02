@php
    use App\Models\MemberConsumers;
    use App\Models\ServiceConnections;
    use App\Models\Users;
@endphp

<style>
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
</style>

<link rel="stylesheet" href="{{ URL::asset('adminlte.min.css') }}">

@for ($i = 0; $i < 2; $i++)
    <div  class="content" style="margin: 15px;">
        <div class="row">
            <div class="col-sm-12">
                {{-- <img src="{{ URL::asset('imgs/company_logo.png'); }}" class="float-left img-circle" style="height: 80px;" alt="Image">  --}}
                <p class="text-center" style="font-size: 1.2em;"><strong>{{ env('APP_COMPANY') }} ({{ env('APP_COMPANY_ABRV') }})</strong></p>
                <p class="text-center">{{ env('APP_ADDRESS') }}</p>
                <br>
            </div>
            <div class="col-sm-12">
                <p class="text-center" style="font-size: 1em;"><strong>SERVICE CONNECTION PAYMENT ORDER STUB</strong></p>
                <br>
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td>Customer Name:</td>
                            <td><strong>{{ $serviceConnection->ServiceAccountName }}</strong></td>
                            <td>Address: </td>
                            <td><strong>{{ ServiceConnections::getAddress($serviceConnection) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Account Number:</td>
                            <td><strong>{{ $serviceConnection->AccountNumber }}</strong></td>
                            <td>Application Type: </td>
                            <td><strong>{{ $serviceConnection->AccountApplicationType }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6" style="margin-top: 20px;">
                <p class="text-muted">Application Fees</p>
                <table class="table table-sm" style="margin-bottom: 140px;">
                    <tbody>
                        <tr>
                            <td>Material Deposit</td>
                            <td class="text-right"><strong>{{ $paymentOrder != null && $paymentOrder->MaterialDeposit != null ? number_format($paymentOrder->MaterialDeposit, 2) : '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Over-head Expenses</td>
                            <td class="text-right"><strong>{{ $paymentOrder != null && $paymentOrder->OverheadExpenses != null ? number_format($paymentOrder->OverheadExpenses, 2) : '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Transformer Rental Fees</td>
                            <td class="text-right"><strong>{{ $paymentOrder != null && $paymentOrder->TransformerRentalFees != null ? number_format($paymentOrder->TransformerRentalFees, 2) : '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Apprehension</td>
                            <td class="text-right"><strong>{{ $paymentOrder != null && $paymentOrder->Apprehension != null ? number_format($paymentOrder->Apprehension, 2) : '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Customer Deposit</td>
                            <td class="text-right"><strong>{{ $paymentOrder != null && $paymentOrder->CustomerDeposit != null ? number_format($paymentOrder->CustomerDeposit, 2) : '-' }}</strong></td>
                        </tr>                    
                        <tr>
                            <td>CIAC</td>
                            <td class="text-right"><strong>{{ $paymentOrder != null && $paymentOrder->CIAC != null ? number_format($paymentOrder->CIAC, 2) : '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Service Fee</td>
                            <td class="text-right"><strong>{{ $paymentOrder != null && $paymentOrder->ServiceFee != null ? number_format($paymentOrder->ServiceFee, 2) : '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Others</td>
                            <td class="text-right"><strong>{{ $paymentOrder != null && $paymentOrder->Others != null ? number_format($paymentOrder->Others, 2) : '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Materials Total</td>
                            <td class="text-right"><strong>{{ $paymentOrder != null && $paymentOrder->MaterialTotal != null ? number_format($paymentOrder->MaterialTotal, 2) : '-' }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-sm-6" style="margin-top: 20px;">
                <p class="text-muted">Over All Total</p>
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <td>Local F. Tax</td>
                            <td class="text-right"><strong>{{ $paymentOrder != null && $paymentOrder->LocalFTax != null ? number_format($paymentOrder->LocalFTax, 2) : '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Sub-total w/o VAT</td>
                            <td class="text-right"><strong>{{ $paymentOrder != null && $paymentOrder->SubTotal != null ? number_format($paymentOrder->SubTotal, 2) : '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td>VAT</td>
                            <td class="text-right"><strong>{{ $paymentOrder != null && $paymentOrder->VAT != null ? number_format($paymentOrder->VAT, 2) : '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>OVERALL TOTAL</strong></td>
                            <td class="text-right"><strong><h2>{{ $paymentOrder != null && $paymentOrder->OverAllTotal != null ? number_format($paymentOrder->OverAllTotal, 2) : '-' }}</h2></strong></td>
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