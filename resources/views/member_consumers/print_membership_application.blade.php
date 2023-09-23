@php
    use App\Models\MemberConsumers;
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
            <img src="{{ URL::asset('imgs/noneco-official-logo.png'); }}" class="float-left" style="height: 80px; margin-top: -20px;" alt="Image"> 
            <p class="text-center" style="font-size: 1.2em;"><strong>APPLICATION FOR MEMBERSHIP</strong></p>
            <br>
            <br>
            <p>Name/Firm:   <u><strong>{{ MemberConsumers::serializeMemberNameFormal($memberConsumers) }}</strong></u></p>
            <p>Spouse/Representative:   <u><strong>{{ MemberConsumers::serializeSpouse($memberConsumers) }}</strong></u></p>
            <p>Address:   <u><strong>{{ MemberConsumers::getAddress($memberConsumers) }}</strong></u></p>
        </div>
        <div class="col-sm-4">            
            <p>Gender:   <u><strong>{{ $memberConsumers->Gender }}</strong></u></p>
        </div>
        <div class="col-sm-4">            
            <p>Civil Status:   <u><strong>{{ $memberConsumers->CivilStatus }}</strong></u></p>
        </div>
        <div class="col-sm-4">            
            <p>Date of Birth:   <u><strong>{{ $memberConsumers->Birthdate != null ? date("F d, Y", strtotime($memberConsumers->Birthdate)) : '-' }}</strong></u></p>
        </div>
        <div class="col-sm-12">
            <br>
            <br>
            <p class="left-indent-more">I HEREBY apply for membership in {{ env('APP_COMPANY') }} and unconditionally agree to:</p>
            <br>
            <p class="left-indent">1. Pay the Membership Fee of FIVE PESOS (₱ 5.00) upon demand;</p>
            <br>
            <p class="left-indent">2. Pay the electric bill promptly in accordance with the Cooperative's policies, approved rate schedule, and other charges;</p>
            <br>
            <p class="left-indent">3. COMPLY with the Articles of Incorporation and By-Laws of {{ env('APP_COMPANY_ABRV') }}, it's policies, rate and regulations, as well as that of the National Electrification Administration;</p>
            <br>
            <p class="left-indent">4. SUBMIT such other membership requirements as may be required from time to time by the Board of Directors of {{ env('APP_COMPANY_ABRV') }};</p>
            <br>
            <p class="left-indent">5. GIVE my full support to {{ env('APP_COMPANY_ABRV') }}, as well as support the goals, purposes and objectives of the National Electrification Program and ADHERE to its concepts and principles;</p>
            <br>
            <p class="left-indent">6. COMPLY with such other obligations which the best interest of the Cooperative may from time to time demand;</p>
            <br>
            <p class="left-indent">7. and, finally, I hereby AGREE to the cancellation by the {{ env('APP_COMPANY_ABRV') }}, Board of Directors of my membership in said Cooperative for non-compliance with any of the foregoing undertaking, or for any act of omission in imical to the interest of {{ env('APP_COMPANY_ABRV') }}, of that which may tend to impede the electrification progam.</p>
            <br><br>
        </div>

        <div class="col-sm-3 offset-sm-8">
            <p class="text-center"><strong>{{ $memberConsumers->MembershipType == MemberConsumers::getJuridicalId() ? MemberConsumers::serializeSpouse($memberConsumers) : MemberConsumers::serializeMemberNameFormal($memberConsumers) }}</strong></p>
            <div class="divider"></div>
            <p class="text-center">Applicant's Name Over Signature</p>
            <br>
            <br>
        </div>

        <div class="col-sm-12">
            <div class="divider" style="background-color: #878787;"></div>
            <br>
            <p>To be signed by Membership Section:</p>
            <br>
        </div>

        <div class="col-sm-6">
            <p><strong>1. Pre-Membership Orientation Seminar (PMOS)</strong></p>
            <p>Place: <u>{{ env('APP_LOCATION') }}</u></p>
            <p>Date: <u>{{ date('F d, Y', strtotime($memberConsumers->DateApplied)) }}</u></p>
        </div>

        <div class="col-sm-6">
            <p><strong>3. Membership Certificate</strong></p>
            <p>No: <u>{{ $memberConsumers->Id }}</u></p>
            <p>Date Issued: <u>{{ date('F d, Y', strtotime($memberConsumers->DateApplied)) }}</u></p>
        </div>

        <div class="col-sm-6">
            <br>
            <p><strong>2. Membership Fee</strong></p>
            <p>OR Number: <u>{{ $transaction != null ? $transaction->ORNumber : '-' }}</u></p>
            <p>OR Date: <u>{{ $transaction != null ? (date('F d, Y', strtotime($transaction->ORDate))) : '-' }}</u></p>
        </div>

        <div class="col-sm-6">
            <br>
            <p><strong>4. Board Approval</strong></p>
            <p>Resolution No: _______________________</p>
            <p>Date Approved: _______________________</p>
        </div>
    </div>       
</div>

<script type="text/javascript">
    window.print();
    
    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>