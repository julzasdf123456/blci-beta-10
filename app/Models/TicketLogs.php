<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TicketLogs
 * @package App\Models
 * @version November 10, 2021, 8:13 am PST
 *
 * @property string $TicketId
 * @property string $Log
 * @property string $LogDetails
 * @property string $LogType
 * @property string $UserId
 */
class TicketLogs extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_TicketLogs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'TicketId',
        'Log',
        'LogDetails',
        'LogType',
        'UserId'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'TicketId' => 'string',
        'Log' => 'string',
        'LogDetails' => 'string',
        'LogType' => 'string',
        'UserId' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'nullable|string',
        'TicketId' => 'nullable|string|max:50',
        'Log' => 'nullable|string|max:100',
        'LogDetails' => 'nullable|string|max:1500',
        'LogType' => 'nullable|string|max:50',
        'UserId' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
