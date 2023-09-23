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

<div  class="content" style="margin: 15px;">
    <div class="row">
        <div class="col-sm-12">
            <img src="{{ URL::asset('imgs/noneco-official-logo.png'); }}" class="float-left" style="height: 90px;" alt="Image"> 
            <p class="text-center" style="font-size: 1.2em;"><strong>{{ env('APP_COMPANY') }} ({{ env('APP_COMPANY_ABRV') }})</strong></p>
            <p class="text-center">{{ env('APP_ADDRESS') }}</p>
            <p class="text-center">{{ env('APP_COMPANY_CONTACT') }}</p>
            <br>
            <div class="divider"></div>
            <br>
        </div>
        <div class="col-sm-12">
            <p class="text-center" style="font-size: 1.2em;"><strong>APPLICATION FOR SERVICE CONNECTION</strong></p>
            <br>
            <p class="text-right">Date: <u><strong>{{ date('F d, Y') }}</strong></u></p>
            <p><strong>I. PERSONAL DATA</strong></p>
        </div>
        {{-- IF MEMBER IS A PERSON --}}
        <div class="col-sm-6">
            <p class="left-indent">Name: <u><strong>{{ $serviceConnection->ServiceAccountName }}</strong></u></p>
            <p class="left-indent">Address: <u><strong>{{ ServiceConnections::getAddress($serviceConnection) }}</strong></u></p>
            <p class="left-indent">Age: _____</p>
            <p class="left-indent">Gender: ___ Male  ___ Female  ___ Not Applicable</p>
            <p class="left-indent">Civil Status: ___ Single  ___ Married  ___ Widow/Widower ___ Not Applicable</p>
            <p class="left-indent">Contact No: <u><strong>{{ $serviceConnection->ContactNumber }}</strong></u></p>
            <p class="left-indent">Date of Membership: ____________________________</p>
        </div> 

        <div class="col-sm-12">
            <br>
            <p><strong>II. PLACE TO BE SERVED</strong></p>
            <p class="left-indent">Building Location: <u><strong>{{ ServiceConnections::getAddress($serviceConnection) }}</strong></u></p>
            <p class="left-indent">Type of Occupancy: <strong>{{ $serviceConnection->TypeOfOccupancy }}</strong></p>
        </div>

        <div class="col-sm-6">
            <p class="left-indent">Owner of Building: ____________________</p> 
            <p class="left-indent">Owner Address: ________________________</p>
            <p class="left-indent">Residence Cert. No: <u><strong>{{ $serviceConnection->ResidenceNumber }}</strong></u></p>
        </div>
        <div class="col-sm-6">
            <p class="left-indent">Duration: <u><strong>{{ $serviceConnection->AccountApplicationType }}</strong></u></p>
            <p class="left-indent">Date Issued: <u><strong>{{ date('F d, Y', strtotime($serviceConnection->DateOfApplication)) }}</strong></u></p>
            <p class="left-indent">Place Issued: <u><strong>{{ $serviceConnection->Office }}</strong></u></p>
        </div>

        <div class="col-sm-12">
            <br>
            <p><strong>III. SERVICE REQUIRED: <u>{{ $serviceConnection->AccountType }} SERVICE CONNECTION</u></strong></p>
            <p><strong>IV. TYPE OF MEMBERSHIP: ___________________</p>
        </div>

        <div class="col-sm-3 offset-sm-8">
            <p class="text-center"><strong>{{ $serviceConnection->ServiceAccountName }}</strong></p>
            <div class="divider"></div>
            <p class="text-center">Applicant's Name Over Signature</p>
        </div>
        
        <div class="col-lg-12">
            <div class="divider"></div>
            <p class="text-center"><strong>PAYMENT DETAILS</strong></p>
            <table class="table table-sm">
                <thead>
                    <th>Description</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Amount</th>
                    <th class="text-right">OR Number</th>
                    <th class="text-right">OR Date</th>
                    <th class="text-right">Teller</th>
                </thead>
                <tbody>
                    @if ($transactionDetails != null)
                        @foreach ($transactionDetails as $item)
                            <tr>
                                <td>{{ $item->Particular }}</td>
                                <td class="text-right">1</td>
                                <td class="text-right">{{ $item->Total != null ? number_format($item->Total, 2) : 0 }}</td>
                                <td class="text-right">{{ $transactionIndex != null ? $transactionIndex->ORNumber : '-' }}</td>
                                <td class="text-right">{{ $transactionIndex != null ? ($transactionIndex->ORDate != null ? date('F d, Y', strtotime($transactionIndex->ORDate)) : '-') : '-' }}</td>
                                <td class="text-right">{{ $transactionIndex != null ? ($transactionIndex->UserId != null ? (Users::find($transactionIndex->UserId) != null ? Users::find($transactionIndex->UserId)->name : '-' ) : '-') : '-' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th>Total</th>
                            <th></th>
                            <th class="text-right">{{ $transactionIndex != null ? number_format($transactionIndex->Total, 2) : '-' }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    @else
                        <tr>
                            <td colspan="6" class="text-center">Payment not found!</td>
                        </tr>
                    @endif
                    
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <p><strong>Processed By:</strong></p>
                <br>
                <p class="text-center"><strong>{{ env('SC_CUSTODIAN') }}</strong></p>
                <div class="divider"></div>
                <p class="text-center">Consumer Welfare Coordinator</p>
            </div>
    
            <div class="col-sm-3">
                <p><strong>Checked By:</strong></p>
                <br>
                <p class="text-center"><strong>{{ env('SC_CS_OFFICER') }}</strong></p>
                <div class="divider"></div>
                <p class="text-center">CS & CD OFFICER</p>
            </div>
    
            <div class="col-sm-3">
                <p><strong>Recommending Approval:</strong></p>
                <br>
                <p class="text-center"><strong>{{ env('SC_CAD_SUPERVISOR') }}</strong></p>
                <div class="divider"></div>
                <p class="text-center">CAD SENIOR SUPERVISOR</p>
            </div>
    
            <div class="col-sm-3">
                <p><strong>Approved:</strong></p>
                <br>
                <p class="text-center"><strong>{{ env('SC_ISD_MANAGER') }}</strong></p>
                <div class="divider"></div>
                <p class="text-center">ISD MANAGER</p>
            </div>
        </div>

        <div class="col-sm-12">
            <br>
            <p class="text-center"><strong>- DO WHAT IS RIGHT, STRIVE TO BE THE BEST -</strong></p>
            <div class="divider"></div>
            <p class="text-center">Coverage Area: EB Magalona, Victorias City, Manapla, Cadiz City, Sagay City, Escalante City, Toboso, Calatrava, and San Carlos City</p>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.print();
    
    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>