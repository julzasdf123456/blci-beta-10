<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ORCancellations
 * @package App\Models
 * @version April 1, 2022, 1:11 pm PST
 *
 * @property string $ORNumber
 * @property string $ORDate
 * @property string $From
 * @property string $ObjectId
 * @property string|\Carbon\Carbon $DateTimeFiled
 * @property string|\Carbon\Carbon $DateTimeApproved
 */
class ORCancellations extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_ORCancellations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ORNumber',
        'ORDate',
        'From',
        'ObjectId',
        'DateTimeFiled',
        'DateTimeApproved'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ORNumber' => 'string',
        'ORDate' => 'date',
        'From' => 'string',
        'ObjectId' => 'string',
        'DateTimeFiled' => 'datetime',
        'DateTimeApproved' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ORNumber' => 'nullable|string|max:255',
        'ORDate' => 'nullable',
        'From' => 'nullable|string|max:255',
        'ObjectId' => 'nullable|string|max:255',
        'DateTimeFiled' => 'nullable',
        'DateTimeApproved' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
