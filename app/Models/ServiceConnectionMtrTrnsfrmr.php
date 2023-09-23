<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnectionMtrTrnsfrmr
 * @package App\Models
 * @version August 13, 2021, 1:39 am UTC
 *
 * @property string $ServiceConnectionId
 * @property string $MeterSerialNumber
 * @property string $MeterBrand
 * @property string $MeterSealNumber
 * @property string $MeterKwhStart
 * @property string $MeterEnclosureType
 * @property string $MeterHeight
 * @property string $MeterNotes
 * @property string $DirectRatedCapacity
 * @property string $InstrumentRatedCapacity
 * @property string $InstrumentRatedLineType
 * @property string $CTPhaseA
 * @property string $CTPhaseB
 * @property string $CTPhaseC
 * @property string $PTPhaseA
 * @property string $PTPhaseB
 * @property string $PTPhaseC
 * @property string $BrandPhaseA
 * @property string $BrandPhaseB
 * @property string $BrandPhaseC
 * @property string $SNPhaseA
 * @property string $SNPhaseB
 * @property string $SNPhaseC
 * @property string $SecuritySealPhaseA
 * @property string $SecuritySealPhaseB
 * @property string $SecuritySealPhaseC
 * @property string $Phase
 * @property string $TransformerQuantity
 * @property string $TransformerRating
 * @property string $TransformerOwnershipType
 * @property string $TransformerOwnership
 * @property string $TransformerBrand
 */
class ServiceConnectionMtrTrnsfrmr extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ServiceConnectionMeterAndTransformer';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;



    public $fillable = [
        'id',
        'ServiceConnectionId',
        'MeterSerialNumber',
        'MeterBrand',
        'MeterSealNumber',
        'MeterKwhStart',
        'MeterEnclosureType',
        'MeterHeight',
        'MeterNotes',
        'DirectRatedCapacity',
        'InstrumentRatedCapacity',
        'InstrumentRatedLineType',
        'CTPhaseA',
        'CTPhaseB',
        'CTPhaseC',
        'PTPhaseA',
        'PTPhaseB',
        'PTPhaseC',
        'BrandPhaseA',
        'BrandPhaseB',
        'BrandPhaseC',
        'SNPhaseA',
        'SNPhaseB',
        'SNPhaseC',
        'SecuritySealPhaseA',
        'SecuritySealPhaseB',
        'SecuritySealPhaseC',
        'Phase',
        'TransformerQuantity',
        'TransformerRating',
        'TransformerOwnershipType',
        'TransformerOwnership',
        'TransformerBrand',
        'TypeOfMetering',
        'TransformerNumber'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'MeterSerialNumber' => 'string',
        'MeterBrand' => 'string',
        'MeterSealNumber' => 'string',
        'MeterKwhStart' => 'string',
        'MeterEnclosureType' => 'string',
        'MeterHeight' => 'string',
        'MeterNotes' => 'string',
        'DirectRatedCapacity' => 'string',
        'InstrumentRatedCapacity' => 'string',
        'InstrumentRatedLineType' => 'string',
        'CTPhaseA' => 'string',
        'CTPhaseB' => 'string',
        'CTPhaseC' => 'string',
        'PTPhaseA' => 'string',
        'PTPhaseB' => 'string',
        'PTPhaseC' => 'string',
        'BrandPhaseA' => 'string',
        'BrandPhaseB' => 'string',
        'BrandPhaseC' => 'string',
        'SNPhaseA' => 'string',
        'SNPhaseB' => 'string',
        'SNPhaseC' => 'string',
        'SecuritySealPhaseA' => 'string',
        'SecuritySealPhaseB' => 'string',
        'SecuritySealPhaseC' => 'string',
        'Phase' => 'string',
        'TransformerQuantity' => 'string',
        'TransformerRating' => 'string',
        'TransformerOwnershipType' => 'string',
        'TransformerOwnership' => 'string',
        'TransformerBrand' => 'string',
        'TypeOfMetering' => 'string',
        'TransformerNumber' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|string',
        'ServiceConnectionId' => 'required|string|max:255',
        'MeterSerialNumber' => 'nullable|string|max:150',
        'MeterBrand' => 'nullable|string|max:200',
        'MeterSealNumber' => 'nullable|string|max:200',
        'MeterKwhStart' => 'nullable|string|max:30',
        'MeterEnclosureType' => 'nullable|string|max:150',
        'MeterHeight' => 'nullable|string|max:20',
        'MeterNotes' => 'nullable|string',
        'DirectRatedCapacity' => 'nullable|string|max:50',
        'InstrumentRatedCapacity' => 'nullable|string|max:50',
        'InstrumentRatedLineType' => 'nullable|string|max:50',
        'CTPhaseA' => 'nullable|string|max:50',
        'CTPhaseB' => 'nullable|string|max:50',
        'CTPhaseC' => 'nullable|string|max:50',
        'PTPhaseA' => 'nullable|string|max:50',
        'PTPhaseB' => 'nullable|string|max:50',
        'PTPhaseC' => 'nullable|string|max:50',
        'BrandPhaseA' => 'nullable|string|max:150',
        'BrandPhaseB' => 'nullable|string|max:150',
        'BrandPhaseC' => 'nullable|string|max:150',
        'SNPhaseA' => 'nullable|string|max:250',
        'SNPhaseB' => 'nullable|string|max:250',
        'SNPhaseC' => 'nullable|string|max:250',
        'SecuritySealPhaseA' => 'nullable|string|max:250',
        'SecuritySealPhaseB' => 'nullable|string|max:250',
        'SecuritySealPhaseC' => 'nullable|string|max:250',
        'Phase' => 'nullable|string|max:80',
        'TransformerQuantity' => 'nullable|string|max:20',
        'TransformerRating' => 'nullable|string|max:150',
        'TransformerOwnershipType' => 'nullable|string|max:150',
        'TransformerOwnership' => 'nullable|string|max:150',
        'TransformerBrand' => 'nullable|string|max:150',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'TypeOfMetering' => 'nullable|string',
        'TransformerNumber' => 'string|nullable'
    ];

    
}
