<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Towns
 * @package App\Models
 * @version July 16, 2021, 9:12 am UTC
 *
 * @property string $Town
 * @property string $District
 * @property string $Station
 */
class Towns extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_Towns';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Town',
        'District',
        'Station'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Town' => 'string',
        'District' => 'string',
        'Station' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'Town' => 'nullable|string|max:300',
        'District' => 'nullable|string|max:300',
        'Station' => 'nullable|string|max:300',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
