<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BillsOfMaterialsSummary
 * @package App\Models
 * @version September 23, 2021, 1:46 pm PST
 *
 * @property string $ServiceConnectionId
 * @property string $ExcludeTransformerLaborCost
 * @property string $TransformerChangedPrice
 * @property string $MonthDuration
 * @property string $TransformerLaborCostPercentage
 * @property string $MaterialLaborCostPercentage
 * @property string $HandlingCostPercentage
 * @property string $SubTotal
 * @property string $LaborCost
 * @property string $HandlingCost
 * @property string $Total
 * @property string $TotalVAT
 */
class BillsOfMaterialsSummary extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_BillsOfMaterialsSummary';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'ExcludeTransformerLaborCost',
        'TransformerChangedPrice',
        'MonthDuration',
        'TransformerLaborCostPercentage',
        'MaterialLaborCostPercentage',
        'HandlingCostPercentage',
        'SubTotal',
        'LaborCost',
        'HandlingCost',
        'Total',
        'TotalVAT',
        'TransformerLaborCost',
        'MaterialLaborCost',
        'TransformerTotal',
        'IsPaid',
        'ORNumber',
        'ORDate'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'ExcludeTransformerLaborCost' => 'string',
        'TransformerChangedPrice' => 'string',
        'MonthDuration' => 'string',
        'TransformerLaborCostPercentage' => 'string',
        'MaterialLaborCostPercentage' => 'string',
        'HandlingCostPercentage' => 'string',
        'SubTotal' => 'string',
        'LaborCost' => 'string',
        'HandlingCost' => 'string',
        'Total' => 'string',
        'TotalVAT' => 'string',
        'TransformerLaborCost' => 'string',
        'MaterialLaborCost' => 'string',
        'TransformerTotal' => 'string',
        'IsPaid' => 'string',
        'ORNumber' => 'string',
        'ORDate' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ServiceConnectionId' => 'nullable|string|max:255',
        'ExcludeTransformerLaborCost' => 'nullable|string|max:255',
        'TransformerChangedPrice' => 'nullable|string|max:255',
        'MonthDuration' => 'nullable|string|max:10',
        'TransformerLaborCostPercentage' => 'nullable|string|max:10',
        'MaterialLaborCostPercentage' => 'nullable|string|max:10',
        'HandlingCostPercentage' => 'nullable|string|max:10',
        'SubTotal' => 'nullable|string|max:20',
        'LaborCost' => 'nullable|string|max:20',
        'HandlingCost' => 'nullable|string|max:20',
        'Total' => 'nullable|string|max:25',
        'TotalVAT' => 'nullable|string|max:20',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'TransformerLaborCost' => 'string|nullable',
        'MaterialLaborCost' => 'string|nullable',
        'TransformerTotal' => 'string|nullable',
        'IsPaid' => 'string|nullable',
        'ORNumber' => 'string|nullable',
        'ORDate' => 'string|nullable'
    ];

    public static function calculateMaterials($materials) {
        $sum = 0.0;
        foreach($materials as $items) {
            $sum += floatval($items->Cost);
        }
        return $sum;
    }

    public static function calculateTransformer($transformer) {
        $sum = 0.0;

        return $sum;
    }

    public static function getVat() {
        return .12;
    }
}
