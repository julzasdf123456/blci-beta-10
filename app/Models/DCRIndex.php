<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DCRIndex
 * @package App\Models
 * @version June 12, 2022, 10:57 am PST
 *
 * @property string $GLCode
 * @property string $NEACode
 * @property string $TableName
 * @property string $Columns
 */
class DCRIndex extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_DCRIndex';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'GLCode',
        'NEACode',
        'TableName',
        'Columns',
        'TownCode',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'GLCode' => 'string',
        'NEACode' => 'string',
        'TableName' => 'string',
        'Columns' => 'string',
        'TownCode' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'GLCode' => 'nullable|string|max:255',
        'NEACode' => 'nullable|string|max:255',
        'TableName' => 'nullable|string|max:255',
        'Columns' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'TownCode' => 'nullable|string',
    ];

    
}
