<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceConnectionAccountTypes
 * @package App\Models
 * @version July 26, 2021, 6:20 am UTC
 *
 * @property string $AccountType
 * @property string $Description
 */
class ServiceConnectionAccountTypes extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'CRM_ServiceConnectionAccountTypes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;


    public $fillable = [
        'id',
        'AccountType',
        'Description',
        'Alias'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'AccountType' => 'string',
        'Description' => 'string',
        'Alias' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'required|string',
        'AccountType' => 'nullable|string|max:200',
        'Description' => 'nullable|string|max:1000',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Alias' => 'string'
    ];

    
}
