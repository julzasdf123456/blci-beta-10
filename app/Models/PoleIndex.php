<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PoleIndex
 * @package App\Models
 * @version September 22, 2021, 11:55 am PST
 *
 * @property string $NEACode
 * @property string $Type
 */
class PoleIndex extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_PoleIndex';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'NEACode',
        'Type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'NEACode' => 'string',
        'Type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'NEACode' => 'nullable|string|max:255',
        'Type' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
