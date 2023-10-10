<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="bill-mirrors-table">
            <thead>
            <tr>
                <th>Billnumber</th>
                <th>Accountnumber</th>
                <th>Serviceperiod</th>
                <th>Additionalcharges</th>
                <th>Deductions</th>
                <th>Netamount</th>
                <th>Billingdate</th>
                <th>Servicedatefrom</th>
                <th>Servicedateto</th>
                <th>Duedate</th>
                <th>Generationsystemcharge</th>
                <th>Transmissiondeliverychargekw</th>
                <th>Transmissiondeliverychargekwh</th>
                <th>Systemlosscharge</th>
                <th>Distributiondemandcharge</th>
                <th>Distributionsystemcharge</th>
                <th>Supplyretailcustomercharge</th>
                <th>Supplysystemcharge</th>
                <th>Meteringretailcustomercharge</th>
                <th>Meteringsystemcharge</th>
                <th>Rfsc</th>
                <th>Lifelinerate</th>
                <th>Interclasscrosssubsidycharge</th>
                <th>Pparefund</th>
                <th>Seniorcitizensubsidy</th>
                <th>Missionaryelectrificationcharge</th>
                <th>Environmentalcharge</th>
                <th>Strandedcontractcosts</th>
                <th>Npcstrandeddebt</th>
                <th>Feedintariffallowance</th>
                <th>Missionaryelectrificationredci</th>
                <th>Generationvat</th>
                <th>Transmissionvat</th>
                <th>Systemlossvat</th>
                <th>Distributionvat</th>
                <th>Realpropertytax</th>
                <th>Notes</th>
                <th>Userid</th>
                <th>Othergenerationrateadjustment</th>
                <th>Othertransmissioncostadjustmentkw</th>
                <th>Othertransmissioncostadjustmentkwh</th>
                <th>Othersystemlosscostadjustment</th>
                <th>Otherlifelineratecostadjustment</th>
                <th>Seniorcitizendiscountandsubsidyadjustment</th>
                <th>Franchisetax</th>
                <th>Businesstax</th>
                <th>Adjustmenttype</th>
                <th>Form2307Amount</th>
                <th>Deducteddeposit</th>
                <th>Excessdeposit</th>
                <th>Isunlockedforpayment</th>
                <th>Unlockedby</th>
                <th>Evat2Percent</th>
                <th>Evat5Percent</th>
                <th>Adjustmentnumber</th>
                <th>Adjustedby</th>
                <th>Dateadjusted</th>
                <th>Forcancellation</th>
                <th>Cancelrequestedby</th>
                <th>Cancelapprovedby</th>
                <th>Katasngvat</th>
                <th>Solarimportpresent</th>
                <th>Solarimportprevious</th>
                <th>Solarexportpresent</th>
                <th>Solarexportprevious</th>
                <th>Solarimportkwh</th>
                <th>Solarexportkwh</th>
                <th>Generationchargesolarexport</th>
                <th>Solarresidualcredit</th>
                <th>Solardemandchargekw</th>
                <th>Solardemandchargekwh</th>
                <th>Solarretailcustomercharge</th>
                <th>Solarsupplysystemcharge</th>
                <th>Solarmeteringretailcharge</th>
                <th>Solarmeteringsystemcharge</th>
                <th>Item1</th>
                <th>Item2</th>
                <th>Item3</th>
                <th>Item4</th>
                <th>Item5</th>
                <th>Paidamount</th>
                <th>Balance</th>
                <th>Acrm</th>
                <th>Poweractreduction</th>
                <th>Acrmvat</th>
                <th>Missionaryelectrificationspug</th>
                <th>Missionaryelectrificationspugtrueup</th>
                <th>Franchisetaxothers</th>
                <th>Othersvat</th>
                <th>Advancedmaterialdeposit</th>
                <th>Customerdeposit</th>
                <th>Transformerrental</th>
                <th>Adjustmentrequestedby</th>
                <th>Adjustmentapprovedby</th>
                <th>Adjustmentstatus</th>
                <th>Dateadjustmentrequested</th>
                <th>Termedpayments</th>
                <th>Ornumber</th>
                <th>Ordate</th>
                <th>Batchnumber</th>
                <th>Teller</th>
                <th>Paidbillid</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($billMirrors as $billMirror)
                <tr>
                    <td>{{ $billMirror->BillNumber }}</td>
                    <td>{{ $billMirror->AccountNumber }}</td>
                    <td>{{ $billMirror->ServicePeriod }}</td>
                    <td>{{ $billMirror->AdditionalCharges }}</td>
                    <td>{{ $billMirror->Deductions }}</td>
                    <td>{{ $billMirror->NetAmount }}</td>
                    <td>{{ $billMirror->BillingDate }}</td>
                    <td>{{ $billMirror->ServiceDateFrom }}</td>
                    <td>{{ $billMirror->ServiceDateTo }}</td>
                    <td>{{ $billMirror->DueDate }}</td>
                    <td>{{ $billMirror->GenerationSystemCharge }}</td>
                    <td>{{ $billMirror->TransmissionDeliveryChargeKW }}</td>
                    <td>{{ $billMirror->TransmissionDeliveryChargeKWH }}</td>
                    <td>{{ $billMirror->SystemLossCharge }}</td>
                    <td>{{ $billMirror->DistributionDemandCharge }}</td>
                    <td>{{ $billMirror->DistributionSystemCharge }}</td>
                    <td>{{ $billMirror->SupplyRetailCustomerCharge }}</td>
                    <td>{{ $billMirror->SupplySystemCharge }}</td>
                    <td>{{ $billMirror->MeteringRetailCustomerCharge }}</td>
                    <td>{{ $billMirror->MeteringSystemCharge }}</td>
                    <td>{{ $billMirror->RFSC }}</td>
                    <td>{{ $billMirror->LifelineRate }}</td>
                    <td>{{ $billMirror->InterClassCrossSubsidyCharge }}</td>
                    <td>{{ $billMirror->PPARefund }}</td>
                    <td>{{ $billMirror->SeniorCitizenSubsidy }}</td>
                    <td>{{ $billMirror->MissionaryElectrificationCharge }}</td>
                    <td>{{ $billMirror->EnvironmentalCharge }}</td>
                    <td>{{ $billMirror->StrandedContractCosts }}</td>
                    <td>{{ $billMirror->NPCStrandedDebt }}</td>
                    <td>{{ $billMirror->FeedInTariffAllowance }}</td>
                    <td>{{ $billMirror->MissionaryElectrificationREDCI }}</td>
                    <td>{{ $billMirror->GenerationVAT }}</td>
                    <td>{{ $billMirror->TransmissionVAT }}</td>
                    <td>{{ $billMirror->SystemLossVAT }}</td>
                    <td>{{ $billMirror->DistributionVAT }}</td>
                    <td>{{ $billMirror->RealPropertyTax }}</td>
                    <td>{{ $billMirror->Notes }}</td>
                    <td>{{ $billMirror->UserId }}</td>
                    <td>{{ $billMirror->OtherGenerationRateAdjustment }}</td>
                    <td>{{ $billMirror->OtherTransmissionCostAdjustmentKW }}</td>
                    <td>{{ $billMirror->OtherTransmissionCostAdjustmentKWH }}</td>
                    <td>{{ $billMirror->OtherSystemLossCostAdjustment }}</td>
                    <td>{{ $billMirror->OtherLifelineRateCostAdjustment }}</td>
                    <td>{{ $billMirror->SeniorCitizenDiscountAndSubsidyAdjustment }}</td>
                    <td>{{ $billMirror->FranchiseTax }}</td>
                    <td>{{ $billMirror->BusinessTax }}</td>
                    <td>{{ $billMirror->AdjustmentType }}</td>
                    <td>{{ $billMirror->Form2307Amount }}</td>
                    <td>{{ $billMirror->DeductedDeposit }}</td>
                    <td>{{ $billMirror->ExcessDeposit }}</td>
                    <td>{{ $billMirror->IsUnlockedForPayment }}</td>
                    <td>{{ $billMirror->UnlockedBy }}</td>
                    <td>{{ $billMirror->Evat2Percent }}</td>
                    <td>{{ $billMirror->Evat5Percent }}</td>
                    <td>{{ $billMirror->AdjustmentNumber }}</td>
                    <td>{{ $billMirror->AdjustedBy }}</td>
                    <td>{{ $billMirror->DateAdjusted }}</td>
                    <td>{{ $billMirror->ForCancellation }}</td>
                    <td>{{ $billMirror->CancelRequestedBy }}</td>
                    <td>{{ $billMirror->CancelApprovedBy }}</td>
                    <td>{{ $billMirror->KatasNgVat }}</td>
                    <td>{{ $billMirror->SolarImportPresent }}</td>
                    <td>{{ $billMirror->SolarImportPrevious }}</td>
                    <td>{{ $billMirror->SolarExportPresent }}</td>
                    <td>{{ $billMirror->SolarExportPrevious }}</td>
                    <td>{{ $billMirror->SolarImportKwh }}</td>
                    <td>{{ $billMirror->SolarExportKwh }}</td>
                    <td>{{ $billMirror->GenerationChargeSolarExport }}</td>
                    <td>{{ $billMirror->SolarResidualCredit }}</td>
                    <td>{{ $billMirror->SolarDemandChargeKW }}</td>
                    <td>{{ $billMirror->SolarDemandChargeKWH }}</td>
                    <td>{{ $billMirror->SolarRetailCustomerCharge }}</td>
                    <td>{{ $billMirror->SolarSupplySystemCharge }}</td>
                    <td>{{ $billMirror->SolarMeteringRetailCharge }}</td>
                    <td>{{ $billMirror->SolarMeteringSystemCharge }}</td>
                    <td>{{ $billMirror->Item1 }}</td>
                    <td>{{ $billMirror->Item2 }}</td>
                    <td>{{ $billMirror->Item3 }}</td>
                    <td>{{ $billMirror->Item4 }}</td>
                    <td>{{ $billMirror->Item5 }}</td>
                    <td>{{ $billMirror->PaidAmount }}</td>
                    <td>{{ $billMirror->Balance }}</td>
                    <td>{{ $billMirror->ACRM }}</td>
                    <td>{{ $billMirror->PowerActReduction }}</td>
                    <td>{{ $billMirror->ACRMVAT }}</td>
                    <td>{{ $billMirror->MissionaryElectrificationSPUG }}</td>
                    <td>{{ $billMirror->MissionaryElectrificationSPUGTRUEUP }}</td>
                    <td>{{ $billMirror->FranchiseTaxOthers }}</td>
                    <td>{{ $billMirror->OthersVAT }}</td>
                    <td>{{ $billMirror->AdvancedMaterialDeposit }}</td>
                    <td>{{ $billMirror->CustomerDeposit }}</td>
                    <td>{{ $billMirror->TransformerRental }}</td>
                    <td>{{ $billMirror->AdjustmentRequestedBy }}</td>
                    <td>{{ $billMirror->AdjustmentApprovedBy }}</td>
                    <td>{{ $billMirror->AdjustmentStatus }}</td>
                    <td>{{ $billMirror->DateAdjustmentRequested }}</td>
                    <td>{{ $billMirror->TermedPayments }}</td>
                    <td>{{ $billMirror->ORNumber }}</td>
                    <td>{{ $billMirror->ORDate }}</td>
                    <td>{{ $billMirror->BatchNumber }}</td>
                    <td>{{ $billMirror->Teller }}</td>
                    <td>{{ $billMirror->PaidBillId }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['billMirrors.destroy', $billMirror->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('billMirrors.show', [$billMirror->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('billMirrors.edit', [$billMirror->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $billMirrors])
        </div>
    </div>
</div>
