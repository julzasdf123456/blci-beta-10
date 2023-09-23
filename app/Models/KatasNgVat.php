<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class KatasNgVat
 * @package App\Models
 * @version August 28, 2022, 9:13 am PST
 *
 * @property string $id
 * @property string $AccountNumber
 * @property string $Balance
 * @property string $SeriesNo
 * @property string $Notes
 */
class KatasNgVat extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Billing_KatasNgVat';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'AccountNumber',
        'Balance',
        'SeriesNo',
        'Notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'AccountNumber' => 'string',
        'Balance' => 'string',
        'SeriesNo' => 'string',
        'Notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'AccountNumber' => 'nullable|string|max:255',
        'Balance' => 'nullable|string|max:255',
        'SeriesNo' => 'nullable|string|max:255',
        'Notes' => 'nullable|string|max:600',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
