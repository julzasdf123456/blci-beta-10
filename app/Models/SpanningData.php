<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SpanningData
 * @package App\Models
 * @version September 27, 2021, 8:20 am PST
 *
 * @property string $ServiceConnectionId
 * @property string $PrimarySpan
 * @property string $PrimarySize
 * @property string $PrimaryType
 * @property string $NeutralSpan
 * @property string $NeutralSize
 * @property string $NeutralType
 * @property string $SecondarySpan
 * @property string $SecondarySize
 * @property string $SecondaryType
 * @property string $SDWSpan
 * @property string $SDWSize
 * @property string $SDWType
 */
class SpanningData extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_SpanninData';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'ServiceConnectionId',
        'PrimarySpan',
        'PrimarySize',
        'PrimaryType',
        'NeutralSpan',
        'NeutralSize',
        'NeutralType',
        'SecondarySpan',
        'SecondarySize',
        'SecondaryType',
        'SDWSpan',
        'SDWSize',
        'SDWType'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'ServiceConnectionId' => 'string',
        'PrimarySpan' => 'string',
        'PrimarySize' => 'string',
        'PrimaryType' => 'string',
        'NeutralSpan' => 'string',
        'NeutralSize' => 'string',
        'NeutralType' => 'string',
        'SecondarySpan' => 'string',
        'SecondarySize' => 'string',
        'SecondaryType' => 'string',
        'SDWSpan' => 'string',
        'SDWSize' => 'string',
        'SDWType' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'ServiceConnectionId' => 'nullable|string|max:255',
        'PrimarySpan' => 'nullable|string|max:15',
        'PrimarySize' => 'nullable|string|max:10',
        'PrimaryType' => 'nullable|string|max:15',
        'NeutralSpan' => 'nullable|string|max:15',
        'NeutralSize' => 'nullable|string|max:10',
        'NeutralType' => 'nullable|string|max:15',
        'SecondarySpan' => 'nullable|string|max:15',
        'SecondarySize' => 'nullable|string|max:10',
        'SecondaryType' => 'nullable|string|max:15',
        'SDWSpan' => 'nullable|string|max:15',
        'SDWSize' => 'nullable|string|max:10',
        'SDWType' => 'nullable|string|max:15',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
