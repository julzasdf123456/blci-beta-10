<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Items
 * @package App\Models
 * @version June 14, 2023, 8:02 am PST
 *
 * @property string $itm_code
 * @property integer $itm_no
 * @property string $itm_desc
 * @property string $itm_specs
 * @property string $itm_uom
 * @property integer $itm_aveqty
 * @property string $itm_cat
 * @property integer $itm_yr
 * @property string $itm_date
 * @property string $itm_time
 * @property string $itm_pcode
 */
class Items extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'tblitems';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "mysql";

    public $timestamps = false;

    public $fillable = [
        'itm_code',
        'itm_no',
        'itm_desc',
        'itm_specs',
        'itm_uom',
        'itm_aveqty',
        'itm_cat',
        'itm_yr',
        'itm_date',
        'itm_time',
        'itm_pcode'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'itm_code' => 'string',
        'itm_no' => 'integer',
        'itm_desc' => 'string',
        'itm_specs' => 'string',
        'itm_uom' => 'string',
        'itm_aveqty' => 'integer',
        'itm_cat' => 'string',
        'itm_yr' => 'integer',
        'itm_date' => 'string',
        'itm_time' => 'string',
        'itm_pcode' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'itm_code' => 'nullable|string|max:12',
        'itm_no' => 'required|integer',
        'itm_desc' => 'nullable|string|max:255',
        'itm_specs' => 'nullable|string|max:255',
        'itm_uom' => 'nullable|string|max:10',
        'itm_aveqty' => 'required|integer',
        'itm_cat' => 'nullable|string|max:5',
        'itm_yr' => 'required|integer',
        'itm_date' => 'nullable|string|max:25',
        'itm_time' => 'nullable|string|max:12',
        'itm_pcode' => 'nullable|string|max:5'
    ];

    
}
