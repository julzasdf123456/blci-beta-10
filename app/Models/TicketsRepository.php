<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TicketsRepository
 * @package App\Models
 * @version October 19, 2021, 11:58 am PST
 *
 * @property string $Name
 * @property string $Description
 * @property string $ParentTicket
 * @property string $Type
 * @property string $KPSCategory
 * @property string $KPSIssue
 */
class TicketsRepository extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_TicketsRepository';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Name',
        'Description',
        'ParentTicket',
        'Type',
        'KPSCategory',
        'KPSIssue'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Name' => 'string',
        'Description' => 'string',
        'ParentTicket' => 'string',
        'Type' => 'string',
        'KPSCategory' => 'string',
        'KPSIssue' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'Name' => 'nullable|string|max:600',
        'Description' => 'nullable|string|max:1000',
        'ParentTicket' => 'nullable|string|max:255',
        'Type' => 'nullable|string|max:255',
        'KPSCategory' => 'nullable|string|max:255',
        'KPSIssue' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
