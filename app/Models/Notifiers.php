<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Notifiers
 * @package App\Models
 * @version April 1, 2022, 11:36 am PST
 *
 * @property string $Notification
 * @property string $From
 * @property string $To
 * @property string $Status
 * @property string $Intent
 * @property string $IntentLink
 * @property string $ObjectId
 */
class Notifiers extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Notifiers';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Notification',
        'From',
        'To',
        'Status',
        'Intent',
        'IntentLink',
        'ObjectId'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Notification' => 'string',
        'From' => 'string',
        'To' => 'string',
        'Status' => 'string',
        'Intent' => 'string',
        'IntentLink' => 'string',
        'ObjectId' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'Notification' => 'nullable|string|max:3000',
        'From' => 'nullable|string|max:255',
        'To' => 'nullable|string|max:255',
        'Status' => 'nullable|string|max:255',
        'Intent' => 'nullable|string|max:600',
        'IntentLink' => 'nullable|string|max:800',
        'ObjectId' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];
}
