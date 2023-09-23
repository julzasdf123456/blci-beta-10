<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class KatasNgVatTotal
 * @package App\Models
 * @version September 4, 2022, 8:01 am PST
 *
 * @property string $Balance
 * @property string $SeriesNo
 * @property string $Description
 * @property string $Year
 * @property string $UserId
 * @property string $Notes
 */
class KatasNgVatTotal extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_KatasNgVatTotal';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'Balance',
        'SeriesNo',
        'Description',
        'Year',
        'UserId',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'Balance' => 'string',
        'SeriesNo' => 'string',
        'Description' => 'string',
        'Year' => 'string',
        'UserId' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'Balance' => 'nullable|string|max:255',
        'SeriesNo' => 'nullable|string|max:255',
        'Description' => 'nullable|string|max:500',
        'Year' => 'nullable|string|max:255',
        'UserId' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:500',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
