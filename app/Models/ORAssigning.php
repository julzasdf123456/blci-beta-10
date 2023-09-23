<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ORAssigning
 * @package App\Models
 * @version March 24, 2022, 1:45 pm PST
 *
 * @property string $ORNumber
 * @property string $UserId
 * @property string $DateAssigned
 * @property string $IsSetManually
 * @property time $TimeAssigned
 * @property string $Office
 */
class ORAssigning extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_ORAssigning';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ORNumber',
        'UserId',
        'DateAssigned',
        'IsSetManually',
        'TimeAssigned',
        'Office'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ORNumber' => 'string',
        'UserId' => 'string',
        'DateAssigned' => 'date',
        'IsSetManually' => 'string',
        'Office' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ORNumber' => 'nullable|string|max:255',
        'UserId' => 'nullable|string|max:255',
        'DateAssigned' => 'nullable',
        'IsSetManually' => 'nullable|string|max:255',
        'TimeAssigned' => 'nullable',
        'Office' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    public static function getORIncrement($inc = 1, $orAssigning) {
        if ($orAssigning != null) {
            return (intval($orAssigning->ORNumber)+$inc);         
        } else {
            return "";
        }
    }
}
