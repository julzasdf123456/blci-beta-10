<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SpanningIndex
 * @package App\Models
 * @version September 24, 2021, 2:41 pm PST
 *
 * @property string $NeaCode
 * @property string $Structure
 * @property string $Description
 * @property string $Size
 * @property string $Type
 * @property string $SpliceNeaCode
 */
class SpanningIndex extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_SpanningIndex';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'NeaCode',
        'Structure',
        'Description',
        'Size',
        'Type',
        'SpliceNeaCode'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'NeaCode' => 'string',
        'Structure' => 'string',
        'Description' => 'string',
        'Size' => 'string',
        'Type' => 'string',
        'SpliceNeaCode' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'nullable|string',
        'NeaCode' => 'nullable|string|max:255',
        'Structure' => 'nullable|string|max:255',
        'Description' => 'nullable|string|max:255',
        'Size' => 'nullable|string|max:255',
        'Type' => 'nullable|string|max:255',
        'SpliceNeaCode' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
