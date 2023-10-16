@php
    use App\Models\ServiceAccounts;
    use App\Models\PaidBills;
    use App\Models\Bills;
    use App\Models\BillMirror;
    use App\Models\PaidBillsDetails;
    use App\Models\User;
    use App\Models\TransactionIndex;
    use App\Models\TransactionDetails;
    use App\Models\TransacionPaymentDetails;
    use App\Models\DCRSummaryTransactions;
    use Illuminate\Support\Facades\DB;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Payment Details - {{ $paymentType }}</h4>
            </div>

            <div class="col-sm-6">
                @if (Auth::user()->hasAnyRole(['Administrator']))
                    <button class="btn btn-danger float-right" title="Cancel This Payment" id="cancel"><i class="fas fa-trash"></i></button>
                @endif                
            </div>
        </div>
    </div>
</section>
<p id="payment-type" style="display: none">{{ $paymentType }}</p>
<div class="content">
    @if ($paymentType == 'BILLS PAYMENT')
        {{-- PAIDBILLS FORM --}}
        @php
            $paidBill = PaidBills::find($id);
            if ($paidBill != null) {
                $account = DB::table('Billing_ServiceAccounts')
                    ->leftJoin('CRM_Towns', 'Billing_ServiceAccounts.Town', '=', 'CRM_Towns.id')
                    ->leftJoin('CRM_Barangays', 'Billing_ServiceAccounts.Barangay', '=', 'CRM_Barangays.id')
                    ->select('Billing_ServiceAccounts.id',
                            'Billing_ServiceAccounts.ServiceAccountName',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'Billing_ServiceAccounts.AccountCount',
                            'Billing_ServiceAccounts.Purok',
                            'Billing_ServiceAccounts.AccountType',
                            'Billing_ServiceAccounts.AccountStatus',
                            'Billing_ServiceAccounts.AreaCode',
                            'Billing_ServiceAccounts.Organization',
                            'Billing_ServiceAccounts.GroupCode',
                            'Billing_ServiceAccounts.SeniorCitizen',
                            'Billing_ServiceAccounts.Evat5Percent',
                            'Billing_ServiceAccounts.Ewt2Percent',
                            'Billing_ServiceAccounts.OldAccountNo',
                            'CRM_Towns.Town',
                            'CRM_Barangays.Barangay')
                    ->where('Billing_ServiceAccounts.id', $paidBill->AccountNumber)
                    ->first();

                $bill = Bills::where('AccountNumber', $paidBill->AccountNumber)
                    ->where('ServicePeriod', $paidBill->ServicePeriod)
                    ->first();

                $billMirror = BillMirror::where('AccountNumber', $paidBill->AccountNumber)
                    ->where('ServicePeriod', $paidBill->ServicePeriod)
                    ->orderByDesc('updated_at')
                    ->first();

                $paidBillDetails = PaidBillsDetails::where('AccountNumber', $paidBill->AccountNumber)
                    ->where('ServicePeriod', $paidBill->ServicePeriod)->orderBy('PaymentUsed')->get();

                $paidBills = DB::table('Cashier_PaidBills')
                    ->leftJoin('users', 'Cashier_PaidBills.Teller', '=', 'users.id')
                    ->where('AccountNumber', $paidBill->AccountNumber)
                    ->where('ServicePeriod', $paidBill->ServicePeriod)
                    ->whereRaw("(Status IS NULL OR Status='Application')")
                    ->select('Cashier_PaidBills.*', 'users.name')
                    ->orderBy('created_at')
                    ->get();

                // $user = User::find($paidBill->UserId);

                // $dcr = DB::table('Cashier_DCRSummaryTransactions')
                //     ->leftJoin('Cashier_AccountGLCodes', 'Cashier_DCRSummaryTransactions.GLCode', '=', 'Cashier_AccountGLCodes.AccountCode')
                //     ->whereRaw("Teller='" . $paidBill->Teller . "' AND ORNumber='" . $paidBill->ORNumber . "' AND AccountNumber='" . $paidBill->AccountNumber . "' 
                //         AND (TRY_CAST(Amount AS DECIMAL(15,2)) != 0) AND Cashier_DCRSummaryTransactions.NEACode='" . $bill->ServicePeriod . "'")
                //     ->where(function ($query) {
                //         $query->where('Cashier_DCRSummaryTransactions.ReportDestination', 'COLLECTION')
                //             ->orWhere('Cashier_DCRSummaryTransactions.ReportDestination', 'BOTH');
                //     })  
                //     ->select('GLCode',
                //         'Amount',
                //         'Cashier_AccountGLCodes.Notes'
                //     )
                //     ->orderBy('GLCode')
                //     ->get();
            } else {
                $account = null;
                $paidBillDetails = null;
                $user = null;
                $dcr = null;
            }
            
        @endphp
        <div class="px-5">
            @if ($paidBill->Status == 'CANCELLED')
                <h4 class="badge bg-danger">CANCELLED</h4>

                @push('page_scripts')
                    <script>
                        $('#cancel').hide()
                    </script>
                @endpush
            @endif
            <div class="row">
                <div class="col-lg-5">
                    <p class="text-muted">Payor</p>
                    <span><strong><a href="{{ $account != null ? (route('serviceAccounts.show', [$account->id])) : '' }}">{{ $account != null ? $account->ServiceAccountName : '-' }}</a></strong></span><br>
                    <span>{{ $account != null ? $account->OldAccountNo : '-' }}</span><br>
                    <span>{{ $account != null ? ServiceAccounts::getAddress($account) : '' }}</span><br>
                    <span>{{ $account != null ? $account->AccountType : '-' }}</span><br>
                </div>

                <div class="col-lg-4">
                    {{-- <span class="text-muted">OR Number</span><br>
                    <span>
                        <strong>{{ $paidBill->ORNumber }}</strong>
                        @if ($paidBill->Source == 'THIRD-PARTY COLLECTION')
                            <span class="badge bg-info">THIRD PARTY COLLECTION</span>
                        @endif 
                    </span><br>
                    <span class="text-muted">OR Date</span><br>
                    <span><strong>{{ date('F d, Y', strtotime($paidBill->ORDate)) }}</strong></span><br>
                    <span class="text-muted">Teller</span><br>
                    @if ($paidBill->Source == 'THIRD-PARTY COLLECTION')
                        <span>
                            <strong>{{ $paidBill->CheckNo }}</strong>
                            <span class="badge bg-info">{{ $paidBill->ObjectSourceId }}</span>
                        </span><br>
                    @else
                        <span><strong>{{ $user != null ? $user->name : '-' }}</strong></span><br>
                    @endif                     --}}
                </div>

                <div class="col-lg-3">
                    <span class="text-muted float-right">Total Amount Paid</span><br>
                    <h2 class="text-success text-right"><strong>₱ {{ number_format($bill->PaidAmount, 2) }}</strong></h2>
                    @if ($bill->Balance > 0)
                        <p class="text-danger text-right"><strong>Balance - ₱ {{ number_format($bill->Balance, 2) }}</strong></p>
                    @else
                        <p class="text-success text-right">Balance - ₱ {{ number_format($bill->Balance, 2) }}</p>
                    @endif
                    
                    <a href="{{ $bill != null ? (route('bills.show', [$bill->id])) : '' }}" class="btn btn-xs btn-success float-right">View Bill</a>
                </div>

                {{-- Paidbill Details table --}}
                <div class="col-lg-12">
                    <br>
                    <span class="text-muted">Payment Details</span><br>
                    <table class="table table-hover table-sm">
                        <thead>
                            <th>Transaction ID</th>
                            <th class="text-right">Amount Paid</th>
                            <th>OR Number</th>
                            <th>Payment Date</th>
                            <th>Teller</th>
                            <th>Office</th>
                            <th>Transaction Time</th>
                        </thead>
                        <tbody>
                            @if ($paidBills != null)
                                @foreach ($paidBills as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td class="text-right text-success"><strong>₱ {{ number_format($item->NetAmount, 2) }}</strong></td>
                                        <td>{{ $item->ORNumber }}</td>
                                        <td>{{ $item->ORDate != null ? date('M d, Y', strtotime($item->ORDate)) : '' }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->OfficeTransacted }}</td>
                                        <td>{{ $item->created_at != null ? date('M d, Y h:i A', strtotime($item->created_at)) : '' }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-12">
                    <div class="card shadow-none">
                        <div class="card-header">
                            <span class="card-title"><i class="fas fa-info ico-tab"></i>Charges Breakdown</span>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <th class="text-center">Charges</th>
                                    <th class="text-right">Bill Amount</th>
                                    <th class="text-right">Paid Amount</th>
                                    <th class="text-right">Balances</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Generation System</strong></td>
                                        <td class="text-right">{{ floatval($bill->GenerationSystemCharge) > 0 ? $bill->GenerationSystemCharge : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->GenerationSystemCharge) > 0 ? $billMirror->GenerationSystemCharge : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->GenerationSystemCharge) - floatval($billMirror->GenerationSystemCharge)) > 0 ? number_format(floatval($bill->GenerationSystemCharge) - floatval($billMirror->GenerationSystemCharge), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>GRAM/ICERA/ACRM</strong></td>
                                        <td class="text-right">{{ floatval($bill->ACRM) > 0 ? $bill->ACRM : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->ACRM) > 0 ? $billMirror->ACRM : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->ACRM) - floatval($billMirror->ACRM)) > 0 ? number_format(floatval($bill->ACRM) - floatval($billMirror->ACRM), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Transmission Delivery Charge</strong></td>
                                        <td class="text-right">{{ floatval($bill->TransmissionDeliveryChargeKWH) > 0 ? $bill->TransmissionDeliveryChargeKWH : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->TransmissionDeliveryChargeKWH) > 0 ? $billMirror->TransmissionDeliveryChargeKWH : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->TransmissionDeliveryChargeKWH) - floatval($billMirror->TransmissionDeliveryChargeKWH)) > 0 ? number_format(floatval($bill->TransmissionDeliveryChargeKWH) - floatval($billMirror->TransmissionDeliveryChargeKWH), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>System Loss Charge</strong></td>
                                        <td class="text-right">{{ floatval($bill->SystemLossCharge) > 0 ? $bill->SystemLossCharge : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->SystemLossCharge) > 0 ? $billMirror->SystemLossCharge : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->SystemLossCharge) - floatval($billMirror->SystemLossCharge)) > 0 ? number_format(floatval($bill->SystemLossCharge) - floatval($billMirror->SystemLossCharge), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Distribution Demand Charge</strong></td>
                                        <td class="text-right">{{ floatval($bill->DistributionDemandCharge) > 0 ? $bill->DistributionDemandCharge : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->DistributionDemandCharge) > 0 ? $billMirror->DistributionDemandCharge : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->DistributionDemandCharge) - floatval($billMirror->DistributionDemandCharge)) > 0 ? number_format(floatval($bill->DistributionDemandCharge) - floatval($billMirror->DistributionDemandCharge), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Distribution System Charge</strong></td>
                                        <td class="text-right">{{ floatval($bill->DistributionSystemCharge) > 0 ? $bill->DistributionSystemCharge : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->DistributionSystemCharge) > 0 ? $billMirror->DistributionSystemCharge : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->DistributionSystemCharge) - floatval($billMirror->DistributionSystemCharge)) > 0 ? number_format(floatval($bill->DistributionSystemCharge) - floatval($billMirror->DistributionSystemCharge), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Supply Retail Customer Charge</strong></td>
                                        <td class="text-right">{{ floatval($bill->SupplyRetailCustomerCharge) > 0 ? $bill->SupplyRetailCustomerCharge : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->SupplyRetailCustomerCharge) > 0 ? $billMirror->SupplyRetailCustomerCharge : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->SupplyRetailCustomerCharge) - floatval($billMirror->SupplyRetailCustomerCharge)) > 0 ? number_format(floatval($bill->SupplyRetailCustomerCharge) - floatval($billMirror->SupplyRetailCustomerCharge), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Metering Retail Customer Charge</strong></td>
                                        <td class="text-right">{{ floatval($bill->MeteringRetailCustomerCharge) > 0 ? $bill->MeteringRetailCustomerCharge : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->MeteringRetailCustomerCharge) > 0 ? $billMirror->MeteringRetailCustomerCharge : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->MeteringRetailCustomerCharge) - floatval($billMirror->MeteringRetailCustomerCharge)) > 0 ? number_format(floatval($bill->MeteringRetailCustomerCharge) - floatval($billMirror->MeteringRetailCustomerCharge), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Metering System Charge</strong></td>
                                        <td class="text-right">{{ floatval($bill->MeteringSystemCharge) > 0 ? $bill->MeteringSystemCharge : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->MeteringSystemCharge) > 0 ? $billMirror->MeteringSystemCharge : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->MeteringSystemCharge) - floatval($billMirror->MeteringSystemCharge)) > 0 ? number_format(floatval($bill->MeteringSystemCharge) - floatval($billMirror->MeteringSystemCharge), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lifeline Rate (Discount/Subsidy)</strong></td>
                                        <td class="text-right">{{ floatval($bill->LifelineRate) > 0 ? $bill->LifelineRate : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->LifelineRate) > 0 ? $billMirror->LifelineRate : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->LifelineRate) - floatval($billMirror->LifelineRate)) > 0 ? number_format(floatval($bill->LifelineRate) - floatval($billMirror->LifelineRate), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Inter-Class Cross Subsidy Charge</strong></td>
                                        <td class="text-right">{{ floatval($bill->InterClassCrossSubsidyCharge) > 0 ? $bill->InterClassCrossSubsidyCharge : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->InterClassCrossSubsidyCharge) > 0 ? $billMirror->InterClassCrossSubsidyCharge : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->InterClassCrossSubsidyCharge) - floatval($billMirror->InterClassCrossSubsidyCharge)) > 0 ? number_format(floatval($bill->InterClassCrossSubsidyCharge) - floatval($billMirror->InterClassCrossSubsidyCharge), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Power Act Reduction</strong></td>
                                        <td class="text-right">{{ floatval($bill->PowerActReduction) > 0 ? $bill->PowerActReduction : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->PowerActReduction) > 0 ? $billMirror->PowerActReduction : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->PowerActReduction) - floatval($billMirror->PowerActReduction)) > 0 ? number_format(floatval($bill->PowerActReduction) - floatval($billMirror->PowerActReduction), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Senior Citizen Subsidy</strong></td>
                                        <td class="text-right">{{ floatval($bill->SeniorCitizenSubsidy) > 0 ? $bill->SeniorCitizenSubsidy : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->SeniorCitizenSubsidy) > 0 ? $billMirror->SeniorCitizenSubsidy : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->SeniorCitizenSubsidy) - floatval($billMirror->SeniorCitizenSubsidy)) > 0 ? number_format(floatval($bill->SeniorCitizenSubsidy) - floatval($billMirror->SeniorCitizenSubsidy), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Environmental Charge</strong></td>
                                        <td class="text-right">{{ floatval($bill->EnvironmentalCharge) > 0 ? $bill->EnvironmentalCharge : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->EnvironmentalCharge) > 0 ? $billMirror->EnvironmentalCharge : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->EnvironmentalCharge) - floatval($billMirror->EnvironmentalCharge)) > 0 ? number_format(floatval($bill->EnvironmentalCharge) - floatval($billMirror->EnvironmentalCharge), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stranded Contract Costs</strong></td>
                                        <td class="text-right">{{ floatval($bill->StrandedContractCosts) > 0 ? $bill->StrandedContractCosts : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->StrandedContractCosts) > 0 ? $billMirror->StrandedContractCosts : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->StrandedContractCosts) - floatval($billMirror->StrandedContractCosts)) > 0 ? number_format(floatval($bill->StrandedContractCosts) - floatval($billMirror->StrandedContractCosts), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>NPC Stranded Debt</strong></td>
                                        <td class="text-right">{{ floatval($bill->NPCStrandedDebt) > 0 ? $bill->NPCStrandedDebt : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->NPCStrandedDebt) > 0 ? $billMirror->NPCStrandedDebt : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->NPCStrandedDebt) - floatval($billMirror->NPCStrandedDebt)) > 0 ? number_format(floatval($bill->NPCStrandedDebt) - floatval($billMirror->NPCStrandedDebt), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Feed-in Tariff Allowance (FIT-All)</strong></td>
                                        <td class="text-right">{{ floatval($bill->FeedInTariffAllowance) > 0 ? $bill->FeedInTariffAllowance : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->FeedInTariffAllowance) > 0 ? $billMirror->FeedInTariffAllowance : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->FeedInTariffAllowance) - floatval($billMirror->FeedInTariffAllowance)) > 0 ? number_format(floatval($bill->FeedInTariffAllowance) - floatval($billMirror->FeedInTariffAllowance), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Missionary Electrification - RED</strong></td>
                                        <td class="text-right">{{ floatval($bill->MissionaryElectrificationREDCI) > 0 ? $bill->MissionaryElectrificationREDCI : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->MissionaryElectrificationREDCI) > 0 ? $billMirror->MissionaryElectrificationREDCI : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->MissionaryElectrificationREDCI) - floatval($billMirror->MissionaryElectrificationREDCI)) > 0 ? number_format(floatval($bill->MissionaryElectrificationREDCI) - floatval($billMirror->MissionaryElectrificationREDCI), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Missionary Electrification - SPUG</strong></td>
                                        <td class="text-right">{{ floatval($bill->MissionaryElectrificationSPUG) > 0 ? $bill->MissionaryElectrificationSPUG : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->MissionaryElectrificationSPUG) > 0 ? $billMirror->MissionaryElectrificationSPUG : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->MissionaryElectrificationSPUG) - floatval($billMirror->MissionaryElectrificationSPUG)) > 0 ? number_format(floatval($bill->MissionaryElectrificationSPUG) - floatval($billMirror->MissionaryElectrificationSPUG), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Missionary Electrification - SPUG TRUE UP</strong></td>
                                        <td class="text-right">{{ floatval($bill->MissionaryElectrificationSPUGTRUEUP) > 0 ? $bill->MissionaryElectrificationSPUGTRUEUP : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->MissionaryElectrificationSPUGTRUEUP) > 0 ? $billMirror->MissionaryElectrificationSPUGTRUEUP : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->MissionaryElectrificationSPUGTRUEUP) - floatval($billMirror->MissionaryElectrificationSPUGTRUEUP)) > 0 ? number_format(floatval($bill->MissionaryElectrificationSPUGTRUEUP) - floatval($billMirror->MissionaryElectrificationSPUGTRUEUP), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Generation VAT</strong></td>
                                        <td class="text-right">{{ floatval($bill->GenerationSystemVAT) > 0 ? $bill->GenerationSystemVAT : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->GenerationSystemVAT) > 0 ? $billMirror->GenerationSystemVAT : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->GenerationSystemVAT) - floatval($billMirror->GenerationSystemVAT)) > 0 ? number_format(floatval($bill->GenerationSystemVAT) - floatval($billMirror->GenerationSystemVAT), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>GRAM/ICERA/ACRM VAT</strong></td>
                                        <td class="text-right">{{ floatval($bill->ACRMVAT) > 0 ? $bill->ACRMVAT : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->ACRMVAT) > 0 ? $billMirror->ACRMVAT : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->ACRMVAT) - floatval($billMirror->ACRMVAT)) > 0 ? number_format(floatval($bill->ACRMVAT) - floatval($billMirror->ACRMVAT), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Transmission VAT</strong></td>
                                        <td class="text-right">{{ floatval($bill->TransmissionVAT) > 0 ? $bill->TransmissionVAT : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->TransmissionVAT) > 0 ? $billMirror->TransmissionVAT : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->TransmissionVAT) - floatval($billMirror->TransmissionVAT)) > 0 ? number_format(floatval($bill->TransmissionVAT) - floatval($billMirror->TransmissionVAT), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>System Loss VAT</strong></td>
                                        <td class="text-right">{{ floatval($bill->SystemLossVAT) > 0 ? $bill->SystemLossVAT : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->SystemLossVAT) > 0 ? $billMirror->SystemLossVAT : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->SystemLossVAT) - floatval($billMirror->SystemLossVAT)) > 0 ? number_format(floatval($bill->SystemLossVAT) - floatval($billMirror->SystemLossVAT), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Distribution VAT</strong></td>
                                        <td class="text-right">{{ floatval($bill->DistributionVAT) > 0 ? $bill->DistributionVAT : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->DistributionVAT) > 0 ? $billMirror->DistributionVAT : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->DistributionVAT) - floatval($billMirror->DistributionVAT)) > 0 ? number_format(floatval($bill->DistributionVAT) - floatval($billMirror->DistributionVAT), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Others VAT</strong></td>
                                        <td class="text-right">{{ floatval($bill->OthersVAT) > 0 ? $bill->OthersVAT : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->OthersVAT) > 0 ? $billMirror->OthersVAT : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->OthersVAT) - floatval($billMirror->OthersVAT)) > 0 ? number_format(floatval($bill->OthersVAT) - floatval($billMirror->OthersVAT), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Franchise Tax</strong></td>
                                        <td class="text-right">{{ floatval($bill->FranchiseTax) > 0 ? $bill->FranchiseTax : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->FranchiseTax) > 0 ? $billMirror->FranchiseTax : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->FranchiseTax) - floatval($billMirror->FranchiseTax)) > 0 ? number_format(floatval($bill->FranchiseTax) - floatval($billMirror->FranchiseTax), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Franchise Tax (Others)</strong></td>
                                        <td class="text-right">{{ floatval($bill->FranchiseTaxOthers) > 0 ? $bill->FranchiseTaxOthers : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->FranchiseTaxOthers) > 0 ? $billMirror->FranchiseTaxOthers : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->FranchiseTaxOthers) - floatval($billMirror->FranchiseTaxOthers)) > 0 ? number_format(floatval($bill->FranchiseTaxOthers) - floatval($billMirror->FranchiseTaxOthers), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Business Tax</strong></td>
                                        <td class="text-right">{{ floatval($bill->BusinessTax) > 0 ? $bill->BusinessTax : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->BusinessTax) > 0 ? $billMirror->BusinessTax : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->BusinessTax) - floatval($billMirror->BusinessTax)) > 0 ? number_format(floatval($bill->BusinessTax) - floatval($billMirror->BusinessTax), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Real Property Tax (RPT)</strong></td>
                                        <td class="text-right">{{ floatval($bill->RealPropertyTax) > 0 ? $bill->RealPropertyTax : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->RealPropertyTax) > 0 ? $billMirror->RealPropertyTax : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->RealPropertyTax) - floatval($billMirror->RealPropertyTax)) > 0 ? number_format(floatval($bill->RealPropertyTax) - floatval($billMirror->RealPropertyTax), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Termed Payments</strong></td>
                                        <td class="text-right">{{ floatval($bill->TermedPayments) > 0 ? $bill->TermedPayments : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->TermedPayments) > 0 ? $billMirror->TermedPayments : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->TermedPayments) - floatval($billMirror->TermedPayments)) > 0 ? number_format(floatval($bill->TermedPayments) - floatval($billMirror->TermedPayments), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Customer Deposit</strong></td>
                                        <td class="text-right">{{ floatval($bill->CustomerDeposit) > 0 ? $bill->CustomerDeposit : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->CustomerDeposit) > 0 ? $billMirror->CustomerDeposit : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->CustomerDeposit) - floatval($billMirror->CustomerDeposit)) > 0 ? number_format(floatval($bill->CustomerDeposit) - floatval($billMirror->CustomerDeposit), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Material Deposit</strong></td>
                                        <td class="text-right">{{ floatval($bill->AdvancedMaterialDeposit) > 0 ? $bill->AdvancedMaterialDeposit : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->AdvancedMaterialDeposit) > 0 ? $billMirror->AdvancedMaterialDeposit : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->AdvancedMaterialDeposit) - floatval($billMirror->AdvancedMaterialDeposit)) > 0 ? number_format(floatval($bill->AdvancedMaterialDeposit) - floatval($billMirror->AdvancedMaterialDeposit), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>2307 (5%)</strong></td>
                                        <td class="text-right">{{ floatval($bill->Evat5Percent) > 0 ? $bill->Evat5Percent : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->Evat5Percent) > 0 ? $billMirror->Evat5Percent : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->Evat5Percent) - floatval($billMirror->Evat5Percent)) > 0 ? number_format(floatval($bill->Evat5Percent) - floatval($billMirror->Evat5Percent), 2) : '' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>2307 (2%)</strong></td>
                                        <td class="text-right">{{ floatval($bill->Evat2Percent) > 0 ? $bill->Evat2Percent : '' }}</td>
                                        <td class="text-right text-success"><strong>{{ $billMirror != null && floatval($billMirror->Evat2Percent) > 0 ? $billMirror->Evat2Percent : '' }}</strong></td>
                                        <td class="text-right text-danger"><strong>{{ $billMirror != null && (floatval($bill->Evat2Percent) - floatval($billMirror->Evat2Percent)) > 0 ? number_format(floatval($bill->Evat2Percent) - floatval($billMirror->Evat2Percent), 2) : '' }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- DCR Summary --}}
                {{-- <div class="col-lg-12">
                    <div class="card shadow-none">
                        <div class="card-header">
                            <span class="card-title">DCR Summary</span>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <th>GL Code</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                </thead>
                                <tbody>
                                    @foreach ($dcr as $item)
                                        <tr>
                                            <td>{{ $item->GLCode }}</td>
                                            <td>{{ $item->Notes }}</td>
                                            <td>{{ is_numeric($item->Amount) ? number_format($item->Amount, 2) : $item->Amount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    @else
        {{-- OTHER TRANSACTIONS FORM --}}
        @php
            $transactionIndex = TransactionIndex::find($id);

            if ($transactionIndex != null) {
                $user = User::find($transactionIndex->UserId);
                $transactionDetails = TransactionDetails::where('TransactionIndexId', $id)->get();
                $transactionLogs = TransacionPaymentDetails::where('ORNumber', $transactionIndex->ORNumber)->get();
            } else {
                $user = null;
                $transactionDetails = null;
                $transactionLogs = null;
            }
        @endphp
        <div class="px-5">
            @if ($transactionIndex->Status == 'CANCELLED')
                <h4 class="badge bg-danger">CANCELLED</h4>

                @push('page_scripts')
                    <script>
                        $('#cancel').hide()
                    </script>
                @endpush
            @endif
            <div class="row">
                <div class="col-lg-5">
                    <p class="text-muted">Invoice</p>
                    <span><strong>{{ $transactionIndex->PaymentTitle != null ? $transactionIndex->PaymentTitle : 'No Detail Provided' }}</strong></span><br>
                    <span class="text-muted">Payment Source: </span>{{ $transactionIndex->Source }}<br>
                    <span class="text-muted">Transaction ID: </span>{{ $transactionIndex->TransactionNumber }}<br>
                    <span class="text-muted">Payor: </span>{{ $transactionIndex->PayeeName != null ? $transactionIndex->PayeeName : '-' }}<br>
                </div>

                <div class="col-lg-4">
                    <span class="text-muted">OR Number</span><br>
                    <span><strong>{{ $transactionIndex->ORNumber }}</strong></span><br>
                    <span class="text-muted">OR Date</span><br>
                    <span><strong>{{ date('F d, Y', strtotime($transactionIndex->ORDate)) }}</strong></span><br>
                    <span class="text-muted">Teller</span><br>                    
                    <span><strong>{{ $user != null ? $user->name : '-' }}</strong></span><br>
                </div>

                <div class="col-lg-3">
                    <span class="text-muted float-right">Total Amount Paid</span><br>
                    <h2 class="text-primary text-right"><strong>₱ {{ number_format($transactionIndex->Total, 2) }}</strong></h2>
                    {{-- <a href="{{ $paidBill->ObjectSourceId != null ? (route('bills.show', [$paidBill->ObjectSourceId])) : '' }}" class="btn btn-xs btn-primary float-right">View Bill</a> --}}
                </div>

                {{-- PAYABLES --}}
                <div class="col-lg-5">
                    <br>
                    <span class="text-muted">Payables</span>
                    <table class="table table-hover table-sm">
                        <thead>
                            <th>Particulars</th>
                            <th>Account Code</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">VAT</th>
                            <th class="text-right">Total</th>
                        </thead>
                        <tbody>
                            @if ($transactionDetails != null)
                                @foreach ($transactionDetails as $item)
                                    <tr>
                                        <td>{{ $item->Particular }}</td>
                                        <td>{{ $item->AccountCode }}</td>
                                        <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                                        <td class="text-right">{{ number_format($item->VAT, 2) }}</td>
                                        <td class="text-right">{{ number_format($item->Total, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- PAYMENT DETAILS --}}
                <div class="col-lg-7">
                    <br>
                    <span class="text-muted">Payment Details</span>
                    <table class="table table-hover">
                        <thead>
                            <th>Transaction ID</th>
                            <th>Amount Paid</th>
                            <th>Payment Used</th>
                            <th>Check No.</th>
                            <th>Bank</th>
                        </thead>
                        <tbody>
                            @if ($transactionLogs != null)
                                @foreach ($transactionLogs as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td class="text-right">₱ {{ number_format($item->Amount, 2) }}</td>
                                        <td class="{{ $item->PaymentUsed == 'Cash' ? 'text-info' : 'text-success' }}"><strong>{{ $item->PaymentUsed }}</strong></td>
                                        <td>{{ $item->CheckNo }}</td>
                                        <td>{{ $item->Bank }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#cancel').on('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{{ route('paidBills.cancel-or-admin') }}",
                            type : 'GET',
                            data : {
                                PaymentType : $('#payment-type').text(),
                                id : "{{ $id }}"
                            },
                            success : function(res) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'OR Cancelled',
                                    showConfirmButton: false,
                                    timer: 1500
                                })

                                location.reload()
                            },
                            error : function(err) {
                                Swal.fire({
                                    title : 'Error cancelling OR',
                                    icon : 'error'
                                })
                            }
                        })
                    }
                })
                
            })
        })
    </script>
@endpush