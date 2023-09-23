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

.left-indent-p {
    text-indent: 80px;
    text-align: justify;
    text-justify: inter-word;
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
            <div class="divider"></div>
        </div>
        <div class="col-sm-12">
            <p class="text-center" style="font-size: 1.2em;"><strong>ELECTRIC SERVICE CONTRACT</strong></p>
            <br>
            <p>Name/Firm:   <u><strong>{{ $serviceConnection->ServiceAccountName }}</strong></u></p>
            <p>Spouse/Representative:   <u><strong>{{ $serviceConnection->OrganizationAccountNumber }}</strong></u></p>
            <p>Location of Installation:   <u><strong>{{ ServiceConnections::getAddress($serviceConnection) }}</strong></u></p>
        </div>
        <div class="col-sm-4">
            <p>Service Required:   <u><strong>{{ $serviceConnection->AccountType }} SERVICE CONNECTION</strong></u></p>
        </div>
        <div class="col-sm-4">
            <p>Type of Occupancy:   <u><strong>{{ $serviceConnection->TypeOfOccupancy }}</strong></u></p>
        </div>
        <div class="col-sm-12">
            <p>Owner of Building or Establishment, if Renting:   _______________________________________</p>
            <p>Address of Building Owner:   _______________________________________</p>
            <br>
            <p><strong>KNOWN BY ALL MEN THESE REPRESENTS:</strong></p>
            <br>
            <p class="left-indent-p">This <strong>CONTRACT</strong>, made and entered into this <u><strong>{{ date('d') }}</strong></u> day of <u><strong>{{ date('F, y') }}</strong></u> in the City/Municipality of Manapla 
                by and between the {{ env('APP_COMPANY') }}, an electric cooperative duly organized, registered, 
                existing and operating by virtue of and in accordance with Philippines Laws, with principal place of business at Tortosa, 
                Manapla, Negros Occidental hereinafter known as {{ env('APP_COMPANY_ABRV') }} represented in this act by its ISD Manager, <u><strong>{{ env('SC_ISD_MANAGER') }}</strong></u> and
                <u><strong>{{ $serviceConnection->ServiceAccountName }}</strong></u>
                hereinafter known as the consumer; and as owner of the premises subject matter of this act.</p>
            <p class="text-center"><strong>WITNESSETH</strong></p>
            <p class="left-indent-p"><strong>POWER SUPPLY.</strong> {{ env('APP_COMPANY_ABRV') }} shall furnish electric service to the CONSUMER's electrical installation 
                located at <u><strong>{{ ServiceConnections::getAddress($serviceConnection) }}</strong></u>, provided said electrical installation 
                is covered by all required permits, inspected and found to be in accordance with its policies, rules and regulations, 
                as well as that of the National Electrification Administration, and the CONSUMER hereby agrees to strictly adhere to and comply 
                with said policies, rules and regulations.</p>

            <p class="left-indent-p"><strong>CONTINUITY OF SERVICE ADEQUACY OF POWER SUPPLY.</strong> {{ env('APP_COMPANY_ABRV') }} shall not be held liable for power 
                interruptions or inadequate supply of electric or for injury to persons or damage to property resulting therefrom. {{ env('APP_COMPANY_ABRV') }}, however, 
                shall exercise reasonable diligence to provide reliable and adequate supply of electricity. {{ env('APP_COMPANY_ABRV') }} reserves the right to temporarily 
                suspend electric service whenever repairs of any of facilities are necessary or for any purpose for the best interest of {{ env('APP_COMPANY_ABRV') }}.</p>

            <p class="left-indent-p"><strong>RIGHT OF WAY.</strong> The CONSUMER shall secure for {{ env('APP_COMPANY_ABRV') }} the necessary right-of-way for the supply of electricity 
                to the CONSUMER's premises.</p>

            <p class="left-indent-p"><strong>OWNERSHIP OF PREMISES.</strong> In case the premises served is not owned by the CONSUMER, both the 
                CONSUMER and the owner of the building shall be signatories of this contract and both shall be jointly and severally liable 
                for the electric bills and other charges, as well as for damages. For all intents and purpose of this contract, the obligations 
                of the CONSUMER likewise extend to the owner of the premises.</p>

            <p class="left-indent-p"><strong>TECHNICAL REQUIREMENTS IF INSTALLATION.</strong>The electrical installation in the CONSUMER's premises 
                shall be designed, constructed, installed and operated according to {{ env('APP_COMPANY_ABRV') }}'s requirements.</p>

            <p class="left-indent-p"><strong>SAFETY INSTALLATION.</strong>The CONSUMER shall maintain the electrical installation within his/her/its 
                premises in proper condition at all times to insure utmost safety. {{ env('APP_COMPANY_ABRV') }}'s responsibility extends only up to the kilowatt-hour meter, 
                beyond which responsibility for maintenance and safety rests with the COPNSUMER at the latter expense.</p>

            <p class="left-indent-p"><strong>ENTRANCE TO PREMISES.</strong>The CONSUMER hereby expressly authorizes entrance to the premises by any 
                representative/s of {{ env('APP_COMPANY_ABRV') }} for inspection of the electrical installation, meter reading or meter testing, making repairs o to remove, 
                detach, disconnect and confiscate any device paraphernalia, accessories and/or instrument used for any prohibited act or for any 
                other purpose consistent with {{ env('APP_COMPANY_ABRV') }}'s policies and regulations. For this purpose, {{ env('APP_COMPANY_ABRV') }} or its representatives are authorized 
                to use the necessary and reasonable force for the purpose of exercising its rights as afore stated to include the right to break 
                open any door or gate for the purpose or purpose aforementioned.</p>

            <p class="left-indent-p"><strong>OWNERSHIP OF MATERIALS.</strong>All meters, wires, equipment and other materials installed at {{ env('APP_COMPANY_ABRV') }} 
                expense in the CONSUMER's premises belong to and shall remain the property of {{ env('APP_COMPANY_ABRV') }} and may be removed by {{ env('APP_COMPANY_ABRV') }} at any time upon 
                disconnection of service.</p>

            <p class="left-indent-p"><strong>PILFERAGE.</strong>The CONSUMER shall not tamper with {{ env('APP_COMPANY_ABRV') }}'s installed kilowatt hour or demand 
                meter and metering accessories nor use tampered kilowatt hour or demand, install jumpers or any other device for pilferage of 
                electricity. The CONSUMER shall likewise be held liable for any breakage or damage to {{ env('APP_COMPANY_ABRV') }}'s properties installed in the premises.</p>

            <p class="left-indent-p">The CONSUMER shall be held responsible and liable for tampering, interfering with or breaking of seal of meter 
                or other equipment of the COOPERATIVE installed on the CONSUMER premises and/or any of the unlawful acts defined and enumerated 
                under Sections 2 and 3 or Republic Act No. 7832.</p>

            <p class="left-indent-p">The Consumer shall be held responsible and liable for any of the existence and presence of the circumstances 
                defined and enumerate under Section 4 of Republic Act No. 7832.</p>

            <p class="left-indent-p">The parties here to have agreed as they hereby agreed that this contract shall be deemed subject to the provisions 
                of the Republic Act No. 7832 otherwise known as the Anti-Electricity and Electric Transmission lines / Materials Pilferage Act of 
                1994 and that all provisions under Republic Act No. 7832 shall be considered written into this Electric Service Contract.</p>

            <p class="left-indent-p"><strong>USAGE OF ELECTRICITY. </strong>The CONSUMER shall use the electric power/energy supplied solely for the 
                purpose stated in his/her/its Application of Electric Service hereto attached as ANNEX "A" and made integral part thereof. 
                The CONSUMER shall not, under any circumstances, resell the electric power/energy purchased now allow any sub-connection without the 
                written approval of {{ env('APP_COMPANY_ABRV') }}.</p>

            <p class="left-indent-p"><strong>INCREASE IN LOAD. </strong>The CONSUMER shall inform {{ env('APP_COMPANY_ABRV') }} in writing of any increase in his/her/its 
                connected load, especially if such increase affects the CONSUMER's rate classification or cause the electric meter to be overloaded 
                or otherwise damaged. In case of failure to inform {{ env('APP_COMPANY_ABRV') }}, the CONSUMER shall not make any alteration or modification in electrical 
                installation without the written authority of {{ env('APP_COMPANY_ABRV') }}.</p>

            <p class="left-indent-p"><strong>ADHERENCE TO {{ env('APP_COMPANY_ABRV') }} RULE. </strong>The CONSUMER hereby agrees to abide by, adhere to and strictly comply 
                with {{ env('APP_COMPANY_ABRV') }}'s rules, regulations and policies, such as payment of electric bills and other charges, apprehension and penalties for 
                prohibited acts, inspection of electrical installation, disconnection and reconnection of service, etc.</p>

            <p class="left-indent-p"><strong>DISCONNECTION OF SERVICE. </strong>The {{ env('APP_COMPANY_ABRV') }} serves the right to immediately discontinue electric service 
                for any violation or failure to comply with the required specifications for electrical Installations, any unsafe practice by the consumer, 
                failure to pay {{ env('APP_COMPANY_ABRV') }}'s claim for which the consumer Is held liable by {{ env('APP_COMPANY_ABRV') }}, or for any other act prejudicial to {{ env('APP_COMPANY_ABRV') }}.</p>

            <p class="left-indent-p" style="display: block; page-break-before: always;"><strong>WAIVER OF RIGHTS. </strong>Disconnection of electric service does not constitute waiver of {{ env('APP_COMPANY_ABRV') }}'s right 
                to Institute such other legal remedies as may be proper nor any delay in the enforcement of such rights constitute waiver of said rights.</p>
        </div>
        
        <div class="col-sm-12">
            <p class="left-indent-p"><strong>FIXING OF RATES. </strong>The CONSUMER hereby acknowledges the authority of {{ env('APP_COMPANY_ABRV') }} and the National 
                Electrification Administration to fix and implement electric rates and impose other charges, as well as to make revisions or modifications 
                thereof from time to time as circumstances may warrant. If the CONSUMER does not agree with the electric rates of {{ env('APP_COMPANY_ABRV') }} and other charges, 
                the CONSUMER shall have the option to discontinue the electric service provided all outstanding accounts are paid In full.</p>

            <p class="left-indent-p"><strong>PAYMENT OF BILLS. </strong>The CONSUMER hereby agrees to pay electric bills in accordance with {{ env('APP_COMPANY_ABRV') }}'s rate 
                schedule, as well as such amount of fuel cost adjustment and other charges which {{ env('APP_COMPANY_ABRV') }} may from time to time Impose for recovery of fuel 
                cost Increase or for other purposes.</p>

            <p class="left-indent-p"><strong>ADJUSTMENT OF BILL. </strong>Whenever {{ env('APP_COMPANY_ABRV') }} has reasonable ground to believe that the CONSUMER Is consuming 
                more electricity than what is actually registered in the meter, or when it is established that the meter Installed is defective. 
                {{ env('APP_COMPANY_ABRV') }} may adjust the bills accordingly, and if the CONSUMER refuses to pay the adjusted bill, {{ env('APP_COMPANY_ABRV') }} may discontinue the service.</p>

            <p class="left-indent-p"><strong>ADVANCES FOR BILLS AND MATERIALS. </strong>{{ env('APP_COMPANY_ABRV') }} reserves the right to require the CONSUMER to pay in 
                advance the cost of transformers and other electrical materials necessary for the electrical installation In accordance with the {{ env('APP_COMPANY_ABRV') }}'s policy, 
                rules, and regulations as well as to require the CONSUMER to advance to {{ env('APP_COMPANY_ABRV') }} some amount of electric bills under such terms and conditions as 
                {{ env('APP_COMPANY_ABRV') }} may deem proper.</p>

            <p class="left-indent-p"><strong>ASSIGNMENTS OF RIGHTS. </strong>Neither this CONTRACT nor any interest therein shall be transferred or assigned 
                by the CONSUMER without written consent of {{ env('APP_COMPANY_ABRV') }}.</p>

            <p class="left-indent-p"><strong>SUCCESSION AND TERMINATION OF CONTRACT. </strong>This CONTRACT shall be binding upon the CONSUMER's heirs and 
                successors-in-interest and shall remain in force unless terminated and heretofore provided. Either party, however, may terminate this contract 
                by giving the other party advance written notice of not less than thirty (30) days in the case of {{ env('APP_COMPANY_ABRV') }} and not less than forty-eight (48) hours 
                in the case of the CONSUMER. As long as the CONSUMER has not given such written notice to {{ env('APP_COMPANY_ABRV') }}, the CONSUMER shall remain liable for all electric 
                bills and other charges for the electric service furnished by {{ env('APP_COMPANY_ABRV') }}.</p>

            <p class="left-indent-p"><strong>LEGAL ACTIONS. </strong>The CONSUMER agrees to pay attorney's fees equivalent to 25% of {{ env('APP_COMPANY_ABRV') }}'s claim plus other 
                legal expenses should the CONSUMER's act necessitates court action. Any legal action arising from this contract shall be filed with the proper 
                court of the City/Municipality where the electric service connection Involved Is Installed.</p>

            <p class="left-indent-p"><strong>IN WITNESS HEREOF</strong>, the parties have signed this Instrument in the City / Municipality of <u><strong>{{ $serviceConnection->Office }}</strong></u> 
                Negros Occidental this <u>{{ date('d') }}</u> day <u>{{ date('F Y') }}</u> <strong>{{ strtoupper(env('APP_COMPANY')) }}</strong>.</p>
            <br>
        </div>

        <div class="col-sm-4">
            <p>By:</p>
            <br>
            <p class="text-center"><strong>{{ env('SC_ISD_MANAGER') }}</strong></p>
            <div class="divider"></div>
            <p class="text-center">ISD Manager</p>
        </div>

        <div class="col-sm-4 offset-sm-4">
            <br>
            <br>
            <p class="text-center"><strong>{{ $serviceConnection->ServiceAccountName }}</strong></p>
            <div class="divider"></div>
            <p class="text-center">Sign Over Printed Name</p>
        </div>

        <div class="col-sm-4 offset-sm-4">
            <br>
            <br>
            <p class="text-center">_____________________________</p>
            <div class="divider"></div>
            <p class="text-center">Owner of Premises</p>
        </div>
        
        <div class="col-sm-12">
            <br>
            <p class="text-center"><strong>SIGNED IN THE PRESENCE OF:</strong></p>
        </div>

        <div class="col-sm-4 offset-sm-1">
            <br>
            <p class="text-center">___________________________________________</p>
        </div>

        <div class="col-sm-4 offset-sm-2">
            <br>
            <p class="text-center">___________________________________________</p>
        </div>

        <div class="col-sm-12">
            <br>
            <p class="text-center"><strong>ACKNOWLEDGEMENT</strong></p>
            <p>REPUBLIC OF THE PHILIPPINES <span style="margin-left: 74px;"></span> }</p>
            <p>PROVINCE OF NEGROS OCCIDENTAL <span style="margin-left: 30px;"></span> } S.S.</p>
            <p>MUNICIPALITY OF MANAPLA <span style="margin-left: 85px;"></span> }</p>
            <p>x..................................................................................x</p>
            <br>
            <p class="left-indent-p">Before me personally appeared the following person with their respective residence certificate as follows:</p>
            <br>
        </div>

        <div class="col-sm-3">
            <p class="text-center">NAME</p>
            <p class="text-center"><u><strong>{{ $serviceConnection->ServiceAccountName }}/strong></u></p>
            <p class="text-center"><u><strong>{{ env('SC_ISD_MANAGER') }}</strong></u></p>
        </div>

        <div class="col-sm-3">
            <p class="text-center">RESIDENCE CERTIFICATE</p>
            <p class="text-center"><u><strong>{{ $serviceConnection->ResidenceNumber }}</strong></u></p>
        </div>

        <div class="col-sm-3">
            <p class="text-center">PLACE AND DATE OF ISSUE</p>
            <p class="text-center"><u><strong>{{ $serviceConnection->Office }}</strong></u></p>
        </div>

        <div class="col-sm-3">
            <br>
            <p class="text-center">_____________________</p>
        </div>

        <div class="col-sm-12">
            <p>All known to me and to me known to be the same persons who executed and signed the foregoing Instrument, and they acknowledge 
                before me that the same is their free and voluntary act and deed.</p>

            <p class="left-indent-p">
                <u><strong>{{ $serviceConnection->ServiceAccountName }}</strong></u>
                 further acknowledge before me that he executed and signed the foregoing contract In his capacity as ISD Manager of {{ env('APP_COMPANY_ABRV') }}, while
                <u><strong>{{ env('SC_ISD_MANAGER') }}</strong></u> acknowledges that he/she executed and signed the same as the owner of the premises where the 
                electrical installation subject matter of this contract is located.
            </p>

            <p>I HEREBY FURTHER CERTIFY that this instrument consists of two (2) pages Including this page signed by the parties and their witnesses on each 
                and every page thereof.</p>

            <p>IN WITNESS WHEREOFF, I have hereunto signed my name and affixed my notarial seal in the Municipality of Manapla this day of <u>{{ date('d F, Y') }}</u> </p>
                <br>
            <p>Doc. No. _____________ until Dec. 31, 20____</p>
            <p>Page No. _____________ PTR No. ____________</p>
            <p>Book No. _____________ Issued At __________</p>
            <p>Series of ______________ On ________________</p>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.print();
    
    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>