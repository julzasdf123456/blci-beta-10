<style>
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

        .nod-print {
            page-break-after: always;
        }

        .nod-print:last-child {
            page-break-after: auto;
        }
    
    }     
    
    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    } 
</style>

<link rel="stylesheet" href="{{ URL::asset('adminlte.min.css') }}">

@php
    use App\Models\ServiceAccounts;
    use App\Models\Bills;

    $i=1;
@endphp
@foreach ($discoList as $item)    
    @if ($i%3 == 0)
        <div class="row nod-print">
            <div class="col-sm-12">
                <br>
                <p class="text-center">NOTICE OF DISCONNECTION</p>                
            </div>
            <div class="col-sm-6">
                <span>{{ env("APP_COMPANY") }}</span><br>
                <span>{{ env("APP_COMPANY_ABRV") }}</span><br>
                <span>{{ env("APP_ADDRESS") }}</span><br>
                <br>
            </div>
            <div class="col-sm-6">
                <span>#{{ sprintf('%04u', $i) }}</span><br>
                <span>{{ date('F d, Y h:i:s A') }}</span><br>
                <br>
            </div>
            <div class="col-sm-6">
                <span>Sequence #: {{ $item->SequenceCode }}</span><br>
                <span>{{ $item->OldAccountNo }}</span><br>
                <span><strong>{{ $item->ServiceAccountName }}</strong></span><br>
                <span>{{ ServiceAccounts::getAddress($item) }}</span><br>
                <br>
            </div>
            <div class="col-sm-6">
                <span>Meter Number: {{ $item->MeterNumber }}</span><br>
                <span>Consumer Type: {{ $item->AccountType }}</span><br>
            </div>
            <div class="col-sm-12">
                <p>Dear Consumer,</p>
                <p style="text-indent: 40px;">Kindly settle your account at NONECO's area offices within 48 hours of receiving this notice. 
                    Failure to do so shall lead to the disconnection of your electric service connection. Below are the details of your pending balances.</p>
                <table class="table table-sm table-borderless">
                    <thead>
                        <th>Billing Month</th>
                        <th>Bill Number</th>
                        <th>kWh Used</th>
                        <th class="text-right">Amnt Due</th>
                        <th class="text-right">Surcharge</th>
                        <th class="text-right">Total Amnt. Due</th>
                        <th>Due Date</th>
                        <th class="text-right">Arrears</th>
                    </thead>
                    <tbody>
                        <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                        <td>{{ $item->BillNumber }}</td>
                        <td>{{ $item->KwhUsed }}</td>
                        <td class="text-right">P {{ number_format($item->NetAmount, 2) }}</td>
                        <td class="text-right">P {{ number_format(Bills::getFinalPenalty($item), 2) }}</td>
                        <th class="text-right">P {{ number_format(floatval($item->NetAmount) + floatval(Bills::getFinalPenalty($item)), 2) }}</th>
                        <td>{{ date('F d, Y', strtotime($item->DueDate)) }}</td>
                        <td class="text-right">P {{ number_format($item->Arrears, 2) }}</td>
                    </tbody>
                </table>
                <p class="text-right" style="margin-right: 100px;">THE MANAGEMENT</p>
                {{-- <div style="width: 90%; margin: auto; border-top: #444444 1px dotted;"></div> --}}
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-sm-12">
                <br>
                <p class="text-center">NOTICE OF DISCONNECTION</p>                
            </div>
            <div class="col-sm-6">
                <span>{{ env("APP_COMPANY") }}</span><br>
                <span>{{ env("APP_COMPANY_ABRV") }}</span><br>
                <span>{{ env("APP_ADDRESS") }}</span><br>
                <br>
            </div>
            <div class="col-sm-6">
                <span>#{{ sprintf('%04u', $i) }}</span><br>
                <span>{{ date('F d, Y h:i:s A') }}</span><br>
                <br>
            </div>
            <div class="col-sm-6">
                <span>Sequence #: {{ $item->SequenceCode }}</span><br>
                <span>{{ $item->OldAccountNo }}</span><br>
                <span><strong>{{ $item->ServiceAccountName }}</strong></span><br>
                <span>{{ ServiceAccounts::getAddress($item) }}</span><br>
                <br>
            </div>
            <div class="col-sm-6">
                <span>Meter Number: {{ $item->MeterNumber }}</span><br>
                <span>Consumer Type: {{ $item->AccountType }}</span><br>
            </div>
            <div class="col-sm-12">
                <p>Dear Consumer,</p>
                <p style="text-indent: 40px;">Kindly settle your account at NONECO's area offices within 48 hours of receiving this notice. 
                    Failure to do so shall lead to the disconnection of your electric service connection. Below are the details of your pending balances.</p>
                <table class="table table-sm table-borderless">
                    <thead>
                        <th>Billing Month</th>
                        <th>Bill Number</th>
                        <th>kWh Used</th>
                        <th class="text-right">Amnt Due</th>
                        <th class="text-right">Surcharge</th>
                        <th class="text-right">Total Amnt. Due</th>
                        <th>Due Date</th>
                        <th class="text-right">Arrears</th>
                    </thead>
                    <tbody>
                        <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                        <td>{{ $item->BillNumber }}</td>
                        <td>{{ $item->KwhUsed }}</td>
                        <td class="text-right">P {{ number_format($item->NetAmount, 2) }}</td>
                        <td class="text-right">P {{ number_format(Bills::getFinalPenalty($item), 2) }}</td>
                        <th class="text-right">P {{ number_format(floatval($item->NetAmount) + floatval(Bills::getFinalPenalty($item)), 2) }}</th>
                        <td>{{ date('F d, Y', strtotime($item->DueDate)) }}</td>
                        <td class="text-right">P {{ number_format($item->Arrears, 2) }}</td>
                    </tbody>
                </table>
                <p class="text-right" style="margin-right: 100px;">THE MANAGEMENT</p>
                <div style="width: 90%; margin: auto; border-top: #444444 1px dotted;"></div>
            </div>
        </div>
    @endif
    
    @php
        $i++;
    @endphp
@endforeach   

<script type="text/javascript">   
    window.print();

    window.setTimeout(function(){
        window.location.href = "{{ route('discoNoticeHistories.generate-nod') }}";
    }, 1000);
</script>
