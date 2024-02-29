<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Readings
 * @package App\Models
 * @version January 25, 2022, 11:10 am PST
 *
 * @property string $AccountNumber
 * @property string $ServicePeriod
 * @property string|\Carbon\Carbon $ReadingTimestamp
 * @property string $KwhUsed
 * @property string $DemandKwhUsed
 * @property string $Notes
 * @property string $Latitude
 * @property string $Longitude
 */
class Readings extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_Readings';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'ServicePeriod',
        'ReadingTimestamp',
        'KwhUsed',
        'DemandKwhUsed',
        'Notes',
        'Latitude',
        'Longitude',
        'FieldStatus', // OVERREADING, STUCK-UP, NOT IN USE, NO DISPLAY
        'MeterReader',
        'SolarKwhUsed',
        'Item1',
        'Item2',
        'Item3',
        'PreviousReadingDate',
        'PreviousReading',
        'KwhConsumed',
        'ReadingErrorCode',
        'ReadingErrorRemarks',
        'HouseNumber',
        'ConsumerName',
        'OldAccountNo',
        'AreaCode',
        'GroupCode',
        'MeterNumber',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'ServicePeriod' => 'string',
        'ReadingTimestamp' => 'datetime',
        'KwhUsed' => 'string',
        'DemandKwhUsed' => 'string',
        'Notes' => 'string',
        'Latitude' => 'string',
        'Longitude' => 'string',
        'FieldStatus' => 'string',
        'MeterReader' => 'string',
        'SolarKwhUsed' => 'string',
        'Item1' => 'string',
        'Item2' => 'string',
        'Item3' => 'string',
        'PreviousReadingDate' => 'string',
        'PreviousReading' => 'string',
        'KwhConsumed' => 'string',
        'ReadingErrorCode' => 'string',
        'ReadingErrorRemarks' => 'string',
        'HouseNumber' => 'string',
        'ConsumerName' => 'string',
        'OldAccountNo' => 'string',
        'AreaCode' => 'string',
        'GroupCode' => 'string',
        'MeterNumber' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'AccountNumber' => 'nullable|string|max:255',
        'ServicePeriod' => 'nullable',
        'ReadingTimestamp' => 'nullable',
        'KwhUsed' => 'nullable|string|max:255',
        'DemandKwhUsed' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:3000',
        'Latitude' => 'nullable|string|max:60',
        'Longitude' => 'nullable|string|max:60',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'FieldStatus' => 'nullable|string',
        'MeterReader' => 'nullable|string',
        'SolarKwhUsed' => 'nullable|string',
        'Item1' => 'nullable|string',
        'Item2' => 'nullable|string',
        'Item3' => 'nullable|string',
        'PreviousReadingDate' => 'nullable|string',
        'PreviousReading' => 'nullable|string',
        'KwhConsumed' => 'nullable|string',
        'ReadingErrorCode' => 'nullable|string',
        'ReadingErrorRemarks' => 'nullable|string',
        'HouseNumber' => 'nullable|string',
        'ConsumerName' => 'nullable|string',
        'OldAccountNo' => 'nullable|string',
        'AreaCode' => 'nullable|string',
        'GroupCode' => 'nullable|string',
        'MeterNumber' => 'nullable|string',
    ];

    public static function getDaysBetweenDates($from, $to) {
        $from = strtotime($from); 
        $to = strtotime($to);
        $datediff = $to - $from;

        return round($datediff / (60 * 60 * 24));
    }

    public static function convertToDecimal($item) {
        if ($item == null) {
            return 0;
        } else {
            if (is_numeric($item)) {
                return floatval($item);
            } else {
                return 0;
            }            
        }
    }

    public static function trailSpaceAfter($charCount, $value) {
        if ($value != null) {
            $len = strlen($value);
            $spaceCount = $charCount - $len;

            return $value . Readings::loopSpace($spaceCount);
        } else {
            return Readings::loopSpace($charCount);
        }
        
    }

    public static function trailSpaceBefore($charCount, $value) {
        if ($value != null) {
            $len = strlen($value);
            $spaceCount = $charCount - $len;

            return Readings::loopSpace($spaceCount) . $value;
        } else {
            return Readings::loopSpace($charCount);
        }
        
    }

    public static function loopSpace($spaceCount) {
        $spaces = "";
        for ($i=0; $i<$spaceCount; $i++) {
            $spaces .= " ";
        }
        return $spaces;
    }
}
