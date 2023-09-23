<?php

namespace App\Repositories;

use App\Models\PaymentOrder;
use App\Repositories\BaseRepository;

/**
 * Class PaymentOrderRepository
 * @package App\Repositories
 * @version June 1, 2023, 9:25 am PST
*/

class PaymentOrderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ServiceConnectionId',
        'MaterialDeposit',
        'TransformerRentalFees',
        'Apprehension',
        'OverheadExpenses',
        'CIAC',
        'ServiceFee',
        'CustomerDeposit',
        'MeterQuantity',
        'MeterUnitPrice',
        'MeterAmount',
        'TwistedWire6Quantity',
        'TwistedWire6UnitPrice',
        'TwistedWire6Amount',
        'StrandedWire8Quantity',
        'StrandedWire8UnitPrice',
        'StrandedWire8Amount',
        'SaleOfItemsQuantity',
        'SaleOfItemsUnitPrice',
        'SaleOfItemsAmount',
        'CompressionTapQuantity',
        'CompressionTapUnitPrice',
        'CompressionTapAmount',
        'PlyboardQuantity',
        'PlyboardUnitPrice',
        'PlyboardAmount',
        'StainlessBuckleQuantity',
        'StainlessBuckleUnitPrice',
        'StainlessBuckleAmount',
        'ElectricalTapeQuantity',
        'ElectricalTapeUnitPrice',
        'ElectricalTapeAmount',
        'StainlessStrapQuantity',
        'StainlessStrapUnitPrice',
        'StainlessStrapAmount',
        'MetalWoodScrewQuantity',
        'MetalWoodScrewUnitPrice',
        'MetalWoodScrewAmount',
        'TotalSales',
        'Others',
        'LocalFTax',
        'SubTotal',
        'VAT',
        'OthersTotal',
        'OverAllTotal',
        'ORNumber',
        'ORDate',
        'Notes'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PaymentOrder::class;
    }
}
