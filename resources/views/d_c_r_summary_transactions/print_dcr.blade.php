
@php
use App\Models\ServiceAccounts;
use App\Models\MemberConsumers;
use App\Models\Bills;
@endphp

<style>
@font-face {
    font-family: 'sax-mono';
    src: url('/fonts/saxmono.ttf');
}
html, body {
    font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
    /* font-stretch: condensed; */
    font-size: .82em;
}

table tbody th,td,
table thead th {
    font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
    /* font-stretch: condensed; */
    /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
    font-size: .71em;
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
        margin-left: 30px;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .break-page {        
        page-break-after: always;
    }

    .break-page:last-child {        
        page-break-after: auto;
    }

    td, th {
        padding-right: 5px;
        padding-left: 5px;
    }

    p {
        padding: 0px !important;
        margin: 0px;
    }
}  

html {
    margin: 10px !important;
}

p {
    padding: 0px !important;
    margin: 0px;
}

td, th {
    padding-right: 5px;
    padding-left: 5px;
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

@php
    use Illuminate\Support\Facades\Auth;
@endphp

{{-- <link rel="stylesheet" href="{{ URL::asset('adminlte.min.css') }}"> --}}

<div id="print-area" class="content">
    {{-- DCR BREAKDOWN --}}
    <div class="dcr-breakdown break-page">
        <p class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</p>
        <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>
        <p class="text-center">DAILY COLLECTION REPORT PER ACCOUNT CODE</p>
        <br>
        <p>TELLER: <span style="margin-left: 85px;">{{ Auth::user()->name; }}</span></p>
        <p>COLLECTION CENTER: <span style="margin-left: 10px;">{{ strtoupper(env('APP_LOCATION')) }}</span></p>
        <p>COLLECTION DATE: <span style="margin-left: 25px;">{{ date('F d, Y', strtotime($day)) }}</span></p>
        <br>
        <table>
            <thead>
                <th style="border-bottom: 1px solid #565656;">GL Code</th>
                <th style="border-bottom: 1px solid #565656;">Description</th>
                <th style="border-bottom: 1px solid #565656;" class="text-right">Amount</th>
            </thead>
            <tbody>
                @php
                    $total = 0.0;
                @endphp
                @foreach ($data as $item)
                    @if (floatval($item->Amount) == 0)
                        
                    @else
                        <tr>
                            <td>{{ $item->GLCode }}</td>
                            <td>{{ $item->Description }}</td>
                            <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                        </tr>
                        @php
                            $total += floatval($item->Amount);
                        @endphp
                    @endif
                    
                @endforeach
                <tr>
                    <th style="border-top: 1px solid #565656;">Total</th>
                    <td style="border-top: 1px solid #565656;"></td>
                    <th style="border-top: 1px solid #565656;" class="text-right">{{ number_format($total, 2) }}</th>
                </tr>
            </tbody>
        </table>
        <br>
        <p>Prepared By:</p>
        <br>
        <br>
        <br>
        <span style="border-top: 1px solid #454545; padding-left: 10px; padding-right: 10px;">{{ strtoupper(Auth::user()->name) }}</span>
    </div>

    {{-- POWER BILLS --}}
    <div class="dcr-breakdown break-page">
        <p class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</p>
        <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>
        <p class="text-center">POWER BILLS DAILY COLLECTION REPORT</p>
        <br>
        <p>TELLER: <span style="margin-left: 85px;">{{ Auth::user()->name; }}</span></p>
        <p>COLLECTION DATE: <span style="margin-left: 25px;">{{ date('F d, Y', strtotime($day)) }}</span></p>
        <br>
        <table style="width: 100%;">
            <thead>
                <th style="border-bottom: 1px solid #565656; width: 5%;">OR Number</th>
                <th style="border-bottom: 1px solid #565656;">Account No.</th>
                <th style="border-bottom: 1px solid #565656;">Consumer Name</th>
                <th style="border-bottom: 1px solid #565656;">Billing <br>Month</th>
                <th style="border-bottom: 1px solid #565656;">Bill #</th>
                <th style="border-bottom: 1px solid #565656;" class="text-right">kWh Used</th>
                <th style="border-bottom: 1px solid #565656;" class="text-right">Amount Paid</th>
            </thead>
            <tbody>
                @if ($powerBills != null)
                    @php
                        $total = 0;
                        $i=0;
                    @endphp
                    @foreach ($powerBills as $item)
                        <tr>
                            <td>{{ $item->ORNumber }}</td>
                            <td>{{ $item->OldAccountNo }}</td>
                            <td>{{ $item->ServiceAccountName }}</td>
                            <td>{{ date('MY', strtotime($item->ServicePeriod)) }}</td>
                            <td>{{ $item->BillNumber }}</td>
                            <td class="text-right">{{ $item->KwhUsed }}</td>
                            <td class="text-right">{{ number_format($item->NetAmount, 2) }}</td>
                        </tr>   
                        @php
                            $total = $total + floatval($item->NetAmount);
                            $i++;
                        @endphp   
                    @endforeach   
                    <tr>
                        <th colspan="2" style="border-top: 1px solid #565656;">Total ({{ $i }} payments)</th> 
                        <th style="border-top: 1px solid #565656;"></th>
                        <th style="border-top: 1px solid #565656;"></th>
                        <th style="border-top: 1px solid #565656;"></th>
                        <th style="border-top: 1px solid #565656;"></th>
                        <th style="border-top: 1px solid #565656;" class="text-right">{{ number_format($total, 2) }}</th>
                    </tr>         
                @endif        
            </tbody>
        </table>
        <br>
        <p>Prepared By:</p>
        <br>
        <br>
        <br>
        <span style="border-top: 1px solid #454545; padding-left: 10px; padding-right: 10px;">{{ strtoupper(Auth::user()->name) }}</span>
    </div>


    {{-- NON POWER BILLS --}}
    <div class="dcr-breakdown break-page">
        <p class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</p>
        <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>
        <p class="text-center">NON-POWER BILLS/MISCELLANEOUS DAILY COLLECTION REPORT</p>
        <br>
        <p>TELLER: <span style="margin-left: 85px;">{{ Auth::user()->name; }}</span></p>
        <p>COLLECTION DATE: <span style="margin-left: 25px;">{{ date('F d, Y', strtotime($day)) }}</span></p>
        <br>
        <table style="width: 100%;">
            <thead>
                <th style="text-align: left; border-bottom: 1px solid #565656; width: 5%">OR Number</th>
                <th style="border-bottom: 1px solid #565656;">Account Number</th>
                <th style="border-bottom: 1px solid #565656;">Account Name</th>
                <th style="border-bottom: 1px solid #565656;">GL Code</th>
                <th style="border-bottom: 1px solid #565656;">Item</th>
                <th style="border-bottom: 1px solid #565656;">Payment Used</th>
                <th style="border-bottom: 1px solid #565656;" class="text-right">Amount</th>
            </thead>
            <tbody>
                @if ($nonPowerBills != null)
                    @php
                        $prevHolder = null;
                        $prev = null;
                        $total = 0;
                        $i=0;
                    @endphp
                    @foreach ($nonPowerBills as $item)
                        @php
                            $prevHolder = $item->ORNumber;
                        @endphp
                        <tr>
                            <td>{{ $prevHolder==$prev ? '' : $item->ORNumber }}</td>
                            <td>{{ $prevHolder==$prev ? '' : $item->OldAccountNo }}</td>
                            <td>{{ $prevHolder==$prev ? '' : $item->ServiceAccountName }}</td>
                            <td>{{ $item->AccountCode }}</td>
                            <td>{{ $item->Particular }}</td>
                            <td>{{ $item->PaymentUsed }}</td>
                            <td class="text-right">{{ $item->Total != null ? number_format($item->Total, 2) : '0.0' }}</td>
                        </tr>   
                        @php
                            if ($prev != $prevHolder) {                                
                                $i++;   
                            }
                            $prev = $prevHolder;    
                            $total = $total + floatval($item->Total);             
                        @endphp   
                    @endforeach    
                    <tr>
                        <th style="border-top: 1px solid #565656;" colspan="2">Total ({{ $i }} payments)</th> 
                        <th style="border-top: 1px solid #565656;"></th>
                        <th style="border-top: 1px solid #565656;"></th>
                        <th style="border-top: 1px solid #565656;"></th>
                        <th style="border-top: 1px solid #565656;"></th>
                        <th style="border-top: 1px solid #565656;" class="text-right">{{ number_format($total, 2) }}</th>
                    </tr>          
                @endif        
            </tbody>
        </table>
        <br>
        <p>Prepared By:</p>
        <br>
        <br>
        <br>
        <span style="border-top: 1px solid #454545; padding-left: 10px; padding-right: 10px;">{{ strtoupper(Auth::user()->name) }}</span>
    </div>

    {{-- CANCELLED OR --}}
    <div class="dcr-breakdown break-page">
        <p class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</p>
        <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>
        <p class="text-center">LIST OF CANCELLED OR</p>
        <br>
        <p>TELLER: <span style="margin-left: 85px;">{{ Auth::user()->name; }}</span></p>
        <p>COLLECTION DATE: <span style="margin-left: 25px;">{{ date('F d, Y', strtotime($day)) }}</span></p>
        <br>
        <table style="width: 100%;">
            <thead>
                <th style="border-bottom: 1px solid #565656; width: 5%;">OR Number</th>
                <th style="border-bottom: 1px solid #565656;">Account No.</th>
                <th style="border-bottom: 1px solid #565656;">Consumer Name</th>
                <th style="border-bottom: 1px solid #565656;">Billing <br>Month</th>
                <th style="border-bottom: 1px solid #565656;">Bill #</th>
                <th style="border-bottom: 1px solid #565656;" class="text-right">Amount Paid</th>
            </thead>
            <tbody>
                @if ($allCancelled != null)
                    @php
                        $total = 0;
                        $i=0;
                    @endphp
                    @foreach ($allCancelled as $item)
                        <tr>
                            <td>{{ $item->ORNumber }}</td>
                            <td>{{ $item->OldAccountNo }}</td>
                            <td>{{ $item->ServiceAccountName }}</td>
                            <td>{{ $item->ServicePeriod != null ? date('MY', strtotime($item->ServicePeriod)) : '' }}</td>
                            <td>{{ $item->BillNumber }}</td>
                            <td class="text-right">{{ number_format($item->Total, 2) }}</td>
                        </tr>   
                        @php
                            $total = $total + floatval($item->Total);
                            $i++;
                        @endphp   
                    @endforeach   
                    <tr>
                        <th colspan="2" style="border-top: 1px solid #565656;">Total ({{ $i }} payments)</th> 
                        <th style="border-top: 1px solid #565656;"></th>
                        <th style="border-top: 1px solid #565656;"></th>
                        <th style="border-top: 1px solid #565656;"></th>
                        <th style="border-top: 1px solid #565656;" class="text-right">{{ number_format($total, 2) }}</th>
                    </tr>         
                @endif        
            </tbody>
        </table>
        <br>
        <p>Prepared By:</p>
        <br>
        <br>
        <br>
        <span style="border-top: 1px solid #454545; padding-left: 10px; padding-right: 10px;">{{ strtoupper(Auth::user()->name) }}</span>
    </div>

    {{-- CHECK PAYMENTS --}}
    <div class="dcr-breakdown break-page">
        <p class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</p>
        <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>
        <p class="text-center">SUMMARY OF CHECK PAYMENTS</p>
        <br>
        <p>TELLER: <span style="margin-left: 85px;">{{ Auth::user()->name; }}</span></p>
        <p>COLLECTION DATE: <span style="margin-left: 25px;">{{ date('F d, Y', strtotime($day)) }}</span></p>
        <br>
        <table style="width: 100%;">
            <thead>
                <th style="border-bottom: 1px solid #565656; width: 5%;">OR Number</th>
                <th style="border-bottom: 1px solid #565656;">Account No.</th>
                <th style="border-bottom: 1px solid #565656;">Consumer Name</th>
                <th style="border-bottom: 1px solid #565656;">Check #</th>
                <th style="border-bottom: 1px solid #565656;">Bank</th>
                <th style="border-bottom: 1px solid #565656;" class="text-right">Amount Paid</th>
                <th style="border-bottom: 1px solid #565656;">Remarks</th>
            </thead>
            <tbody>
                @if ($allCheck != null)
                    @php
                        $total = 0;
                        $i=0;
                    @endphp
                    @foreach ($allCheck as $item)
                        <tr>
                            <td>{{ $item->ORNumber }}</td>
                            <td>{{ $item->OldAccountNo }}</td>
                            <td>{{ $item->ServiceAccountName }}</td>
                            <td>{{ $item->CheckNo }}</td>
                            <td>{{ $item->Bank }}</td>
                            <td class="text-right">{{ number_format($item->Total, 2) }}</td>
                            <td></td>
                        </tr>   
                        @php
                            $total = $total + floatval($item->Total);
                            $i++;
                        @endphp   
                    @endforeach   
                    <tr>
                        <th colspan="2" style="border-top: 1px solid #565656;">Total ({{ $i }} payments)</th> 
                        <th style="border-top: 1px solid #565656;"></th>
                        <th style="border-top: 1px solid #565656;"></th>
                        <th style="border-top: 1px solid #565656;"></th>
                        <th style="border-top: 1px solid #565656;" class="text-right">{{ number_format($total, 2) }}</th>
                        <th style="border-top: 1px solid #565656;"></th>
                    </tr>         
                @endif        
            </tbody>
        </table>
        <br>
        <p>Prepared By:</p>
        <br>
        <br>
        <br>
        <span style="border-top: 1px solid #454545; padding-left: 10px; padding-right: 10px;">{{ strtoupper(Auth::user()->name) }}</span>
    </div>
</div>
<script type="text/javascript">
window.print();

// window.setTimeout(function(){
//     window.history.go(-1)
// }, 800);
</script>