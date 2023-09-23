<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TransformerIndex
 * @package App\Models
 * @version September 21, 2021, 9:21 am PST
 *
 * @property string $NEACode
 */
class TransformerIndex extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_TransformerIndex';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'NEACode',
        'LinkFuseCode',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'NEACode' => 'string',
        'LinkFuseCode' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'NEACode' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'LinkFuseCode' => 'string|nullable',
    ];

    
}
