
@php
use Illuminate\Support\Facades\DB;
@endphp

<style>
html, body {
    font-family: sans-serif;
    /* font-stretch: condensed; */
    font-size: .85em;
}

th, td {
    font-family: sans-serif;
    /* font-stretch: condensed; */
    font-size: .68em;
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

    .print-area {        
        page-break-after: always;
    }

    .print-area:last-child {        
        page-break-after: auto;
    }

    .u-bottom {
        border-bottom: 1px solid #444555;
        padding-bottom: 2px;
        padding-left: 10px;
        padding-right: 10px;
    }

    .half {
        display: inline-table; 
        width: 49%;
    }

    table, th, tr {
        border-collapse: collapse;
        border: 1px solid #444555;
    }

    p {
        margin: 0;
        padding: 0;
    }
}  

.left-indent {
    padding-left: 15px;
}

.left-indent-more {
    padding-left: 40px;
}

.text-right {
    text-align: right;
}

.text-left {
    text-align: left;
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

.u-bottom {
    border-bottom: 1px solid #444555;
    padding-bottom: 2px;
    padding-left: 10px;
    padding-right: 10px;
}

.third {
    display: inline-table; 
    width: 31%;
}

table, th, tr, td {
    border-collapse: collapse;
    border: 1px solid #444555;
}

th, td {
    padding-top: 4px;
    padding-bottom: 3px;
}

.no-border-top-bottom {
    border-bottom: 0px !important;
    border-top: 0px !important;
}

.border-bottom-only {
    border-bottom: 1px solid #444555 !important;
    border-top: 0px !important;
}

p {
    margin: 0;
    padding: 0;
}
</style>

<div id="print-area">
    <div style="text-align: center; display: inline;">
        <img src="{{ URL::asset('imgs/noneco-official-logo.png'); }}" width="60px;" style="position: absolute; left: 0; top: 0;"> 

        <p class="text-center"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>
        <p class="text-center">{{ env('APP_ADDRESS') }}</p>
        <br>
        <h4 class="text-center" style="margin: 0px !important; padding: 0px !important;">SUMMARY OF SALES PER TYPE OF CONSUMER</h4>
        <p class="text-center">BILLING MONTH OF {{ strtoupper(date('F Y', strtotime($period))) }}</p>
    </div>
    <br>
    <div>
        <table class="table table-bordered" style="width: 100%;">
            <thead>
                <tr>
                    <th rowspan="2" class="text-center">Classification</th>
                    <th rowspan="2" class="text-center">Number of Consumers</th>
                    <th colspan="2" class="text-center">TOTAL SOLD</th>
                    <th rowspan="2" class="text-center">AMOUNT</th>
                    <th rowspan="2" class="text-center">REAL PROPERTY TAX</th>
                    <th colspan="5" class="text-center">VALUE ADDED TAX</th>
                    <th rowspan="2" class="text-center">TOTAL AMOUNT</th>
                </tr>
                <tr>
                    <th class="text-center">KWHR</th>
                    <th class="text-center">KW</th>
                    <th class="text-center">GENERATION</th>
                    <th class="text-center">TRANSMISSION</th>
                    <th class="text-center">SYSTEM LOSS</th>
                    <th class="text-center">DIST./OTHERS</th>
                    <th class="text-center">TOTAL</th>
                </tr>
            </thead>
            @if ($sales != null && $sales->Status=='CLOSED')
                @include('kwh_sales.closed_summary_of_sales')
            @else
                @include('kwh_sales.attach_summary_of_sales')
            @endif 
        </table>
    </div>
    <br>
    <div style="width: 100%;">
        <p>Prepared By:</p>
        <br>
        <br>
        <p><u><strong>{{ strtoupper(Auth::user()->name) }}</strong></u></p>
    </div>
    <br>
    <br>
    <br>
    <div>
        <div class="third">
            <p>Noted By:</p>
            <br>
            <br>
            <p class="text-center"><strong>ANTHONY B. LAGRADA</strong></p>
            <p class="text-center"><i>OIC, CorPlan Manager</i></p>
        </div>

        <div class="third">
            <p>Recommending Approval:</p>
            <br>
            <br>
            <p class="text-center"><strong>ELREEN JANE Z. BANOT</strong></p>
            <p class="text-center"><i>Finance Manager</i></p>
        </div>

        <div class="third">
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
    }, 1000);
</script>