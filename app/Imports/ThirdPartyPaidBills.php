<?php
namespace App\Imports;

use App\Models\PaidBills;
use App\Models\ServiceAccounts;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\IDGenerator;

class ThirdPartyPaidBills implements WithCalculatedFormulas, ToModel, WithHeadingRow {
    private $userId, $seriesNo;

    public function __construct($userId, $seriesNo) {
        $this->userId = $userId;
        $this->seriesNo = $seriesNo;
    }

    public function model(array $row) {
        $account = ServiceAccounts::where('OldAccountNo', $row['account_number'])
            ->first();
        
        $ordate = intval($row['date_transacted']);

        return new PaidBills([
            'id' => IDGenerator::generateIDandRandString(),
            'BillNumber' => null,
            'AccountNumber' => $account != null ? $account->id : null,
            'ServicePeriod' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($row['billing_month']))->format('Y-m-d'),
            'ORNumber' => $row['ornumber'],
            'ORDate' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ordate)->format('Y-m-d'),
            'DCRNumber' => $row['reference_id'], // THIRD PARTY REFERENCE ID
            'KwhUsed' => null,
            'Teller' => $this->userId,
            'OfficeTransacted' => env('APP_LOCATION'),
            'PostingDate' => null,
            'Surcharge' => null,
            'Form2307TwoPercent' => null,
            'Form2307FivePercent' => null,
            'AdditionalCharges' => null,
            'Deductions' => null,
            'NetAmount' => round(floatval(str_replace(",", "", $row['amount_paid'])), 2),
            'Source' => 'THIRD-PARTY COLLECTION', // THIRD PARTY COLLECTION INDICATOR
            'ObjectSourceId' => $row['third_party_company'], // THIRD PARTY COMPANY
            'UserId' => $this->userId,
            'Status' => 'PENDING POST',
            'FiledBy' => null,
            'ApprovedBy' => null,
            'AuditedBy' => $row['account_number'], // ACCOUNT NUMBER IN THE BILL
            'Notes' => $this->seriesNo, // SERIES REF NO
            'CheckNo' => $row['teller'], // TELLER
            'Bank' => $row['office'], // THIRD PARTY OFFICE
            'CheckExpiration' => null,
            'PaymentUsed' => 'Cash',
        ]);
    }
}