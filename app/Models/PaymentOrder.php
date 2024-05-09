<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PaymentOrder
 * @package App\Models
 * @version June 1, 2023, 9:25 am PST
 *
 * @property string $ServiceConnectionId
 * @property number $MaterialDeposit
 * @property number $TransformerRentalFees
 * @property number $Apprehension
 * @property number $OverheadExpenses
 * @property number $CIAC
 * @property number $ServiceFee
 * @property number $CustomerDeposit
 * @property number $MeterQuantity
 * @property number $MeterUnitPrice
 * @property number $MeterAmount
 * @property number $TwistedWire6Quantity
 * @property number $TwistedWire6UnitPrice
 * @property number $TwistedWire6Amount
 * @property number $StrandedWire8Quantity
 * @property number $StrandedWire8UnitPrice
 * @property number $StrandedWire8Amount
 * @property number $SaleOfItemsQuantity
 * @property number $SaleOfItemsUnitPrice
 * @property number $SaleOfItemsAmount
 * @property number $CompressionTapQuantity
 * @property number $CompressionTapUnitPrice
 * @property number $CompressionTapAmount
 * @property number $PlyboardQuantity
 * @property number $PlyboardUnitPrice
 * @property number $PlyboardAmount
 * @property number $StainlessBuckleQuantity
 * @property number $StainlessBuckleUnitPrice
 * @property number $StainlessBuckleAmount
 * @property number $ElectricalTapeQuantity
 * @property number $ElectricalTapeUnitPrice
 * @property number $ElectricalTapeAmount
 * @property number $StainlessStrapQuantity
 * @property number $StainlessStrapUnitPrice
 * @property number $StainlessStrapAmount
 * @property number $MetalWoodScrewQuantity
 * @property number $MetalWoodScrewUnitPrice
 * @property number $MetalWoodScrewAmount
 * @property number $TotalSales
 * @property number $Others
 * @property number $LocalFTax
 * @property number $SubTotal
 * @property number $VAT
 * @property number $OthersTotal
 * @property number $OverAllTotal
 * @property string $ORNumber
 * @property string $ORDate
 * @property string $Notes
 */
class PaymentOrder extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_PaymentOrder';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'MaterialDeposit',
        'TransformerRentalFees',
        'Apprehension',
        'OverheadExpenses',
        'CIAC',
        'ServiceFee',
        'CustomerDeposit',
        'TotalSales',
        'Others',
        'LocalFTax',
        'SubTotal',
        'VAT',
        'OthersTotal',
        'OverAllTotal',
        'ORNumber',
        'ORDate',
        'Notes',
        'MaterialTotal',
        'AmountPaid',
        'SaleOfMaterials',
        'InspectionFee',
        'InspectionFeeORNumber',
        'InspectionFeeORDate',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'MaterialDeposit' => 'decimal:2',
        'TransformerRentalFees' => 'decimal:2',
        'Apprehension' => 'decimal:2',
        'OverheadExpenses' => 'decimal:2',
        'CIAC' => 'decimal:2',
        'ServiceFee' => 'decimal:2',
        'CustomerDeposit' => 'decimal:2',
        'TotalSales' => 'decimal:2',
        'Others' => 'decimal:2',
        'LocalFTax' => 'decimal:2',
        'SubTotal' => 'decimal:2',
        'VAT' => 'decimal:2',
        'OthersTotal' => 'decimal:2',
        'OverAllTotal' => 'decimal:2',
        'ORNumber' => 'string',
        'ORDate' => 'date',
        'Notes' => 'string',
        'MaterialTotal' => 'string',
        'AmountPaid' => 'string',
        'SaleOfMaterials' => 'string',
        'InspectionFee' => 'string',
        'InspectionFeeORNumber' => 'string',
        'InspectionFeeORDate' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ServiceConnectionId' => 'required|string|max:255',
        'MaterialDeposit' => 'nullable|numeric',
        'TransformerRentalFees' => 'nullable|numeric',
        'Apprehension' => 'nullable|numeric',
        'OverheadExpenses' => 'nullable|numeric',
        'CIAC' => 'nullable|numeric',
        'ServiceFee' => 'nullable|numeric',
        'CustomerDeposit' => 'nullable|numeric',
        'MeterQuantity' => 'nullable|numeric',
        'MeterUnitPrice' => 'nullable|numeric',
        'MeterAmount' => 'nullable|numeric',
        'TotalSales' => 'nullable|numeric',
        'Others' => 'nullable|numeric',
        'LocalFTax' => 'nullable|numeric',
        'SubTotal' => 'nullable|numeric',
        'VAT' => 'nullable|numeric',
        'OthersTotal' => 'nullable|numeric',
        'OverAllTotal' => 'nullable|numeric',
        'ORNumber' => 'nullable|string|max:255',
        'ORDate' => 'nullable',
        'Notes' => 'nullable|string|max:2000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'MaterialTotal' => 'nullable|string',
        'AmountPaid' => 'nullable|string',
        'SaleOfMaterials' => 'nullable|string',
        'InspectionFee' => 'nullable|string',
        'InspectionFeeORNumber' => 'nullable|string',
        'InspectionFeeORDate' => 'nullable|string',
    ];

    
}
