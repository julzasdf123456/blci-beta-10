<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Illuminate\Support\Facades\DB;
use App\Models\IDGenerator;
use App\Models\Bills;
use App\Models\PaidBills;
use App\Models\ServiceAccounts;
class Ledgers implements  WithCalculatedFormulas, ToCollection
{
    private $userId, $period;

    public function __construct($userId, $period) {
        $this->userId = $userId;
        $this->period = $period;
    }

    public function collection(Collection $rows) {
        foreach ($rows as $key => $row) {
            if ($key > 1) { // skip header rows
                // get account first
                $acct = ServiceAccounts::where('OldAccountNo', htmlspecialchars_decode(trim($row[0])))->first();

                $billNo = IDGenerator::generateID() . $key;
                // create bills
                Bills::create([
                    'id' => IDGenerator::generateIDandRandString() . $key,
                    'BillNumber' => $billNo,
                    'AccountNumber' => $acct != null ? $acct->id : htmlspecialchars_decode(trim($row[0])),
                    'ServicePeriod' => $this->period,
                    'Multiplier' => $row[4] != null ? (is_numeric(htmlspecialchars_decode(trim($row[4]))) ? (floatval(htmlspecialchars_decode(trim($row[4]))) === 0 ? 1 : floatval(htmlspecialchars_decode(trim($row[4])))) : 1) : 1,
                    'KwhUsed' => $row[5] != null ? htmlspecialchars_decode(trim($row[5])) : 0,
                    'PreviousKwh' => $row[6] != null ? htmlspecialchars_decode(trim($row[6])) : 0,
                    'PresentKwh' => $row[8] != null ? htmlspecialchars_decode(trim($row[8])) : 0,
                    'DemandPresentKwh' => $row[10] != null ? htmlspecialchars_decode(trim($row[10])) : 0,
                    'NetAmount' => $row[11] != null ? htmlspecialchars_decode(trim($row[11])) : 0,
                    'BillingDate' => $row[12] != null ? ((Bills::validateDate(date('Y-m-d', strtotime(htmlspecialchars_decode(trim($row[12])))))) ? date('Y-m-d', strtotime(htmlspecialchars_decode(trim($row[12])))) : '1970-01-01' ) : null,
                    'ServiceDateFrom' => (Bills::validateDate(date('Y-m-15', strtotime($this->period . ' -1 month')))) ? date('Y-m-15', strtotime($this->period . ' -1 month')) : '1970-01-01'  ,
                    'ServiceDateTo' => (Bills::validateDate( date('Y-m-15', strtotime($this->period)))) ?  date('Y-m-15', strtotime($this->period)) : '1970-01-01' ,
                    'DueDate' => $row[13] != null ? ((Bills::validateDate( date('Y-m-d', strtotime(htmlspecialchars_decode(trim($row[13])))))) ?  date('Y-m-d', strtotime(htmlspecialchars_decode(trim($row[13])))) : '1970-01-01')  : null,
                    'MeterNumber' => $row[1] != null ? htmlspecialchars_decode(trim($row[1])) : null,
                    'GenerationSystemCharge' => $row[14] != null ? htmlspecialchars_decode(trim($row[14])) : 0,
                    'TransmissionDeliveryChargeKW' => 0,
                    'TransmissionDeliveryChargeKWH' => $row[16] != null ? htmlspecialchars_decode(trim($row[16])) : 0,
                    'SystemLossCharge' => $row[17] != null ? htmlspecialchars_decode(trim($row[17])) : 0,
                    'DistributionDemandCharge' => $row[19] != null ? htmlspecialchars_decode(trim($row[19])) : 0,
                    'DistributionSystemCharge' => $row[18] != null ? htmlspecialchars_decode(trim($row[18])) : 0,
                    'SupplyRetailCustomerCharge' => $row[20] != null ? htmlspecialchars_decode(trim($row[20])) : 0,
                    'SupplySystemCharge' => 0,
                    'MeteringRetailCustomerCharge' => $row[22] != null ? htmlspecialchars_decode(trim($row[22])) : 0,
                    'MeteringSystemCharge' => $row[21] != null ? htmlspecialchars_decode(trim($row[21])) : 0,
                    'LifelineRate' => $row[24] != null ? htmlspecialchars_decode(trim($row[24])) : 0,
                    'InterClassCrossSubsidyCharge' => $row[25] != null ? htmlspecialchars_decode(trim($row[25])) : 0,
                    'SeniorCitizenSubsidy' => $row[26] != null ? htmlspecialchars_decode(trim($row[26])) : 0,
                    'EnvironmentalCharge' => $row[27] != null ? htmlspecialchars_decode(trim($row[27])) : 0,
                    'StrandedContractCosts' => $row[28] != null ? htmlspecialchars_decode(trim($row[28])) : 0,
                    'NPCStrandedDebt' => $row[29] != null ? htmlspecialchars_decode(trim($row[29])) : 0,
                    'FeedInTariffAllowance' => $row[30] != null ? htmlspecialchars_decode(trim($row[30])) : 0,
                    'MissionaryElectrificationREDCI' => $row[31] != null ? htmlspecialchars_decode(trim($row[31])) : 0,
                    'GenerationVAT' => $row[34] != null ? htmlspecialchars_decode(trim($row[34])) : 0,
                    'TransmissionVAT' => $row[35] != null ? htmlspecialchars_decode(trim($row[35])) : 0,
                    'SystemLossVAT' => $row[36] != null ? htmlspecialchars_decode(trim($row[36])) : 0,
                    'DistributionVAT' => $row[37] != null ? htmlspecialchars_decode(trim($row[37])) : 0,
                    'OthersVAT' => $row[38] != null ? htmlspecialchars_decode(trim($row[38])) : 0,
                    'RealPropertyTax' => 0,
                    'Notes' => 'Imported XLSX',
                    'UserId' => $this->userId,
                    'FranchiseTax' => $row[39] != null ? htmlspecialchars_decode(trim($row[39])) : 0,
                    'BusinessTax' => 0,
                    'PaidAmount' => $row[44] != null ? htmlspecialchars_decode(trim($row[44])) : 0,
                    'Balance' => $row[45] != null ? htmlspecialchars_decode(trim($row[45])) : 0,
                    'ACRM' => $row[15] != null ? htmlspecialchars_decode(trim($row[15])) : 0,
                    'PowerActReduction' => $row[23] != null ? htmlspecialchars_decode(trim($row[23])) : 0,
                    'ACRMVAT' => 0,
                    'MissionaryElectrificationSPUG' => $row[32] != null ? htmlspecialchars_decode(trim($row[32])) : 0,
                    'MissionaryElectrificationSPUGTRUEUP' => $row[33] != null ? htmlspecialchars_decode(trim($row[33])) : 0,
                    'FranchiseTaxOthers' => $row[40] != null ? htmlspecialchars_decode(trim($row[40])) : 0,
                    'AdvancedMaterialDeposit' => $row[41] != null ? htmlspecialchars_decode(trim($row[41])) : 0,
                    'CustomerDeposit' => $row[42] != null ? htmlspecialchars_decode(trim($row[42])) : 0,
                    'TransformerRental' => $row[43] != null ? htmlspecialchars_decode(trim($row[43])) : 0,
                    'SMSSent' => 'Yes',
                    'EmailSent' => 'Yes',
                ]);

                // create paidbills
                $or = htmlspecialchars_decode(trim($row[0]));
                if ($or != null && ($or != 0 | $or != '0')) {
                    PaidBills::create([
                        'id' => IDGenerator::generateIDandRandString() . $key,
                        'BillNumber' => $billNo,
                        'AccountNumber' => $acct != null ? $acct->id : htmlspecialchars_decode(trim($row[0])),
                        'ServicePeriod' => $this->period,
                        'ORNumber' => $row[46] != null ? htmlspecialchars_decode(trim($row[46])) : null,
                        'ORDate' => $row[47] != null ? date('Y-m-d', strtotime(htmlspecialchars_decode(trim($row[47])))) : null,
                        'KwhUsed' => $row[5] != null ? htmlspecialchars_decode(trim($row[5])) : 0,
                        'Teller' => null,
                        'OfficeTransacted' => 'TAGBILARAN',
                        'PostingDate' => $row[47] != null ? date('Y-m-d', strtotime(htmlspecialchars_decode(trim($row[47])))) : null,
                        'PostingTime' => null,
                        'Surcharge' => $row[48] != null ? htmlspecialchars_decode(trim($row[48])) : 0,
                        'NetAmount' => $row[44] != null ? htmlspecialchars_decode(trim($row[44])) : 0,
                        'UserId' => $this->userId,
                        'Notes' => 'Imported XLSX'
                    ]);
                }
            }
        }
    }
}