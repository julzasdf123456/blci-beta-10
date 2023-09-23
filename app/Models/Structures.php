<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Structures
 * @package App\Models
 * @version September 16, 2021, 8:28 am PST
 *
 * @property string $Type
 * @property string $Data
 */
class Structures extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_Structures';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Type',
        'Data'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Type' => 'string',
        'Data' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'Type' => 'nullable|string|max:255',
        'Data' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public static function groupConAss($type) {
        if ($type == 'ANC_F' | $type == 'GUY_E') {
            return '6';
        } elseif ($type == 'A_DT' | $type == 'DT_R') {
            return '3';
        } elseif ($type == 'PPT') {
            return '2';
        } elseif ($type == 'SEC_J' | $type == 'SEC_M3') {
            return '7';
        } elseif ($type == 'SVC_K') {
            return '8';
        } elseif ($type == 'GND_M2') {
            return '4';
        } elseif ($type == 'MISC') {
            return '5';
        } elseif ($type == null) {
            return '1'; // POLES
        } else {
            return '9'; // Spanning
        }
    }
}
