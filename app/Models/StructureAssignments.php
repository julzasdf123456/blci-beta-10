<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class StructureAssignments
 * @package App\Models
 * @version September 17, 2021, 9:58 am PST
 *
 * @property string $ServiceConnectionId
 * @property string $StructureId
 */
class StructureAssignments extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_StructureAssignments';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'StructureId',
        'Quantity',
        'Type',
        'ConAssGrouping'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'StructureId' => 'string',
        'Quantity' => 'string',
        'Type' => 'string',
        'ConAssGrouping' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'nullable|string',
        'ServiceConnectionId' => 'nullable|string|max:255',
        'StructureId' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Quantity' => 'nullable|string',
        'Type' => 'nullable|string',
        'ConAssGrouping' => 'string|nullable',
    ];

    
}
