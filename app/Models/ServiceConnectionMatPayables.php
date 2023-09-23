<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnectionMatPayables
 * @package App\Models
 * @version August 17, 2021, 12:43 am UTC
 *
 * @property string $Material
 * @property string $Rate
 * @property string $Description
 * @property string $VatPercentage
 * @property string $Notes
 */
class ServiceConnectionMatPayables extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ServiceConnectionMaterialPayables';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Material',
        'Rate',
        'Description',
        'VatPercentage',
        'BuildingType',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Material' => 'string',
        'Rate' => 'string',
        'Description' => 'string',
        'VatPercentage' => 'string',
        'BuildingType' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|string',
        'Material' => 'nullable|string|max:500',
        'Rate' => 'nullable|string|max:50',
        'Description' => 'nullable|string|max:800',
        'VatPercentage' => 'nullable|string|max:50',
        'Notes' => 'nullable|string|max:1000',
        'BuildingType' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
