@php
    use App\Models\ServiceAccounts;
    use App\Models\IDGenerator;
    use App\Models\Readings;
    use Illuminate\Support\Facades\Auth;
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
            /* margin: 10px; */
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

    td, th {
        border: 1px solid #999999;
    }

    table {        
        border-collapse: collapse;
    }

</style>

<div id="print-area" class="content">
    <table style="width: 100%;">
        <p class="text-center">{{ strtoupper(env('APP_COMPANY')) }}</p>
        <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>
        <p class="text-center">Collection Efficiency Per Meter Reader</p>
        <p class="text-center">Collection Month: {{ date('F Y', strtotime($month)) }}</p>

        <p class="text-left">AREA : {{ $townData != null ? $townData->id : '' }} - {{ $townData != null ? $townData->Town : '' }}</p>
        <p class="text-left">METER READER : {{ $mreader != null ? strtoupper($mreader->username) : '' }} - {{ $mreader != null ? strtoupper($mreader->name) : '' }}</p>
        <p class="text-left">GROUPING : All</p>
        <thead>
            <tr>
                <th class="align-middle" rowspan="4">Route<br>Code</th>
                <th class="text-center" colspan="2">{{ strtoupper(date('F Y', strtotime($period))) }} SALES</th>
                <th class="text-center" colspan="4">{{ strtoupper(date('F Y', strtotime($period))) }} COLLECTION</th>
                <th colspan="2"></th>
                <th class="text-center" colspan="2">{{ strtoupper(date('F Y', strtotime($month))) }}</th>
                <th class="text-center" colspan="4">{{ strtoupper(date('F Y', strtotime($period))) }} SALES</th>
                <th class="text-center" colspan="4">COLLECTION EFFICIENCY</th>
                <th class="text-center" colspan="3">OUTSTANDING ACCOUNTS</th>
            </tr>   
            <tr>
                <th></th>
                <th></th>
                <th colspan="2" class="text-center">PREV MONTH</th>
                <th colspan="2" class="text-center">THIS MONTH</th>
                <th colspan="2" class="text-center">COLLECTED ARREARS</th>
                <th colspan="2" class="text-center">ADVANCE COLLECTION</th>
                <th colspan="2" class="text-center">DISCONNECTED</th>
                <th colspan="2" class="text-center">OTHER ADJUSTMENTS</th>
                <th colspan="2" rowspan="2" class="text-center">All</th> 
                <th colspan="2" rowspan="2" class="text-center">exclude disco<br>& other ajd</th>
                <th colspan="3" class="text-center">Uncollected</th>
            </tr> 
            <tr>
                <th></th>
                <th class="text-right">Bill Amount</th>
                <th></th>
                <th class="text-right">Bill Amount</th> 
                <th></th>
                <th class="text-right">Bill Amount</th> 
                <th></th>
                <th class="text-right">Bill Amount</th> 
                <th></th>
                <th class="text-right">Bill Amount</th> 
                <th></th>
                <th class="text-right">Bill Amount</th> 
                <th></th>
                <th class="text-right">Bill Amount</th> 
                <th class="text-right">% Bills</th>              
                <th class="text-right">% Amnt</th>              
                <th class="text-right">Bill Amnt</th> 
            </tr> 
            <tr>    
                <th class="text-right"># Bills</th>                    
                <th class="text-right">Others</th>
                <th class="text-right"># Bills</th>                  
                <th class="text-right">Others</th> 
                <th class="text-right"># Bills</th>                  
                <th class="text-right">Others</th> 
                <th class="text-right"># Bills</th>                  
                <th class="text-right">Others</th> 
                <th class="text-right"># Bills</th>                  
                <th class="text-right">Others</th> 
                <th class="text-right"># Bills</th>                  
                <th class="text-right">Others</th> 
                <th class="text-right"># Bills</th>                  
                <th class="text-right">Others</th> 
                <th class="text-right">% Bills</th>                  
                <th class="text-right">% Amnt</th> 
                <th class="text-right">% Bills</th>                  
                <th class="text-right">% Amnt</th>  
                <th class="text-right"># Bills</th>  
                <th></th> 
                <th></th>
            </tr>                     
        </thead>
        <tbody>
            @php
                $totalBillSalesCount = 0;
                $totalBillSalesAmount = 0;
                $totalBillSalesPrevMonthCount = 0;
                $totalBillSalesPrevMonthAmount = 0;
                $totalBillSalesThisMonthCount = 0;
                $totalBillSalesThisMonthAmount = 0;
                $totalPeriodOtherSales = 0;
                $totalArrearsCollectedCount = 0;
                $totalArrearsCollectedAmount = 0;
                $totalCurrentCollectedCount = 0;
                $totalCurrentCollectedAmount = 0;
                $totalCurrentOtherSales = 0;
                $totalDiscoCount = 0;
                $totalDiscoAmount = 0;
                $totalAdjustmentCount = 0;
                $totalAdjustmentAmount = 0;
                $totalCollectionEffAllCount = 0;
                $totalCollectionEffAllAmount = 0;
                $totalCollectionEffExcludedCount = 0;
                $totalCollectionEffExcludedAmount = 0;
                $totalUncollectedCountPercentage = 0;
                $totalUncollectedAmountPercentage = 0;
                $i=0;
            @endphp
            @foreach ($data as $item)
                @if ($item->AreaCode != null)
                    @php
                        $allCollectionCount = IDGenerator::getPercentage(floatval($item->PeriodNoOfBillsCurrentMonthCollection) + floatval($item->PeriodNoOfBillsPrevMonthCollection), $item->PeriodNoOfBillsSales);
                        $allCollectionCount = intval($item->PeriodNoOfBillsSales) ==0 ? 100 : ($allCollectionCount > 100 ? 100 : $allCollectionCount);
                        $allCollectionAmount = IDGenerator::getPercentage(floatval($item->PeriodAmountCurrentMonthCollection) + floatval($item->PeriodAmountPrevMonthCollection), $item->PeriodBillAmountSales);
                        // IF WAY BILL, TOMATIC 100% ANG COLLECTION AMOUNT
                        $allCollectionAmount = intval($item->PeriodNoOfBillsSales) ==0 ? 100 : ($allCollectionAmount > 100 ? 100 : $allCollectionAmount);
                        $excludedCollectionCount = IDGenerator::getPercentage(floatval($item->PeriodNoOfBillsCurrentMonthCollection) + floatval($item->PeriodNoOfBillsPrevMonthCollection) + floatval($item->DiscoCount) + floatval($item->AdjustmentCount), $item->PeriodNoOfBillsSales);
                        $excludedCollectionCount = intval($item->PeriodNoOfBillsSales) ==0 ? 100 : ($excludedCollectionCount > 100 ? 100 : $excludedCollectionCount);
                        $excludedCollectionAmount = IDGenerator::getPercentage(floatval($item->PeriodAmountCurrentMonthCollection) + floatval($item->PeriodAmountPrevMonthCollection) + floatval($item->DiscoAmount) + floatval($item->AdjustmentAmount), $item->PeriodBillAmountSales);
                        // IF WAY BILL, TOMATIC 100% ANG COLLECTION AMOUNT
                        $excludedCollectionAmount = intval($item->PeriodNoOfBillsSales) ==0 ? 100 : ($excludedCollectionAmount > 100 ? 100 : $excludedCollectionAmount);
                        $uncollectedCountPercent = 100 - $excludedCollectionCount;
                        $uncollectedCountPercent = $uncollectedCountPercent < 0 ? 0 : $uncollectedCountPercent;
                        $uncollectedAmountPercent = 100 - $excludedCollectionAmount;
                        // $uncollectedAmountPercent = $uncollectedAmountPercent < 0 ? 0 : $uncollectedAmountPercent;
                    @endphp
                    <tr>
                        <th rowspan="2">{{ $item->AreaCode }}</th>
                        <td class="text-right">{{ $item->PeriodNoOfBillsSales }}</td>
                        <td class="text-right">{{ number_format($item->PeriodBillAmountSales, 2) }}</td>
                        <td class="text-right">{{ $item->PeriodNoOfBillsPrevMonthCollection }}</td>
                        <td class="text-right">{{ number_format($item->PeriodAmountPrevMonthCollection, 2) }}</td>
                        <td class="text-right">{{ $item->PeriodNoOfBillsCurrentMonthCollection }}</td>
                        <td class="text-right">{{ number_format($item->PeriodAmountCurrentMonthCollection, 2) }}</td>
                        <td class="text-right">{{ $item->PeriodNoOfBillsArrearsCollected }}</td>
                        <td class="text-right">{{ number_format($item->PeriodAmountArrearsCollected, 2) }}</td>
                        <td class="text-right">{{ $item->CurrentNoOfBillsSales }}</td>
                        <td class="text-right">{{ number_format($item->CurrentAmountSales, 2) }}</td>
                        <td class="text-right">{{ $item->DiscoCount }}</td>
                        <td class="text-right">{{ number_format($item->DiscoAmount, 2) }}</td>
                        <td class="text-right">{{ $item->AdjustmentCount }}</td>
                        <td class="text-right">{{ number_format($item->AdjustmentAmount, 2) }}</td>
                        <td class="text-right">{{ $allCollectionCount > 100 ? 100 : $allCollectionCount }}%</td>
                        <td class="text-right">{{ $allCollectionAmount }}%</td>
                        <td class="text-right">{{ $excludedCollectionCount > 100 ? 100 : $excludedCollectionCount }}%</td>
                        <td class="text-right">{{ $excludedCollectionAmount }}%</td>
                        <td class="text-right">{{ round($uncollectedCountPercent, 2) }}%</td>
                        @if ($uncollectedCountPercent <= 0)
                            <td class="text-right">0%</td>
                            <td class="text-right text-primary">0</td>
                        @else
                            <td class="text-right">{{ round($uncollectedAmountPercent, 2) }}%</td>
                            <td class="text-right">{{ number_format(floatval($item->PeriodBillAmountSales) - (floatval($item->PeriodAmountCurrentMonthCollection) + floatval($item->PeriodAmountPrevMonthCollection) + floatval($item->AdjustmentAmount) + floatval($item->DiscoAmount)), 2) }}</td>
                        @endif
                    </tr>
                    <tr>     
                        <td></td>   
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right">{{ number_format($item->PeriodOthersSales, 2) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right">{{ number_format($item->CurrentOthersSales, 2) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @if ($uncollectedCountPercent <= 0)
                            <td class="text-right">0</td>
                        @else
                            <td class="text-right">{{ round((floatval($item->PeriodNoOfBillsSales)) - (floatval($item->PeriodNoOfBillsCurrentMonthCollection) + floatval($item->PeriodNoOfBillsPrevMonthCollection) + floatval($item->DiscoCount) + floatval($item->AdjustmentCount))) }}</td>
                        @endif                        
                        <td></td>
                        <td></td>
                    </tr>
                    @php
                        $totalBillSalesCount += intval($item->PeriodNoOfBillsSales);
                        $totalBillSalesAmount += floatval($item->PeriodBillAmountSales);
                        $totalBillSalesPrevMonthCount += intval($item->PeriodNoOfBillsPrevMonthCollection);
                        $totalBillSalesPrevMonthAmount += floatval($item->PeriodAmountPrevMonthCollection);
                        $totalBillSalesThisMonthCount += intval($item->PeriodNoOfBillsCurrentMonthCollection);
                        $totalBillSalesThisMonthAmount += floatval($item->PeriodAmountCurrentMonthCollection);
                        $totalPeriodOtherSales += floatval($item->PeriodOthersSales);  
                        $totalArrearsCollectedCount += intval($item->PeriodNoOfBillsArrearsCollected);
                        $totalArrearsCollectedAmount += floatval($item->PeriodAmountArrearsCollected);  
                        $totalCurrentCollectedCount += intval($item->CurrentNoOfBillsSales);
                        $totalCurrentCollectedAmount += floatval($item->CurrentAmountSales);  
                        $totalCurrentOtherSales += floatval($item->CurrentOthersSales); 
                        $totalDiscoCount += intval($item->DiscoCount);
                        $totalDiscoAmount += floatval($item->DiscoAmount); 
                        $totalAdjustmentCount += intval($item->AdjustmentCount);
                        $totalAdjustmentAmount += floatval($item->AdjustmentAmount); 
                        $totalCollectionEffAllCount += floatval($allCollectionCount);
                        $totalCollectionEffAllAmount += floatval($allCollectionAmount);
                        $totalCollectionEffExcludedCount += floatval($excludedCollectionCount);
                        $totalCollectionEffExcludedAmount += floatval($excludedCollectionAmount);
                        $totalUncollectedCountPercentage += intval($uncollectedCountPercent);
                        $totalUncollectedAmountPercentage += floatval($uncollectedAmountPercent);
                        $i++;
                    @endphp
                @endif
                
            @endforeach
        </tbody>
        <tr>
            <th rowspan="3" class="text-center">TOTAL</th>
            <th class="text-right">{{ $totalBillSalesCount }}</th>
            <th class="text-right">{{ number_format($totalBillSalesAmount, 2) }}</th>
            <th class="text-right">{{ $totalBillSalesPrevMonthCount }}</th>
            <th class="text-right">{{ number_format($totalBillSalesPrevMonthAmount, 2) }}</th>
            <th class="text-right">{{ $totalBillSalesThisMonthCount }}</th>
            <th class="text-right">{{ number_format($totalBillSalesThisMonthAmount, 2) }}</th>
            <th class="text-right">{{ $totalArrearsCollectedCount }}</th>
            <th class="text-right">{{ number_format($totalArrearsCollectedAmount, 2) }}</th>
            <th class="text-right">{{ $totalCurrentCollectedCount }}</th>
            <th class="text-right">{{ number_format($totalCurrentCollectedAmount, 2) }}</th>
            <th class="text-right">{{ $totalDiscoCount }}</th>
            <th class="text-right">{{ number_format($totalDiscoAmount, 2) }}</th>
            <th class="text-right">{{ $totalAdjustmentCount }}</th>
            <th class="text-right">{{ number_format($totalAdjustmentAmount, 2) }}</th>
            <th class="text-right">{{ round(IDGenerator::getAverage($totalCollectionEffAllCount, $i), 2) }}%</th>
            <th class="text-right">{{ round(IDGenerator::getAverage($totalCollectionEffAllAmount, $i), 2) }}%</th>
            <th class="text-right">{{ round(IDGenerator::getAverage($totalCollectionEffExcludedCount, $i), 2) }}%</th>
            <th class="text-right">{{ round(IDGenerator::getAverage($totalCollectionEffExcludedAmount, $i), 2) }}%</th>
            <th class="text-right">{{ round(IDGenerator::getAverage($totalUncollectedCountPercentage, $i), 2) }}%</th>
            <th class="text-right">{{ round(IDGenerator::getAverage($totalUncollectedAmountPercentage, $i), 2) }}%</th>
            <td></td>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th class="text-right">{{ number_format($totalPeriodOtherSales, 2) }}</th>
            <th></th>
            <th></th>
            <th></th>
            <th class="text-right">{{ number_format($totalCurrentOtherSales, 2) }}</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>                            
        </tr>
        <tr>
            <th class="text-right"></th>
            <th class="text-right">{{ number_format($totalBillSalesAmount, 2) }}</th>
            <th class="text-right">{{ $totalBillSalesPrevMonthCount }}</th>
            <th class="text-right">{{ number_format($totalBillSalesPrevMonthAmount, 2) }}</th>
            <th class="text-right">{{ $totalBillSalesThisMonthCount }}</th>
            <th class="text-right">{{ number_format(floatval($totalBillSalesThisMonthAmount) + floatval($totalPeriodOtherSales), 2) }}</th>
            <th class="text-right">{{ $totalArrearsCollectedCount }}</th>
            <th class="text-right">{{ number_format($totalArrearsCollectedAmount, 2) }}</th>
            <th class="text-right">{{ $totalCurrentCollectedCount }}</th>
            <th class="text-right">{{ number_format(floatval($totalCurrentCollectedAmount) + floatval($totalCurrentOtherSales), 2) }}</th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <th class="text-right"></th>
            <td></td>
        </tr>
    </table>

    <br>
    <br>
    <p>Prepared By:</p>
    <br>
    <br>
    <p><u><strong>{{ strtoupper(Auth::user()->name) }}</strong></u></p>
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 1600);
</script>