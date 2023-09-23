<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MeterReaders
 * @package App\Models
 * @version November 25, 2021, 11:39 am PST
 *
 * @property string $MeterReaderCode
 * @property string $UserId
 * @property string $DeviceMacAddress
 * @property string $AreaCodeAssignment
 */
class MeterReaders extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_MeterReaders';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'MeterReaderCode',
        'UserId',
        'DeviceMacAddress',
        'AreaCodeAssignment'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'MeterReaderCode' => 'string',
        'UserId' => 'string',
        'DeviceMacAddress' => 'string',
        'AreaCodeAssignment' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'MeterReaderCode' => 'nullable|string|max:30',
        'UserId' => 'nullable|string|max:50',
        'DeviceMacAddress' => 'nullable|string|max:60',
        'AreaCodeAssignment' => 'nullable|string|max:20',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public static function getMeterAreaCodeScope($areaCode) {
        if ($areaCode == '05') {
            return ['05', '08'];
        } else if ($areaCode == '07') {
            return ['07', '09'];
        } else if ($areaCode == '04') {
            return ['04', '03'];
        } else {
            return [$areaCode];
        }
    }

    public static function getMeterAreaCodeScopeSql($areaCode) {
        if ($areaCode == '05') {
            return "('05', '08')";
        } else if ($areaCode == '07') {
            return "('07', '09')";
        } else if ($areaCode == '04') {
            return "('04', '03')";
        } else {
            return "('" . $areaCode . "')";
        }
    }

    public static function getAreaScopeSql($area) {
        if ($area == 'SAN CARLOS') {
            return "('SAN CARLOS', 'CALATRAVA')";
        } else if ($area == 'ESCALANTE') {
            return "('ESCALANTE', 'TOBOSO')";
        } else if ($area == 'VICTORIAS') {
            return "('VICTORIAS', 'MANAPLA')";
        } else if ($area == 'MAIN') {
            return "('VICTORIAS', 'MANAPLA', 'SAN CARLOS', 'CALATRAVA', 'TOBOSO', 'EB MAGALONA', 'ESCALANTE', 'SAGAY', 'CADIZ')";
        } else {
            return "('" . $area . "')";
        }
    }
}
