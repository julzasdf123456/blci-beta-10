<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Barangays
 * @package App\Models
 * @version July 16, 2021, 9:13 am UTC
 *
 * @property string $Barangay
 * @property string $TownId
 * @property string $Notes
 */
class Barangays extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_Barangays';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'Barangay',
        'TownId',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'Barangay' => 'string',
        'TownId' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'Barangay' => 'nullable|string|max:300',
        'TownId' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
