@php
    use App\Models\ServiceAccounts;
    use App\Models\Bills;
    use App\Models\BAPAAdjustments;

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
            orientation: portrait;
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
        background-color: #dedede;
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

    .half {
        display: inline-table; 
        width: 49%;
    }

    .thirty {
        display: inline-table; 
        width: 30%;
    }

    .seventy {
        display: inline-table; 
        width: 69%;
    }

</style>

<div id="print-area" class="content">
    <p>Generated On: {{ date('F d, Y h:i:s A') }}</p>
    <br>
    <p class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</p>
    <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>
    <br>
    <p class="text-center"><strong>AF/PPD CASH VOUCHER</strong></p>
    <br> 
    <div class="half">
        <p>TOWN: <strong>{{ $town != null ? strtoupper($town->Town) : '' }}</strong></p>
        <p>BAPA: <strong>{{ $bapaName != null ? strtoupper($bapaName) : '' }}</strong></p>
    </div>
    
    <div class="half">
        <p class="text-right">BILLING MONTH: <strong>{{ $period != null ? strtoupper(date('F Y', strtotime($period))) : '' }}</strong></p>
        <p class="text-right">TRANSACTION DATE: <strong>{{ $dateAdjusted != null ? strtoupper(date('F d, Y', strtotime($dateAdjusted))) : '' }}</strong></p>
    </div>

    <br><br><br>
    <div class="half">
        <span>RECEIVED BY: <strong style="border-bottom: 1px solid #454545; padding-bottom: 2px;">{{ $representative != null ? strtoupper($representative) : '' }}</strong></span><br>
        <p style="margin-left: 80px; margin-top: 5px;">{{ $bapaAdjustmentData != null ? BAPAAdjustments::convertNumberToWord($bapaAdjustmentData->DiscountTotal) : 'ZERO' }}</p>
    </div>

    <div class="half">
        <p style="margin-left: 50px;">ANMOUNT OF: <strong>{{ $bapaAdjustmentData != null ? number_format($bapaAdjustmentData->DiscountTotal, 2) : '0.0' }}</strong></p>
    </div>
    <br>
    <br>
    <br>
    <div style="width: 100%; border-top: 2px solid #454545; border-bottom: 2px solid #454545; padding-top: 5px; padding-bottom: 5px;">
        <p class="text-center"><strong>PARTICULARS</strong></p>
    </div>

    <br>
    <div class="thirty">
        <p>Representing payment for the following:</p>
        <br>
        <p class="text-center"><strong>DATE</strong></p>
        <p class="text-center">{{ date('Y-m-d') }}</p>
        <p class="text-center"><strong>TOTAL</strong></p>
    </div>

    <div class="seventy">
        <br><br>
        <table style="width: 100%;">
            <thead>
                <th style="border-bottom: 1px solid #454545;" class="text-center">ACCEPTANCE FEE</th>
                <th style="border-bottom: 1px solid #454545;"  class="text-center">PROMPT PAYMENT DISCOUNT</th>
                <th style="border-bottom: 1px solid #454545;"  class="text-center">TOTAL</th>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">{{ $bapaAdjustmentData != null ? number_format($bapaAdjustmentData->DiscountTotal, 2) : '0.0' }}</td>
                    <td></td>
                    <td class="text-center">{{ $bapaAdjustmentData != null ? number_format($bapaAdjustmentData->DiscountTotal, 2) : '0.0' }}</td>
                </tr>
                <tr>
                    <th style="border-top: 1px solid #454545;" class="text-center">{{ $bapaAdjustmentData != null ? number_format($bapaAdjustmentData->DiscountTotal, 2) : '0.0' }}</th>
                    <th style="border-top: 1px solid #454545;"  class="text-center"></th>
                    <th style="border-top: 1px solid #454545;"  class="text-center">{{ $bapaAdjustmentData != null ? number_format($bapaAdjustmentData->DiscountTotal, 2) : '0.0' }}</th>
                </tr>
            </tbody>
        </table>
    </div>

    <br>
    <br>
    <div style="width: 100%; border-top: 2px solid #454545; padding-top: 5px; padding-bottom: 5px;">
        <br>
        <div class="thirty">
            <p>Prepared By:</p>
            <br><br>
            <span class="text-center" style="border-top: 1px solid #454545; padding-left: 10px; padding-right: 10px;">SCAO TELLER AUGMENTA</span>
        </div>

        <div class="thirty">
            <p>Approved By:</p>
            <br><br>
            <span class="text-center" style="border-top: 1px solid #454545; padding-left: 10px; padding-right: 10px;">{{ env('AREA_MANAGER') }}</span>
        </div>

        <div class="thirty">
            <p>Payment Received By:</p>
            <br><br>
            <span class="text-center" style="border-top: 1px solid #454545; padding-left: 10px; padding-right: 10px;">{{ $representative != null ? strtoupper($representative) : '' }}</span>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>